@if (userPermission('parent-dashboard') && menuStatus(56))
    <li data-position="{{ menuPosition(56) }}" class="sortable_li">
        <a href="{{ route('parent-dashboard') }}">
            <div class="nav_icon_small">
                <span class="flaticon-resume"></span>
            </div>
            <div class="nav_title">
                <span>@lang('common.dashboard')</span>

            </div>
        </a>
    </li>
@endif
@if (userPermission('my-children') && menuStatus(66))
    <li data-position="{{ menuPosition(66) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
                <span> @lang('common.my_children')</span>

            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentMyChildren">


            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('my_children', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif

@if (moduleStatusCheck('Lms') == true)
    @if (userPermission('lms') && menuStatus(1500))
        <li data-position="{{ menuPosition(1500) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('lms::lms.lms')</span>

                </div>
            </a>
            <ul class="list-unstyled" id="lmsButtonMenu">
                @foreach ($childrens as $children)
                    @if (userPermission(1508) && menuStatus(1508))
                        <li data-position="{{ menuPosition(1508) }}" class="sortable_li">
                            <a href="{{ route('lms.allCourse', [$children->user_id]) }}">@lang('lms::lms.all_course')
                                ({{ $children->full_name }})
                            </a>
                        </li>
                    @endif
                    @if (userPermission(1509) && menuStatus(1509))
                        <li data-position="{{ menuPosition(1509) }}" class="sortable_li">
                            <a href="{{ route('lms.enrolledCourse', [$children->user_id]) }}">{{ $children->full_name }}
                                - @lang('lms::lms.course')</a>
                        </li>
                    @endif
                    @if (userPermission(1510) && menuStatus(1510))
                        <li data-position="{{ menuPosition(1510) }}" class="sortable_li">
                            <a href="{{ route('lms.student.purchaseLog', [$children->user_id]) }}">{{ $children->full_name }}
                                - @lang('lms::lms.purchase_history')</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
@endif


@if (generalSetting()->fees_status == 0)
    @if (userPermission('fees') && menuStatus(71))
        <li data-position="{{ menuPosition(71) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


                <div class="nav_icon_small">
                    <span class="flaticon-wallet"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('fees.fees')</span>

                </div>
            </a>
            <ul class="list-unstyled" id="subMenuParentFees">
                @foreach ($childrens as $children)
                    @if (moduleStatusCheck('FeesCollection') == false)
                        <li>
                            <a href="{{ route('parent_fees', [$children->id]) }}">{{ $children->full_name }}</a>
                        </li>
                    @else
                        <li>
                            <a
                                href="{{ route('feescollection/parent-fee-payment', [$children->id]) }}">{{ $children->full_name }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
@endif

@if (moduleStatusCheck('Wallet') && isMenuAllowToShow('wallet'))
    @includeIf('wallet::menu.sidebar')
@endif

@if (generalSetting()->fees_status == 1 && isMenuAllowToShow('fees'))
    @includeIf('fees::sidebar.feesParentSidebar')
@endif

@if (userPermission('parent_class_routine') && menuStatus(72))
    <li data-position="{{ menuPosition(72) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-calendar-1"></span>
            </div>
            <div class="nav_title">
                <span> @lang('academics.class_routine')</span>

            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentClassRoutine">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_class_routine', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif

@if (userPermission('lesson') && menuStatus(97))
    <li data-position="{{ menuPosition(97) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-calendar-1"></span>
            </div>
            <div class="nav_title">
                <span> @lang('lesson::lesson.lesson')</span>

            </div>
        </a>
        <ul class="list-unstyled" id="subMenuLessonPlan">
            @foreach ($childrens as $children)
                @if (userPermission('lesson-parent-lessonPlan') && menuStatus(98))
                    <li data-position="{{ menuPosition(98) }}">
                        <a class="d-block pre-wrap" href="{{ route('lesson-parent-lessonPlan', [$children->id]) }}">
                            {{ $children->full_name }}-@lang('lesson::lesson.lesson_plan')</a>
                    </li>
                @endif
                @if (userPermission('lesson-parent-lessonPlan-overview') && menuStatus(99))
                    <li data-position="{{ menuPosition(99) }}">
                        <a class="d-block pre-wrap"
                            href="{{ route('lesson-parent-lessonPlan-overview', [$children->id]) }}">
                            {{ $children->full_name }}-@lang('lesson::lesson.lesson_plan_overview')</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
@endif
@if (userPermission('parent_homework') && menuStatus(73))
    <li data-position="{{ menuPosition(73) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">

            <div class="nav_icon_small">
                <span class="flaticon-book"></span>
            </div>
            <div class="nav_title">
                <span>@lang('homework.home_work')</span>

            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentHomework">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_homework', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
@if (userPermission('parent_attendance') && menuStatus(75))
    <li data-position="{{ menuPosition(75) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">

            <div class="nav_icon_small">
                <span class="flaticon-authentication"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('student.attendance')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentAttendance">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_attendance', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
@if (userPermission('exam') && menuStatus(76))
    <li data-position="{{ menuPosition(76) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


            <div class="nav_icon_small">
                <span class="flaticon-test"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('exam.exam')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentExamination">
            @foreach ($childrens as $children)
                @if (userPermission('parent_examination') && menuStatus(77))
                    <li data-position="{{ menuPosition(77) }}">
                        <a href="{{ route('parent_examination', [$children->id]) }}">{{ $children->full_name }}</a>
                    </li>
                @endif
                @if (userPermission('parent_exam_schedule') && menuStatus(78))
                    <li data-position="{{ menuPosition(78) }}">
                        <a href="{{ route('parent_exam_schedule', [$children->id]) }}">@lang('exam.exam_schedule')</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
@endif



@if (moduleStatusCheck('ExamPlan') == true)
    @if (userPermission('admit_card') && menuStatus(2503))
        <li data-position="{{ menuPosition(2503) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('examplan::exp.admit_card')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="subMenuParentMyChildren">
                @foreach ($childrens as $children)
                    <li>
                        <a
                            href="{{ route('examplan.admitCardParent', [$children->id]) }}">{{ $children->full_name }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
@endif



@if (moduleStatusCheck('OnlineExam') == false)

    @if (userPermission('online_exam') && menuStatus(2016))
        <li data-position="{{ menuPosition(2016) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-test"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('exam.online_exam')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="subMenuOnlineExam">
                @if (moduleStatusCheck('OnlineExam') == false)
                    @foreach ($childrens as $children)
                        @if (userPermission('parent_online_examination') && menuStatus(2018))
                            <li data-position="{{ menuPosition(2018) }}">
                                <a class="d-block pre-wrap"
                                    href="{{ route('parent_online_examination', [$children->id]) }}">@lang('exam.online_exam')
                                    - {{ $children->full_name }}</a>
                            </li>
                        @endif
                        @if (userPermission('parent_online_examination_result') && menuStatus(2017))
                            <li data-position="{{ menuPosition(2017) }}">
                                <a class="d-block pre-wrap"
                                    href="{{ route('parent_online_examination_result', [$children->id]) }}">@lang('exam.online_exam_result')
                                    - {{ $children->full_name }}</a>
                            </li>
                        @endif
                    @endforeach

                @endif
            </ul>
        </li>
    @endif
@endif
@if (moduleStatusCheck('OnlineExam') == true)

    @if (userPermission('online_exam') && menuStatus(2101))
        <li data-position="{{ menuPosition(79) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-test"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('onlineexam::onlineExam.online_exam')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="subMenuOnlineExamModule">


                @foreach ($childrens as $children)
                    @if (userPermission('om_parent_online_examination') && menuStatus(2001))
                        <li data-position="{{ menuPosition(2001) }}">
                            <a href="{{ route('om_parent_online_examination', $children->id) }}"> @lang('onlineexam::onlineExam.online_exam')
                                {{ count($childrens) > 1 ? '-' . $children->full_name : '' }} </a>
                        </li>
                    @endif
                    @if (userPermission('om_parent_online_examination_result') && menuStatus(2002))
                        <li data-position="{{ menuPosition(2002) }}">
                            <a href="{{ route('om_parent_online_examination_result', $children->id) }}">
                                @lang('onlineexam::onlineExam.online_exam_result') {{ count($childrens) > 1 ? '-' . $children->full_name : '' }} </a>
                        </li>
                    @endif
                    @if (userPermission('parent_pdf_exam') && menuStatus(2103))
                        <li data-position="{{ menuPosition(2103) }}">
                            <a href="{{ route('parent_pdf_exam', $children->id) }}"> @lang('onlineexam::onlineExam.pdf_exam')
                                {{ count($childrens) > 1 ? '-' . $children->full_name : '' }} </a>
                        </li>
                    @endif
                    @if (userPermission('parent_view_pdf_result') && menuStatus(2104))
                        <li data-position="{{ menuPosition(2104) }}">
                            <a href="{{ route('parent_view_pdf_result', $children->id) }}"> @lang('onlineexam::onlineExam.pdf_exam_result')
                                {{ count($childrens) > 1 ? '-' . $children->full_name : '' }} </a>
                        </li>
                    @endif

                    <hr>
                @endforeach


            </ul>
        </li>
    @endif
@endif


@if (userPermission('parent_leave') && menuStatus(80))
    <li data-position="{{ menuPosition(80) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-test"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('leave.leave')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentLeave">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_leave', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
            @if (userPermission('parent-apply-leave') && menuStatus(81))
                <li data-position="{{ menuPosition(81) }}">
                    <a href="{{ route('parent-apply-leave') }}">@lang('leave.apply_leave')</a>
                </li>
            @endif
            @if (userPermission('parent-pending-leave') && menuStatus(82))
                <li data-position="{{ menuPosition(82) }}">
                    <a href="{{ route('parent-pending-leave') }}">@lang('leave.pending_leave_request')</a>
                </li>
            @endif

        </ul>
    </li>
@endif
@if (userPermission('parent_noticeboard') && menuStatus(85))
    <li data-position="{{ menuPosition(85) }}" class="sortable_li">
        <a href="{{ route('parent_noticeboard') }}">
            <div class="nav_icon_small">
                <span class="flaticon-poster"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('communicate.notice_board')</span>
            </div>
        </a>
    </li>
@endif
@if (userPermission('parent_subjects') && menuStatus(86))
    <li data-position="{{ menuPosition(86) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


            <div class="nav_icon_small">
                <span class="flaticon-reading-1"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('common.subjects')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentSubject">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_subjects', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
@if (userPermission('parent_teacher_list') && menuStatus(87))
    <li data-position="{{ menuPosition(87) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-professor"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('common.teacher_list')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentTeacher">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_teacher_list', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
@if (userPermission('p_library') && menuStatus(88))
    <li data-position="{{ menuPosition(88) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-book-1"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('library.library')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuStudentLibrary">
            @if (userPermission('parent_library') && menuStatus(89))
                <li data-position="{{ menuPosition(89) }}">
                    <a href="{{ route('parent_library') }}"> @lang('library.book_list')</a>
                </li>
            @endif
            @if (userPermission('parent_book_issue') && menuStatus(90))
                <li data-position="{{ menuPosition(90) }}">
                    <a href="{{ route('parent_book_issue') }}">@lang('library.book_issue')</a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if (userPermission('parent_transport') && menuStatus(91))
    <li data-position="{{ menuPosition(91) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


            <div class="nav_icon_small">
                <span class="flaticon-bus"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('transport.transport')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentTransport">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_transport', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
@if (userPermission('parent_dormitory_list') && menuStatus(92))
    <li data-position="{{ menuPosition(92) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


            <div class="nav_icon_small">
                <span class="flaticon-hotel"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('dormitory.dormitory_list')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuParentDormitory">
            @foreach ($childrens as $children)
                <li>
                    <a href="{{ route('parent_dormitory_list', [$children->id]) }}">{{ $children->full_name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endif

<!-- chat module sidebar -->

@if (userPermission('chat') && menuStatus(910))
    <li data-position="{{ menuPosition(900) }}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


            <div class="nav_icon_small">
                <span class="flaticon-test"></span>
            </div>
            <div class="nav_title">
                <span>
                    @lang('chat::chat.chat')</span>
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuChat">
            @if (userPermission('chat.index') && menuStatus(911))
                <li data-position="{{ menuPosition(901) }}">
                    <a href="{{ route('chat.index') }}">@lang('chat::chat.chat_box')</a>
                </li>
            @endif

            @if (userPermission('chat.invitation') && menuStatus(913))
                <li data-position="{{ menuPosition(903) }}">
                    <a href="{{ route('chat.invitation') }}">@lang('chat::chat.invitation')</a>
                </li>
            @endif

            @if (userPermission('chat.blocked.users') && menuStatus(914))
                <li data-position="{{ menuPosition(904) }}">
                    <a href="{{ route('chat.blocked.users') }}">@lang('chat::chat.blocked_user')</a>
                </li>
            @endif


        </ul>
    </li>
@endif

<!-- BBB Menu  -->
@if (moduleStatusCheck('BBB') == true)
    @if (userPermission('bbb') && menuStatus(105))
        <li data-position="{{ menuPosition(105) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">


                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('bbb::bbb.bbb')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="bigBlueButtonMenu">
                @foreach ($childrens as $children)
                    @if (userPermission('bbb.parent.virtual-class') && menuStatus(106))
                        <li data-position="{{ menuPosition(106) }}">
                            <a href="{{ route('bbb.parent.virtual-class', [$children->id]) }}">
                                @if (count($childrens) > 1)
                                    {{ $children->full_name }}
                                @endif @lang('common.virtual_class')
                            </a>
                        </li>
                    @endif
                @endforeach
                @if (userPermission('bbb.meetings') && menuStatus(107))
                    <li data-position="{{ menuPosition(107) }}">
                        <a href="{{ route('bbb.meetings') }}">@lang('common.virtual_meeting')</a>
                    </li>
                @endif
                @foreach ($childrens as $children)
                    @if (userPermission('bbb.parent.class.recording.list') && menuStatus(115))
                        <li data-position="{{ menuPosition(115) }}">
                            <a href="{{ route('bbb.parent.class.recording.list', $children->id) }}">
                                @if (count($childrens) > 1)
                                    {{ $children->full_name }}
                                @endif @lang('common.class_record_list')
                            </a>
                        </li>
                    @endif
                @endforeach

                @if (userPermission('bbb.parent.meeting.recording.list') && menuStatus(116))
                    <li data-position="{{ menuPosition(116) }}">
                        <a href="{{ route('bbb.parent.meeting.recording.list') }}"> @lang('bbb::bbb.meeting_record_list')</a>
                    </li>
                @endif

            </ul>
        </li>

    @endif

@endif
<!-- BBB  Menu end -->
<!-- Jitsi Menu  -->
@if (moduleStatusCheck('Jitsi') == true)
    @if (userPermission('jitsi') && menuStatus(108))
        <li data-position="{{ menuPosition(108) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('jitsi::jitsi.jitsi')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="subMenuJisti">
                @foreach ($childrens as $children)
                    @if (userPermission('jitsi.parent.virtual-class') && menuStatus(109))
                        <li data-position="{{ menuPosition(109) }}">
                            <a href="{{ route('jitsi.parent.virtual-class', [$children->id]) }}">
                                @if (count($childrens) > 1)
                                    {{ $children->full_name }}
                                @endif @lang('common.virtual_class')
                            </a>
                        </li>
                    @endif
                @endforeach
                @if (userPermission('jitsi.meetings') && menuStatus(110))
                    <li data-position="{{ menuPosition(110) }}">
                        <a href="{{ route('jitsi.meetings') }}">@lang('common.virtual_meeting')</a>
                    </li>
                @endif

            </ul>
        </li>

    @endif
@endif
<!-- jitsi Menu end -->

<!-- Zomm Menu  start -->
@if (moduleStatusCheck('Zoom') == true)

    @if (userPermission('zoom') && menuStatus(100))
        <li data-position="{{ menuPosition(100) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('zoom::zoom.zoom')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="zoomMenu">

                @foreach ($childrens as $children)
                    @if (userPermission('zoom.parent.virtual-class') && menuStatus(101))
                        <li data-position="{{ menuPosition(101) }}">
                            <a href="{{ route('zoom.parent.virtual-class', [$children->id]) }}">
                                @if (count($childrens) > 1)
                                    {{ $children->full_name }}
                                @endif @lang('common.virtual_class')
                            </a>
                        </li>
                    @endif
                @endforeach
                @if (userPermission('zoom.parent.meetings') && menuStatus(103))
                    <li data-position="{{ menuPosition(103) }}">
                        <a href="{{ route('zoom.parent.meetings') }}">@lang('common.virtual_meeting')</a>
                    </li>
                @endif

            </ul>
        </li>
    @endif
@endif
<!-- zoom Menu  -->
@if (moduleStatusCheck('Gmeet') == true)
    @if (userPermission('gmeet') && menuStatus(3105))
        <li data-position="{{ menuPosition(3105) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span>
                        @lang('gmeet::gmeet.gmeet')</span>
                </div>
            </a>
            <ul class="list-unstyled" id="gmeetMenu">

                @foreach ($childrens as $children)
                    @if (userPermission('g-meet.virtual-class.parent.virtual-class') && menuStatus(3106))
                        <li data-position="{{ menuPosition(3106) }}">
                            <a href="{{ route('g-meet.virtual-class.parent.virtual-class', [$children->id]) }}">
                                @if (count($childrens) > 1)
                                    {{ $children->full_name }}
                                @endif @lang('common.virtual_class')
                            </a>
                        </li>
                    @endif
                @endforeach
                @if (userPermission('g-meet.virtual-meeting.index') && menuStatus(3107))
                    <li data-position="{{ menuPosition(3107) }}">
                        <a href="{{ route('g-meet.virtual-meeting.index') }}">@lang('common.virtual_meeting')</a>
                    </li>
                @endif

            </ul>
        </li>
    @endif
@endif
