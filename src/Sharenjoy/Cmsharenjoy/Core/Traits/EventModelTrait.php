<?php namespace Sharenjoy\Cmsharenjoy\Core\Traits;

use Sharenjoy\Cmsharenjoy\Utilities\String;
use Session, Hash;

trait EventModelTrait {

    public function eventSlug($args, $model)
    {
        if ($this->$args[1])
            $this->$args[0] = String::slug($this->$args[1]);
        else
            $this->$args[0] = String::slug($this->$args[0]);
    }

    public function eventUserId($key, $model)
    {
        $this->$key = Session::get('user')->id;
    }

    public function eventSort($key, $model)
    {
        $this->$key = time();
    }

    public function eventStatusId($key, $model)
    {
        $this->$key = 1;
    }

    public function eventPassword($args, $model)
    {
        if ($this->$args[1])
            $this->$args[0] = Hash::make($this->$args[1]);
        else
            $this->$args[0] = Hash::make($this->$args[0]);
    }

}