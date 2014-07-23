@if(Session::get('onController') != 'filer')
    <!-- Filemanager modal -->
    <div class="modal fade" id="modal-filemanager" tabindex="-1" role="dialog">
        <div class="modal-dialog" style="width:800px">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('cmsharenjoy::app.menu.file')}}</h4>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('cmsharenjoy::buttons.close')}}</button>
                </div>
            </div>
        </div>
    </div>
    @if(isset($albumId))
        <div class="modal fade" id="modal-file-album" tabindex="-1" role="dialog">
            <div class="modal-dialog" style="width:800px">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{{trans('cmsharenjoy::app.menu.file')}}</h4>
                    </div>
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <iframe src="{{url($urlSegment.'/filer/filealbum/'.$albumId)}}" id="iframe-modal-file-album" width="100%" height="560" frameborder="0" scrolling="no"></iframe>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('cmsharenjoy::buttons.close')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Filemanager modal Ends -->
@endif

<!-- Common model Starts -->
<div class="modal fade" id="common-modal" tabindex="-1" role="dialog">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('cmsharenjoy::buttons.cancel')}}</button>
                    {{Form::button(trans('cmsharenjoy::buttons.confirm'), ['type'=>'submit', 'class'=>"btn btn-info"])}}
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Common model Ends -->
