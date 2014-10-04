<?php namespace Sharenjoy\Cmsharenjoy\Repo\Report;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class ReportRepository extends EloquentBaseRepository implements ReportInterface {
    
    public function __construct(Report $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;
        
        parent::__construct();
    }

}