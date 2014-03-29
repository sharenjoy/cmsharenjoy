<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
use View, Redirect, Input, App, Request, Config, Response, Lang, Paginator;
use Session, Debugbar, Message;

abstract class ObjectBaseController extends BaseController {

    /**
     * The model to work with for editing stuff
     */
    protected $repository;

    /**
     * By default a mass assignment is used to validate things on a model
     * Sometimes you want to confirm inputs (such as password confirmations)
     * that you don't want to be necessarily stored on the model. This will validate
     * inputs from Input::all() not from $model->fill();
     * @var boolean
     */
    protected $validateWithInput = false;

    /**
     * This is the MessageBag instance
     * @var MessageBag
     */
    protected $messages;

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

        $filterForm = $this->repository->composeForm($this->filterFormConfig, 'list-filter', Input::all());
        $filterForm ? View::share('filterForm', $filterForm) : '';

        // Debugbar::info($filterForm);
        // Debugbar::info(Input::all());
        // Message::add('info', 'test')->flash();

    }

    /**
     * Main users page.
     * @return   View
     */
    public function getIndex($sort = '')
    {
        $perPage    = Input::get('perPage');
        $page       = Input::get('page', 1);
        $query      = Request::query();
        
        $perPage    = empty($perPage) || $perPage == '' ? $this->paginationCount : $perPage;
        $sortable   = $sort == 'sort' ? true : false;
        $filterable = $sort == 'sort' ? false : true;
        
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
                     ->with('sortable', $sortable)
                     ->with('filterable', $filterable)
                     ->with('listConfig', $this->listConfig)
                     ->with('items', $items)
                     ->with('messages', Message::getMessageBag());
    }

    /**
     * The new object
     * @return View
     */
    public function getCreate()
    {
        $this->layout->with('fieldsForm', $this->repository->setModelForm('create-form'))
                     ->with('messages', Message::getMessageBag());
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

        $fieldsForm = $this->repository->setModelForm('update-form', $item);

        $this->layout->with('item' , $item)
                     ->with('fieldsForm', $fieldsForm)
                     ->with('messages', Message::getMessageBag());
    }

    /**
     * Delete an object based on the ID passed in
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function getDelete($id)
    {
        try {
            $this->repository->deleteOne($id);
        }
        catch(EntityNotFoundException $e)
        {
            return Redirect::to($this->objectUrl);
        }

        // delete success message
        Message::merge(array('success'=>'The item was successfully removed.'))->flash();

        return Redirect::to($this->objectUrl)
                    ->with('messages', Message::getMessageBag());
    }

    /**
     * The new object method, very generic, 
     * just allows mass assignable stuff to be filled and saved
     * @return Redirect
     */
    public function postCreate()
    {   
        // To create data
        $result = $this->repository->createOne(Input::all());

        if ( ! $result)
        {   
            return Redirect::to($this->createUrl)
                        ->with('messages', Message::getMessageBag())
                        ->withInput();
        }
        else
        {
            Message::merge(array('success'=>'Item Created'))->flash();
            return Redirect::to($this->objectUrl)
                        ->with('messages', Message::getMessageBag());
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
        $result = $this->repository->updateOne($id, Input::all());

        if ( ! $result)
        {
            return Redirect::to($this->updateUrl.$id)
                        ->with('messages', Message::getMessageBag())
                        ->withInput();
        }
        else
        {
            Message::merge(array('success'=>'Item Saved'))->flash();
            return Redirect::to($this->updateUrl.$id)
                        ->with('messages', Message::getMessageBag());
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
    
}