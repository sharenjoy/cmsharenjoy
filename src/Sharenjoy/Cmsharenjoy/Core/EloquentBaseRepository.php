<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;

/**
 * Base Eloquent Repository Class Built On From Shawn McCool <-- This guy is pretty amazing
 */
class EloquentBaseRepository {

    /**
     * The instance of model
     * @var Eloquent
     */
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * Return a instance of model
     * @return Eloquent
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Get everything (active only)
     * @return Eloquent
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get only deleted items
     * @return Eloquent
     */
    public function getAllTrashed()
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Get an Eloquent object for pagination
     * @param  int $count How many rows per page
     * @return Eloquent
     */
    public function getAllPaginated($count)
    {
        return $this->model->orderBy('sort', 'desc')->paginate($count);
    }

    /**
     * Get a record by its ID
     * @param  integer $id The ID of the record
     * @return Eloquent
     */
    public function getById($id)
    {
        return $this->model->with('tags')->find($id);
    }

    /**
     * Get an Eloquent object if entity exist
     * @param  int $id The id of data
     * @return Eloquent
     */
    public function requireById($id)
    {
        $model = $this->getById($id);

        if ( ! $model)
        {
            throw new EntityNotFoundException;
        }

        return $model;
    }

    /**
     * Get a record by its ID even if it is trashed
     * @param  integer $id The ID of the record
     * @return Eloquent
     */
    public function getByIdWithTrashed($id)
    {
        return $this->model->withTrashed()->find($id);
    }

    /**
     * Get a record by it's slug
     * @param  string $slug The slug name
     * @return Eloquent
     */
    public function getBySlug($slug)
    {
        return $this->model->where('slug','=',$slug)->first();
    }

    /**
     * Get all posts that have a tag of the type passed in
     * @return Eloquent
     */
    public function getAllByTag($tag)
    {
        $table = $this->model->getTableName();

        return $this->model->join('tags', 'tags.taggable_id', '=', $table.'.id')->where('tag','=',$tag)->get();
    }

    /**
     * Thranform an array to an Eloquent object
     * @param  array  $attributes
     * @return Eloquent
     */
    public function getNew($attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * It depends on what type of data to store
     * @param  mixed $data Could be an array or Eloquent object
     * @return void
     */
    public function store($data)
    {
        if ($data instanceOf \Eloquent)
        {
            $this->storeEloquentModel($data);
        }
        elseif (is_array($data))
        {
            $this->storeArray($data);
        }
    }

    /**
     * Store an array data to database by an id
     * @param  int $id   The id need to store
     * @param  array $data The data need to store
     * @return void
     */
    public function storeById($id, $data)
    {
        $this->model->where('id', '=', $id)->update($data);
    }

    /**
     * Store the eloquent model that is passed in
     * @param  Eloquent $model The Eloquent Model
     * @return void
     */
    protected function storeEloquentModel($model)
    {
        if ($model->getDirty())
        {
            $model->save();
        }
        else
        {
            $model->touch();
        }
    }

    /**
     * Store an array of data
     * @param  array $data The Data Array
     * @return void
     */
    protected function storeArray($data)
    {
        $model = $this->getNew($data);
        $this->storeEloquentModel($model);
    }

    /**
     * Sync tags for article
     * @param \Illuminate\Database\Eloquent\Model  $article
     * @param array  $tags
     * @return void
     */
    public function syncTags($model, array $tags)
    {
        // Create or add tags and return an Elqquent object
        $found = $this->tag->findOrCreate($tags);

        $tagIds = array();

        foreach($found as $tag)
        {
            $tagIds[] = $tag->id;
        }

        // Assign set tags to model
        $model->tags()->sync($tagIds);
    }

    /**
     * Delete the model passed in
     * @param  Eloquent $model The description
     * @return void
     */
    public function delete($model)
    {
        $model->delete();
    }

}