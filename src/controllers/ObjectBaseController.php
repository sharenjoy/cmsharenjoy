<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use View, Redirect, Input, Request, Config;
use Response, Paginator, Message, Poster;

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
        $perPage    = $perPage ?: $this->paginationCount;

        $model      = Poster::getModel();

        // Set filter form fields
        $filterForm = $this->repository->getForm(Input::all(), 'filter');
        if ($filterForm)
        {
            View::share('filterForm', $filterForm);

            $filter = array_except($query, $this->filterExcept);
            $model  = Poster::filter($filter);
        }

        $result = Poster::showByPage($page, (int)$perPage, $model);

        $result->items = Poster::processMulitple($result->items);

        // Set Pagination of data 
        $query = array_except($query, ['page']);
        $items = Paginator::make($result->items, $result->totalItems, $result->limit)->appends($query);

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
        $query      = Request::query();
        $perPage    = $perPage ?: $this->paginationCount;
        
        // Get the model
        $model  = Poster::getModel();

        $result = Poster::showByPage($page, (int)$perPage, $model);

        $result->items = Poster::processMulitple($result->items);

        // Set Pagination of data 
        $query = array_except($query, ['page']);
        $items = Paginator::make($result->items, $result->totalItems, $result->limit)->appends($query);

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
        $this->layout->with('fieldsForm', $this->repository->getForm());
    }

    /**
     * The generic method for the start of editing something
     * @return View
     */
    public function getUpdate($id)
    {
        try
        {
            $model = Poster::showById($id);
            $model = Poster::process($model);

            $fieldsForm = $this->repository->getForm($model);
        }
        catch(\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
        {
            Message::output('msg', 'errors', trans('cmsharenjoy::exception.not_found', array('id' => $id)));
            return Redirect::to($this->objectUrl);
        }

        $this->layout->with('item' , $model)
                     ->with('fieldsForm', $fieldsForm);
    }

    /**
     * Delete an object based on the ID passed in
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function getDelete($id)
    {
        try
        {
            $this->repository->delete($id);
        }
        catch(\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
        {
            Message::output('msg', 'errors', trans('cmsharenjoy::exception.not_found', array('id' => $id)));

            return Redirect::to($this->objectUrl);
        }

        Message::output('msg', 'success', trans('cmsharenjoy::admin.success_deleted'));

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
        
        Message::output('msg', 'success', trans('cmsharenjoy::admin.success_created'));

        return Redirect::to($this->objectUrl);
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

        Message::output('msg', 'success', trans('cmsharenjoy::admin.success_updated'));

        return Redirect::to($this->updateUrl.$id);
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
                $this->repository->store($id, array('sort' => $sort));
            }
        }

        $result = Message::output('ajax', 'success', "排序成功");

        return Response::json($result, 200);
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