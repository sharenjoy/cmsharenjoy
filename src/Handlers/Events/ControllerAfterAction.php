<?php namespace Sharenjoy\Cmsharenjoy\Handlers\Events;

use Session, Filer;

class ControllerAfterAction {
    
    public function handle($data)
    {
        switch (Session::get('onMethod'))
        {
            case 'get-index':
                Session::forget('allLists');
                break;

            case 'get-update':
                $this->outputAlbumIdToView($data);
                break;

            case 'post-delete':
                $this->deleteAlbum($data);
                break;
            
            default:
                break;
        }
    }

    private function outputAlbumIdToView($data)
    {
        if ($data->isAlbumable())
        {
            view()->share('albumId', $data->album_id);
        }
    }

    private function deleteAlbum($data)
    {
        if ($data->isAlbumable())
        {
            Filer::deleteFolderDoNotConfirm($data->album_id);
        }
    }

}