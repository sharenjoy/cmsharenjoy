<?php namespace Sharenjoy\Cmsharenjoy\Modules\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseHandler;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class PostHandler extends EloquentBaseHandler implements PostInterface {
    
    public function __construct(Post $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;
    }

}