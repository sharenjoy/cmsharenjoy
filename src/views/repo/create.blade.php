@extends('cmsharenjoy::layouts.interface-create')

@section('title')
    Create New {{ $appName }}
@stop

@section('form-items')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <h2>Create New {{$appName}}</h2>
                    </div>
                    
                    <!-- <div class="panel-options">
                        <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                    </div> -->
                </div>
                
                <div class="panel-body">
                    
                    {{ Form::open( array( 'url'=>$createUrl, 'class'=>'form-horizontal form-groups-bordered', 'role'=>'form' ) ) }}

                        @if(isset($fieldsForm))
                            @foreach($fieldsForm as $key => $value)
                                @if(isset($value['field']))
                                    {{$value['field']}}
                                @endif
                            @endforeach
                        @endif
                        
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


@section('scripts')
    @parent
@stop
