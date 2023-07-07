@extends('backEnd.master')
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
    margin-left: 25px;
    grid-template-columns: repeat(3, 1fr);
    /* grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); */
}
.erp_role_permission_area .single_permission .permission_body > ul > li ul li {
    margin-right: 20px;
   
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
  padding: 10px 25px 0px 25px;
}
.erp_role_permission_area .single_permission .permission_header {
  padding: 10px 25px 0px 25px;
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
    top: -2px;
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
</style>
@section('title')
@lang('menumanage::menuManage.menu_position')
@endsection
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('menumanage::menuManage.menu') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('menumanage::menuManage.menu')</a>
                <a href="#">@lang('menumanage::menuManage.menu_position')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-10">
       
        <div class="row">        
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        
                        <div class="erp_role_permission_area ">



                            <!-- single_permission  -->
                                    @if(count($all_sidebars)>0)
                                   
                                   
                    
                                            <div  class="mesonary_role_header" >
                                               
                                                @foreach($all_sidebars as $key => $module_info)
                    
                                                   <div class="single_role_blocks" data-id="menu{{ $module_info->id }}">
                                                            <div class="single_permission" id="{{$module_info->id}}">
                    
                                                               <div class="permission_header d-flex align-items-center justify-content-between">
                    
                                                                   <div>
                                                                       {{-- <input type="checkbox" name="module_id[]" value="{{$module_info->id}}" id="Main_Module_{{$key}}" class="common-radio permission-checkAll main_module_id_{{$module_info->id}}" checked> --}}
                                                                       <a href="{{route('menumanage.edit',[$module_info->id])}}">  <i class="fa fa-edit"></i> </a>
                                                                       <label for="Main_Module_{{$key}}">{{@$module_info->name}}</label>
                                                                   </div>
                    
                                                                   <div class="arrow collapsed" data-toggle="collapse" data-target="#Role{{$module_info->id}}">
                    
                    
                                                                   </div>
                    
                                                               </div>
                    
                                                               <div id="Role{{$module_info->id}}" class="collapse">
                                                                       <div  class="permission_body">
                                                                               <ul>
                    
                    
                                                                                   <?php 
                    
                                                                                       $subModule= DB::table('sidebars')->where('parent_id',$module_info->infix_module_id)->where('active_status', 1)->get();
                                                                                   ?>
                                                                                       @foreach($subModule as $row2)
                    
                    
                                                                                       @if(moduleStatusCheck('Saas') == TRUE && $row2->id == 547)
                    
                                                                                       @else
                    
                    
                                                                                       <li>
                                                                                           <div class="submodule">
                                                                                                <a href="{{route('menumanage.edit',[$row2->id])}}">  <i class="fa fa-edit"></i> </a>
                                                                                              
                                                                                               <label for="Sub_Module_{{$row2->id}}">{{$row2->name}}-{{$row2->id}}</label>
                                                                                               <br>
                                                                                           </div>
                    
                                                                                       
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
                           
                    
                                  
                    
                                         @else 
                                       
                                         {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' =>'menumanage.store.sidebar',
                                         'method' => 'POST']) }}
                    
                                         <input type="hidden" name="role_id" value="{{@$role->id}}">
                    
                                         <div  class="mesonary_role_header" >
                                    
                                                 @foreach($all_modules as $key => $row)
                                                          @php 
                                                    
                                                             if (moduleStatusCheck('SaasRolePermission') == TRUE) {
                                                                 $module_info = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->first();
                                                                 $all_group_modules = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('id', '!=', $key)->get();
                                                             } else {
                                                                 $module_info = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('is_saas',0)->first();
                                                                 $all_group_modules = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('id', '!=', $key)->where('is_saas',0)->get();
                                                             }
                    
                                  
                    
                    
                    
                                                             $all_group_modules = Modules\RolePermission\Entities\InfixModuleInfo::where('module_id', $key)->where('id', '!=', $key)->where('active_status', 1)->get();
                    
                    
                    
                                                         @endphp
                    
                                                             <div class="single_role_blocks" data-id="menu{{ $module_info->id }}">
                                                                      <div class="single_permission" id="{{$module_info->id}}">
                    
                                                                         <div class="permission_header d-flex align-items-center justify-content-between">
                    
                                                                             <div>
                                                                               
                                                                                 <label for="Main_Module_{{$key}}">{{$module_info->name}}</label>
                                                                             </div>
                    
                                                                             <div class="arrow collapsed" data-toggle="collapse" data-target="#Role{{$module_info->id}}">
                    
                    
                                                                             </div>
                    
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
                                                                                                     <div class="submodule">
                                                                                                         <input type="hidden"  name="parent_module_id[]" value="{{$row2->parent_id}}">
                    
                    
                                                                                                         <label for="Sub_Module_{{$row2->id}}">{{$row2->name}}</label>
                                                                                                         <br>
                                                                                                     </div>
                    
                                                                                                 
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
                                                                     <button class="primary-btn fix-gr-bg">
                                                                         <span class="ti-check"></span>
                                                                         @lang('submit')
                                                                         
                                                                     </button>
                                                                 </div>
                                                             </div>
                    
                                      {{ Form::close() }}
                                         @endif
                    
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection