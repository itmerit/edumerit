<?php
    if (Auth::user() == "") { header('location:' . url('/login')); exit(); }

    Session::put('permission', App\GlobalVariable::GlobarModuleLinks());

    if(Module::find('FeesCollection')){
        $module = Module::find('FeesCollection');
        $module_name = @$module->getName();
        $module_status = @$module->isDisabled();
    }else{
        $module_name =NULL;
        $module_status =TRUE;
    }
?>
<input type="hidden" name="url" id="url" value="{{url('/')}}">
<nav id="sidebar">
    <div class="sidebar-header update_sidebar">
        <a href="{{url('/')}}">
            <img  src="{{ file_exists(@generalSetting()->logo) ? asset(generalSetting()->logo) : asset('public/uploads/settings/logo.png') }}" alt="logo">
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>
    </div>
    {{-- {{ Auth::user()->role_id }} --}}
    <ul class="list-unstyled components">
 

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
        <a href="#subMenuStudentRegistration" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
           
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
                </div>
                <div class="nav_title">
                    @lang('student.registration')
                </div>
        </a>
        <ul class="collapse list-unstyled" id="subMenuStudentRegistration">
           
            <li>
                <a href="{{route('parentregistration/saas-student-list')}}"> @lang('common.student_list')</a>
            </li>
            <li>
                <a href="{{route('parentregistration/settings')}}"> @lang('system_settings.settings')</a>
            </li>
        </ul>
    </li>


    <li>
        <a href="#subMenuAdministrator" data-toggle="collapse" aria-expanded="false"
            class="dropdown-toggle">
            
           
            
            <div class="nav_icon_small">
                <span class="flaticon-analytics"></span>
                </div>
                <div class="nav_title">
                    @lang('system_sttings.institution')
                </div>
        </a>
        <ul class="collapse list-unstyled" id="subMenuAdministrator">
            <li>
                <a href="{{route('administrator/institution-list')}}">@lang('system_sttings.institution_list')</a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#subMenuSubscription" data-toggle="collapse" aria-expanded="false"
            class="dropdown-toggle">
           
           
            <div class="nav_icon_small">
                <span class="flaticon-analytics"></span>
                </div>
                <div class="nav_title">
                    @lang('common.subscription')
                </div>
        </a>
        <ul class="collapse list-unstyled" id="subMenuSubscription">
            <li>
                <a href="{{route('subscription/packages')}}">@lang('common.package')</a>
            </li>
            <li>
                <a href="{{route('subscription/payment-method')}}">@lang('accounts.payment_method')</a>
            </li>
            <li>
                <a href="{{route('subscription/payment-method-setting')}}">@lang('accounts.payment_method_setting')</a>
            </li>
            <li>
                <a href="{{route('subscription/settings')}}"> @lang('system_settings.settings')</a>
            </li>


            <li>
                <a href="{{route('TrailInstitution')}}"> @lang('common.trial_institutions')</a>
            </li>


            <li>
                <a href="{{route('subscription/school-payments')}}">  @lang('accounts.all_payments')</a>
            </li>

            <li>
                <a href="{{route('subscription/payment-history')}}">  @lang('accounts.payment_history')</a>
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
        
        <div class="nav_icon_small">
            <span class="flaticon-email"></span>
            </div>
            <div class="nav_title">
                @lang('communicate.communicate')
            </div>
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
        <li><a href="{{route('administrator/teacher-list')}}">@lang('hr.teacher_list')</a></li>
        <li><a href="{{route('administrator/class-list')}}">@lang('common.class_list')</a></li>
        <li><a href="{{route('administrator/class-routine')}}">@lang('common.class_routine')</a></li>
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
       
       
        <div class="nav_icon_small">
            <span class="flaticon-settings"></span>
            </div>
            <div class="nav_title">
                @lang('system_settings.system_settings')
            </div>
    </a>
    <ul class="collapse list-unstyled" id="subMenusystemSettings">
        
            <li>
                <a href="{{route('manage-adons')}}">@lang('system_settings.module_manager')</a>
            </li>
            <li>
                <a href="{{route('administrator/general-settings')}}"> @lang('system_settings.general_settings')</a>
            </li>
         
            <li>
                <a href="{{route('administrator/email-settings')}}">@lang('system_settings.email_settings')</a>
            </li>
            <li>
                <a href="{{route('sms-settings')}}">@lang('system_settings.sms_settings')</a>
            </li>
    
            <li>
                <a href="{{route('administrator/manage-currency')}}">@lang('system_settings.manage-currency')</a>
            </li>
            
        
            {{-- <li>
                <a href="{{url('payment-method-settings')}}">@lang('system_settings.payment_method_settings')</a>
            </li> --}}
        
        
            {{-- <li>
                <a href="{{url('role')}}">@lang('system_settings.role')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{ url('administrator/module-permission')}}">@lang('system_settings.module_permission')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('login-access-control')}}">@lang('system_settings.login_permission')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('administrator/base-group')}}">@lang('system_settings.base_group')</a>
            </li> --}}
        
            <li>
                <a href="{{route('base_setup')}}">@lang('system_settings.base_setup')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('academic-year')}}">@lang('common.academic_year')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('session')}}">@lang('system_settings.session')</a>
            </li> --}}
            {{-- <li>
                <a href="{{url('sms-settings')}}">@lang('system_settings.sms_settings')</a>
            </li> --}}

            @if(moduleStatusCheck(152)) 
                            <li>
                    <a href="{{route('payment_method')}}"> @lang('system_settings.payment_method')</a>
                </li>
            @endif

            <li>
                <a href="{{route('payment-method-settings')}}">@lang('system_settings.payment_method_settings')</a>
            </li>
            {{-- <li>
                <a href="{{url('email-settings')}}">@lang('system_settings.email_settings')</a>
            </li> --}}
            <li>
                <a href="{{route('language-list')}}">@lang('system_settings.language')</a>
            </li>
            <li>
                <a href="{{route('administrator/language-settings')}}">@lang('system_settings.language_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/backup-settings')}}">@lang('system_settings.backup_settings')</a>
            </li>
            <li>
                <a href="{{route('button-disable-enable')}}">@lang('system_settings.button_manage') </a>
            </li>
            <li>
                <a href="{{route('templatesettings.email-template')}}">@lang('common.email_template')</a>
            </li>
            <li>
                <a href="{{route('sms-template')}}">@lang('system_settings.sms_template')</a>
            </li>
            <li>
                <a href="{{route('about-system')}}">@lang('system_settings.about')</a>
            </li>
            <li>
                <a href="{{route('update-system')}}">@lang('common.update')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('administrator/update-system')}}">@lang('system_settings.update_system')</a>
            </li> --}}
        
        {{-- @if(Auth::user()->role_id == 1)
            <li>
                <a href="{{url('administrator/admin-data-delete')}}">@lang('lang.SampleDataEmpty')</a>
            </li>
        @endif --}}
    
    </ul>
    </li>
    
    
    
    
                    <li>
                        <a href="#subMenusystemStyle" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            
                            
                            <div class="nav_icon_small">
                                <span class="flaticon-settings"></span>
                                </div>
                                <div class="nav_title">
                                    @lang('style.style')
                                </div>
                        </a>
                        <ul class="collapse list-unstyled" id="subMenusystemStyle">
                                <li>
                                    <a href="{{route('background-setting')}}">@lang('style.background_settings')</a>
                                    {{-- <a href="{{route('administrator/background-setting')}}">@lang('style.background_settings')</a> --}}
                                </li>
                                <li>
                                    <a href="{{route('color-style')}}">@lang('style.color_theme')</a>
                                    {{-- <a href="{{url('administrator/color-style')}}">@lang('style.color_theme')</a> --}}
                                </li>
                        </ul>
                    </li>
    
                    <li>
                        <a href="#subMenuApi" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                          
                            
                            <div class="nav_icon_small">
                                <span class="flaticon-settings"></span>
                                </div>
                                <div class="nav_title">
                                    @lang('virtual_meeting.api_permission')
                                </div>
                        </a>
                        <ul class="collapse list-unstyled" id="subMenuApi">
                                <li>
                                    <a href="{{route('administrator/api/permission')}}">@lang('virtual_meeting.api_permission') </a>
                                </li>
                        </ul>
                    </li>
    
    
    
                    {{-- <li>
                        <a href="#subMenufrontEndSettings" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-software"></span>
                            @lang('lang.front_settings')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenufrontEndSettings">
                            <li>
                                <a href="{{url('admin-home-page')}}"> @lang('lang.home_page') </a>
                            </li>
    
                            <li>
                                <a href="{{url('news')}}">@lang('lang.news_list')</a>
                            </li>
                            <li>
                                <a href="{{url('news-category')}}">@lang('lang.news') @lang('student.category')</a>
                            </li>
                            <li>
                                <a href="{{url('testimonial')}}">@lang('lang.testimonial')</a>
                            </li>
                            <li>
                                <a href="{{url('course-list')}}">@lang('lang.course_list')</a>
                            </li>
                            <li>
                                <a href="{{url('contact-page')}}">@lang('lang.contact_page') </a>
                            </li>
                            <li>
                                <a href="{{url('contact-message')}}">@lang('lang.contact_message')</a>
                            </li>
                            <li>
                                <a href="{{url('about-page')}}"> @lang('lang.about_us') </a>
                            </li>
                            <li>
                                <a href="{{url('custom-links')}}"> @lang('lang.custom_links') </a>
                            </li>
                        </ul>
                    </li> --}}
    
    
    <li>
    <a href="#Ticket" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
     
        
          
        <div class="nav_icon_small">
            <span class="flaticon-settings"></span>
            </div>
            <div class="nav_title">
                @lang('system_settings.ticket_system')
            </div>
    </a>
    <ul class="collapse list-unstyled" id="Ticket">
        <li><a href="{{ route('ticket-category') }}"> @lang('system_settings.ticket_category')</a></li>
        <li><a href="{{ route('ticket-priority') }}">@lang('system_settings.ticket_priority')</a></li>
        <li><a href="{{ route('admin/ticket-view') }}">@lang('system_settings.ticket_list')</a> </li> 
    </ul>
    </li>
    
    {{-- SAAS -302 --}}


    
    
    
    

       
        
        
        
        
            


    </ul>
</nav>
