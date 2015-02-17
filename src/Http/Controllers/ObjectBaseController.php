<?php namespace Sharenjoy\Cmsharenjoy\Http\Controllers;

use Illuminate\Http\Request;
use Sharenjoy\Cmsharenjoy\Utilities\Transformer;
use View, Redirect, Input, Config, Event;
use Response, Message, Session, Lister, Formaker;

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

        $this->paginationCount = Config::get('cmsharenjoy.paginationCount');
    }

    /**
     * Set go back previous session
     */
    protected function setGoBackPrevious($request)
    {
        Session::put('goBackPrevious', $request->fullUrl());
    }

    /**
     * Main users page.
     * @return   View
     */
    public function getIndex(Request $request)
    {
        // switch pagination count
        if ($request->has('query_string'))
        {
            parse_str($request->input('query_string'), $query);
            $query['perPage'] = $request->input('perPage');
            unset($query['page']);

            return redirect($this->objectUrl.'?'.http_build_query($query));
        }

        $this->setGoBackPrevious($request);

        $this->repo->grabAllLists();
        
        $input = $request->all();
        $limit = $request->input('perPage', $this->paginationCount);
        $query = $request->query();

        $model = $this->repo->makeQuery();

        if (isset($input['filter']))
        {
            $filter = array_except($query, $this->filterExcept);
            $model = $this->repo->filter($filter, $model);
        }

        $items = $this->repo->showByPage($limit, $query, $model);
        
        $forms = Formaker::setModel($this->repo->getModel())->make($input);

        $data = ['paginationCount'=>$limit, 'sortable'=>false, 'rules'=>$this->functionRules];
        $lister = Lister::make($items, $this->listConfig, $data);

        Event::fire('controllerAfterAction', ['']);

        return $this->layout()->with('filterForm', $forms)
                              ->with('listEmpty', $items->isEmpty())
                              ->with('lister', $lister);
    }

    /**
     * To sort item of page.
     * @return   View
     */
    public function getSort(Request $request)
    {
        $this->setGoBackPrevious($request);

        $input = $request->all();
        $limit = $request->input('perPage', $this->paginationCount);
        $query = $request->query();

        $model = $this->repo->makeQuery();

        $items = $this->repo->showByPage($limit, $query, $model);

        $data = ['paginationCount'=>$limit, 'sortable'=>true, 'rules'=>$this->functionRules];
        $lister = Lister::make($items, $this->listConfig, $data);
        
        return $this->layout()->with('listEmpty', $items->isEmpty())
                              ->with('sortable', true)
                              ->with('lister', $lister);
    }

    /**
     * The new object
     * @return View
     */
    public function getCreate()
    {
        $fields = Formaker::setModel($this->repo->getModel())->make();

        return $this->layout()->with('fieldsForm', $fields);
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

            return redirect(Session::get('goBackPrevious'));
        }

        $fields = Formaker::setModel($this->repo->getModel())->make($model->toArray());

        Event::fire('controllerAfterAction', [$model]);

        return $this->layout()->with('item', $model)->with('fieldsForm', $fields);
    }

    /**
     * The new object method, very generic, 
     * just allows mass assignable stuff to be filled and saved
     * @return Redirect
     */
    public function postCreate(Request $request)
    {
        $this->repo->setInput($request->all());

        if ( ! $this->repo->validate())
        {
            return redirect($this->createUrl)->withInput();
        }

        $model = $this->repo->create();

        $model ? Message::success(pick_trans('success_created'))
               : Message::error(pick_trans('fail_created'));
        
        $redirect = $request->has('exit') ? $this->objectUrl : $this->updateUrl.$model->id;

        return redirect($redirect);
    }

    /**
     * The method to handle the posted data
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postUpdate(Request $request, $id)
    {
        $this->repo->setInput($request->all());

        if ( ! $this->repo->validate($id))
        {
            return redirect($this->updateUrl.$id)->withInput();
        }

        $result = $this->repo->update($id);

        $result ? Message::success(pick_trans('success_updated'))
                : Message::error(pick_trans('fail_updated'));

        $redirect = $request->has('exit') ? Session::get('goBackPrevious') : $this->updateUrl.$id;

        return redirect($redirect);
    }

    /**
     * Delete an object based on the ID passed in
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function postDelete(Request $request)
    {
        $id     = $request->input('id');
        $model  = $this->repo->showById($id);
        $result = $this->repo->delete($id);

        $result ? Message::success(pick_trans('success_deleted'))
                : Message::error(pick_trans('fail_deleted'));
        
        Event::fire('controllerAfterAction', [$model]);

        return redirect(Session::get('goBackPrevious'));
    }

    /**
     * Before delete an object get some information
     * @return Response object
     */
    public function postDeleteconfirm(Request $request)
    {
        if( ! $request->ajax()) response()->json('error', 400);

        $id    = $request->input('id');
        $model = $this->repo->showById($id);

        if ( ! $model)
        {
            $message = pick_trans('exception.not_found', ['id' => $id]);

            return Message::json(404, $message);
        }

        $result['title']   = Transformer::title($model->toArray());
        $result['subject'] = pick_trans('confirm_deleted');

        return Message::json(200, '', $result);
    }

    /**
     * Set the order of the list
     * @return Response
     */
    public function postOrder(Request $request)
    {
        // This should only be accessible via AJAX you know...
        if( ! $request->ajax()) response()->json('error', 400);

        $id_value   = $request->input('id_value');
        $sort_value = $request->input('sort_value');
        
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
                    return redirect($this->objectUrl);
                }
            }
        }

        $message = pick_trans('success_ordered');
        $data = ['success' => pick_trans('success')];
        
        return Message::json(200, $message, $data);
    }
    
}