<?php

/**
 * To register some filter
 */

// Filter all requests ensuring a user is logged in when this filter is called
Route::filter('adminFilter', 'Sharenjoy\Cmsharenjoy\Filters\Admin');

// To overwrite app/filter.php csrf filter also include ajax
// It needs to add mate tag and jquerySetup to view, It like following
// <meta name="csrf-token" content="{{csrf_token()}}">
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content');
//     }
// });
Route::filter('csrfFilter', function()
{
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');

    if (Session::token() != $token)
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});