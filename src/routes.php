<?php

/**
 * Route setting
 */

/* ====================================== */

Route::get($urlSegment.'/language/{lang}' , function($lang)
{
    if (array_key_exists($lang, Config::get('cmsharenjoy::app.locales')))
    {
        Session::put('sharenjoy.backEndLanguage', $lang);
        return Redirect::back();
    }
});

Route::controller($urlSegment.'/test'     , 'Sharenjoy\Cmsharenjoy\Controllers\TestableController');


Route::controller($urlSegment.'/user'     , 'Sharenjoy\Cmsharenjoy\User\UserController');
Route::controller($urlSegment.'/member'   , 'Sharenjoy\Cmsharenjoy\Repo\Member\MemberController');
Route::controller($urlSegment.'/order'    , 'Sharenjoy\Cmsharenjoy\Repo\Order\OrderController');
Route::controller($urlSegment.'/filer'    , 'Sharenjoy\Cmsharenjoy\Filer\FilerController');
Route::controller($urlSegment.'/setting'  , 'Sharenjoy\Cmsharenjoy\Setting\SettingController');
Route::controller($urlSegment.'/product'  , 'Sharenjoy\Cmsharenjoy\Repo\Product\ProductController');
Route::controller($urlSegment.'/qna'      , 'Sharenjoy\Cmsharenjoy\Repo\Qna\QnaController');
Route::controller($urlSegment.'/report'   , 'Sharenjoy\Cmsharenjoy\Repo\Report\ReportController');
Route::controller($urlSegment.'/tag'      , 'Sharenjoy\Cmsharenjoy\Repo\Tag\TagController');
Route::controller($urlSegment.'/post'     , 'Sharenjoy\Cmsharenjoy\Repo\Post\PostController');
Route::controller($urlSegment.'/category' , 'Sharenjoy\Cmsharenjoy\Repo\Category\CategoryController');
Route::controller($urlSegment             , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');
