<?php namespace Sharenjoy\Cmsharenjoy\Validators;
use Sharenjoy\Cmsharenjoy\Abstracts\ValidatorBase;

class Login extends ValidatorBase
{

    protected $rules = array(
        'email'         =>  'required|email',
        'password'      =>  'required'
    );

}