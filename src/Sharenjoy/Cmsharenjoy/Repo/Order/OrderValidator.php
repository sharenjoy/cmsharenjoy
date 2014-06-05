<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class OrderValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'name'    => 'required',
        'mobile'  => 'required',
        'email'   => 'required|email',
        'address' => 'required',
    ];

}