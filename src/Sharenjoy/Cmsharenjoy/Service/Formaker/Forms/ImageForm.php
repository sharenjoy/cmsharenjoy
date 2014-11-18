<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Forms;

class ImageForm extends FormAbstract implements FormInterface {

    public function make(Array $data)
    {
        $otherSetting = $data['others'];
        $name         = $data['name'];
        $value        = $data['value'];

        $size = array_get($otherSetting['setting'], 'size') ?: '/200x150&text=-';
        $img = $value ? asset('uploads/'.$value) : "http://placehold.it/{$size}";
        $select_image = pick_trans('cmsharenjoy::buttons.select_image');
        $change       = pick_trans('cmsharenjoy::buttons.change');
        $remove       = pick_trans('cmsharenjoy::buttons.remove');
        $form         = <<<EOE
            <div class="fileinput fileinput-new file-pick-open-manager">
                <div data-type="image" class="fileinput-new thumbnail" id="image-{$name}" style="width: 200px; height: 150px;">
EOE;
        $form        .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
        $form        .= <<<EOE
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

        set_package_asset_to_view('file-picker-reload');
        
        return $form;
    }

}
