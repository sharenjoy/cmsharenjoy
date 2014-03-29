@extends('cmsharenjoy::layouts.interface-new')

@section('title')
    Create New {{ $appName }}
@stop

@section('heading')
    Create New {{ $appName }}
@stop

@section('form-items')

    <div class="form-group">
        {{ Form::label( "tag" , 'Tag' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-5">
            {{ Form::text( "tag" , Input::old( "tag" ) , array( 'class'=>'form-control' , 'placeholder'=>'Tag' ) ) }}
        </div>
    </div>
    
@stop


@section('scripts')
    @parent
    <link rel="stylesheet" href="{{ asset('packages/sharenjoy/cmsharenjoy/js/wysihtml5/bootstrap-wysihtml5.css') }}">
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/wysihtml5/wysihtml5-0.4.0pre.min.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/wysihtml5/bootstrap-wysihtml5.js') }}"></script>
@stop
