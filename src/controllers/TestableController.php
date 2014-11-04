<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use App, Redirect, Request, Response, View;

class TestableController extends BaseController {

    public function getIndex()
    {
        $tag = \Sharenjoy\Cmsharenjoy\Modules\Tag\Tag::withAllRelation()->first();

        ii($tag->toArray());




        return View::make('cmsharenjoy::testable');
    }

}