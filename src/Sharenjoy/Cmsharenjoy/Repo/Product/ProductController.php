<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
use Input, View, Request, Paginator;

class ProductController extends ObjectBaseController {

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order'  => true,
    ];

    protected $listConfig = [
        'ctitle'       => ['name'=>'category',     'type'=>'',       'align'=>'center', 'width'=>''    ],
        'title'        => ['name'=>'title',        'type'=>'',       'align'=>'',       'width'=>''    ],
        'title_jp'     => ['name'=>'title_jp',     'type'=>'',       'align'=>'',       'width'=>''    ],
        'price'        => ['name'=>'price',        'type'=>'',       'align'=>'right',  'width'=>''    ],
        'img'          => ['name'=>'image',        'type'=>'image',  'align'=>'center', 'width'=>'80'  ],
        'created_at'   => ['name'=>'created',      'type'=>'',       'align'=>'center', 'width'=>'20%' ],
    ];

    public function __construct(ProductInterface $repo)
    {
        $this->repo = $repo;
        parent::__construct();
    }

}