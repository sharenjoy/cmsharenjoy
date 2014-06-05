<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Eloquent;

class Order extends Eloquent {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'orders';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array(
        'sn',
        'name',
        'mobile',
        'email',
        'address',
        'content',
        'total',
        'delivery_price',
        'totalamount'
    );

    public $formConfig = [
        'sn'          => ['order' => '5'],
        'name'        => ['order' => '8'],
        'mobile'      => ['order' => '10'],
        'email'       => ['order' => '20'],
        'address'     => ['order' => '30'],
        'totalamount' => ['order' => '40'],
        'content'     => ['order' => '50', 'type'=>'textarea'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    /**
     * Indicates if the model should soft delete.
     * @var bool
     */
    protected $softDelete = false;

    public function orderDetail()
    {
        return $this->hasMany('Sharenjoy\Cmsharenjoy\Order\OrderDetail', 'order_id');
    }

}