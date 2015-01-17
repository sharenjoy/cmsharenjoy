<?php namespace Sharenjoy\Cmsharenjoy\Modules\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Sharenjoy\Cmsharenjoy\Modules\Tag\TaggableTrait;
use Sharenjoy\Cmsharenjoy\Filer\AlbumTrait;

class Post extends EloquentBaseModel {

    use TaggableTrait;
    use AlbumTrait;

    protected $table    = 'posts';

    protected $fillable = [
        'user_id',
        'status_id',
        'process_id',
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

    public $filterFormConfig = [
        'keyword'     => ['filter' => 'posts.title,posts.content'],
    ];

    public $formConfig = [
        'title'       => ['order' => '10', 'inner-div-class'=>'col-sm-5'],
        'tag'         => ['order' => '20', 'inner-div-class'=>'col-sm-5'],
        'process_id'  => ['order' => '25', 'type'=>'checkbox', 'option'=>'process', 'inner-div-class'=>'col-sm-5'],
        'album'       => ['order' => '28', 'create'=>'deny', 'update'=>[]],
        'content'     => ['order' => '30', 'inner-div-class'=>'col-sm-9'],
    ];

}