<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class UserValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6'
    ];

    protected $updateRules = [
        'email'                 => 'required|email|unique:users,email',
    ];

}