@extends('backEnd.master')
    @section('title') 
        @lang('bulkprint::bulk.fees_invoice_settings') 
    @endsection
    @section('css')
        <style>
            /* .copyPaper {
            display: none!important;
            } */
            .copyPaperShow {
                display: show;
            }
            .radio-btn-flex {
                margin-top: 10px;
            }
        </style>
    @endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('bulkprint::bulk.fees_invoice_settings') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('bulkprint::bulk.bulk_print')</a>   
                    <a href="#">@lang('bulkprint::bulk.fees_invoice_settings') </a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('fees-invoice-settings-update') }}" method="POST">
                        @csrf
                        <div class="white-box">
                            <div class="row p-0">
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('bulkprint::bulk.fees_invoice_settings')</h3>
                                    <hr>
                                    <input type="hidden" name="id" value="{{$feesInvoiceSettings->id}}">
                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">
                                                        @lang('bulkprint::bulk.invoice_type')
                                                    </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="invoice_type" id="invoice" value="invoice" class="common-radio relationButton invoiceType" {{$feesInvoiceSettings->invoice_type == 'invoice' ? 'checked':''}} >
                                                                    <label for="invoice">@lang('bulkprint::bulk.invoice')</label>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="invoice_type" id="slip" value="slip" class="common-radio relationButton invoiceType" {{$feesInvoiceSettings->invoice_type == 'slip' ? 'checked':''}} >
                                                                    <label for="slip">@lang('bulkprint::bulk.slip')</label>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 invType d-none">
                                            <div class="row">
                                                <div class="col-lg-3 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('bulkprint::bulk.showing_page') (@lang('bulkprint::bulk.part'))</p>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="d-flex radio-btn-flex"> 
                                                        <div class="mr-30">
                                                            <input type="checkbox" name="copy_s_per_th" id="c_part1" class="common-radio relationButton copy_per_th" {{@$feesInvoiceSettings->c_signature_p == 1? 'checked':''}}>
                                                            <label for="c_part1" id="copys">{{$feesInvoiceSettings->copy_s}}</label>
                                                            
                                                        </div>
                                                        <div class="mr-30">
                                                            <input type="checkbox" name="copy_c_per_th" id="c_part2"  class="common-radio relationButton copy_per_th" {{@$feesInvoiceSettings->c_signature_c == 1? 'checked':''}}>
                                                            <label for="c_part2" id="copyc">{{$feesInvoiceSettings->copy_c}}</label>
                                                          
                                                        </div>
                                                        <div class="mr-30">
                                                            <input type="checkbox" name="copy_o_per_th" id="c_part3"  class="common-radio relationButton copy_per_th" {{@$feesInvoiceSettings->c_signature_o == 1? 'checked':''}}>
                                                            <label for="c_part3" id="copyo">{{$feesInvoiceSettings->copy_o}}</label>
                                                           
                                                        </div>                                           
                                                    </div>
                                                    @if ($errors->has('per_th'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            <strong>{{ @$errors->first('per_th') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-40 mt-40 invType d-none">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('common.signature')</label>
                                                <input class="primary_input_field form-control{{ $errors->has('signature_p') ? ' is-invalid' : '' }}" type="text" name="footer_1" value="{{$feesInvoiceSettings->footer_1}}">
                                                
                                                
                                                @if ($errors->has('signature_p'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('signature_p') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('bulkprint::bulk.is_showing_signature')  </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_p" id="signature_p_on" value="1" class="common-radio relationButton" {{$feesInvoiceSettings->signature_p==1 ? 'checked':''}} >
                                                                    <label for="signature_p_on">@lang('common.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_p" id="participant_video" value="0" class="common-radio relationButton" {{$feesInvoiceSettings->signature_p==0 ? 'checked':''}} >
                                                                    <label for="participant_video">@lang('common.no')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-40 mt-40 invType d-none">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('common.signature')</label>

                                                <input class="primary_input_field form-control{{ $errors->has('footer_2') ? ' is-invalid' : '' }}" type="text" name="footer_2" value="{{$feesInvoiceSettings->footer_2}}">
                                                
                                                @if ($errors->has('footer_2'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('footer_2') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('bulkprint::bulk.is_showing_signature') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class=" radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_c" id="signature_c" value="1" class="common-radio relationButton"  {{$feesInvoiceSettings->signature_c==1 ? 'checked':''}}>
                                                                    <label for="signature_c">@lang('common.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_c" id="join_before_host" value="0" class="common-radio relationButton"  {{$feesInvoiceSettings->signature_c==0 ? 'checked':''}}>
                                                                    <label for="join_before_host">@lang('common.no')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-40 mt-40 invType d-none">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('common.signature')</label>

                                                <input class="primary_input_field form-control{{ $errors->has('footer_3') ? ' is-invalid' : '' }}" type="text"  name="footer_3" value="{{$feesInvoiceSettings->footer_3}}">
                                                
                                                @if ($errors->has('footer_3'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('footer_3') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('bulkprint::bulk.is_showing_signature')</p>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class=" radio-btn-flex ml-20">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_o" id="signature_o_on" value="1" class="common-radio relationButton"  {{$feesInvoiceSettings->signature_o==1 ? 'checked':''}} >
                                                                    <label for="signature_o_on">@lang('common.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_o" id="waiting_room" value="0" class="common-radio relationButton"  {{$feesInvoiceSettings->signature_o==0 ? 'checked':''}} >
                                                                    <label for="waiting_room">@lang('common.no')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-40 mt-40 invType d-none">
                                        <div class="col-lg-4">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('bulkprint::bulk.copy_for')</label>
                                                <input class="primary_input_field form-control{{ $errors->has('copy_s') ? ' is-invalid' : '' }}" type="text" name="copy_s" id="copy_s"  value="{{$feesInvoiceSettings->copy_s}}">
                                            
                                                
                                                @if ($errors->has('copy_s'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('copy_s') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('bulkprint::bulk.copy_for')</label>
                                                <input class="primary_input_field form-control{{ $errors->has('copy_s') ? ' is-invalid' : '' }}" type="text" name="copy_o" id="copy_o" value="{{$feesInvoiceSettings->copy_o}}">
                                            
                                                
                                                @if ($errors->has('copy_s'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('copy_s') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('bulkprint::bulk.copy_for')</label>
                                                <input class="primary_input_field form-control{{ $errors->has('copy_c') ? ' is-invalid' : '' }}" type="text" name="copy_c"  id="copy_c" value="{{$feesInvoiceSettings->copy_c}}">
                                                
                                                
                                                @if ($errors->has('copy_s'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('copy_c') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>                                  
                                    <div class="row mb-40 mt-40">
                                    </div>
                                    @if(userPermission(570))
                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" id="_submit_btn_admission">
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
            $(document).ready(function(){
                $(document).on('change','.per_th',function(){
                    let per_th = $(this).val();           
                    // $(".copyPaper").show();
                    $('#copyPaperShow').addClass('copyPaperShow');
                    $('#copyPaperShow').removeClass('copyPaper');
                }) 
            })

            $(document).ready(function(){
                selectinvoiceType($('.invoiceType:checked').val());
                $(document).on('click','.invoiceType',function(){
                    let invoiceType = $(this).val();
                    selectinvoiceType(invoiceType);
                }) 
            })
            
            function selectinvoiceType(invoiceType){
                if(invoiceType == "slip"){
                    $('.invType').removeClass('d-none');
                }else{
                    $('.invType').addClass('d-none');
                }
            }

            $(document).on("keyup", "#copy_s", function(event) {
                let titleValue = $(this).val();
                $("#copys").html(titleValue);
            });

            $(document).on("keyup", "#copy_c", function(event) {
                let titleValue = $(this).val();
                $("#copyc").html(titleValue);
            });
            
            $(document).on("keyup", "#copy_o", function(event) {
                let titleValue = $(this).val();
                $("#copyo").html(titleValue);
            });
        </script>
    @endsection
@endsection

