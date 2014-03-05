<?php namespace Sharenjoy\Cmsharenjoy\Filters;
use Auth, Redirect, Config;

class Admin {

    /**
     * If the user is not logged in then we need to get them outta here.
     * @return mixed
     */
    public function filter()
    {

        if (Auth::guest())
        {
            return Redirect::guest( Config::get('cmsharenjoy::app.access_url').'/login');
        }
    }

}