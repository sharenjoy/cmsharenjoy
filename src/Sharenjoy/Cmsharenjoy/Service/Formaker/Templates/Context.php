<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Templates;

class Context {
    
    public function __construct(HtmlTemplateInterface $template)
    {
        $template->make();
    }

}