<?php namespace Sharenjoy\Cmsharenjoy\Repo\Category;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class CategoryValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'type'       => 'required',
        'title'      => 'required'
    ];

}