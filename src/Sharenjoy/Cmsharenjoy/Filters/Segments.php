<?php namespace Sharenjoy\Cmsharenjoy\Filters;

use Config, Redirect, Request, Session;

class Segments {

    /**
     * If the user is not logged in then we need to get them outta here.
     * @return mixed
     */
    public function filter()
    {
        $segment = Request::segment(2);

    }

}