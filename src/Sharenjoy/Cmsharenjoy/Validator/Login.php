<?php namespace Sharenjoy\Cmsharenjoy\Validator;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class Login extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'email'                 => 'required|email',
        'password'              => 'required|min:6'
    ];

}