<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class LinkList extends ListAbstract implements ListInterface {

    public function make(array $data)
    {
        $config = $data['config'];
        $key    = $data['key'];
        $item   = $data['item'];

        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';
        
        if ($item->$key != '')
        {
            $content .= '<a href="'.$item->$key.'" target="_blank">'.pick_trans('linkto').'</a>';
        }
        else
        {
            $content .= '-';
        }
        
        $content .= '</td>';

        return $content;
    }

}
