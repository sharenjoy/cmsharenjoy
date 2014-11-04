@extends('cmsharenjoy::layouts.interface')

@section('title')
{{pick_trans('app.manage')}}{{pick_trans("app.$appName")}}
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
            <div class="col-md-12">
                @include('cmsharenjoy::partials.list-table')
            </div>
        </div>
    @else
        <div class="alert alert-info">
            {{pick_trans('app.no_item_yet')}}
        </div>
    @endif
@stop


@section('scripts')
    @parent
@stop
