<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Illuminate\Routing\Controller;
use Auth, Session, App, View, Config, Str, Route, Request, Theme, Message;

abstract class BaseController extends Controller {

    /**
     * This is the whitelist which allow some entries can enter.
     * @var array
     */
    protected $whitelist = array();

    /**
     * The URL segment that can be used to access the system
     * @var string
     */
    protected $urlSegment;

    /**
     * The application name and also is view name
     * @var string
     */
    protected $appName;

    /**
     * To manage some function that can open or not
     * @var array
     */
    protected $functionRules;

    /**
     * The URL to get the root of this object ( /admin/posts for example )
     * @var string
     */
    protected $objectUrl;

    /**
     * The URL to create a new entry
     * @var string
     */
    protected $createUrl;

    /**
     * The URL that is used to edit shit
     * @var string
     */
    protected $updateUrl;

    /**
     * The URL to delete an entry
     * @var string
     */
    protected $deleteUrl;

    /**
     * The URL to sort page
     * @var string
     */
    protected $sortUrl;

    /**
     * The controller active right away
     * @var string
     */
    protected $onController;

    /**
     * The action active right away
     * @var string
     */
    protected $onAction;

    /**
     * The layout
     * @var string
     */
    protected $layout;

    /**
     * The theme instancd
     * @var object
     */
    protected $theme;

    /**
     * Initializer.
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        $this->filterProcess();
        $this->setCommonVariable();
        $this->setHandyUrls();
        $this->shareHandyUrls();
    }

    protected function filterProcess()
    {
        // Setup composed views and the variables that they require
        $this->beforeFilter('adminFilter' , array('except' => $this->whitelist));

        // csrf and include ajax
        $this->beforeFilter('csrfFilter', array('on' => 'post'));
    }

    protected function setCommonVariable()
    {
        // Achieve that segment
        $this->urlSegment = Config::get('cmsharenjoy::app.access_url');

        // Get the action name
        $routeArray = Str::parseCallback(Route::currentRouteAction(), null);
        
        if (last($routeArray) != null)
        {
            // Remove 'controller' from the controller name.
            $controller = str_replace('Controller', '', class_basename(head($routeArray)));

            // Take out the method from the action.
            $action = str_replace(array('get', 'post', 'patch', 'put', 'delete'), '', last($routeArray));

            $this->onController = $controller;
            $this->onAction = Str::slug(Request::method(). '-' .$action);
            Session::put('onAction', $this->onAction);
        }

        // Get the login user
        $user = Auth::user();
        Session::put('user', $user);

        // Share some variables to views
        $settings = App::make('Sharenjoy\Cmsharenjoy\Settings\SettingsInterface');
        View::share('app_name', $settings->getAppName());
        View::share('appName' , $this->appName);
        View::share('functionRules', $this->functionRules);
        View::share('user', $user);

        $composed_views = array('cmsharenjoy::*');
        View::composer($composed_views, 'Sharenjoy\Cmsharenjoy\Composers\Page');

        // Set the theme
        $this->theme = Theme::uses('admin');

        // Message
        View::share('messages', Message::getMessageBag());
    }

    /**
     * Set the URL's to be used in the views
     * @return void
     */
    protected function setHandyUrls()
    {
        $this->objectUrl = is_null($this->objectUrl) ? url($this->urlSegment.'/'.$this->appName) : null;
        $this->updateUrl = is_null($this->updateUrl) ? $this->objectUrl.'/update/' : null;
        $this->createUrl = is_null($this->createUrl) ? $this->objectUrl.'/create' : null;
        $this->deleteUrl = is_null($this->deleteUrl) ? $this->objectUrl.'/delete/' : null;
        $this->sortUrl   = is_null($this->sortUrl)   ? $this->objectUrl.'/sort/' : null;
    }

    /**
     * Set the view to have variables detailing
     * some of the key URL's used in the views
     * Trying to keep views generic...
     * @return void
     */
    protected function shareHandyUrls()
    {
        // Share these variables with any views
        View::share('objectUrl', $this->objectUrl);
        View::share('createUrl', $this->createUrl);
        View::share('updateUrl', $this->updateUrl);
        View::share('deleteUrl', $this->deleteUrl);
        View::share('sortUrl',   $this->sortUrl);
    }

}