<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class CommonList extends ListAbstract implements ListInterface {

    public function make($item, $column, $config)
    {
        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';
        $content .= $item->$column;
        $content .= '</td>';

        return $content;
    }

}