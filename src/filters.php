<?php

/**
 * To register filter
 */

// Filter all requests ensuring a user is logged in when this filter is called
Route::filter('adminFilter', 'Sharenjoy\Cmsharenjoy\Filters\Admin');