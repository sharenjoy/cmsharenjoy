@if( $errors->all() )
    <div class="alert alert-danger">
        <p><strong>Whoops! There was a problem.</strong></p>
        @foreach ($errors->all('<p>:message</p>') as $msg)
            {{ $msg }}
        @endforeach
    </div>
@endif

@if( $success->all() )
    <div class="alert alert-success">
        <p><strong>Success!</strong></p>
        @foreach ($success->all('<p>:message</p>') as $msg)
            {{ $msg }}
        @endforeach
    </div>
@endif

@if( isset($sortable) && $sortable == true )
    <div class="alert alert-info">
        <p><strong>Notes!</strong> You can use drap to order this list.</p>
    </div>
@endif