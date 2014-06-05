<?php namespace Sharenjoy\Cmsharenjoy\Service\Poster;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class PosterServiceProvider extends ServiceProvider {

    public function register() {}

    public function boot()
    {
        $config = $this->app['config'];

        $this->registerPosterTransport($config);

        // Adding an Aliac in app/config/app.php
        AliasLoader::getInstance()->alias('Poster', 'Sharenjoy\Cmsharenjoy\Service\Poster\Facades\Poster');
    }

    /**
     * Register the Poster Transport instance.
     *
     * @param  array  $config
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function registerPosterTransport($config)
    {
        $driver = $config->get('cmsharenjoy::poster.driver');
        
        $this->app->bind(
            'Sharenjoy\Cmsharenjoy\Service\Poster\PosterInterface',
            function() use ($driver)
            {
                switch ($driver)
                {
                    case 'eloquent':
                        return new PosterEloquent();
                    
                    default:
                        throw new \InvalidArgumentException('Invalid poster driver.');
                }
            }
        );
    }

    public function provides()
    {
        return array();
    }
}
