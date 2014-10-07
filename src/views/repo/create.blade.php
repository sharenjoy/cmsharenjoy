@extends('cmsharenjoy::layouts.interface-create')

@section('title')
{{pick_trans('app.add')}}{{pick_trans("app.$appName")}}
@stop

@section('form-items')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>{{pick_trans('app.add')}}</h3>
                    </div>
                    
                    <!-- <div class="panel-options">
                        <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                    </div> -->
                </div>
                
                <div class="panel-body">
                    
                    {{Form::open(['url'=>$createUrl, 'class'=>'form-horizontal form-groups-bordered', 'role'=>'form', 'id'=>'created-form'])}}

                        @if(isset($fieldsForm))
                            @foreach($fieldsForm as $key => $value)
                                {{$value}}
                            @endforeach
                        @endif
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                @include('cmsharenjoy::partials.button-create')
                            </div>
                        </div>

                    {{Form::close()}}
                    
                </div>
            </div>
        </div>

        <!-- <div class="col-md-4">
            <div class="panel panel-primary" data-collapsed="0">
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>{{pick_trans('app.add')}}</h3>
                    </div>
                </div>
                
                <div class="panel-body">
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            @include('cmsharenjoy::partials.button-update')
                        </div>
                    </div>
                    
                </div>
            </div>
        </div> -->

    </div>
@stop


@section('scripts')
    @parent
@stop
