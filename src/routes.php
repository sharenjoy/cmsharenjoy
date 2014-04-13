<?php

/**
 * Route setting
 */

// Confide routes
// Route::get( 'users/create',                 'UserController@create');
// Route::post('users',                        'UserController@store');
// Route::get( 'users/login',                  'UserController@login');
// Route::post('users/login',                  'UserController@do_login');
// Route::get( 'users/confirm/{code}',         'UserController@confirm');
// Route::get( 'users/forgot_password',        'UserController@forgot_password');
// Route::post('users/forgot_password',        'UserController@do_forgot_password');
// Route::get( 'users/reset_password/{token}', 'UserController@reset_password');
// Route::post('users/reset_password',         'UserController@do_reset_password');
// Route::get( 'users/logout',                 'UserController@logout');

Route::controller($urlSegment.'/user'     , 'Sharenjoy\Cmsharenjoy\Controllers\UserController');
Route::controller($urlSegment.'/gallery'  , 'Sharenjoy\Cmsharenjoy\Controllers\GalleryController');
Route::controller($urlSegment.'/setting'  , 'Sharenjoy\Cmsharenjoy\Controllers\SettingController');
Route::controller($urlSegment.'/tag'      , 'Sharenjoy\Cmsharenjoy\Controllers\TagController');
Route::controller($urlSegment.'/post'     , 'Sharenjoy\Cmsharenjoy\Controllers\PostController');
Route::controller($urlSegment.'/category' , 'Sharenjoy\Cmsharenjoy\Controllers\CategoryController');
Route::controller($urlSegment             , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');