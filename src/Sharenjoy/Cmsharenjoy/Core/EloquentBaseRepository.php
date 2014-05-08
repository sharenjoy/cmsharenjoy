<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
use Sharenjoy\Cmsharenjoy\Exception\ComposeFormException;
use Debugbar, StdClass, Str, Session, Message, Lang, Formaker;

/**
 * Base Eloquent Repository Class Built On From Shawn McCool <-- This guy is pretty amazing
 */
abstract class EloquentBaseRepository implements EloquentBaseInterface {

    /**
     * The instance of model
     * @var Eloquent
     */
    protected $model;

    /**
     * The instance of Vaildator
     * @var ValidableInterface
     */
    protected $validator;

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

    public function pushFormConfig($type = 'list', $data)
    {
        
        switch ($type)
        {
            case 'list':
                if (isset($this->model->formConfig))
                    $this->model->formConfig = array_merge($this->model->formConfig, $data);
                break;
            case 'create':
                if (isset($this->model->createFormConfig))
                    $this->model->createFormConfig = array_merge($this->model->createFormConfig, $data);
                break;
            case 'update':
                if (isset($this->model->updateFormConfig))
                    $this->model->updateFormConfig = array_merge($this->model->updateFormConfig, $data);
                break;
        }
    }

    /**
     * Get a record by its ID
     * @param  integer $id The ID of the record
     * @return Eloquent
     */
    public function byId($id)
    {
        $model = $this->model->find($id);

        if ( ! $model)
        {
            Message::merge(array(
                'errors' => Lang::get('cmsharenjoy::exception.not_found', array('id' => $id))
            ))->flash();
            throw new EntityNotFoundException();
        }

        // Do some final things in the repository
        $model = $this->finalProcess(Session::get('onAction'), $model);

        return $model;
    }

    /**
     * Get a record by it's slug
     * @param  string $slug The slug name
     * @return Eloquent
     */
    public function bySlug($slug)
    {
        $model = $this->model->where('slug', $slug)->first();

        if ( ! $model)
        {
            throw new EntityNotFoundException;
        }

        return $model;
    }

    /**
     * Get paginated articles
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
     * Get articles by their tag
     * @param string  URL slug of tag
     * @param int Number of articles per page
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function byTag($tag, $page = 1, $limit)
    {
        $foundTag = $this->model->where('slug', $tag)->first();

        $result = new StdClass;
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
     * @param array  Data to create a new object
     * @return string The insert id
     */
    public function create(array $input)
    {
        $data = $this->composeInputData($input);

        if ( ! $this->valid($data))
        {
            $this->getErrorsToFlashMessageBag();
            return false;
        }

        // Create the model
        $model = $this->model->create($data);
        $this->storeById($model->id, array('sort' => $model->id));
        
        $model = $this->finalProcess(Session::get('onAction'), $model, $data);

        return $model->id;
    }

    /**
     * Update an existing Article
     * @param array  Data to update an Article
     * @return boolean
     */
    public function update($id, array $input)
    {
        $data = $this->composeInputData($input);

        if(isset($this->model->uniqueFields) && count($this->model->uniqueFields))
        {
            $this->validator->setUniqueUpdateFields($this->model->uniqueFields, $id);
        }
        
        if ( ! $this->valid($data))
        {
            $this->getErrorsToFlashMessageBag();
            return false;
        }

        $model = $this->model->find($id)->fill($data);

        $model = $this->finalProcess(Session::get('onAction'), $model, $data);
        
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
        $model = $this->model->where('id', $id)->update($data);
        
        if ( ! $model) {
            throw new EntityNotFoundException;
        }
    }

    /**
     * Delete the model passed in
     * @param  int This is the id that needs to delete
     * @return void
     */
    public function delete($id)
    {
        $model = $this->model->find($id);

        if ( ! $model) {
            Message::merge(array(
                'errors' => Lang::get('cmsharenjoy::exception.not_found', array('id' => $id))
            ))->flash();
            throw new EntityNotFoundException;
        }

        $model->delete();
    }

    protected function lists($title, $id = 'id', $model = null)
    {
        $model = $model ?: $this->model;
        return $model->lists($title, $id);
    }

    /**
     * Get total count
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
     * @param  array The input needs to valid
     * @return boolean
     */
    protected function valid(array $input, $validator = null)
    {
        $validator = $validator ?: $this->validator;
        return $validator->with($input)->passes();
    }

    /**
     * Return any validation errors
     * @return array
     */
    public function errors($validator = null)
    {
        $validator = $validator ?: $this->validator;
        return $validator->errors();
    }

    /**
     * Merge message to flashMessageBag
     * @return void
     */
    public function getErrorsToFlashMessageBag($validator = null)
    {
        $validator = $validator ?: $this->validator;

        if ($validator->getErrorsToArray())
        {
            foreach ($validator->getErrorsToArray() as $message)
            {
                Message::merge(array('errors' => $message))->flash();
            }
        }
    }

    /**
     * Compose some of useful form fields
     * @param  array  $config The config
     * @param  string $type  The type of form fields
     * @return array
     */
    public function composeForm(array $config, $type, $input = array())
    {
        if (is_array($config) && count($config))
        {
            foreach ($config as $key => $value)
            {
                if (isset($value['model']) && isset($value['item']))
                {
                    $config[$key]['option'] = $this->$value['model']->lists($value['item']);
                }

                // If use custom key of value otherwise use the $key
                if (isset($value['input']) && isset($input[$value['input']]))
                {
                    $config[$key]['value'] = $input[$value['input']];
                }
                elseif(isset($input[$key]))
                {
                    $config[$key]['value'] = $input[$key];
                }
            }
            
            // Compose form fields from Formaker
            $fieldsForm = Formaker::composeForm($config, $type);

            if( ! $fieldsForm)
            {
                throw new ComposeFormException;
            }

            return $fieldsForm;
        }

        return false;
    }

    public function setFormFields($input = array())
    {
        $type          = Session::get('doAction');
        $typeConfigStr = $type.'FormConfig';
        $typeDenyStr   = $type.'FormDeny';
        $typeConfig    = $this->model->$typeConfigStr ?: [];

        $formConfig = array_merge($this->model->formConfig, $typeConfig);

        if( ! empty($this->model->$typeDenyStr))
        {
            foreach ($this->model->$typeDenyStr as $value)
            {
                unset($formConfig[$value]);
            }
        }

        // To order the form config
        $formConfig = array_sort($formConfig, function($value)
        {
            return $value['order'];
        });
        
        $fieldsForm = $this->composeForm($formConfig, $type, $input);

        return $fieldsForm;
    }

    /**
     * Compose input data and slug to an array
     * @param  array  $data      Input data
     * @param  string $separator separator
     * @return array
     */
    protected function composeInputData(array $data)
    {
        // slug
        if ( ! empty($this->slug))
        {
            $data = array_merge($data, array(
                'slug' => Str::slug($data[$this->slug], '-')
            ));
        }

        // Author
        $data = array_merge($data, array(
            'user_id' => Session::get('user')->id
        ));
        
        return $data;
    }

}