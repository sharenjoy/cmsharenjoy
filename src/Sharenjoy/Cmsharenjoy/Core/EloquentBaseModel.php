<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Eloquent;

abstract class EloquentBaseModel extends Eloquent {

    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function username($field = __FUNCTION__)
    {
        $this->$field = $this->author->name;
    }

}