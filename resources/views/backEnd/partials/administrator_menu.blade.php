

<li>

    @if(Auth::user()->role_id == 1)
        <a href="{{route('superadmin-dashboard')}}" id="admin-dashboard">
    @else
        <a href="{{route('admin-dashboard')}}" id="admin-dashboard">
    @endif
    
        <span class="flaticon-speedometer"></span>
        @lang('common.dashboard')
    </a>
    </li>
    <li>
    <a href="#subMenuAdministrator" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-analytics"></span>
        @lang('common.institution')
        
    </a>
    <ul class="collapse list-unstyled" id="subMenuAdministrator">
        <li>
            <a href="{{route('administrator/institution-list')}}">@lang('common.institution_list')</a>
        </li>
    </ul>
    </li>
    
    
    
    {{-- <li>
    <a href="#subMenuPackages" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-analytics"></span>
        @lang('lang.packages')
    </a>
    <ul class="collapse list-unstyled" id="subMenuPackages">
        <li>
            <a href="{{url('administrator/package-list')}}"> @lang('lang.package_list')</a>
        </li>
    </ul>
    </li>
    
    <li>
    <a href="#subMenuInfixInvoice" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-accounting"></span> Invoice </a>
    <ul class="collapse list-unstyled" id="subMenuInfixInvoice">
        <li><a href="{{url('infix/invoice-create')}}">Invoice Create</a></li>
        <li><a href="{{url('infix/invoice-list')}}">Invoice list</a></li>
        <li><a href="{{url('infix/invoice-category')}}">Invoice Category</a></li>
        <li><a href="{{url('infix/invoice-setting')}}">Invoice Setting</a></li>
    
    </ul>
    </li> --}}
    
    <li>
    <a href="#subMenuCommunicate" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-email"></span>
        @lang('communicate.communicate')
    </a>
    <ul class="collapse list-unstyled" id="subMenuCommunicate">
        <li>
            <a href="{{route('administrator/send-mail')}}">@lang('communicate.send_mail')</a>
            <a href="{{route('administrator/send-sms')}}">@lang('communicate.send_sms')</a>
            <a href="{{route('administrator/send-notice')}}">@lang('communicate.send_notice')</a>
        </li>
    </ul>
    </li>
    
    <li>
    <a href="#subMenuInfixInvoice" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-accounting"></span> Reports </a>
    <ul class="collapse list-unstyled" id="subMenuInfixInvoice">
        <li><a href="{{route('administrator/student-list')}}">@lang('common.student_list')</a></li>
        <li><a href="{{route('administrator/income-expense')}}">@lang('accounts.date_to')/@lang('accounts.expense')</a></li>
        <li><a href="{{route('administrator/teacher-list')}}">@lang('common.teacher_list')</a></li>
        <li><a href="{{route('administrator/class-list')}}">@lang('common.class_list')</a></li>
        <li><a href="{{route('administrator/class-routine')}}">@lang('academics.class_routine')</a></li>
        <li><a href="{{route('administrator/student-attendance')}}">@lang('student.student_attendance')</a></li>
        <li><a href="{{route('administrator/staff-attendance')}}">@lang('hr.staff_attendance')</a></li>
        <li><a href="{{route('administrator/merit-list-report')}}">@lang('exam.merit_list_report')</a></li>
        <li><a href="{{route('saas_mark_sheet_report_student')}}">@lang('exam.mark_sheet_report')</a></li>
        <li><a href="{{url('administrator/tabulation-sheet-report')}}">@lang('reports.tabulation_sheet_report')</a></li>
    
        <li><a href="{{route('administrator/progress-card-report')}}">Progress Card Report</a></li>
    </ul>
    </li>
    <li>
    <a href="#subMenusystemSettings" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-settings"></span>
        @lang('system_settings.system_settings')
    </a>
    <ul class="collapse list-unstyled" id="subMenusystemSettings">
        
            <li>
                <a href="{{route('administrator/general-settings')}}"> @lang('system_settings.general_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/email-settings')}}">@lang('system_settings.email_settings')</a>
            </li>
    
            <li>
                <a href="{{route('administrator/manage-currency')}}">@lang('system_settings.manage-currency')</a>
            </li>
            
        
            {{-- <li>
                <a href="{{url('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
            </li> --}}
        
        
            {{-- <li>
                <a href="{{route('role')}}">@lang('lang.role')</a>
            </li> --}}
        
            <li>
                <a href="{{ route('administrator/module-permission')}}">@lang('system_settings.module_permission')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('login-access-control')}}">@lang('system_settings.login_permission')</a>
            </li> --}}
        
            <li>
                <a href="{{route('base_group')}}">@lang('system_settings.base_group')</a>
            </li>
        
            <li>
                <a href="{{route('base_setup')}}">@lang('system_settings.base_setup')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('academic-year')}}">@lang('common.academic_year')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('session')}}">@lang('lang.session')</a>
            </li> --}}
            {{-- <li>
                <a href="{{url('sms-settings')}}">@lang('lang.sms_settings')</a>
            </li> --}}
            <li>
                <a href="{{route('administrator/language-settings')}}">@lang('system_settings.language_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/backup-settings')}}">@lang('system_settings.backup_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/update-system')}}">@lang('system_settings.update_system')</a>
            </li>
        
        @if(Auth::user()->role_id == 1)
            <li>
                <a href="{{route('administrator/admin-data-delete')}}">@lang('system_settings.SampleDataEmpty')</a>
            </li>
        @endif
    
    </ul>
    </li>
    
    
    
    
                    <li>
                        <a href="#subMenusystemStyle" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-settings"></span>
                            @lang('style.style')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenusystemStyle">
                                <li>
                                    <a href="{{route('administrator/background-setting')}}">@lang('style.background_settings')</a>
                                </li>
                                <li>
                                    <a href="{{route('administrator/color-style')}}">@lang('style.color_theme')</a>
                                </li>
                        </ul>
                    </li>
    
                    <li>
                        <a href="#subMenuApi" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-settings"></span>
                            @lang('system_settings.api_permission')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenuApi">
                                <li>
                                    <a href="{{route('administrator/api/permission')}}">@lang('system_settings.api_permission') </a>
                                </li>
                        </ul>
                    </li>
    
    
    
                    <li>
                        <a href="#subMenufrontEndSettings" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-software"></span>
                            @lang('front_settings.front_settings')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenufrontEndSettings">
                            <li>
                                <a href="{{route('admin-home-page')}}"> @lang('front_settings.home_page') </a>
                            </li>
    
                            <li>
                                <a href="{{route('news')}}">@lang('front_settings.news_list')</a>
                            </li>
                            <li>
                                <a href="{{route('news-category')}}">@lang('front_settings.news_category')</a>
                            </li>
                            <li>
                                <a href="{{route('testimonial')}}">@lang('front_settings.testimonial')</a>
                            </li>
                            <li>
                                <a href="{{route('course-list')}}">@lang('front_settings.course_list')</a>
                            </li>
                            <li>
                                <a href="{{route('conpactPage')}}">@lang('front_settings.contact_page') </a>
                            </li>
                            <li>
                                <a href="{{route('contactMessage')}}">@lang('front_settings.contact_message')</a>
                            </li>
                            <li>
                                <a href="{{route('about-page')}}"> @lang('front_settings.about_us') </a>
                            </li>
                            <li>
                                <a href="{{route('custom-links')}}"> @lang('front_settings.custom_links') </a>
                            </li>
                        </ul>
                    </li>
    
    
    <li>
    <a href="#Ticket" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-settings"></span>
        @lang('system_settings.ticket_system')
    </a>
    <ul class="collapse list-unstyled" id="Ticket">
        <li><a href="{{ route('ticket.category') }}"> @lang('system_settings.ticket_category')</a></li>
        <li><a href="{{ route('ticket.priority') }}">@lang('system_settings.ticket_priority')</a></li>
        <li><a href="{{ route('admin.ticket_list') }}">@lang('system_settings.ticket_list')</a>
        </li>
    </ul>
    </li>
    
    {{-- SAAS -302 --}}
    
    
    
    

       
        
        
        
        
            