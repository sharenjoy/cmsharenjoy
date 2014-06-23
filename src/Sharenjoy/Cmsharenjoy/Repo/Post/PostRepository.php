<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class PostRepository extends EloquentBaseRepository implements PostInterface {

    protected $tag;
    
    public function __construct(Post $post, TagInterface $tag, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $post;
        $this->tag       = $tag;

        parent::__construct();
    }

}