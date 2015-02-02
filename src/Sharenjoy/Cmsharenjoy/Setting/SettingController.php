<?php namespace Sharenjoy\Cmsharenjoy\Setting;

use Sharenjoy\Cmsharenjoy\Controllers\BaseController;
use Input, Redirect, Message, Form, Response, Request;

class SettingController extends BaseController {

    public function __construct(SettingInterface $repo)
    {
        $this->repo = $repo;
        parent::__construct();
    }

    public function getIndex()
    {
        $buttons = Form::button(pick_trans('buttons.save'), ['class'=>'btn btn-success btn-save']).'&nbsp;'.
                   Form::button(pick_trans('buttons.reset'), ['class'=>'btn btn-reset']);

        return $this->layout->with('items', $this->item())
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
            $this->repo->edit($data['item'], ['value' => $data['value']], 'key');
        }
        catch (\Sharenjoy\Cmsharenjoy\Exception\EntityNotFoundException $e)
        {
            Message::error(pick_trans('exception.not_found', ['id' => $data['id']]));
            return Redirect::to($this->objectUrl);
        }

        return Message::json(200, pick_trans('success_updated'), $data);
    }

    protected function item()
    {
        $model = $this->repo->getModel();

        $items['general']['item'] = $model->where('module', 'general')->orderBy('sort')->get();
        $items['file']['item']    = $model->where('module', 'file')->orderBy('sort')->get();

        return $items;
    }

}