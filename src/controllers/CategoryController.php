<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Category\CategoryInterface;
use Response, Input, Request, Categorize, Session, Poster;

class CategoryController extends ObjectBaseController {

    protected $appName = 'category';

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

    public function __construct(CategoryInterface $category)
    {
        $this->repository = $category;

        if (Request::segment(3) == 'index')
        {
            Session::put('categoryType', Request::segment(4));
        }
        $this->type = Session::get('categoryType');

        parent::__construct();

        $this->repository->pushFormConfig('list', array(
            'type' => array(
                'args'=>['value'=>ucfirst($this->type), 'readonly'=>'readonly'],
                'order'=>'10'
            )
        ));

    }

    protected function setHandyUrls()
    {
        $this->objectUrl = url('/admin/'.$this->appName.'/index/'.$this->type);
        $this->createUrl = url('/admin/'.$this->appName.'/create/'.$this->type);
        $this->updateUrl = url('/admin/'.$this->appName.'/update/').'/';
        $this->deleteUrl = url('/admin/'.$this->appName.'/delete/').'/';
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

        return Response::json('success', 200);
    }

    protected function storeSortById($model, $sortNum)
    {
        $this->repository->store($model->id, array('sort' => $sortNum));
    }

}