@extends('cmsharenjoy::layouts.interface')

@section('title')
    {{ Lang::get('cmsharenjoy::admin.manage') }} {{ Lang::get("cmsharenjoy::app.$appName") }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="sorted">
                @include('cmsharenjoy::partials.function')
            </div>
        </div>
    </div>

    @include('cmsharenjoy::partials.messaging')
    
    @if( ! $items->isEmpty() )
        <div class="row">
            @include('cmsharenjoy::partials.maintable')
        </div>
    @else
        <div class="alert alert-info">
            <strong>No Items Yet:</strong> You don't have any items here just yet. Add one using the button below.
        </div>
    @endif

@stop


@section('scripts')
    @parent
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/bootstrap-colorpicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('packages/sharenjoy/cmsharenjoy/js/daterangepicker/daterangepicker-bs3.css') }}">
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('packages/sharenjoy/cmsharenjoy/js/daterangepicker/daterangepicker.js') }}"></script>
@stop
