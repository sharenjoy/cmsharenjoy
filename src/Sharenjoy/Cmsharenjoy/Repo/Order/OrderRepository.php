<?php namespace Sharenjoy\Cmsharenjoy\Repo\Order;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Config, Formaker, View, Session;

class OrderRepository extends EloquentBaseRepository implements OrderInterface {
    
    /**
     * Construct Shit
     * @param Orders $Orders
     */
    public function __construct(Order $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;
    }

    protected function repoFinalProcess($model = null, $data = null)
    {
        $model  = $model ?: $this->model;
        $action = Session::get('onController').'-'.Session::get('onAction');

        switch ($action)
        {
            case 'report-post-create':
            case 'report-post-update':
                break;
            
            default:
                break;
        }
        
        return $model;
    }

}