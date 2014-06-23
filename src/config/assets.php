<?php

return array(

    'path'    => 'packages/sharenjoy/cmsharenjoy/',

    'package' => [
        
        'wysihtml5' => [
            'bootstrap-wysihtml5-css'    => [
                'file'  => 'js/wysihtml5/bootstrap-wysihtml5.css',
                'type'  => 'style',
                'queue' => false,
            ],

            'wysihtml5'                  => [
                'file'  => 'js/wysihtml5/wysihtml5-0.4.0pre.min.js',
                'type'  => 'script',
                'queue' => false,
            ],
            'bootstrap-wysihtml5-js'     => [
                'file'  => 'js/wysihtml5/bootstrap-wysihtml5.js',
                'type'  => 'script',
                'queue' => false,
            ]
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
                'file'  => 'js/file-picker-reload.js',
                'type'  => 'script',
                'queue' => false,
            ]
        ],

    ],

);
