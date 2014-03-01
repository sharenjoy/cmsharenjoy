<?php namespace Sharenjoy\Cmsharenjoy\Posts;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;

class PostsRepository extends EloquentBaseRepository implements PostsInterface {

    protected $tag;

    /**
     * Construct Shit
     * @param Posts $posts
     */
    public function __construct(Posts $posts, TagInterface $tag)
    {
        $this->model = $posts;
        $this->tag = $tag;
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