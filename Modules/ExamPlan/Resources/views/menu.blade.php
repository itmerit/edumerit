
@if (userPermission(3100) && menuStatus(3100))
    <li data-position="{{ menuPosition(3100) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-test"></span>
            </div>
            <div class="nav_title">
                <span>@lang('examplan::exp.exam_plan')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuExamSeat">
            @if (userPermission(3102) && menuStatus(3102))
                <li data-position="{{ menuPosition(3102) }}">
                    <a href="{{ route('examplan.admitcard.index') }}"> @lang('examplan::exp.admit_card')</a>
                </li>
            @endif
            @if (userPermission(3101) && menuStatus(3101))
                <li data-position="{{ menuPosition(3101) }}">
                    <a href="{{ route('examplan.admitcard.setting') }}"> @lang('examplan::exp.admit_card_setting')</a>
                </li>
            @endif
            @if (userPermission(3106) && menuStatus(3106))
                <li data-position="{{ menuPosition(3106) }}">
                    <a href="{{ route('examplan.seatplan.index') }}"> @lang('examplan::exp.seat_plan')</a>
                </li>
            @endif
            @if (userPermission(3105) && menuStatus(3105))
                <li data-position="{{ menuPosition(3105) }}">
                    <a href="{{ route('examplan.seatplan.setting') }}"> @lang('examplan::exp.seat_plan_setting')</a>
                </li>
            @endif
        </ul>
    </li>
@endif