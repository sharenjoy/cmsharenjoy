<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;

class TagController extends ObjectBaseController {

    /**
     * The application name and also is view name
     * @var string
     */
    protected $appName = 'tags';

    /**
     * This array are fileds of setting data which show the table list.
     * @var array
     */
    protected $listConfig = [
        'tag' => [
            'name'  => 'tag',
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
    protected $filterFormConfig = [];

    /**
     * Construct Shit
     */
    public function __construct(TagInterface $tag)
    {
        $this->repository = $tag;
        parent::__construct();
    }

}