<?php

/**
 * To register some filters
 */

/* ====================================== */


// Filter all requests ensuring a user is logged in when this filter is called
Route::filter('adminFilter', function() use ($urlSegment)
{
    if ( ! Sentry::check()) return Redirect::to($urlSegment.'/login');
});

// Filter cache
Route::filter('cache.fetch', 'Sharenjoy\Cmsharenjoy\Filters\CacheFilter@fetch');
Route::filter('cache.put'  , 'Sharenjoy\Cmsharenjoy\Filters\CacheFilter@put');

/**
 * To overwrite app/filter.php csrf filter also include ajax
 * It needs to add mate tag and jquerySetup to view, It like following
 * <meta name="csrf-token" content="{{csrf_token()}}">
 * $.ajaxSetup({
 *     headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'); }
 * });
 */
Route::filter('csrfFilter', function() use ($urlSegment)
{
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
    
    if (Session::token() != $token)
    {
        if ($this->whichEnd == 'backEnd')
        {
            Message::error('可能閒置太久，或是某些地方發生錯誤囉！');
            return Redirect::to($urlSegment.'/login');            
        }
        else
        {
            Message::error('某些地方發生錯誤囉！');
            return Redirect::to('/'); 
        }
    }
});

