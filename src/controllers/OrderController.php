<?php namespace Sharenjoy\Cmsharenjoy\Controllers;


use Sharenjoy\Cmsharenjoy\Repo\Order\OrderInterface;
use Sharenjoy\Cmsharenjoy\Repo\Order\OrderDetail;

class OrderController extends ObjectBaseController {

    protected $appName = 'order';

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
        'email'       => ['name'=>'email',        'align'=>'',       'width'=>''   ],
        'address'     => ['name'=>'address',      'align'=>'',       'width'=>''   ],
        'totalamount' => ['name'=>'amount',       'align'=>'right',  'width'=>''   ],
        'created_at'  => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(OrderInterface $order)
    {
        $this->repository = $order;
        parent::__construct();
    }

    public function getUpdate($id)
    {
        // Catch some validation if can't get the data
        try {
            $model = $this->repository->byId($id);

            $detail = OrderDetail::where('order_id', $id)->get();
        }
        catch(EntityNotFoundException $e)
        {
            return Redirect::to($this->objectUrl);
        }

        $fieldsForm = $this->repository->setFormFields($model);

        $this->layout->with('item' , $model)
                     ->with('orderDetail', $detail)
                     ->with('fieldsForm', $fieldsForm);
    }

}