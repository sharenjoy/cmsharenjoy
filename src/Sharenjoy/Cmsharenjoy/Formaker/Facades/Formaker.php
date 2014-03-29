<?php namespace Sharenjoy\Cmsharenjoy\Formaker\Facades;

use Illuminate\Support\Facades\Facade;

class Formaker extends Facade {

    protected static function getFacadeAccessor()
    { 
        return 'Sharenjoy\Cmsharenjoy\Formaker\FormakerInterface'; 
    }

}