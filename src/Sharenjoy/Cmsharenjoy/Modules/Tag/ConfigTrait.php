<?php namespace Sharenjoy\Cmsharenjoy\Modules\Tag;

trait ConfigTrait {

    protected $eventItem = [];

    public $filterFormConfig = [];

    public $formConfig = [
        'tag'          => ['type'  => 'text', 'order' => '10'],
        'count'        => ['type'  => 'text', 'order' => '20']
    ];

    public $previewFormConfig = [];
    public $createFormConfig  = [];
    public $updateFormConfig  = [];

    public $previewFormDeny   = [];
    public $createFormDeny    = [];
    public $updateFormDeny    = [];

}