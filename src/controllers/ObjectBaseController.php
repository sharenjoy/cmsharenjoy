<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use View, Redirect, Input, Request, Config;
use Response, Paginator, Message, Poster, Session;

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
        // switch pagination count
        if (Input::get('query_string'))
        {
            parse_str(Input::get('query_string'), $query);
            $query['perPage'] = Input::get('perPage');
            unset($query['page']);

            return Redirect::to($this->objectUrl.'?'.http_build_query($query));
        }

        $this->setGoBackPrevious();
        
        $perPage = Input::get('perPage', $this->paginationCount);
        $page    = Input::get('page', 1);
        $query   = Request::query();
        $model   = Poster::getModel();
        
        // Set filter form fields
        $filterForm = $this->repository->getForm(Input::all(), 'filter');
        if ($filterForm)
        {
            View::share('filterForm', $filterForm);
            $filter = array_except($query, $this->filterExcept);
            // $model  = Poster::filter($filter, $model);
        }

        $model  = $this->makeQuery($model);
        $result = Poster::setModel($model)->hasQuery()->showByPage($page, (int)$perPage);

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
        $this->setGoBackPrevious();

        $perPage = Input::get('perPage', $this->paginationCount);
        $page    = Input::get('page', 1);
        $query   = Request::query();
        $model   = Poster::getModel();

        $model  = $this->makeQuery($model);
        $result = Poster::setModel($model)->hasQuery()->showByPage($page, (int)$perPage);

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
     * Set what kind of data that we went to get
     * @param  Model $model
     * @return Model
     */
    protected function makeQuery($model)
    {
        return $model->orderBy('sort', 'DESC');
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
            Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $id]));
            return Redirect::to($this->objectUrl);
        }

        $this->layout->with('item' , $model)
                     ->with('fieldsForm', $fieldsForm);
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
            $validator = $this->repository->getValidator();
            return Redirect::to($this->createUrl)->withInput()->withErrors($validator->errors());
        }
        
        Message::success(trans('cmsharenjoy::app.success_created'));

        if (Input::has('exit')) return Redirect::to($this->objectUrl);
        return Redirect::to($this->updateUrl.$result->id);
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
        
        Message::success(trans('cmsharenjoy::app.success_updated'));

        if (Input::has('exit')) return Redirect::to(Session::get('goBackPrevious'));
        return Redirect::to($this->updateUrl.$id);
    }

    /**
     * Delete an object based on the ID passed in
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function postDelete()
    {
        try
        {
            $id = Input::get('id');
            $this->repository->delete($id);
        }
        catch(\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
        {
            Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $id]));
            return Redirect::to($this->objectUrl);
        }

        Message::success(trans('cmsharenjoy::app.success_deleted'));
        return Redirect::to($this->objectUrl);
    }

    /**
     * Before delete an object get some information
     * @return Response object
     */
    public function postDeleteconfirm()
    {
        if( ! Request::ajax()) Response::json('error', 400);

        $id        = Input::get('id');
        $data      = Poster::showById($id)->toArray();
        $existsAry = ['title', 'name', 'subject', 'tag'];
        
        $result['subject'] = trans('cmsharenjoy::app.confirm_deleted');

        foreach ($existsAry as $value)
        {
            if (array_key_exists($value, $data))
            {
                $result['title'] = $data[$value];
                break;
            }
        }

        return Response::json(Message::json('success', '', $result), 200);
    }

    /**
     * Set the order of the list
     * @return Response
     */
    public function postOrder()
    {
        // This should only be accessible via AJAX you know...
        if( ! Request::ajax()) Response::json('error', 400);

        $id_value   = Input::get('id_value');
        $sort_value = Input::get('sort_value');
        
        foreach($id_value as $key => $id)
        {
            if($id)
            {
                try
                {
                    $sort = $sort_value[$key];
                    $this->repository->edit($id, array('sort' => $sort));
                }
                catch (\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
                {
                    Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $id]));
                    return Redirect::to($this->objectUrl);
                }
            }
        }

        return Response::json(Message::json('success', trans('cmsharenjoy::app.success_ordered'), ['success'=>trans('cmsharenjoy::app.success')]), 200);
    }

    /**
     * Set go back previous session
     */
    protected function setGoBackPrevious()
    {
        Session::put('goBackPrevious', Request::fullUrl());
    }

    /**
     * Setting the output layout priority
     * @return view
     */
    protected function setupLayout()
    {
        $action = $this->doAction;

        // If action equat sort so that set the action to index
        $action = $this->onAction == 'get-sort' ? 'index' : $action;
        
        // Get reop directory from config
        $commonRepoLayout = Config::get('cmsharenjoy::app.commonRepoLayoutDirectory');
        
        if (View::exists('cmsharenjoy::'.$this->onController.'.'.$action))
        {
            $this->layout = View::make('cmsharenjoy::'.$this->onController.'.'.$action);
        }
        else if(View::exists('cmsharenjoy::'.$commonRepoLayout.'.'.$action))
        {
            $this->layout = View::make('cmsharenjoy::'.$commonRepoLayout.'.'.$action);
        }
    }
    
}