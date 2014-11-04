<?php namespace Sharenjoy\Cmsharenjoy\Core\Traits;

use Session, ReflectionClass, Event;

trait CommonModelTrait {

    protected static $inputData = [];

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

    public function setInput(array $input)
    {
        self::$inputData = $input;
    }

    public function getInput()
    {
        return self::$inputData;
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
     * Figure out if tags can be used on the model
     * @return boolean
     */
    public function isTaggable()
    {
        return in_array(
            'Sharenjoy\Cmsharenjoy\Modules\Tag\Traits\TaggableTrait', 
            (new ReflectionClass($this))->getTraitNames()
        );
    }

    /**
     * Figure out if album can be used on the model
     * @return boolean
     */
    public function isAlbumable()
    {
        return in_array(
            'Sharenjoy\Cmsharenjoy\Filer\Traits\AlbumModelTrait',
            (new ReflectionClass($this))->getTraitNames()
        );
    }


}