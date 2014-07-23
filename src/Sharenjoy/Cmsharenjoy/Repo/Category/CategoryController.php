<?php namespace Sharenjoy\Cmsharenjoy\Repo\Category;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
use Response, Input, Request, Categorize, Session, Poster, Message;

class CategoryController extends ObjectBaseController {

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
    ];

    protected $listConfig = [
        'type'         => ['name'=>'type',         'align'=>'center', 'width'=>'25%'],
        'title'        => ['name'=>'title',        'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    protected $type;

    protected $categoryLevelNumber = [
        'product' => 1,
        'post'    => 1
    ];

    public function __construct(CategoryInterface $repo)
    {
        $this->repository = $repo;

        if (Request::segment(3) == 'index')
        {
            Session::put('categoryType', Request::segment(4));
        }
        $this->type = Session::get('categoryType');

        parent::__construct();

        $this->repository->pushFormConfig('list', array(
            'type' => [
                'args'  => ['value'=>ucfirst($this->type), 'readonly'=>'readonly'],
                'order' => '10'
            ]
        ));

    }

    protected function setHandyUrls()
    {
        $this->objectUrl = url('/admin/'.$this->onController.'/index/'.$this->type);
        $this->createUrl = url('/admin/'.$this->onController.'/create/'.$this->type);
        $this->updateUrl = url('/admin/'.$this->onController.'/update/').'/';
        $this->deleteUrl = url('/admin/'.$this->onController.'/delete/').'/';
    }

    public function getIndex()
    {
        $type    = $this->type;
        $model   = Poster::getModel();
        $model   = $model->whereType($type)->orderBy('sort');
        $perPage = $model->count();

        // Set Pagination of data 
        $items = $model->paginate($perPage);

        $num = $this->getCategoryLevelNumber($type);

        $categories = Categorize::getCategoryProvider()->root()->whereType($type)->orderBy('sort')->get();
        $categories = Categorize::tree($categories)->toArray();

        $this->layout->with('paginationCount', $perPage)
                     ->with('sortable', false)
                     ->with('filterable', true)
                     ->with('listConfig', $this->listConfig)
                     ->with('categoryLevelNum', $num)
                     ->with('categories', $categories)
                     ->with('items', $items);
    }

    protected function getCategoryLevelNumber($type)
    {
        return array_get($this->categoryLevelNumber, $type);
    }

    public function postOrder()
    {
        if( ! Request::ajax())
        {
            Response::json('error', 400);
        }

        $result  = json_decode(Input::get('sort_value'), true);
        $sortNum = 0;

        foreach ($result as $keyOne => $valueOne)
        {
            $categoryOne = Categorize::getCategoryProvider()->findById($valueOne['id']);
            $categoryOne->makeRoot();
            $this->storeSortById($categoryOne, ++$sortNum);

            if(isset($valueOne['children']) && count($valueOne['children']))
            {
                foreach ($valueOne['children'] as $keyTwo => $valueTwo)
                {
                    $categoryTwo = Categorize::getCategoryProvider()->findById($valueTwo['id']);
                    $categoryTwo->makeChildOf($categoryOne);
                    $this->storeSortById($categoryTwo, ++$sortNum);

                    if(isset($valueTwo['children']) && count($valueTwo['children']))
                    {
                        foreach ($valueTwo['children'] as $keyThree => $valueThree)
                        {
                            $categoryThree = Categorize::getCategoryProvider()->findById($valueThree['id']);
                            $categoryThree->makeChildOf($categoryTwo);
                            $this->storeSortById($categoryThree, ++$sortNum);
                        }
                    }
                }
            }
        }

        return Response::json(Message::json('success', trans('cmsharenjoy::app.success_ordered')), 200);
    }

    protected function storeSortById($model, $sortNum)
    {
        $this->repository->edit($model->id, array('sort' => $sortNum));
    }

}