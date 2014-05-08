<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
use View, Redirect, Input, App, Request, Config, Response, Lang, Paginator, Categorize;
use Session, Debugbar, Message, Sentry, Theme, API;

abstract class ObjectBaseController extends BaseController {

    /**
     * The model to work with for editing stuff
     */
    protected $repository;

    /**
     * These are the data of array that don't need to filter
     * @var array
     */
    protected $filterExcept = array(
        'filter',
        'perPage', 
        'page'
    );

    /**
     * The default number of pagination
     * @var integer
     */
    protected $paginationCount = 15;

    /**
     * ObjectBaseController construct
     */
    public function __construct()
    {
        parent::__construct();

        // Debugbar::info($filterForm);
        // $environment = App::environment();
        // Debugbar::info(Session::get('action'));

        // Message::add('info', 'test')->flash();
    }

    /**
     * Main users page.
     * @return   View
     */
    public function getIndex()
    {
        $perPage    = Input::get('perPage');
        $page       = Input::get('page', 1);
        $query      = Request::query();
        $perPage    = isset($perPage) ?: $this->paginationCount;

        // Set filter form fields
        if (isset($this->filterFormConfig) && !is_null($this->filterFormConfig))
        {
            $filterForm = $this->repository->composeForm($this->filterFormConfig, 'list-filter', Input::all());
            $filterForm ? View::share('filterForm', $filterForm) : '';
        }
        
        // Get the model from repositroy
        $model = $this->repository->getModel();

        // If we get the request of get contains filter
        // We will do some filter things
        if (Input::get('filter'))
        {
            $filter = array_except($query, $this->filterExcept);
            $model = $this->repository->setFilterQuery($model, $filter, $this->filterFormConfig);
        }

        $result = $this->repository->byPage($page, $perPage, $model);

        // Do some final things process
        $result->items = $this->repository->finalProcess($this->onAction, $result->items);

        // Set Pagination of data 
        $items = Paginator::make($result->items, $result->totalItems, $perPage)->appends($query);

        $this->layout->with('paginationCount', $perPage)
                     ->with('sortable', false)
                     ->with('filterable', true)
                     ->with('listConfig', $this->listConfig)
                     ->with('items', $items);
    }

    /**
     * To sort item of page.
     * @return   View
     */
    public function getSort()
    {
        $perPage    = Input::get('perPage');
        $page       = Input::get('page', 1);
        $perPage    = isset($perPage) ?: $this->paginationCount;
        
        // Get the model from repositroy
        $model  = $this->repository->getModel();
        $result = $this->repository->byPage($page, $perPage, $model);

        // Set Pagination of data 
        $items = Paginator::make($result->items, $result->totalItems, $perPage);

        $this->layout->with('paginationCount', $perPage)
                     ->with('sortable', true)
                     ->with('filterable', false)
                     ->with('listConfig', $this->listConfig)
                     ->with('items', $items);
    }

    /**
     * The new object
     * @return View
     */
    public function getCreate()
    {
        $this->layout->with('fieldsForm', $this->repository->setFormFields());
    }

    /**
     * The generic method for the start of editing something
     * @return View
     */
    public function getUpdate($id)
    {
        // Catch some validation if can't get the data
        try {
            $item = $this->repository->byId($id);
        }
        catch(EntityNotFoundException $e)
        {
            return Redirect::to($this->objectUrl);
        }

        $fieldsForm = $this->repository->setFormFields($item);

        $this->layout->with('item' , $item)
                     ->with('fieldsForm', $fieldsForm);
    }

    /**
     * Delete an object based on the ID passed in
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function getDelete($id)
    {
        try {
            $this->repository->delete($id);
        }
        catch(EntityNotFoundException $e)
        {
            return Redirect::to($this->objectUrl);
        }

        // delete success message
        Message::merge(array('success'=>trans('cmsharenjoy::admin.success_deleted')))->flash();

        return Redirect::to($this->objectUrl);
    }

    /**
     * The new object method, very generic, 
     * just allows mass assignable stuff to be filled and saved
     * @return Redirect
     */
    public function postCreate()
    {   
        // To create data
        $result = $this->repository->create(Input::all());

        if ( ! $result)
        {
            return Redirect::to($this->createUrl)->withInput();
        }
        else
        {
            Message::merge(array('success'=>trans('cmsharenjoy::admin.success_created')))->flash();
            return Redirect::to($this->objectUrl);
        }
    }

    /**
     * The method to handle the posted data
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postUpdate($id)
    {
        // To update data
        $result = $this->repository->update($id, Input::all());

        if ( ! $result)
        {
            return Redirect::to($this->updateUrl.$id)->withInput();
        }
        else
        {
            Message::merge(array('success'=>trans('cmsharenjoy::admin.success_updated')))->flash();
            return Redirect::to($this->updateUrl.$id);
        }
    }

    /**
     * Set the order of the list
     * @return Response
     */
    public function postOrder()
    {
        // This should only be accessible via AJAX you know...
        if( ! Request::ajax())
        {
            Response::json('error', 400);
        }

        $id_value   = Input::get('id_value');
        $sort_value = Input::get('sort_value');
        
        foreach($id_value as $key => $id)
        {
            if($id)
            {
                $sort = $sort_value[$key];
                $this->repository->storeById($id, array('sort' => $sort));
            }
        }

        return Response::json('success', 200);
    }

    protected function setupLayout()
    {
        $action = $this->doAction;

        // If action equat sort so that set the action to index
        $action = $this->onAction == 'get-sort' ? 'index' : $action;
        
        // Get reop directory from config
        $commonRepoLayout = Config::get('cmsharenjoy::app.commonRepoLayoutDirectory');
        
        if (View::exists('cmsharenjoy::'.$this->appName.'.'.$action))
        {
            $this->layout = View::make('cmsharenjoy::'.$this->appName.'.'.$action);
        }
        else if(View::exists('cmsharenjoy::'.$commonRepoLayout.'.'.$action))
        {
            $this->layout = View::make('cmsharenjoy::'.$commonRepoLayout.'.'.$action);
        }
    }
    
}