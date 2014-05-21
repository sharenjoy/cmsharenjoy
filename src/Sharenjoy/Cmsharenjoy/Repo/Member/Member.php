<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Eloquent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Member extends Eloquent implements UserInterface, RemindableInterface {

    protected $table = 'members';

    protected $fillable = array(
        'email',
        'password',
        'name',
        'phone',
        'mobile',
        'description'
    );

    public $uniqueFields = ['email'];

    public $formConfig = [
        'name'                  => ['order' => '10'],
        'email'                 => ['order' => '20'],
        'phone'                 => ['order' => '30'],
        'mobile'                => ['order' => '35'],
        'password'              => ['order' => '40'],
        'password_confirmation' => ['order' => '50'],
        'description'           => ['order' => '60'],
    ];

    public $updateFormDeny = ['password', 'password_confirmation'];

    protected $hidden = ['password'];

    /**
     * Get the unique identifier for the member.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the member.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

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

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

}
