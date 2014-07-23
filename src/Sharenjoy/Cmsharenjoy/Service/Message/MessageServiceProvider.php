<?php namespace Sharenjoy\Cmsharenjoy\Service\Message;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MessageServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->bind(
            'Illuminate\Support\Contracts\MessageProviderInterface',
            function() use ($app)
            {
                return new FlashMessageBag(
                    $app->make('session.store')
                );
            }
        );

        // Alias
        AliasLoader::getInstance()->alias('Message', 'Sharenjoy\Cmsharenjoy\Service\Message\Facades\Message');
    }

    public function provides()
    {
        return array();
    }

}