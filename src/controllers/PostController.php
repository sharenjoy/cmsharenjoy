<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Post\PostInterface;

class PostController extends ObjectBaseController {

    /**
     * The application name and also is view name
     * @var string
     */
    protected $appName = 'posts';

    /**
     * This array are fileds of setting data which show the table list.
     * @var array
     */
    protected $listConfig = [
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
     * If filter's data from database, It should be use App::make
     * an instance of object what we need.
     * @var array
     */
    protected $filterFormConfig = [
        'tag' => [
            'type'  => 'select',
            'model' => 'tag',
            'item'  => 'tag',
            'args'  => ['placeholder'=>'This is a tag'],
        ],
        'status' => [],
        'language' => [
            'type'   => 'select',
            'option' => ['1'=>'tw', '2'=>'en']
        ],
        'keyword' => [
            'args' => ['placeholder'=>'Please enter the keyword that you want to search.']
        ],
        'dateRange' => [
            'type' => 'daterange',
            'args' => ['placeholder'=>'Please enter the keyword that you want to search.']
        ],
        'startTime' => [
            'type' => 'datepicker',
            'args' => ['placeholder'=>'Please enter the keyword that you want to search.']
        ],
        'color' => [
            'type' => 'colorpicker',
            'args' => ['placeholder'=>'Please enter the keyword that you want to search.']
        ],

    ];

    /**
     * Construct Shit
     */
    public function __construct(PostInterface $post)
    {
        $this->repository = $post;
        parent::__construct();
    }

}