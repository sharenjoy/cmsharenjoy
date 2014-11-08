<?php namespace Sharenjoy\Cmsharenjoy\Service\Validation;

use Illuminate\Validation\Factory;
use App, Message, StdClass, Lang, Session;

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
    protected $data = [];

    /**
     * Validation errors
     * @var Array
     */
    protected $errors = [];

    /**
     * Validation rules
     * @var Array
     */
    protected $rules = [];

    /**
     * This is the unique id that want to update row's id
     * @var string
     */
    protected $uniqueId = null;

    /**
     * Unique fields
     * @var Array
     */
    protected $unique = [];

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
     * @param mixed $rule The new rule
     */
    public function setRule($rule)
    {
        if (is_string($rule))
        {
            if (isset($this->$rule))
                $this->rules = $this->$rule;
            else
                throw new \Sharenjoy\Cmsharenjoy\Exception\ValidatorRulesNotFoundException;
        }
        elseif (is_array($rule))
        {
            $this->rules = $rule;
        }

        return $this;
    }

    /**
     * To set the unique id that want to update row
     * @param string $id
     */
    public function setUniqueId($id)
    {
        if (is_string($id))
        {
            $this->uniqueId = $id;
        }
        return $this;
    }

    /**
     * To overwrite normal unique
     * @param string $action The new rule
     */
    public function setUniqueFields($unique)
    {
        if (count($unique))
        {
            $this->unique = $unique;
        }
        else
        {
            throw new \Sharenjoy\Cmsharenjoy\Exception\ValidatorRulesNotFoundException;
        }

        return $this;
    }

    /**
     * To set some column don't need to valid
     * @param array $keyAry
     * @param string $id
     */
    public function setUnique($id = null, $unique = null)
    {
        $id     = $id ?: $this->uniqueId;
        $unique = $unique ?: $this->unique;

        if (count($unique) && $id != null)
        {
            foreach ($unique as $field)
            {
                if (isset($this->rules[$field]))
                {
                    $rules = $this->rules;
                    $this->rules[$field] = $rules[$field].','.$id;
                }
            }
        }

        return $this;
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
            $messages = array_merge($messages, Lang::get('cmsharenjoy::app.form.validation'));
        }
        if (Lang::has('app.form.validation'))
        {
            $messages = array_merge($messages, Lang::get('app.form.validation'));
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
            if (Lang::has('app.form.'.$key))
            {
                $attributes[$key] = Lang::get('app.form.'.$key);
            }
            elseif (Lang::has('cmsharenjoy::app.form.'.$key))
            {
                $attributes[$key] = Lang::get('cmsharenjoy::app.form.'.$key);
            }
        }

        return $attributes;
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
        $validator = $this->validator->make(
            $this->data,
            $this->rules,
            $this->messages(),
            $this->attributes()
        );

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
     * Merge message to flashMessageBag
     * @return void
     */
    public function getErrorsToFlashMessageBag()
    {
        $errors = $this->errors()->toArray();
        
        if (count($errors))
        {
            foreach ($errors as $message)
            {
                Message::error($message);
            }
        }
    }

    /**
     * Test if form validator passes
     * @param  array The input needs to valid
     * @param  array The type of message, it can be 'messageBeg'
     * @return boolean|Message
     */
    public function valid(array $input, $errorType = 'error')
    {
        $result = $this->with($input)->passes();

        if ( ! $result)
        {
            switch ($errorType)
            {
                case 'error':
                    Session::flash('sharenjoy.validation.errors', $this->errors());
                    Message::error(trans('cmsharenjoy::app.check_some_wrong'));
                    break;

                case 'flash':
                    $this->getErrorsToFlashMessageBag();
                    break;

                case 'json':
                    return Message::result(
                        false,
                        trans('cmsharenjoy::app.check_some_wrong'),
                        $this->errors()->toArray()
                    );
                    break;
                
                default:
                    break;
            }
        }
        
        return $result;
    }

}