<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Order\OrderInterface;
use Sharenjoy\Cmsharenjoy\Repo\Order\OrderDetail;
use Poster;

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
        'process_id'  => ['name'=>'process',      'align'=>'center', 'width'=>''   ],
        'created_at'  => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(OrderInterface $order)
    {
        $this->repository = $order;
        parent::__construct();
    }

    public function getUpdate($id)
    {
        try
        {
            $model = Poster::showById($id);
            $detail = $model->orderDetail()->get();
        }
        catch(EntityNotFoundException $e)
        {
            return Redirect::to($this->objectUrl);
        }

        $fieldsForm = $this->repository->getForm($model);

        $this->layout->with('item' , $model)
                     ->with('orderDetail', $detail)
                     ->with('fieldsForm', $fieldsForm);
    }

}