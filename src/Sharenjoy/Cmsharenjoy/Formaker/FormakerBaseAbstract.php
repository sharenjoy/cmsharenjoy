<?php namespace Sharenjoy\Cmsharenjoy\Formaker;

use Config, Lang, Session;

Abstract class FormakerBaseAbstract {

    /**
     * The name of field
     * @var [type]
     */
    protected $name;

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
        if (Lang::has('cmsharenjoy::app.form.'.$name))
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
    protected function guessOption()
    {
        return array_get(Config::get('cmsharenjoy::formaker.commonOption'), $this->name) ?: ['1'=>'Yes', '2'=>'No'];
    }

    /**
     * Compose some of useful form fields
     * @param  array  $filterFormConfig The config
     * @param  string $type  The type of form fields
     * @return array
     */
    public function makeForm(array $formConfig, $type)
    {
        if (count($formConfig))
        {
            $data = array();

            // Set the fields is list
            $this->setFormType($type);
            
            foreach ($formConfig as $key => $value)
            {
                $formaker = array();
                $data[$key] = $value;

                if(isset($value['type'])  && !is_null($value['type'])) $formaker['type'] = $value['type'];
                if(isset($value['value']) && !is_null($value['value'])) $formaker['value'] = $value['value'];
                if(isset($value['option']) && count($value['option'])) $formaker['option'] = $value['option'];
                if(isset($value['args']) && count($value['args'])) $formaker = array_merge($formaker, $value['args']);

                $data[$key]['field'] = $this->$key($formaker);
            }

            return $data;
        }

        return false;
    }

    /**
     * Handle dynamic method calls
     * @param string $name
     * @param array $args
     */
    public function __call($name, $args)
    {
        $this->inputData = '';
        $this->args = empty($args) ? [] : $args[0];
        $this->name = $name;

        return $this->make();
    }

}
