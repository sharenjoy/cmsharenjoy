<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException;
use Illuminate\Support\MessageBag;
use View, Redirect, Input, App, ReflectionClass, Request, Config, Response, URL, Lang;

abstract class ObjectBaseController extends BaseController {

    /**
     * The model to work with for editing stuff
     */
    protected $model;

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
     * Is the controller allowed to upload images?
     * @var boolean
     */
    protected $uploadable;

    /**
     * Is the controller allowed to have tags?
     * @var boolean
     */
    protected $taggable;

    /**
     * Can items be deleted?
     * @var boolean
     */
    protected $deletable = true;

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
    protected $pagination_count = 15;

    /**
     * ObjectBaseController construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->setHandyUrls();
        $this->shareHandyUrls();

        $this->uploads_model = App::make('Sharenjoy\Cmsharenjoy\Uploads\UploadsInterface');
    }

    /**
     * Set the URL's to be used in the views
     * 
     * @return void
     */
    private function setHandyUrls()
    {
        if(is_null($this->editUrl))
        {
            $this->editUrl = $this->objectUrl.'/edit/';
        }
        if(is_null($this->newUrl))
        {
            $this->newUrl = $this->objectUrl.'/new';
        }
        if(is_null($this->deleteUrl))
        {
            $this->deleteUrl = $this->objectUrl.'/delete/';
        }
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

    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function getIndex($sort = '')
    {
        $pnum = Input::get('pnum');

        if(empty($pnum) || $pnum == '') {
            $pnum = $this->pagination_count;
        }
        
        $sortable = $sort == 'sort' ? true : false;

        return View::make('cmsharenjoy::'.$this->appName.'.index')
                   ->with('pagination_count', $pnum)
                   ->with('sortable', $sortable)
                   ->with('fieldsAry', $this->fieldsAry)
                   ->with('items', $this->model->getAllPaginated($pnum));
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
        try {
            $item = $this->model->requireById($id);
        } catch (EntityNotFoundException $e){
            return Redirect::to($this->objectUrl)->with('errors', new MessageBag(
                array("An item with the ID:$id could not be found.")
            ));
        }
        $item->tags_csv = $this->tag->getTagsCsv($item->tags);
        print_r(DB::getQueryLog());
        // if( ! View::exists('cmsharenjoy::'.$this->appName.'.edit'))
        // {
        //     return App::abort(404, 'Page not found');
        // }

        // return View::make('cmsharenjoy::'.$this->appName.'.edit')->with('item' , $item);
    }

    /**
     * Delete an object based on the ID passed in
     * 
     * @param  integer $id The object ID
     * @return Redirect
     */
    public function getDelete($id)
    {
        if($this->deletable == false)
        {
            return App::abort(404, 'Page not found');
        }

        $model = $this->model->getById($id)->delete();

        $message = 'The item was successfully removed.';
        return Redirect::to($this->objectUrl)
                     ->with('success', new MessageBag(array($message)));
    }

    /**
     * The new object method, very generic, 
     * just allows mass assignable stuff to be filled and saved
     * 
     * @return Redirect
     */
    public function postNew()
    {
        $record = $this->model->getNew(Input::all());

        $valid = $this->validateWithInput === true ? $record->isValid(Input::all()) : $record->isValid();

        if( ! $valid)
        {
            return Redirect::to($this->newUrl)->with('errors', $record->getErrors())->withInput();
        }

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->save();

        // insert id
        $insertId = $record->id;
        $this->model->storeById($insertId, array('sort' => $insertId));

        // save tags
        $tags = $this->tag->getTagsArray(Input::get('tags'));
        $this->model->syncTags($record, $tags);

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to($this->objectUrl)->with('success', new MessageBag(array('Item Created')));
    }

    /**
     * The method to handle the posted data
     * 
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postEdit($id)
    {
        $record = $this->model->requireById($id);
        $record->fill(Input::all());

        $valid = $this->validateWithInput === true ? $record->isValid(Input::all()) : $record->isValid();

        if( ! $valid)
        {
            return Redirect::to($this->editUrl.$id)->with('errors', $record->getErrors())->withInput();
        }

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->save();

        // save tags
        $tags = $this->tag->getTagsArray(Input::get('tags'));
        $this->model->syncTags($record, $tags);

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to($this->editUrl.$id)->with('success', new MessageBag(array('Item Saved')));
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
                $this->model->storeById($id, array('sort' => $sort));
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
        if( ! Request::ajax() or !$this->model->getById($id))
        {
            Response::json('error', 400);
        }
        
        $key = $this->model->getModel()->getTableName();
        $type = get_class($this->model->getModel());
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
    
}