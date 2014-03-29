<?php namespace Sharenjoy\Cmsharenjoy\Formaker;

interface FormakerInterface {

    /**
     * Make a form fields
     * @param  string $name This is field name
     * @param  array      
     * @return string
     */
    public function make();

    /**
     * Can compose some of custom fields
     * @param  array $formConfig It's config of fields
     * @param  string $type
     * @return array  The data of form fields
     */
    public function composeForm($formConfig, $type);

}