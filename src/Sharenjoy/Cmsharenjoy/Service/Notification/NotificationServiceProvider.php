<?php namespace Sharenjoy\Cmsharenjoy\Service\Notification;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class NotificationServiceProvider extends ServiceProvider {

    public function register() {}

    public function boot()
    {
        $config = $this->app['config'];

        $this->registerTransport($config);

        // Adding an Aliac in app/config/app.php
        AliasLoader::getInstance()->alias('Notify', 'Sharenjoy\Cmsharenjoy\Service\Notification\Facades\Notification');
    }

    /**
     * Register the Notification Transport instance.
     *
     * @param  array  $config
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function registerTransport($config)
    {
        $driver = $config->get('cmsharenjoy::notification.driver');
        
        $this->app->bind(
            'Sharenjoy\Cmsharenjoy\Service\Notification\NotificationInterface',
            function() use ($driver, $config)
            {
                switch ($driver)
                {
                    case 'twilio':
                        return new TwilioSmsNotification();
                    
                    default:
                        throw new \InvalidArgumentException('Invalid notification driver.');
                }
            }
        );
    }

    public function provides()
    {
        return array();
    }

}