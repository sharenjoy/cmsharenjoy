<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Eloquent;

abstract class EloquentBaseModel extends Eloquent {

    public function scopeKeyword($query, $value)
    {
        if ($value)
        {
            $filter = array_get($this->filterFormConfig, 'keyword.args.filter');
            $fields = explode(',', $filter);
            
            return $query->where(function($query) use ($value, $fields)
            {
                foreach ($fields as $field)
                {
                    $query->orWhere($field, 'LIKE', "%$value%");
                }
            });
        }
        else
        {
            return $query;
        }
    }

}