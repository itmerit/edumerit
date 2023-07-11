@extends('backEnd.master')
@section('title') 
@lang('admin.generate_id_card')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1> @lang('admin.generate_id_card')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('admin.admin_section')</a>
                <a href="#">@lang('admin.generate_id_card')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'generate_id_card_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
            <div class="white-box">
                <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-6 mt-30-md">
                                <select class="primary_select new_test w-100 bb form-control {{ @$errors->has('role') ? ' is-invalid' : '' }}" name="role">
                                    <option data-display="@lang('common.select_role') *" value="">@lang('common.select_role') *</option>
                                    @foreach($roles as $role)
                                    <option value="{{@$role->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$role->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role'))
                                <span class="text-danger invalid-select" role="alert">
                                    <strong>{{ @$errors->first('role') }}
                                </span>
                                @endif
                            </div>
                            {{-- <div class="col-lg-4 mt-30-md" id="select_section_div">
                                <select class="primary_select " id="select_section" name="section">
                                    <option data-display="@lang('common.select_section')" value=""> @lang('common.select_section')</option>
                                    @if(isset($section))
                                    <option value="{{@$section->id}}" selected>{{@$section->section_name}}</option>
                                    @endif
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                            </div> --}}
                            
                            <div class="col-lg-6 mt-30-md">
                                <select class="primary_select  form-control{{ $errors->has('id_card') ? ' is-invalid' : '' }}" id="id_card" name="id_card">
                                    <option data-display=" @lang('common.select_id_card') *" value=""> @lang('common.select_id_card') *</option>
                                    @foreach($id_cards as $id_card)
                                    <option value="{{@$id_card->id}}" {{isset($card_id)? ($card_id == $id_card->id? 'selected':''):''}}>{{@$id_card->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('id_card'))
                                <span class="text-danger invalid-select" role="alert">
                                    <strong>{{ @$errors->first('id_card') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
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
</section>


@if(isset($students))
 <section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">

        <div class="row mt-40">  
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('common.student_list')</h3>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <a href="javascript:;" id="genearte-id-card-print-button" class="primary-btn small fix-gr-bg" >
                            @lang('admin.generate')
                        </a>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <table class="table school-table-style" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">
                                        <input type="checkbox" id="checkAll" class="common-checkbox generate-id-card-print-all" name="checkAll" value="">
                                        <label for="checkAll">@lang('common.all')</label>
                                    </th>
                                    <th>@lang('student.admission_no')</th>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.class_Sec')</th>
                                    <th>@lang('student.father_name')</th>
                                    <th>@lang('common.date_of_birth')</th>
                                    <th>@lang('common.gender')</th>
                                    <th>@lang('common.mobile')</th>
                                </tr>
                            </thead>

                            <tbody>
                               @foreach($students as $student)
                               <tr>
                                    <td>
                                        <input type="checkbox" id="student.{{@$student->id}}" class="common-checkbox generate-id-card-print" name="student_checked[]" value="{{@$student->id}}">
                                            <label for="student.{{@$student->id}}"></label>
                                        </td>
                                    <td>
                                        {{@$student->admission_no}}
                                    </td>
                                    <td>{{@$student->full_name}}</td>
                                    <td>{{@$student->class !=""?@$student->class->class_name:""}} ({{@$student->section!=""?@$student->section->section_name:""}})</td>
                                    <td>{{@$student->parents !=""?@$student->parents->fathers_name:""}}</td>
                                    <td> 
                                        {{@$student->date_of_birth != ""? dateConvert(@$student->date_of_birth):''}}
                                    </td>
                                    <td>{{@$student->gender!=""?@$student->gender->base_setup_name:""}}</td>
                                    <td>{{@$student->mobile}}</td>
                                </tr>
                               @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
