# This is a cms.
--------------------------------------

### Composer Require
<!-- Nice and simple -->

    "sharenjoy/cmsharenjoy": "1.*"

### Linking The Service Provider To Your Installation
<!-- Add this string to your array of providers in app/config/app.php -->

    Sharenjoy\Cmsharenjoy\CmsharenjoyServiceProvider

### Publishing The Configuration
<!-- Publish the configurations for this package in order to change them to your liking: -->

    php artisan config:publish sharenjoy/cmsharenjoy

### Publishing The Assets
<!-- You need assets bro! -->

    php artisan asset:publish sharenjoy/cmsharenjoy

### Migrating and Seeding The Database
<!-- Seed the database, this pretty much just seeds an example user and settings. Migration is pretty simple, ensure your database config is setup and run this: -->

    php artisan migrate --package="sharenjoy/cmsharenjoy"
    php artisan db:seed --class="Sharenjoy\\Cmsharenjoy\\Seeds\\DatabaseSeeder"
