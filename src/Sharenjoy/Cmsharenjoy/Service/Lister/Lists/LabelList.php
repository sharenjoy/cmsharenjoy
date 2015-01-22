<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class LabelList extends ListAbstract implements ListInterface {

    public function make(array $data)
    {
        $config = $data['config'];
        $key    = $data['key'];
        $item   = $data['item'];

        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';
        
        $labels = explode(',', $item->$key);
        foreach ($labels as $value)
        {
            $content .= '<div class="label label-secondary">'.$value.'</div>';
        }

        $content .= '</td>';

        return $content;
    }

}

