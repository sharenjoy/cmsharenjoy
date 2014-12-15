<?php namespace Sharenjoy\Cmsharenjoy\Core\Traits;

use ReflectionClass;

trait CommonModelTrait {

    protected static $inputData = [];

    protected static $reflection = null;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {$model->eventProcess('creating', $model);});
        static::created(function($model)  {$model->eventProcess('created', $model);});
        static::updating(function($model) {$model->eventProcess('updating', $model);});
        static::updated(function($model)  {$model->eventProcess('updated', $model);});
        static::saving(function($model)   {$model->eventProcess('saving', $model);});
        static::saved(function($model)    {$model->eventProcess('saved', $model);});
        static::deleting(function($model) {$model->eventProcess('deleting', $model);});
        static::deleted(function($model)  {$model->eventProcess('deleted', $model);});
    }

    /**
     * To process the input data in advance
     * @param array $input
     */
    public function setInput(array $input)
    {
        $input = $this->parseCheckboxValue($input);

        self::$inputData = $input;
    }

    public function getInput()
    {
        return self::$inputData;
    }

    protected function parseCheckboxValue($input)
    {
        /**
         * If the field of input is an array
         * To convert the array to string
         */
        if (count($input))
        {
            foreach ($input as $key => $value)
            {
                if (is_array($input[$key]))
                {
                    $input[$key] = join(',', $value);
                }
            }
        }

        /**
         * If the value of field is null
         * To set the value to null
         */
        if (count($this->formConfig))
        {
            $check_type = ['checkbox'];

            foreach ($this->formConfig as $key => $value)
            {
                if ( ! isset($input[$key]) && in_array($value['type'], $check_type))
                {
                    $input[$key] = '';
                }
            }
        }

        return $input;
    }

    /**
     * Set what kind of data that we went to get
     * @param  Model $model
     * @return Model
     */
    public function listQuery()
    {
        return $this->orderBy('sort', 'DESC');
    }

    /**
     * To process the event of model
     * @param  string $event
     * @param  object $model
     * @return void
     */
    public function eventProcess($event, $model = null)
    {
        if (isset($this->eventItem[$event]) && count($this->eventItem[$event]))
        {
            foreach ($this->eventItem[$event] as $item)
            {
                if (strpos($item, '|') !== false)
                {
                    $args   = explode('|', $item);
                    $method = 'event'.studly_case($args[0]);
                    $this->{$method}($args, $model);
                }
                else
                {
                    $method = 'event'.studly_case($item);
                    $this->{$method}($item, $model);
                }
            }
        }
    }

    /**
     * What this method doing is can dynamic push
     * some form data config to model's form config
     * @param  array  $data
     * @param  string $form
     * @return void
     */
    public function pushForm(array $data, $form = 'formConfig')
    {
        if (isset($this->$form))
        {
            return $this->$form = array_merge($this->$form, $data);
        }

        throw new \InvalidArgumentException("The model doesn't exist the {$form} property.");
    }

    public function processForm($formConfig, $input)
    {
        if (count($formConfig))
        {
            foreach ($formConfig as $name => $config)
            {
                // To get the options of select element from the Model
                if (isset($config['method']))
                {
                    $method = camel_case($config['method']);
                    $formConfig[$name] = array_merge($formConfig[$name], $this->$method());
                }

                // If use key that the name is input of value otherwise use the $key
                if (isset($config['input']) && isset($input[$config['input']]))
                    $formConfig[$name]['value'] = $input[$config['input']];
                elseif ($name == 'tag')
                    $formConfig[$name]['value'] = $input->tag;
                elseif (isset($input[$name]))
                    $formConfig[$name]['value'] = $input[$name];
            }
        }

        return $formConfig;
    }

    public function getReflection()
    {
        if (is_null(static::$reflection))
        {
            static::$reflection = new ReflectionClass($this);
        }

        return static::$reflection;
    }

    /**
     * Figure out if tags can be used on the model
     * @return boolean
     */
    public function isTaggable()
    {
        return in_array(
            'Sharenjoy\Cmsharenjoy\Modules\Tag\TaggableTrait', 
            $this->getReflection()->getTraitNames()
        );
    }

    /**
     * Figure out if album can be used on the model
     * @return boolean
     */
    public function isAlbumable()
    {
        return in_array(
            'Sharenjoy\Cmsharenjoy\Filer\AlbumTrait',
            $this->getReflection()->getTraitNames()
        );
    }


}