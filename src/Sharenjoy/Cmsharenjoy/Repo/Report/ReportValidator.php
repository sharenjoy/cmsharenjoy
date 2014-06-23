<?php namespace Sharenjoy\Cmsharenjoy\Repo\Report;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class ReportValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'title'     => 'required',
        // 'link'      => 'required',
        'source'    => 'required',
        'content'   => 'required',
        'img'       => 'required'
    ];

}