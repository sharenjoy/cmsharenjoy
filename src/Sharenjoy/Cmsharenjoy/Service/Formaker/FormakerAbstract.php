<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Config, Lang, Session, Theme;

abstract class FormakerAbstract {

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
     * The array of arguments
     * @var array
     */
    protected $config;

    /**
     * The array of arguments
     * @var array
     */
    protected $errors;

    public function __construct()
    {
        if (Session::has('sharenjoy.validation.errors'))
        {
            $this->errors = Session::get('sharenjoy.validation.errors');
        }

        $end = Session::get('sharenjoy.environment.whichEnd');

        if ($end == 'backEnd')
        {
            $this->config = Config::get('cmsharenjoy::formaker.backend.'.$this->theme);
        }
        elseif ($end == 'frontEnd')
        {
            $this->config = Config::get('cmsharenjoy::formaker.frontend.'.$this->theme);
        }
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

    protected function setType()
    {
        if (isset($this->setting['type']) && ! is_null($this->setting['type']))
        {
            $this->type = $this->setting['type'];
        }
        else
        {
            $cfg = Config::get('cmsharenjoy::formaker.commonInputsLookup');
            $this->type = array_get($cfg, $this->name) ?: 'text';
        }
    }

    protected function setValue()
    {
        if (isset($this->setting['value']) && ! is_null($this->setting['value']))
        {
            $this->value = $this->setting['value'];
        }
    }

    protected function setOption()
    {
        if (isset($this->setting['option']) && ! is_null($this->setting['option']))
        {
            $option = $this->setting['option'];

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

            if (isset($this->setting['pleaseSelect']) && $this->setting['pleaseSelect'] === true)
            {
                $this->option[0] = trans('cmsharenjoy::option.pleaseSelect');
            }
        }
    }

    protected function setArgs()
    {
        if (isset($this->setting['args']) && ! is_null($this->setting['args']))
        {
            $this->args = $this->setting['args'];
        }
        else
        {
            $this->args = [];
        }

        $result = $this->getLanguageSetting('placeholder');
        if ($result) $this->args['placeholder'] = $result;
    }

    protected function getLanguageSetting($type)
    {
        // Set the lang of placeholder from config
        $targetA = 'app.form.'.$type.'.'.Session::get('onController').'.'.$this->name;
        $targetB = 'app.form.'.$type.'.'.$this->name;

        if (isset($this->setting[$type]) && ! is_null($this->setting[$type]))
        {
            $lang = $this->setting[$type];
        }
        elseif (Lang::has('cmsharenjoy::'.$targetA) || Lang::has($targetA))
        {
            if (Lang::has($targetA))
            {
                $lang = Lang::get($targetA);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetA))
            {
                $lang = Lang::get('cmsharenjoy::'.$targetA);
            }
        }
        elseif (Lang::has('cmsharenjoy::'.$targetB) || Lang::has($targetB))
        {
            if (Lang::has($targetB))
            {
                $lang = Lang::get($targetB);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetB))
            {
                $lang = Lang::get('cmsharenjoy::'.$targetB);
            }
        }
        else
        {
            return false;
        }

        return $lang;
    }

    /**
     * Compose some of useful form fields
     * @param  array  $config The config
     * @param  string $type  The type of form fields
     * @return array
     */
    public function form(array $config, $theme = 'common', $format = 'string')
    {
        $data = $format == 'array' ? [] : null;

        if (count($config))
        {
            foreach ($config as $name => $config)
            {
                $this->name    = $name;
                $this->setting = $config;

                $this->setType();
                $this->setValue();
                $this->setOption();
                $this->setArgs();

                // To make form
                $form = null;
                switch ($theme)
                {
                    case 'filter':
                        $form = $this->make();
                        break;
                    default:
                        $form = $this->make();
                        break;
                }

                switch ($format)
                {
                    case 'string':
                        $data .= $form;
                        break;
                    case 'array':
                        $data[$name] = $form;
                        break;
                    case 'json':
                        $data .= json_encode(htmlspecialchars($form));
                        break;
                }
            }
        }

        return $data;
    }

    /**
     * Handle dynamic method calls
     * @param string $name
     * @param array $args
     */
    public function __call($name, $args)
    {
        $config = [
            $name => empty($args) ? [] : $args[0]
        ];
        
        return $this->form($config);
    }

}
