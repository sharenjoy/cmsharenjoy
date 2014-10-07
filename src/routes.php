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


Route::group(['prefix' => $urlSegment], function()
{
    Route::controller('test'    , 'Sharenjoy\Cmsharenjoy\Controllers\TestableController');

    Route::controller('user'    , 'Sharenjoy\Cmsharenjoy\User\UserController');
    Route::controller('member'  , 'Sharenjoy\Cmsharenjoy\Repo\Member\MemberController');
    Route::controller('order'   , 'Sharenjoy\Cmsharenjoy\Repo\Order\OrderController');
    Route::controller('filer'   , 'Sharenjoy\Cmsharenjoy\Filer\FilerController');
    Route::controller('setting' , 'Sharenjoy\Cmsharenjoy\Setting\SettingController');
    Route::controller('product' , 'Sharenjoy\Cmsharenjoy\Repo\Product\ProductController');
    Route::controller('qna'     , 'Sharenjoy\Cmsharenjoy\Repo\Qna\QnaController');
    Route::controller('report'  , 'Sharenjoy\Cmsharenjoy\Repo\Report\ReportController');
    Route::controller('tag'     , 'Sharenjoy\Cmsharenjoy\Repo\Tag\TagController');
    Route::controller('post'    , 'Sharenjoy\Cmsharenjoy\Repo\Post\PostController');
    Route::controller('category', 'Sharenjoy\Cmsharenjoy\Repo\Category\CategoryController');
    // Route::controller(''        , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');
});

