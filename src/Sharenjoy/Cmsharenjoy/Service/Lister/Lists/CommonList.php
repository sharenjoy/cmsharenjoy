<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class CommonList extends ListAbstract implements ListInterface {

    public function make(array $data)
    {
        $config = $data['config'];
        $key    = $data['key'];
        $item   = $data['item'];

        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';
        $content .= $item->$key;
        $content .= '</td>';

        return $content;
    }

}