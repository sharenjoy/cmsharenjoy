<?php namespace Sharenjoy\Cmsharenjoy\Service\Notification;

use Services_Twilio;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app['sharenjoy.notifier'] = $app->share(function() use ($app)
        {
            $config = $app['config'];

            $twilio = new Services_Twilio(
                $config->get('cmsharenjoy::twilio.account_id'),
                $config->get('cmsharenjoy::twilio.auth_token')
            );

            $notifier = new SmsNotifier($twilio);

            $notifier->from($config['cmsharenjoy::twilio.from']);

            return $notifier;
        });
    }

    public function provides()
    {
        return array('sharenjoy.notifier');
    }

}