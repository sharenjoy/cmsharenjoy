<?php namespace Sharenjoy\Cmsharenjoy\Exception;

use Illuminate\Support\ServiceProvider;

class ExceptionServiceProvider extends ServiceProvider {

    public function register()
    {
        $app = $this->app;

        $app['sharenjoy.exception'] = $app->share(function($app)
        {
            // return new NotifyHandler($app['sharenjoy.notifier']);
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        $app->error(function(SharenjoyException $e) use ($app)
        {
            // $app['sharenjoy.exception']->handle($e);
        });
    }
}