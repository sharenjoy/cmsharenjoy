<?php namespace Sharenjoy\Cmsharenjoy\Repo\Tag;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class TagValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'tag'       => 'required',
        'slug'      => 'required|unique:tags,slug'
    ];

}