@extends('cmsharenjoy::layouts.interface-create')

@section('title')
    Create New {{ $appName }}
@stop

@section('heading')
    Create New {{ $appName }}
@stop

@section('form-items')
    
    @if(isset($fieldsForm))
        @foreach($fieldsForm as $key => $value)
            @if(isset($value['field']))
                {{$value['field']}}
            @endif
        @endforeach
    @endif
    
@stop


@section('scripts')
    @parent
    <link rel="stylesheet" href="{{ asset('packages/sharenjoy/cmsharenjoy/js/wysihtml5/bootstrap-wysihtml5.css') }}">
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/wysihtml5/wysihtml5-0.4.0pre.min.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/wysihtml5/bootstrap-wysihtml5.js') }}"></script>
@stop
