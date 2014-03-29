<?php namespace Sharenjoy\Cmsharenjoy\Repo;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        // The Posts Bindings
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Post\PostInterface', function($app)
        {
            return new Post\PostRepository(
                new Post\Post,
                $app->make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface'),
                new Post\PostValidator($app['validator'])
            );
        });

        // The Tags Bindings
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface', function($app)
        {
            return new Tag\TagRepository(
                new Tag\Tag,
                new Tag\TagValidator($app['validator'])
            );
        });
    }

}