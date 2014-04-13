<?php namespace Sharenjoy\Cmsharenjoy\Repo\Category;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class CategoryValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'type'       => 'required',
        'title'      => 'required|unique:categories,title'
    ];

}