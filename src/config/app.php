<?php

/**
 * The application configuration file, used to setup 
 * globally used values throughout the application
 */
return [

    /**
     * The name of the application, will be used in 
     * the main management areas of the application
     */
    'name' => 'CMS',

    /**
     * The URL key to access the main admin area
     */
    'access_url' => 'admin',

    /**
     * The array of language
     */
    'locales' => array(
        'zh-TW' => '繁體中文',
        // 'en'    => 'English',
        // 'zh-CN' => '簡體中文',
        // 'ja'    => '日文',
    ),

    /**
     * The common directory of layout
     */
    'commonRepoLayoutDirectory' => 'repo',

    /**
     * The menu items shown at the top and side 
     * of the application
     */
    'menu_items' => array(
        'post' => array(
            'name' => 'cmsharenjoy::app.menu.post',
            'icon' => 'entypo-doc-text',
        ),
        'report' => array(
            'name' => 'cmsharenjoy::app.menu.report',
            'icon' => 'entypo-doc-text',
        ),
        'product' => array(
            'name' => 'cmsharenjoy::app.menu.product',
            'icon' => 'entypo-feather',
        ),
        'category' => array(
            'name' =>'cmsharenjoy::app.menu.category',
            'icon' =>'entypo-flow-tree',
            'sub'  => array(
                'category/index/product' => array(
                    'name' => 'cmsharenjoy::app.menu.product_category'
                ),
            )
        ),
        'tag' => array(
            'name' => 'cmsharenjoy::app.menu.tag',
            'icon' => 'entypo-tag',
        ),
        'qna' => array(
            'name' => 'cmsharenjoy::app.menu.qna',
            'icon' => 'entypo-lamp',
        ),
        'filer' => array(
            'name' => 'cmsharenjoy::app.menu.file',
            'icon' => 'entypo-box',
        ),
        'order' => array(
            'name' => 'cmsharenjoy::app.menu.order',
            'icon' => 'entypo-basket',
        ),
        'member' => array(
            'name' => 'cmsharenjoy::app.menu.member',
            'icon' => 'entypo-users',
        ),
        'user' => array(
            'name' => 'cmsharenjoy::app.menu.user',
            'icon' => 'entypo-key',
        ),
        'setting' => array(
            'name' => 'cmsharenjoy::app.menu.setting',
            'icon' => 'entypo-cog',
        )
    ),
    
    /**
     * This configuration file is used to setup 
     * the initial user and seed to the database
     */
    'administrator' => [
        'email'         => 'example@example.com',
        'name'          => 'Example',
        'password'      => 'password',
        'phone'         => '0939999999',
    ],

];
