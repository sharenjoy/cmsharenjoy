<?php namespace Sharenjoy\Cmsharenjoy\Formaker;

use Form, Lang, Theme, Config, Session;

class BootstrapBackend extends FormakerBaseAbstract implements FormakerInterface {

    /**
     * Should form inputs receive a class name?
     */
    private $inputClass = 'form-control';

    /**
     * Add prefix to every fields's name
     * @var string
     */
    private $fieldPrefix = '';

    private $assets = array();

    /**
     * Composing the arguments to some data
     * @param  array  $args
     * @return array
     */
    protected function composeArguments($args = array())
    {
        $arguments = array();

        $arguments['position'] = array_get($args, 'position') ?: 'bottom';
        unset($args['position']);

        $arguments['tag'] = array_get($args, 'tag') ?: 'div';
        unset($args['tag']);

        $arguments['elements'] = '';
        foreach ($args as $key => $value)
        {
            $arguments['elements'] .= $key.'="'.$value.'"';
        }

        return $arguments;
    }

    protected function wrapper($args = array())
    {
        $data = $this->inputData;
        $item = $this->composeArguments($args);

        $this->inputData = '<'.$item['tag'].' '.$item['elements'].'>'.$data.'</'.$item['tag'].'>';

        return $this;
    }

    protected function inner($inner, $args = array())
    {
        $data  = $this->inputData;
        $item  = $this->composeArguments($args);
        $field = '<'.$item['tag'].' '.$item['elements'].'>'.$inner.'</'.$item['tag'].'>';

        $this->inputData = $item['position'] == 'top' ? $field.$data : $data.$field;

        return $this;
    }

    /**
     * Handle of creation of the label
     * @param array $args
     * @param string $name
     */
    protected function label($args = array())
    {
        $data = $this->inputData;
        $item = $this->composeArguments($args);

        $label = array_get($this->args, 'label') ?: $this->prettifyFieldName();
        unset($this->args['label']);

        // Add prefix
        $name  = $this->fieldPrefix.$this->name;
        $field = '<label for="'.$name.'" '.$item['elements'].'>'.$label.'</label>';

        $this->inputData = $item['position'] == 'top' ? $field.$data : $data.$field;

        return $this;
    }

    /**
     * Create help block
     * @param  string $help The description of help
     * @return string       The element of help block
     */
    protected function help($args = array())
    {
        $target = 'app.form.help.'.Session::get('onController').'.'.$this->name;
        if (Lang::has('cmsharenjoy::'.$target))
        {
            $description = Lang::get('cmsharenjoy::'.$target);
            unset($this->args['help']);
        }
        elseif (array_get($this->args, 'help'))
        {
            $description = array_get($this->args, 'help');
            unset($this->args['help']);
        }
        else
        {
            return $this;
        }

        $data  = $this->inputData;
        $item  = $this->composeArguments($args);
        $field = '<'.$item['tag'].' '.$item['elements'].'>'.$description.'</'.$item['tag'].'>';

        $this->inputData = $item['position'] == 'top' ? $field.$data : $data.$field;
        
        return $this;
    }

    /**
     * Create the form field
     * @param string $name
     * @param array $args
     */
    protected function field()
    {
        // Compose the input filed
        $this->inputData .= $this->createInputArgs();
        return $this;
    }

    /**
     * Manage creation of input arguments
     * @param string $type
     * @param array $args
     * @param string An input
     */
    protected function createInputArgs()
    {
        // If the user specifies an input type, we'll just use that.
        // Otherwise, we'll take a best guess approach.
        $type = array_get($this->args, 'type') ?: $this->guessInputType();
        unset($this->args['type']);

        // get exist value and then unset
        $value = array_get($this->args, 'value') ?: '';
        unset($this->args['value']);

        // We'll default to Bootstrap-friendly input class names
        if( ! isset($this->args['class']))
        {
            $this->args = array_merge(array('class' => $this->inputClass), $this->args);
        }

        // Set the lang of placeholder from config
        $target = 'app.form.placeholder.'.Session::get('onController').'.'.$this->name;
        if (Lang::has('cmsharenjoy::'.$target))
        {
            $this->args['placeholder'] = Lang::get('cmsharenjoy::'.$target);
        }

        return $this->createInput($type, $value);
    }

    /**
     * Manage creation of input
     * @param string $type
     * @param array $name
     * @param string $value
     * @param array $args
     * @return string An input
     */
    private function createInput($type, $value)
    {
        // Add prefix
        $name    = $this->fieldPrefix.$this->name;
        $args    = $this->args;
        $input   = '';

        switch ($type)
        {
            case 'text':
                $input = Form::text($name, $value, $args);
                break;

            case 'password':
                $input = Form::password($name, $args);
                break;

            case 'email':
                $input = Form::email($name, $value, $args);
                break;

            case 'url':
                $input = Form::url($name, $value, $args);
                break;

            case 'tag':
                // Overwrite a class element to array
                $args['class'] = 'form-control tagsinput';
                $input = Form::text($name, $value, $args);
                $this->assets[] = ['asset'=>'tag-input-js', 'type'=>'script', 'queue'=>false];
                break;

            case 'textarea':
                $input = Form::textarea($name, $value, $args);
                break;

            case 'wysihtml5':
                // Merge a textarea class element to array
                $args['class'] = 'form-control wysihtml5';
                $args = array_merge(
                    array(
                        'data-stylesheet-url'=>'/packages/sharenjoy/cmsharenjoy/css/wysihtml5-color.css'
                    ), $args);
                $input = Form::textarea($name, $value, $args);
                $this->assets[] = ['asset'=>'bootstrap-wysihtml5-css', 'type'=>'style', 'queue'=>false];
                $this->assets[] = ['asset'=>'wysihtml5-js', 'type'=>'script', 'queue'=>false];
                $this->assets[] = ['asset'=>'bootstrap-wysihtml5-js', 'type'=>'script', 'queue'=>false];
                break;

            case 'select':
                $option = array_get($args, 'option') ?: $this->guessOption($name);
                unset($args['option']);
                $input = Form::select($name, $option, $value, $args);
                break;

            case 'checkbox':
                $option = array_get($args, 'option') ?: $this->guessOption($name);
                unset($args['option']);
                $checked = array_get($args, 'checked') ?: '';
                unset($args['checked']);
                $name = $name.'[]';
                foreach ($option as $optionKey => $optionValue)
                {
                    $input .= '<div class="checkbox"><label>';
                    $isChecked = $checked == $optionKey ? true : false;
                    $input .= Form::checkbox($name, $optionKey, $isChecked);
                    $input .= $optionValue;
                    $input .= '</label></div>';
                }
                break;

            case 'radio':
                $option = array_get($args, 'option') ?: $this->guessOption($name);
                unset($args['option']);
                $checked = array_get($args, 'checked') ?: '';
                unset($args['checked']);
                foreach ($option as $optionKey => $optionValue)
                {
                    $input .= '<div class="radio"><label>';
                    $isChecked = $checked == $optionKey ? true : false;
                    $input .= Form::radio($name, $optionKey, $isChecked);
                    $input .= $optionValue;
                    $input .= '</label></div>';
                }
                break;

            case 'datepicker':
                // Merge a daterange class element to array
                $args['class'] = 'form-control datepicker';
                $args = array_merge(
                    array(
                        'data-format'        => 'yyyy-mm-dd',
                        // 'data-start-date'    => '-2d',
                        // 'data-end-date'      => '+1w',
                        // 'data-disabled-days' => '1,3',
                        // 'data-start-view'    => '2',
                    ), $args);
                $input .= '<div class="input-group"><div class="input-group-addon"><i class="entypo-calendar"></i></div>';
                $input .= Form::text($name, $value, $args);
                $input .= '</div>';
                $this->assets[] = ['asset'=>'bootstrap-datepicker-js', 'type'=>'script', 'queue'=>false];
                break;

            case 'daterange':
                // Merge a daterange class element to array
                $args['class'] = 'form-control daterange daterange-inline add-ranges';
                $args = array_merge(
                            array(
                                'data-format'     => 'YYYY-MM-DD',
                                'data-start-date' => date('Y-m-d', time() - 86400),
                                'data-end-date'   => date('Y-m-d', time()),
                                'data-separator'  => ' ~ '
                            ), $args);
                $input .= '<div class="input-group"><div class="input-group-addon"><i class="entypo-calendar"></i></div>';
                $input .= Form::text($name, $value, $args);
                $input .= '</div>';
                $this->assets[] = ['asset'=>'daterangepicker-css', 'type'=>'style', 'queue'=>false];
                $this->assets[] = ['asset'=>'moment-js', 'type'=>'script', 'queue'=>false];
                $this->assets[] = ['asset'=>'daterangepicker-js', 'type'=>'script', 'queue'=>false];
                break;

            case 'colorpicker':
                // Merge a daterange class element to array
                $args['class'] = 'form-control colorpicker';
                $args = array_merge(array('data-format' => 'hex'), $args);
                $input .= '<div class="input-group"><div class="input-group-addon"><i class="color-preview"></i></div>';
                $input .= Form::text($name, $value, $args);
                $input .= '</div>';
                $this->assets[] = ['asset'=>'bootstrap-colorpicker-js', 'type'=>'script', 'queue'=>false];
                break;

            case 'file':
                $args['class'] = '';
                $args = array_merge(array('accept' => 'image/*'), $args);
                $input .= '<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                <img src="http://placehold.it/200x150" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                            <div>
                                <span class="btn btn-white btn-file">
                                    <span class="fileinput-new">Select image</span>
                                    <span class="fileinput-exists">Change</span>';
                $input .= Form::file($name, $args);
                $input .= '</span>
                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>';
                $this->assets[] = ['asset'=>'file-input-js', 'type'=>'script', 'queue'=>false];
                break;

            default:
                throw new \InvalidArgumentException('Invalid argument.');
        }

        return $input;
    }

    protected function setThemeAssets()
    {
        $package = 'packages/sharenjoy/cmsharenjoy/';
        $assetsConfig  = Config::get('cmsharenjoy::assets');

        if (count($this->assets))
        {
            foreach ($this->assets as $key => $value)
            {
                if ($value['queue'])
                {
                    Theme::asset()->queue($value['type'])
                                  ->add($value['asset'], $package.$assetsConfig[$value['asset']]);
                }
                else
                {
                    Theme::asset()->add($value['asset'], $package.$assetsConfig[$value['asset']]);
                }
            }
        }
    }

    /**
     * Make the form field
     * @param string $name
     * @param array $args
     */
    public function make()
    {
        $type = $this->formType;

        switch ($type)
        {
            case 'create':
            case 'update':
                $this->field()
                     ->help(['tag'=>'span', 'class'=>'help-block'])
                     ->wrapper(['class'=>'col-sm-5'])
                     ->label(['class'=>'col-sm-2 control-label', 'position'=>'top'])
                     ->wrapper(['class'=>'form-group']);
                break;

            case 'list-filter':
                $this->field()
                     ->label(['tag'=>'span', 'position'=>'top'])
                     ->wrapper(['class'=>'list-filter col-md-3 col-sm-6']);
                break;
        }

        // Set assets
        $this->setThemeAssets();

        return $this->inputData;
    }

}
