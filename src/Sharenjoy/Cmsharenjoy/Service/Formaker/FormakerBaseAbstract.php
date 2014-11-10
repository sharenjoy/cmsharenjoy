<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Config, Lang, Session;

Abstract class FormakerBaseAbstract {

    /**
     * The name of field
     * @var string
     */
    protected $name;

    /**
     * The type of field
     * @var string
     */
    protected $type;

    /**
     * The value of field
     * @var string
     */
    protected $value;

    /**
     * The option of field
     * @var array
     */
    protected $option;

    /**
     * The array of arguments
     * @var array
     */
    protected $args;

    /**
     * Default type needs to create
     * @var string
     */
    protected $formType;

    /**
     * The field data needs to create
     * @var string
     */
    protected $inputData;

    /**
     * Which type needs to create
     * It can set 'normal, filter'
     * @param  string $type
     * @return Formaker
     */
    public function setFormType($type)
    {
        $this->formType = $type;
    }

    /**
     * Clean up the field name for the label
     * @param string $name
     */
    protected function prettifyFieldName($name = '')
    {
        if ( ! $name)
        {
            $name = $this->name;
        }

        // If doesn't set the config of lang
        if (Lang::has('app.form.'.$name))
        {
            return Lang::get('app.form.'.$name);
        }
        elseif (Lang::has('cmsharenjoy::app.form.'.$name))
        {
            return Lang::get('cmsharenjoy::app.form.'.$name);
        }
        else
        {
            // convert foo_boo to fooBoo and then convert to Foo Boo
            return ucwords(preg_replace('/(?<=\w)(?=[A-Z])/', " $1", camel_case($name)));
        }
    }

    /**
     * Provide a best guess for what the
     * input type should be.
     * @param string $name
     */
    protected function guessInputType()
    {
        return array_get(Config::get('cmsharenjoy::formaker.commonInputsLookup'), $this->name) ?: 'text';
    }

    /**
     * Provide a best guess for what the
     * option value should be.
     * @param string $name
     */
    protected function guessOption($option)
    {
        if (is_array($option))
        {
            return $option;
        }
        elseif(is_string($option))
        {
            return Config::get('cmsharenjoy::options.'.$option);
        }
        else
        {
            return Config::get('cmsharenjoy::options.'.$this->name) ?: ['1'=>'Yes', '2'=>'No'];
        }
    }

    /**
     * Compose some of useful form fields
     * @param  array  $config The config
     * @param  string $type  The type of form fields
     * @return array
     */
    public function form(array $config, $type = 'create')
    {
        $data = [];

        if (count($config))
        {
            // Set the fields is list
            $this->setFormType($type);

            foreach ($config as $key => $value)
            {
                $formaker = [];

                // To extract the element type,value,option,args
                $extract = ['type', 'value', 'option', 'args'];
                foreach ($extract as $element)
                {
                    if (isset($value[$element]) && ( ! is_null($value[$element]) || count($value[$element])))
                        $this->$element = $value[$element];
                }

                $data[$key] = $this->compose();
            }
        }

        return $data;
    }

    /**
     * Handle dynamic method calls
     * @param string $name
     * @param array $args
     */
    public function compose()
    {
        $this->inputData = '';

        return $this->make();
    }

}
