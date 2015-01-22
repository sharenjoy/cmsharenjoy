<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class ImageList extends ListAbstract implements ListInterface {

    public function make(array $data)
    {
        $config = $data['config'];
        $key    = $data['key'];
        $item   = $data['item'];

        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';
        
        if ($item->$key != '')
        {
            $content .= '<img src="'.asset('uploads/thumbs/'.$item->$key).'" width="75">';
        }
        else
        {
            $content .= '-';
        }
        
        $content .= '</td>';

        return $content;
    }

}

