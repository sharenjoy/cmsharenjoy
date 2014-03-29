@if(isset($messages))
    @if($messages->has('errors'))
        <div class="alert alert-danger">
            <p><strong>Whoops! There was a problem.</strong></p>
            @foreach ($messages->get('errors', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('success'))
        <div class="alert alert-success">
            <p><strong>Success!</strong></p>
            @foreach ($messages->get('success', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif

    @if($messages->has('info'))
        <div class="alert alert-warning">
            <p><strong>Warning!</strong></p>
            @foreach ($messages->get('info', '<p>:message</p>') as $msg)
                {{$msg}}
            @endforeach
        </div>
    @endif
@endif


@if(isset($sortable) && $sortable === true)
    <div class="alert alert-info">
        <p><strong>Notice!</strong> You can use drap to order this list.</p>
    </div>
@endif