<?php

/**
 * The application configuration file, used to setup globally used values throughout the application
 */
return array(

    /**
     * The name of the application, will be used in the main management areas of the application
     */
    'name' => 'CMS',

    /**
     * The email address associated with support enquires on a technical basis
     */
    'support_email' => '',

    /**
     * The URL key to access the main admin area
     */
    'access_url' => 'admin',

    /**
     * The array of language
     */
    'locales' => array(
        'zh-TW' => '繁體中文',
        'en'    => '英文',
        // 'zh-CN' => '簡體中文',
        // 'ja'    => '日文',
    ),

    /**
     * The common directory of layout
     */
    'commonRepoLayoutDirectory' => 'repo',

    /**
     * The menu items shown at the top and side of the application
     */
    'menu_items' => array(
        'post' => array(
            'name' => 'menu.post',
            'icon' => 'entypo-doc-text',
        ),
        'category' => array(
            'name' =>'menu.category',
            'icon' =>'entypo-flow-tree',
            'sub'  => array(
                'category/index/product' => array(
                    'name' => 'menu.product_category'
                ),
                'category/index/post' => array(
                    'name' => 'menu.post_category'
                ),
            )
        ),
        'tag' => array(
            'name' => 'menu.tag',
            'icon' => 'entypo-tag',
        ),
        'filer' => array(
            'name' => 'menu.file',
            'icon' => 'entypo-box',
        ),
        'member' => array(
            'name' => 'menu.member',
            'icon' => 'entypo-users',
        ),
        'user' => array(
            'name' => 'menu.user',
            'icon' => 'entypo-key',
        ),
        'setting' => array(
            'name' => 'menu.setting',
            'icon' => 'entypo-cog',
        )
    )
);
