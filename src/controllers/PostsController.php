<?php namespace Sharenjoy\Cmsharenjoy\Controllers;
use Sharenjoy\Cmsharenjoy\Posts\PostsInterface;
use Input, Redirect, Str, Lang;
use Illuminate\Support\MessageBag;
class PostsController extends ObjectBaseController {

    /**
     * The place to find the views / URL keys for this controller
     * @var string
     */
    public $view_key = 'posts';

    /**
     * The application name
     * @var string
     */
    public $application_name = 'posts';

    /**
     * This array are fileds setting data which show the table list.
     * @var array
     */
    public $fields_ary = [
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
    public function __construct( PostsInterface $posts )
    {
        $this->model = $posts;
        parent::__construct();
    }

    /**
     * The method to handle the posted data
     * @param  integer $id The ID of the object
     * @return Redirect
     */
    public function postEdit( $id )
    {
        $record = $this->model->requireById( $id );
        $record->fill( Input::all() );

        if( !$record->isValid() )
            return Redirect::to( $this->edit_url.$id )->with( 'errors' , $record->getErrors() );

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->hydrate()->save();

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to( $this->object_url )->with( 'success' , new MessageBag( array( 'Item Saved' ) ) );
    }

}