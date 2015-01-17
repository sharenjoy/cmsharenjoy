@extends('cmsharenjoy::layouts.interface')

@section('title')
    {{pick_trans('manage')}}{{pick_trans($onController)}}
@stop

@section('content')

    @include('cmsharenjoy::partials.function')

    @include('cmsharenjoy::partials.messaging')
    
    @if( ! $items->isEmpty())
        <div class="row">
            <div class="col-md-12">
                @include('cmsharenjoy::partials.list-table')
            </div>
        </div>
    @else
        <div class="alert alert-info">
            {{pick_trans('no_item_yet')}}
        </div>
    @endif
@stop


@section('scripts')
    @parent
@stop
