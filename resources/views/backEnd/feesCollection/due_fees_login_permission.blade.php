@extends('backEnd.master')
@section('title')
@lang('rolepermission::role.login_permission')
@endsection
@section('mainContent')
<style>
    table.dataTable thead .sorting_asc:after {
        top: 10px;
        left: 5px;
    }

    table.dataTable thead .sorting_asc {
    vertical-align: text-top;
}

    table.dataTable thead .sorting:after {
        top: 10px;
        left: 4px;
    }

</style>
<link rel="stylesheet" href="{{asset('public/backEnd/css/login_access_control.css')}}" />
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('rolepermission::role.fees_due_users_login_permission') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('rolepermission::role.role_permission')</a>
                <a href="#">@lang('rolepermission::role.fees_due_users_login_permission')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                         {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'due_fees_login_permission', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
                         <div class="row">
                             <div class="col-lg-12">
                                 <div class="white-box filter_card">
                                     <div class="row">
                                         <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
             
             
                                         @if(moduleStatusCheck('University'))
                                               @includeIf('university::common.session_faculty_depart_academic_semester_level',['mt'=>'mt-30','hide'=>['USUB'], 'required'=>['USEC']])
                                             <div class="col-lg-3 mt-25">
                                                 <div class="primary_input ">
                                                     <input class="primary_input_field" type="text" placeholder="Name" name="name" value="{{isset($name)? $name: ''}}">
                                                     <label class="primary_input_label" for="">@lang('student.search_by_name')</label>
                                                     
                                                 </div>
                                             </div>
                                             <div class="col-lg-3 mt-25">
                                                 <div class="primary_input md_mb_20">
                                                     <label class="primary_input_label" for="">@lang('student.search_by_roll_no')</label>
                                                     <input class="primary_input_field" type="text" placeholder="Roll" name="roll_no" value="{{isset($roll_no)? $roll_no: ''}}">
                                                     
                                                     
                                                 </div>
                                             </div>
                                         @else
                                             @include('backEnd.common.search_criteria',[
                                                 'mt'=>'mt-30',
                                                 'div'=>'col-lg-3',
                                                 'required'=>[],
                                                 'visiable'=>['class', 'section']
                                             ])
                                             <div class="col-lg-3 mt-30">
                                                 <div class="primary_input sm_mb_20 ">
                                                     <label class="primary_input_label" for="">@lang('student.search_by_name')</label>
                                                     
                                                     <input class="primary_input_field" type="text" placeholder="Name" name="name" value="{{ isset($name)?$name:old('name')}}">
                                                    
                                                 </div>
                                             </div>
                                             <div class="col-lg-3 mt-30">
                                                 <div class="primary_input sm_mb_20 ">
                                                     <label class="primary_input_label" for="">@lang('student.admission_no')</label>
                                                     <input class="primary_input_field" type="text" placeholder="@lang('student.admission_no')" name="admission_no" value="{{ isset($admission_no)?$admission_no:old('admission_no')}}"> 
                                                 </div>
                                             </div>
                                         @endif
                                         <div class="col-lg-12 mt-20 text-right">
                                             <button type="submit" class="primary-btn small fix-gr-bg" id="btnsubmit">
                                                 <span class="ti-search pr-2"></span>
                                                 @lang('common.search')
                                             </button>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>       
                         {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        @if(isset($students))
        <div class="row mt-60">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-3">@lang('common.student_list') ({{@$students->count()}})</h3>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <x-table> 
                              <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed"
                                   cellspacing="0" width="100%">
                                   <thead>
                                        <tr>
                                             <th>@lang('student.admission') </th>
                                             <th>@lang('student.roll')</th>
                                             <th>@lang('common.name')</th>
                                             <th>@lang('common.class')</th>
                                             <th>@lang('rolepermission::role.student_permission')</th>
                                             <th>@lang('student.parents')</th>
                                             <th>@lang('rolepermission::role.parents_permission')</th>
                                        </tr>
                                   </thead>

                                   <tbody>
                                        @foreach($students as $student)
                                             <tr id="{{@$student->user_id}}">
                                                  <td>{{@$student->admission_no}} </td>
                                                  <td> {{@$student->roll_no}}</td>
                                                  <td>{{@$student->first_name.' '.@$student->last_name}} </td>
                                                  <td>
                                                       @foreach($student->studentRecords as $record)
                                                       {{!empty(@$record->class)?@$record->class->class_name:''}}
                                                       ({{!empty(@$record->section)?@$record->section->section_name:''}})
                                                       @endforeach

                                                  </td>
                                                  <td>
                                                       <input type="hidden" name="id" value="{{$student->user_id}}">
                                                       <label class="switch_toggle">
                                                       <input type="checkbox" id="ch{{@$student->user_id}}"
                                                            onclick="enableDisable({{@$student->user_id}},{{@$role}})" class="switch-input11"
                                                            {{@$student->user->loginApproved ? 'checked':''}}>
                                                       <span class="slider round"></span>
                                                       </label>
                                                  </td>
                                                  <td> {{$student->parents->parent_user->full_name}}</td>
                                                  
                                                  <td>

                                                       <input type="hidden" name="ParentID" value="{{@$student->parents->user_id}}"
                                                       id="ParentID">

                                                       <label class="switch_toggle">
                                           
                                                       <input type="checkbox" class="parent-login-disable" id="pr{{@$student->parents->parent_user->id}}"  onclick="enableDisableParent({{@$student->parents->parent_user->id}},{{@$student->parents->parent_user->role_id}})"
                                                            {{@$student->parents->parent_user->loginApproved ? 'checked':''}}>
                                                       <span class="slider round"></span>

                                                       </label>

                                                  </td>
                                                  
                                             </tr>
                                        @endforeach
                                   </tbody>
                              </table>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>
    </div>
    </div>
</section>


@endsection
@include('backEnd.partials.data_table_js')

@push('script')

<script>
     enableDisable = (id, role) => {
        var x = $(`#ch${id}`).is(":checked");
        if (x) {
            var status = "on";
        } else {
            var status = "off";
        }

        var formData = {
            id: id,
            status: status,
        };
        console.log(formData);

        var url = $("#url").val();

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "due_fees_login_permission_store",
            success: function(data) {
                console.log(data);

                setTimeout(function() {
                    toastr.success(
                        "Operation Success!",
                        "Success Alert", {
                            iconClass: "customer-info",
                        }, {
                            timeOut: 2000,
                        }
                    );
                }, 500);
            },
            error: function(data) {
                console.log("no");

                setTimeout(function() {
                    toastr.error("Operation Failed!", "Error Alert", {
                        timeOut: 5000,
                    });
                }, 500);
            },
        });
    };


    //parent 
    enableDisableParent = (id, role) => {
     console.log(id,role);
        var x = $(`#pr${id}`).is(":checked");
        if (x) {
            var status = "on";
        } else {
            var status = "off";
        }

        var formData = {
            id: id,
            status: status,
        };
        console.log(formData);

        var url = $("#url").val();

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "due_fees_login_permission_store",
            success: function(data) {
                console.log(data);

                setTimeout(function() {
                    toastr.success(
                        "Operation Success!",
                        "Success Alert", {
                            iconClass: "customer-info",
                        }, {
                            timeOut: 2000,
                        }
                    );
                }, 500);
            },
            error: function(data) {
                console.log("no");

                setTimeout(function() {
                    toastr.error("Operation Failed!", "Error Alert", {
                        timeOut: 5000,
                    });
                }, 500);
            },
        });
    };



</script>

@endpush
