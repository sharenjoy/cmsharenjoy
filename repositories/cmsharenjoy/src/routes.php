<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Backend
Route::group(['prefix' => 'admin'], function()
{
    // Route::controller('customer'   , 'Axes\Modules\Customer\CustomerController');
    
    // Route::controller('flower'     , 'App\Flower\Controllers\FlowerController');
    
    // Route::controller('company'    , 'Axes\Modules\Organization\Company\CompanyController');
    // Route::controller('department' , 'Axes\Modules\Organization\Department\DepartmentController');
    // Route::controller('position'   , 'Axes\Modules\Organization\Position\PositionController');
    // Route::controller('division'   , 'Axes\Modules\Organization\Division\DivisionController');
    // Route::controller('role'       , 'Axes\Modules\Organization\Role\RoleController');
    // Route::controller('employee'   , 'Axes\Modules\Organization\Employee\EmployeeController');

    Route::controller('post'      , 'Sharenjoy\Cmsharenjoy\Modules\Post\PostController');
    Route::controller('tag'       , 'Sharenjoy\Cmsharenjoy\Modules\Tag\TagController');
    Route::controller('category'  , 'Sharenjoy\Cmsharenjoy\Modules\Category\CategoryController');
    Route::controller('filer'     , 'Sharenjoy\Cmsharenjoy\Filer\FilerController');
    Route::controller('user'      , 'Sharenjoy\Cmsharenjoy\User\UserController');
    Route::controller('setting'   , 'Sharenjoy\Cmsharenjoy\Setting\SettingController');
    Route::controller(''          , 'Sharenjoy\Cmsharenjoy\Http\Controllers\DashController');
});

Route::get('admin/language/{lang}' , function($lang)
{
    if (array_key_exists($lang, config('cmsharenjoy.locales')))
    {
        Session::put('sharenjoy.backEndLanguage', $lang);
        return redirect()->back();
    }
});