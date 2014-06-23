<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Setting\SettingInterface;
use Input, Redirect, Message, Form, Response, Request;

class SettingController extends BaseController {

    public function __construct(SettingInterface $setting)
    {
        $this->repository = $setting;
        parent::__construct();
    }

    public function getIndex()
    {
        $items = $this->item();
        
        $buttons = Form::button(trans('cmsharenjoy::buttons.save'), array('class'=>'btn btn-success btn-save')).'&nbsp;'.
                   Form::button(trans('cmsharenjoy::buttons.reset'), array('class'=>'btn btn-reset'));

        return $this->layout->with('items', $items)
                            ->with('buttons', $buttons);
    }

    public function postStore()
    {
        if( ! Request::ajax()) Response::json('error', 400);

        $data['id']    = Input::get('id');
        $data['item']  = Input::get('item');
        $data['value'] = Input::get('value');

        if (Input::get('type') == 'checkbox' && $data['value'] != '')
        {
            $array = explode('&', $data['value']);
            foreach($array as $value)
            {
                $val = explode('=', $value);
                $ary[] = $val[1];
            }
            $data['value'] = implode(',', $ary);
        }

        try
        {
            $this->repository->edit($data['item'], array('value' => $data['value']), 'key');
        }
        catch (\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
        {
            Message::output('flash', 'errors', trans('cmsharenjoy::exception.not_found', array('id' => $data['id'])));
            return Redirect::to($this->objectUrl);
        }

        return Response::json(Message::output('json', 'success', trans('cmsharenjoy::admin.success_ordered'), $data), 200);
    }

    protected function item()
    {
        $model = $this->repository->getModel();

        $items['general']['item'] = $model->where('module', 'general')->get();
        $items['file']['item']    = $model->where('module', 'file')->get();

        return $items;
    }

}