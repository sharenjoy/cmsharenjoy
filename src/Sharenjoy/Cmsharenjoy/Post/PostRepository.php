<?php namespace Sharenjoy\Cmsharenjoy\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use App;

class PostRepository extends EloquentBaseRepository implements PostInterface {

    /**
     * Construct Shit
     * @param Posts $posts
     */
    public function __construct(Post $post, TagInterface $tag, ValidableInterface $validator)
    {
        $this->model = $post;
        $this->tag = $tag;
        $this->validator = $validator;

        parent::__construct();
    }

}