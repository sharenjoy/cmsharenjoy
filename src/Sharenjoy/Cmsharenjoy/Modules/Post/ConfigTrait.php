<?php namespace Sharenjoy\Cmsharenjoy\Modules\Post;

trait ConfigTrait {

    protected $eventItem = [
        'creating'    => ['user_id', 'status_id', 'slug|title', 'sort'],
        'created'     => ['album'],
        'updating'    => ['user_id', 'status_id', 'slug|title'],
        'saved'       => ['taggable'],
        'deleted'     => ['un_taggable'],
    ];

    public $filterFormConfig = [
        'keyword'     => ['filter' => 'posts.title,posts.content'],
    ];

    public $formConfig = [
        'title'       => ['order' => '10', 'inner-div-class'=>'col-sm-5'],
        'tag'         => ['order' => '20', 'inner-div-class'=>'col-sm-5'],
        'process_id'  => ['order' => '25', 'type'=>'file', 'inner-div-class'=>'col-sm-5'],
        'content'     => ['order' => '30', 'inner-div-class'=>'col-sm-9'],
    ];

    public $viewFormConfig   = [];
    public $createFormConfig = [];
    public $updateFormConfig = [
        'album'       => ['order' => '25'],
    ];

    public $viewFormDeny     = [];
    public $createFormDeny   = [];
    public $updateFormDeny   = [];

}