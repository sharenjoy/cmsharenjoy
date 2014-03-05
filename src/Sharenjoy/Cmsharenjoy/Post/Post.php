<?php namespace Sharenjoy\Cmsharenjoy\Post;

use Eloquent;

class Post extends Eloquent {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'posts';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array(
        'title',
        'slug',
        'content'
    );

    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = true;

    /**
     * The relationship setup for taggable classes
     * @return Eloquent
     */
    public function tagMorph()
    {
        return $this->morphMany('Sharenjoy\Cmsharenjoy\Repo\Tag\Tag', 'taggable');
    }

    /**
     * Define a many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Repo\Tag\Tag', 'posts_tags', 'post_id', 'tag_id')
                    ->withTimestamps();
    }

}