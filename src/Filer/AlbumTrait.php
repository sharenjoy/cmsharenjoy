<?php

namespace Sharenjoy\Cmsharenjoy\Filer;

use Event, Filer;
use Sharenjoy\Cmsharenjoy\Filer\File;
use Sharenjoy\Cmsharenjoy\Filer\Folder;
use Sharenjoy\Cmsharenjoy\Utilities\Transformer;

trait AlbumTrait
{
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'album_id');
    }

    public function album()
    {
        return $this->hasMany(File::class, 'folder_id', 'album_id')->orderBy('sort');
    }

    public function eventAlbum($key, $model)
    {
        $title = Transformer::title($model);

        $result = Filer::createFolder(0, $title, 'local', '', 1);
        $model->album_id = $result['data']['id'];
        $model->save();
    }

    /**
     * Figure out if album can be used on the model
     * @return boolean
     */
    public function isAlbumable()
    {
        return in_array(
            'Sharenjoy\Cmsharenjoy\Filer\AlbumTrait',
            $this->getReflection()->getTraitNames()
        );
    }

}