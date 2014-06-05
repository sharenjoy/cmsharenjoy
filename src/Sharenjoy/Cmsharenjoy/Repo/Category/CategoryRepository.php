<?php namespace Sharenjoy\Cmsharenjoy\Repo\Category;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category;

class CategoryRepository extends EloquentBaseRepository implements CategoryInterface {

    // Class expects an Eloquent model
    public function __construct(Category $category, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $category;

        parent::__construct();
    }

    /**
     * Create a new Article
     * @param array  Data to create a new object
     * @return string The insert id
     */
    public function create(array $input)
    {
        $data = $this->composeInputData($input);

        if ( ! $this->valid($data))
        {
            $this->getErrorsToFlashMessageBag();
            return false;
        }

        // Create the model
        $model = $this->model->fill($data);
        $model->makeRoot();
        $this->store($model->id, array('sort' => $model->id));

        return $model->id;
    }

}