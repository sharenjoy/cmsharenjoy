<div class="panel panel-primary" data-collapsed="0">

    <!-- panel head -->
    <div class="panel-heading">
        <div class="panel-title">
            <h2>{{ Lang::get("cmsharenjoy::app.$appName") }}</h2>
        </div>
    </div>
    
    <!-- panel body -->
    <div class="panel-body">
        
        <a href="{{ $objectUrl }}" class="btn btn-default btn-icon icon-left">
            <i class="fa fa-bars"></i>{{ Lang::get('cmsharenjoy::admin.show_list') }}
        </a>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ $newUrl }}" class="btn btn-blue btn-icon icon-left">
            <i class="fa fa-plus"></i>{{ Lang::get('cmsharenjoy::admin.new_item') }}
        </a>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ $objectUrl }}/index/sort" class="btn btn-default btn-icon icon-left">
            <i class="fa fa-sort"></i>{{ Lang::get('cmsharenjoy::admin.sort_list') }}
        </a>
        
    </div>
</div>
