<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class OrderDetail extends EloquentBaseModel {

    protected $table    = 'order_detail';

    protected $fillable = array(
        'order_id',
        'product_id',
        'name',
        'img',
        'quantity',
        'price'
    );

    protected $eventItem = [];

    public $filterFormConfig = [];

    public $formConfig = [
        'img'               => ['order' => '5'],
        'category_id'       => ['order' => '8'],
        'title'             => ['order' => '10'],
        'title_jp'          => ['order' => '20'],
        'price'             => ['order' => '30'],
        'description'       => ['order' => '40'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\Repo\Order\Order', 'order_id');
    }

}