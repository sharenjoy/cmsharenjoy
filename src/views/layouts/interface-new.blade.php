@extends('cmsharenjoy::layouts.interface')

@section('content')

    <div class="row">
        <div class="col-md-12">
            
            <div class="sorted">
                @include('cmsharenjoy::partials.function')
            </div>

            @include('cmsharenjoy::partials.messaging')

            <div class="panel panel-primary" data-collapsed="0">
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <h2>@yield('heading')</h2>
                    </div>
                    
                    <!-- <div class="panel-options">
                        <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                    </div> -->
                </div>
                
                <div class="panel-body">
                    
                    {{ Form::open( array( 'url'=>$newUrl, 'class'=>'form-horizontal form-groups-bordered', 'role'=>'form' ) ) }}

                        @yield('form-items')
                        
                        <div class="form-group">
                            <div class="col-sm-8">
                                {{ Form::submit('Create Item' , array('class'=>'btn btn-large btn-success')) }}
                            </div>
                        </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop