<?php namespace Sharenjoy\Cmsharenjoy\Repo\Tag;

use Eloquent;

class Tag extends Eloquent {

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'tags';

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

    public $formConfig = [];

    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function posts()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Repo\Post\Post', 'posts_tags', 'tag_id', 'post_id');
    }

}