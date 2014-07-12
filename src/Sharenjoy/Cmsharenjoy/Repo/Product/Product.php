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

    public $uniqueFields = [];
    
    public $createComposeItem = ['user', 'sort'];
    public $updateComposeItem = ['user'];

    public $processItem = [
        'get-index'   => [],
        'get-sort'    => [],
        'get-create'  => [],
        'get-update'  => [],
        'post-create' => [],
        'post-create' => [],
    ];

    public $filterFormConfig = [
        'category_id'       => ['args' => ['category'=>'Product']],
        'keyword'           => [],
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


    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function username($field = __FUNCTION__)
    {
        $this->$field = isset($this->author->name) ? $this->author->name : '';
    }

    public function categories()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\Repo\Category\Category', 'category_id');
    }

    public function scopeCategory_id($query, $value)
    {
        return $value ? $query->where('category_id', $value) : $query;
    }

    public function scopeKeyword($query, $value)
    {
        return $value ? $query->where('title', 'LIKE', "%$value%") : $query;
    }

}