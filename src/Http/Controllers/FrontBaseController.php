<?php namespace Sharenjoy\Cmsharenjoy\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Auth, Route, Request, Theme, Message, Setting;

abstract class FrontBaseController extends Controller {

    use DispatchesCommands, ValidatesRequests, AppNamespaceDetectorTrait;

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
    protected $onMethod;

    /**
     * The action active right away
     * @var string
     */
    protected $onAction;

    /**
     * The theme instancd
     * @var object
     */
    protected $theme;

    /**
     * The login member
     * @var object
     */
    protected $member;

    /**
     * Initializer.
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        $this->setCommonVariable();
        $this->getAuthInfo();
    }

    protected function setCommonVariable()
    {
        // Achieve that segment
        $this->accessUrl = config('cmsharenjoy.access_url');
        
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
            session()->put('onController', $this->onController);
            view()->share('onController', $this->onController);

            // get-create, post-create
            $this->onMethod = Str::slug(Request::method(). '-' .$action);
            session()->put('onMethod', $this->onMethod);
            view()->share('onMethod', $this->onMethod);

            // create, update
            $this->onAction = strtolower($action);
            session()->put('onAction', $this->onAction);
            view()->share('onAction', $this->onAction);
        }

        // Brand name from setting
        $this->brandName = Setting::get('brand_name');
        
        // Share some variables to views
        view()->share('brandName'     , $this->brandName);
        view()->share('functionRules' , $this->functionRules);
        view()->share('langLocales'   , config('cmsharenjoy.locales'));
        view()->share('activeLanguage', session('sharenjoy.backEndLanguage'));

        // Set the theme
        $this->theme = Theme::uses('front');

        // Message
        view()->share('messages', Message::getMessageBag());
    }

    protected function getAuthInfo()
    {
        // Get the login member
        if (Auth::check())
        {
            $this->member = Auth::user();

            session()->put('member', $this->member->toArray());
            view()->share('member', $this->member);
        }
    }

    protected function getModuleNamespace()
    {
        return $this->getAppNamespace().config('cmsharenjoy.moduleNamespace');
    }

    /**
     * Setting the output layout priority
     * @return view
     */
    protected function layout()
    {
        $action = $this->onAction;

        // If action equat sort so that set the action to index
        $action = $this->onMethod == 'get-sort' ? 'index' : $action;
        
        // Get reop directory from config
        $commonLayout = config('cmsharenjoy.commonLayoutDirectory');
        
        $pathA = $this->onController.'.'.$action;
        $pathB = $commonLayout.'.'.$action;

        // resources/views/member/create
        if (view()->exists($pathA))
        {
            return view($pathA);
        }

        // resources/views/create
        if (view()->exists($action))
        {
            return view($action);
        }

        // organization/views/member/create
        if (view()->exists($this->onPackage.'::'.$pathA))
        {
            return view($this->onPackage.'::'.$pathA);
        }
        
        // organization/views/common/create
        if (view()->exists($this->onPackage.'::'.$pathB))
        {
            return view($this->onPackage.'::'.$pathB);
        }
        
        // resources/views/common/create
        if (view()->exists($pathB))
        {
            return view($pathB);
        }
        
    }

}