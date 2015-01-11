<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Illuminate\Routing\Controller;
use Sharenjoy\Cmsharenjoy\User\Account;
use Sharenjoy\Cmsharenjoy\User\User;
use App, Sentry, Session, View, Config, Str;
use Response, Route, Request, Theme, Message, Setting;

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
    protected $accessUrl;

    /**
     * The brand name
     * @var string
     */
    protected $brandName;

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
    protected $onMethod;

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
        // Define 404 page
        App::missing(function($exception)
        {
            return Response::view('cmsharenjoy::unity.missing', array(), 404);
        });
        
        // Setup composed views and the variables that they require
        $this->beforeFilter('adminFilter', ['except' => $this->whitelist]);

        // csrf and include ajax
        $this->beforeFilter('csrfFilter', ['on' => 'post']);
    }

    protected function setCommonVariable()
    {
        // Achieve that segment
        $this->accessUrl = Config::get('cmsharenjoy::app.access_url');
        View::share('accessUrl', $this->accessUrl);
        
        // Get the action name
        $routeArray = Str::parseCallback(Route::currentRouteAction(), null);
        
        if (last($routeArray) != null)
        {
            // Remove 'controller' from the controller name.
            $controller = str_replace('Controller', '', class_basename(head($routeArray)));

            // Take out the method from the action.
            $action = str_replace(['get', 'post', 'patch', 'put', 'delete'], '', last($routeArray));

            // post, report
            $this->onController = strtolower($controller);
            Session::put('onController', $this->onController);

            // get-create, post-create
            $this->onMethod = Str::slug(Request::method(). '-' .$action);
            Session::put('onMethod', $this->onMethod);

            // create, update
            $this->onAction = strtolower($action);
            Session::put('onAction', $this->onAction);
        }

        // Get the login user
        if (Sentry::check())
        {
            $user = Sentry::getUser();
            Session::put('user', $user);
            View::share('user', $user);
        }

        // Brand name from setting
        $this->brandName = Setting::get('brand_name');
        
        // Share some variables to views
        View::share('brandName', $this->brandName);
        View::share('appName' , $this->onController);
        View::share('functionRules', $this->functionRules);
        View::share('langLocales', Config::get('cmsharenjoy::app.locales'));
        View::share('menu_items', Config::get('cmsharenjoy::app.menu_items'));
        View::share('active_language', Session::get('sharenjoy.backEndLanguage'));

        // $composed_views = array('cmsharenjoy::*');
        // View::composer($composed_views, 'Sharenjoy\Cmsharenjoy\Composers\Page');

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
        $this->objectUrl = is_null($this->objectUrl) ? url($this->accessUrl.'/'.$this->onController) : null;
        $this->updateUrl = is_null($this->updateUrl) ? $this->objectUrl.'/update/' : null;
        $this->createUrl = is_null($this->createUrl) ? $this->objectUrl.'/create' : null;
        $this->deleteUrl = is_null($this->deleteUrl) ? $this->objectUrl.'/delete/' : null;
        $this->sortUrl   = is_null($this->sortUrl)   ? $this->objectUrl.'/sort' : null;
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

    /**
     * Setting the output layout priority
     * @return view
     */
    protected function setupLayout()
    {
        $commonLayout = Config::get('cmsharenjoy::app.commonLayoutDirectory');
        
        $pathA = $this->onController.'.'.$this->onAction;
        $pathB = $commonLayout.'.'.$this->onAction;

        if (View::exists($this->accessUrl.'.'.$pathA))
        {
            $this->layout = View::make($this->accessUrl.'.'.$pathA);
        }
        elseif (View::exists('cmsharenjoy::'.$pathA))
        {
            $this->layout = View::make('cmsharenjoy::'.$pathA);
        }
        elseif (View::exists($this->accessUrl.'.'.$pathB))
        {
            $this->layout = View::make($this->accessUrl.'.'.$pathB);
        }
        elseif (View::exists('cmsharenjoy::'.$pathB))
        {
            $this->layout = View::make('cmsharenjoy::'.$pathB);
        }
    }

}