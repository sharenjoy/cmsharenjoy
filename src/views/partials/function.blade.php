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
        <a href="{{ $objectUrl }}" class="btn btn-default btn-icon icon-left">
            <i class="fa fa-bars"></i>{{ Lang::get('cmsharenjoy::admin.show_list') }}
        </a>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ $newUrl }}" class="btn btn-default btn-icon icon-left">
            <i class="fa fa-plus"></i>{{ Lang::get('cmsharenjoy::admin.new_item') }}
        </a>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ $objectUrl }}/index/sort" class="btn btn-default btn-icon icon-left">
            <i class="fa fa-sort"></i>{{ Lang::get('cmsharenjoy::admin.sort_list') }}
        </a>
    </div>

    @if($filterable)
    <div class="panel-body filter-box">
        {{Form::open(array('url'=>$objectUrl, 'role'=>'form', 'method'=>'GET'))}}
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    {{Form::label('ststus', 'Status')}}<br>
                    {{Form::select('status',
                    Config::get('cmsharenjoy::option.statusOption'), 
                    Input::get('status'),
                    array('class'=>'form-control'))}}
                </div>
                <div class="col-md-3 col-sm-6">
                    {{Form::label('keyword', 'Keyword')}}<br>
                    {{Form::text('keyword', Input::get("keyword"), array('class'=>'form-control', 'placeholder'=>'Keyword'))}}
                </div>
                <div class="col-md-3 col-sm-6">
                    <i class="entypo-calendar"></i>{{Form::label('dateRange', 'Date Range')}}<br>
                    {{Form::text('dateRange', Input::get("dateRange"),
                    array(
                        'class'=>'form-control daterange daterange-inline add-ranges',
                        'placeholder'=>'Date Range',
                        'data-format'=>'YYYY-MM-DD',
                        'data-start-date'=>date('Y-m-d', time() - 86400),
                        'data-end-date'=>date('Y-m-d', time()),
                        'data-separator'=>' ~ '
                    ))}}
                </div>
                <div class="col-md-3 col-sm-6">
                    {{Form::hidden('filter', 'true')}}
                    {{Form::submit(Lang::get('cmsharenjoy::admin.filter'), array('class'=>'btn btn-blue btn-sm'))}}
                </div>
            </div>
        {{Form::close()}}
    </div>
    @endif

</div>
