<?php namespace Sharenjoy\Cmsharenjoy\Service\Poster;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

Abstract class PosterBaseAbstract {

    /**
     * The instance of model
     * @var Eloquent
     */
    protected $model;

    /**
     * Return a instance of model
     * @return Eloquent
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set an object from outside
     * @param Eloqunet $model
     */
    public function setModel($model)
    {
        if (is_string($model))
        {
            $this->model = new $model;
        }
        else
        {
            $this->model = $model;
        }
        
        return $this;
    }

    /**
     * Get an instance of Eloquent
     * @param  array  $attributes The data of array
     * @return Eloquent
     */
    public function getNewEloquent(array $attributes)
    {
        return $this->model->newInstance($attributes);
    }

}
