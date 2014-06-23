<?php namespace Sharenjoy\Cmsharenjoy\Repo\Product;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Repo\Tag\TagInterface;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class ProductRepository extends EloquentBaseRepository implements ProductInterface {

    protected $tag;
    
    public function __construct(Product $model, TagInterface $tag, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;
        $this->tag       = $tag;

        parent::__construct();
    }

}