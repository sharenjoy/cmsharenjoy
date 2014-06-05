<?php namespace Sharenjoy\Cmsharenjoy\Service\Poster\Facades;

use Illuminate\Support\Facades\Facade;

class Poster extends Facade {

    protected static function getFacadeAccessor()
    { 
        return 'Sharenjoy\Cmsharenjoy\Service\Poster\PosterInterface'; 
    }

}