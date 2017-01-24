<?php

namespace Jellis\Check\Providers;

use Jellis\Check\Check;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class CheckServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Gives us the @check($action, $model) directive in templates
        Blade::directive('check', function($action, Model $model = null) {
            return !!$model ? "<?php if (Check::can($action, $model)) : ?>" : "<?php if (Check::can($action)) : ?>";
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