<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class PostRepository extends EloquentBaseRepository implements PostInterface {
    
    public function __construct(Post $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;

        parent::__construct();
    }

}