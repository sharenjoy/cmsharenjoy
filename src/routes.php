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

    Route::controller('tag'     , 'Sharenjoy\Cmsharenjoy\Repo\Tag\TagController');
    Route::controller('user'    , 'Sharenjoy\Cmsharenjoy\User\UserController');
    Route::controller('filer'   , 'Sharenjoy\Cmsharenjoy\Filer\FilerController');
    Route::controller('setting' , 'Sharenjoy\Cmsharenjoy\Setting\SettingController');
    Route::controller('category', 'Sharenjoy\Cmsharenjoy\Repo\Category\CategoryController');
    // Route::controller(''        , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');
});

