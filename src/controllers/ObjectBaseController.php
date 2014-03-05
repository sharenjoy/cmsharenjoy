<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
use Illuminate\Support\MessageBag;
use View, Redirect, Input, App, ReflectionClass, Request, Config;
use Response, URL, Lang, Debugbar, Paginator, Str;

abstract class ObjectBaseController extends BaseController {

    /**
     * The model to work with for editing stuff
     */
    protected $repo;

    /**
     * The URL that is used to edit shit
     * @var string
     */
    protected $editUrl;

    /**
     * The URL to create a new entry
     * @var string
     */
    protected $newUrl;

    /**
     * The URL to delete an entry
     * @var string
     */
    protected $deleteUrl;

    /**
     * The uploads model
     * @var UploadsInterface
     */
    protected $uploads_model;

    /**
     * By default a mass assignment is used to validate things on a model
     * Sometimes you want to confirm inputs (such as password confirmations)
     * that you don't want to be necessarily stored on the model. This will validate
     * inputs from Input::all() not from $model->fill();
     * 
     * @var boolean
     */
    protected $validateWithInput = false;

    /**
     * The default number of pagination
     * 
     * @var integer
     */
    protected $paginationCount = 15;

    /**
     * ObjectBaseController construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->setHandyUrls();
        $this->shareHandyUrls();

        $this->uploads_model = App::make('Sharenjoy\Cmsharenjoy\Uploads\UploadsInterface');

        $this->composeFilterform();
    }

    /**
     * Set the URL's to be used in the views
     * 
     * @return void
     */
    private function setHandyUrls()
    {
        $this->editUrl   = is_null($this->editUrl) ? $this->objectUrl.'/edit/' : null;
        $this->newUrl    = is_null($this->newUrl) ? $this->objectUrl.'/new' : null;
        $this->deleteUrl = is_null($this->deleteUrl) ? $this->objectUrl.'/delete/' : null;
    }

    /**
     * Set the view to have variables detailing
     * some of the key URL's used in the views
     * Trying to keep views generic...
     * 
     * @return void
     */
    private function shareHandyUrls()
    {
        // Share these variables with any views
        View::share('editUrl', $this->editUrl);
        View::share('newUrl', $this->newUrl);
        View::share('deleteUrl', $this->deleteUrl);
    }

    private function composeFilterForm()
    {
        if (is_array($this->filterAry))
        {
            $data = array();
            foreach ($this->filterAry as $key => $value)
            {
                $data[$key] = $value;

                if ($value['source'] == 'database')
                {
                    $model = $this->tag->getModel();
                    $query = $model->with('posts')->get();
                    Debugbar::info($query->toArray());
                }
                else
                {
                    $data[$key]['option'] = Config::get('cmsharenjoy::'.$value['source']);
                }
            }
            Debugbar::info($data);
            // View::share('filterForm', $data);
        }
    }

    /**
     * Main users page.
     *
     * @access   public
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
        
        $model = $this->repo->getModel();

        if (Input::get('filter'))
        {
            $filter = array_except($query, array('filter', 'perPage', 'page'));
            $model = $this->setFilterQuery($model, $filter);
        }

        $result = $this->repo->byPage($page, $perPage, $model);
        $items = Paginator::make($result->items, $result->totalItems, $perPage)->appends($query);

        return View::make('cmsharenjoy::'.$this->appName.'.index')
                   ->with('paginationCount', $perPage)
                   ->with('sortable', $sortable)
                   ->with('filterable', $filterable)
                   ->with('fieldsAry', $this->fieldsAry)
                   ->with('items', $items);
    }

    /**
     * The new object
     * 
     * @access public
     * @return View
     */
    public function getNew()
    {
        if( ! View::exists('cmsharenjoy::'.$this->appName.'.new'))
        {
            return App::abort(404, 'Page not found');
        }

        return View::make('cmsharenjoy::'.$this->appName.'.new');
    }

    /**
     * The generic method for the start of editing something
     * 
     * @return View
     */
    public function getEdit($id)
    {
        $item = $this->repo->byId($id);
        $item->tags_csv = implode(',', array_pluck($item->tags->toArray(), 'tag'));

        if( ! View::exists('cmsharenjoy::'.$this->appName.'.edit'))
        {
            return App::abort(404, 'Page not found');
        }

        return View::make('cmsharenjoy::'.$this->appName.'.edit')->with('item' , $item);
    }

    /**
     * Delete an object based on the ID passed in
     * 
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function getDelete($id)
    {
        $model = $this->repo->byId($id)->delete();

        $message = 'The item was successfully removed.';
        return Redirect::to($this->objectUrl)->with('success', new MessageBag(array($message)));
    }

    /**
     * The new object method, very generic, 
     * just allows mass assignable stuff to be filled and saved
     * 
     * @return Redirect
     */
    public function postNew()
    {
        if ($this->slug)
        {
            $input = $this->composeSlugInputData(Input::all(), '-');
        }
        else
        {
            $input = Input::all();
        }
        
        $result = $this->repo->create($input);

        if ( ! $result)
        {
            return Redirect::to($this->newUrl)->with('errors', $this->repo->errors())->withInput();
        }
        else
        {
            return Redirect::to($this->objectUrl)->with('success', new MessageBag(array('Item Created')));
        }
    }

    /**
     * The method to handle the posted data
     * 
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postEdit($id)
    {
        if ($this->slug)
        {
            $input = $this->composeSlugInputData(Input::all(), '-');
        }
        else
        {
            $input = Input::all();
        }

        $result = $this->repo->update($id, $input);

        if ( ! $result)
        {
            return Redirect::to($this->editUrl.$id)->with('errors', $this->repo->errors())->withInput();
        }
        else
        {
            return Redirect::to($this->editUrl.$id)->with('success', new MessageBag(array('Item Saved')));
        }
    }

    /**
     * Set the order of the list
     * 
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
            if($id != '')
            {
                $sort = $sort_value[$key];
                $this->repo->storeById($id, array('sort' => $sort));
            }
        }

        return Response::json('success', 200);
    }

    /**
     * Upload an image for this product ID
     * 
     * @return Response
     */
    public function postUpload($id)
    {

        // This should only be accessible via AJAX you know...
        if( ! Request::ajax() or !$this->repo->getById($id))
        {
            Response::json('error', 400);
        }
        
        $key = $this->repo->getModel()->getTableName();
        $type = get_class($this->repo->getModel());
        $success = $this->uploads_model->doUpload($id, $type, $key);

        if( ! $success)
        {
            Response::json('error', 400);
        }

        return Response::json('success', 200);
    }

    /**
     * Set the order of the images
     * 
     * @return Response
     */
    public function postOrderImages()
    {
        // This should only be accessible via AJAX you know...
        if( ! Request::ajax())
        {
            Response::json('error', 400);
        }

        // Ensure that the product images that need to be deleted get deleted
        $this->uploads_model->setOrder(Input::get('data'));

        return Response::json('success', 200);
    }

    protected function composeSlugInputData(array $data, $separator = '-')
    {
        $input = array_merge($data, array('slug' => Str::slug($data[$this->slug], $separator)));
        
        return $input;
    }
    
}