<?php

namespace Camc\BugsnagDeploy\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class Bugsnagnotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bugsnag:notify
                            {--revision= : specify the revision manually for deploy notification}
                            {--appVersion= : specify the app version manually for deploy notification}
                            {--configAppVersion= : override the default config attribute to retrieve the app version}
                            {--endpoint= : force the endpoint to a specific URL}
                            {--dry-run : output the data to be sent to BugSnag rather than sending it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify bugsnag of a deployment.';

    protected static $DEFAULT_VERSION = '0.0.1-alpha';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Simple error handling wrapper for returning error codes from the handle function
     *
     * @param $message
     * @param int $error_code
     * @return int
     */
    protected function fail($message, $error_code = 1)
    {
        $this->error($message);
        return $error_code;
    }

    /**
     * Define the URL to POST notification for deployment
     * 
     * @return array|string
     */
    protected function getDeployEndpoint()
    {
        if (!$endpoint = $this->option('endpoint')) {
            $endpoint = Config::get('bugsnag.endpoint') ?: \Bugsnag_Configuration::$DEFAULT_ENDPOINT;
            $endpoint .= '/deploy';
        }

        return $endpoint;
    }

    /**
     * Wrapper for posting JSON data to a URL
     *
     * @param $url
     * @param $json
     * @return int|void
     */
    protected function postNotification($url, $json)
    {
        $json_string = json_encode($json);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_string))
        );

        $result = curl_exec($ch);

        if (!$result)
            return $this->error("POST Failure: " . curl_errno($ch), 2);

        $this->comment($result);

        return 0;
    }

    /**
     * Simple abstraction to calculate the current app version for notification
     *
     * @return array|string
     */
    protected function getAppVersion()
    {
        if ($this->option('appVersion'))
            return $this->option('appVersion');

        $config = $this->option('configAppVersion') ?: 'app.version';

        return Config::get($config, self::$DEFAULT_VERSION);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$api_key = Config::get('bugsnag.api_key'))
            return $this->fail('bugnsag.api_key not set');

        $data = [
            'apiKey' => $api_key,
            'releaseStage' => Config::get('app.env')
        ];

        if (!$revision = $this->option('revision'))
            $revision = trim(exec('git rev-parse HEAD'));

        $data['revision'] = $revision;
        $data['appVersion'] = $this->getAppVersion();

        $endpoint = $this->getDeployEndpoint();

        if ($this->option('dry-run')) {
            $this->line('POSTing to: ' . $endpoint);
            $this->line(json_encode($data));
        }
        else {
            return $this->postNotification($endpoint, $data);
        }
    }
}
