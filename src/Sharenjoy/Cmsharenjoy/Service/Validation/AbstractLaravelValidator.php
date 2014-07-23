<?php namespace Sharenjoy\Cmsharenjoy\Service\Validation;

use Illuminate\Validation\Factory;
use Sharenjoy\Cmsharenjoy\Exception\ValidatorRulesNotFoundException;
use App, Message, StdClass, Lang;

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
    protected $rules = array();

    public function __construct(Factory $validator = null)
    {
        if ($validator == null)
        {
            $this->validator = App::make('validator');
        }
        else
        {
            $this->validator = $validator;
        }
    }

    /**
     * To overwrite normal rules
     * @param string $action The new rule
     */
    public function setRule($ruleName)
    {
        if (isset($this->$ruleName))
        {
            $this->rules = $this->$ruleName;
        }
        else
        {
            throw new ValidatorRulesNotFoundException;
        }

        return $this;
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
     * Validation passes or fails
     * @return Boolean
     */
    public function passes()
    {
        $validator = $this->validator->make($this->data, $this->rules, $this->messages(), $this->attributes());

        if ($validator->fails())
        {
            $this->errors = $validator->messages();
            return false;
        }

        return true;
    }

    /**
     * Return errors
     * @return MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return the custom message form lang file
     * @return array
     */
    protected function messages()
    {
        $messages = [];

        if (Lang::has('cmsharenjoy::app.form.validation'))
        {
            $messages = Lang::get('cmsharenjoy::app.form.validation');
        }

        return $messages;
    }

    /**
     * Custom message attribute
     * @return array
     */
    protected function attributes()
    {
        $attributes = [];

        foreach ($this->rules as $key => $value)
        {
            if (Lang::has('cmsharenjoy::app.form.'.$key))
            {
                $attributes[$key] = Lang::get('cmsharenjoy::app.form.'.$key);
            }
        }

        return $attributes;
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
     * Merge message to flashMessageBag
     * @return void
     */
    public function getErrorsToFlashMessageBag()
    {
        if ($this->getErrorsToArray())
        {
            foreach ($this->getErrorsToArray() as $message)
            {
                Message::error($message);
            }
        }
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

    /**
     * Test if form validator passes
     * @param  array The input needs to valid
     * @param  array The type of message, it can be 'messageBeg'
     * @return boolean
     */
    public function valid(array $input, $ruleName = null, $uniqueField = array(), $errorType = 'flash')
    {
        // Set rule by other rules
        if (isset($this->$ruleName))
        {
            $this->setRule($ruleName);
        }

        if (count($uniqueField))
        {
            list($ary, $id) = $uniqueField;
            if (count($ary) && $id != '')
            {
                $this->setUniqueUpdateFields($ary, $id);
            }
        }

        $result = new StdClass;
        $result->status = $this->with($input)->passes();

        if ( ! $result->status)
        {
            switch ($errorType)
            {
                case 'flash':
                    $this->getErrorsToFlashMessageBag();
                    $result->message = 'MessageBag';
                    break;

                case 'json':
                    $result->message = Message::json('error', $this->getErrorsToArray());
                    break;
                
                default:
                    break;
            }
        }
        
        return $result;
    }

}