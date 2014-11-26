<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Session;

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
        $end = Session::get('sharenjoy.whichEnd');

        if ($end == 'frontEnd')
        {
            $driver = $config->get('cmsharenjoy::formaker.driver-front');
        }
        elseif ($end == 'backEnd')
        {
            $driver = $config->get('cmsharenjoy::formaker.driver-back');
        }
        
        $this->app->bind(
            'Sharenjoy\Cmsharenjoy\Service\Formaker\FormakerInterface',
            function() use ($driver)
            {
                switch ($driver)
                {
                    case 'TwitterBootstrapV3':
                        return new TwitterBootstrapV3();
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
