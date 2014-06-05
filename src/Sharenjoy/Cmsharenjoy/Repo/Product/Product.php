<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Eloquent;

class Product extends Eloquent {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'products';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'category_id',
        'title',
        'title_jp',
        'price',
        'description',
        'img'
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

    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function categories()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\Repo\Category\Category', 'category_id');
    }

}