@php
    $school_config = schoolConfig();
    $isSchoolAdmin = Session::get('isSchoolAdmin');
@endphp
<!-- sidebar part here -->
<nav id="sidebar" class="sidebar">

    <div class="sidebar-header update_sidebar">
        @if (auth()->user()->role_id != 2 && auth()->user()->role_id != 3)
            @if (userPermission('dashboard'))
                @if (moduleStatusCheck('Saas') == true &&
                        auth()->user()->is_administrator == 'yes' &&
                        Session::get('isSchoolAdmin') == false &&
                        auth()->user()->role_id == 1)
                    <a href="{{ route('superadmin-dashboard') }}" id="superadmin-dashboard">
                    @else
                        <a href="{{ route('admin-dashboard') }}" id="admin-dashboard">
                @endif
            @else
                <a href="{{ url('/') }}" id="admin-dashboard">
            @endif
        @else
            <a href="{{ url('/') }}" id="admin-dashboard">
        @endif
        @if (!is_null($school_config->logo))
            <img src="{{ asset($school_config->logo) }}" alt="logo">
        @else
            <img src="{{ asset('public/uploads/settings/logo.png') }}" alt="logo">
        @endif
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>

    </div>
    @if (auth()->user()->is_saas == 0)

        <ul class="sidebar_menu list-unstyled" id="sidebar_menu">
            <input type="hidden" name="" id="default_position" value="{{ menuPosition('is_submit') }}">
            @if (auth()->user()->role_id != 2 && auth()->user()->role_id != 3)
                @if (userPermission('dashboard'))
                    <li>
                        @if (moduleStatusCheck('Saas') == true &&
                                auth()->user()->is_administrator == 'yes' &&
                                Session::get('isSchoolAdmin') == false &&
                                auth()->user()->role_id == 1)
                            <a href="{{ route('superadmin-dashboard') }}" id="superadmin-dashboard">
                            @else
                                <a href="{{ route('admin-dashboard') }}" id="admin-dashboard">
                        @endif

                        <div class="nav_icon_small">
                            <span class="fas fa-th"></span>
                        </div>
                        <div class="nav_title">
                            <span>@lang('common.dashboard')</span>
                        </div>

                        </a>
                    </li>
                @endif
            @endif

            @if (moduleStatusCheck('InfixBiometrics') == true && auth()->user()->role_id == 1)
                @include('infixbiometrics::menu.InfixBiometrics')
            @endif

            {{-- Parent Registration Menu --}}




            {{-- Saas Subscription Menu --}}
            @if (isSubscriptionEnabled() && auth()->user()->is_administrator != 'yes')
                @include('saas::menu.SaasSubscriptionSchool')
            @endif

            {{-- Saas Module Menu --}}
            @if (moduleStatusCheck('Saas') == true &&
                    auth()->user()->is_administrator == 'yes' &&
                    Session::get('isSchoolAdmin') == false &&
                    auth()->user()->role_id == 1)
                @include('saas::menu.Saas')
            @else
                @if (isMenuAllowToShow('menu_manage'))
                    @include('menumanage::menu.sidebar')
                @endif

                @if (auth()->user()->role_id != 2 && auth()->user()->role_id != 3)

                    {{-- admin_section --}}
                    @if (moduleStatusCheck('ParentRegistration'))
                        @includeIf('parentregistration::menu.ParentRegistration')
                    @endif
                    @if (userPermission('admin_section') && menuStatus(11) && isMenuAllowToShow('admin_section'))
                        <li data-position="{{ menuPosition(11) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="flaticon-analytics"> </span>

                                </div>
                                <div class="nav_title">
                                    <span>@lang('admin.admin_section')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled">
                                @if (userPermission('admission_query') && menuStatus(12))
                                    <li data-position="{{ menuPosition(12) }}">
                                        <a href="{{ route('admission_query') }}">@lang('admin.admission_query') </a>
                                    </li>
                                @endif

                                @if (userPermission('visitor') && menuStatus(16))
                                    <li data-position="{{ menuPosition(16) }}">
                                        <a href="{{ route('visitor') }}">@lang('admin.visitor_book') </a>
                                    </li>
                                @endif

                                @if (userPermission('complaint') && menuStatus(21))
                                    <li data-position="{{ menuPosition(21) }}">
                                        <a href="{{ route('complaint') }}">@lang('admin.complaint')</a>
                                    </li>
                                @endif
                                @if (userPermission('postal-receive') && menuStatus(27))
                                    <li data-position="{{ menuPosition(27) }}">
                                        <a href="{{ route('postal-receive') }}">@lang('admin.postal_receive')</a>
                                    </li>
                                @endif
                                @if (userPermission('postal-dispatch') && menuStatus(32))
                                    <li data-position="{{ menuPosition(32) }}">
                                        <a href="{{ route('postal-dispatch') }}">@lang('admin.postal_dispatch')</a>
                                    </li>
                                @endif
                                @if (userPermission('phone-call') && menuStatus(36))
                                    <li data-position="{{ menuPosition(36) }}">
                                        <a href="{{ route('phone-call') }}">@lang('admin.phone_call_log')</a>
                                    </li>
                                @endif
                                @if (userPermission('setup-admin') && menuStatus(41))
                                    <li data-position="{{ menuPosition(41) }}">
                                        <a href="{{ route('setup-admin') }}">@lang('admin.admin_setup')</a>
                                    </li>
                                @endif
                                @if (userPermission('student-certificate') && menuStatus(49))
                                    <li data-position="{{ menuPosition(49) }}">
                                        <a href="{{ route('student-certificate') }}">@lang('admin.student_certificate')</a>
                                    </li>
                                @endif
                                @if (userPermission('generate_certificate') && menuStatus(53))
                                    <li data-position="{{ menuPosition(53) }}">
                                        <a href="{{ route('generate_certificate') }}">@lang('admin.generate_certificate')</a>
                                    </li>
                                @endif
                                @if (userPermission('student-id-card') && menuStatus(45))
                                    <li data-position="{{ menuPosition(45) }}">
                                        <a href="{{ route('student-id-card') }}">@lang('admin.id_card')</a>
                                    </li>
                                @endif
                                @if (userPermission('generate_id_card') && menuStatus(57))
                                    <li data-position="{{ menuPosition(57) }}">
                                        <a href="{{ route('generate_id_card') }}">@lang('admin.generate_id_card')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (moduleStatusCheck('Lead') == true)
                        @includeIf('lead::menu.lead_menu')
                    @endif
                    {{-- student_information --}}
                    @if (userPermission('student_info') && menuStatus(61) && isMenuAllowToShow('student_info'))
                        <li data-position="{{ menuPosition(61) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-user-tie"></span>
                                </div>
                                <div class="nav_title">
                                    <span> @lang('student.student_information')</span>
                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuStudent">
                                @if (userPermission('student_category') && menuStatus(71))
                                    <li data-position="{{ menuPosition(71) }}">
                                        <a href="{{ route('student_category') }}">
                                            @lang('student.student_category')</a>
                                    </li>
                                @endif
                                @if (userPermission('student_admission') && menuStatus(62))
                                    <li data-position="{{ menuPosition(62) }}">
                                        <a href="{{ route('student_admission') }}">@lang('student.add_student')</a>
                                    </li>
                                @endif
                                @if (userPermission('student_list') && menuStatus(64))
                                    <li data-position="{{ menuPosition(64) }}">
                                        <a href="{{ route('student_list') }}"> @lang('student.student_list')</a>
                                    </li>
                                @endif
                                @if (userPermission('student.multi-class-student') && menuStatus(64))
                                    <li data-position="{{ menuPosition(64) }}">
                                        <a href="{{ route('student.multi-class-student') }}"> @lang('student.multi_class_student')</a>
                                    </li>
                                @endif
                                @if (userPermission('student.delete-student-record') && menuStatus(64))
                                    <li data-position="{{ menuPosition(64) }}">
                                        <a href="{{ route('student.delete-student-record') }}"> @lang('student.delete_student_record')</a>
                                    </li>
                                @endif
                                @if (userPermission('student_attendance') && menuStatus(68))
                                    <li data-position="{{ menuPosition(68) }}">
                                        <a href="{{ route('student_attendance') }}">
                                            @lang('student.student_attendance')</a>
                                    </li>
                                @endif
                                @if (userPermission('student_attendance_report') && menuStatus(70))
                                    <li data-position="{{ menuPosition(70) }}">
                                        <a href="{{ route('student_attendance_report') }}">
                                            @lang('student.student_attendance_report')</a>
                                    </li>
                                @endif
                                @if (userPermission('subject-wise-attendance') && menuStatus(533))
                                    <li data-position="{{ menuPosition(533) }}">
                                        <a href="{{ route('subject-wise-attendance') }}">
                                            @lang('student.subject_wise_attendance') </a>
                                    </li>
                                @endif
                                @if (userPermission(535) && menuStatus(535))
                                    <li data-position="{{ menuPosition(535) }}">
                                        <a href="{{ url('subject-attendance-average-report') }}">
                                            @lang('student.subject_attendance_report')</a>
                                    </li>
                                @endif
                                @if (userPermission(76) && menuStatus(76))
                                    <li data-position="{{ menuPosition(76) }}">
                                        <a href="{{ route('student_group') }}">@lang('student.student_group')</a>
                                    </li>
                                @endif
                                @if (userPermission(81) && menuStatus(81))
                                    <li data-position="{{ menuPosition(81) }}">
                                        @if (moduleStatusCheck('University'))
                                            <a href="{{ route('university.student_promote') }}">@lang('student.student_promote')</a>
                                        @else
                                            <a href="{{ route('student_promote') }}">@lang('student.student_promote')</a>
                                        @endif

                                    </li>
                                @endif
                                @if (userPermission(83) && menuStatus(83))
                                    <li data-position="{{ menuPosition(83) }}">
                                        <a href="{{ route('unassigned_student') }}">@lang('student.unassigned_student')</a>
                                    </li>
                                @endif

                                @if (userPermission(83) && menuStatus(83))
                                    <li data-position="{{ menuPosition(83) }}">
                                        <a href="{{ route('disabled_student') }}">@lang('student.disabled_student')</a>
                                    </li>
                                @endif

                                @if (userPermission(663) && menuStatus(663))
                                    <li data-position="{{ menuPosition(663) }}">
                                        <a href="{{ route('all-student-export') }}">@lang('student.student_export')</a>
                                    </li>
                                @endif

                                @if (userPermission(950) && menuStatus(950))
                                    <li data-position="{{ menuPosition(950) }}">
                                        <a href="{{ route('notification_time_setup') }}">@lang('student.sms_sending_time')</a>
                                    </li>
                                @endif

                                @if (userPermission(951) && menuStatus(951))
                                    <li data-position="{{ menuPosition(951) }}">
                                        <a href="{{ route('student_settings') }}">@lang('student.settings')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- global class section subject  --}}
                    @if (userPermission(245) && menuStatus(245) && isMenuAllowToShow('academics'))
                        <li data-position="{{ menuPosition(245) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="flaticon-book"></span>
                                </div>
                                <div class="nav_title">
                                    @lang('academics.global_academics')
                                </div>
                            </a>
                            <ul class="mm-collapse" id="subMenuAcademic">
                                @if (userPermission(537) && menuStatus(537))
                                    <li data-position="{{ menuPosition(537) }}">
                                        <a href="{{ route('global_section') }}"> @lang('academics.global_section')
                                        </a>
                                    </li>
                                @endif

                                @if (userPermission(537) && menuStatus(537))
                                    <li data-position="{{ menuPosition(537) }}">
                                        <a href="{{ route('global_class') }}"> @lang('academics.global_class')
                                        </a>
                                    </li>
                                @endif
                                @if (userPermission(537) && menuStatus(537))
                                    <li data-position="{{ menuPosition(537) }}">
                                        <a href="{{ route('global_subject') }}"> @lang('academics.global_subject')
                                        </a>
                                    </li>
                                @endif
                                @if (userPermission(537) && menuStatus(537))
                                    <li data-position="{{ menuPosition(537) }}">
                                        <a href="{{ route('global_assign_subject') }}"> @lang('academics.assign_subject')
                                        </a>
                                    </li>
                                @endif

                                @if (userPermission(537) && menuStatus(537))
                                    <li data-position="{{ menuPosition(537) }}">
                                        <a href="{{ route('global-upload-content') }}"> @lang('academics.global_study_material')
                                        </a>
                                    </li>
                                @endif

                                @if (userPermission(208) && menuStatus(208))
                                    <li data-position="{{ menuPosition(208) }}">
                                        <a href="{{ route('global_exam-type') }}"> @lang('exam.global_exam_type')</a>
                                    </li>
                                @endif

                                @if (userPermission(208) && menuStatus(208))
                                    <li data-position="{{ menuPosition(208) }}">
                                        <a href="{{ route('global-exam') }}"> @lang('exam.global_exam')</a>
                                    </li>
                                @endif

                                @if (userPermission(208) && menuStatus(208))
                                    <li data-position="{{ menuPosition(208) }}">
                                        <a href="{{ route('global-assign') }}"> @lang('academics.global_exam_setup')</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- academics --}}
                    @if (userPermission(245) && menuStatus(245) && isMenuAllowToShow('academics'))
                        <li data-position="{{ menuPosition(245) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-graduation-cap"></span>
                                </div>
                                <div class="nav_title">
                                    <span> @lang('academics.academics')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuAcademic">
                                @if (moduleStatusCheck('University') == false)
                                    @if (userPermission(537) && menuStatus(537))
                                        <li data-position="{{ menuPosition(537) }}">
                                            <a href="{{ route('optional-subject') }}"> @lang('academics.optional_subject')
                                            </a>
                                        </li>
                                    @endif
                                    @if (userPermission(265) && menuStatus(265))
                                        <li data-position="{{ menuPosition(265) }}">
                                            <a href="{{ route('section') }}"> @lang('common.section')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(261) && menuStatus(261))
                                        <li data-position="{{ menuPosition(261) }}">
                                            <a href="{{ route('class') }}"> @lang('common.class')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(257) && menuStatus(257))
                                        <li data-position="{{ menuPosition(257) }}">
                                            <a href="{{ route('subject') }}"> @lang('common.subjects')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(253) && menuStatus(253))
                                        <li data-position="{{ menuPosition(253) }}">
                                            <a href="{{ route('assign-class-teacher') }}">
                                                @lang('academics.assign_class_teacher')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(250) && menuStatus(250))
                                        <li data-position="{{ menuPosition(250) }}">
                                            <a href="{{ route('assign_subject') }}"> @lang('academics.assign_subject')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(269) && menuStatus(269))
                                        <li data-position="{{ menuPosition(269) }}">
                                            <a href="{{ route('class-room') }}"> @lang('academics.class_room')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(246) && menuStatus(246))
                                        <li data-position="{{ menuPosition(246) }}">
                                            <a href="{{ route('class_routine') }}"> @lang('academics.class_routine')</a>
                                        </li>
                                    @endif
                                @else
                                    @if (userPermission(269) && menuStatus(269))
                                        <li data-position="{{ menuPosition(269) }}">
                                            <a href="{{ route('class-room') }}"> @lang('academics.class_room')</a>
                                        </li>
                                    @endif
                                    @if (moduleStatusCheck('University'))
                                        @if (userPermission(246) && menuStatus(246))
                                            <li data-position="{{ menuPosition(246) }}">
                                                <a href="{{ route('university.academics.classRoutine') }}">
                                                    @lang('academics.class_routine')</a>
                                            </li>
                                        @endif
                                    @endif

                                @endif


                                @if (auth()->user()->role_id != 4)
                                    @if (userPermission(387) && menuStatus(387))
                                        <li data-position="{{ menuPosition(387) }}">
                                            <a
                                                href="{{ route('teacher_class_routine_report') }}">@lang('academics.teacher_class_routine')</a>
                                        </li>
                                    @endif
                                @endif
                                <!-- only for teacher -->
                                @if (auth()->user()->role_id == 4)
                                    <li>
                                        <a href="{{ route('view-teacher-routine') }}">@lang('academics.view_class_routine')</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- study_material --}}
                    @if (userPermission('study_material') && menuStatus(87) && isMenuAllowToShow('study_material'))
                        <li data-position="{{ menuPosition(87) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-solid fa-download"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('study.study_material')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuTeacher">
                                @if (userPermission('upload-content') && menuStatus(87))
                                    <li data-position="{{ menuPosition(88) }}">
                                        <a href="{{ route('upload-content') }}"> @lang('study.upload_content')</a>
                                    </li>
                                @endif
                                @if (userPermission('assignment-list') && menuStatus(92))
                                    <li data-position="{{ menuPosition(92) }}">
                                        <a href="{{ route('assignment-list') }}">@lang('study.assignment')</a>
                                    </li>
                                @endif
                                @if (userPermission(100) && menuStatus(100))
                                    <li data-position="{{ menuPosition(100) }}">
                                        <a href="{{ route('syllabus-list') }}">@lang('study.syllabus')</a>
                                    </li>
                                @endif
                                @if (userPermission(105) && menuStatus(105))
                                    <li data-position="{{ menuPosition(105) }}">
                                        <a href="{{ route('other-download-list') }}">@lang('study.other_download')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Lesson Plan --}}
                    @if (userPermission(800) && menuStatus(800) && isMenuAllowToShow('lesson_plan'))
                        <li data-position="{{ menuPosition(800) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa fa-list-alt"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('lesson::lesson.lesson_plan')</span>

                                </div>
                            </a>

                            <ul class="list-unstyled" id="subMenuTeacherLesson">
                                @if (userPermission(801) && menuStatus(801))
                                    <li data-position="{{ menuPosition(801) }}">
                                        <a href="{{ route('lesson') }}"> @lang('lesson::lesson.lesson')</a>
                                    </li>
                                @endif
                                @if (userPermission(805) && menuStatus(805))
                                    <li data-position="{{ menuPosition(805) }}">
                                        <a href="{{ route('lesson.topic') }}"> @lang('lesson::lesson.topic')</a>
                                    </li>
                                @endif
                                @if (userPermission(809) && menuStatus(809))
                                    <li data-position="{{ menuPosition(809) }}">
                                        <a href="{{ route('topic-overview') }}">
                                            @lang('lesson::lesson.topic_overview')</a>
                                    </li>
                                @endif
                                @if (userPermission(810) && menuStatus(810))
                                    <li data-position="{{ menuPosition(810) }}">
                                        <a href="{{ route('lesson.lesson-planner') }}">
                                            @lang('lesson::lesson.lesson_plan')</a>
                                    </li>
                                @endif

                                @if (userPermission(815) && menuStatus(815))
                                    <li data-position="{{ menuPosition(815) }}">
                                        <a href="{{ route('lesson.lessonPlan-overiew') }}">
                                            @lang('lesson::lesson.lesson_plan_overview')</a>
                                    </li>
                                @endif

                                @if (userPermission(835) && menuStatus(835))
                                    <li data-position="{{ menuPosition(835) }}">
                                        <a href="{{ route('lesson.lessonPlan-setting') }}">
                                            @lang('lesson::lesson.lesson_plan_setting')</a>
                                    </li>
                                @endif
                                @if (auth()->user()->role_id == 4)
                                    <li>
                                        <a href="{{ route('view-teacher-lessonPlan') }}">@lang('lesson::lesson.my_lesson_plan')
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ route('view-teacher-lessonPlan-overview') }}">@lang('lesson::lesson.my_lesson_plan_overview')</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- FeesCollection --}}


                    @if (moduleStatusCheck('FeesCollection') == true)
                        @include('feescollection::menu.FeesCollection')
                    @else
                        @if (generalSetting()->fees_status == 0 || !moduleStatusCheck('Fees'))
                            @if (userPermission(108) && menuStatus(108))
                                <li data-position="{{ menuPosition(108) }}" class="sortable_li">
                                    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                        <div class="nav_icon_small">
                                            <span class="flaticon-wallet"></span>
                                        </div>
                                        <div class="nav_title">
                                            <span>@lang('fees.fees_collection')</span>

                                        </div>
                                    </a>
                                    <ul class="list-unstyled" id="subMenuFeesCollection">

                                        @if (!moduleStatusCheck('University') && directFees() == false)
                                            @if (userPermission(123) && menuStatus(123))
                                                <li data-position="{{ menuPosition(123) }}">
                                                    <a href="{{ route('fees_group') }}"> @lang('fees.fees_group')</a>
                                                </li>
                                            @endif
                                            @if (userPermission(127) && menuStatus(127))
                                                <li data-position="{{ menuPosition(127) }}">
                                                    <a href="{{ route('fees_type') }}"> @lang('fees.fees_type')</a>
                                                </li>
                                            @endif
                                        @endif
                                        @if (userPermission(131) && menuStatus(131))
                                            <li data-position="{{ menuPosition(131) }}">
                                                <a href="{{ route('fees-master') }}"> @lang('fees.fees_master')</a>
                                            </li>
                                        @endif


                                        @if (userPermission(118) && menuStatus(118))
                                            <li data-position="{{ menuPosition(118) }}">
                                                <a href="{{ route('fees_discount') }}"> @lang('fees.fees_discount')</a>
                                            </li>
                                        @endif
                                        @if (userPermission(109) && menuStatus(109))
                                            <li data-position="{{ menuPosition(109) }}">
                                                <a href="{{ route('collect_fees') }}"> @lang('fees.collect_fees')</a>
                                            </li>
                                        @endif

                                        @if (userPermission(113) && menuStatus(113))
                                            <li data-position="{{ menuPosition(113) }}">
                                                <a href="{{ route('search_fees_payment') }}">
                                                    @lang('fees.search_fees_payment')</a>
                                            </li>
                                        @endif
                                        @if (userPermission(116) && menuStatus(116) && !directFees())
                                            <li data-position="{{ menuPosition(116) }}">
                                                <a href="{{ route('search_fees_due') }}">
                                                    @lang('fees.search_fees_due')</a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('bank-payment-slip') }}"> @lang('fees.bank_payment')</a>
                                        </li>

                                        @if (!moduleStatusCheck('University') && directFees() == false)
                                            @if (userPermission(136) && menuStatus(136))
                                                <li data-position="{{ menuPosition(136) }}">
                                                    <a href="{{ route('fees_forward') }}"> @lang('fees.fees_forward')</a>
                                                </li>
                                            @endif
                                        @endif

                                        @if (userPermission(383) && menuStatus(383))
                                            <li data-position="{{ menuPosition(383) }}">
                                                <a href="{{ route('transaction_report') }}">@lang('fees.collection_report')</a>
                                            </li>
                                        @endif


                                        {{-- @if (userPermission(840))
                                            <li data-position="{{menuPosition(840)}}" class="sortable_li">
                                                <a href="#subMenuFeesReport" data-toggle="collapse" aria-expanded="false"
                                                class="dropdown-toggle">
                                                    @lang('lang.report')
                                                </a>
                                                <ul class="list-unstyled" id="subMenuFeesReport">
                                                    @if (userPermission(383))
                                                        <li data-position="{{menuPosition(383)}}">
                                                            <a href="{{route('transaction_report')}}">@lang('lang.collection_report')</a>
                                                        </li>
                                                @endif

                                                </ul>
                                            </li>
                                            @endif --}}
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endif

                    @if (generalSetting()->fees_status == 1 && moduleStatusCheck('Fees') && isMenuAllowToShow('fees'))
                        @includeIf('fees::sidebar.adminSidebar')
                    @endif

                    {{-- check module link permission --}}
                    @if (moduleStatusCheck('University'))
                        @include('university::un_menu')
                    @endif


                    @if (moduleStatusCheck('Alumni'))
                        @include('alumni::alumni_menu')
                    @endif


                    @if (isMenuAllowToShow('wallet'))
                        @includeIf('wallet::menu.sidebar')
                    @endif
                    @if (moduleStatusCheck('Lms') == true)
                        @include('lms::menu.lms_sidebar')
                    @endif

                    @if (moduleStatusCheck('BulkPrint') == true && isMenuAllowToShow('bulk_print'))
                        @include('bulkprint::menu.bulk_print_sidebar')
                    @endif

                    {{-- accounts --}}
                    @if (userPermission(137) && menuStatus(137) && isMenuAllowToShow('accounts'))
                        <li data-position="{{ menuPosition(137) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-university"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('accounts.accounts')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuAccount">
                                @if (userPermission(148) && menuStatus(148))
                                    <li data-position="{{ menuPosition(148) }}">
                                        <a href="{{ route('chart-of-account') }}">
                                            @lang('accounts.chart_of_account')</a>
                                    </li>
                                @endif
                                @if (userPermission(156) && menuStatus(156))
                                    <li data-position="{{ menuPosition(156) }}">
                                        <a href="{{ route('bank-account') }}"> @lang('accounts.bank_account')</a>
                                    </li>
                                @endif
                                @if (userPermission(139) && menuStatus(139))
                                    <li data-position="{{ menuPosition(139) }}">
                                        <a href="{{ route('add_income') }}"> @lang('accounts.income')</a>
                                    </li>
                                @endif
                                @if (userPermission(138) && menuStatus(138))
                                    <li data-position="{{ menuPosition(138) }}">
                                        <a href="{{ route('profit') }}"> @lang('accounts.profit_&_loss')</a>
                                    </li>
                                @endif
                                @if (userPermission(143) && menuStatus(143))
                                    <li data-position="{{ menuPosition(143) }}">
                                        <a href="{{ route('add-expense') }}"> @lang('accounts.expense')</a>
                                    </li>
                                @endif
                                {{-- @if (userPermission(147))
                                    <li>
                                        <a href="{{route('search_account')}}"> @lang('common.search')</a>
                                    </li>
                                @endif --}}
                                @if (userPermission(704) && menuStatus(704))
                                    <li data-position="{{ menuPosition(704) }}">
                                        <a href="{{ route('fund-transfer') }}">@lang('accounts.fund_transfer')</a>
                                    </li>
                                @endif
                                @if (userPermission(700) && menuStatus(700))
                                    @php
                                        $subMenuAccountReport = ['fine-report', 'accounts-payroll-report', 'transaction'];
                                        $subMenuAccount = array_merge(['lead.index'], $subMenuAccountReport);
                                    @endphp
                                    <li data-position="{{ menuPosition(700) }}">
                                        <a href="javascript:void(0)"
                                            class="has-arrow {{ spn_nav_item_open($subMenuAccount, 'active') }}"
                                            aria-expanded="false">
                                            @lang('reports.report')
                                        </a>
                                        <ul class="list-unstyled {{ spn_nav_item_open($subMenuAccount, 'show') }}"
                                            id="subMenuAccountReport">
                                            @if (generalSetting()->fees_status == 0 && userPermission(701) && menuStatus(701))
                                                <li>
                                                    <a href="{{ route('fine-report') }}">
                                                        @lang('accounts.fine_report')</a>
                                                </li>
                                            @endif
                                            @if (userPermission(702) && menuStatus(702))
                                                <li>
                                                    <a href="{{ route('accounts-payroll-report') }}">
                                                        @lang('accounts.payroll_report')</a>
                                                </li>
                                            @endif
                                            @if (userPermission(703) && menuStatus(703))
                                                <li>
                                                    <a href="{{ route('transaction') }}">
                                                        @lang('accounts.transaction')</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- human_resource --}}
                    @if (userPermission(160) && menuStatus(160) && isMenuAllowToShow('human_resource'))
                        <li data-position="{{ menuPosition(160) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-users-cog"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('hr.human_resource')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuHumanResource">
                                @if (userPermission(180) && menuStatus(180))
                                    <li data-position="{{ menuPosition(180) }}">
                                        <a href="{{ route('designation') }}"> @lang('hr.designation')</a>
                                    </li>
                                @endif
                                @if (userPermission(184) && menuStatus(184))
                                    <li data-position="{{ menuPosition(184) }}">
                                        <a href="{{ route('department') }}"> @lang('hr.department')</a>
                                    </li>
                                @endif
                                @if (userPermission(162) && menuStatus(162))
                                    <li data-position="{{ menuPosition(162) }}">
                                        <a href="{{ route('addStaff') }}"> @lang('common.add_staff') </a>
                                    </li>
                                @endif
                                @if (userPermission(161) && menuStatus(161))
                                    <li data-position="{{ menuPosition(161) }}">
                                        <a href="{{ route('staff_directory') }}"> @lang('hr.staff_directory')</a>
                                    </li>
                                @endif
                                @if (userPermission(165) && menuStatus(162))
                                    <li data-position="{{ menuPosition(165) }}">
                                        <a href="{{ route('staff_attendance') }}"> @lang('hr.staff_attendance')</a>
                                    </li>
                                @endif
                                @if (userPermission(169) && menuStatus(169))
                                    <li data-position="{{ menuPosition(169) }}">
                                        <a href="{{ route('staff_attendance_report') }}">
                                            @lang('hr.staff_attendance_report')</a>
                                    </li>
                                @endif
                                @if (userPermission(170) && menuStatus(170))
                                    <li data-position="{{ menuPosition(170) }}">
                                        <a href="{{ route('payroll') }}"> @lang('hr.payroll')</a>
                                    </li>
                                @endif
                                @if (userPermission(178) && menuStatus(178))
                                    <li data-position="{{ menuPosition(178) }}">
                                        <a href="{{ route('payroll-report') }}"> @lang('hr.payroll_report')</a>
                                    </li>
                                @endif

                                @if (userPermission(952) && menuStatus(952))
                                    <li data-position="{{ menuPosition(951) }}">
                                        <a href="{{ route('staff_settings') }}">@lang('student.settings')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- leave --}}
                    @if (userPermission(188) && menuStatus(188) && isMenuAllowToShow('leave'))
                        <li data-position="{{ menuPosition(188) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">

                                    <span class="flaticon-slumber"></span>
                                </div>
                                <div class="nav_title">
                                    <span> @lang('leave.leave')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuLeaveManagement">
                                @if (userPermission(203) && menuStatus(203))
                                    <li data-position="{{ menuPosition(203) }}">
                                        <a href="{{ route('leave-type') }}"> @lang('leave.leave_type')</a>
                                    </li>
                                @endif
                                @if (userPermission(199) && menuStatus(199))
                                    <li data-position="{{ menuPosition(199) }}">
                                        <a href="{{ route('leave-define') }}"> @lang('leave.leave_define')</a>
                                    </li>
                                @endif
                                @if (userPermission(189) && menuStatus(189))
                                    <li data-position="{{ menuPosition(189) }}">
                                        <a href="{{ route('approve-leave') }}">@lang('leave.approve_leave_request')</a>
                                    </li>
                                @endif
                                @if (userPermission(196) && menuStatus(196))
                                    <li data-position="{{ menuPosition(196) }}">
                                        <a href="{{ route('pending-leave') }}">@lang('leave.pending_leave_request')</a>
                                    </li>
                                @endif
                                @if (auth()->user()->role_id != 1)

                                    @if (userPermission(193) && menuStatus(193))
                                        <li data-position="{{ menuPosition(193) }}">
                                            <a href="{{ route('apply-leave') }}">@lang('leave.apply_leave')</a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Custom Field Start --}}
                    @if (userPermission(1100) && menuStatus(1100) && isMenuAllowToShow('custom_field'))
                        <li data-position="{{ menuPosition(1100) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="flaticon-slumber"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('student.custom_field')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuCustomField">
                                @if (moduleStatusCheck('Lead') == true)
                                    @if (userPermission(1441) && menuStatus(1441))
                                        <li data-position="{{ menuPosition(1441) }}">
                                            <a href="{{ route('lead.lead-reg-custom-field') }}">
                                                @lang('lead::lead.lead_registration')
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if (userPermission(1101) && menuStatus(1101))
                                    <li data-position="{{ menuPosition(1101) }}">
                                        <a href="{{ route('student-reg-custom-field') }}">
                                            @lang('student.student_registration')
                                        </a>
                                    </li>
                                @endif
                                @if (userPermission(1105) && menuStatus(1105))
                                    <li data-position="{{ menuPosition(1105) }}">
                                        <a href="{{ route('staff-reg-custom-field') }}">
                                            @lang('hr.staff_registration')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Custom Field End --}}

                    {{-- Chat --}}

                    @if (moduleStatusCheck('Chat') == true && isMenuAllowToShow('chat'))
                        @include('chat::menu')
                    @endif
                    {{-- Examination --}}
                    @if (userPermission(207) && menuStatus(207) && isMenuAllowToShow('examination'))
                        <li data-position="{{ menuPosition(207) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fa fa-map-o"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('exam.examination')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuExam">
                                @if (userPermission(225) && menuStatus(225))
                                    <li data-position="{{ menuPosition(225) }}">
                                        <a href="{{ route('marks-grade') }}"> @lang('exam.marks_grade')</a>
                                    </li>
                                @endif
                                {{-- @if (userPermission(571) && menuStatus(571))
                                    <li  data-position="{{menuPosition(571)}}" >
                                        <a href="{{route('exam-time')}}"> @lang('exam.exam_time')</a>
                                    </li>
                                @endif --}}
                                @if (userPermission(208) && menuStatus(208))
                                    <li data-position="{{ menuPosition(208) }}">
                                        <a href="{{ route('exam-type') }}"> @lang('exam.exam_type')</a>
                                    </li>
                                @endif
                                @if (userPermission(214) && menuStatus(214))
                                    <li data-position="{{ menuPosition(214) }}">
                                        <a href="{{ route('exam') }}"> @lang('exam.exam_setup')</a>
                                    </li>
                                @endif
                                @if (userPermission(217) && menuStatus(217))
                                    <li data-position="{{ menuPosition(217) }}">
                                        <a href="{{ route('exam_schedule') }}"> @lang('exam.exam_schedule')</a>
                                    </li>
                                @endif
                                @if (userPermission(221) && menuStatus(221))
                                    <li data-position="{{ menuPosition(221) }}">
                                        <a href="{{ route('exam_attendance') }}"> @lang('exam.exam_attendance')</a>
                                    </li>
                                @endif
                                @if (userPermission(222) && menuStatus(222))
                                    <li data-position="{{ menuPosition(222) }}">
                                        <a href="{{ route('marks_register') }}"> @lang('exam.marks_register')</a>
                                    </li>
                                @endif
                                @if (userPermission(229) && menuStatus(229))
                                    <li data-position="{{ menuPosition(229) }}">
                                        <a href="{{ route('send_marks_by_sms') }}">
                                            @lang('exam.send_marks_by_sms')</a>
                                    </li>
                                @endif
                                @if (@generalSetting()->result_type == 'mark')
                                    @if (userPermission(3214) && menuStatus(3214))
                                        <li data-position="{{ menuPosition(3214) }}">
                                            <a href="{{ route('custom-marksheet-report') }}">
                                                @lang('exam.marksheet_report')
                                            </a>
                                        </li>
                                    @endif

                                    @if (userPermission(3215) && menuStatus(3215))
                                        <li data-position="{{ menuPosition(3215) }}">
                                            <a href="{{ route('subjectMarkSheet') }}">@lang('exam.subject_mark_sheet')</a>
                                        </li>
                                    @endif

                                    @if (userPermission(3216) && menuStatus(3216))
                                        <li data-position="{{ menuPosition(3216) }}">
                                            <a href="{{ route('finalMarkSheet') }}">@lang('exam.final_mark_sheet')</a>
                                        </li>
                                    @endif

                                    @if (userPermission(3217) && menuStatus(3217))
                                        <li data-position="{{ menuPosition(3217) }}">
                                            <a href="{{ route('studentFinalMarkSheet') }}">@lang('exam.student_final_mark_sheet')</a>
                                        </li>
                                    @endif

                                @endif
                                {{-- @if (userPermission(230))
                                    <li>
                                        <a href="{{route('question-group')}}">@lang('lang.question_group')</a>
                                    </li>
                                @endif
                                @if (userPermission(234))
                                    <li>
                                        <a href="{{route('question-bank')}}">@lang('lang.question_bank')</a>
                                    </li>
                                @endif
                                @if (userPermission(238))
                                    <li>
                                        <a href="{{route('online-exam')}}">@lang('onlineexam::onlineExam.online_exam')</a>
                                    </li>
                                @endif --}}
                                @if ((userPermission(436) && menuStatus(436)) || (userPermission(706) && menuStatus(706)))
                                    @php
                                        $examSettings = ['custom-result-setting', 'exam-settings'];
                                        $subMenuExam = array_merge(['lead.index'], $examSettings);
                                    @endphp
                                    <li>
                                        <a href="javascript:void(0)"
                                            class="has-arrow {{ spn_nav_item_open($subMenuExam, 'active') }}"
                                            aria-expanded="false">
                                            @lang('exam.settings')
                                        </a>
                                        <ul class="list-unstyled {{ spn_nav_item_open($subMenuExam, 'show') }}"
                                            id="examSettings">
                                            @if (userPermission(436) && menuStatus(436))
                                                <li data-position="{{ menuPosition(436) }}">
                                                    <a
                                                        href="{{ route('custom-result-setting') }}">@lang('exam.setup_exam_rule')</a>
                                                </li>
                                            @endif

                                            @if (userPermission(706) && menuStatus(706))
                                                <li data-position="{{ menuPosition(706) }}">
                                                    <a href="{{ route('exam-settings') }}">@lang('exam.format_settings')</a>
                                                </li>
                                            @endif

                                            <li>
                                                <a href="{{ route('exam-report-position') }}">@lang('exam.position')</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- online Exam --}}


                    {{-- Exam Seat Plan & Admit Card --}}
                    @if (moduleStatusCheck('ExamPlan'))
                        @include('examplan::menu')
                    @endif

                    {{-- Exam Seat Plan & Admit Card --}}

                    @if (moduleStatusCheck('OnlineExam') == true)
                        @include('onlineexam::menu_onlineexam')
                    @else
                        @if (userPermission(875) && menuStatus(875) && isMenuAllowToShow('online_exam'))
                            <li data-position="{{ menuPosition(875) }}" class="sortable_li">
                                <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                    <div class="nav_icon_small">
                                        <span class="fas fa-globe"></span>
                                    </div>
                                    <div class="nav_title">
                                        <span> @lang('exam.online_exam')</span>

                                    </div>
                                </a>
                                <ul class="list-unstyled" id="subMenuOnlineExam">
                                    @if (userPermission(230) && menuStatus(230))
                                        <li data-position="{{ menuPosition(230) }}">
                                            <a href="{{ route('question-group') }}">@lang('exam.question_group')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(234) && menuStatus(234))
                                        <li data-position="{{ menuPosition(234) }}">
                                            <a href="{{ route('question-bank') }}">@lang('exam.question_bank')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(238) && menuStatus(238))
                                        <li data-position="{{ menuPosition(238) }}">
                                            <a href="{{ route('online-exam') }}">@lang('exam.online_exam')</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif

                    {{-- HomeWork --}}
                    @if (userPermission(277) && menuStatus(277) && isMenuAllowToShow('home_work'))
                        <li data-position="{{ menuPosition(277) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">

                                    <span class="fas fa-book-open"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('homework.home_work')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuHomework">
                                @if (userPermission(278) && menuStatus(278))
                                    <li data-position="{{ menuPosition(278) }}">
                                        <a href="{{ route('add-homeworks') }}"> @lang('homework.add_homework')</a>
                                    </li>
                                @endif
                                @if (userPermission(280) && menuStatus(280))
                                    <li data-position="{{ menuPosition(280) }}">
                                        <a href="{{ route('homework-list') }}"> @lang('homework.homework_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(284) && menuStatus(284))
                                    <li data-position="{{ menuPosition(284) }}">
                                        <a href="{{ route('evaluation-report') }}">
                                            @lang('homework.evaluation_report')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Communicate --}}
                    @if (userPermission(286) && menuStatus(286) && isMenuAllowToShow('communicate'))
                        <li data-position="{{ menuPosition(286) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">

                                    <span class="fas fa-bullhorn"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('communicate.communicate')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuCommunicate">
                                @if (userPermission(287) && menuStatus(287))
                                    <li data-position="{{ menuPosition(287) }}">
                                        <a href="{{ route('notice-list') }}">@lang('communicate.notice_board')</a>
                                    </li>
                                @endif
                                @if (moduleStatusCheck('Saas') == true && auth()->user()->is_administrator != 'yes')
                                    <li>
                                        <a href="{{ route('administrator-notice') }}">@lang('communicate.administrator_notice')</a>
                                    </li>
                                @endif
                                @if (userPermission(291) && menuStatus(291))
                                    <li data-position="{{ menuPosition(291) }}">
                                        <a href="{{ route('send-email-sms-view') }}">@lang('communicate.send_email_\/_sms')</a>
                                    </li>
                                @endif
                                @if (userPermission(293) && menuStatus(293))
                                    <li data-position="{{ menuPosition(293) }}">
                                        <a href="{{ route('email-sms-log') }}">@lang('communicate.email_sms_log')</a>
                                    </li>
                                @endif
                                @if (userPermission(294) && menuStatus(294))
                                    <li data-position="{{ menuPosition(294) }}">
                                        <a href="{{ route('event') }}">@lang('communicate.event')</a>
                                    </li>
                                @endif
                                {{-- @if (moduleStatusCheck('Saas') == false) --}}
                                @if (userPermission(710) && menuStatus(710))
                                    <li data-position="{{ menuPosition(710) }}">
                                        <a
                                            href="{{ route('templatesettings.sms-template') }}">@lang('communicate.sms_template')</a>
                                    </li>
                                @endif
                                @if (userPermission(480) && menuStatus(480))
                                    <li data-position="{{ menuPosition(480) }}">
                                        <a href="{{ route('templatesettings.email-template') }}">
                                            @lang('communicate.email_template')
                                        </a>
                                    </li>
                                    {{-- @endif --}}
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Library --}}
                    @if (userPermission(298) && menuStatus(298) && isMenuAllowToShow('library'))
                        <li data-position="{{ menuPosition(298) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">

                                    <span class="flaticon-book-1"></span>
                                </div>
                                <div class="nav_title">
                                    <span> @lang('library.library')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenulibrary">
                                @if (userPermission(304) && menuStatus(304))
                                    <li data-position="{{ menuPosition(304) }}">
                                        <a href="{{ route('book-category-list') }}">
                                            @lang('library.book_category')</a>
                                    </li>
                                @endif
                                @if (userPermission(579) && menuStatus(579))
                                    <li data-position="{{ menuPosition(529) }}">
                                        <a href="{{ route('library_subject') }}"> @lang('library.subject')</a>
                                    </li>
                                @endif
                                @if (userPermission(299) && menuStatus(299))
                                    <li data-position="{{ menuPosition(299) }}">
                                        <a href="{{ route('add-book') }}"> @lang('library.add_book')</a>
                                    </li>
                                @endif
                                @if (userPermission(301) && menuStatus(301))
                                    <li data-position="{{ menuPosition(301) }}">
                                        <a href="{{ route('book-list') }}"> @lang('library.book_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(308) && menuStatus(308))
                                    <li data-position="{{ menuPosition(308) }}">
                                        <a href="{{ route('library-member') }}"> @lang('library.library_member')</a>
                                    </li>
                                @endif
                                @if (userPermission(311) && menuStatus(311))
                                    <li data-position="{{ menuPosition(311) }}">
                                        <a href="{{ route('member-list') }}"> @lang('library.member_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(314) && menuStatus(314))
                                    <li data-position="{{ menuPosition(314) }}">
                                        <a href="{{ route('all-issed-book') }}"> @lang('library.all_issued_book')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Inventory --}}
                    @if (userPermission(315) && menuStatus(315) && isMenuAllowToShow('inventory'))
                        <li data-position="{{ menuPosition(315) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">

                                    <span class="flaticon-inventory"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('inventory.inventory')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuInventory">
                                @if (userPermission(316) && menuStatus(316))
                                    <li data-position="{{ menuPosition(316) }}">
                                        <a href="{{ route('item-category') }}"> @lang('inventory.item_category')</a>
                                    </li>
                                @endif
                                @if (userPermission(320) && menuStatus(320))
                                    <li data-position="{{ menuPosition(320) }}">
                                        <a href="{{ route('item-list') }}"> @lang('inventory.item_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(324) && menuStatus(324))
                                    <li data-position="{{ menuPosition(324) }}">
                                        <a href="{{ route('item-store') }}"> @lang('inventory.item_store')</a>
                                    </li>
                                @endif
                                @if (userPermission(328) && menuStatus(328))
                                    <li data-position="{{ menuPosition(328) }}">
                                        <a href="{{ route('suppliers') }}"> @lang('inventory.supplier')</a>
                                    </li>
                                @endif
                                @if (userPermission(332) && menuStatus(332))
                                    <li data-position="{{ menuPosition(332) }}">
                                        <a href="{{ route('item-receive') }}"> @lang('inventory.item_receive')</a>
                                    </li>
                                @endif
                                @if (userPermission(334) && menuStatus(334))
                                    <li data-position="{{ menuPosition(334) }}">
                                        <a href="{{ route('item-receive-list') }}">
                                            @lang('inventory.item_receive_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(339) && menuStatus(339))
                                    <li data-position="{{ menuPosition(339) }}">
                                        <a href="{{ route('item-sell-list') }}"> @lang('inventory.item_sell')</a>
                                    </li>
                                @endif
                                @if (userPermission(345) && menuStatus(345))
                                    <li data-position="{{ menuPosition(345) }}">
                                        <a href="{{ route('item-issue') }}"> @lang('inventory.item_issue')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Transport --}}
                    @if (userPermission(348) && menuStatus(348) && isMenuAllowToShow('transport'))
                        <li data-position="{{ menuPosition(348) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">

                                    <span class="flaticon-bus"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('transport.transport')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuTransport">
                                @if (userPermission(349) && menuStatus(349))
                                    <li data-position="{{ menuPosition(349) }}">
                                        <a href="{{ route('transport-route') }}"> @lang('transport.routes')</a>
                                    </li>
                                @endif
                                @if (userPermission(353) && menuStatus(353))
                                    <li data-position="{{ menuPosition(353) }}">
                                        <a href="{{ route('vehicle') }}"> @lang('transport.vehicle')</a>
                                    </li>
                                @endif
                                @if (userPermission(357) && menuStatus(357))
                                    <li data-position="{{ menuPosition(357) }}">
                                        <a href="{{ route('assign-vehicle') }}"> @lang('transport.assign_vehicle')</a>
                                    </li>
                                @endif
                                @if (userPermission(361) && menuStatus(361))
                                    <li data-position="{{ menuPosition(361) }}">
                                        <a href="{{ route('student_transport_report') }}">
                                            @lang('transport.student_transport_report')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Dormitory --}}
                    @if (userPermission(362) && menuStatus(362) && isMenuAllowToShow('dormitory'))
                        <li data-position="{{ menuPosition(362) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-hotel"></span>
                                </div>
                                <div class="nav_title">
                                    <span> @lang('dormitory.dormitory')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuDormitory">
                                @if (userPermission(371) && menuStatus(371))
                                    <li data-position="{{ menuPosition(371) }}">
                                        <a href="{{ route('room-type') }}"> @lang('dormitory.room_type')</a>
                                    </li>
                                @endif
                                @if (userPermission(367) && menuStatus(367))
                                    <li data-position="{{ menuPosition(367) }}">
                                        <a href="{{ route('dormitory-list') }}"> @lang('dormitory.dormitory')</a>
                                    </li>
                                @endif
                                @if (userPermission(363) && menuStatus(363))
                                    <li data-position="{{ menuPosition(363) }}">
                                        <a href="{{ route('room-list') }}"> @lang('dormitory.dormitory_rooms')</a>
                                    </li>
                                @endif
                                @if (userPermission(375) && menuStatus(375))
                                    <li data-position="{{ menuPosition(375) }}">
                                        <a href="{{ route('student_dormitory_report') }}">
                                            @lang('dormitory.student_dormitory_report')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Reports --}}
                    @if (userPermission(376) && menuStatus(376) && isMenuAllowToShow('reports'))
                        <li data-position="{{ menuPosition(376) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="ti-agenda"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('reports.reports')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenusystemReports">
                                @if (userPermission(538) && menuStatus(538))
                                    <li data-position="{{ menuPosition(538) }}">
                                        <a href="{{ route('student_report') }}">@lang('reports.student_report')</a>
                                    </li>
                                @endif
                                @if (userPermission(377) && menuStatus(377))
                                    <li data-position="{{ menuPosition(377) }}">
                                        <a href="{{ route('guardian_report') }}">@lang('reports.guardian_report')</a>
                                    </li>
                                @endif
                                @if (userPermission(378) && menuStatus(378))
                                    <li data-position="{{ menuPosition(378) }}">
                                        <a href="{{ route('student_history') }}">@lang('reports.student_history')</a>
                                    </li>
                                @endif
                                @if (userPermission(379) && menuStatus(379))
                                    <li data-position="{{ menuPosition(379) }}">
                                        <a href="{{ route('student_login_report') }}">@lang('reports.student_login_report')</a>
                                    </li>
                                @endif
                                @if (generalSetting()->fees_status == 0)
                                    @if (userPermission(381) && menuStatus(381))
                                        <li data-position="{{ menuPosition(381) }}">
                                            <a href="{{ route('fees_statement') }}">@lang('reports.fees_statement')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(382) && menuStatus(382))
                                        <li data-position="{{ menuPosition(382) }}">
                                            <a href="{{ route('balance_fees_report') }}">@lang('reports.balance_fees_report')</a>
                                        </li>
                                    @endif
                                @endif

                                @if (!moduleStatusCheck('University'))
                                    @if (userPermission(384) && menuStatus(384))
                                        <li data-position="{{ menuPosition(384) }}">
                                            <a href="{{ route('class_report') }}">@lang('reports.class_report')</a>
                                        </li>
                                    @endif
                                @endif
                                @if (userPermission(385) && menuStatus(385))
                                    <li data-position="{{ menuPosition(385) }}">
                                        <a href="{{ route('class_routine_report') }}">@lang('reports.class_routine')</a>
                                    </li>
                                @endif
                                @if (!moduleStatusCheck('University'))
                                    @if (userPermission(386) && menuStatus(386))
                                        <li data-position="{{ menuPosition(386) }}">
                                            <a href="{{ route('exam_routine_report') }}">@lang('reports.exam_routine')</a>
                                        </li>
                                    @endif
                                @endif

                                @if (userPermission(388) && menuStatus(388))
                                    <li data-position="{{ menuPosition(388) }}">
                                        <a href="{{ route('merit_list_report') }}">@lang('reports.merit_list_report')</a>
                                    </li>
                                @endif
                                {{-- @if (userPermission(583))
                                    <li>
                                        <a href="{{route('custom-merit-list')}}">@lang('reports.custom_merit_list_report')</a>
                                    </li>
                                @endif --}}
                                @if (!moduleStatusCheck('University'))
                                    @if (userPermission(389) && menuStatus(389))
                                        <li data-position="{{ menuPosition(389) }}">
                                            <a href="{{ route('online_exam_report') }}">@lang('reports.online_exam_report')</a>
                                        </li>
                                    @endif
                                @endif
                                @if (userPermission(390) && menuStatus(390))
                                    <li data-position="{{ menuPosition(390) }}">
                                        <a href="{{ route('mark_sheet_report_student') }}">@lang('reports.mark_sheet_report')</a>
                                    </li>
                                @endif




                                @if (!moduleStatusCheck('University'))
                                    @if (userPermission(391) && menuStatus(391))
                                        <li data-position="{{ menuPosition(391) }}">
                                            <a href="{{ route('tabulation_sheet_report') }}">@lang('reports.tabulation_sheet_report')</a>
                                        </li>
                                    @endif
                                    @if (userPermission(392) && menuStatus(392))
                                        <li data-position="{{ menuPosition(392) }}">
                                            <a href="{{ route('progress_card_report') }}">@lang('reports.progress_card_report')</a>
                                        </li>
                                    @endif
                                @endif

                                @if (moduleStatusCheck('University'))
                                    <li data-position="">
                                        <a href="{{ route('university.exam-report') }}">
                                            @lang('university::un.exam_report')
                                        </a>
                                    </li>
                                @endif
                                {{-- @if (userPermission(584))
                                    <li>
                                        <a href="{{route('custom-progress-card')}}"> @lang('reports.custom_progress_card_report')</a>
                                    </li>
                                @endif --}}
                                {{-- @if (userPermission(393))
                                    <li>
                                        <a href="{{route('student_fine_report')}}">@lang('reports.student_fine_report')</a>
                                    </li>
                                @endif --}}
                                @if (userPermission(394) && menuStatus(394))
                                    <li data-position="{{ menuPosition(394) }}">
                                        <a href="{{ route('user_log') }}">@lang('reports.user_log')</a>
                                    </li>
                                @endif
                                @if (userPermission(539) && menuStatus(539))
                                    <li data-position="{{ menuPosition(539) }}">
                                        <a href="{{ route('previous-class-results') }}">@lang('reports.previous_result')
                                        </a>
                                    </li>
                                @endif
                                @if (userPermission(540) && menuStatus(540))
                                    <li data-position="{{ menuPosition(540) }}">
                                        <a href="{{ route('previous-record') }}">@lang('reports.previous_record') </a>
                                    </li>
                                @endif
                                {{-- New Client report start --}}
                                @if (auth()->user()->role_id == 1)
                                    @if (moduleStatusCheck('ResultReports') == true)
                                        {{-- ResultReports --}}
                                        <li>
                                            <a
                                                href="{{ route('resultreports/cumulative-sheet-report') }}">@lang('reports.cumulative_sheet_report')</a>
                                        </li>
                                        <li>
                                            <a
                                                href="{{ route('resultreports/continuous-assessment-report') }}">@lang('lang.contonuous_assessment_report')</a>
                                        </li>
                                        <li>
                                            <a
                                                href="{{ route('resultreports/termly-academic-report') }}">@lang('lang.termly_academic_report')</a>
                                        </li>
                                        <li>
                                            <a
                                                href="{{ route('resultreports/academic-performance-report') }}">@lang('lang.academic_performance_report')</a>
                                        </li>
                                        <li>
                                            <a
                                                href="{{ route('resultreports/terminal-report-sheet') }}">@lang('lang.terminal_report_sheet')</a>
                                        </li>
                                        <li>
                                            <a
                                                href="{{ route('resultreports/continuous-assessment-sheet') }}">@lang('lang.continuous_assessment_sheet')</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('resultreports/result-version-two') }}">@lang('lang.result_version')
                                                V2</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('resultreports/result-version-three') }}">@lang('lang.result_version')
                                                V3
                                            </a>
                                        </li>
                                        {{-- End New result result report --}}
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- UserManagement --}}
                    @if (userPermission(417) && menuStatus(417) && isMenuAllowToShow('role_permission'))
                        <li data-position="{{ menuPosition(417) }}" class="sortable_li">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="flaticon-authentication"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('rolepermission::role.role_&_permission')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenuUserManagement">
                                @if (userPermission(585) && menuStatus(585))
                                    <li data-position="{{ menuPosition(585) }}">
                                        <a href="{{ route('rolepermission/role') }}">@lang('rolepermission::role.role')</a>
                                    </li>
                                @endif
                                @if (userPermission(421) && menuStatus(421))
                                    <li data-position="{{ menuPosition(421) }}">
                                        <a href="{{ route('login-access-control') }}">@lang('rolepermission::role.login_permission')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- System Settings --}}
                    @if (userPermission(398) && menuStatus(398) && isMenuAllowToShow('system_settings'))
                        <li data-position="{{ menuPosition(398) }}" class="sortable_li metis_submenu_up_collaspe">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-cogs"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('system_settings.system_settings')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenusystemSettings">

                                @if (moduleStatusCheck('Saas') == true && auth()->user()->is_administrator == 'yes')
                                    @if (userPermission(405) && menuStatus(405))
                                        <li data-position="{{ menuPosition(405) }}">
                                            <a href="{{ route('school-general-settings') }}">
                                                @lang('system_settings.general_settings')</a>
                                        </li>
                                    @endif
                                @else
                                    @if (userPermission(405) && menuStatus(405))
                                        <li data-position="{{ menuPosition(405) }}">
                                            <a href="{{ route('general-settings') }}">
                                                @lang('system_settings.general_settings')</a>
                                        </li>
                                    @endif
                                @endif
                                {{-- @if (userPermission(417))
                                    <li>
                                        <a href="{{route('rolepermission/role')}}">@lang('system_settings.role')</a>
                                    </li>
                                @endif
                                @if (userPermission(421))
                                    <li>
                                        <a href="{{route('login-access-control')}}">@lang('system_settings.login_permission')</a>
                                    </li>
                                @endif --}}

                                @if (userPermission(424) && menuStatus(424))
                                    <li data-position="{{ menuPosition(424) }}">
                                        <a href="{{ route('class_optional') }}">@lang('system_settings.optional_subject')</a>
                                    </li>
                                @endif

                                @if (userPermission(121) && menuStatus(121))
                                    {{-- <li> <a href="{{route('base_group')}}">@lang('system_settings.base_group')</a> </li> --}}
                                @endif
                                @if (!moduleStatusCheck('University'))
                                    @if (userPermission(432) && menuStatus(432))
                                        <li data-position="{{ menuPosition(432) }}">
                                            <a href="{{ route('academic-year') }}">@lang('common.academic_year')</a>
                                        </li>
                                    @endif
                                @endif

                                @if (userPermission(440) && menuStatus(440))
                                    <li data-position="{{ menuPosition(440) }}">
                                        <a href="{{ route('holiday') }}">@lang('system_settings.holiday')</a>
                                    </li>
                                @endif

                                @if (userPermission(401) && menuStatus(401))
                                    <li data-position="{{ menuPosition(401) }}">
                                        <a href="{{ route('manage-currency') }}">@lang('system_settings.manage_currency')</a>
                                    </li>
                                @endif


                                @if (userPermission(428) && menuStatus(428))
                                    <li data-position="{{ menuPosition(428) }}">
                                        <a href="{{ route('base_setup') }}">@lang('system_settings.base_setup')</a>
                                    </li>
                                @endif


                                @if (userPermission(448) && menuStatus(448))
                                    <li data-position="{{ menuPosition(448) }}">
                                        <a href="{{ url('weekend') }}">@lang('system_settings.weekend')</a>
                                    </li>
                                @endif

                                @if (userPermission(451) && menuStatus(451))
                                    <li data-position="{{ menuPosition(451) }}">
                                        <a href="{{ route('language-settings') }}">@lang('system_settings.language_settings')</a>
                                    </li>
                                @endif

                                @if (userPermission(463) && menuStatus(463))
                                    <li data-position="{{ menuPosition(463) }}">
                                        <a href="{{ route('button-disable-enable') }}">@lang('system_settings.dashboard_setup')
                                        </a>
                                    </li>
                                @endif


                                @if (userPermission(412) && menuStatus(412))
                                    <li data-position="{{ menuPosition(412) }}">
                                        <a href="{{ route('payment-method-settings') }}">@lang('system_settings.payment_settings')</a>
                                    </li>
                                @endif

                                @if (userPermission(410) && menuStatus(410))
                                    <li data-position="{{ menuPosition(410) }}">
                                        <a href="{{ route('email-settings') }}">@lang('system_settings.email_settings')</a>
                                    </li>
                                @endif

                                @if (userPermission(444) && menuStatus(444))
                                    <li data-position="{{ menuPosition(444) }}">
                                        <a href="{{ route('sms-settings') }}">@lang('system_settings.sms_settings')</a>
                                    </li>
                                @endif
                                @if (moduleStatusCheck('Saas') and config('app.allow_custom_domain'))
                                    <li>
                                        <a href="{{ route('saas.custom-domain') }}">@lang('saas::saas.custom_domain')</a>
                                    </li>
                                @endif


                                {{-- SAAS DISABLE --}}
                                @if (moduleStatusCheck('Saas') == false)
                                    @include('backEnd.partials.without_saas_school_admin_menu')
                                @endif
                                @if (userPermission(2200) && menuStatus(2200))
                                    <li data-position="{{ menuPosition(2200) }}">
                                        <a href="{{ route('setting.preloader') }}">@lang('system_settings.Preloader Settings') </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- style --}}
                    {{--                 @if (moduleStatusCheck('Saas') == false) --}}
                    @if (userPermission(485) && menuStatus(485) && isMenuAllowToShow('style'))
                        <li data-position="{{ menuPosition(485) }}" class="sortable_li metis_submenu_up_collaspe">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="fas fa-bezier-curve"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('style.style')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenusystemStyle">
                                @if (userPermission(486) && menuStatus(486))
                                    <li data-position="{{ menuPosition(486) }}">
                                        <a href="{{ route('background-setting') }}">@lang('style.background_settings')</a>
                                    </li>
                                @endif
                                @if (userPermission(490) && menuStatus(490))
                                    <li data-position="{{ menuPosition(490) }}">
                                        <a href="{{ route('color-style') }}">@lang('style.color_theme')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- @endif --}}

                    {{-- Front Settings --}}
                    {{-- @if (moduleStatusCheck('Saas') == false) --}}
                    @if (userPermission(492) && menuStatus(492) && isMenuAllowToShow('front_setting'))
                        <li data-position="{{ menuPosition(492) }}" class="sortable_li metis_submenu_up_collaspe">
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="flaticon-software"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('front_settings.front_settings')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="subMenufrontEndSettings">
                                @if (userPermission(650) && menuStatus(650))
                                    <li data-position="{{ menuPosition(650) }}">
                                        <a href="{{ route('header-menu-manager') }}">@lang('front_settings.header_menu_manager')</a>
                                    </li>
                                @endif
                                @if (userPermission(493) && menuStatus(493))
                                    <li data-position="{{ menuPosition(498) }}">
                                        <a href="{{ route('admin-home-page') }}"> @lang('front_settings.home_page')
                                        </a>
                                    </li>
                                @endif
                                @if (userPermission(523) && menuStatus(523))
                                    <li data-position="{{ menuPosition(528) }}">
                                        <a href="{{ route('news-heading-update') }}">@lang('front_settings.news_heading')</a>
                                    </li>
                                @endif
                                @if (userPermission(500) && menuStatus(500))
                                    <li data-position="{{ menuPosition(500) }}">
                                        <a href="{{ route('news-category') }}">@lang('front_settings.news')
                                            @lang('student.category')</a>
                                    </li>
                                @endif
                                @if (moduleStatusCheck('AppSlider') == true)
                                    @if (userPermission(930) && menuStatus(930))
                                        <li data-position="{{ menuPosition(930) }}">
                                            <a href="{{ route('appslider.index') }}">@lang('front_settings.app_slider')</a>
                                        </li>
                                    @endif
                                @endif
                                @if (userPermission(495) && menuStatus(495))
                                    <li data-position="{{ menuPosition(495) }}">
                                        <a href="{{ route('news_index') }}">@lang('front_settings.news_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(525) && menuStatus(525))
                                    <li data-position="{{ menuPosition(525) }}">
                                        <a href="{{ route('course-heading-update') }}">@lang('front_settings.course_heading')</a>
                                    </li>
                                @endif
                                @if (userPermission(525) && menuStatus(525))
                                    <li data-position="{{ menuPosition(525) }}">
                                        <a href="{{ route('course-details-heading') }}">@lang('front_settings.course_details_heading')</a>
                                    </li>
                                @endif
                                @if (userPermission(673) && menuStatus(673))
                                    <li data-position="{{ menuPosition(673) }}">
                                        <a href="{{ route('course-category') }}">@lang('front_settings.course_category')</a>
                                    </li>
                                @endif
                                @if (userPermission(509) && menuStatus(509))
                                    <li data-position="{{ menuPosition(509) }}">
                                        <a href="{{ route('course-list') }}">@lang('front_settings.course_list')</a>
                                    </li>
                                @endif
                                @if (userPermission(504) && menuStatus(504))
                                    <li data-position="{{ menuPosition(504) }}">
                                        <a href="{{ route('testimonial_index') }}">@lang('front_settings.testimonial')</a>
                                    </li>
                                @endif
                                @if (userPermission(514) && menuStatus(514))
                                    <li data-position="{{ menuPosition(514) }}">
                                        <a href="{{ route('conpactPage') }}">@lang('front_settings.contact_page') </a>
                                    </li>
                                @endif
                                @if (userPermission(517) && menuStatus(517))
                                    <li data-position="{{ menuPosition(517) }}">
                                        <a href="{{ route('contactMessage') }}">@lang('front_settings.contact_message')</a>
                                    </li>
                                @endif
                                @if (userPermission(520) && menuStatus(520))
                                    <li data-position="{{ menuPosition(520) }}">
                                        <a href="{{ route('about-page') }}"> @lang('front_settings.about_us') </a>
                                    </li>
                                @endif
                                @if (userPermission(529) && menuStatus(529))
                                    <li data-position="{{ menuPosition(529) }}">
                                        <a href="{{ route('social-media') }}"> @lang('front_settings.social_media')
                                        </a>
                                    </li>
                                @endif
                                @if (userPermission(654) && menuStatus(654))
                                    <li data-position="{{ menuPosition(654) }}">
                                        <a href="{{ route('page-list') }}">@lang('front_settings.pages')</a>
                                    </li>
                                @endif
                                @if (userPermission(527) && menuStatus(527))
                                    <li data-position="{{ menuPosition(527) }}">
                                        <a href="{{ route('custom-links') }}"> @lang('front_settings.footer_widget')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- @endif --}}

                    {{-- Ticket --}}
                    @if (moduleStatusCheck('Saas') == true && auth()->user()->is_administrator != 'yes')
                        <li>
                            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                <div class="nav_icon_small">
                                    <span class="flaticon-settings"></span>
                                </div>
                                <div class="nav_title">
                                    <span>@lang('saas::saas.ticket_system')</span>

                                </div>
                            </a>
                            <ul class="list-unstyled" id="Ticket">
                                <li>
                                    <a href="{{ route('school/ticket-view') }}">@lang('saas::saas.ticket_list')</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Zoom Menu -->
                    @if (moduleStatusCheck('Zoom') == true)
                        @include('zoom::menu.Zoom')
                    @endif
                    @if (moduleStatusCheck('Gmeet') == true)
                        @include('gmeet::menu')
                    @endif

                    <!-- Zoom Menu -->


                    <!-- BBB Menu -->
                    @if (moduleStatusCheck('BBB') == true)
                        @include('bbb::menu.bigbluebutton_sidebar')
                    @endif

                    <!-- Jitsi Menu -->
                    @if (moduleStatusCheck('Jitsi') == true)
                        @include('jitsi::menu.jitsi_sidebar')
                    @endif

                @endif

                <!-- Student Panel -->
                @if (auth()->user()->role_id == 2)
                    @include('backEnd/partials/student_sidebar')
                @endif

                <!-- Parents Panel Menu -->
                @if (auth()->user()->role_id == 3)
                    @include('backEnd/partials/parents_sidebar')
                @endif
            @endif
        </ul>
    @endif
</nav>
<!-- sidebar part end -->
