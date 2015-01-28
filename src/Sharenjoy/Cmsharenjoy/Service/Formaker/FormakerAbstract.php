<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Config, Lang, Session;

abstract class FormakerAbstract {

    /**
     * The model
     * @var object
     */
    protected $model;

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

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

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
            $this->option = ['0' => pick_trans('option.pleaseSelect')] + $this->option;
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
        $targetA = 'form.'.$type.'.'.Session::get('onController').'.'.$this->name;
        $targetB = 'form.'.$type.'.'.$this->name;

        if (isset($this->setting[$type]) && ! is_null($this->setting[$type]))
        {
            return $this->setting[$type];
        }
        
        if (pick_trans($targetA))
        {
            return pick_trans($targetA);
        }

        if (pick_trans($targetB))
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
                $this->template = $template == 'index' ? 'filter' : $template;

                $this->setType();
                $this->setValue();
                $this->setOption();
                $this->setArgs();

                // To fetch form
                $form = $this->fetch();

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
     * To make a form fields
     * @param  array  $input
     * @param  String $formType
     * @param  array  $config This is you can customise form config
     * @return array  Build from Formaker
     */
    public function make($input = array(), string $config = null)
    {
        $onAction = Session::get('onAction');

        if ($config != null)
        {
            if ( ! isset($this->model->$config))
            {
                throw new \InvalidArgumentException("The model property doesn't exist of the {$config} argument.");
            }

            $formConfig = $this->model->$config;
        }
        else
        {
            $formConfig = $onAction == 'index' 
                        ? $this->model->filterFormConfig
                        : $this->model->formConfig;
        }

        if (count($formConfig))
        {
            foreach ($formConfig as $key => $item)
            {
                if (isset($item[$onAction]))
                {
                    if ($item[$onAction] == 'deny')
                    {
                        unset($formConfig[$key]);
                    }
                    elseif (is_array($item[$onAction]))
                    {
                        $formConfig[$key] = array_merge($formConfig[$key], $item[$onAction]);
                    }
                }
            }
            
            // To order the form config
            $formConfig = array_sort($formConfig, function($value)
            {
                return $value['order'];
            });
        }

        // To do other process that needs to be done
        $formConfig = $this->model->processForm($formConfig, $input);

        return $this->form($formConfig, $onAction);
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
