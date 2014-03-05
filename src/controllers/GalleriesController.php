<?php namespace Sharenjoy\Cmsharenjoy\Controllers;
use Sharenjoy\Cmsharenjoy\Galleries\GalleriesInterface;

class GalleriesController extends ObjectBaseController {

    /**
     * The place to find the views / URL keys for this controller
     * @var string
     */
    protected $view_key = 'galleries';

    /**
     * Construct Shit
     */
    public function __construct( GalleriesInterface $galleries )
    {
        $this->model = $galleries;
        parent::__construct();
    }

}