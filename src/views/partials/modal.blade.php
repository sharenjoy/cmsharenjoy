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

<!-- Common model Starts -->
<div class="modal fade" id="common-modal">
  <form action="" method="POST">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="common-modal-title"></h4>
        </div>
        
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="common-modal-body"></div>
          </div>
        </div>
        
        <div class="modal-footer">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{pick_trans('buttons.cancel')}}</button>
          {{Form::button(pick_trans('buttons.confirm'), ['type'=>'submit', 'class'=>"btn btn-info"])}}
        </div>
      </div>
    </div>
  </form>
</div>
<!-- Common model Ends -->