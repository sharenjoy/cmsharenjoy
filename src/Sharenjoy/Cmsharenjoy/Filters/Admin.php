<?php namespace Sharenjoy\Cmsharenjoy\Filters;

use Sentry, Redirect, Config, Request, Session;

class Admin {

    /**
     * If the user is not logged in then we need to get them outta here.
     * @return mixed
     */
    public function filter()
    {
        if ( ! Sentry::check())
        {
            return Redirect::guest(Config::get('cmsharenjoy::app.access_url').'/login');
        }
        
        $segment = Request::segment(2);

        // This is for language
        $lang = $segment;
        if (array_key_exists($lang, Config::get('cmsharenjoy::app.locales')))
        {
            Session::put('admin-locale', $lang);

            return Redirect::back();
        }
    }

}