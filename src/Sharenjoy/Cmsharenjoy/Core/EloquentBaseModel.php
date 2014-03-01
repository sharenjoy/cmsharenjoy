<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Sharenjoy\Cmsharenjoy\Exception\NoValidationRulesFoundException;
use Validator, Eloquent, ReflectionClass, Input;

/**
 * Base Eloquent Class Built On From Shawn McCool <-- This guy is pretty amazing
 */
class EloquentBaseModel extends Eloquent {

    protected $validationRules = [];
    protected $validator;

    public function isValid($data = array())
    {
        if ( ! isset($this->validationRules) or empty($this->validationRules))
        {
            throw new NoValidationRulesFoundException('no validation rules found in class ' . get_called_class());
        }

        if( ! $data)
        {
            $data = $this->getAttributes();
        }

        $this->validator = Validator::make($data, $this->getPreparedRules());

        return $this->validator->passes();
    }

    public function getErrors()
    {
        return $this->validator->errors();
    }

    protected function getPreparedRules()
    {
        if ( ! $this->validationRules)
        {
            return [];
        }

        $preparedRules = $this->replaceIdsIfExists($this->validationRules);

        return $preparedRules;
    }

    protected function replaceIdsIfExists($rules)
    {
        $preparedRules = [];

        foreach ($rules as $key => $rule)
        {
            if (false !== strpos($rule, "<id>"))
            {
                if ($this->exists)
                {
                    $rule = str_replace("<id>", $this->getAttribute($this->primaryKey), $rule);
                }
                else
                {
                    $rule = str_replace("<id>", "", $rule);
                }
            }

            $preparedRules[$key] = $rule;
        }

        return $preparedRules;
    }          

    /**
     * Return the table name
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }

}