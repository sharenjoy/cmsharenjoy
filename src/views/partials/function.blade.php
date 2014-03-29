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
    <div class="panel-body fun-box">

        <div class="pull-left">
            <a href="{{ $objectUrl }}" class="btn btn-default btn-icon icon-left">
                <i class="fa fa-bars"></i>{{ Lang::get('cmsharenjoy::admin.show_list') }}
            </a>
        </div>

        <div class="pull-left">
            <a href="{{ $createUrl }}" class="btn btn-default btn-icon icon-left">
                <i class="fa fa-plus"></i>{{ Lang::get('cmsharenjoy::admin.new_item') }}
            </a>
        </div>
        
        <div class="pull-left">
            <a href="{{ $objectUrl }}/index/sort" class="btn btn-default btn-icon icon-left">
                <i class="fa fa-sort"></i>{{ Lang::get('cmsharenjoy::admin.sort_list') }}
            </a>
        </div>

    </div>

    @if(isset($filterable) && $filterable === true && isset($filterForm))
    <div class="panel-body filter-box">
        {{Form::open(array('url'=>$objectUrl, 'role'=>'form', 'method'=>'GET'))}}
            <div class="row">

                @foreach($filterForm as $key => $value)
                    @if(isset($value['field']))
                        {{$value['field']}}
                    @endif
                @endforeach

                <div class="list-filter col-md-3 col-sm-6">
                    {{Form::hidden('filter', 'true')}}
                    {{Form::label('')}}<br>
                    {{Form::submit(Lang::get('cmsharenjoy::admin.filter'), array('class'=>'btn btn-blue'))}}
                </div>

            </div>
        {{Form::close()}}
    </div>
    @endif

</div>
