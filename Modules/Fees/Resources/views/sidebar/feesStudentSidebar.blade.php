@if(auth()->user()->student)
    @if(userPermission("fees.student-fees-list") && menuStatus(1156))
        <li data-position="{{menuPosition(1156)}}" class="sortable_li">
            <a href="{{route('fees.student-fees-list',[auth()->user()->student->id])}}">
                <div class="nav_icon_small">
                    <span class="flaticon-wallet"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('fees.fees')</span>
                   
                </div>
            </a>
        </li>
    @endif
@endif