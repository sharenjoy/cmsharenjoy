<?php namespace Sharenjoy\Cmsharenjoy\Galleries;
use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Abstracts\Traits\TaggableRepository;

class GalleriesRepository extends EloquentBaseRepository implements GalleriesInterface
{

    use TaggableRepository;

    /**
     * Construct Shit
     * @param Galleries $galleries
     */
    public function __construct( Galleries $galleries )
    {
        $this->model = $galleries;
    }

}