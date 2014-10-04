<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Config;

class Order extends EloquentBaseModel {

    protected $table    = 'orders';

    protected $fillable = [
        'sn',
        'member_id',
        'name',
        'mobile',
        'email',
        'address',
        'content',
        'total',
        'delivery_price',
        'totalamount',
        'easy_contact_time_id',
        'delivery_time_zone_id',
        'process_id',
    ];

    protected $eventItem = [];

    public $filterFormConfig = [];

    public $formConfig = [
        'sn'                    => ['order' => '5'],
        'name'                  => ['order' => '8'],
        'mobile'                => ['order' => '10'],
        'email'                 => ['order' => '20'],
        'address'               => ['order' => '30'],
        'totalamount'           => ['order' => '40'],
        'easy_contact_time_id'  => ['order' => '45', 'type'=>'select', 'option' => 'easy_contact_time'],
        'delivery_time_zone_id' => ['order' => '47', 'type'=>'select', 'option' => 'delivery_time_zone'],
        'process_id'            => ['order' => '48', 'type'=>'radio', 'option' => 'process'],
        'content'               => ['order' => '50', 'type'=>'textarea'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];

    public function listQuery()
    {
        return $this->orderBy('created_at', 'DESC');
    }

    public function orderDetail()
    {
        return $this->hasMany('Sharenjoy\Cmsharenjoy\Repo\Order\OrderDetail', 'order_id');
    }

    public function getProcessAttribute()
    {
        return Config::get('cmsharenjoy::options.process.'.$this->process_id);
    }

}