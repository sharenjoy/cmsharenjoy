<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$accessUrl = session('accessUrl');

Route::group(['middleware' => ['web']], function () use ($accessUrl) {

    // Backend
    Route::group(['prefix' => $accessUrl], function() use ($accessUrl) {

        // content language
        Route::get('specify-content-language/{lang}', function($lang) use ($accessUrl) {
            session()->put('cmsharenjoy.language', $lang);
            return redirect($accessUrl);
        });

        Route::get('/'                , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@getIndex');
        Route::get('login'            , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@getLogin');
        Route::post('login'           , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@postLogin');
        Route::get('logout'           , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@getLogout');
        Route::get('remind-password'  , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@getRemindPassword');
        Route::post('remind-password' , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@postRemindPassword');
        Route::get('resetpassword/{code}'    , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@getResetPassword');
        Route::post('resetpassword'   , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController@postResetPassword');

        Route::group(['prefix' => 'filer'], function() {
            Route::get(''                       , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getIndex');
            Route::get('index/{parentId}'       , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getIndex');
            Route::get('filemanager'            , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getFilemanager');
            Route::get('filemanager/{parentId}' , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getFilemanager');
            Route::get('ckeditor'               , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getCkeditor');
            Route::get('ckeditor/{parentId}'    , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getCkeditor');
            Route::get('filealbum'              , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getFilealbum');
            Route::get('filealbum/{parentId}'   , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@getFilealbum');
            Route::post('newfolder/{toWhere}'   , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postNewfolder');
            Route::post('order'                 , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postOrder');
            Route::post('upload'                , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postUpload');
            Route::post('updatefile/{toWhere}'  , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postUpdatefile');
            Route::post('deletefile'            , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postDeletefile');
            Route::post('deletefolder'          , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postDeletefolder');
            Route::post('find'                  , 'Sharenjoy\Cmsharenjoy\Filer\FilerController@postFind');
        });

        Route::group(['prefix' => 'user'], function() {
            Route::get('/'              , 'Sharenjoy\Cmsharenjoy\User\UserController@getIndex');
            Route::get('sort'           , 'Sharenjoy\Cmsharenjoy\User\UserController@getSort');
            Route::get('create'         , 'Sharenjoy\Cmsharenjoy\User\UserController@getCreate');
            Route::get('update/{id}'    , 'Sharenjoy\Cmsharenjoy\User\UserController@getUpdate');
            Route::post('create'        , 'Sharenjoy\Cmsharenjoy\User\UserController@postCreate');
            Route::post('update/{id}'   , 'Sharenjoy\Cmsharenjoy\User\UserController@postUpdate');
            Route::post('delete'        , 'Sharenjoy\Cmsharenjoy\User\UserController@postDelete');
            Route::post('deleteconfirm' , 'Sharenjoy\Cmsharenjoy\User\UserController@postDeleteconfirm');
            Route::post('order'         , 'Sharenjoy\Cmsharenjoy\User\UserController@postOrder');
            Route::get('remindpassword/{id}' , 'Sharenjoy\Cmsharenjoy\User\UserController@getRemindpassword');
        });

        Route::group(['prefix' => 'setting'], function() {
            Route::get('/'      , 'Sharenjoy\Cmsharenjoy\Setting\SettingController@getIndex');
            Route::post('store' , 'Sharenjoy\Cmsharenjoy\Setting\SettingController@postStore');
        });
    });

    Route::get('language/{lang}' , function ($lang) {
        if (array_key_exists($lang, config('cmsharenjoy.locales'))) {
            session()->put('sharenjoy.backEndLanguage', $lang);
            return redirect()->back();
        }
    });

});