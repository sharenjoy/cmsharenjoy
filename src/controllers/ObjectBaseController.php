<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Utilities\Transformer;
use View, Redirect, Input, Request, Config, Event;
use Response, Paginator, Message, Session;

abstract class ObjectBaseController extends BaseController {

    /**
     * The model to work with for editing stuff
     */
    protected $handler;

    /**
     * The default number of pagination
     * @var integer
     */
    protected $paginationCount;

    /**
     * These are the data of array that don't need to filter
     * @var array
     */
    protected $filterExcept = ['filter', 'perPage', 'page'];

    /**
     * ObjectBaseController construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->paginationCount = Config::get('cmsharenjoy::app.paginationCount');
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
        
        $input = Input::all();
        $limit = Input::get('perPage', $this->paginationCount);
        $page  = Input::get('page', 1);
        $query = Request::query();

        $model = $this->handler->makeQuery();

        if (isset($input['filter']))
        {
            $filter = array_except($query, $this->filterExcept);
            $this->handler->filter($filter, $model);
        }

        $items = $this->handler->showByPage($limit, $page, array_except($query, ['page']), $model);

        $filterForm = $this->handler->formaker($input, 'filter');

        $this->layout->with('paginationCount', $limit)
                     ->with('sortable', false)
                     ->with('listConfig', $this->listConfig)
                     ->with('filterForm', $filterForm)
                     ->with('items', $items);
    }

    /**
     * To sort item of page.
     * @return   View
     */
    public function getSort()
    {
        $this->setGoBackPrevious();

        $input = Input::all();
        $limit = Input::get('perPage', $this->paginationCount);
        $page  = Input::get('page', 1);
        $query = Request::query();

        $model = $this->handler->makeQuery();

        $items = $this->handler->showByPage($limit, $page, array_except($query, ['page']), $model);

        $filterForm = $this->handler->formaker($input, 'filter');

        $this->layout->with('paginationCount', $limit)
                     ->with('sortable', true)
                     ->with('listConfig', $this->listConfig)
                     ->with('items', $items);       
    }

    /**
     * The new object
     * @return View
     */
    public function getCreate()
    {
        $this->layout->with('fieldsForm', $this->handler->formaker());
    }

    /**
     * The generic method for the start of editing something
     * @return View
     */
    public function getUpdate($id)
    {
        $model = $this->handler->showById($id);

        if ( ! $model)
        {
            Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $id]));

            return Redirect::to(Session::get('goBackPrevious'));
        }

        $fieldsForm = $this->handler->formaker($model);

        $this->layout->with('item' , $model)
                     ->with('fieldsForm', $fieldsForm);

        Event::fire('cmsharenjoy.afterAction', [$model]);
    }

    /**
     * The new object method, very generic, 
     * just allows mass assignable stuff to be filled and saved
     * @return Redirect
     */
    public function postCreate()
    {
        if ( ! $this->handler->setInput(Input::all())->validate())
        {
            return Redirect::to($this->createUrl)->withInput();
        }

        $result   = $this->handler->create();    
        
        $redirect = Input::has('exit') ? $this->objectUrl
                                       : $this->updateUrl.$result['data']->id;

        Message::success($result['message']);

        return Redirect::to($redirect);
    }

    /**
     * The method to handle the posted data
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postUpdate($id)
    {
        if ( ! $this->handler->setInput(Input::all())->validate($id))
        {
            return Redirect::to($this->updateUrl.$id)->withInput();
        }

        $result   = $this->handler->update($id);

        $redirect = Input::has('exit') ? Session::get('goBackPrevious')
                                       : $this->updateUrl.$id;

        Message::success($result['message']);

        return Redirect::to($redirect);
    }

    /**
     * Delete an object based on the ID passed in
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function postDelete()
    {
        $id     = Input::get('id');
        $result = $this->handler->delete($id);

        if ( ! $result['status'])
        {
            Message::error($result['message']);

            return Redirect::to(Session::get('goBackPrevious'));
        }

        Message::success($result['message']);

        return Redirect::to(Session::get('goBackPrevious'));
    }

    /**
     * Before delete an object get some information
     * @return Response object
     */
    public function postDeleteconfirm()
    {
        if( ! Request::ajax()) Response::json('error', 400);

        $id    = Input::get('id');
        $model = $this->handler->showById($id);

        if ( ! $model)
        {
            $message = trans('cmsharenjoy::exception.not_found', ['id' => $id]);
            $subject = trans('cmsharenjoy::app.some_wrong');

            return Response::json(Message::result('error', $message, $subject), 200);
        }

        $result['title']   = Transformer::title($model->toArray());
        $result['subject'] = trans('cmsharenjoy::app.confirm_deleted');

        return Response::json(Message::result('success', '', $result), 200);
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
                    $this->handler->edit($id, array('sort' => $sort));
                }
                catch (\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
                {
                    Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $id]));
                    return Redirect::to($this->objectUrl);
                }
            }
        }

        $message = trans('cmsharenjoy::app.success_ordered');
        $data = ['success' => trans('cmsharenjoy::app.success')];
        
        return Response::json(Message::result('success', $message, $data), 200);
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
        $commonLayout = Config::get('cmsharenjoy::app.commonLayoutDirectory');
        
        $pathA = $this->onController.'.'.$action;
        $pathB = $commonLayout.'.'.$action;

        if (View::exists($this->urlSegment.'.'.$pathA))
        {
            $this->layout = View::make($this->urlSegment.'.'.$pathA);
        }
        elseif (View::exists('cmsharenjoy::'.$pathA))
        {
            $this->layout = View::make('cmsharenjoy::'.$pathA);
        }
        else if(View::exists($this->urlSegment.'.'.$pathB))
        {
            $this->layout = View::make($this->urlSegment.'.'.$pathB);
        }
        else if(View::exists('cmsharenjoy::'.$pathB))
        {
            $this->layout = View::make('cmsharenjoy::'.$pathB);
        }
    }
    
}