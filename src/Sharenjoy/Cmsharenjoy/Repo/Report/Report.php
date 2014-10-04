<?php namespace Sharenjoy\Cmsharenjoy\Repo\Report;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Sharenjoy\Cmsharenjoy\Repo\Tag\Traits\TaggableTrait;

class Report extends EloquentBaseModel {

    use TaggableTrait;

    protected $table    = 'reports';

    protected $fillable = [
        'user_id',
        'status_id',
        'title',
        'link',
        'source',
        'content',
        'img',
        'sort'
    ];

    protected $eventItem = [
        'creating'    => ['user_id', 'status_id', 'sort'],
        'updating'    => ['user_id', 'status_id'],
        'saved'       => ['taggable'],
        'deleted'     => ['un_taggable'],
    ];


    public $filterFormConfig = [
        'status'     => [],
        'keyword'    => ['args'=>['data-filter'=>'title,content']],
    ];

    public $formConfig = [
        'img'               => ['order' => '5'],
        'title'             => ['order' => '10'],
        'link'              => ['order' => '20'],
        'source'            => ['order' => '30'],
        'tag'               => ['order' => '35'],
        'content'           => ['order' => '40'],
    ];
    public $createFormConfig = [];
    public $updateFormConfig = [];
    public $createFormDeny   = [];
    public $updateFormDeny   = [];

}