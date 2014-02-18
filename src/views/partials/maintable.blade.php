<div class="col-md-12">
    <div class="dataTables_wrapper">

        <div class="row">
            <div class="col-xs-6 col-left">
                <div class="dataTables_length">
                    <h2>{{ Lang::get('cmsharenjoy::admin.manage') }} {{ Lang::get("cmsharenjoy::app.$application_name") }}</h2>
                </div>
            </div>
            <div class="col-xs-6 col-right">
                <div class="dataTables_filter" id="table-1_filter">
                    <label>{{ Lang::get('cmsharenjoy::admin.search') }}: <input type="text" aria-controls="table-1"></label>
                </div>
            </div>
        </div>
         
        <table class="table table-bordered table-hover datatable table-responsive" @if($sortable == true)id="sortable"@endif>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    @foreach( $fields_ary as $item )
                        <th align="center">{{ Lang::get("cmsharenjoy::app.$item[name]") }}</th>
                    @endforeach
                </tr>
            </thead>
            
            <tbody>
                @foreach($items as $item)
                    <tr id="{{ $item->id }}">
                        <td width="15%">
                            <div class="text-center">
                                <a href="{{ $edit_url.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('cmsharenjoy::admin.edit') }}" data-original-title="{{ Lang::get('cmsharenjoy::admin.edit') }}">
                                    <i class="fa fa-pencil-square-o fa-lg"></i>
                                </a>
                                &nbsp;&nbsp;
                                <a href="{{ $delete_url.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('cmsharenjoy::admin.delete') }}" data-original-title="{{ Lang::get('cmsharenjoy::admin.delete') }}">
                                    <i class="fa fa-trash-o fa-lg"></i>
                                </a>
                            </div>
                        </td>
                        @foreach( $fields_ary as $key => $value )
                            <td align="{{ $value['align'] }}" width="{{ $value['width'] }}">{{ $item->$key }}</td>
                        @endforeach
                    </tr>
                @endforeach

                @if($items->count() < $pagination_count)
                     @for($i=0; $i < $pagination_count - $items->count(); $i++)
                        <tr>
                            <td>&nbsp;</td>
                            @foreach( $fields_ary as $item )
                                <td>&nbsp;</td>
                            @endforeach
                        </tr>
                     @endfor
                @endif
            </tbody>
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
</div>