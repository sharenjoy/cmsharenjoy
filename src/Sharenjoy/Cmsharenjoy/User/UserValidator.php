<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class UserValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'email'                 => 'required',
        'password'              => 'required|between:4,12',

        // 'email'                 => 'required|unique:users,email',
        // 'password'              => 'required|between:4,12|confirmed',
        // 'password_confirmation' => 'required|between:4,12'
    ];

}