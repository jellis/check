<?php

namespace Jellis\Check\Providers;

use Blade;
use Jellis\Check\Check;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class CheckServiceProvider extends ServiceProvider
{

    public function boot()
    {
        
    }

    public function register()
    {
        $this->app->singleton(Check::class, function($app) {
            return $app['auth']->guest() || !method_exists($app['auth']->user(), 'getRole')
                ? new Check
                : new Check($app['auth']->user()->getRole());
        });
    }

}