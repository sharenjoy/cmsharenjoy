<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class OrderValidator extends AbstractLaravelValidator {

    public $unique = [];

    public $rules = [
        'name'    => 'required',
        'mobile'  => 'required',
        // 'email'   => 'required|email',
        'address' => 'required',
    ];

    /**
     * This is for frontend order page
     * @var array
     */
    public $orderInfoRules = [
        'name'    => 'required',
        'mobile'  => 'required',
        'address' => 'required'
    ];

}