<?php namespace Sharenjoy\Cmsharenjoy\Service\Message;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MessageServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}

    public function boot()
    {
        $this->app->bindShared(
            'Illuminate\Support\Contracts\MessageProviderInterface',
            function()
            {
                return new FlashMessageBag(
                    $this->app->make('session.store')
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