<?php namespace Sharenjoy\Cmsharenjoy\User;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface as LaravelUserInterface;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel implements LaravelUserInterface, RemindableInterface {

    protected $table  = 'users';

    protected $fillable = array(
        'email',
        'password'
    );

    public $uniqueFields = ['email'];

    public $formConfig = [
        'last_name'             => ['order' => '10'],
        'first_name'            => ['order' => '20'],
        'email'                 => ['order' => '30'],
        'phone'                 => ['order' => '35'],
        'password'              => ['order' => '40'],
        'password_confirmation' => ['order' => '50'],
        'description'           => ['order' => '60'],
    ];

    public $updateFormDeny = ['password', 'password_confirmation'];

    public function account()
    {
        return $this->hasOne('Sharenjoy\Cmsharenjoy\User\Account', 'user_id');
    }

    /**
     * sentry methods
     */
    public function getAuthIdentifier(){ return $this->getKey(); }
    public function getAuthPassword(){ return $this->password; }
    public function getReminderEmail(){ return $this->email; }

}