<?php namespace Sharenjoy\Cmsharenjoy\Validator;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class Login extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'email'                 => 'required|email',
        'password'              => 'required'
    ];

}