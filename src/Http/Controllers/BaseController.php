<?php namespace Sharenjoy\Cmsharenjoy\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Sharenjoy\Cmsharenjoy\User\User;
use Illuminate\Support\Str;
use Sentry, Route, Request, Theme, Message, Setting;

abstract class BaseController extends Controller {

    use DispatchesCommands, ValidatesRequests;
    
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
     * The URL to preview a new entry
     * @var string
     */
    protected $previewUrl;

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
     * The package active right away
     * @var string
     */
    protected $onPackage;

    /**
     * The theme instancd
     * @var object
     */
    protected $theme;

    /**
     * The login user
     * @var object
     */
    protected $user;

    /**
     * Initializer.
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        $this->middleware('admin.auth');

        $this->setCommonVariable();
        $this->getAuthInfo();
        $this->setPackageInfo();
        $this->setHandyUrls();
        $this->parseMenuItems();
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
        $this->theme = Theme::uses('admin');

        // Message
        view()->share('messages', Message::getMessageBag());
    }

    protected function getAuthInfo()
    {
        // Get the login user
        if (Sentry::check())
        {
            $this->user = Sentry::getUser();

            session()->put('user', $this->user->toArray());
            view()->share('user', $this->user->toArray());
        }
    }

    protected function getPackageInfo()
    {
        return 'cmsharenjoy';
    }

    protected function setPackageInfo()
    {
        $this->onPackage = $this->getPackageInfo();
        
        // active package
        session()->put('onPackage', $this->onPackage);
        view()->share('onPackage', $this->onPackage);
    }

    /**
     * Set the URL's to be used in the views
     * @return void
     */
    protected function setHandyUrls()
    {
        $this->objectUrl  = is_null($this->objectUrl)  ? url($this->accessUrl.'/'.$this->onController) : null;
        $this->previewUrl = is_null($this->previewUrl) ? $this->objectUrl.'/preview/' : null;
        $this->createUrl  = is_null($this->createUrl)  ? $this->objectUrl.'/create' : null;
        $this->updateUrl  = is_null($this->updateUrl)  ? $this->objectUrl.'/update/' : null;
        $this->deleteUrl  = is_null($this->deleteUrl)  ? $this->objectUrl.'/delete/' : null;
        $this->sortUrl    = is_null($this->sortUrl)    ? $this->objectUrl.'/sort' : null;

        // Share these variables with any views
        view()->share('accessUrl', $this->accessUrl);
        session()->put('accessUrl', $this->accessUrl);

        view()->share('objectUrl', $this->objectUrl);
        session()->put('objectUrl', $this->objectUrl);

        view()->share('previewUrl', $this->previewUrl);
        view()->share('createUrl', $this->createUrl);
        view()->share('updateUrl', $this->updateUrl);
        view()->share('deleteUrl', $this->deleteUrl);
        view()->share('sortUrl',   $this->sortUrl);
    }

    /**
     * To parse the menu which one is actived
     * @return View share variable
     */
    protected function parseMenuItems()
    {
        $menuItems = config('cmsharenjoy.menu_items');
        $masterMenu = null;
        $subMenu = null;

        foreach ($menuItems as $url => $items)
        {
            if (isset($items['sub']))
            {
                foreach ($items['sub'] as $suburl => $item)
                {
                    if (strpos($suburl, '?'))
                    {
                        // earse behind and include of the '?'
                        $suburl = substr_replace($suburl, '', strpos($suburl, '?'));
                    }

                    if (Request::is("$this->accessUrl/$suburl*"))
                    {
                        $masterMenu = $url;
                        $subMenu = $suburl;
                    }
                }
            }
            else
            {
                if (strpos($url, '?'))
                {
                    $url = substr_replace($url, '', strpos($url, '?'));
                }

                if (Request::is("$this->accessUrl/$url*"))
                {
                    $masterMenu = $url;
                }
            }
        }

        view()->share('masterMenu', $masterMenu);
        view()->share('subMenu', $subMenu);
        view()->share('menuItems', $menuItems);
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

        // resources/views/admin/member/create
        if (view()->exists($this->accessUrl.'.'.$pathA))
        {
            return view($this->accessUrl.'.'.$pathA);
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
        
        // resources/views/admin/common/create
        if (view()->exists($this->accessUrl.'.'.$pathB))
        {
            return view($this->accessUrl.'.'.$pathB);
        }
        
    }

}