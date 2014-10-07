<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Form, Lang, Theme, Config, Session, Categorize;

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

    private $assets = [];

    protected $errors;

    public function __construct()
    {
        if (Session::has('sharenjoy.validation.errors'))
        {
            $this->errors = Session::get('sharenjoy.validation.errors');
        }
    }

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
        $targetA = 'app.form.help.'.Session::get('onController').'.'.$this->name;
        $targetB = 'app.form.help.'.$this->name;
        
        if (array_get($this->args, 'help'))
        {
            $description = array_get($this->args, 'help');
            unset($this->args['help']);
        }
        elseif (Lang::has('cmsharenjoy::'.$targetA) || Lang::has($targetA))
        {
            if (Lang::has($targetA))
            {
                $description = Lang::get($targetA);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetA))
            {
                $description = Lang::get('cmsharenjoy::'.$targetA);
            }

            unset($this->args['help']);    
        }
        elseif (Lang::has('cmsharenjoy::'.$targetB) || Lang::has($targetB))
        {
            if (Lang::has($targetB))
            {
                $description = Lang::get($targetB);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetB))
            {
                $description = Lang::get('cmsharenjoy::'.$targetB);
            }

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

    public function error()
    {
        if (Session::has('sharenjoy.validation.errors'))
        {
            $this->inputData .= $this->errors->first($this->fieldPrefix.$this->name, '<span class="validate-has-error">:message</span>');
        }
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
        $targetA = 'app.form.placeholder.'.Session::get('onController').'.'.$this->name;
        $targetB = 'app.form.placeholder.'.$this->name;

        if (array_get($this->args, 'placeholder'))
        {
            unset($this->args['placeholder']);
        }
        elseif (Lang::has('cmsharenjoy::'.$targetA) || Lang::has($targetA))
        {
            if (Lang::has($targetA))
            {
                $this->args['placeholder'] = Lang::get($targetA);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetA))
            {
                $this->args['placeholder'] = Lang::get('cmsharenjoy::'.$targetA);
            }
        }
        elseif (Lang::has('cmsharenjoy::'.$targetB) || Lang::has($targetB))
        {
            if (Lang::has($targetB))
            {
                $this->args['placeholder'] = Lang::get($targetB);
            }
            elseif (Lang::has('cmsharenjoy::'.$targetB))
            {
                $this->args['placeholder'] = Lang::get('cmsharenjoy::'.$targetB);
            }
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
        $name         = $this->fieldPrefix.$this->name;
        $args         = $this->args;
        $input        = '';
        $dependencies = [];

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

            case 'album':
                $input = '<button type="button" class="btn btn-info btn-lg btn-icon file-album-pick-open-manager">
                            '.trans('cmsharenjoy::buttons.open').'
                            <i class="entypo-picture"></i>
                        </button>';
                break;

            case 'tag':
                // Overwrite a class element to array
                $args['class'] = 'form-control tagsinput';
                $input = Form::text($name, $value, $args);
                $this->assets[] = 'tag';
                break;

            case 'textarea':
                $input = Form::textarea($name, $value, $args);
                break;

            case 'wysiwyg-simple':
                $args['class'] = 'form-control wysiwyg-simple';
                $input = Form::textarea($name, $value, $args);
                $this->assets[] = 'ckeditor';
                break;

            case 'wysiwyg-advanced':
                $args['class'] = 'form-control wysiwyg-advanced';
                $input = Form::textarea($name, $value, $args);
                $this->assets[] = 'ckeditor';
                break;

            case 'select':
                $option = $this->guessOption(array_get($args, 'option'));
                unset($args['option']);
                $input = Form::select($name, $option, $value, $args);
                break;

            case 'category':
                $categoryType = $args['category'];
                unset($args['category']);
                $categories = Categorize::getCategoryProvider()->root()
                                                               ->whereType($categoryType)
                                                               ->orderBy('sort', 'asc')
                                                               ->get();
                $option = array('0' => Lang::get('cmsharenjoy::option.pleaseSelect')) +
                          Categorize::tree($categories)->lists('title', 'id');

                $input = Form::select($name, $option, $value, $args);
                break;

            case 'checkbox':
                $option = $this->guessOption(array_get($args, 'option'));
                unset($args['option']);
                $name = $name.'[]';
                if ( ! is_array($value)) $value = explode(',', (string)$value);
                foreach ($option as $optionKey => $optionValue)
                {
                    $input .= '<div class="checkbox"><label>';

                    $isChecked = in_array($optionKey, $value) ? true : false;
                    $input .= Form::checkbox($name, $optionKey, $isChecked);
                    $input .= $optionValue;
                    $input .= '</label></div>';
                }
                break;

            case 'radio':
                $option = $this->guessOption(array_get($args, 'option'));
                unset($args['option']);
                foreach ($option as $optionKey => $optionValue)
                {
                    $input .= '<div class="radio"><label>';
                    $isChecked = $value == $optionKey ? true : false;
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
                $this->assets[] = 'datepicker';
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
                $this->assets[] = 'daterange';
                break;

            case 'colorpicker':
                // Merge a daterange class element to array
                $args['class'] = 'form-control colorpicker';
                $args = array_merge(array('data-format' => 'hex'), $args);
                $input .= '<div class="input-group"><div class="input-group-addon"><i class="color-preview"></i></div>';
                $input .= Form::text($name, $value, $args);
                $input .= '</div>';
                $this->assets[] = 'colorpicker';
                break;

            case 'image':
                $args['class'] = '';
                $size = array_get($args, 'size') ?: '/200x150&text=-';
                unset($args['size']);
                $img = $value ? asset('uploads/'.$value) : "http://placehold.it/{$size}";
                $select_image = Lang::get('cmsharenjoy::buttons.select_image');
                $change       = Lang::get('cmsharenjoy::buttons.change');
                $remove       = Lang::get('cmsharenjoy::buttons.remove');
                $input        = <<<EOE
                    <div class="fileinput fileinput-new file-pick-open-manager">
                        <div data-type="image" class="fileinput-new thumbnail" id="image-{$name}" style="width: 200px; height: 150px;">
EOE;
                $input       .= Form::hidden($name, $value);
                $input       .= <<<EOE
                            <img src="{$img}">
                        </div>
                        <div>
                            <span class="btn btn-white btn-file">
                                <span class="fileinput-new">{$select_image}</span>
                                <span class="fileinput-exists">{$change}</span>
                            </span>
                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">{$remove}</a>
                        </div>
                    </div>
EOE;
                $this->assets[] = 'file-picker-reload';
                break;

            case 'file':
                $args['class'] = '';
                $select_image  = Lang::get('cmsharenjoy::buttons.select_file');
                $change        = Lang::get('cmsharenjoy::buttons.change');
                $remove        = Lang::get('cmsharenjoy::buttons.remove');
                $input         = <<<EOE
                    <div class="fileinput fileinput-new file-pick-open-manager">
                        <div data-type="file" class="fileinput-new thumbnail" id="file-{$name}" style="width: 100px; height: 100px;">
EOE;
                $input        .= Form::hidden($name, $value);
                $input        .= <<<EOE
                            <img src="http://placehold.it/100&text=FILE">
                        </div>
                        <div>
                            <span class="btn btn-white btn-file">
                                <span class="fileinput-new">{$select_image}</span>
                                <span class="fileinput-exists">{$change}</span>
                            </span>
                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">{$remove}</a>
                        </div>
                    </div>
EOE;
                $this->assets[] = 'file-picker-reload';
                break;
                
            default:
                throw new \InvalidArgumentException('Invalid argument.');
        }

        return $input;
    }

    protected function setThemeAssets()
    {
        if (count($this->assets))
        {
            $path    = Config::get('cmsharenjoy::assets.path');
            $package = Config::get('cmsharenjoy::assets.package');

            foreach ($this->assets as $asset)
            {
                $pkg = $package[$asset];
                foreach ($pkg as $key => $value)
                {
                    if ($value['queue'])
                    {
                        Theme::asset()->queue($value['type'])
                                      ->add($key, $path.$value['file']);
                    }
                    else
                    {
                        Theme::asset()->add($key, $path.$value['file']);
                    }
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
                if (Session::has('sharenjoy.validation.errors') && $this->errors->has($this->fieldPrefix.$this->name))
                {
                    $this->field()
                     ->error()
                     ->help(['tag'=>'span', 'class'=>'help-block'])
                     ->wrapper(['class'=>'col-sm-7 validate-has-error'])
                     ->label(['class'=>'col-sm-2 control-label', 'position'=>'top'])
                     ->wrapper(['class'=>'form-group']);
                }
                else
                {
                    $this->field()
                     ->help(['tag'=>'span', 'class'=>'help-block'])
                     ->wrapper(['class'=>'col-sm-7'])
                     ->label(['class'=>'col-sm-2 control-label', 'position'=>'top'])
                     ->wrapper(['class'=>'form-group']);
                }
                break;

            case 'filter':
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
