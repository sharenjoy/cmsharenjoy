<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;

class OrderRepository extends EloquentBaseRepository implements OrderInterface {
    
    public function __construct(Order $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;

        parent::__construct();
    }

}