<ul>

    @if(isset($functionRules['preview']) AND $functionRules['preview'] == true)
        <li>
            <a href="{{ $updateUrl.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('cmsharenjoy::buttons.edit') }}" data-original-title="{{ trans('cmsharenjoy::buttons.edit') }}">
                <i class="fa fa-pencil-square-o fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['update']) AND $functionRules['update'] == true)
        <li>
            <a href="{{ $updateUrl.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('cmsharenjoy::buttons.edit') }}" data-original-title="{{ trans('cmsharenjoy::buttons.edit') }}">
                <i class="fa fa-pencil-square-o fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['delete']) AND $functionRules['delete'] == true)
        <li>
            <a href="{{ $deleteUrl.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('cmsharenjoy::buttons.delete') }}" data-original-title="{{ trans('cmsharenjoy::buttons.delete') }}">
                <i class="fa fa-trash-o fa-lg"></i>
            </a>
        </li>
    @endif

    @if(isset($functionRules['resetpassword']) AND $functionRules['resetpassword'] == true)
        <li>
            <a href="{{ $objectUrl.'/resetpassword/'.$item->id }}" class="tooltip-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('cmsharenjoy::buttons.resetpassword') }}" data-original-title="{{ trans('cmsharenjoy::buttons.resetpassword') }}">
                <i class="fa fa-shield fa-lg"></i>
            </a>
        </li>
    @endif

</ul>