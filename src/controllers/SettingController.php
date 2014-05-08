<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Setting\SettingInterface;
use Input, Redirect, Message, Debugbar;

class SettingController extends BaseController {

    protected $appName = 'setting';

    public function __construct(SettingInterface $setting)
    {
        $this->repository = $setting;
        parent::__construct();
    }

    public function getIndex()
    {
        $model = $this->repository->getModel();
        $items = $this->item($model);
        
        return $this->layout->with('items', $items);
    }

    public function postIndex()
    {
        $settings = Input::all();
        $settings = array_except($settings, array('_token', 'type'));

        $model = $this->repository->getModel();

        foreach($settings as $key => $value)
        {
            $model = $model->where('key', $key)->first();

            if ($model)
            {
                $model->value = $value;
                $model->save();
            }
        }

        $items = $this->item($model);

        Message::add('success', trans('cmsharenjoy::admin.success_updated'))->flash();
        
        return Redirect::to($this->objectUrl)->with('items', $items);
    }

    protected function item($model)
    {
        $items['general'] = $model->where('module', 'general')->get();
        $items['file']    = $model->where('module', 'file')->get();

        return $items;
    }

}