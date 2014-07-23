<?php namespace Sharenjoy\Cmsharenjoy\Core;

use Session, Formaker, Poster;

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

    public function __construct()
    {
        Poster::setModel($this->model);
    }

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
     * Create a new Article
     * @param array  Data to create a new object
     * @return string The insert id
     */
    public function create(array $input)
    {
        // Compose some necessary variable to input data
        $input = Poster::compose($input, $this->model->createComposeItem);
        
        $result = $this->validator->valid($input);
        if ( ! $result->status) return false;

        // Create the model
        $model = $this->model->create($input);

        return $model;
    }

    /**
     * Update an existing Article
     * @param array  Data to update an Article
     * @return boolean
     */
    public function update($id, array $input, $vaidatorRules = 'updateRules')
    {
        // Compose some necessary variable to input data
        $input = Poster::compose($input, $this->model->updateComposeItem);

        $result = $this->validator->valid($input, $vaidatorRules, [$this->model->uniqueFields, $id]);
        if ( ! $result->status) return false;

        // Update the model
        $model = $this->model->find($id)->fill($input);
        $model->save();

        return $model;
    }

    /**
     * Edit data by id
     * @param  int $id
     * @param  array $data
     * @return void
     */
    public function edit($value, $data, $field = 'id')
    {
        $model = $this->model->where($field, $value)->update($data);
        
        if ( ! $model)
        {
            throw new \Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
        }
        return $model;
    }

    /**
     * Delete the model passed in
     * @param  int This is the id that needs to delete
     * @return void
     */
    public function delete($id)
    {
        $model = $this->model->find($id);

        if ( ! $model)
        {
            throw new \Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
        }
        $model->delete();
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

    public function getForm($input = array(), $formType = null)
    {
        $type          = $formType ?: Session::get('doAction');
        $typeConfigStr = $type.'FormConfig';
        $typeDenyStr   = $type.'FormDeny';
        $typeConfig    = $this->model->$typeConfigStr ?: [];
        
        switch ($type)
        {
            case 'create':
            case 'update':
                $formConfig = array_merge($this->model->formConfig, $typeConfig);

                // Deny some fields
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
                break;

            case 'filter':
                $formConfig = $typeConfig;
                break;
        }
        return $this->composeForm($formConfig, $type, $input);
    }

    /**
     * Compose some of useful form fields
     * @param  array  $config The config
     * @param  string $type  The type of form fields
     * @return array
     */
    public function composeForm(array $config, $type, $input = array())
    {
        if (count($config))
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
            $result = Formaker::makeForm($config, $type);

            if( ! $result)
            {
                throw new \Sharenjoy\Cmsharenjoy\Exception\ComposeFormException;
            }
            return $result;
        }
        return false;
    }

}