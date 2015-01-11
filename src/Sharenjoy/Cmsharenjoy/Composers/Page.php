<?php namespace Sharenjoy\Cmsharenjoy\Composers;

// use Illuminate\Support\MessageBag;
use Session, Config;

class Page {

    /**
     * Compose the view with the following variables bound do it
     * @param  View $view The View
     * @return null
     */
    public function compose($view)
    {
        $view->with('menu_items', Config::get('cmsharenjoy::app.menu_items'))
             ->with('active_language', Session::get('sharenjoy.backEndLanguage'));
    }

}