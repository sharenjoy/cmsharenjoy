<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker;

use Form, Lang;

class BootstrapFrontend extends FormakerBaseAbstract implements FormakerInterface {

    /*
     * What should each form element be
     * wrapped within?
    */
    protected $wrapper = 'div';

    /*
     * What class should the wrapper
     * element receive?
    */
    protected $wrapperClass = 'form-group';

    /*
     * What should each form element be
     * inner within?
    */
    protected $inner = 'div';

    /*
     * What class should the inner
     * element receive?
    */
    protected $innerClass = 'col-sm-5';

    /**
     * Label class name
     */
    protected $labelClass = 'col-sm-2 control-label';

    /**
     * Help block class name
     */
    protected $helpClass = 'help-block';

    /**
     * Should form inputs receive a class name?
     */
    protected $inputClass    = 'form-control';
    protected $tagClass      = 'form-control tagsinput';
    protected $textareaClass = 'fform-control wysihtml5';

    /**
     * Frequent input names can map
     * to their respective input types.
     *
     * This way, you may do FormField::description()
     * and it'll know to automatically set it as a textarea.
     * Otherwise, do FormField::thing(['type' = 'textarea'])
     *
     */
    protected $commonInputsLookup = [
        'email'                 => 'email',
        'emailAddress'          => 'email',
        
        'description'           => 'textarea',
        'bio'                   => 'textarea',
        'body'                  => 'textarea',
        
        'password'              => 'password',
        'password_confirmation' => 'password',
    ];



    /**
     * Make the form field
     *
     * @param string $name
     * @param array $args
     */
    public function make($name, array $args)
    {
        $wrapper = $this->createWrapper();
        $field = $this->createField($name, $args);

        return str_replace('{{FIELD}}', $field, $wrapper);
    }

    /**
     * Prepare the wrapping container for
     * each field.
     */
    protected function createWrapper()
    {
        $wrapper = $this->wrapper;
        $wrapperClass = $this->wrapperClass;

        return $wrapper ? '<'.$wrapper.' class="'.$wrapperClass.'">{{FIELD}}</'.$wrapper.'>' : '{{FIELD}}';
    }

    /**
     * Prepare the inner container for
     * each field.
     */
    protected function createInner()
    {
        $inner      = $this->inner;
        $innerClass = $this->innerClass;

        return $inner ? '<'.$inner.' class="'.$innerClass.'">{{INPUT}}</'.$inner.'>' : '{{INPUT}}';
    }

    /**
     * Create the form field
     *
     * @param string $name
     * @param array $args
     */
    protected function createField($name, $args)
    {
        // If the user specifies an input type, we'll just use that.
        // Otherwise, we'll take a best guess approach.
        $type = array_get($args, 'type') ?: $this->guessInputType($name);

        $field = $this->createLabel($args, $name);
        unset($args['label']);

        $help = array_get($args, 'help');
        unset($args['help']);

        // Compose the input filed
        $input = $this->createInput($type, $args, $name);
        // Compose help block
        $input .= $help ? $this->createHelp($help) : '';

        $inner = $this->createInner();
        $field .= str_replace('{{INPUT}}', $input, $inner);

        return $field;
    }

    /**
     * Handle of creation of the label
     *
     * @param array $args
     * @param string $name
     */
    protected function createLabel($args, $name)
    {
        $label = array_get($args, 'label');

        // If no label was provided, let's do our best to construct
        // a label from the method name.
        is_null($label) and $label = $this->prettifyFieldName($name);

        return $label ? Form::label($name, $label, array('class' => $this->labelClass)) : '';
    }

    /**
     * Manage creation of input
     *
     * @param string $type
     * @param array $args
     * @param string $name
     */
    protected function createInput($type, $args, $name)
    {
        $input = '';

        // We'll default to Bootstrap-friendly input class names
        $args = array_merge(['class' => $this->inputClass], $args);

        switch ($type)
        {
            case 'text':
                $input = Form::text($name, null, $args);
                break;
            case 'tag':
                // Merge a tag class element to array
                $args['class'] = $this->tagClass;
                $input = Form::text($name, null, $args);
                break;
            case 'textarea':
                // Merge a textarea class element to array
                $args['class'] = $this->textareaClass;
                $input = Form::textarea($name, null, $args);
                break;
            case 'password':
                $input = Form::password($name, $args);
                break;
            default:
                throw new \InvalidArgumentException('Invalid argument.');
        }

        return $input;
    }

    /**
     * Create help block
     * @param  string $help The description of help
     * @return string       The element of help block
     */
    protected function createHelp($help)
    {
        $helpClass = $this->helpClass;
        return '<span class="'.$helpClass.'">'.$help.'</span>';
    }

    /**
     * Provide a best guess for what the
     * input type should be.
     *
     * @param string $name
     */
    protected function guessInputType($name)
    {
        return array_get($this->commonInputsLookup, $name) ?: 'text';
    }

}
