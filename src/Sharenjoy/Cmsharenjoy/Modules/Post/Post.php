<?php namespace Sharenjoy\Cmsharenjoy\Modules\Post;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Sharenjoy\Cmsharenjoy\Modules\Tag\TaggableTrait;
use Sharenjoy\Cmsharenjoy\Filer\AlbumTrait;

class Post extends EloquentBaseModel {

    use ConfigTrait;
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

}