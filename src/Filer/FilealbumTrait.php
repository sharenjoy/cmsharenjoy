<?php namespace Sharenjoy\Cmsharenjoy\Filer;

use Event, Filer;
use Sharenjoy\Cmsharenjoy\Filer\File;
use Sharenjoy\Cmsharenjoy\Filer\Folder;
use Sharenjoy\Cmsharenjoy\Utilities\Transformer;

trait FilealbumTrait {

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'filealbum_id');
    }

    public function filealbum()
    {
        return $this->hasMany(File::class, 'folder_id', 'filealbum_id')->orderBy('sort');
    }

    public function eventFilealbum($key, $model)
    {
        $title = Transformer::title($model->toArray());

        $result = Filer::createFolder(0, $title, 'local', '', 1);
        $model->filealbum_id = $result['data']['id'];
        $model->save();
    }

    /**
     * Figure out if file album can be used on the model
     * @return boolean
     */
    public function isFileAlbumable()
    {
        return in_array(
            'Sharenjoy\Cmsharenjoy\Filer\FilealbumTrait',
            $this->getReflection()->getTraitNames()
        );
    }

}