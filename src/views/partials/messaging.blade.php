@if(isset($messages))
    @if($messages->has('error'))
        <div class="alert alert-danger">
            <p><strong>{{trans('cmsharenjoy::app.some_wrong')}}</strong></p>
            @foreach ($messages->get('error', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('success'))
        <div class="alert alert-success">
            <p><strong>{{trans('cmsharenjoy::app.success')}} !</strong></p>
            @foreach ($messages->get('success', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('info'))
        <div class="alert alert-info">
            <p><strong>{{trans('cmsharenjoy::app.info')}} !</strong></p>
            @foreach ($messages->get('info', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('warning'))
        <div class="alert alert-warning">
            <p><strong>{{trans('cmsharenjoy::app.warning')}} !</strong></p>
            @foreach ($messages->get('warning', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif
@endif


@if(isset($sortable) && $sortable === true)
    <div class="alert alert-info">
        <p><strong>{{trans('cmsharenjoy::app.notice')}} !</strong> {{trans('cmsharenjoy::app.please_drag')}}</p>
    </div>
@endif