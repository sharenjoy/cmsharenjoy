<?php namespace Sharenjoy\Cmsharenjoy\Modules\Category;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
use Response, Input, Request, Categorize, Session, Message;

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
        $this->repo = $repo;

        if (Request::segment(3) == 'index')
        {
            Session::put('categoryType', Request::segment(4));
        }
        $this->type = Session::get('categoryType');

        parent::__construct();

        $this->repo->pushForm([
            'type' => [
                'args'  => ['value'=>ucfirst($this->type), 'readonly'=>'readonly'],
                'order' => '10'
            ]
        ]);

    }

    protected function setHandyUrls()
    {
        $this->objectUrl = url('/admin/'.$this->onController.'/index/'.$this->type);
        $this->createUrl = url('/admin/'.$this->onController.'/create/'.$this->type);
        $this->updateUrl = url('/admin/'.$this->onController.'/update/').'/';
        $this->deleteUrl = url('/admin/'.$this->onController.'/delete/').'/';
    }

    protected function getCategoryLevelNumber($type)
    {
        return array_get($this->categoryLevelNumber, $type);
    }

    public function getIndex()
    {
        $this->setGoBackPrevious();

        $type    = $this->type;
        $model   = $this->repo->getModel();
        $model   = $model->whereType($type)->orderBy('sort');
        $perPage = $model->count();

        // Set Pagination of data 
        $items = $model->paginate($perPage);

        $num = $this->getCategoryLevelNumber($type);

        $categories = Categorize::getCategoryProvider()->root()->whereType($type)->orderBy('sort')->get();
        $categories = Categorize::tree($categories)->toArray();

        $this->layout->with('paginationCount', $perPage)
                     ->with('sortable', false)
                     ->with('listConfig', $this->listConfig)
                     ->with('categoryLevelNum', $num)
                     ->with('categories', $categories)
                     ->with('items', $items);
    }

    public function postOrder()
    {
        if( ! Request::ajax()) Response::json('error', 400);

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

        return Response::json(Message::result('success', trans('cmsharenjoy::app.success_ordered')), 200);
    }

    protected function storeSortById($model, $sortNum)
    {
        $this->repo->edit($model->id, ['sort' => $sortNum]);
    }

}