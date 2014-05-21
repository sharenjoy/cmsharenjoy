<?php

/**
 * Route setting
 */

Route::controller($urlSegment.'/user'     , 'Sharenjoy\Cmsharenjoy\Controllers\UserController');
Route::controller($urlSegment.'/member'   , 'Sharenjoy\Cmsharenjoy\Controllers\MemberController');
Route::controller($urlSegment.'/filer'    , 'Sharenjoy\Cmsharenjoy\Controllers\FilerController');
Route::controller($urlSegment.'/setting'  , 'Sharenjoy\Cmsharenjoy\Controllers\SettingController');
Route::controller($urlSegment.'/tag'      , 'Sharenjoy\Cmsharenjoy\Controllers\TagController');
Route::controller($urlSegment.'/post'     , 'Sharenjoy\Cmsharenjoy\Controllers\PostController');
Route::controller($urlSegment.'/category' , 'Sharenjoy\Cmsharenjoy\Controllers\CategoryController');
Route::controller($urlSegment             , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');