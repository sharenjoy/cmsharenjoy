<?php namespace Sharenjoy\Cmsharenjoy\Service\Message;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App;

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
            function()
            {
                return new FlashMessageBag(
                    App::make('session.store')
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