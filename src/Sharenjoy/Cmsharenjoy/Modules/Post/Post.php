<?php namespace Sharenjoy\Cmsharenjoy\Modules\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Sharenjoy\Cmsharenjoy\Modules\Tag\Traits\TaggableTrait;
use Sharenjoy\Cmsharenjoy\Filer\Traits\AlbumModelTrait;

class Post extends EloquentBaseModel {

    use TaggableTrait;
    use AlbumModelTrait;

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
        'category_id'       => ['category' => 'Product'],
        'created_range'     => ['type' => 'daterange'],
        'hide'              => ['type' => 'hidden', 'value'=>'rodlld'],
        'keyword'           => ['filter' => 'products.title,products.title_jp,products.description'],
    ];

    public $formConfig = [
        'title'       => ['order' => '10', 'inner-div-class'=>'col-sm-5'],
        'tag'         => ['order' => '20', 'inner-div-class'=>'col-sm-5'],
        'process_id'  => ['order' => '25', 'type'=>'file', 'inner-div-class'=>'col-sm-5'],
        'content'     => ['order' => '30', 'inner-div-class'=>'col-sm-9'],
        'hide'        => ['order' => '22', 'type'=>'hidden', 'value'=>'Ronald'],
    ];

    public $reviewFormConfig = [];
    public $createFormConfig = [];
    public $updateFormConfig = [
        'album'       => ['order' => '25'],
    ];

    public $reviewFormDeny   = [];
    public $createFormDeny   = [];
    public $updateFormDeny   = [];

}