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

                        @if(isset($fieldsForm) AND ! is_null($fieldsForm))
                            {{$fieldsForm}}
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

@section('modal')
    <!-- Filemanager modal -->
    @if(Session::get('onController') != 'filer')
      
      <div class="modal fade custom-width" id="modal-filemanager">
        <div class="modal-dialog" style="width:800px">
          <div class="modal-content">
            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">{{pick_trans('app.menu.file')}}</h4>
            </div>
            
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <iframe src="{{url($urlSegment.'/filer/filemanager')}}" id="iframe-modal-filemanager" width="100%" height="560" frameborder="0" scrolling="no"></iframe>
                </div>
              </div>
            </div>
            
            <div class="modal-footer">
              <input type="hidden" name="pick_field_name" id="pick_field_name" value="">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{pick_trans('buttons.close')}}</button>
            </div>
          </div>
        </div>
      </div>

      @if(isset($albumId))
        <div class="modal fade custom-width" id="modal-file-album">
          <div class="modal-dialog" style="width:800px">
            <div class="modal-content">
              
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{pick_trans('app.menu.file')}}</h4>
              </div>
              
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <iframe src="{{url($urlSegment.'/filer/filealbum/'.$albumId)}}" id="iframe-modal-file-album" width="100%" height="560" frameborder="0" scrolling="no"></iframe>
                  </div>
                </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{pick_trans('buttons.close')}}</button>
              </div>
            </div>
          </div>
        </div>
      @endif

    @endif
    <!-- Filemanager modal Ends -->
@stop

@section('scripts')
    @parent
@stop
