<?php namespace Sharenjoy\Cmsharenjoy\Setting;

use Eloquent;

class Setting extends Eloquent {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'settings';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array( 'id' , 'value');

}
