<?php namespace Sharenjoy\Cmsharenjoy\Repo\Post;

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
        'user_id',
        'title',
        'slug',
        'content'
    );

    public $formConfig = [
        'title' => [
            'args' => [
                'placeholder'=>'Post title',
            ],
            'order' => '10'
        ],
        'tag' => [
            'input' => 'tags_csv',
            'args'  => [
                'placeholder'=>'Comma Separsted Tags',
                'help'=>'Press enter or type a comma after each tag to set it.'
            ],
            'order' => '20'
        ],
        'content' => [
            'args' => [
                'placeholder'=>'Post content',
            ],
            'order' => '30'
        ]

    ];

    public $createFormConfig = [];

    public $updateFormConfig = [];

    /**
     * Indicates if the model should soft delete.
     * @var bool
     */
    protected $softDelete = false;

    public function author()
    {
        return $this->belongsTo('Sharenjoy\Cmsharenjoy\User\User', 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany('Sharenjoy\Cmsharenjoy\Repo\Tag\Tag', 'posts_tags', 'post_id', 'tag_id');
    }

}