<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class Product extends EloquentBaseModel {

    protected $table    = 'products';

    protected $fillable = array(
        'user_id',
        'category_id',
        'title',
        'title_jp',
        'price',
        'description',
        'img',
        'sort'
    );

    protected $eventItem = [
        'creating'    => ['user_id', 'sort'],
        'updating'    => ['user_id'],
    ];

    public $filterFormConfig = [
        'category_id'       => ['args' => ['category'=>'Product']],
        'created_range'     => ['type' => 'daterange'],
        'keyword'           => ['args' => ['data-filter' => 'products.title,products.title_jp,products.description']],
    ];

    public $formConfig = [
        'img'               => ['order' => '5'],
        'category_id'       => ['order' => '8', 'args' => ['category'=>'Product']],
        'title'             => ['order' => '10'],
        'title_jp'          => ['order' => '20'],
        'price'             => ['order' => '30'],
        'description'       => ['order' => '40'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];

    public function listQuery()
    {
        return $this->select(['products.*', 'categories.title as ctitle'])
                     ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                     ->orderBy('categories.sort')
                     ->orderBy('products.sort', 'DESC');
    }

    public function categories()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category', 'category_id');
    }

    public function scopeCategory_id($query, $value)
    {
        return $value ? $query->where('products.category_id', $value) : $query;
    }

    public function scopeCreated_range($query, $value)
    {
        $between = explode(' ~ ', $value);
        return $value ? $query->whereBetween('products.created_at', $between) : $query;
    }

}