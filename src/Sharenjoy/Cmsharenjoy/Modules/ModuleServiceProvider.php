<?php namespace Sharenjoy\Cmsharenjoy\Modules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // The Post Binding
        $this->app->bindShared('Sharenjoy\Cmsharenjoy\Modules\Post\PostInterface', function()
        {
            return new Post\PostRepository(new Post\Post, new Post\PostValidator);
        });

        // The Category Binding
        $this->app->bindShared('Sharenjoy\Cmsharenjoy\Modules\Category\CategoryInterface', function()
        {
            return new Category\CategoryRepository(
                new \Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category,
                new Category\CategoryValidator);
        });

        // The Tag Binding
        $this->app->bindShared('Sharenjoy\Cmsharenjoy\Modules\Tag\TagInterface', function()
        {
            return new Tag\TagRepository(new Tag\Tag, new Tag\TagValidator);
        });

    }

}