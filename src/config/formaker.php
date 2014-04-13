<?php

return array(
	
    /*
     * --------------------------------------------------------------------------
     *  How to use
     * --------------------------------------------------------------------------
     * 
     * {{Formaker::title()}}
     * {{Formaker::email()}}
     * {{Formaker::url()}}
     * {{Formaker::description(['value'=>'This is value'])}}
     * {{Formaker::description(['type'=>'wysihtml5'])}}
     * {{Formaker::tag(['help'=>'This is tag', 'placeholder'=>'Create'])}}
     * {{Formaker::language(['type'=>'select', 'option'=>['tw'=>'中文', 'en'=>'英文']])}}
     * {{Formaker::status(['type'=>'checkbox', 'help'=>'This is help'])}}
     * {{Formaker::status(['type'=>'radio', 'checked'=>'tw', 'option'=>['tw'=>'中文', 'en'=>'英文']])}}
     * {{Formaker::date(['type'=>'daterange', 'placeholder'=>'Date Range'])}}
     * {{Formaker::date(['type'=>'datepicker', 'placeholder'=>'Date Picker'])}}
     * {{Formaker::colorpicker(['type'=>'colorpicker'])}}
     * {{Formaker::file(['type'=>'file'])}}
     * 
     * {{Formaker::custom('list-filter')->title()}}
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
        
        // For wysihtml5
        'content'               => 'wysihtml5',
        
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
        
        // For file
        'file'                  => 'file',
    ],


    /**
    * Frequent option value can map
    * to their respective input types.
    */
    'commonOption' =>
    [
        'status' => [
            '0' => Lang::get('cmsharenjoy::option.pleaseSelect'),
            '1' => Lang::get('cmsharenjoy::option.enable'),
            '2' => Lang::get('cmsharenjoy::option.disable'),
        ],
    ],

);
