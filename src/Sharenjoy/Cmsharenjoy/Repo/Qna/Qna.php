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

    public $uniqueFields = [];
    
    public $createComposeItem = [
        'user',
        'status',
        'sort'
    ];

    public $updateComposeItem = [
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
    
    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function username($field = __FUNCTION__)
    {
        $this->$field = isset($this->author->name) ? $this->author->name : '';
    }

}