@extends('backEnd.master')
    @section('title') 
        @if (isset($invoiceInfo))
            @lang('common.edit')
        @else
            @lang('common.add')
        @endif 
            @lang('fees.fees_invoice')
    @endsection
@section('mainContent')
    @push('css')
        <link rel="stylesheet" href="{{url('Modules\Fees\Resources\assets\css\feesStyle.css')}}"/>
        <style>
            .ti-calendar:before {
                position: relative !important;
                top: 5px !important;
            }
            .school-table-style tr th {
                padding: 10px 18px 10px 10px !important;
            }
            .school-table-style tr td {
                padding: 20px 10px 20px 10px !important;
            }
            .school-table-style tfoot tr td {
                padding: 10px 10px 10px 10px !important;
            }
        </style>
    @endpush
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@if (isset($invoiceInfo))
                    @lang('common.edit')
                @else
                    @lang('common.add')
                @endif 
                    @lang('fees.fees_invoice')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('fees.fees')</a>
                    <a href="{{route('fees.fees-invoice-list')}}">@lang('fees.fees_invoice')</a>
                    <a href="#">
                        @if (isset($invoiceInfo))
                            @lang('common.edit')
                        @else
                            @lang('common.add')
                        @endif
                            @lang('fees.fees_invoice')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($invoiceInfo))
                {{ Form::open(['class' => 'form-horizontal', 'method' => 'POST', 'route' => 'fees.fees-invoice-update']) }}
                <input type="hidden" name="id" class="editValue" value="{{$invoiceInfo->id}}">
            @else
                {{ Form::open(['class' => 'form-horizontal', 'method' => 'POST', 'route' => 'fees.fees-invoice-store']) }}
            @endif
                <div class="row">
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">
                                        @if (isset($invoiceInfo))
                                            @lang('common.edit')
                                        @else
                                            @lang('common.add')
                                        @endif
                                            @lang('fees.fees_invoice')
                                    </h3>
                                </div>
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="white-box">
                                    <div class="add-visitor">                              
                                        <div class="row">
                                            <div class="col-lg-12 d-flex">
                                                <div>@lang('fees.invoice')- &nbsp;</div>
                                                <div class="d-flex" id="showValue"></div>
                                            </div>
                                        </div>

                                    @if (moduleStatusCheck('University'))
                                        @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                        ['required' => 
                                            ['USN', 'UD', 'UA', 'US', 'USL','USEC'],'hide'=> ['USUB'],
                                            'div'=>'col-lg-12','row'=>1,
                                             'mt'=>'mt-0','disabled'=>true
                                        ])
                    
                                        <div class="row">
                                            <div class="col-lg-12 mt-15" id="select_un_student_div">
                                                <label class="primary_input_label" for="">
                                                    {{ __('common.student') }}
                                                        <span class="text-danger"> *</span>
                                                </label>
                                                {{ Form::select('student_id', @$students ?? [""=>__('common.select_student').'*'], $invoiceInfo ? $invoiceInfo->student_id : null , ['class' => 'primary_select  form-control'. ($errors->has('student_id') ? ' is-invalid' : ''), 'id'=>'select_un_student',  isset($invoiceInfo) ? 'disabled' : '']) }}

                                                
                                                <div class="pull-right loader loader_style" id="select_un_student_loader">
                                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                </div>
                                                @if ($errors->has('student_id'))
                                                    <span class="text-danger custom-error-message" role="alert">
                                                        {{ @$errors->first('student_id') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-lg-12 mt-15">
                                                <label class="primary_input_label" for="">
                                                    {{ __('common.class') }}
                                                        <span class="text-danger"> *</span>
                                                </label>
                                                <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" name="class" id="selectClass">
                                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                                        @foreach($classes as $class)
                                                            <option value="{{$class->id}}" {{isset($invoiceInfo)? ($invoiceInfo->class_id == $class->id?'selected':''):''}} >{{$class->class_name}}</option>
                                                        @endforeach
                                                </select>
                                                @if($errors->has('class'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('class') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-15">
                                            <div class="col-lg-12" id="selectStudentDiv">
                                                <label class="primary_input_label" for="">
                                                    {{ __('common.section') }}
                                                        <span class="text-danger"> *</span>
                                                </label>
                                                <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="selectStudent" name="student">
                                                <option data-display="@lang('common.select_student') *" value="">@lang('common.select_student')*</option>
                                                @if (isset($invoiceInfo))
                                                @dump($students)
                                                    @foreach ($students as $student)
                                                        <option value="{{$student->id}}" {{($student->id == $invoiceInfo->record_id)? 'selected':''}}>{{$student->studentDetail->full_name}} ({{$student->section->section_name}} - {{$student->roll_no}})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="pull-right loader" id="selectStudentLoader" style="margin-top: -30px;padding-right: 21px;">
                                                <img src="{{asset('Modules/Fees/Resources/assets/img/pre-loader.gif')}}" alt="" style="width: 28px;height:28px;">
                                            </div>
                                            @if ($errors->has('student'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('student') }}
                                                </span>
                                            @endif
                                            </div>
                                        </div>
                                    @endif
                                        <div class="row mt-15">
                                          
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('fees.create_date') <span class="text-danger"> *</span></label>
                                                    <div class="primary_datepicker_input">
                                                        <div class="no-gutters input-right-icon">
                                                            <div class="col">
                                                                <div class="">
                                                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('create_date') ? ' is-invalid' : '' }}" id="create_date" type="text" name="create_date" value="{{isset($invoiceInfo)? date('m/d/Y', strtotime($invoiceInfo->create_date)) : date('m/d/Y')}}">
                                                                </div>
                                                            </div>
                                                            <button class="btn-date" data-id="#create_date" type="button">
                                                                <label for="create_date">
                                                                    <i class="ti-calendar" id="create_date"></i>
                                                                </label>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('create_date'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('create_date') }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row mt-15">
                                           
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('fees.due_date') <span class="text-danger"> *</span></label>
                                                    <div class="primary_datepicker_input">
                                                        <div class="no-gutters input-right-icon">
                                                            <div class="col">
                                                                <div class="">
                                                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="due_date" type="text" name="due_date" value="{{isset($invoiceInfo)? date('m/d/Y', strtotime($invoiceInfo->due_date)) : date('m/d/Y')}}">
                                                                </div>
                                                            </div>
                                                            <button class="btn-date" data-id="#due_date" type="button">
                                                                <label for="due_date">
                                                                    <i class="ti-calendar" id="due_date"></i>
                                                                </label>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('due_date'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('due_date') }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-15 {{isset($invoiceInfo) ? 'd-none' : ''}}">
                                            <div class="col-lg-12">
                                                <label class="primary_input_label" for="">
                                                    {{ __('fees.payment_status') }}
                                                        <span class="text-danger"> *</span>
                                                </label>
                                                <select class="primary_select  form-control{{ $errors->has('payment_status') ? ' is-invalid' : '' }}" name="payment_status" id="paymentStatus">
                                                    <option data-display="@lang('fees.payment_status') *" value="">@lang('fees.payment_status') *</option>
                                                    <option value="not" {{isset($invoiceInfo)? ($invoiceInfo->payment_status == "not"?'selected':''):(old('payment_status') == 'not' ? 'selected' : '')}}>@lang('fees.not_paid')</option>
                                                    <option value="partial" {{isset($invoiceInfo)? ($invoiceInfo->payment_status == "partial"?'selected':''):(old('payment_status') == 'partial' ? 'selected' : '')}}>@lang('fees.partial_paid')</option>
                                                    <option value="full" {{isset($invoiceInfo)? ($invoiceInfo->payment_status == "full"?'selected':''):(old('payment_status') == 'full' ? 'selected' : '')}}>@lang('fees.full_paid')</option>
                                                </select>
                                                @if($errors->has('payment_status'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('payment_status') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-15 d-none" id="paymentMethod">
                                            <div class="col-lg-12">
                                                <label class="primary_input_label" for="">
                                                    {{ __('fees.payment_method') }}
                                                        <span class="text-danger"> *</span>
                                                </label>
                                                <select class="primary_select  form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}" name="payment_method" id="paymentMethodName">
                                                    <option data-display="@lang('fees.payment_method')*" value="">@lang('fees.payment_method')*</option>
                                                    @foreach ($paymentMethods as $paymentMethod)
                                                        <option value="{{$paymentMethod->method}}" {{isset($invoiceInfo)? ($invoiceInfo->payment_method == $paymentMethod->method?'selected':''):(old('payment_method') == $paymentMethod->method ? 'selected' : '')}}>{{$paymentMethod->method}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('payment_method'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('payment_method') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-15 d-none" id="bankPayment">
                                            <div class="col-lg-12">
                                                <label class="primary_input_label" for="">
                                                    {{ __('fees.bank') }}
                                                        <span class="text-danger"> *</span>
                                                </label>
                                                <select class="primary_select  form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}" name="bank">
                                                    <option data-display="@lang('fees.select_bank')*" value="">@lang('fees.select_bank')*</option>
                                                    @foreach ($bankAccounts as $bankAccount)
                                                        <option value="{{$bankAccount->id}}" {{isset($invoiceInfo)? ($invoiceInfo->bank_id == $bankAccount->id?'selected':''):(old('bank') == $bankAccount->id ? 'selected' : '')}}>{{$bankAccount->bank_name}} ({{$bankAccount->account_number}})</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('bank'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('bank') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @php 
                                        $tooltip = "";
                                        
                                        @endphp
                                        <input type="hidden" value="{{@$invoiceInfo->id}}" id="newFeesEditId">

                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn fix-gr-bg submit fmInvoice" data-tooltip="tooltip" title="{{$tooltip}}">
                                                    <span class="ti-check"></span>
                                                    @if (isset($invoiceInfo))
                                                        @lang('common.update')
                                                    @else
                                                        @lang('common.save')
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('fees.fees_type_list')</h3>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-15">
                            <div class="col-lg-12">
                                <div class="white-box pb-0 fees_invoice_type_div">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <select class="primary_select  form-control{{ $errors->has('fees_type') ? ' is-invalid' : '' }}" id="selectFeesType" name="fees_type">
                                                <option data-display="@lang('fees.fees_type') *" value="">@lang('fees.fees_type') *</option>
                                                <option value="" disabled>@lang('fees.fees_group')</option>
                                                @foreach ($feesGroups as $feesGroup)
                                                    <option value="grp{{$feesGroup->id}}">{{$feesGroup->name}}</option>
                                                @endforeach
                                                <option value="" disabled>@lang('fees.fees_type')</option>
                                                @foreach ($feesTypes as $feesType)
                                                    <option value="typ{{$feesType->id}}">{{$feesType->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('fees_type'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('fees_type') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-12 justify-content-end d-flex">
                                            <div class="text-right">
                                                <input type="checkbox" name="singleInvoice" id="singleInvoice" class="common-checkbox form-control" value="1">
                                                <label for="singleInvoice">@lang('fees::feesModule.group_fees_generate_seperate_invoice')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-12 justify-content-end d-flex">
                                            <div class="text-right">
                                                <input type="checkbox" id="cloneAmount" class="common-checkbox form-control permission-checkAll">
                                                <label for="cloneAmount">@lang('fees::feesModule.clone_amount')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="weaverType" value="amount">
                                <div class="big-table">
                                    <table class="table school-table-style fees_invoice_type_table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('common.sl')</th>
                                                <th>@lang('fees.fees_type')</th>
                                                <th>@lang('accounts.amount')</th>
                                                <th>@lang('exam.waiver')</th>
                                                <th>@lang('fees.sub_total')</th>
                                                @if(!isset($invoiceInfo))
                                                    <th>@lang('fees.paid_amount')</th>
                                                @endif
                                                <th>@lang('common.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="allFeesTypes">
                                            @if (isset($invoiceInfo))
                                                @foreach ($invoiceDetails as $key=>$invoiceDetail)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{$invoiceDetail->feesType->name}}</td>
                                                        <input type="hidden" name="feesType[]" value="{{$invoiceDetail->fees_type}}">
                                                        <td>
                                                            <div class="primary_input">
                                                                <input class="primary_input_field form-control amount{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="amount[]" autocomplete="off" value="{{isset($invoiceDetail)? $invoiceDetail->amount: old('amount')}}">
                                                                
                                                                @if ($errors->has('amount'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('amount') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="primary_input">
                                                                <input class="primary_input_field form-control weaver{{ $errors->has('weaver') ? ' is-invalid' : '' }}" type="text" name="weaver[]" autocomplete="off" value="{{isset($invoiceDetail)? $invoiceDetail->weaver: old('weaver')}}">
                                                                
                                                                @if ($errors->has('weaver'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('weaver') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="subTotal">{{isset($invoiceDetail)? $invoiceDetail->sub_total: ""}}</td>
                                                        <input type="hidden" name="sub_total[]" class="inputSubTotal" value="{{isset($invoiceDetail)? $invoiceDetail->sub_total: ""}}">
                                                        @if(!isset($invoiceDetail))
                                                            <td>
                                                                <input class="primary_input_field form-control paidAmount{{ $errors->has('paid_amount') ? ' is-invalid' : '' }}" type="text" name="paid_amount[]" autocomplete="off" disabled value="{{isset($invoiceDetail)? $invoiceDetail->paid_amount: old('paid_amount')}}">
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <button class="primary-btn icon-only fix-gr-bg" data-toggle="modal" data-tooltip="tooltip" data-target="#addNotesModal{{$invoiceDetail->fees_type}}" type="button"
                                                                title="@lang('common.edit_note')">
                                                                <span class="ti-pencil-alt"></span>
                                                            </button>
                                                            <button class="primary-btn icon-only fix-gr-bg" type="button" data-tooltip="tooltip" title="@lang('common.delete')" id="deleteField">
                                                                <span class="ti-trash"></span>
                                                            </button>
                                                            {{-- Notes Modal Start --}}
                                                            <div class="modal fade admin-query" id="addNotesModal{{$invoiceDetail->fees_type}}">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">@lang('common.edit_note')</h4>
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="primary_input">
                                                                                <input class="primary_input_field form-control has-content" type="text" name="note[]" autocomplete="off" value="{{isset($invoiceDetail)? $invoiceDetail->note: ""}}">
                                                                                <label class="primary_input_label" for="">@lang('common.note')</label>
                                                                                
                                                                            </div>
                                                                            </br>
                                                                            <div class="mt-40 d-flex justify-content-between">
                                                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                                <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">@lang('common.update')</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Notes Modal End --}}
                                                            <input type="hidden" class="fees" value="grp{{$invoiceDetail->feesType->fees_group_id}}">
                                                            <input type="hidden" class="fees" value="typ{{$invoiceDetail->fees_type}}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>@lang('exam.result')</td>
                                                <td></td>
                                                <td class="showTotalAmount">0.00</td>
                                                <td class="showTotalWeaver">0.00</td>
                                                <td class="showSubTotalDiscount">0.00</td>
                                                @if(!isset($invoiceInfo))
                                                    <td class="showPaidAmount">0.00</td>
                                                @endif
                                                <td></td>
                                                <input class="totalPaidAmount" type="hidden" name="total_paid_amount">
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')
@push('script')
    <script type="text/javascript" src="{{url('Modules\Fees\Resources\assets\js\app.js')}}"></script>
    <script>
        selectPosition({!! feesInvoiceSettings()->invoice_positions !!});
    </script>
    <script>
        $(document).ready(function() {
            $("#select_semester_label").on("change", function() {

                var url = $("#url").val();
                var i = 0;
                let semester_id = $(this).val();
                let academic_id = $('#select_academic').val();  
                let session_id = $('#select_session').val();
                let faculty_id = $('#select_faculty').val();
                let department_id = $('#select_dept').val();
                let un_semester_label_id = $('#select_semester_label').val();

                if (session_id =='') {
                    setTimeout(function() {
                        toastr.error(
                        "Session Not Found",
                        "Error ",{
                            timeOut: 5000,
                    });}, 500);
                
                    $("#select_semester option:selected").prop("selected", false)
                    return ;
                }
                if (department_id =='') {
                    setTimeout(function() {
                        toastr.error(
                        "Department Not Found",
                        "Error ",{
                            timeOut: 5000,
                    });}, 500);
                    $("#select_semester option:selected").prop("selected", false)

                    return ;
                }
                if (semester_id =='') {
                    setTimeout(function() {
                        toastr.error(
                        "Semester Not Found",
                        "Error ",{
                            timeOut: 5000,
                    });}, 500);
                    $("#select_semester option:selected").prop("selected", false)

                    return ;
                }
                if (academic_id =='') {
                    setTimeout(function() {
                        toastr.error(
                        "Academic Not Found",
                        "Error ",{
                            timeOut: 5000,
                    });}, 500);
                    return ;
                }
                if (un_semester_label_id =='') {
                    setTimeout(function() {
                        toastr.error(
                        "Semester Label Not Found",
                        "Error ",{
                            timeOut: 5000,
                    });}, 500);
                    return ;
                }

                var formData = {
                    semester_id : semester_id,
                    academic_id : academic_id,
                    session_id : session_id,
                    faculty_id : faculty_id,
                    department_id : department_id,
                    un_semester_label_id : un_semester_label_id,
                };
            
                // Get Student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/university/" + "get-university-wise-student",
                    beforeSend: function() {
                        $('#select_un_student_loader').addClass('pre_loader').removeClass('loader');
                    },
                    success: function(data) {
                        var a = "";
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#select_un_student").find("option").not(":first").remove();
                                $("#select_un_student_div ul").find("li").not(":first").remove();

                                $("#select_un_student").append(
                                    $("<option>", {
                                        value: 'all_student',
                                        text: "All Student",
                                    })
                                );

                                $.each(item, function(i, students) {
                                    $("#select_un_student").append(
                                        $("<option>", {
                                            value: students.student.id,
                                            text: students.student.full_name,
                                        })
                                    );

                                    $("#select_un_student_div ul").append(
                                        "<li data-value='" +
                                        students.student.id +
                                        "' class='option'>" +
                                        students.student.full_name +
                                        "</li>"
                                    );
                                });
                                $("#select_un_student").niceSelect('update');
                            } else {
                                $("#select_un_student").find("option").not(":first").remove();
                                $("#select_un_student_div ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#select_un_student_loader').removeClass('pre_loader').addClass('loader');
                        }
                    }
                });
            });
        });
    </script>
@endpush
