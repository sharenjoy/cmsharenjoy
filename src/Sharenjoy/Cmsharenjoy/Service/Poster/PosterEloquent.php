<?php namespace Sharenjoy\Cmsharenjoy\Service\Poster;

use Str, Session, StdClass;

class PosterEloquent extends PosterBaseAbstract implements PosterInterface {

    /**
     * Get a record by its ID
     * @param  integer $id The ID of the record
     * @param  array   $items The other things I need to do in model
     * @return Eloquent
     */
    public function showById($id, $model = null)
    {
        $model = $model ?: $this->model;
        $model = $model->find($id);

        if ( ! $model)
        {
            throw new \Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException();
        }
        return $model;
    }

    /**
     * Get a record by it's slug
     * @param  string $slug The slug name
     * @return Eloquent
     */
    public function showBySlug($slug, $model = null)
    {
        $model = $model ?: $this->model;
        $model = $model->where('slug', $slug)->first();

        if ( ! $model)
        {
            throw new \Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException();
        }
        return $model;
    }

    /**
     * Get paginated articles
     * @param int $page Number of articles per page
     * @param int $limit Results per page
     * @param boolean $all Show published or all
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function showByPage($page = 1, $limit, $model = null, $sort = 'sort', $order = 'desc')
    {
        $model = $model ?: $this->model;

        $result             = new StdClass;
        $result->page       = $page;
        $result->limit      = $limit;
        $result->totalItems = 0;
        $result->items      = array();

        $query = $model->orderBy($sort, $order);

        $rows = $query->skip($limit * ($page-1))
                      ->take($limit)
                      ->get();

        $result->totalItems = $model->count();
        $result->items = $rows->all();

        return $result;
    }

    /**
     * Get articles by their tag
     * @param string  URL slug of tag
     * @param int Number of articles per page
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function showByTag($tag, $page = 1, $limit)
    {
        $foundTag = $this->model->where('slug', $tag)->first();

        $result = new StdClass;
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = array();

        if( !$foundTag )
        {
            return $result;
        }

        $articles = $this->tag->articles()
                        ->where('articles.status_id', 1)
                        ->orderBy('articles.created_at', 'desc')
                        ->skip( $limit * ($page-1) )
                        ->take($limit)
                        ->get();

        $result->totalItems = $this->totalByTag();
        $result->items = $articles->all();

        return $result;
    }

    public function showAll($model = null, $sort = 'sort', $order = 'desc')
    {
        $model = $model ?: $this->model;

        return $model->orderBy($sort, $order)->get();
    }

    public function showList($title, $id = 'id', $model = null)
    {
        $model = $model ?: $this->model;
        return $model->lists($title, $id);
    }

    /**
     * Get total count
     * @todo I hate that this is public for the decorators.
     *       Perhaps interface it?
     * @return int  Total articles
     */
    public function totalRows($model = null)
    {
        $model = $model ?: $this->model;
        return $model->count();
    }

    public function filter(array $data, $model = null)
    {
        if (count($data))
        {
            $model = $model ?: $this->model;

            foreach ($data as $key => $value)
            {
                $model = $model->$key($value);
            }
            return $model;
        }
    }

    /**
     * Resolving some veriable make compose data work
     * @param  array $item The data
     * @return array
     */
    public function resolve($item)
    {
        $ary  = explode('|', $item);
        $data = [
            'item'  => $ary[0],
            'field' => $ary[1],
        ];
        return $data;
    }

    /**
     * Compose input data and slug to an array
     * @param  array $data      Input data
     * @param  array $composeItem
     * @return array
     */
    public function compose(array $data, array $composeItem)
    {
        if (count($composeItem))
        {
            foreach ($composeItem as $key => $item)
            {
                $field = '';

                if (strpos($item, '|') !== false)
                {
                    extract($this->resolve($item), EXTR_OVERWRITE);
                }

                switch ($item)
                {
                    case 'slug':
                        $data[$item] = Str::slug($data[$field], '-');
                        break;

                    case 'user':
                        $data['user_id'] = Session::get('user')->id;
                        break;

                    case 'sort':
                        $data[$item] = time();
                        break;

                    case 'status':
                        $value = $field ?: 1;
                        $data['status_id'] = $value;
                        break;
                    
                    default:
                        break;
                }
            }
        }
        return $data;
    }

    public function process($model = null, array $processItem = null)
    {
        $model = $model ?: $this->model;
        $items = $processItem ?: $model->processItem[Session::get('onAction')];

        if (count($items))
        {
            foreach ($items as $key => $item)
            {
                $field = '';

                if (strpos($item, '|') !== false)
                {
                    extract($this->resolve($item), EXTR_OVERWRITE);
                }

                if ($field)
                {
                    $model->$item($field);
                }
                else
                {
                    $model->$item();
                }
            }
        }

        return $model;
    }

    public function processMulitple(array $data, array $processItem = null)
    {
        if (count($data))
        {
            foreach ($data as $key => $model)
            {
                $data[$key] = $this->process($model);
            }
        }

        return $data;
    }

}
