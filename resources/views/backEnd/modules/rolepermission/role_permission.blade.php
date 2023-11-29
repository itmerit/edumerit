@extends('backEnd.master')
@section('title')
@lang('system_settings.role_permission') 
@endsection
@section('mainContent')
<link rel="stylesheet" href="{{asset('/Modules/RolePermission/public/css/style.css')}}">
<style type="text/css">
    .erp_role_permission_area {
    display: block !important;
}

.single_permission {
    margin-bottom: 0px;
}
.erp_role_permission_area .single_permission .permission_body > ul > li ul {
    display: grid;
    margin-left: 8px;
    grid-template-columns: repeat(3, 1fr);
    /* grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); */
}
.erp_role_permission_area .single_permission .permission_body > ul > li ul li {
    margin-right: 15px;
   
}
.mesonary_role_header{
    column-count: 2;
    column-gap: 30px;
}
.single_role_blocks {
    display: inline-block;
    background: #fff;
    box-sizing: border-box;
    width: 100%;
    margin: 0 0 5px;
}
.erp_role_permission_area .single_permission .permission_body > ul > li {
  padding: 15px 25px 12px 25px;
}
.erp_role_permission_area .single_permission .permission_header {
  padding: 20px 25px 11px 25px;
  position: relative;
}
@media (min-width: 320px) and (max-width: 1199.98px) { 
    .mesonary_role_header{
    column-count: 1;
    column-gap: 30px;
}
 }
@media (min-width: 320px) and (max-width: 767.98px) { 
    .erp_role_permission_area .single_permission .permission_body > ul > li ul {
    grid-template-columns: repeat(2, 1fr);
    grid-gap:10px
    /* grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); */
    }
 }
.permission_header{
    position: relative;
}

.arrow::after {
    position: absolute;
    content: "\e622";
    top: 50%;
    right: 12px;
    height: auto;
    font-family: 'themify';
    color: #fff;
    font-size: 18px;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    right: 22px;
}
.arrow.collapsed::after {
    content: "\e61a";
    color: #fff;
    font-size: 18px;
}
.erp_role_permission_area .single_permission .permission_header div {
    position: relative;
    top: -5px;
    position: relative;
    z-index: 999;
}
.erp_role_permission_area .single_permission .permission_header div.arrow {
    position: absolute;
    width: 100%;
    z-index: 0;
    left: 0;
    bottom: 0;
    top: 0;
    right: 0;
}
.erp_role_permission_area .single_permission .permission_header div.arrow i{
    color:#FFF;
    font-size: 20px;
}
.rtl .arrow::after {
    right: auto;
    left: 22px;
}
.rtl .common-radio:empty ~ label{
    float: right !important;
}

.rtl .erp_role_permission_area .single_permission .permission_body > ul > li ul li {
    margin-right: 0;
}
.rtl .erp_role_permission_area .single_permission .permission_body > ul > li ul label {
	
	white-space: nowrap;
}

</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.role_permission') </h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.role_permission')</a> 
            </div>
        </div>
    </div>
</section>
<div class="role_permission_wrap">
    <div class="permission_title">
        <h4>Assign Permission ({{@$role->name}})</h4>
    </div>
</div>

<div class="erp_role_permission_area ">
        <!-- single_permission  -->
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'rolepermission/role-permission-assign',
                        'method' => 'POST']) }}
                        <input type="hidden" name="role_id" value="{{@$role->id}}">
                        <div  class="mesonary_role_header">
	@foreach($all_modules as $key => $row)
    @php
        if (moduleStatusCheck('SaasRolePermission') == TRUE) {
            $module_info = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('active_status', 1)->first();
            $all_group_modules = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('id', '!=', $key)->where('active_status', 1)->get();
        } else {
            $module_info = Modules\RolePermission\Entities\InfixModuleInfo::where('parent_id',0)->where('module_id', $key)->where('is_saas',0)->where('active_status', 1)->first();
            $all_group_modules = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('id', '!=', $key)->where('is_saas',0)->where('active_status', 1)->get();
        }
        $check_all = 1;
        foreach($all_group_modules as $all_group_module){

            if(in_array($all_group_module->id, $already_assigned)){
                $check_all = 1;
            }else{
                $check_all = 0;
            }
        }
        $all_group_modules = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('id', '!=', $key)->where('active_status', 1)->get();
        $check_all = 0;
        foreach($all_group_modules as $all_group_module){
            if(!in_array($all_group_module->id, $already_assigned)){
                $check_all++;
            }
        }
    @endphp
    <div class="single_role_blocks">
        <div class="single_permission" id="{{$module_info->id}}">
            <div class="permission_header d-flex align-items-center justify-content-between">
                <div>
                    <input type="checkbox" name="module_id[]" value="{{$module_info->id}}" id="Main_Module_{{$key}}" class="common-radio permission-checkAll main_module_id_{{$module_info->id}}" {{in_array($module_info->id,$already_assigned)? 'checked':''}}>
                    <label for="Main_Module_{{$key}}">{{$module_info->name}}</label>
                </div>
                <div class="arrow collapsed" data-toggle="collapse" data-target="#Role{{$module_info->id}}"></div>
            </div>
            <div id="Role{{$module_info->id}}" class="collapse">
                <div  class="permission_body">
                    <ul>
                            <?php 
                                $subModule= DB::table('infix_module_infos')->where('parent_id',$module_info->id)->where('active_status', 1)->get();
                            ?>
                            @foreach($subModule as $row2)
                            @if(moduleStatusCheck('Saas') == TRUE && $row2->id == 547)
                            @else
                        <li>
                            <div class="submodule mb-2">
                                <input id="Sub_Module_{{$row2->id}}" name="module_id[]" value="{{$row2->id}}"  class="infix_csk common-radio module_id_{{$module_info->id}} module_link"  type="checkbox" {{in_array($row2->id ,$already_assigned)? 'checked':''}}>
                                <label for="Sub_Module_{{$row2->id}}">{{$row2->name}}</label>
                                <br>
                            </div>
                            <ul class="option">
                                <?php 
                                    $childModule= DB::table('infix_module_infos')->where('parent_id',$row2->id)->where('active_status', 1)->get();
                                ?>
                                @foreach($childModule as $row3)
                                    <li>
                                        <div class="module_link_option_div" id="{{$row2->id}}">
                                            <input id="Option_{{$row3->id}}" name="module_id[]" value="{{$row3->id}}"  class="infix_csk common-radio module_id_{{$module_info->id}} module_option_{{$module_info->id}}_{{$row2->id}} module_link_option"  type="checkbox" {{in_array($row3->id ,$already_assigned)? 'checked':''}}>
                                            <label class="nowrap" for="Option_{{$row3->id}}">{{$row3->name}}</label>
                                            <br>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                            @endif
                            @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach
 </div>
         <div class="row mt-40">
            <div class="col-lg-12 text-center">

                @if(Illuminate\Support\Facades\Config::get('app.app_sync')  && $role->id < 10)
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('common.submit') </button></span>
                @else
                    <button class="primary-btn fix-gr-bg">
                        <span class="ti-check"></span>
                        @lang('submit')
                        
                    </button>
                @endif

            </div>
        </div>
    </div>
{{ Form::close() }}
</div>
@endsection
@section('script')
<script type="text/javascript">
    // Fees Assign 
    $('.permission-checkAll').on('click', function () {
        //$('.module_id_'+$(this).val()).prop('checked', this.checked);
       if($(this).is(":checked")){
            $( '.module_id_'+$(this).val() ).each(function() {
              $(this).prop('checked', true);
            });
       }else{
            $( '.module_id_'+$(this).val() ).each(function() {
              $(this).prop('checked', false);
            });
       }
    });

    $('.module_link').on('click', function () {
       var module_id = $(this).parents('.single_permission').attr("id");
       var module_link_id = $(this).val();
       if($(this).is(":checked")){
            $(".module_option_"+module_id+'_'+module_link_id).prop('checked', true);
        }else{
            $(".module_option_"+module_id+'_'+module_link_id).prop('checked', false);
        }
       var checked = 0;
       $( '.module_id_'+module_id ).each(function() {
          if($(this).is(":checked")){
            checked++;
          }
        });

        if(checked > 0){
            $(".main_module_id_"+module_id).prop('checked', true);
        }else{
            $(".main_module_id_"+module_id).prop('checked', false);
        }
     });

    $('.module_link_option').on('click', function () {
       var module_id = $(this).parents('.single_permission').attr("id");
       var module_link = $(this).parents('.module_link_option_div').attr("id");
       // module link check
        var link_checked = 0;
       $( '.module_option_'+module_id+'_'+ module_link).each(function() {
          if($(this).is(":checked")){
            link_checked++;
          }
        });

        if(link_checked > 0){
            $("#Sub_Module_"+module_link).prop('checked', true);
        }else{
            $("#Sub_Module_"+module_link).prop('checked', false);
        }
       // module check
       var checked = 0;
       $( '.module_id_'+module_id ).each(function() {
          if($(this).is(":checked")){
            checked++;
          }
        });

        if(checked > 0){
            $(".main_module_id_"+module_id).prop('checked', true);
        }else{
            $(".main_module_id_"+module_id).prop('checked', false);
        }
     });
</script>
@endsection
