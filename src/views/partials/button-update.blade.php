{{Form::reset(trans('cmsharenjoy::buttons.reset'), ['class'=>'btn btn-large btn-default'])}}
&nbsp;
{{Form::submit(trans('cmsharenjoy::buttons.update'), ['class'=>'btn btn-large btn-success'])}}
&nbsp;
{{Form::button(trans('cmsharenjoy::buttons.update_exit'), ['class'=>'btn btn-large btn-blue', 'data-type'=>'exit'])}}
&nbsp;
{{Form::button(trans('cmsharenjoy::buttons.cancel'), ['class'=>'btn btn-large btn-danger', 'onclick'=>'location.href="'.Session::get('goBackPrevious').'"'])}}

