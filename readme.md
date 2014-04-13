# This is cmsharenjoy.
--------------------------------------

This is cmsharenjoy.

### Composer Require
<!-- Nice and simple -->

    "sharenjoy/cmsharenjoy": "dev-develop"

### Adding the service provider to app.php
<!-- Add this string to your array of providers in app/config/app.php -->

    'Barryvdh\Debugbar\ServiceProvider',
    'Sharenjoy\Cmsharenjoy\CmsharenjoyServiceProvider',
    'Sharenjoy\Cmsharenjoy\Repo\RepoServiceProvider',
    'Sharenjoy\Cmsharenjoy\Exception\ExceptionServiceProvider',
    'Sharenjoy\Cmsharenjoy\Service\Message\MessageServiceProvider',
    'Sharenjoy\Cmsharenjoy\Service\Notification\NotificationServiceProvider',
    'Teepluss\Theme\ThemeServiceProvider',
    'Sharenjoy\Cmsharenjoy\Formaker\FormakerServiceProvider',
    'Sharenjoy\Cmsharenjoy\Service\Categorize\CategorizeServiceProvider',
    'Zizaco\Confide\ConfideServiceProvider',

### Adding the alices to app.php

    'Debugbar'        => 'Barryvdh\Debugbar\Facade',
    'Theme'           => 'Teepluss\Theme\Facades\Theme',
    'Confide'         => 'Zizaco\Confide\ConfideFacade'

### Publishing the configuration(Optional)
<!-- Publish the configurations for this package in order to change them to your liking: -->

    php artisan config:publish sharenjoy/cmsharenjoy

### Publishing the assets(Optional)
<!-- You need assets bro! -->

    php artisan asset:publish sharenjoy/cmsharenjoy

### Migrating and seeding the database
<!-- Seed the database, this pretty much just seeds an example user and settings. Migration is pretty simple, ensure your database config is setup and run this: -->

    php artisan migrate --package="sharenjoy/cmsharenjoy"
    php artisan db:seed --class="Sharenjoy\Cmsharenjoy\Seeds\DatabaseSeeder"

### Publishing the assets and config of Debugbar

    php artisan debugbar:publish
    php artisan config:publish barryvdh/laravel-debugbar

### Publishing the assets and create a theme of Laravel4-theme

    php artisan config:publish teepluss/theme
    php artisan theme:create admin
