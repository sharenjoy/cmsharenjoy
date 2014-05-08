<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;

class TagController extends ObjectBaseController {

    protected $appName = 'tag';

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

    public function __construct(TagInterface $tag)
    {
        $this->repository = $tag;
        parent::__construct();
    }

}