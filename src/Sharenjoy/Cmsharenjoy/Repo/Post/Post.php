<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Sharenjoy\Cmsharenjoy\Repo\Tag\Traits\TaggableTrait;
use Sharenjoy\Cmsharenjoy\Filer\Traits\AlbumModelTrait;

class Post extends EloquentBaseModel {

    use TaggableTrait;
    use AlbumModelTrait;

    protected $table    = 'posts';

    protected $fillable = [
        'user_id',
        'status_id',
        'title',
        'slug',
        'content',
        'sort'
    ];

    protected $eventItem = [
        'creating'    => ['user_id', 'status_id', 'slug|title', 'sort'],
        'created'     => ['album'],
        'updating'    => ['user_id', 'status_id', 'slug|title'],
        'saved'       => ['taggable'],
        'deleted'     => ['un_taggable'],
    ];

    public $filterFormConfig = [];

    public $formConfig = [
        'title'       => ['order' => '10'],
        'tag'         => ['order' => '20'],
        'content'     => ['order' => '30']
    ];

    public $createFormConfig = [];
    public $updateFormConfig = [
        'album'       => ['order' => '25'],
    ];

    public $createFormDeny   = [];
    public $updateFormDeny   = [];

}