<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Post\PostInterface;

class PostController extends ObjectBaseController {

    protected $appName = 'post';

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'order'  => true,
    ];

    protected $listConfig = [
        'title'        => ['name'=>'title',        'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

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
        'date_range' => [
            'type' => 'daterange',
        ],
        'start_date' => [
            'type' => 'datepicker',
        ],
        'color' => [
            'type' => 'colorpicker',
        ],

    ];

    public function __construct(PostInterface $post)
    {
        $this->repository = $post;
        parent::__construct();
    }

    protected function controllerFinalProcess($model = null)
    {
        $model = $model ?: $this->repository->getModel();
        
        switch ($this->onAction) {
            case 'get-index':
                foreach ($model as $key => $value)
                {
                    $model[$key]->tags_csv   = $value->tags->implode('tag', ',');
                    $model[$key]->user_name  = $value->author->first_name.' '.$value->author->last_name;
                    $model[$key]->user_email = $value->author->email;
                }
                break;
            case 'get-update':
                $model->tags_csv = $model->tags->implode('tag', ',');
                break;
            
            default:
                break;
        }

        return $model;
    }

}