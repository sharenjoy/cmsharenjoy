<?php namespace Sharenjoy\Cmsharenjoy\Front\Controllers;

use Illuminate\Routing\Controller;
use App, Auth, Session, View, Str, Route;
use Response, Request, Theme, Message, Setting;

abstract class FrontBaseController extends Controller {

    /**
     * The brand name
     * @var string
     */
    protected $brandName;

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
     * The action active right away
     * @var string
     */
    protected $doAction;

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
    }

    protected function filterProcess()
    {
        // Define 404 page
        App::missing(function($exception)
        {
            return Response::view('errors.missing', array(), 404);
        });

        // csrf and include ajax
        $this->beforeFilter('csrfFilter', array('on' => 'post'));
    }

    protected function setCommonVariable()
    {
        // Get the action name
        $routeArray = Str::parseCallback(Route::currentRouteAction(), null);
        
        if (last($routeArray) != null)
        {
            // Remove 'controller' from the controller name.
            $controller = str_replace('Controller', '', class_basename(head($routeArray)));

            // Take out the method from the action.
            $action = str_replace(array('get', 'post', 'patch', 'put', 'delete'), '', last($routeArray));

            // post, report
            $this->onController = strtolower($controller);
            Session::put('onController', $this->onController);

            // get-create, post-create
            $this->onAction = Str::slug(Request::method(). '-' .$action);
            Session::put('onAction', $this->onAction);

            // create, update
            $this->doAction = strtolower($action);
            Session::put('doAction', $this->doAction);
        }

        // Get the login user
        if (Auth::check())
        {
            $member = Auth::user();
            Session::put('member', $member->toArray());
            View::share('member', $member);
        }

        // Brand name from setting
        $this->brandName = Setting::get('brand_name');
        View::share('brandName', $this->brandName);
        
        // Set the theme
        $this->theme = Theme::uses('front');

        // Message
        View::share('messages', Message::getMessageBag());
    }

    /**
     * This is the order that show layout
     * 1. view/member/create.blade.php
     * 2. view/member-index.blade.php
     * 3. view/member.blade.php (action = index)
     * 4. view/create.blade.php
     * @return View
     */
    protected function setupLayout()
    {
        if (View::exists($this->onController.'.'.$this->doAction))
        {
            $this->layout = View::make($this->onController.'.'.$this->doAction);
        }
        elseif (View::exists($this->onController.'-'.$this->doAction))
        {
            $this->layout = View::make($this->onController.'-'.$this->doAction);
        }
        elseif ($this->doAction == 'index' && View::exists($this->onController))
        {
            $this->layout = View::make($this->onController);
        }
        elseif (View::exists($this->doAction))
        {
            $this->layout = View::make($this->doAction);
        }
    }

    /**
     * Handle dynamic method calls
     * @param string $name
     * @param array $args
     */
    // public function __call($name, $args)
    // {
    //     $args = empty($args) ? [] : $args[0];
    //     $action = 'get'.$name;
    //     \Debugbar::info($name);
    //     if ($this->layout == null)
    //     {
    //         App::abort(404);
    //     }

    //     return Redirect::to($this->object);
    // }

}