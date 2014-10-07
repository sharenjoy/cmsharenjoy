<div class="dataTables_wrapper">

    <div class="row">
        <div class="col-xs-8 col-left">
            <div class="dataTables_length">
                <h3>{{pick_trans('app.manage')}}</h3>
            </div>
        </div>
        <div class="col-xs-4 col-right">
            <div class="dataTables_filter" id="table-1_filter">
                <!-- <label>{{pick_trans('app.search')}}: <input type="text" aria-controls="table-1"></label> -->
            </div>
        </div>
    </div>

     
    <table class="table table-bordered table-hover table-responsive" @if($sortable == true)id="sortable"@endif>
        
        <thead>
            <tr>
                <th>&nbsp;</th>
                @foreach($listConfig as $item)
                    <th align="center">{{pick_trans("app.$item[name]")}}</th>
                @endforeach
            </tr>
        </thead>
        
        <tbody>
            @foreach($items as $item)
                <tr id="{{$item->id}}">
                    <td width="20%">
                        <div class="list-fun-box">
                            @include('cmsharenjoy::partials.function-list')
                        </div>
                    </td>
                    @foreach($listConfig as $key => $value)
                        @if(isset($value['type']) AND $value['type'] == 'image')
                            <td align="{{$value['align']}}" width="{{$value['width']}}">
                                @if($item->$key != '')
                                <img src="{{asset('uploads/thumbs/'.$item->$key)}}" width="75">
                                @else - @endif
                            </td>
                        @elseif(isset($value['type']) AND $value['type'] == 'link')
                            <td align="{{$value['align']}}" width="{{$value['width']}}">
                                @if($item->$key != '')
                                <a href="{{$item->$key}}" target="_blank">{{pick_trans('app.linkto')}}</a>
                                @else - @endif
                            </td>
                        @else
                            <td align="{{$value['align']}}" width="{{$value['width']}}">{{$item->$key}}</td>
                        @endif
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