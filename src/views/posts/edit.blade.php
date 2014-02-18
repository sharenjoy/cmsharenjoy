@extends('cmsharenjoy::layouts.interface-edit')

@section('title')
    Edit {{ $application_name }}: {{ $item->title }}
@stop

@section('heading')
    Edit {{ $application_name }}: <small>{{ $item->title }}</small>
@stop

@section('form-items')

    <div class="form-group">
        {{ Form::label( "title" , 'Post Title' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-5">
            {{ Form::text( "title" , Input::old( "title" , $item->title ) , array( 'class'=>'form-control' , 'placeholder'=>'Post Title' ) ) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label( "tags" , 'Object Tags' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-5">
            {{ Form::text( "tags" , Input::old( "tags" , $item->tags_csv ) , array( 'class'=>'form-control tagsinput' , 'placeholder'=>'Comma Separated Tags' ) ) }}
            <span class="help-block">Press enter or type a comma after each tag to set it.</span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label( "content" , 'Post Content' , array( 'class'=>'col-sm-2 control-label' ) ) }}
        <div class="col-sm-6">
            {{ Form::textarea( "content" , Input::old( "content" , $item->content ) , array( 'class'=>'form-control wysihtml5' , 'data-stylesheet-url'=>'/packages/davzie/cmsharenjoy/css/wysihtml5-color.css' , 'placeholder'=>'Post Content' ) ) }}
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
