<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator;

class ProductValidator extends AbstractLaravelValidator {

    public $unique = [];

    public $rules = [
        'img'         => 'required',
        'category_id' => 'required',
        'title'       => 'required',
        'price'       => 'required|numeric'
    ];

}