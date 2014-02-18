<?php

// Get the URL segment to use for routing
$urlSegment = Config::get('cmsharenjoy::app.access_url');

// Filter all requests ensuring a user is logged in when this filter is called
Route::filter('adminFilter', 'Sharenjoy\Cmsharenjoy\Filters\Admin');

Route::filter('localeFilter', 'Sharenjoy\Cmsharenjoy\Filters\Locale');

Route::controller( $urlSegment.'/users'     , 'Sharenjoy\Cmsharenjoy\Controllers\UsersController' );
Route::controller( $urlSegment.'/galleries' , 'Sharenjoy\Cmsharenjoy\Controllers\GalleriesController' );
Route::controller( $urlSegment.'/settings'  , 'Sharenjoy\Cmsharenjoy\Controllers\SettingsController' );
Route::controller( $urlSegment.'/blocks'    , 'Sharenjoy\Cmsharenjoy\Controllers\BlocksController' );
Route::controller( $urlSegment.'/posts'     , 'Sharenjoy\Cmsharenjoy\Controllers\PostsController' );
Route::controller( $urlSegment              , 'Sharenjoy\Cmsharenjoy\Controllers\DashController'  );

/** Include IOC Bindings **/
include __DIR__.'/bindings.php';