<?php
namespace Sharenjoy\Cmsharenjoy\Composers;
use Illuminate\Support\MessageBag;
use Auth, Session, Config, App;

class Page{

    /**
     * Compose the view with the following variables bound do it
     * @param  View $view The View
     * @return null
     */
    public function compose($view)
    {
        $settings = App::make('Sharenjoy\Cmsharenjoy\Settings\SettingsInterface');

        $view->with('user', Auth::user())
             ->with('app_name', $settings->getAppName() )
             ->with('urlSegment', Config::get('cmsharenjoy::app.access_url') )
             ->with('menu_items', Config::get('cmsharenjoy::app.menu_items') )
             ->with('success', Session::get('success' , new MessageBag ) )
             ->with('active_language', Session::get('locale'));
    }

}