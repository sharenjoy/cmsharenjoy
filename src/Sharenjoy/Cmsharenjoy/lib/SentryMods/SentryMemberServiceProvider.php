<?php namespace Sharenjoy\Cmsharenjoy\Lib\SentryMods;

use Cartalyst\Sentry\Cookies\IlluminateCookie;
use Cartalyst\Sentry\Groups\Eloquent\Provider as GroupProvider;
use Cartalyst\Sentry\Hashing\BcryptHasher;
use Cartalyst\Sentry\Hashing\NativeHasher;
use Cartalyst\Sentry\Hashing\Sha256Hasher;
use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Sessions\IlluminateSession;
use Cartalyst\Sentry\Throttling\Eloquent\Provider as ThrottleProvider;
use Cartalyst\Sentry\Users\Eloquent\Provider as UserProvider;
use Illuminate\Support\ServiceProvider;

class SentryMemberServiceProvider extends ServiceProvider {

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(){
        $this->observeEvents();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
        $this->registerHasher();
        $this->registerUserProvider();
        $this->registerGroupProvider();
        $this->registerThrottleProvider();
        $this->registerSession();
        $this->registerCookie();
        $this->registerSentry();
    }

    /**
     * Register the hasher used by Sentry.
     *
     * @return void
     */
    protected function registerHasher(){
        $this->app['sentry.member.hasher'] = $this->app->share(function($app){
            $hasher = $app['config']['cartalyst/sentry::config.member.hasher'];

            switch ($hasher){
                case 'native':
                    return new NativeHasher;
                    break;

                case 'bcrypt':
                    return new BcryptHasher;
                    break;

                case 'sha256':
                    return new Sha256Hasher;
                    break;
            }

            throw new \InvalidArgumentException("Invalid hasher [$hasher] chosen for Sentry.");
        });
    }

    /**
     * Register the user provider used by Sentry.
     *
     * @return void
     */
    protected function registerUserProvider(){
        $this->app['sentry.member.user'] = $this->app->share(function($app){
            $model = $app['config']['cartalyst/sentry::config.member.users.model'];

            // We will never be accessing a user in Sentry without accessing
            // the user provider first. So, we can lazily setup our user
            // model's login attribute here. If you are manually using the
            // attribute outside of Sentry, you will need to ensure you are
            // overriding at runtime.
            if (method_exists($model, 'setLoginAttribute')){
                $loginAttribute = $app['config']['cartalyst/sentry::sentry.users.login_attribute'];
                forward_static_call_array(
                    array($model, 'setLoginAttribute'),
                    array($loginAttribute)
                );
            }

            return new UserProvider($app['sentry.member.hasher'], $model);
        });
    }

    /**
     * Register the group provider used by Sentry.
     *
     * @return void
     */
    protected function registerGroupProvider(){
        $this->app['sentry.member.group'] = $this->app->share(function($app){
            $model = $app['config']['cartalyst/sentry::config.member.groups.model'];
            return new GroupProvider($model);
        });
    }

    /**
     * Register the throttle provider used by Sentry.
     *
     * @return void
     */
    protected function registerThrottleProvider(){
        $this->app['sentry.member.throttle'] = $this->app->share(function($app){
            $model = $app['config']['cartalyst/sentry::sentry.throttling.model'];

            $throttleProvider = new ThrottleProvider($app['sentry.member.user'], $model);

            if ($app['config']['cartalyst/sentry::config.member.throttling.enabled'] === false){
                $throttleProvider->disable();
            }
            if (method_exists($model, 'setAttemptLimit')){
                $attemptLimit = $app['config']['cartalyst/sentry::config.member.throttling.attempt_limit'];

                forward_static_call_array(
                    array($model, 'setAttemptLimit'),
                    array($attemptLimit)
                );
            }
            if (method_exists($model, 'setSuspensionTime')){
                $suspensionTime = $app['config']['cartalyst/sentry::config.member.throttling.suspension_time'];
                forward_static_call_array(
                    array($model, 'setSuspensionTime'),
                    array($suspensionTime)
                );
            }
            return $throttleProvider;
        });
    }

    /**
     * Register the session driver used by Sentry.
     *
     * @return void
     */
    protected function registerSession()
    {
        $this->app['sentry.member.session'] = $this->app->share(function($app)
        {
            return new IlluminateSession($app['session']);
        });
    }

    /**
     * Register the cookie driver used by Sentry.
     *
     * @return void
     */
    protected function registerCookie()
    {
        $this->app['sentry.member.cookie'] = $this->app->share(function($app)
        {
            return new IlluminateCookie($app['cookie']);
        });
    }

    /**
     * Takes all the components of Sentry and glues them
     * together to create Sentry.
     *
     * @return void
     */
    protected function registerSentry()
    {
        $this->app['sentry.member'] = $this->app->share(function($app)
        {
            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['sentry.member.loaded'] = true;

            return new Sentry(
                $app['sentry.member.user'],
                $app['sentry.member.group'],
                $app['sentry.member.throttle'],
                $app['sentry.member.session'],
                $app['sentry.member.cookie'],
                $app['request']->getClientIp()
            );
        });
    }

    /**
     * Sets up event observations required by Sentry.
     *
     * @return void
     */
    protected function observeEvents()
    {
        // Set the cookie after the app runs
        $app = $this->app;
        $this->app->after(function($request, $response) use ($app)
        {
            if (isset($app['sentry.member.loaded']) and $app['sentry.member.loaded'] == true and ($cookie = $app['sentry.member.cookie']->getCookie()))
            {
                $response->headers->setCookie($cookie);
            }
        });
    }

}