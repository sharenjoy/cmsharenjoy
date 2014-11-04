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
        $app = $this->app;

        // The Category Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Modules\Category\CategoryInterface', function($app)
        {
            return new Category\CategoryRepository(
                new \Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category,
                new Category\CategoryValidator
            );
        });

        // The Tag Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Modules\Tag\TagInterface', function($app)
        {
            return new Tag\TagRepository(new Tag\Tag, new Tag\TagValidator);
        });

    }

}