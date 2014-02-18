@extends('cmsharenjoy::layouts.interface')

@section('title')
    {{ Lang::get('cmsharenjoy::admin.manage') }} {{ Lang::get("cmsharenjoy::app.$application_name") }}
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