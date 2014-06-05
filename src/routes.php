<?php

/**
 * Route setting
 */

Route::controller($urlSegment.'/user'     , 'Sharenjoy\Cmsharenjoy\Controllers\UserController');
Route::controller($urlSegment.'/member'   , 'Sharenjoy\Cmsharenjoy\Controllers\MemberController');
Route::controller($urlSegment.'/order'    , 'Sharenjoy\Cmsharenjoy\Controllers\OrderController');
Route::controller($urlSegment.'/filer'    , 'Sharenjoy\Cmsharenjoy\Controllers\FilerController');
Route::controller($urlSegment.'/setting'  , 'Sharenjoy\Cmsharenjoy\Controllers\SettingController');
Route::controller($urlSegment.'/product'  , 'Sharenjoy\Cmsharenjoy\Controllers\ProductController');
Route::controller($urlSegment.'/report'   , 'Sharenjoy\Cmsharenjoy\Controllers\ReportController');
Route::controller($urlSegment.'/category' , 'Sharenjoy\Cmsharenjoy\Controllers\CategoryController');
Route::controller($urlSegment             , 'Sharenjoy\Cmsharenjoy\Controllers\DashController');