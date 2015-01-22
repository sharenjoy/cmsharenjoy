<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Utilities\Transformer;
use View, Redirect, Input, Request, Config, Event;
use Response, Paginator, Message, Session, Lister;

abstract class ObjectBaseController extends BaseController {

    /**
     * The model to work with for editing stuff
     */
    protected $repo;

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
     * Set go back previous session
     */
    protected function setGoBackPrevious()
    {
        Session::put('goBackPrevious', Request::fullUrl());
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

        $model = $this->repo->makeQuery();

        if (isset($input['filter']))
        {
            $filter = array_except($query, $this->filterExcept);
            $model = $this->repo->filter($filter, $model);
        }

        $items = $this->repo->showByPage($limit, $page, array_except($query, ['page']), $model);

        $forms = $this->repo->formaker($input, 'filter');

        $data = ['paginationCount'=>$limit, 'sortable'=>false, 'rules'=>$this->functionRules];
        $lister = Lister::make($items, $this->listConfig, $data);
        
        $this->layout->with('filterForm', $forms)
                     ->with('listEmpty', $items->isEmpty())
                     ->with('lister', $lister);
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

        $model = $this->repo->makeQuery();

        $items = $this->repo->showByPage($limit, $page, array_except($query, ['page']), $model);

        $filterForm = $this->repo->formaker($input, 'filter');

        $data = ['paginationCount'=>$limit, 'sortable'=>true, 'rules'=>$this->functionRules];
        $lister = Lister::make($items, $this->listConfig, $data);
        
        $this->layout->with('listEmpty', $items->isEmpty())
                     ->with('lister', $lister);      
    }

    /**
     * The new object
     * @return View
     */
    public function getCreate()
    {
        $this->layout->with('fieldsForm', $this->repo->formaker());
    }

    /**
     * The generic method for the start of editing something
     * @return View
     */
    public function getUpdate($id)
    {
        $model = $this->repo->showById($id);

        if ( ! $model)
        {
            Message::error(pick_trans('exception.not_found', ['id' => $id]));

            return Redirect::to(Session::get('goBackPrevious'));
        }

        $fieldsForm = $this->repo->formaker($model);

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
        if ( ! $this->repo->setInput(Input::all())->validate())
        {
            return Redirect::to($this->createUrl)->withInput();
        }

        $result   = $this->repo->create();    
        
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
        if ( ! $this->repo->setInput(Input::all())->validate($id))
        {
            return Redirect::to($this->updateUrl.$id)->withInput();
        }

        $result   = $this->repo->update($id);

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
        $model  = $this->repo->showById($id);
        $result = $this->repo->delete($id);

        if ( ! $result['status'])
        {
            Message::error($result['message']);

            return Redirect::to(Session::get('goBackPrevious'));
        }

        Message::success($result['message']);
        
        Event::fire('cmsharenjoy.afterAction', [$model]);

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
        $model = $this->repo->showById($id);

        if ( ! $model)
        {
            $message = pick_trans('exception.not_found', ['id' => $id]);
            $subject = pick_trans('some_wrong');

            return Response::json(Message::result('error', $message, $subject), 200);
        }

        $result['title']   = Transformer::title($model->toArray());
        $result['subject'] = pick_trans('confirm_deleted');

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
                    $this->repo->edit($id, array('sort' => $sort));
                }
                catch (\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
                {
                    Message::error(pick_trans('exception.not_found', ['id' => $id]));
                    return Redirect::to($this->objectUrl);
                }
            }
        }

        $message = pick_trans('success_ordered');
        $data = ['success' => pick_trans('success')];
        
        return Response::json(Message::result('success', $message, $data), 200);
    }
    
}