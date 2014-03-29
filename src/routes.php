<?php

/**
 * Route setting
 */

Route::controller($urlSegment.'/users'     , 'Sharenjoy\Cmsharenjoy\Controllers\UsersController');
Route::controller($urlSegment.'/galleries' , 'Sharenjoy\Cmsharenjoy\Controllers\GalleriesController');
Route::controller($urlSegment.'/settings'  , 'Sharenjoy\Cmsharenjoy\Controllers\SettingsController');
Route::controller($urlSegment.'/tags'      , 'Sharenjoy\Cmsharenjoy\Controllers\TagController');
Route::controller($urlSegment.'/posts'     , 'Sharenjoy\Cmsharenjoy\Controllers\PostController');
Route::controller($urlSegment              , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');