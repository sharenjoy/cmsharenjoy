<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Illuminate\Routing\Controller;
use Sharenjoy\Cmsharenjoy\User\Account;
use Sharenjoy\Cmsharenjoy\User\User;
use App, Sentry, Session, View, Config, Str;
use Route, Response, Request, Theme, Message, Setting;

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
        \Debugbar::disable();
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

            $this->onController = strtolower($controller);
            $this->onAction = Str::slug(Request::method(). '-' .$action);
            $this->doAction = strtolower($action);
            Session::put('onController', $this->onController);
            Session::put('onAction', $this->onAction);
            Session::put('doAction', $this->doAction);
        }

        // Get the login user
        $user = Sentry::getUser();
        if ($user)
        {
            // Debugbar::info($user->account()->getParentKey());
            Session::put('user', $user);
            View::share('user', $user);
        }

        // Brand name from setting
        $this->brandName = Setting::get('brand_name');
        
        // Set the theme
        $this->theme = Theme::uses('front');

        // Share some variables to views
        View::share('brandName', $this->brandName);

        // Message
        View::share('messages', Message::getMessageBag());
    }

    protected function setupLayout()
    {   
        if (View::exists($this->onController.'.'.$this->doAction))
        {
            $this->layout = View::make($this->onController.'.'.$this->doAction);
        }
        elseif (View::exists($this->doAction))
        {
            $this->layout = View::make($this->doAction);
        }
        else
        {
            throw new \Sharenjoy\Cmsharenjoy\Exception\ViewNotFoundException;
        }
    }

}