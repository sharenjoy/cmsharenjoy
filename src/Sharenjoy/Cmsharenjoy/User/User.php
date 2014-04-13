<?php namespace Sharenjoy\Cmsharenjoy\User;

use Zizaco\Confide\ConfideUser;

class User extends ConfideUser {

    protected $table  = 'users';

    protected $fillable = array(
        'email',
        'password'
    );

    public static $rules = array(
        'email'                 => 'required|email|unique:users',
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'min:6',
    );

    public $uniqueFields = ['email'];

    public $formConfig = [
        'first_name' => [
            'args' => [
                'placeholder'=>'Your first name',
            ],
            'order' => '10'
        ],
        'last_name' => [
            'args' => [
                'placeholder'=>'Your last name',
            ],
            'order' => '20'
        ],
        'email' => [
            'args' => [
                'placeholder'=>'Your email',
            ],
            'order' => '30'
        ],
        'phone' => [
            'args' => [
                'placeholder'=>'Your cell phone',
            ],
            'order' => '35'
        ],
        'password' => ['order' => '40'],
        'password_confirmation' => ['order' => '50'],

    ];

    public function account()
    {
        return $this->hasOne('Sharenjoy\Cmsharenjoy\User\Account', 'user_id');
    }

}