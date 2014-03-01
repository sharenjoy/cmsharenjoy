<?php namespace Sharenjoy\Cmsharenjoy\Posts;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Str, Input;

class Posts extends EloquentBaseModel {

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'posts';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('title', 'slug', 'content');

    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = true;

    /**
     * Validation some rules
     * @var array
     */
    protected $validationRules = [
        'title'     => 'required',
        'slug'      => 'required|unique:posts,id,<id>',
        'content'   => 'required'
    ];

    /**
     * Fill the model up like we usually do but also allow us to fill some custom stuff
     * @param  array $array The array of data, this is usually Input::all();
     * @return void
     */
    public function fill(array $attributes)
    {
        parent::fill($attributes);
        $this->slug = Str::slug($this->title , '-');
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

    /**
     * The relationship setup for taggable classes
     * @return Eloquent
     */
    // public function tags()
    // {
    //     return $this->morphMany( 'Sharenjoy\Cmsharenjoy\Tags\Tags' , 'taggable' );
    // }

}