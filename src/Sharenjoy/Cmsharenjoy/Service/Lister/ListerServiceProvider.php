<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ListerServiceProvider extends ServiceProvider {

    public function register() {}

    public function boot()
    {
        $this->registerListerTransport();

        // Adding an Aliac in app/config/app.php
        AliasLoader::getInstance()->alias('Lister', 'Sharenjoy\Cmsharenjoy\Service\Lister\Facades\Lister');
    }

    /**
     * Register the Lister Transport instance.
     *
     * @param  array  $config
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function registerListerTransport()
    {
        $config = $this->app['config']->get('cmsharenjoy::lister');

        $driver = $config['driver'];
        
        $this->app->bindShared(
            'Sharenjoy\Cmsharenjoy\Service\Lister\ListerInterface',
            function() use ($driver)
            {
                switch ($driver)
                {
                    case 'default':
                        return new Lister();
                        break;
                    
                    default:
                        throw new \InvalidArgumentException();
                        break;
                }
            }
        );
    }

    public function provides()
    {
        return array();
    }
}
