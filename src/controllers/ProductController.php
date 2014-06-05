<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Product\ProductInterface;

class ProductController extends ObjectBaseController {

    protected $appName = 'product';

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order'  => true,
    ];

    protected $listConfig = [
        'title'        => ['name'=>'title',        'type'=>'',       'align'=>'',       'width'=>''    ],
        'title_jp'     => ['name'=>'title_jp',     'type'=>'',       'align'=>'',       'width'=>''    ],
        'price'        => ['name'=>'price',        'type'=>'',       'align'=>'right',  'width'=>''    ],
        'img'          => ['name'=>'image',        'type'=>'image',  'align'=>'center', 'width'=>'80'  ],
        'created_at'   => ['name'=>'created',      'type'=>'',       'align'=>'center', 'width'=>'20%' ],
    ];

    public function __construct(ProductInterface $repo)
    {
        $this->repository = $repo;
        parent::__construct();
    }

}