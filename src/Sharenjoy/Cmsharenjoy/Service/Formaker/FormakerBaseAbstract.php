<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Sharenjoy\Cmsharenjoy\Utilities\Parser;
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
     * The array of arguments
     * @var array
     */
    protected $setting;

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
     * To parser the string
     * @var object Sharenjoy\Cmsharenjoy\Utilities\Parser
     */
    protected $parser;

    public function __construct()
    {
        $this->parser = new Parser();
    }

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
     * @param array $config
     */
    protected function setType($config)
    {
        if (isset($config['type']) && ! is_null($config['type']))
        {
            $this->type = $config['type'];
        }
        else
        {
            $cfg = Config::get('cmsharenjoy::formaker.commonInputsLookup');
            $this->type = array_get($cfg, $this->name) ?: 'text';
        }
    }

    /**
     * Provide a best guess for what the
     * input type should be.
     * @param array $config
     */
    protected function setValue($config)
    {
        if (isset($config['value']) && ! is_null($config['value']))
        {
            $this->value = $config['value'];
        }
    }

    /**
     * Provide a best guess for what the
     * option value should be.
     * @param array $config
     */
    protected function setOption($config)
    {
        $option = $config['option'];

        if (is_array($option))
        {
            $this->option = $option;
        }
        elseif(is_string($option))
        {
            $this->option = Config::get('cmsharenjoy::options.'.$option);
        }
        else
        {
            $this->option = Config::get('cmsharenjoy::options.'.$this->name) ?: 
                            Config::get('cmsharenjoy::options.yesno');
        }

        if (isset($config['pleaseSelect']) && $config['pleaseSelect'] === true)
        {
            $this->option[0] = trans('cmsharenjoy::option.pleaseSelect');
        }
    }

    /**
     * Provide a best guess for what the
     * option value should be.
     * @param array $config
     */
    protected function setArgs($config)
    {
        $this->args = $config['args'];

        // Set the lang of placeholder from config
        $targetA = 'app.form.placeholder.'.Session::get('onController').'.'.$this->name;
        $targetB = 'app.form.placeholder.'.$this->name;

        if (isset($config['placeholder']) && ! is_null($config['placeholder']))
        {
            $this->args['placeholder'] = $config['placeholder'];
        }
        elseif (Lang::has('cmsharenjoy::'.$targetA) || Lang::has($targetA))
        {
            if (Lang::has($targetA))
            {
                $this->args['placeholder'] = Lang::get($targetA);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetA))
            {
                $this->args['placeholder'] = Lang::get('cmsharenjoy::'.$targetA);
            }
        }
        elseif (Lang::has('cmsharenjoy::'.$targetB) || Lang::has($targetB))
        {
            if (Lang::has($targetB))
            {
                $this->args['placeholder'] = Lang::get($targetB);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetB))
            {
                $this->args['placeholder'] = Lang::get('cmsharenjoy::'.$targetB);
            }
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

            foreach ($config as $key => $config)
            {
                $this->name    = $key;
                $this->setting = $config;

                $this->setType($config);
                $this->setValue($config);
                $this->setOption($config);
                $this->setArgs($config);


                $data[$key] = $this->make();
            }
        }

        return $data;
    }

}
