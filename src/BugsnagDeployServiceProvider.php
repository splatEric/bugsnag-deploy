<?php

namespace Camc\BugsnagDeploy;

use Illuminate\Support\ServiceProvider;

class BugsnagDeployServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Camc\BugsnagDeploy\Console\Commands\BugsnagNotify'
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
