<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class Post extends EloquentBaseModel {

    protected $table    = 'posts';

    protected $fillable = array(
        'user_id',
        'status_id',
        'title',
        'slug',
        'content',
        'sort'
    );

    public $uniqueFields = ['slug'];
    
    public $createComposeItem = [
        'slug|title',
        'user',
        'status',
        'sort'
    ];

    public $updateComposeItem = [
        'slug|title',
        'user',
        'status'
    ];

    public $processItem = [
        'get-index'   => [],
        'get-sort'    => [],
        'get-create'  => [],
        'get-update'  => [],
        'post-create' => [],
        'post-create' => [],
    ];

    public $filterFormConfig = [
        // 'tag'         => ['type' => 'select','model'=>'tag','item'=>'tag','args'=>['placeholder'=>'This is a tag']],
        // 'status'      => [],
        // 'language'    => ['type' => 'select', 'option' => ['1'=>'tw', '2'=>'en']],
        // 'keyword'     => [],
        // 'date_range'  => ['type' => 'daterange'],
        // 'start_date'  => ['type' => 'datepicker'],
        // 'color'       => ['type' => 'colorpicker'],
    ];

    public $formConfig = [
        'title'       => ['order' => '10'],
        // 'tag'         => ['input' => 'tags_csv', 'order' => '20'],
        'content'     => ['order' => '30']
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

    public function tags()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Repo\Tag\Tag', 'posts_tags', 'post_id', 'tag_id');
    }

}