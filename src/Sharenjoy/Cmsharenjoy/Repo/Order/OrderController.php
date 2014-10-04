<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;

class OrderController extends ObjectBaseController {

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
    ];

    protected $listConfig = [
        'sn'          => ['name'=>'order_sn',     'align'=>'',       'width'=>''   ],
        'name'        => ['name'=>'name',         'align'=>'',       'width'=>''   ],
        'mobile'      => ['name'=>'mobile',       'align'=>'',       'width'=>''   ],
        'totalamount' => ['name'=>'amount',       'align'=>'right',  'width'=>''   ],
        'process'     => ['name'=>'process',      'align'=>'center', 'width'=>''   ],
        'created_at'  => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(OrderInterface $repo)
    {
        $this->repo = $repo;
        parent::__construct();
    }

    public function getUpdate($id)
    {
        try
        {
            $model = $this->repo->showById($id);

            // This is differente from parent
            $detail = $model->orderDetail()->get();
            
            $fieldsForm = $this->repo->makeForm($model);
        }
        catch(\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
        {
            Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $id]));
            return Redirect::to($this->objectUrl);
        }

        $this->layout->with('item' , $model)
                     ->with('orderDetail', $detail)
                     ->with('fieldsForm', $fieldsForm);
    }

}