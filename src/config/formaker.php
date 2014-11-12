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
     * {{Formaker::description(['type' => 'wysiwyg-simple'])}}
     * {{Formaker::tag(['help' => 'This is tag', 'placeholder' => 'You can use "," to sperate every tag'])}}
     * {{Formaker::name(['args' => ['class'=>'options', 'id'=>'name']])}}
     * 
     * {{Formaker::status(['type' => 'checkbox', 'value' => '1,2'])}}
     * 
     * // If set key that the name is input the means the data form title input
     * {{Formaker::content(['input' => 'title'])}}
     *
     * // If set the key that the name is filter means will filter from the value
     * {{Formaker::keyword(['filter' => 'title,title_jp,description'])}}
     *
     * // The following setting is for select element option
     * {{Formaker::category_id([category' => 'News'])}}
     * {{Formaker::category_id([category' => 'News', 'pleaseSelect' => true])}}
     * {{Formaker::category(['type' => 'category', 'category' => 'Product'])}}
     * // The following setting will triger the method of Model $this->model->categoryLists();
     * {{Formaker::category(['type' => 'select', 'lists' => 'category_lists'])}}
     * {{Formaker::delivery_time_zone_id(['type' => 'select', 'option' => 'delivery_time_zone'])}}
     * {{Formaker::language(['type' => 'select', 'value' => 'tw', 'option' => ['tw'=>'中文', 'en'=>'英文']])}}
     * 
     */
    
    /*
     * --------------------------------------------------------------------------
     *  Default Formaker Driver
     * --------------------------------------------------------------------------
     * 
     *  This option controls the formaker driver that will be utilized.
     * 
     *  Supported: "TwitterBootstrapV3"
     * 
    */

    // back-end
    'driver-back'  => 'TwitterBootstrapV3',

    // fore-end
    'driver-front' => 'TwitterBootstrapV3',


    'loadingNamespace' => [
        'Sharenjoy\Cmsharenjoy\Service\Formaker\Inputs',
    ],


    'backend' => [
        'TwitterBootstrapV3' => [
            'class' => [
                'label'     => 'col-sm-2 control-label',
                'error'     => 'validate-has-error',
                'help'      => 'help-block',
                'input'     => 'form-control',
                'inner-div' => 'col-sm-7',
                'outer-div' => 'form-group',
                'filter'    => 'list-filter col-md-3 col-sm-6'
            ],
        ],
    ],

    'froentend' => [
        'TwitterBootstrapV3' => [
            
        ],
    ],

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
