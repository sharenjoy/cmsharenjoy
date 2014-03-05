@extends('cmsharenjoy::layouts.interface-new')

@section('title')
    Create New {{ $appName }}
@stop

@section('heading')
    Create New {{ $appName }}
@stop

@section('form-items')

    <div class="form-group">
        {{ Form::label( "title" , 'Post Title' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-5">
            {{ Form::text( "title" , Input::old( "title" ) , array( 'class'=>'form-control' , 'placeholder'=>'Post Title' ) ) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label( "tags" , 'Tags' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-5">
            {{ Form::text( "tags" , Input::old( "tags" ) , array( 'class'=>'form-control tagsinput' , 'placeholder'=>'Comma Separated Tags' ) ) }}
            <span class="help-block">Press enter or type a comma after each tag to set it.</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label( "content" , 'Post Content' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-6">
            {{ Form::textarea( "content" , Input::old( "content" ) , array( 'class'=>'form-control wysihtml5' , 'data-stylesheet-url'=>'/packages/sharenjoy/cmsharenjoy/css/wysihtml5-color.css' , 'width'=>'100%' , 'placeholder'=>'Post Content' ) ) }}
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
