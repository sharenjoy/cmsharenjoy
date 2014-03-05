<?php namespace Sharenjoy\Cmsharenjoy\Post;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class PostValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = [
        'title'     => 'required',
        'slug'      => 'required|unique:posts,id,<id>',
        'content'   => 'required'
    ];

}