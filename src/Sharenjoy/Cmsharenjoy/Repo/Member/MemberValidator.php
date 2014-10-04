<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class MemberValidator extends AbstractLaravelValidator {

    public $unique = ['email'];
    
    public $rules = [
        'name'                  => 'required',
        'email'                 => 'required|email|unique:members,email',
        'mobile'                => 'required',
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6',
    ];

    public $updateRules = [
        'name'                  => 'required',
        'email'                 => 'required|email|unique:members,email',
        'mobile'                => 'required'
    ];

}