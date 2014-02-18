<?php namespace Sharenjoy\Cmsharenjoy\Galleries;
use Sharenjoy\Cmsharenjoy\Core\EloquentBaseModel;
use Sharenjoy\Cmsharenjoy\Abstracts\Traits\TaggableRelationship;
use Sharenjoy\Cmsharenjoy\Abstracts\Traits\UploadableRelationship;

class Galleries extends EloquentBaseModel
{

    use TaggableRelationship; // Enable The Tags Relationships
    use UploadableRelationship; // Enable The Uploads Relationships

    /**
     * The table to get the data from
     * @var string
     */
    protected $table    = 'galleries';

    /**
     * These are the mass-assignable keys
     * @var array
     */
    protected $fillable = array('title', 'slug', 'description');

    protected $validationRules = [
        'title'     => 'required',
        'slug'      => 'required|unique:galleries,id,<id>'
    ];

}
