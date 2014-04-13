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
            <div class="col-md-7">
                @include('cmsharenjoy::partials.list-table')
            </div>

            <div class="col-md-5">

                <div class="panel panel-primary" data-collapsed="0">
            
                    <div class="panel-heading">
                        <div class="panel-title">
                            Simple Draggable List
                        </div>
                        
                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        </div>
                    </div>
                    
                    <div class="panel-body">

                        <div id="list-1" class="nested-list dd with-margins">

                            <ul class="dd-list">
                                @foreach($categories as $keyOne => $valueOne)
                                <li class="dd-item" data-id="{{$valueOne['id']}}">
                                    <div class="dd-handle"> {{$valueOne['title']}} </div>
                                    
                                    @if(isset($valueOne['children']) && count($valueOne['children']))
                                    <ul class="dd-list">
                                        @foreach($valueOne['children'] as $keyTwo => $valueTwo)
                                            <li class="dd-item" data-id="{{$valueTwo['id']}}">
                                                <div class="dd-handle"> {{$valueTwo['title']}} </div>
                                            </li>
                                        @endforeach

                                        @if(isset($valueTwo['children']) && count($valueTwo['children']))
                                        <ul class="dd-list">
                                            @foreach($valueTwo['children'] as $keyThree => $valueThree)
                                                <li class="dd-item" data-id="{{$valueThree['id']}}">
                                                    <div class="dd-handle"> {{$valueThree['title']}} </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </ul>
                                    @endif

                                </li>
                                @endforeach
                            </ul>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <strong>No Items Yet:</strong> You don't have any items here just yet. Add one using the button below.
        </div>
    @endif
@stop


@section('scripts')
    @parent
    <script src="{{asset('packages/sharenjoy/cmsharenjoy/js/jquery.nestable.js')}}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($)
        {
            $(".dd").nestable({
                maxDepth: {{$categoryLevelNum}},
                expandBtnHTML: false,
                collapseBtnHTML: false,
            }).on('change', function(e) {
                var list = e.length ? e : $(e.target);
                var result = {};

                result = {
                    'sort_value': window.JSON.stringify(list.nestable('serialize'))
                };
                // console.log(result);

                $.post("../order", result, function(data, status) {

                });
            });
        });
    </script>
@stop
