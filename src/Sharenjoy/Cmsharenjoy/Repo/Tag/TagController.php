<?php namespace Sharenjoy\Cmsharenjoy\Repo\Tag;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;

class TagController extends ObjectBaseController {

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order'  => true,
    ];

    protected $listConfig = [
        'tag'          => ['name'=>'tag',          'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(TagInterface $repo)
    {
        $this->repository = $repo;
        parent::__construct();
    }

}