<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Config, Lang, Session;

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
     * The arguments of field
     * @var array
     */
    protected $args;

    /**
     * The setting of field
     * @var array
     */
    protected $setting;

    /**
     * The config
     * @var array
     */
    protected $config;

    /**
     * The template of view
     * @var string
     */
    protected $template;

    protected function setType()
    {
        $this->type = null;

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
        $this->value = null;

        $session = Session::getOldInput();

        if (isset($session[$this->name]))
        {
            $this->value = $session[$this->name];
        }
        elseif (isset($this->setting['value']) && ! is_null($this->setting['value']))
        {
            $this->value = $this->setting['value'];
        }
    }

    protected function setOption()
    {
        $this->option = null;

        if ($this->type)
        {
            if (isset($this->setting['option']) && ! is_null($this->setting['option']))
            {
                $option = $this->setting['option'];

                if (is_array($option))
                {
                    $this->option = $option;
                }
                elseif (is_string($option))
                {
                    $this->option = Config::get('cmsharenjoy::options.'.$option);
                }
            }
            else
            {
                $this->option = Config::get('cmsharenjoy::options.'.$this->name) ?: 
                                Config::get('cmsharenjoy::options.yesno');
            }
        }

        if (isset($this->setting['pleaseSelect']) && $this->setting['pleaseSelect'] === true)
        {
            array_unshift($this->option, trans('cmsharenjoy::option.pleaseSelect'));
        }
    }

    protected function setArgs()
    {
        $this->args = null;

        if (isset($this->setting['args']) && ! is_null($this->setting['args']))
        {
            $this->args = $this->setting['args'];
        }
        else
        {
            $this->args = [];
        }

        $result = $this->getFormText('placeholder');

        if ($result) $this->args['placeholder'] = $result;
    }

    protected function getFormText($type)
    {
        // Set the lang of placeholder from config
        $targetA = 'app.form.'.$type.'.'.Session::get('onController').'.'.$this->name;
        $targetB = 'app.form.'.$type.'.'.$this->name;

        if (isset($this->setting[$type]) && ! is_null($this->setting[$type]))
        {
            return $this->setting[$type];
        }
        
        if (Lang::has('cmsharenjoy::'.$targetA) || Lang::has($targetA))
        {
            return pick_trans($targetA);
        }

        if (Lang::has('cmsharenjoy::'.$targetB) || Lang::has($targetB))
        {
            return pick_trans($targetB);
        }

        return;
    }

    /**
     * Compose some of useful form fields
     * @param  array  $config The config
     * @param  string $type  The type of form fields
     * @return array
     */
    public function form(array $config, $template = 'common', $format = 'string')
    {
        $data = $format == 'array' ? [] : null;

        if (count($config))
        {
            foreach ($config as $name => $setting)
            {
                $this->name     = $name;
                $this->setting  = $setting;
                $this->template = $template;

                $this->setType();
                $this->setValue();
                $this->setOption();
                $this->setArgs();

                // To make form
                $form = $this->make();

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
