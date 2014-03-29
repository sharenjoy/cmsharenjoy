@extends('cmsharenjoy::layouts.interface-update')

@section('title')
    Edit {{ $appName }}: {{ $item->title }}
@stop

@section('heading')
    Edit {{ $appName }}: <small>{{ $item->title }}</small>
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
    
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/bootstrap-colorpicker.min.js') }}"></script>
    
    <link rel="stylesheet" href="{{ asset('packages/sharenjoy/cmsharenjoy/js/daterangepicker/daterangepicker-bs3.css') }}">
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/daterangepicker/daterangepicker.js') }}"></script>

    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/fileinput.js') }}"></script>

@stop
