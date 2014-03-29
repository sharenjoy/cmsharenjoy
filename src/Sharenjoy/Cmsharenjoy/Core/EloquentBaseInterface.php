<?php namespace Sharenjoy\Cmsharenjoy\Core;

interface EloquentBaseInterface {

    public function setFilterQuery($model = null, $query);

    public function finalProcess($action, $model, $data = null);

}