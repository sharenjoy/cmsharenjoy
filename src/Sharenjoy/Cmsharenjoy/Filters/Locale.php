<?php namespace Sharenjoy\Cmsharenjoy\Filters;
use Config, Redirect, Request, Session, App;

class Locale {

    /**
     * If the user is not logged in then we need to get them outta here.
     * @return mixed
     */
    public function filter()
    {
        $lang = Request::segment(2);

        if ( in_array($lang, Config::get('cmsharenjoy::app.locales')) ) {
            Session::put('locale', $lang);

            return Redirect::back();
        }

        if ( Session::has('locale') ) {
            App::setLocale(Session::get('locale'));
            $lang = Session::get('locale');
        } else {
            $lang = Config::get('app.locale');
        }
    }

}