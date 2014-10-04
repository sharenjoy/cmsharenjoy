<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class PostValidator extends AbstractLaravelValidator {

    public $unique = ['title'];

    public $rules = [
        'title'     => 'required|unique:posts,title',
        'content'   => 'required'
    ];

}