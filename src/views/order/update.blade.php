@extends('cmsharenjoy::layouts.interface-update')

@section('title')
{{trans('cmsharenjoy::admin.edit')}}{{trans('cmsharenjoy::app.'.$appName)}}
@stop

@section('form-items')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>{{trans('cmsharenjoy::admin.edit')}}</h3>
                    </div>
                    
                    <!-- <div class="panel-options">
                        <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                    </div> -->
                </div>

                <div class="panel-heading">
                    
                    <div class="form-group">
                        <div class="col-sm-5">
                            <br>   
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>-</th>
                                <th>食材</th>
                                <th>單價</th>
                                <th>數量</th>
                              </tr>
                            </thead>
                            <tbody>

                              @foreach($orderDetail as $value)
                              <tr data-id="{{$value->id}}">
                                <td>
                                  <img class="img-responsive" width="80" src="{{asset('uploads/'.$value->img)}}">
                                </td>
                                <td>{{$value->name}} {{$value->name_jp}}
                                    <p class="text-muted">{{$value->description}}</p></td>
                                <td width="15%" align="right">${{number_format($value->price)}}</td>
                                <td width="15%" align="center"> {{$value->quantity}} 份 </td>
                              </tr>
                              @endforeach

                            </tbody>
                          </table>

                        </div>
                    </div>

                </div>
                
                
                <div class="panel-body">


                    
                    {{ Form::open( array( 'url'=>$updateUrl.$item->id , 'class'=>'form-horizontal form-groups-bordered' , 'role'=>'form', 'id'=>'item-form' ) ) }}
                    
                        @if(isset($fieldsForm))
                            @foreach($fieldsForm as $key => $value)
                                @if(isset($value['field']))
                                    {{$value['field']}}
                                @endif
                            @endforeach
                        @endif
                        
                        <div class="form-group">
                            <div class="col-sm-8">
                                {{ Form::reset(trans('cmsharenjoy::buttons.reset') , array('class'=>'btn btn-large btn-default')) }}
                                &nbsp;
                                {{ Form::submit(trans('cmsharenjoy::buttons.update') , array('class'=>'btn btn-large btn-success')) }}
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
