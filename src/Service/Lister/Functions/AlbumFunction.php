<?php

namespace Sharenjoy\Cmsharenjoy\Service\Lister\Functions;

class AlbumFunction extends FunctionAbstract implements FunctionInterface
{
    public function make(array $data)
    {
        $content =
        '<li>
            <a href="#" class="tooltip-primary index-modal-album" 
                data-id="'.$data['item']['id'].'" 
                data-title="'.$data['item']['title'].'" 
                data-path="'.url(session('accessUrl').'/filer/filealbum').'/'.$data['item']['album_id'].'" 
                data-toggle="tooltip" data-placement="top" title="相簿管理" data-original-title="相簿管理">
                <i class="fa fa-window-maximize fa-lg"></i>
            </a>
        </li>';

        return $content;
    }

}
