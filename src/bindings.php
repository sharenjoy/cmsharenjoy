<?php

// The Posts Bindings
App::bind('Sharenjoy\Cmsharenjoy\Post\PostInterface', function($app)
{
    return new Sharenjoy\Cmsharenjoy\Post\PostRepository(
        new Sharenjoy\Cmsharenjoy\Post\Post,
        App::make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface'),
        new Sharenjoy\Cmsharenjoy\Post\PostValidator($app['validator'])
    );
});

// The Tags Bindings
App::bind('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Repo\Tag\EloquentTag(
        new Sharenjoy\Cmsharenjoy\Repo\Tag\Tag
    );
});

// The Users Bindings
App::bind('Sharenjoy\Cmsharenjoy\Accounts\UserInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Accounts\UserRepository(
        new Sharenjoy\Cmsharenjoy\Accounts\User
    );
});

// The Settings Bindings
App::bind('Sharenjoy\Cmsharenjoy\Settings\SettingsInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Settings\SettingsRepository(
        new Sharenjoy\Cmsharenjoy\Settings\Settings
    );
});

// The Uploads Bindings
App::bind('Sharenjoy\Cmsharenjoy\Uploads\UploadsInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Uploads\UploadsRepository(
        new Sharenjoy\Cmsharenjoy\Uploads\Uploads
    );
});
