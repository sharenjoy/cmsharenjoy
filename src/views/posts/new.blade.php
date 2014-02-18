@extends('cmsharenjoy::layouts.interface-new')

@section('title')
    Create New {{ $application_name }}
@stop

@section('heading')
    Create New {{ $application_name }}
@stop

@section('form-items')

    <div class="form-group">
        {{ Form::label( "title" , 'Post Title' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-5">
            {{ Form::text( "title" , Input::old( "title" ) , array( 'class'=>'form-control' , 'placeholder'=>'Post Title' ) ) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label( "content" , 'Post Content' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-6">
            {{ Form::textarea( "content" , Input::old( "content" ) , array( 'class'=>'form-control wysihtml5' , 'data-stylesheet-url'=>'/packages/davzie/cmsharenjoy/css/wysihtml5-color.css' , 'width'=>'100%' , 'placeholder'=>'Post Content' ) ) }}
        </div>
    </div>
    
@stop


@section('scripts')
    @parent
    <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/js/wysihtml5/bootstrap-wysihtml5.css') }}">
    <script src="{{ asset('packages/davzie/cmsharenjoy/js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('packages/davzie/cmsharenjoy/js/wysihtml5/wysihtml5-0.4.0pre.min.js') }}"></script>
    <script src="{{ asset('packages/davzie/cmsharenjoy/js/wysihtml5/bootstrap-wysihtml5.js') }}"></script>
@stop
