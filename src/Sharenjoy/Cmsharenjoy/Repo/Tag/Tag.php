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
        'tag',
        'slug',
    );

    /**
     * Define a many-to-many relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Posts\Posts', 'posts_tags', 'tag_id', 'post_id');
    }

}