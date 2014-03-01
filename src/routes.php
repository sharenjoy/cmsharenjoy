<?php


// Filter all requests ensuring a user is logged in when this filter is called
Route::filter('adminFilter', 'Sharenjoy\Cmsharenjoy\Filters\Admin');
// Filter language what used
Route::filter('localeFilter', 'Sharenjoy\Cmsharenjoy\Filters\Locale');


/**
 * Route setting
 */

Route::controller( $urlSegment.'/users'     , 'Sharenjoy\Cmsharenjoy\Controllers\UsersController' );
Route::controller( $urlSegment.'/galleries' , 'Sharenjoy\Cmsharenjoy\Controllers\GalleriesController' );
Route::controller( $urlSegment.'/settings'  , 'Sharenjoy\Cmsharenjoy\Controllers\SettingsController' );
Route::controller( $urlSegment.'/blocks'    , 'Sharenjoy\Cmsharenjoy\Controllers\BlocksController' );
Route::controller( $urlSegment.'/posts'     , 'Sharenjoy\Cmsharenjoy\Controllers\PostsController' );
Route::controller( $urlSegment              , 'Sharenjoy\Cmsharenjoy\Controllers\DashController'  );