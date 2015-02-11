<?php namespace Sharenjoy\Cmsharenjoy\Modules\Category;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class CategoryValidator extends AbstractLaravelValidator {

    public $unique = [];
    
    public $rules = [
        'type'       => 'required',
        'title'      => 'required'
    ];

}