<?php namespace Sharenjoy\Cmsharenjoy\Repo\Qna;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class QnaValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'title'       => 'required',
        'answer'      => 'required',
        'description' => 'required',
    ];

}