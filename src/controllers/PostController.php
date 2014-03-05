<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Post\PostInterface;
use App;

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
    protected $fieldsAry = [
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
    protected $filterAry = [
        'tag' => [
            'title'  => 'Tag',
            'name'   => 'f_tag',
            'source' => 'database',
            'type'   => 'select'
        ],
        'status' => [
            'title'  => 'Status',
            'name'   => 'f_status',
            'source' => 'option.statusOption',
            'type'   => 'select'
        ]
    ];

    protected $slug = 'title';

    protected $tag;

    /**
     * Construct Shit
     */
    public function __construct(PostInterface $post)
    {
        $this->repo = $post;
        $this->tag = App::make('Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface');

        parent::__construct();
    }

    public function setFilterQuery($model, $query)
    {
        if (count($query) !== 0)
        {
            foreach ($query as $key => $value)
            {
                $$key = $value;
            }
        }

        $model = $status != 0 ? $model->where('status_id', $status) : $model;
        $model = $keyword != '' ? $model->where('title', 'LIKE', "%$keyword%"): $model;
        $model = $model->whereBetween('created_at', array('2014-03-01', '2014-03-03'));
        

        return $model;                     
    }

}