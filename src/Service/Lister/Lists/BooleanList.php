<?php

namespace Sharenjoy\Cmsharenjoy\Service\Lister\Lists;

class BooleanList extends ListAbstract implements ListInterface
{
    public function make($item, $column, $config)
    {
        $content = '<td align="'.$config['align'].'" width="'.$config['width'].'">';
        
        if (isset($item->{$column}))
        {
            if ($item->{$column})
            {
                $content .= '<i class="fa fa-check text-success"></i><br>'.trans('option.yes');
            }
            else
            {
                $content .= '<i class="fa fa-times text-danger"></i><br>'.trans('option.no');
            }
        }
        else
        {
            $content .= '-';
        }
        
        $content .= '</td>';

        return $content;
    }

}
