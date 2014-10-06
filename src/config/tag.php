<?php

return [

    /**
     * These are allowed model taggable
     * key: The key is relationshop method of model
     * value: This is relationship
     * sample: 'posts' => 'Sharenjoy\Cmsharenjoy\Repo\Post\Post'
     */
    'taggableModel' => [
        'posts'    => 'Sharenjoy\Cmsharenjoy\Repo\Post\Post',
        'reports'  => 'Sharenjoy\Cmsharenjoy\Repo\Report\Report',
        'products' => 'Sharenjoy\Cmsharenjoy\Repo\Product\Product',
    ],

];