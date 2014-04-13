<?php namespace Sharenjoy\Cmsharenjoy\Core;

interface EloquentBaseInterface {

    public function finalProcess($action, $model, $data = null);

}