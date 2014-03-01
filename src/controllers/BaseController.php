<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Illuminate\Routing\Controller;
use View, Config;

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
     * The URL to get the root of this object ( /admin/posts for example )
     * @var string
     */
    protected $objectUrl;

    /**
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        // Setup composed views and the variables that they require
        $this->beforeFilter('adminFilter' , array('except' => $this->whitelist));

        // This is a filter which configure the language
        $this->beforeFilter('localeFilter');

        // Achieve that segment
        $this->urlSegment = Config::get('cmsharenjoy::app.access_url');

        if(is_null($this->objectUrl))
        {
            $this->objectUrl = url($this->urlSegment.'/'.$this->appName);
        }

        // Share some variables to views
        View::share('appName' , $this->appName);
        View::share('objectUrl', $this->objectUrl);
        $composed_views = array( 'cmsharenjoy::*' );
        View::composer($composed_views, 'Sharenjoy\Cmsharenjoy\Composers\Page');
    }

}