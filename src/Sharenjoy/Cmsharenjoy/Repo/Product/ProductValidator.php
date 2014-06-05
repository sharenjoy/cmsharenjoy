<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class ProductValidator extends AbstractLaravelValidator {

    /**
     * Validation rules
     *
     * @var Array
     */
    public $rules = [
        'img'         => 'required',
        'category_id' => 'required',
        'title'       => 'required',
        'price'       => 'required|numeric'
    ];

}