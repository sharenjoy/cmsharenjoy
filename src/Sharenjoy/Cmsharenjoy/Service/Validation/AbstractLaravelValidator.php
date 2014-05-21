<?php namespace Sharenjoy\Cmsharenjoy\Service\Validation;

use Illuminate\Validation\Factory;

abstract class AbstractLaravelValidator implements ValidableInterface {

    /**
     * Validator
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * Validation data key => value array
     * @var Array
     */
    protected $data = array();

    /**
     * Validation errors
     * @var Array
     */
    protected $errors = array();

    /**
     * Validation rules
     * @var Array
     */
    public $rules = array();

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Set data to validate
     * @return \Sharenjoy\Cmsharenjoy\Service\Validation\AbstractLaravelValidator
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * To overwrite normal rules
     * @param string $action The new rule
     */
    public function setRule($action)
    {
        $this->rules = $this->$action;

        return $this;
    }

    /**
     * Validation passes or fails
     * @return Boolean
     */
    public function passes()
    {
        $validator = $this->validator->make($this->data, $this->rules);

        if ($validator->fails())
        {
            $this->errors = $validator->messages();
            return false;
        }

        return true;
    }

    /**
     * Return errors, if any
     * @return MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return an array of errors
     * @return array
     */
    public function getErrorsToArray()
    {
        return $this->errors()->toArray();
    }

    /**
     * To set some column don't need to valid
     * @param array $keyAry
     * @param string $id
     */
    public function setUniqueUpdateFields($keyAry, $id)
    {
        foreach ($keyAry as $field)
        {
            if (isset($this->rules[$field]))
            {
                $rules = $this->rules;
                $this->rules[$field] = $rules[$field].','.$id;
            }
        }
    }

}