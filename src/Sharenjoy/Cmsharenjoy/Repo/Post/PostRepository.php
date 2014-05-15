<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Config, Formaker, View, Session, Debugbar;

class PostRepository extends EloquentBaseRepository implements PostInterface {

    /**
     * Responsible for converting to slug
     * @var string
     */
    protected $slug = 'title';

    /**
     * The instance of tag
     * @var Object
     */
    protected $tag;    
    
    /**
     * The instance of upload
     * @var Object
     */
    protected $upload;
    
    /**
     * Construct Shit
     * @param Posts $posts
     */
    public function __construct(Post $post, TagInterface $tag, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $post;
        $this->tag       = $tag;
    }

    public function setFilterQuery($model = null, $query)
    {
        $model = $model ?: $this->model;

        if (count($query) !== 0)
        {
            extract($query);
        
            $model = $status != 0 ? $model->where('status_id', $status) : $model;
            $model = $keyword != '' ? $model->where('title', 'LIKE', "%$keyword%"): $model;
            // $model = $model->whereBetween('created_at', array('2014-03-01', '2014-03-03'));
        }
        return $model;                     
    }

    protected function repoFinalProcess($model = null, $data = null)
    {
        $model  = $model ?: $this->model;
        $action = Session::get('onController').'-'.Session::get('onAction');

        switch ($action)
        {
            case 'post-post-create':
            case 'post-post-update':
                // $tags is an array that return from syncTags
                $tags = $this->tag->syncTags($data['tag']);
                // Assign set tags to model
                $model->tags()->sync($tags);
                break;
            
            default:
                break;
        }
        
        return $model;
    }

}