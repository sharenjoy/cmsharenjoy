<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Session, Paginator, StdClass, Message;

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
     * Return a instance of validator
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Set the form input
     * @param array $input
     */
    public function setInput(array $input)
    {
        $this->model->setInput($input);

        return $this;
    }

    /**
     * Return the form input
     * @return array
     */
    public function getInput()
    {
        return $this->model->getInput();
    }

    /**
     * To validate the form result that follow rules
     * @param  int $id
     * @param  string $rule
     * @param  string $errorType
     * @return boolean
     */
    public function validate($id = null, $rule = 'updateRules', $errorType = 'error')
    {
        if ($id !== null)
        {
            if (isset($this->validator->$rule))
            {
                $this->validator->setRule($rule);
            }
            
            return $this->validator->setUnique($id)->valid($this->getInput(), $errorType);
        }

        return $this->validator->valid($this->getInput(), $errorType);
    }

    /**
     * To filter the items that have given
     * @param  array  $items
     * @param  Model $model
     * @return Builder
     */
    public function filter(array $filterItems, $model = null)
    {
        $model = $model ?: $this->model;

        if (count($filterItems))
        {
            foreach ($filterItems as $key => $value)
            {
                $method = camel_case($key);
                $model = $model->$method($value);
            }
        }

        return $model;
    }

    /**
     * Create a new item
     * @return Model
     */
    public function create()
    {
        $model = $this->model->create($this->getInput());

        return $model;
    }

    /**
     * Update an existing item
     * @param  int  This variable is primary key that wants to update
     * @return boolean
     */
    public function update($id)
    {
        $result = $this->model->find($id)->fill($this->getInput())->save();

        return $result;
    }

    /**
     * Edit data by id
     * @param  mixed $value
     * @param  array $data
     * @param  string $field
     * @return int How many record has been changed
     */
    public function edit($value, array $data, $field = 'id')
    {
        $result = $this->model->where($field, $value)->update($data);
        
        return $result;
    }

    /**
     * Delete the model passed in
     * @param  int This is the id that needs to delete
     * @return boolean
     */
    public function delete($id)
    {
        try
        {
            $model  = $this->model->findOrFail($id);
            $result = $model->delete();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            Message::error(pick_trans('exception.not_found', ['id' => $id]));
            return false;
        }
        
        return $result;
    }

    /**
     * Get a record by its ID
     * @param  integer $id The ID of the record
     * @param  Model   $model
     * @return Builder|Model|null
     */
    public function showById($id, $model = null)
    {
        $model = $model ?: $this->model;

        $model = $model->find($id);

        return $model;
    }

    /**
     * Get paginated articles
     * @param int $limit Results per page
     * @param int $page Number of articles per page
     * @param array $query from Request::query()
     * @param Model
     * @return Paginator Object
     */
    public function showByPage($limit, $page, $query = null, $model = null)
    {
        $model = $model ?: $this->model;

        // Get the total rows of model
        $total = $model->count();
        
        // get the pagenation
        $rows  = $model->skip($limit * ($page-1))
                       ->take($limit)
                       ->get();

        $items = $rows->all();

        // Set Pagination of data 
        if (Session::get('sharenjoy.whichEnd') == 'backEnd')
        {
            Paginator::setViewName('pagination::slider-3');
        }
        
        $result = Paginator::make($items, $total, $limit);

        if ($query) $result = $result->appends($query);

        return $result;
    }

    /**
     * To show all the rows form model
     * @param  string $sort
     * @param  string $order
     * @param  Model $model
     * @return Model
     */
    public function showAll($model = null)
    {
        $model = $model ?: $this->model;

        $model = $model->listQuery();
        
        return $model->get();
    }

    /**
     * Get total count
     * @todo I hate that this is public for the decorators.
     *       Perhaps interface it?
     * @return int  Total articles
     */
    public function total($model = null)
    {
        $model = $model ?: $this->model;

        return $model->count();
    }

    /**
     * To make query from model setting
     * @param  string $method
     * @param  Model $model
     * @return Model
     */
    public function makeQuery($method = 'listQuery', $model = null)
    {
        $model = $model ?: $this->model;

        return $model->$method();
    }

    /**
     * To set all of the lists veriable to session
     * form the all method of model
     * If the method name if start to 'get' and end to 'Lists'
     */
    public function setAllLists()
    {
        $results = [];
        $className = strtolower($this->model->getReflection()->getShortName());

        foreach (get_class_methods($this->model) as $method)
        {
            if (preg_match('/^get(.+)Lists$/', $method, $matches))
            {
                $key = strtolower($matches[1]);
                $results[$className][$key] = $this->model->$method();
            }
        }

        if (count($results))
        {
            Session::put('allLists', $results);
        }
    }

}