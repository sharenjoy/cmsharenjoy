<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Eloquent;
use Sharenjoy\Cmsharenjoy\Core\Traits\EventModelTrait;
use Sharenjoy\Cmsharenjoy\Core\Traits\ScopeModelTrait;
use Sharenjoy\Cmsharenjoy\Core\Traits\AuthorModelTrait;
use Sharenjoy\Cmsharenjoy\Core\Traits\CommonModelTrait;

abstract class EloquentBaseModel extends Eloquent {

    use CommonModelTrait;
    use EventModelTrait;
    use ScopeModelTrait;
    use AuthorModelTrait;

}