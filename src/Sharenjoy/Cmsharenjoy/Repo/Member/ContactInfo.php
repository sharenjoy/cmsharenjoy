<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;

class ContactInfo extends EloquentBaseModel {

    protected $table = 'member_contacts';

    protected $fillable = [
        'member_id',
        'esay_contact_time_id',
        'name',
        'phone',
        'address'
    ];

    protected $eventItem = [];

    public $filterFormConfig = [];

    public $formConfig = [
        'name'                  => ['order' => '10'],
        'phone'                 => ['order' => '20'],
        'address'               => ['order' => '30'],
        'easy_contact_time_id'  => ['order' => '40'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];
    
}
