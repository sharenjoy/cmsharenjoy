<?php namespace Sharenjoy\Cmsharenjoy\Setting;

use Sharenjoy\Cmsharenjoy\Controllers\BaseController;
use Input, Redirect, Message, Form, Response, Request;

class SettingController extends BaseController {

    public function __construct(SettingInterface $repo)
    {
        $this->repository = $repo;
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
            Message::error(trans('cmsharenjoy::exception.not_found', ['id' => $data['id']]));
            return Redirect::to($this->objectUrl);
        }

        return Response::json(Message::json('success', trans('cmsharenjoy::app.success_updated'), $data), 200);
    }

    protected function item()
    {
        $model = $this->repository->getModel();

        $items['general']['item'] = $model->where('module', 'general')->orderBy('sort')->get();
        $items['file']['item']    = $model->where('module', 'file')->orderBy('sort')->get();

        return $items;
    }

}