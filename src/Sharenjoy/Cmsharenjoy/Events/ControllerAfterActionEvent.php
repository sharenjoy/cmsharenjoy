<?php namespace Sharenjoy\Cmsharenjoy\Events;

use Session, View;

class ControllerAfterActionEvent {
    
    public function handle($data)
    {
        switch (Session::get('onMethod'))
        {
            case 'get-update':

                $this->outputAlbumIdToView($data);

                break;
            
            default:
                break;
        }
    }

    private function outputAlbumIdToView($data)
    {
        if ($data->isAlbumable())
        {
            View::share('albumId', $data->album_id);
        }
    }

}