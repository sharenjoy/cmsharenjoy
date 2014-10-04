<?php namespace Sharenjoy\Cmsharenjoy\Repo\Qna;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class Qna extends EloquentBaseModel {

    protected $table    = 'qnas';

    protected $fillable = [
        'user_id',
        'status_id',
        'title',
        'answer',
        'description',
        'sort'
    ];

    protected $eventItem = [
        'creating'    => ['user_id', 'status_id', 'sort'],
        'updating'    => ['user_id', 'status_id'],
    ];

    public $filterFormConfig = [];

    public $formConfig = [
        'title'       => ['order' => '10'],
        'answer'      => ['order' => '20'],
        'description' => ['order' => '30'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];

}