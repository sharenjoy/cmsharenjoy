<?php namespace Sharenjoy\Cmsharenjoy\Repo\Tag;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class Tag extends EloquentBaseModel {

    protected $table = 'tags';

    protected $fillable = array(
        'user_id',
        'tag',
        'slug',
        'sort'
    );

    public $uniqueFields = ['slug'];
    
    public $createComposeItem = [
        'slug|title',
        'user',
        'sort',
    ];

    public $updateComposeItem = [
        'slug|title',
        'user',
    ];

    public $processItem = [
        'get-index'   => [],
        'get-sort'    => [],
        'get-create'  => [],
        'get-update'  => [],
        'post-create' => [],
        'post-create' => [],
    ];

    public $filterFormConfig = [];

    public $formConfig = [
        'tag'         => ['type'  => 'text', 'order' => '10']
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

    public function posts()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Repo\Post\Post', 'posts_tags', 'tag_id', 'post_id');
    }

}