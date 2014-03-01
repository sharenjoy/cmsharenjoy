<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Posts\PostsInterface;
use Illuminate\Support\MessageBag;
use Input, Redirect, Str, Lang, View, App;

class PostsController extends ObjectBaseController {

    /**
     * The application name and also is view name
     * @var string
     */
    public $appName = 'posts';

    /**
     * This array are fileds of setting data which show the table list.
     * @var array
     */
    public $fieldsAry = [
        'title' => [
            'name'  => 'title',
            'align' => '',
            'width' => ''
        ],
        'created_at' => [
            'name'  => 'created',
            'align' => 'center',
            'width' => '20%'
        ],
    ];


    /**
     * Construct Shit
     */
    public function __construct(PostsInterface $posts)
    {
        $this->model = $posts;
        parent::__construct();
        
        $this->tag = App::make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface');
    }

    /**
     * The method to handle the posted data
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postEdit($id)
    {
        $record = $this->model->requireById($id);
        $record->fill(Input::all());

        if( ! $record->isValid())
        {
            return Redirect::to($this->editUrl.$id)->with('errors', $record->getErrors());
        }

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->save();

        // save tags
        $tags = $this->tag->getTagsArray(Input::get('tags'));
        $this->model->syncTags($record, $tags);

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to($this->objectUrl)->with('success', new MessageBag(array('Item Saved')));
    }

}