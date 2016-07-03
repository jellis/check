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
        // Gives us the @check($action, $model) directive in templates
        Blade::directive('check', function($action, Model $model = null) {
            return "<?php if (Check::can($action, $model)) : ?>";
        });

        Blade::directive('endcheck', function(){
            return "<?php endif ?>";
        });
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