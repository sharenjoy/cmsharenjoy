<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FormakerServiceProvider extends ServiceProvider {

    public function register() {}

    public function boot()
    {
        $config = $this->app['config'];

        $this->registerFormakerTransport($config);

        // Adding an Aliac in app/config/app.php
        AliasLoader::getInstance()->alias('Formaker', 'Sharenjoy\Cmsharenjoy\Service\Formaker\Facades\Formaker');
    }

    /**
     * Register the Formaker Transport instance.
     *
     * @param  array  $config
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function registerFormakerTransport($config)
    {
        $driver = $config->get('cmsharenjoy::formaker.driver-back');
        
        $this->app->bind(
            'Sharenjoy\Cmsharenjoy\Service\Formaker\FormakerInterface',
            function() use ($driver)
            {
                switch ($driver)
                {
                    case 'bootstrap-v3':
                        return new BootstrapBackend();

                    case 'bootstrap-v4':
                        return new BootstrapFrontend();                   
                    
                    default:
                        throw new \InvalidArgumentException('Invalid formaker driver.');
                }
            }
        );
    }

    public function provides()
    {
        return array();
    }
}
