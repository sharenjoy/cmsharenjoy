<?php namespace Sharenjoy\Cmsharenjoy\User;

use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;
use Sharenjoy\Cmsharenjoy\Core\Traits\CommonModelTrait;

class User extends SentryUserModel {

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

    protected $eventItem = [];

    public $filterFormConfig = [];

    public $formConfig = [
        'avatar'                => ['order' => '5', 'help'=>'建議尺寸180x180px', 'size'=>'180x180'],
        'name'                  => ['order' => '10'],
        'email'                 => ['order' => '20'],
        'phone'                 => ['order' => '30'],
        'password'              => ['order' => '40', 'update'=>'deny'],
        'password_confirmation' => ['order' => '50', 'update'=>'deny'],
        'description'           => ['order' => '60'],
    ];

    protected $hidden = ['password'];

    /**
     * sentry methods
     */
    public function getAuthIdentifier(){ return $this->getKey(); }
    public function getAuthPassword(){ return $this->password; }
    public function getReminderEmail(){ return $this->email; }

    public function listQuery()
    {
        return $this->orderBy('created_at', 'DESC');
    }

}