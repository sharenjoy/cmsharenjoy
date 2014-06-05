<?php namespace Sharenjoy\Cmsharenjoy\Repo\Report;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class ReportRepository extends EloquentBaseRepository implements ReportInterface {
    
    public function __construct(Report $report, TagInterface $tag, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $report;
        $this->tag       = $tag;
        
        parent::__construct();
    }

}