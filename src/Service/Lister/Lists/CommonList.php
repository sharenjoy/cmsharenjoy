<?php namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class CommonList extends ListAbstract implements ListInterface {

    public function make($item, $column, $config)
    {
        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';

        if (isset($config['lists']))
        {
            if (isset(session('allLists.'.$config['lists'])[$item[$column]]))
            {
                $content .= session('allLists.'.$config['lists'])[$item[$column]];
            }
            else
            {
                $content .= $item[$column];
            }
        }
        else
        {
            $content .= $item[$column];
        }

        $content .= '</td>';

        return $content;
    }

}