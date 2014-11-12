<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Form, Lang, Config, Session, Categorize;

class TwitterBootstrapV3 extends FormakerAbstract implements FormakerInterface {

    protected $theme = 'TwitterBootstrapV3';

    protected function label()
    {
        $tag = "label";
        $attributes = [
            'for'   => $this->name,
            'class' => array_get($this->setting, 'class.label') ?: array_get($this->config, 'class.label')
        ];
        $content = array_get($this->setting, 'label') ?: $this->prettifyFieldName();

        return (new Templates\BlockTemplate())->make(compact('tag','attributes','content'));
    }

    protected function help()
    {
        $result = $this->getLanguageSetting('help');
        if ( ! $result) return;

        $tag = 'span';
        $attribures = [
            'class' => array_get($this->setting, 'help-class') ?: array_get($this->config, 'class.help')
        ];
        $content = $result;

        return (new Templates\BlockTemplate())->make(compact('tag','attributes','content'));
    }

    protected function error()
    {
        if (isset($this->errors) && $this->errors->has($this->name))
        {
            $tag = "span";
            $attributes = [
                'class' => array_get($this->setting, 'class.error') ?: array_get($this->config, 'class.error')
            ];
            $content = $this->errors->first($this->name);

            return (new Templates\BlockTemplate())->make(compact('tag','attributes','content'));
        }

        return;
    }

    protected function field()
    {
        $className = 'Sharenjoy\Cmsharenjoy\Service\Formaker\Forms\\'.ucfirst(strtolower($this->type)).'Form';
        $data = [
            'name'  => $this->name,
            'value' => $this->value
        ];

        return (new $className())->make(array_merge($data, $this->args));
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
        $name         = $this->name;
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
                $categoryType = $this->setting['category'];
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

    /**
     * Make the form field
     * @param string $name
     * @param array $args
     */
    public function make()
    {
        $template = new Templates\CommonTemplate();
        $data = [
            'label' => $this->label(),
            'help'  => $this->help(),
            'error' => $this->error(),
            'field' => $this->field(),
        ];

        return $template->make($data);
    }

}
