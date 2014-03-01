<?php namespace Sharenjoy\Cmsharenjoy\Repo\Tag;

use Sharenjoy\Cmsharenjoy\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;
use Str, Input;

class EloquentTag implements TagInterface {

    protected $tag;

    // Class expects an Eloquent model
    public function __construct(Model $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Find existing tags or create if they don't exist
     *
     * @param  array $tags  Array of strings, each representing a tag
     * @return array        Array or Arrayable collection of Tag objects
     */
    public function findOrCreate(array $tags)
    {
        $foundTags = $this->tag->whereIn('tag', $tags)->get();

        $returnTags = array();

        if( $foundTags )
        {
            foreach( $foundTags as $tag )
            {
                $pos = array_search($tag->tag, $tags);

                // Add returned tags to array
                if( $pos !== false )
                {
                    $returnTags[] = $tag;
                    unset($tags[$pos]);
                }
            }
        }

        // Add remainings tags as new
        foreach( $tags as $tag )
        {
            $returnTags[] = $this->tag->create(array(
                                'tag' => $tag,
                                'slug' => Str::slug($tag, '-'),
                            ));
        }
        return $returnTags;
    }

    /**
     * Return a comma separated list of tags for use in the views, 
     * can be called like $item->tags_csv
     * @return string
     */
    public function getTagsCsv($data)
    {
        $tags = array();
        foreach($data as $tag)
        {
            $tags[] = $tag->tag;
        }

        return implode(',', $tags);
    }

    /**
     * Convert string of tags to
     * array of tags
     * @param  string
     * @return array
     */
    public function getTagsArray($tags)
    {
        $tags = explode(',', $tags);

        foreach( $tags as $key => $tag )
        {
            $tags[$key] = trim($tag);
        }

        return $tags;
    }

}