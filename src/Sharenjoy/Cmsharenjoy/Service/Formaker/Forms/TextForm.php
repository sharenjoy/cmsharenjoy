<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Forms;

class TextForm extends FormAbstract implements FormInterface {

    protected $data;

    public function make(Array $data)
    {
        $attributes = $this->attributes($data);
        
        $form = '<input type="text"'.$attributes.'>';
        
        return $form;
    }

}