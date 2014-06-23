<?php namespace Sharenjoy\Cmsharenjoy\User;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface as LaravelUserInterface;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel implements LaravelUserInterface, RemindableInterface {

    protected $table  = 'users';

    protected $fillable = array(
        'email',
        'password',
        'name',
        'phone',
        'description'
    );

    public $uniqueFields = ['email'];

    public $createComposeItem = ['sort'];
    public $updateComposeItem = [];

    public $processItem = [
        'get-index'   => [],
        'get-sort'    => [],
        'get-create'  => [],
        'get-update'  => [],
        'post-create' => [],
        'post-create' => [],
    ];

    public $formConfig = [
        'name'                  => ['order' => '10'],
        'email'                 => ['order' => '20'],
        'phone'                 => ['order' => '30'],
        'password'              => ['order' => '40'],
        'password_confirmation' => ['order' => '50'],
        'description'           => ['order' => '60'],
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [];

    public $createFormDeny   = [];
    public $updateFormDeny   = ['password', 'password_confirmation'];

    protected $hidden = ['password'];

    /**
     * sentry methods
     */
    public function getAuthIdentifier(){ return $this->getKey(); }
    public function getAuthPassword(){ return $this->password; }
    public function getReminderEmail(){ return $this->email; }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

}