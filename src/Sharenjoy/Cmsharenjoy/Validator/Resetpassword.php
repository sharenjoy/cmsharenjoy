<?php namespace Sharenjoy\Cmsharenjoy\Validator;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class Resetpassword extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'old_password'          => 'required|min:6',
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6'
    ];

}