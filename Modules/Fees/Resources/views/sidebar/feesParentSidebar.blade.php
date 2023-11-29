@if(userPermission('parent-fees') && menuStatus(1157))
    <li data-position="{{menuPosition(1157)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">            
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
                @lang('fees::feesModule.fees')
            </div>
        </a>
        <ul class="list-unstyled">
            @foreach($childrens as $children)
                <li>
                    <a href="{{route('fees.student-fees-list', [$children->id])}}">{{$children->full_name}}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif