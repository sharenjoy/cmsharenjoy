<?php namespace Sharenjoy\Cmsharenjoy\User;

trait ConfigTrait {

    protected $eventItem = [];

    public $formConfig = [
        'avatar'                => ['order' => '5', 'help'=>'建議尺寸180x180px', 'size'=>'180x180'],
        'name'                  => ['order' => '10'],
        'email'                 => ['order' => '20'],
        'phone'                 => ['order' => '30'],
        'password'              => ['order' => '40'],
        'password_confirmation' => ['order' => '50'],
        'description'           => ['order' => '60'],
    ];

    public $viewFormConfig   = [];
    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $viewFormDeny     = [];
    public $createFormDeny   = [];
    public $updateFormDeny   = ['password', 'password_confirmation'];

}