<?php namespace Sharenjoy\Cmsharenjoy\Posts;
use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Abstracts\Traits\TaggableRepository;

class PostsRepository extends EloquentBaseRepository implements PostsInterface
{

    use TaggableRepository;

    /**
     * Construct Shit
     * @param Posts $posts
     */
    public function __construct( Posts $posts )
    {
        $this->model = $posts;
    }

    /**
     * Get all posts by date published ascending
     * @return Posts
     */
    public function getAllByDateAsc()
    {
        return $this->model->orderBy('created_at','asc')->get();
    }

    /**
     * Get all posts by date published descending
     * @return Posts
     */
    public function getAllByDateDesc()
    {
        return $this->model->orderBy('created_at','desc')->get();
    }

}