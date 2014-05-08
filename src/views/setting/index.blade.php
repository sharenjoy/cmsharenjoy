@extends('cmsharenjoy::layouts.interface')

@section('title')
{{trans('cmsharenjoy::app.'.$appName)}}
@stop

@section('content')
    
    <div class="row">
        <div class="col-md-12">
            <div class="sorted">

                <div class="panel panel-primary" data-collapsed="0">

                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h2>{{ Lang::get("cmsharenjoy::app.$appName") }}</h2>
                        </div>
                        <div class="panel-options">
                            <!-- <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> -->
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <!-- <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a> -->
                            <!-- <a href="#" data-rel="close"><i class="entypo-cancel"></i></a> -->
                        </div>
                    </div>
                    
                    <!-- panel body -->
                    <div class="panel-body">
                        
                        @include('cmsharenjoy::partials.messaging')
                        
                        <div class="row">
                            <div class="col-md-6">
                            
                                <ul class="nav nav-tabs bordered"><!-- available classes "bordered", "right-aligned" -->
                                    <li class="active">
                                        <a href="#general" data-toggle="tab">
                                            <span class="visible-xs"><i class="entypo-home"></i></span>
                                            <span class="hidden-xs">General</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#file" data-toggle="tab">
                                            <span class="visible-xs"><i class="entypo-home"></i></span>
                                            <span class="hidden-xs">File</span>
                                        </a>
                                    </li>
                                </ul>
                                
                                <div class="tab-content">
                                    <div class="tab-pane active" id="general">
                                            
                                        {{ Form::open( array( 'url'=>$objectUrl , 'class'=>'form-horizontal form-groups-bordered' , 'role'=>'form', 'id'=>'item-form' ) ) }}
                                            
                                            @foreach($items['general'] as $item)
                                            <div class="form-group">
                                                <div class="col-sm-12 col-md-12">
                                                    <p>{{ Form::label( $item->key , $item->label , array( 'class'=>'control-label' ) ) }}</p>
                                                    {{ Form::text( $item->key , $item->value , array( 'class'=>'form-control' , 'placeholder'=>'' ) ) }}
                                                    <span class="help-block">{{$item->description}}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                            <div class="form-group">
                                                <div class="col-sm-12 col-md-12">
                                                    {{Form::hidden('type', 'general')}}
                                                    {{ Form::submit('Save' , array('class'=>'btn btn-large btn-success')) }}
                                                </div>
                                            </div>

                                        {{ Form::close() }}
                                
                                    </div>

                                    <div class="tab-pane" id="file">
                                            
                                        {{ Form::open( array( 'url'=>$objectUrl , 'class'=>'form-horizontal form-groups-bordered' , 'role'=>'form', 'id'=>'item-form' ) ) }}
                                            
                                            @foreach($items['file'] as $item)
                                            <div class="form-group">
                                                <div class="col-sm-12 col-md-12">
                                                    <p>{{ Form::label( $item->key , $item->label , array( 'class'=>'control-label' ) ) }}</p>
                                                    {{ Form::text( $item->key , $item->value , array( 'class'=>'form-control' , 'placeholder'=>'' ) ) }}
                                                    <span class="help-block">{{$item->description}}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                            <div class="form-group">
                                                <div class="col-sm-12 col-md-12">
                                                    {{Form::hidden('type', 'file')}}
                                                    {{ Form::submit('Save' , array('class'=>'btn btn-large btn-success')) }}
                                                </div>
                                            </div>

                                        {{ Form::close() }}
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    @parent
@stop
