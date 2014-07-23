<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
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

    public function __construct(OrderInterface $repo)
    {
        $this->repository = $repo;
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