<?php namespace Sharenjoy\Cmsharenjoy\Tags;
use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;

class TagsRepository extends EloquentBaseRepository implements TagsInterface
{

    /**
     * Construct Shit
     * @param Tags $tags
     */
    public function __construct( Tags $tags )
    {
        $this->model = $tags;
    }

}