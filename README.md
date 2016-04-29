# bugsnag-deploy
Simple package to provide Laravel 5 artisan command to trigger deployment notifications to bugsnag.

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

## About

The aim of this package is to provide a simple integration tool with the [Bugsnag Deploy Api](https://bugsnag.com/docs/deploy-tracking-api), pulling information from the Laravel configuration/environment as much as possible to ensure accurate data is passed to the API.

It's very niche, and probably only useful to a handful of people. A lot of the motivation for developing it was actually for the experience of putting together Laravel a package.

## Usage

This package provides a single artisan command:

`php artisan bugsnag:notify --help`

Will provide details of current options

### TODO

* Some tests.
* Configuration of which API variables to pass (to prevent unnecessary defaults).
* Support for branch and repository attributes.
