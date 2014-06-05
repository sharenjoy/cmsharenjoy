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

        // The Post Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Post\PostInterface', function($app)
        {
            return new Post\PostRepository(
                new Post\Post,
                $app->make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface'),
                new Post\PostValidator($app['validator'])
            );
        });

        // The Category Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Category\CategoryInterface', function($app)
        {
            return new Category\CategoryRepository(
                new \Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category,
                new Category\CategoryValidator($app['validator'])
            );
        });

        // The Tag Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface', function($app)
        {
            return new Tag\TagRepository(
                new Tag\Tag,
                new Tag\TagValidator($app['validator'])
            );
        });

        // The Member Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Member\MemberInterface', function($app)
        {
            return new Member\MemberRepository(
                new Member\Member,
                new Member\MemberValidator($app['validator'])
            );
        });

        // The Report Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Report\ReportInterface', function($app)
        {
            return new Report\ReportRepository(
                new Report\Report,
                $app->make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface'),
                new Report\ReportValidator($app['validator'])
            );
        });

        // The Product Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Product\ProductInterface', function($app)
        {
            return new Product\ProductRepository(
                new Product\Product,
                $app->make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface'),
                new Product\ProductValidator($app['validator'])
            );
        });

        // The Order Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Order\OrderInterface', function($app)
        {
            return new Order\OrderRepository(
                new Order\Order,
                new Order\OrderValidator($app['validator'])
            );
        });
    }

}