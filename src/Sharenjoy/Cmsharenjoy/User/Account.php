<?php namespace Sharenjoy\Cmsharenjoy\User;

use Eloquent;

class Account extends Eloquent {

    protected $table = 'accounts';

    public $timestamps = false;

    protected $fillable = array(
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'description'
    );

    public function user()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

}