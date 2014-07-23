<?php namespace Sharenjoy\Cmsharenjoy\Repo\Report;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;

class ReportController extends ObjectBaseController {

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order'  => true,
    ];

    protected $listConfig = [
        'title'        => ['name'=>'title',        'type'=>'',       'align'=>'',       'width'=>''    ],
        'link'         => ['name'=>'link',         'type'=>'link',   'align'=>'center', 'width'=>'80'  ],
        'img'          => ['name'=>'image',        'type'=>'image',  'align'=>'center', 'width'=>'80'  ],
        'created_at'   => ['name'=>'created',      'type'=>'',       'align'=>'center', 'width'=>'20%' ],
    ];

    public function __construct(ReportInterface $repo)
    {
        $this->repository = $repo;
        parent::__construct();
    }

}