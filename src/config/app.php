<?php

/**
 * The application configuration file, used to setup 
 * globally used values throughout the application
 */
return [

    /**
     * The key defines the url of
     * access of the main admin area
     */
    'access_url' => 'admin',

    /**
     * The array of language
     */
    'locales' => [
        'zh-TW' => '繁體中文',
        // 'en'    => 'English',
        // 'zh-CN' => '簡體中文',
        // 'ja'    => '日文',
    ],

    /**
     * The path and size of logo
     */
    'logo' => [
        'favicon' => [
            'path' => 'img/favicon.ico',
            'width' => '16',
        ],
        'login' => [
            'path' => 'img/logo.png',
            'width' => '160',
        ],
        'index' => [
            'path' => 'img/logo.png',
            'width' => '100',
        ]
    ],

    /**
     * The common directory of layout
     */
    'commonLayoutDirectory' => 'common',

    /**
     * The item amount of page
     */
    'paginationCount' => 15,

    /**
     * The menu items shown at the top
     * and side of the application
     */
    'menu_items' => [
        'post' => [
            'name' => 'app.menu.post',
            'icon' => 'entypo-doc-text',
        ],
        'category' => [
            'name' =>'app.menu.category',
            'icon' =>'entypo-flow-tree',
            'sub'  => [
                'category/index/product' => [
                    'name' => 'app.menu.product_category'
                ],
            ]
        ],
        'tag' => [
            'name' => 'app.menu.tag',
            'icon' => 'entypo-tag',
        ],
        'filer' => [
            'name' => 'app.menu.file',
            'icon' => 'entypo-box',
        ],
        'user' => [
            'name' => 'app.menu.user',
            'icon' => 'entypo-key',
        ],
        'setting' => [
            'name' => 'app.menu.setting',
            'icon' => 'entypo-cog',
        ]
    ],
    
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
