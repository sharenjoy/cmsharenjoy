<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Templates;

class BlockTemplate extends TemplateAbstract implements TemplateInterface {
    
    protected $template = '<{tag}{attributes}>{content}</{tag}>';

    public function make(Array $data)
    {
        $data['attributes'] = $this->attributes($data['attributes']);
        return $this->parser->parse($this->template, $data);
    }

}