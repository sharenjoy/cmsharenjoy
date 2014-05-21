<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class PostValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'title'     => 'required',
        'slug'      => 'required|unique:posts,slug',
        'content'   => 'required'
    ];

}