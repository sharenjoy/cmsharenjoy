<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class UserValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'name'                  => 'required',
        'email'                 => 'required|email|unique:users,email',
        'phone'                 => 'required',
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6',
    ];

    public $updateRules = [
        'name'                  => 'required',
        'email'                 => 'required|email|unique:users,email',
        'phone'                 => 'required'
    ];

}