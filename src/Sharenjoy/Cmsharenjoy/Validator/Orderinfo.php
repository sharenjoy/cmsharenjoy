<?php namespace Sharenjoy\Cmsharenjoy\Validator;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class Orderinfo extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'name'    => 'required',
        'mobile'  => 'required',
        'address' => 'required'
    ];

}