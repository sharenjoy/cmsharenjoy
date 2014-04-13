<div class="dataTables_wrapper">

    <div class="row">
        <div class="col-xs-8 col-left">
            <div class="dataTables_length">
                <h2>{{ Lang::get('cmsharenjoy::admin.manage') }} {{ Lang::get("cmsharenjoy::app.$appName") }}</h2>
            </div>
        </div>
        <div class="col-xs-4 col-right">
            <div class="dataTables_filter" id="table-1_filter">
                <!-- <label>{{ Lang::get('cmsharenjoy::admin.search') }}: <input type="text" aria-controls="table-1"></label> -->
            </div>
        </div>
    </div>

     
    <table class="table table-bordered table-hover table-responsive" @if($sortable == true)id="sortable"@endif>
        
        <thead>
            <tr>
                <th>&nbsp;</th>
                @foreach($listConfig as $item)
                    <th align="center">{{ Lang::get("cmsharenjoy::app.$item[name]") }}</th>
                @endforeach
            </tr>
        </thead>
        
        <tbody>
            @foreach($items as $item)
                <tr id="{{ $item->id }}">
                    <td width="20%">
                        <div class="text-center">
                            <a href="{{ $updateUrl.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('cmsharenjoy::admin.edit') }}" data-original-title="{{ Lang::get('cmsharenjoy::admin.edit') }}">
                                <i class="fa fa-pencil-square-o fa-lg"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a href="{{ $deleteUrl.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('cmsharenjoy::admin.delete') }}" data-original-title="{{ Lang::get('cmsharenjoy::admin.delete') }}">
                                <i class="fa fa-trash-o fa-lg"></i>
                            </a>
                        </div>
                    </td>
                    @foreach($listConfig as $key => $value)
                        <td align="{{ $value['align'] }}" width="{{ $value['width'] }}">{{ $item->$key }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        
        @if($items->count() < $paginationCount)
             <tfoot>
             @for($i=0; $i < $paginationCount - $items->count(); $i++)
                <tr>
                    <td>&nbsp;</td>
                    @foreach($listConfig as $item)
                        <td>&nbsp;</td>
                    @endforeach
                </tr>
             @endfor
             </tfoot>
        @endif
        
    </table>
    
    <div class="row">
        @include('cmsharenjoy::partials.pagination')
    </div>
    
    @if($sortable == true)
        <form id="sortform" name="sortform">
            @foreach($items as $item)
                <input type="hidden" value="{{$item->sort}}" />
            @endforeach
        </form>
    @endif

</div>