<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Session, Formaker, Paginator, StdClass, Message;

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
     * @return array This array is from Message::result()
     */
    public function create()
    {
        $model = $this->model->create($this->getInput());

        $result = Message::result(true, trans('cmsharenjoy::app.success_created'), $model);

        return $result;
    }

    /**
     * Update an existing item
     * @param  int  This variable is primary key that wants to update
     * @return array This array is from Message::result()
     */
    public function update($id)
    {
        $model = $this->model->find($id)->fill($this->getInput());

        $model->save();

        $result = Message::result(true, trans('cmsharenjoy::app.success_updated'), $model);

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
            return Message::result(false, trans('cmsharenjoy::exception.not_found', ['id' => $id]));
        }
        
        return Message::result(true, trans('cmsharenjoy::app.success_deleted'));
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
     * What this method doing is can dynamic push
     * some form data config to model's form config
     * @param  array  $data
     * @param  string $form
     * @return void
     */
    public function pushForm(array $data, $form = 'formConfig')
    {
        $this->model->pushForm($data, $form);
    }

    /**
     * To make a form fields
     * @param  array  $input
     * @param  String $formType
     * @param  array  $config This is you can customise form config
     * @return array  Build from Formaker
     */
    public function formaker($input = array(), $formType = null, array $config = array())
    {
        $formConfig = $config;
        
        if ( ! count($config))
        {
            $type          = $formType ?: Session::get('onAction');
            $typeConfigStr = $type.'FormConfig';
            $typeDenyStr   = $type.'FormDeny';
            $typeConfig    = $this->model->$typeConfigStr ?: [];

            switch ($type)
            {
                case 'filter':
                    $formConfig = $typeConfig;
                    break;

                default:
                    $formConfig = array_merge($this->model->formConfig, $typeConfig);

                    // Deny some fields
                    if(count($this->model->$typeDenyStr))
                    {
                        foreach ($this->model->$typeDenyStr as $value)
                            unset($formConfig[$value]);
                    }

                    // To order the form config
                    $formConfig = array_sort($formConfig, function($value)
                    {
                        return $value['order'];
                    });
                    break;
            }
        }

        // To do other process that needs to be done
        $formConfig = $this->model->processForm($formConfig, $input);

        return Formaker::form($formConfig, $type);
    }

}