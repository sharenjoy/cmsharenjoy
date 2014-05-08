@if(isset($messages))
    @if($messages->has('errors'))
        <div class="alert alert-danger">
            <p><strong>{{trans('cmsharenjoy::admin.some_wrong')}}</strong></p>
            @foreach ($messages->get('errors', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('success'))
        <div class="alert alert-success">
            <p><strong>{{trans('cmsharenjoy::admin.success')}} !</strong></p>
            @foreach ($messages->get('success', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('info'))
        <div class="alert alert-warning">
            <p><strong>{{trans('cmsharenjoy::admin.warning')}} !</strong></p>
            @foreach ($messages->get('info', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif
@endif


@if(isset($sortable) && $sortable === true)
    <div class="alert alert-info">
        <p><strong>{{trans('cmsharenjoy::admin.notice')}} !</strong> {{trans('cmsharenjoy::admin.please_drag')}}</p>
    </div>
@endif