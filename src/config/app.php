<?php

/**
 * The application configuration file, used to setup globally used values throughout the application
 */
return array(

    /**
     * The name of the application, will be used in the main management areas of the application
     */
    'name' => 'Your Fantastic CMS',

    /**
     * The email address associated with support enquires on a technical basis
     */
    'support_email' => 'example@example.com',

    /**
     * The base path to put uploads into
     */
    'upload_base_path'=>'uploads/',

    /**
     * The URL key to access the main admin area
     */
    'access_url'=>'admin',

    /**
     * The array of language
     */
    'locales' => array('en', 'tw'),

    /**
     * The common directory of layout
     */
    'commonRepoLayoutDirectory' => 'repo',

    /**
     * The menu items shown at the top and side of the application
     */
    'menu_items'=>array(
        'post'=>array(
            'name'=>'Posts',
            'icon'=>'entypo-chart-bar',
            'top'=>true
        ),
        'category'=>array(
            'name' =>'Categories',
            'icon' =>'entypo-chart-bar',
            'top'  =>true,
            'sub'  => array(
                'category/index/product' => array(
                    'name' => 'Product Categories'
                ),
                'category/index/post' => array(
                    'name' => 'Post Categories'
                ),
            )
        ),
        'tag'=>array(
            'name'=>'Tags',
            'icon'=>'entypo-chart-bar',
            'top'=>true
        ),
        'gallery'=>array(
            'name'=>'Gallery',
            'icon'=>'entypo-chart-bar',
            'top'=>true
        ),
        'user'=>array(
            'name'=>'Users',
            'icon'=>'entypo-chart-bar',
            'top'=>true
        ),
        'setting'=>array(
            'name'=>'Settings',
            'icon'=>'entypo-chart-bar',
            'top'=>true
        )
    )
);
