<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Forms;

use Theme, Config;

abstract class FormAbstract {

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
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     * @return string
     */
    protected function attributes($attributes)
    {
        $html = array();

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array) $attributes as $key => $value)
        {
            $element = $this->attributeElement($key, $value);

            if ( ! is_null($element)) $html[] = $element;
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) $key = $value;

        if ( ! is_null($value)) return $key.'="'.e($value).'"';
    }

}