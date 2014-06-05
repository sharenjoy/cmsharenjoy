<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Eloquent;

class OrderDetail extends Eloquent {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'order_detail';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array(
        'order_id',
        'product_id',
        'name',
        'img',
        'quantity',
        'price'
    );

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

    /**
     * Indicates if the model should soft delete.
     * @var bool
     */
    protected $softDelete = false;

    public $timestamps = false;

    public function orderDetail()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\Order\Order', 'order_id');
    }

}