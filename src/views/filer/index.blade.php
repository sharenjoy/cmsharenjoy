@extends('cmsharenjoy::layouts.interface')

@section('title')
{{trans('cmsharenjoy::admin.manage')}}{{trans("cmsharenjoy::app.$appName")}}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h2>{{trans("cmsharenjoy::app.$appName")}}</h2>
                    </div>
                    <div class="panel-options">
                        <!-- <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> -->
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <!-- <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a> -->
                    </div>
                </div>

                <div class="panel-body">
                    <button type="button" id="new-folder" class="btn btn-blue btn-lg" onclick="jQuery('#modal-new-folder').modal('show', {backdrop: 'static'});">{{trans('cmsharenjoy::files.new_folder')}}</button>
                    <button type="button" id="new-file" class="btn btn-blue btn-lg" onclick="jQuery('#modal-create-file').modal('show', {backdrop: 'static'});">{{trans('cmsharenjoy::files.role_upload')}}</button>
                    <button type="button" id="file-detail" class="btn btn-success btn-lg" style="display:none">{{trans('cmsharenjoy::files.role_edit_file')}}</button>
                    <button type="button" id="delete-file" class="btn btn-danger btn-lg" style="display:none">{{trans('cmsharenjoy::files.role_delete_file')}}</button>
                </div>

            </div>
            
            @include('cmsharenjoy::partials.messaging')
            
            @include('cmsharenjoy::filer.manage')

        </div>
    </div>

@stop

@section('modal')

<!-- Modal 6 (Long Modal)-->
<div class="modal fade" id="modal-new-folder">
    <form action="{{$objectUrl.'/newfolder'}}" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('cmsharenjoy::files.role_create_folder')}}</h4>
                </div>
                
                <div class="modal-body">
                
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                {{Form::label('name', 'Name', array('class'=>'control-label'))}}
                                {{Form::text('name', '', array('placeholder'=>'Name', 'class'=>'form-control', 'id'=>'name'))}}
                            </div>  
                            
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{Form::button('Create', array('type'=>'submit', 'class'=>"btn btn-info"))}}
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal-create-file">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{trans('cmsharenjoy::files.role_upload')}}</h4>
            </div>
            
            <div class="modal-body">
            
                <div class="row" id="dropzone">
                    <div class="col-md-12">
                        
                        {{Form::open(array('url'=>$objectUrl.'/upload', 'files'=>true, 'class'=>'dropzone', 'id'=>'dropzone_example'))}}
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                            {{Form::hidden('parent_id', $parentId)}}

                        {{Form::close()}}
                        
                    </div>
                </div>

                <h4 class="text-danger">{{trans('cmsharenjoy::files.uploader')}}</h4>
                
            </div>

            <div id="dze_info" class="hidden">
    
                <br />
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">Dropzone Uploaded Files Info</div>
                    </div>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="40%">File name</th>
                                <th width="15%">Size</th>
                                <th width="15%">Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-file-detail">
    <div class="modal-dialog">
        <form action="{{$objectUrl.'/updatefile'}}" method="POST">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{trans('cmsharenjoy::files.role_edit_file')}}</h4>
                </div>
                
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('name', 'Name', array('class'=>'control-label'))}}
                                {{Form::text('name', '', array('placeholder'=>'Name', 'class'=>'form-control', 'id'=>'name'))}}
                            </div>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('alt_attribute', 'Alt', array('class'=>'control-label'))}}
                                {{Form::text('alt_attribute', '', array('placeholder'=>'Alt', 'class'=>'form-control', 'id'=>'alt_attribute'))}}
                            </div>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('description', 'Description', array('class'=>'control-label'))}}
                                {{Form::textarea('description', '', array('placeholder'=>'Description', 'class'=>'form-control', 'id'=>'description'))}}
                            </div>  
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="file_id" name="id" value="">
                    <input type="hidden" id="folder_id" name="folder_id" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    {{Form::button('Update', array('type'=>'submit', 'class'=>"btn btn-info"))}}
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-delete-file">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{trans('cmsharenjoy::files.role_delete_file')}}</h4>
            </div>
            
            <div class="modal-body">
                {{trans('cmsharenjoy::files.role_delete_file')}}
                <span id="modal-file-name"></span>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info delete-button">Delete</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('main-scripts')
    @parent
    sharenjoy.file = {};
    sharenjoy.file.parent_id = "{{$parentId}}";
    sharenjoy.file.upload_max_filesize = {{$uploadMaxFilesize}};
@stop

@section('scripts')
    @parent
    <link rel="stylesheet" href="{{asset('packages/sharenjoy/cmsharenjoy/css/fileupload/files.css')}}">
    <link rel="stylesheet" href="{{asset('packages/sharenjoy/cmsharenjoy/js/dropzone/dropzone.css')}}">
    <script src="{{asset('packages/sharenjoy/cmsharenjoy/js/dropzone/dropzone.min.js')}}"></script>

    <script>
        $(function() {

            var $new_folder_button  = $('#new-folder');
            var $new_file_button    = $('#new-file');
            var $delete_file_button = $('#delete-file');
            var $file_detail_button = $('#file-detail');
            var $modal_delete_file  = $('#modal-delete-file');
            var $modal_file_detail  = $('#modal-file-detail');
            var unselected_blank    = ['UL', 'TD', 'TH', 'DIV'];
            var append_files = [];

            $('.dd-list li[data-id="{{$parentId}}"]').find('.dd-handle').addClass('selected');
            $('.dd-list li[data-id="{{$parentId}}"] .dd-handle').find('i').attr('class', 'fa fa-folder-open');

            $('#modal-new-folder').on('shown.bs.modal', function (e) {
                $(this).find('input[type="text"]').val('');
            });

            $('#modal-create-file').on('hidden.bs.modal', function (e) {
                for (var i = 0; i < append_files.length; i++) {
                    $('.folders-center').append(append_files[i]);
                }
                append_files = [];
            });

            $new_folder_button.click(function(){
                hide_something();
            });

            $new_file_button.click(function(){
                hide_something();
            });

            var show_something = function(){
                // The file was clicked and show delete button
                if($delete_file_button.css('display') == 'none'){
                    $delete_file_button.fadeIn();
                }

                // show file detail button
                if($file_detail_button.css('display') == 'none'){
                    $file_detail_button.fadeIn();
                }
            }

            var hide_something = function(){
                $('#file-point b').text('');
                $delete_file_button.hide();
                $file_detail_button.hide();
                $('.folders-center li').removeClass('selected');
            }

            $('.folders-center').on('click', 'li', function (e) {
                e.preventDefault();

                // console.log(e.target.tagName);
                $('.folders-center li').removeClass('selected');
                $(this).toggleClass('selected');

                // show file name
                $('#file-point b').html($(this).find('span').html());

                show_something();
                
            });

            $('.file-manage').on('click', function(e){
                // console.log(e.target.tagName);
                if (jQuery.inArray(e.target.tagName, unselected_blank) !== -1){
                    $(this).find('li').removeClass('selected');
                    hide_something();
                }
            });

            $file_detail_button.on('click', function(){
                var send_data = {};
                var $selected = $('.folders-center li.selected');

                send_data.file_id = $selected.attr('data-id');
                send_data.file_name = $selected.attr('data-name');
                
                $.post(sharenjoy.APPURL + "/find", send_data, function(data, status) {
                    if (data.status == true) {
                        $modal_file_detail.find('#file_id').val(data.data.id);
                        $modal_file_detail.find('#folder_id').val(data.data.folder_id);
                        $modal_file_detail.find('#name').val(data.data.name);
                        $modal_file_detail.find('#alt_attribute').val(data.data.alt_attribute);
                        $modal_file_detail.find('#description').val(data.data.description);

                        $modal_file_detail.modal('show', {backdrop: 'static'});
                    }
                });

            });

            $delete_file_button.on('click', function(){
                $modal_delete_file.find('#modal-file-name').text($('.folders-center li.selected span').html());
                $modal_delete_file.modal('show', {backdrop: 'static'});
            });

            $modal_delete_file.on('click', '.delete-button', function(){
                var send_data = {};
                var $selected = $('.folders-center li.selected');

                send_data.file_id = $selected.attr('data-id');
                send_data.file_name = $selected.attr('data-name');

                $modal_delete_file.modal('hide');

                $.post(sharenjoy.APPURL + "/deletefile", send_data, function(data, status) {
                    if (data.status == true) {
                        $selected.remove();
                        hide_something();
                    }
                });
            });

            // myDropzone is the configuration for the element that has an id attribute
            // with the value my-dropzone (or myDropzone)
            var myDropzone = new Dropzone("#dropzone .dropzone", {
                maxFilesize: sharenjoy.file.upload_max_filesize,
                headers: { 'X-CSRF-Token': sharenjoy.csrf_token },
                init: function () {
                    this.on("addedfile", function (file) {
                        // console.log(file);
                    });

                    this.on("success", function (file, response) {
                        if (response.status == true) {
                            append_files.push(response.data.append);
                        }
                    });
                }
            });

            if($("#dropzone_example").length)
            {
                var dze_info = $("#dze_info"),
                    status = {uploaded: 0, errors: 0};
                
                var $f = $('<tr><td class="name"></td><td class="size"></td><td class="type"></td><td class="status"></td></tr>');

                myDropzone.on("success", function(file) {
                    
                    var _$f = $f.clone();
                    
                    dze_info.removeClass('hidden');
                    
                    _$f.addClass('success');
                    
                    _$f.find('.name').html(file.name);
                    _$f.find('.size').html(parseInt(file.size / 1024, 10) + ' KB');
                    _$f.find('.type').html(file.type);
                    _$f.find('.status').html('Uploaded <i class="entypo-check"></i>');
                    
                    dze_info.find('tbody').append( _$f );
                    
                    status.uploaded++;
                    
                    dze_info.find('tfoot td').html('<span class="label label-success">' + status.uploaded + ' uploaded</span> <span class="label label-danger">' + status.errors + ' not uploaded</span>');
                    
                })
                .on('error', function(file)
                {
                    var _$f = $f.clone();
                    
                    dze_info.removeClass('hidden');
                    
                    _$f.addClass('danger');
                    
                    _$f.find('.name').html(file.name);
                    _$f.find('.size').html(parseInt(file.size / 1024, 10) + ' KB');
                    _$f.find('.type').html(file.type);
                    _$f.find('.status').html('Uploaded <i class="entypo-cancel"></i>');
                    
                    dze_info.find('tbody').append( _$f );
                    
                    status.errors++;
                    
                    dze_info.find('tfoot td').html('<span class="label label-success">' + status.uploaded + ' uploaded</span> <span class="label label-danger">' + status.errors + ' not uploaded</span>');
                });
            }

            // Return a helper with preserved width of cells
            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };
            $( "#sortable ul").sortable({
                helper: fixHelper,
                update:function(e, ui){

                    var id_value = [];
                    var sort_value = [];
                    var send_result = {};

                    $(this).find('li').each(function() {
                        id_value.push($(this).attr('data-id'));
                    });

                    $('.file-context form input').each(function() {
                        sort_value.push($(this).val());
                    });

                    send_result = {
                        'sort_value': sort_value,
                        'id_value': id_value
                    };

                    // console.log(send_result);

                    $.post(sharenjoy.APPURL + "/order", send_result, function(data, status) {
                        // console.log(data);
                    });
                }
            });
            $( "#sortable ul" ).disableSelection();

        });
    </script>
@stop
