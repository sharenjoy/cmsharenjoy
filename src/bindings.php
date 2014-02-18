<?php

// The Posts Bindings
App::bind('Sharenjoy\Cmsharenjoy\Posts\PostsInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Posts\PostsRepository(new Sharenjoy\Cmsharenjoy\Posts\Posts);
});

// The Users Bindings
App::bind('Sharenjoy\Cmsharenjoy\Accounts\UserInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Accounts\UserRepository(new Sharenjoy\Cmsharenjoy\Accounts\User);
});

// The Settings Bindings
App::bind('Sharenjoy\Cmsharenjoy\Settings\SettingsInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Settings\SettingsRepository(new Sharenjoy\Cmsharenjoy\Settings\Settings);
});

// The Blocks Bindings
App::bind('Sharenjoy\Cmsharenjoy\Blocks\BlocksInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Blocks\BlocksRepository(new Sharenjoy\Cmsharenjoy\Blocks\Blocks);
});

// The Tags Bindings
App::bind('Sharenjoy\Cmsharenjoy\Tags\TagsInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Tags\TagsRepository(new Sharenjoy\Cmsharenjoy\Tags\Tags);
});

// The Uploads Bindings
App::bind('Sharenjoy\Cmsharenjoy\Uploads\UploadsInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Uploads\UploadsRepository(new Sharenjoy\Cmsharenjoy\Uploads\Uploads);
});

// The Galleries Bindings
App::bind('Sharenjoy\Cmsharenjoy\Galleries\GalleriesInterface', function()
{
    return new Sharenjoy\Cmsharenjoy\Galleries\GalleriesRepository(new Sharenjoy\Cmsharenjoy\Galleries\Galleries);
});
