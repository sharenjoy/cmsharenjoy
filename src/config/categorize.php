<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Category Model
    |--------------------------------------------------------------------------
    |
    | When using the "eloquent" driver, we need to know which
    | Eloquent models should be used throughout Up.
    |
    */

    'categories' => array(

        'model' => 'Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category',
    ),

    /*
    |--------------------------------------------------------------------------
    | Category Hierarchy Model
    |--------------------------------------------------------------------------
    |
    | When using the "eloquent" driver, we need to know which
    | Eloquent models should be used throughout Up.
    |
    */

    'categoryHierarchy' => array(

        'model' => 'Sharenjoy\Cmsharenjoy\Service\Categorize\CategoryHierarchy\Hierarchy',
    ),

    /*
    |--------------------------------------------------------------------------
    | Category Relate Model
    |--------------------------------------------------------------------------
    |
    | When using the "eloquent" driver, we need to know which
    | Eloquent models should be used throughout Up.
    |
    */

    'CategoryRelates' => array(

        'model' => 'Sharenjoy\Cmsharenjoy\Service\Categorize\CategoryRelates\Relate',
    ),

);