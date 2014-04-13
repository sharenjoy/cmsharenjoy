@extends('cmsharenjoy::layouts.interface')

@section('content')
    
    <div class="row">
        <div class="col-md-12">
            
            <div class="sorted">
                @include('cmsharenjoy::partials.function')
            </div>

            @include('cmsharenjoy::partials.messaging')

            @yield('form-items')

        </div>
    </div>
@stop
