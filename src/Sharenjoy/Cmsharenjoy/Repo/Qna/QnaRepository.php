<?php namespace Sharenjoy\Cmsharenjoy\Repo\Qna;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class QnaRepository extends EloquentBaseRepository implements QnaInterface {
    
    public function __construct(Qna $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;
        
        parent::__construct();
    }

}