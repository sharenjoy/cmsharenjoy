<?php namespace Sharenjoy\Cmsharenjoy\Repo\Report;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class Report extends EloquentBaseModel {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'reports';

    protected $fillable = array(
        'user_id',
        'status_id',
        'title',
        'link',
        'source',
        'content',
        'img',
        'sort'
    );

    public $uniqueFields = [];
    
    public $composeItem = [
        'slug|title',
        'user',
        'sort',
        'status'
    ];

    public $processItem = [
        'get-index'   => ['username|thisismyname'],
        'get-sort'    => [],
        'get-create'  => [],
        'get-update'  => ['username|thisismyname'],
        'post-create' => [],
        'post-create' => [],
    ];

    public $filterFormConfig = [
        // 'tag'        => ['type' => 'select','model'=>'tag','item'=>'tag','args'=>['placeholder'=>'This is a tag'],],
        'status'     => [],
        // 'language'   => ['type' => 'select', 'option' => ['1'=>'tw', '2'=>'en'] ],
        'keyword'    => [],
        // 'date_range' => ['type' => 'daterange', ],
        // 'start_date' => ['type' => 'datepicker', ],
        // 'color'      => ['type' => 'colorpicker', ],
    ];

    public $formConfig = [
        'img'               => ['order' => '5'],
        'title'             => ['order' => '10'],
        'link'              => ['order' => '20'],
        'source'            => ['order' => '30'],
        'content'           => ['order' => '40'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];
    

    public function scopeStatus($query, $value)
    {
        return $value ? $query->where('status_id', $value) : $query;
    }

    public function scopeKeyword($query, $value)
    {
        return $value ? $query->where('title', 'LIKE', "%$value%") : $query;
    }

}