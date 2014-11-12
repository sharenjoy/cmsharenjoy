<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Templates;

class CommonTemplate extends TemplateAbstract implements TemplateInterface {
    
    protected $template = '<div class="form-group">
                             {label}
                             <div class="col-sm-7">
                               {field}
                             </div>
                             {error}
                             {help}
                           </div>';

    public function make(Array $data)
    {
        return $this->parser->parse($this->template, $data);
    }

}