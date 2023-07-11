@if(userPermission('student-dashboard') && menuStatus(1))
    <li data-position="{{menuPosition(1)}}" class="sortable_li">
        <a href="{{route('student-dashboard')}}">
            <div class="nav_icon_small">
                <span class="flaticon-resume"></span>
                </div>
                <div class="nav_title">
                <span>@lang('common.dashboard')</span>
                    
                </div>
        </a>
    </li>
@endif
@if(userPermission('student-profile') && menuStatus(11))
    <li data-position="{{menuPosition(11)}}" class="sortable_li">
        <a href="{{route('student-profile')}}">
            
            <div class="nav_icon_small">
                <span class="flaticon-resume"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('student.my_profile')</span>
                    
                </div>
        </a>
    </li>
@endif
@if(generalSetting()->fees_status == 0)
    @if(userPermission('fees') && menuStatus(20))
        <li data-position="{{menuPosition(20)}}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">                
                <div class="nav_icon_small">
                    <span class="flaticon-wallet"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('fees.fees')</span>
                </div>
            </a>
            <ul class="list-unstyled" >
                @if(moduleStatusCheck('FeesCollection')== false )
                    <li data-position="{{menuPosition('student_fees')}}">
                        <a href="{{route('student_fees')}}">@lang('fees.pay_fees')</a>
                    </li>
                @else
                    <li data-position="{{ menuPosition(21) }}">
                        <a href="{{ route('feescollection/student-fees') }}">@lang('fees.pay_fees')</a>
                    </li>

                @endif
            </ul>
        </li>
    @endif
@endif

@if (generalSetting()->fees_status == 1 && isMenuAllowToShow('fees'))
    @includeIf('fees::sidebar.feesStudentSidebar')
@endif



@if (moduleStatusCheck('Lms') == true)
    @if (userPermission('lms') && menuStatus(1500))
        <li data-position="{{ menuPosition(1500) }}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('lms::lms.lms')</span>
                   
                </div>
            </a>
            <ul class="list-unstyled" id="lmsButtonMenu">
                @if (userPermission(1500) && menuStatus(1500))
                    <li data-position="{{ menuPosition(1555) }}">
                        <a href="{{ route('lms.allCourse', [auth()->user()->id]) }}">@lang('lms::lms.course')</a>
                    </li>
                @endif
 
                @if(generalSetting()->lms_checkout)
                    @if (userPermission(1500) && menuStatus(1500))
                        <li data-position="{{ menuPosition(1555) }}">
                            <a href="{{ route('lms.enrolledCourse',[auth()->user()->id] ) }}">@lang('lms::lms.my_course')</a>
                        </li>
                    @endif 
                    @if (userPermission(1500) && menuStatus(1500))
                        <li data-position="{{ menuPosition(1555) }}">
                            <a href="{{ route('lms.student.purchaseLog', [auth()->user()->id]) }}">@lang('lms::lms.purchase_history')</a>
                        </li>
                    @endif
                @endif

                @if (userPermission(1500) && menuStatus(1504))
                    <li data-position="{{ menuPosition(1504) }}">
                        <a href="{{ route('lms.student.quiz') }}">@lang('lms::lms.my_quiz')</a>
                    </li>
                @endif
                @if (userPermission(1500) && menuStatus(1505))
                    <li data-position="{{ menuPosition(1505) }}">
                        <a href="{{ route('lms.student.quizReport')}}">@lang('lms::lms.quiz_report')</a>
                    </li>
                @endif

                @if (userPermission(1500) && menuStatus(1505))
                    <li data-position="{{ menuPosition(1505) }}">
                        <a href="{{ route('lms.student.certificate', auth()->id())}}">@lang('lms::lms.certificate')</a>
                    </li>
                @endif

                
            </ul>
        </li>
    @endif
@endif



@if (moduleStatusCheck('Wallet') == true)
    <li data-position="{{ menuPosition('wallet.my-wallet') }}" class="sortable_li">
        <a href="{{ route('wallet.my-wallet') }}">
            <div class="nav_icon_small">
                <span class="flaticon-wallet"></span>
            </div>
            <div class="nav_title">
                <span> @lang('wallet::wallet.my_wallet')</span>
               
            </div>
        </a>
    </li>
@endif


@if(userPermission('student_class_routine') && menuStatus(22))
    <li data-position="{{menuPosition(22)}}" class="sortable_li">
        <a href="{{route('student_class_routine')}}">
          
            
            <div class="nav_icon_small">
                <span class="flaticon-calendar-1"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('academics.class_routine')</span>
                   
                </div>
        </a>
    </li>
@endif

@if(userPermission('lesson') && menuStatus(800))
    <li data-position="{{menuPosition(800)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            
            <div class="nav_icon_small">
                <span class="flaticon-calendar-1"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('lesson::lesson.lesson')</span>
                    
                </div>
        </a>
        <ul class="list-unstyled" >
            @if(userPermission('lesson-student-lessonPlan') && menuStatus(810))
                <li data-position="{{menuPosition(810)}}">
                    <a href="{{route('lesson-student-lessonPlan')}}">@lang('lesson::lesson.lesson_plan')</a>
                </li>
            @endif
            @if (userPermission('lesson-student-lessonPlan-overview') && menuStatus(815))
                <li data-position="{{ menuPosition(815) }}">
                    <a
                        href="{{ route('lesson-student-lessonPlan-overview') }}">@lang('lesson::lesson.lesson_plan_overview')</a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if(userPermission('student_homework') && menuStatus(23))
    <li data-position="{{menuPosition(23)}}" class="sortable_li">
        <a href="{{route('student_homework')}}">           
            <div class="nav_icon_small">
                <span class="flaticon-book"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('homework.home_work')</span>
                    
                </div>
        </a>
    </li>
@endif
@if(userPermission(26) && menuStatus(26) && isMenuAllowToShow('study_material'))
    <li data-position="{{menuPosition(26)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
           
            
            <div class="nav_icon_small">
                <span class="flaticon-data-storage"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('study.download_center')</span>
                    
                </div>
        </a>
        <ul class="list-unstyled" id="subMenuDownloadCenter">
            @if(userPermission('student_assignment') && menuStatus(27))
                <li data-position="{{menuPosition(27)}}">
                    <a href="{{route('student_assignment')}}">@lang('study.assignment')</a>
                </li>
            @endif
            {{-- @if (userPermission(29) && menuStatus(29))

                @if (userPermission('student_pdf_exam') && menuStatus(2048))
                    <li data-position="{{ menuPosition(2048) }}">
                        <a href="{{ route('student_pdf_exam') }} " class="active"> PDF @lang('exam.exam') </a>
                    </li>
                @endif

                @if (userPermission('student_view_pdf_result') && menuStatus(2049))
                    <li data-position="{{ menuPosition(2049) }}">
                        <a href=" {{ route('student_view_pdf_result') }} "> PDF @lang('exam.exam_result') </a>
                    </li>
                @endif

            @endif --}}

        </ul>
    </li>
@endif

@if(userPermission('student_noticeboard') && menuStatus(48))
    <li data-position="{{menuPosition(48)}}" class="sortable_li">
        <a href="{{route('student_noticeboard')}}">
            <div class="nav_icon_small">
                <span class="flaticon-poster"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('communicate.notice_board')</span>
                    
                </div>
        </a>
    </li>
@endif
@if(userPermission('student_subject') && menuStatus(49))
    <li data-position="{{menuPosition(49)}}" class="sortable_li">
        <a href="{{route('student_subject')}}">
            <div class="nav_icon_small">
                <span class="flaticon-reading-1"></span>
                </div>
                <div class="nav_title">
                    <span>  @lang('common.subjects')</span>
                  
                </div>
        </a>
    </li>
@endif
@if(userPermission('student_teacher') && menuStatus(50))
    <li data-position="{{menuPosition(50)}}" class="sortable_li">
        <a href="{{route('student_teacher')}}">
           
           
            <div class="nav_icon_small">
                <span class="flaticon-professor"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('common.teacher')</span>
                   
                </div>
        </a>
    </li>
@endif

@if(userPermission('leave') && menuStatus(39))
    <li data-position="{{menuPosition(39)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            
            
            <div class="nav_icon_small">
                <span class="flaticon-slumber"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('leave.leave')</span>
                   
                </div>
        </a>
        <ul class="list-unstyled" id="subMenuLeaveManagement">

            @if (userPermission('student-apply-leave') && menuStatus(40))

                <li data-position="{{ menuPosition(40) }}">
                    <a href="{{ route('student-apply-leave') }}">@lang('leave.apply_leave')</a>
                </li>
            @endif

            @if (userPermission('student-pending-leave') && menuStatus(44))

                <li data-position="{{ menuPosition(44) }}">
                    <a href="{{ route('student-pending-leave') }}">@lang('leave.pending_leave_request')</a>
                </li>
            @endif
        </ul>
    </li>
@endif


@if(userPermission(51) && menuStatus(51))
    <li data-position="{{menuPosition(51)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
           
            <div class="nav_icon_small">
                <span class="flaticon-book-1"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('library.library')</span>
                    
                </div>
        </a>
        <ul class="list-unstyled" id="subMenuStudentLibrary">
            @if(userPermission('student_library') && menuStatus(52))
                <li data-position="{{menuPosition(52)}}">
                    <a href="{{route('student_library')}}"> @lang('library.book_list')</a>
                </li>
            @endif
            @if (userPermission('student_book_issue') && menuStatus(53))
                <li data-position="{{ menuPosition(53) }}">
                    <a href="{{ route('student_book_issue') }}">@lang('library.book_issue')</a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if(userPermission('student_transport') && menuStatus(54))
    <li data-position="{{menuPosition(54)}}" class="sortable_li">
        <a href="{{route('student_transport')}}">
            
           
            
            <div class="nav_icon_small">
                <span class="flaticon-bus"></span>
                </div>
                <div class="nav_title">
                    <span>@lang('transport.transport')</span>
                    
                </div>
        </a>
    </li>
@endif
@if(userPermission('student_dormitory') && menuStatus(55))
    <li data-position="{{menuPosition(55)}}" class="sortable_li">
        <a href="{{route('student_dormitory')}}">
           
           
            <div class="nav_icon_small">
                <span class="flaticon-hotel"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('dormitory.dormitory')</span>
                   
                </div>
        </a>
    </li>
@endif

@if(isMenuAllowToShow('chat'))
@include('chat::menu')
@endif

<!-- Zoom Menu -->
@if (moduleStatusCheck('Zoom') == true)

    @include('zoom::menu.Zoom')
@endif

<!-- BBB Menu -->
@if (moduleStatusCheck('BBB') == true)
    @include('bbb::menu.bigbluebutton_sidebar')
@endif

@if (moduleStatusCheck('Gmeet') == true)
    @include('gmeet::menu')
@endif

{{-- @if (moduleStatusCheck('Lms') == true)
    @include('lms::menu.lms_sidebar')
@endif --}}

<!-- Jitsi Menu -->
@if (moduleStatusCheck('Jitsi') == true)
    @include('jitsi::menu.jitsi_sidebar')
@endif
