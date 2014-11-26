<?php namespace Sharenjoy\Cmsharenjoy\User;

use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface as LaravelUserInterface;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;
use Sharenjoy\Cmsharenjoy\Core\Traits\CommonModelTrait;

class User extends SentryUserModel implements LaravelUserInterface, RemindableInterface {

    use ConfigTrait;
    use CommonModelTrait;

    protected $table  = 'users';

    protected $fillable = [
        'email',
        'password',
        'name',
        'phone',
        'avatar',
        'description'
    ];

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

    public function listQuery()
    {
        return $this->orderBy('created_at', 'DESC');
    }

}