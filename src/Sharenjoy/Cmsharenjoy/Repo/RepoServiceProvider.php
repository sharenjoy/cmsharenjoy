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
            return new Post\PostRepository(new Post\Post, new Post\PostValidator);
        });

        // The Category Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Category\CategoryInterface', function($app)
        {
            return new Category\CategoryRepository(
                new \Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category,
                new Category\CategoryValidator
            );
        });

        // The Tag Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface', function($app)
        {
            return new Tag\TagRepository(new Tag\Tag, new Tag\TagValidator);
        });

        // The Member Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Member\MemberInterface', function($app)
        {
            return new Member\MemberRepository(new Member\Member, new Member\MemberValidator);
        });

        // The Report Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Report\ReportInterface', function($app)
        {
            return new Report\ReportRepository(new Report\Report, new Report\ReportValidator);
        });

        // The Qna Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Qna\QnaInterface', function($app)
        {
            return new Qna\QnaRepository(new Qna\Qna, new Qna\QnaValidator);
        });

        // The Product Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Product\ProductInterface', function($app)
        {
            return new Product\ProductRepository(new Product\Product, new Product\ProductValidator);
        });

        // The Order Binding
        $app->bind('Sharenjoy\Cmsharenjoy\Repo\Order\OrderInterface', function($app)
        {
            return new Order\OrderRepository(new Order\Order, new Order\OrderValidator);
        });
    }

}