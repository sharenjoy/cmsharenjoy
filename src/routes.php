<?php

/**
 * Route setting
 */

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