<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Session;

class FormakerServiceProvider extends ServiceProvider {

    public function register() {}

    public function boot()
    {
        $this->registerFormakerTransport();

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
    protected function registerFormakerTransport()
    {
        $end = Session::get('sharenjoy.whichEnd');

        $config = $this->app['config']->get('cmsharenjoy::formaker');

        if ($end == 'frontEnd')
        {
            $driver = $config['driver-front'];
        }
        elseif ($end == 'backEnd')
        {
            $driver = $config['driver-back'];
        }
        
        $this->app->bindShared(
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
