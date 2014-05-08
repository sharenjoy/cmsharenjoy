<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class AccountValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'last_name'  => 'required',
        'first_name' => 'required',
        'phone'      => 'required'
    ];

}