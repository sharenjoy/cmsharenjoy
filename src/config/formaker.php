<?php

return [
	
    /*
     * --------------------------------------------------------------------------
     *  How to use
     * --------------------------------------------------------------------------
     * 
     * {{Formaker::title()}}
     * {{Formaker::email()}}
     * {{Formaker::url()}}
     * {{Formaker::description(['value' => 'This is value'])}}
     * {{Formaker::description(['type' => 'wysihtml5'])}}
     * {{Formaker::tag(['help' => 'This is tag', 'placeholder' => 'You can use "," sperate every tag'])}}
     * {{Formaker::language(['type' => 'select', 'value' => 'tw', 'option' => ['tw' => '中文', 'en' => '英文']])}}
     * {{Formaker::status(['type' => 'checkbox', 'value' => '1,2'])}}
     * {{Formaker::category(['type' => 'category', 'args' => ['category' => 'Product']])}}
     * {{Formaker::category_id(['args' => ['category' => 'News']])}}
     * {{Formaker::content(['input' => 'title'])}}  // If set input key the means is the data form title input
     * {{Formaker::delivery_time_zone_id(['type' => 'select', 'option' => 'delivery_time_zone'])}}
     * {{Formaker::keyword(['args' => ['data-filter' => 'title,title_jp,description']])}}
     * {{Formaker::category(['type' => 'select', 'lists' => 'category_lists'])}} // $this->model->category_lists();
     * 
     */
    
    /*
     * --------------------------------------------------------------------------
     *  Default Formaker Driver
     * --------------------------------------------------------------------------
     * 
     *  This option controls the formaker driver that will be utilized.
     * 
     *  Supported: 
     *  back-end: "bootstrap-v3"
     *  fore-end: "bootstrap-v3"
     * 
    */

    // back-end
    'driver-back'  => 'bootstrap-v3',

    // fore-end
    'driver-front' => 'bootstrap-v3',


    /**
     * Frequent input names can map
     * to their respective input types.
     *
     * This way, you may do FormField::description()
     * and it'll know to automatically set it as a textarea.
     * Otherwise, do FormField::thing(['type' = 'textarea'])
     *
     */
    'commonInputsLookup' =>
    [
        // For email
        'email'                 => 'email',
        'emailAddress'          => 'email',
        
        // For textarea
        'description'           => 'textarea',
        'bio'                   => 'textarea',
        'body'                  => 'textarea',
        
        // For wysiwyg
        'content'               => 'wysiwyg-advanced',
        
        // For password
        'password'              => 'password',
        'passwordConfirmation'  => 'password',
        'password_confirmation' => 'password',
        
        // For tag
        'tag'                   => 'tag',
        'related'               => 'tag',
        
        // For select
        'status'                => 'select',
        
        // For url
        'url'                   => 'url',
        'link'                  => 'url',
        
        // For image
        'img'                   => 'image',
        'img1'                  => 'image',
        'img2'                  => 'image',
        'img3'                  => 'image',
        'img4'                  => 'image',
        'img5'                  => 'image',
        'image'                 => 'image',
        'imagename'             => 'image',

        // For file
        'file'                  => 'file',
        'filename'              => 'file',

        // For album
        'album'                 => 'album',
        'filealbum'             => 'album',

        // For category
        'category_id'           => 'category',
    ],

];
