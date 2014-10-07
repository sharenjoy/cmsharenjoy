<ul>

    @if(isset($functionRules['preview']) AND $functionRules['preview'] == true)
        <li>
            <a href="{{$updateUrl.$item->id}}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{pick_trans('buttons.edit')}}" data-original-title="{{pick_trans('buttons.edit')}}">
                <i class="fa fa-pencil-square-o fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['update']) AND $functionRules['update'] == true)
        <li>
            <a href="{{$updateUrl.$item->id}}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{pick_trans('buttons.edit')}}" data-original-title="{{pick_trans('buttons.edit')}}">
                <i class="fa fa-pencil-square-o fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['delete']) AND $functionRules['delete'] == true)
        <li>
            <a class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{pick_trans('buttons.delete')}}" data-original-title="{{pick_trans('buttons.delete')}}" onclick="common_modal({type:'delete', id:'{{$item->id}}'});">
                <i class="fa fa-trash-o fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['remindpassword']) AND $functionRules['remindpassword'] == true)
        <li>
            <a href="{{$objectUrl.'/remindpassword/'.$item->id}}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{pick_trans('buttons.remindpassword')}}" data-original-title="{{pick_trans('buttons.remindpassword')}}">
                <i class="fa fa-shield fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['sendmessage']) AND $functionRules['sendmessage'] == true)
        <li>
            <a href="{{$objectUrl.'/sendmessage/'.$item->id}}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{pick_trans('buttons.sendmessage')}}" data-original-title="{{pick_trans('buttons.sendmessage')}}">
                <i class="fa fa-phone fa-lg"></i>
            </a>
        </li>
    @endif

</ul>