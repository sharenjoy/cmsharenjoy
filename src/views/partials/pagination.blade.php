<div class="col-xs-4 col-sm-4 col-left">
    <div class="dataTables_info">
        {{ Form::open( array('method'=>'get', 'role'=>'form', 'id'=>'pagination_count_form') ) }}
            {{ Form::select(
                'pnum', 
                array(
                    '10' => '10',
                    '15' => '15',
                    '20' => '20',
                    '30' => '30',
                    '50' => '50'), 
                $pagination_count,
                array('class'=>'form-control pagination_count', 'id'=>'pagination_count')
            )}}
        &nbsp;&nbsp;
        {{ Lang::get('cmsharenjoy::admin.pagination_desc', array('from'=>$items->getFrom(), 'to'=>$items->getTo(), 'total'=>$items->getTotal())) }}
    </div>
</div>
<div class="col-xs-8 col-sm-8 col-right">
    <div class="dataTables_paginate paging_bootstrap">
        {{ $items->appends(array('pnum'=>$pagination_count))->links() }}
    </div>
</div>