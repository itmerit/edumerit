@extends('backEnd.master')
@section('title') 
@lang('auth.two_factor_setting') 
@endsection

@section('css')
<style>
    .copyPaperShow {
        display: show;
    }
    </style>
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('auth.two_factor_setting') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.general_settings')</a>   
                <a href="#">@lang('auth.two_factor_setting') </a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('two_factor_auth_setting_update') }}" method="POST">
                    @csrf
                    <div class="white-box">
                            <div class="row p-0">
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('auth.two_factor_setting')</h3>
                                    <hr>
                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('auth.two_factor_otp')</p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="d-flex radio-btn-flex"> 

                                                       <div class="mr-30">
                                                            <input type="radio" name="two_factor" value="1" id="two_factor_on" class="common-radio relationButton copy_per_th" {{generalSetting()->two_factor == 1? 'checked':''}}>
                                                            <label for="two_factor_on" id="two_factor_on">@lang('common.enable')</label>
                                                        </div>

                                                        <div class="mr-30">
                                                            <input type="radio" name="two_factor" value="0" id="two_factor_of"  class="common-radio relationButton copy_per_th" {{generalSetting()->two_factor == 0? 'checked':''}}>
                                                            <label for="two_factor_of" id="two_factor_of">@lang('common.disable')</label>
                                                        </div>                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('auth.send_code_via')</p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="d-flex radio-btn-flex"> 

                                                       <div class="mr-30">
                                                            <input type="checkbox" name="via_sms" id="via_sms" class="common-radio relationButton copy_per_th" {{@$setting->via_sms == 1? 'checked':''}}>
                                                            <label for="via_sms" id="via_sms">@lang('common.sms')</label>
                                                        </div>

                                                        <div class="mr-30">
                                                            <input type="checkbox" name="via_email" id="via_email"  class="common-radio relationButton copy_per_th" {{@$setting->via_email == 1? 'checked':''}}>
                                                            <label for="via_email" id="via_email">@lang('admin.email')</label>
                                                        </div>                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>                                 

                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                             <div class="row">
                                                 <div class="col-lg-3 d-flex">
                                                     <p class="text-uppercase fw-500 mb-10">@lang('auth.applicable_for')</p>
                                                 </div>
                                                 <div class="col-lg-9">
                                                     <div class="d-flex radio-btn-flex"> 
                                                        <div class="mr-30">
                                                             <input type="checkbox" name="for_admin" id="for_admin" class="common-radio relationButton copy_per_th" {{@$setting->for_admin == 1? 'checked':''}}>
                                                             <label for="for_admin" id="for_admin">@lang('admin.admin')</label>
                                                         </div>
                                                         <div class="mr-30">
                                                             <input type="checkbox" name="for_student" id="for_student"  class="common-radio relationButton copy_per_th" {{@$setting->for_student == 2? 'checked':''}}>
                                                             <label for="for_student" id="for_student">@lang('student.student')</label>
                                                         </div>
                                                         <div class="mr-30">
                                                             <input type="checkbox" name="for_parent" id="for_parent" class="common-radio relationButton copy_per_th" {{@$setting->for_parent == 3? 'checked':''}}>
                                                             <label for="for_parent" id="for_parent">@lang('student.parents')</label>
                                                         </div>
                                                         <div class="mr-30">
                                                             <input type="checkbox" name="for_teacher"  id="for_teacher" class="common-radio relationButton copy_per_th" {{@$setting->for_teacher == 4? 'checked':''}}>
                                                             <label for="for_teacher" id="for_teacher">@lang('common.teacher')</label>
                                                         </div> 
                                                         <div class="mr-30">
                                                             <input type="checkbox" name="for_staff" id="for_staff"  class="common-radio relationButton copy_per_th" {{@$setting->for_staff == 6? 'checked':''}}>
                                                             <label for="for_staff" id="for_staff">@lang('common.staff')</label>
                                                         </div>                                           
                                                     </div>
                                                 </div>
                                             </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                 <div class="col-lg-5 d-flex">
                                                      <label class="primary_input_label text-uppercase fw-500" for="">@lang('auth.code_lifetime') <span>(second)</span></label>
                                                  </div>
                                                  <div class="col-lg-7">
                                                      <div class="primary_input ">
                                                           <input class="primary_input_field form-control{{ $errors->has('expired_time') ? ' is-invalid' : '' }}"   type="number" name="expired_time"  id="expired_time" value="{{$setting->expired_time}}">
                                                          @if ($errors->has('expired_time'))
                                                          <span class="text-danger invalid-select" role="alert">
                                                              {{ $errors->first('expired_time') }}</span>
                                                          @endif
                                                       </div>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(userPermission('two_factor_auth_setting'))
                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    @lang('common.update')
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


@section('script')
<script>
 
</script>
@endsection
@endsection

