<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Config, Formaker, View, Session;

class ProductRepository extends EloquentBaseRepository implements ProductInterface {

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
     * Construct Shit
     * @param Products $Products
     */
    public function __construct(Product $model, TagInterface $tag, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;
        $this->tag       = $tag;

        parent::__construct();
    }

    protected function repoFinalProcess($model = null, $data = null)
    {
        $model  = $model ?: $this->model;
        $action = Session::get('onController').'-'.Session::get('onAction');

        switch ($action)
        {
            case 'report-post-create':
            case 'report-post-update':
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