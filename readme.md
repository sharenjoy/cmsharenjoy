# This is cmsharenjoy.
--------------------------------------

This is cmsharenjoy.

### Composer Require
<!-- Nice and simple -->

    "sharenjoy/cmsharenjoy": "dev-develop"

### Adding the service provider to app.php
<!-- Add this string to your array of providers in app/config/app.php -->

    'Barryvdh\Debugbar\ServiceProvider',
    'Cartalyst\Sentry\SentryServiceProvider',
    'Sharenjoy\Cmsharenjoy\CmsharenjoyServiceProvider',
    'Sharenjoy\Cmsharenjoy\Repo\RepoServiceProvider',
    'Sharenjoy\Cmsharenjoy\Exception\ExceptionServiceProvider',
    'Sharenjoy\Cmsharenjoy\Service\Message\MessageServiceProvider',
    'Sharenjoy\Cmsharenjoy\Service\Notification\NotificationServiceProvider',
    'Teepluss\Theme\ThemeServiceProvider',
    'Sharenjoy\Cmsharenjoy\Formaker\FormakerServiceProvider',
    'Sharenjoy\Cmsharenjoy\Service\Categorize\CategorizeServiceProvider',
    'Intervention\Image\ImageServiceProvider',
    'Moltin\Cart\CartServiceProvider',

### Adding the alices to app.php

    'Debugbar'        => 'Barryvdh\Debugbar\Facade',
    'Theme'           => 'Teepluss\Theme\Facades\Theme',
    'Sentry'          => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
    'Image'           => 'Intervention\Image\Facades\Image',
    'Cart'            => 'Moltin\Cart\Facade',

### Publishing the configuration(Optional)
<!-- Publish the configurations for this package in order to change them to your liking: -->

    php artisan config:publish sharenjoy/cmsharenjoy

### Publishing the assets(Optional)
<!-- You need assets bro! -->

    php artisan asset:publish sharenjoy/cmsharenjoy

### Publishing the Debugbar assets and config

    php artisan debugbar:publish
    php artisan config:publish barryvdh/laravel-debugbar

### Publishing the Sentry config and migrate database

    php artisan migrate --package=cartalyst/sentry
    php artisan config:publish cartalyst/sentry

>Add a `type` and `sort` column to users table
>And distory email column unique

### Modify the config of Sentry for new User model that extends Sentry User model

    'users' => array(
        
        // Change to the model
        'model' => '\Sharenjoy\Cmsharenjoy\User\User',

    ),

### Modify Sentry user model line 302 don't thrown exception
    
    // Vendor/Cartalyst/Sentry/src/Cartalyst/Sentry/Users/Eloquent/User

    // Check if the user already exists
    $query = $this->newQuery();
    $persistedUser = $query->where($this->getLoginName(), '=', $login)->first();

    if ($persistedUser and $persistedUser->getId() != $this->getId())
    {
        // throw new UserExistsException("A user already exists with login [$login], logins must be unique for users.");
    }

### Migrating and seeding the database
<!-- Seed the database, this pretty much just seeds an example user and settings. Migration is pretty simple, ensure your database config is setup and run this: -->

    php artisan migrate --package="sharenjoy/cmsharenjoy"
    php artisan db:seed --class="Sharenjoy\Cmsharenjoy\Seeds\DatabaseSeeder"

### Publishing the Laravel4-theme assets and create a theme

    php artisan config:publish teepluss/theme
    php artisan theme:create admin
