<?php namespace Sharenjoy\Cmsharenjoy\Modules\Category;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
use Response, Input, Request, Categorize, Session;
use Message, Config, View, Paginator, Lister;

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

    protected $categoryLayerNumber;

    public function __construct(CategoryInterface $repo)
    {
        $this->repo = $repo;

        $this->categoryLayerNumber = Config::get('cmsharenjoy::module.category.layer');

        if (Request::query('category'))
        {
            Session::put('sharenjoy.categoryType', Request::query('category'));
        }

        $this->type = Session::get('sharenjoy.categoryType');

        parent::__construct();

        View::share('specifyName', pick_trans('menu.'.$this->type.'_category'));
    }

    protected function getCategoryLayerNumber($type)
    {
        return array_get($this->categoryLayerNumber, $type);
    }

    public function getIndex()
    {
        $this->setGoBackPrevious();

        $type    = $this->type;
        $model   = $this->repo->getModel();
        $model   = $model->whereType($type)->orderBy('sort');
        $perPage = $model->count();

        // Set Pagination of data
        Paginator::setViewName('pagination::slider-3');
        $items = $model->paginate($perPage);

        $num = $this->getCategoryLayerNumber($type);

        $categories = Categorize::getCategoryProvider()->root()->whereType($type)->orderBy('sort')->get();
        $categories = Categorize::tree($categories)->toArray();

        $data = ['paginationCount'=>$perPage, 'sortable'=>false, 'rules'=>$this->functionRules];
        $lister = Lister::make($items, $this->listConfig, $data);

        $this->layout->with('listEmpty', $items->isEmpty())
                     ->with('lister', $lister)
                     ->with('categoryLevelNum', $num)
                     ->with('categories', $categories);
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

        return Response::json(Message::result('success', pick_trans('success_ordered')), 200);
    }

    public function getCreate()
    {
        $this->pushForm();
        parent::getCreate();
    }

    public function getUpdate($id)
    {
        $this->pushForm();
        parent::getUpdate($id);
    }

    private function pushForm()
    {
        $this->repo->pushForm([
            'type' => [
                'args'  => ['value'=>$this->type, 'readonly'=>'readonly'],
                'order' => '10'
            ]
        ]);
    }

    protected function storeSortById($model, $sortNum)
    {
        $this->repo->edit($model->id, ['sort' => $sortNum]);
    }

}