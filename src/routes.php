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
    Route::controller('test'      , 'Sharenjoy\Cmsharenjoy\Controllers\TestableController');

    Route::controller('post'      , 'Sharenjoy\Cmsharenjoy\Modules\Post\PostController');
    Route::controller('tag'       , 'Sharenjoy\Cmsharenjoy\Modules\Tag\TagController');
    Route::controller('category'  , 'Sharenjoy\Cmsharenjoy\Modules\Category\CategoryController');
    Route::controller('filer'     , 'Sharenjoy\Cmsharenjoy\Filer\FilerController');
    Route::controller('user'      , 'Sharenjoy\Cmsharenjoy\User\UserController');
    Route::controller('setting'   , 'Sharenjoy\Cmsharenjoy\Setting\SettingController');
});

