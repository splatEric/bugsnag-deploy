# bugsnag-deploy
Simple package to provide Laravel 5 artisan command to trigger deployment notifications to bugsnag

## Requirements
This package is only of any use to users of [Bugsnag](http://www.bugsnag.com). Instructions for Laravel Bugsnag integration are available through their [github package](https://github.com/bugsnag/bugsnag-laravel).

## Installation

Use composer to install this package:

`composer require camc/bugsnag-deploy`

Then add the Service Provider to your app configuration

```
'providers' => [
  ...
  Camc\BugsnagDeploy\BugsnagDeployServiceProvider::class,
  ...
]
```


## Usage

This package provides a single artisan command:

`php artisan bugsnag:notify --help`

Will provide details of current options
