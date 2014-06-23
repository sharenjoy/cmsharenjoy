<?php namespace Sharenjoy\Cmsharenjoy\Repo\Category;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Sharenjoy\Cmsharenjoy\Service\Categorize\Categories\Category;
use Poster;

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
        $result = $this->validator->valid($input);
        if ( ! $result->status) return false;

        // Compose some necessary variable to input data
        $input = Poster::compose($input, $this->model->createComposeItem);

        // Create the model
        $model = $this->model->create($input);
        $model->makeRoot();
        $model = $this->edit($model->id, array('sort' => $model->id));

        return $model;
    }

}