<?php namespace Sharenjoy\Cmsharenjoy\Controllers;
use Illuminate\Routing\Controller;
use View, Config;

abstract class BaseController extends Controller{

    protected $whitelist = array();

    /**
     * The URL segment that can be used to access the system
     * @var string
     */
    protected $urlSegment;

    /**
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        // Achieve that segment
        $this->urlSegment = Config::get('cmsharenjoy::app.access_url');

        // Setup composed views and the variables that they require
        $this->beforeFilter( 'adminFilter' , array('except' => $this->whitelist) );

        // This is a filter which configure the language
        $this->beforeFilter( 'localeFilter' );

        $composed_views = array( 'cmsharenjoy::*' );
        View::composer($composed_views, 'Sharenjoy\Cmsharenjoy\Composers\Page');
    }

}