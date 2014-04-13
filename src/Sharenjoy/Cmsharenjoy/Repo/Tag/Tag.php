<?php namespace Sharenjoy\Cmsharenjoy\Repo\Tag;

use Eloquent;

class Tag extends Eloquent {

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'tags';

    /**
     * It's the unique fields
     * @var array
     */
    public $uniqueFields = ['slug'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'tag',
        'slug'
    );

    /**
     * Indicates if the model should soft delete.
     * @var bool
     */
    protected $softDelete = false;

    public $formConfig = [
        'tag' => [
            'type'  => 'text',
            'order' => '10'
        ]
    ];

    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function posts()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Repo\Post\Post', 'posts_tags', 'tag_id', 'post_id');
    }

}