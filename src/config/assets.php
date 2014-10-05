<?php

return [

    'path'    => 'packages/sharenjoy/cmsharenjoy/',

    'package' => [

        'ckeditor' => [
            'ckeditor'        => [
                'file'  => 'js/ckeditor/ckeditor.js',
                'type'  => 'script',
                'queue' => false,
            ],
            'ckeditor-jquery' => [
                'file'  => 'js/ckeditor/adapters/jquery.js',
                'type'  => 'script',
                'queue' => false,
            ],
            'ckeditor-custom' => [
                'file'  => 'js/sharenjoy/ckeditor.js',
                'type'  => 'script',
                'queue' => false,
            ],
        ],
        
        'tag' => [
            'tag-input' => [
                'file'  => 'js/bootstrap-tagsinput.min.js',
                'type'  => 'script',
                'queue' => false,
            ]
        ],
        
        'datepicker' => [
            'bootstrap-datepicker' => [
                'file'  => 'js/bootstrap-datepicker.js',
                'type'  => 'script',
                'queue' => false,
            ]
        ],
        
        'daterange' => [
            'daterangepicker-css' => [
                'file'  => 'js/daterangepicker/daterangepicker-bs3.css',
                'type'  => 'style',
                'queue' => false,
            ],
            'moment' => [
                'file'  => 'js/daterangepicker/moment.min.js',
                'type'  => 'script',
                'queue' => false,
            ],
            'daterangepicker-js' => [
                'file'  => 'js/daterangepicker/daterangepicker.js',
                'type'  => 'script',
                'queue' => false,
            ]
        ],

        'colorpicker' => [
            'bootstrap-colorpicker' => [
                'file'  => 'js/bootstrap-colorpicker.min.js',
                'type'  => 'script',
                'queue' => false,
            ]
        ],
        
        'file-picker-reload' => [
            'file-input-extra' => [
                'file'  => 'js/sharenjoy/file-picker-reload.js',
                'type'  => 'script',
                'queue' => false,
            ]
        ],

    ],

];
