<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
use Debugbar, StdClass;

/**
 * Base Eloquent Repository Class Built On From Shawn McCool <-- This guy is pretty amazing
 */
class EloquentBaseRepository {

    /**
     * The instance of model
     * @var Eloquent
     */
    protected $model;

    /**
     * The instance of tag
     * @var Object
     */
    protected $tag;
    public $taggable;
    
    /**
     * The instance of upload
     * @var Object
     */
    protected $upload;
    public $uploadable;

    public function __construct()
    {
        $this->taggable   = is_null($this->tag) ? false : true;
        $this->uploadable = is_null($this->upload) ? false : true;
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
     * Get an instance of Eloquent
     * @param  array  $attributes The data of array
     * @return Eloquent
     */
    public function getNewEloquent(array $attributes)
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Get a record by its ID
     * @param  integer $id The ID of the record
     * @return Eloquent
     */
    public function byId($id)
    {
        if ($this->taggable)
        {
            return $this->model->with('tags')->find($id);
        }
        else
        {
            return $this->model->find($id);
        }
    }

    /**
     * Get paginated articles
     *
     * @param int $page Number of articles per page
     * @param int $limit Results per page
     * @param boolean $all Show published or all
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function byPage($page = 1, $limit, $model)
    {
        $result             = new StdClass;
        $result->page       = $page;
        $result->limit      = $limit;
        $result->totalItems = 0;
        $result->items      = array();

        $query = $model->orderBy('sort', 'desc');

        $rows = $query->skip($limit * ($page-1))
                      ->take($limit)
                      ->get();

        $result->totalItems = $this->totalRows($model);
        $result->items = $rows->all();

        return $result;
    }

    /**
     * Get a record by it's slug
     * @param  string $slug The slug name
     * @return Eloquent
     */
    public function bySlug($slug)
    {
        return $this->model->where('slug','=',$slug)->first();
    }

    /**
     * Get articles by their tag
     *
     * @param string  URL slug of tag
     * @param int Number of articles per page
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function byTag($tag, $page=1, $limit=10)
    {
        $foundTag = $this->tag->where('slug', $tag)->first();

        $result = new \StdClass;
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = array();

        if( !$foundTag )
        {
            return $result;
        }

        $articles = $this->tag->articles()
                        ->where('articles.status_id', 1)
                        ->orderBy('articles.created_at', 'desc')
                        ->skip( $limit * ($page-1) )
                        ->take($limit)
                        ->get();

        $result->totalItems = $this->totalByTag();
        $result->items = $articles->all();

        return $result;
    }

    /**
     * Create a new Article
     *
     * @param array  Data to create a new object
     * @return boolean
     */
    public function create(array $data)
    {
        if (! $this->valid($data)) return false;

        // Create the model
        $model = $this->model->create($data);
        $this->storeById($model->id, array('sort' => $model->id));

        is_null($data['tags']) ?: $this->syncTags($model, $data['tags']);

        return true;
    }

    /**
     * Update an existing Article
     *
     * @param array  Data to update an Article
     * @return boolean
     */
    public function update($id, array $data)
    {
        if (! $this->valid($data)) return false;

        $model = $this->model->find($id)->fill($data);
        is_null($data['tags']) ?: $this->syncTags($model, $data['tags']);
        $model->save();

        return true;
    }

    /**
     * Store data by id
     * @param  int $id
     * @param  array $data
     * @return void
     */
    public function storeById($id, $data)
    {
        $this->model->where('id', '=', $id)->update($data);
    }

    /**
     * Delete the model passed in
     * @param  Eloquent $model The description
     * @return void
     */
    public function delete()
    {
        $this->model->delete();
    }

    /**
     * Sync tags
     * @param \Illuminate\Database\Eloquent\Model
     * @param mixed  $tags can be array or string
     * @return void
     */
    public function syncTags($model, $tags)
    {
        $tags = $this->tag->getTagsArray($tags);

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
     * Get total count
     *
     * @todo I hate that this is public for the decorators.
     *       Perhaps interface it?
     * @return int  Total articles
     */
    protected function totalRows($model)
    {
        return $model->count();
    }

    /**
     * Test if form validator passes
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }

    /**
     * Return any validation errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

}