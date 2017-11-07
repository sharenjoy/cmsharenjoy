<?php

namespace Sharenjoy\Cmsharenjoy\Service\Lister;

use Config, Exception;
use Sharenjoy\Cmsharenjoy\Service\Lister\Templates\GridTemplate;
use Sharenjoy\Cmsharenjoy\Service\Lister\Templates\DefaultTemplate;

class Lister extends ListerAbstract implements ListerInterface
{
    /**
     * Make the list templates
     * @param array $items
     * @param array $listConfig
     * @param array $data
     * @param string $lister
     */
    public function make($items, $listConfig = array(), $data = array(), $lister = 'default')
    {
        switch ($lister)
        {
            case 'default':
                $config = Config::get('lister.default');
                $template = new DefaultTemplate();
                break;
            case 'grid':
                $config = Config::get('lister.grid');
                $template = new GridTemplate();
                break;
            default:
                $config = Config::get('lister.'.$lister);
                
                if (! $config) {
                    throw new Exception('There is no '.$lister.' driver setting in the lister config.');
                }

                $template = $this->getTemplate($lister);
        }

        $data = [
            'listConfig' => $listConfig,
            'items'      => $items,
            'data'       => $data,
            'config'     => $config,
        ];

        return $template->make($data);
    }

    protected function getTemplate($driver)
    {
        $templatesNamespace = Config::get('lister.loadTemplatesNamespace');

        foreach ($templatesNamespace as $namespace)
        {
            $templateName  = ucfirst($driver).'Template';
            $className = $namespace.$templateName;

            if (class_exists($className)) {
                return (new $className());
            }
        }

        throw new \InvalidArgumentException("It doesn't have any '{$templateName}' class exists");
    }

}
