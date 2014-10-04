<?php namespace Sharenjoy\Cmsharenjoy\Repo\Qna;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;

class QnaController extends ObjectBaseController {

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order'  => true,
    ];

    protected $listConfig = [
        'title'        => ['name'=>'title',        'type'=>'',       'align'=>'',       'width'=>''    ],
        'answer'       => ['name'=>'answer',       'type'=>'',       'align'=>'',       'width'=>''    ],
        'created_at'   => ['name'=>'created',      'type'=>'',       'align'=>'center', 'width'=>'20%' ],
    ];

    public function __construct(QnaInterface $repo)
    {
        $this->repo = $repo;
        parent::__construct();
    }

}