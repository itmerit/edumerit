@extends('backEnd.master')
@section('title') 
@lang('bulkprint::bulk.invoice_settings') 
@endsection

@section('css')
<style>

    /* .copyPaper {
       display: none!important;
    } */
    .copyPaperShow {
        display: show;
    }
    </style>
@endsection
@section('mainContent')
@php  $setting = App\SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp 

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('bulkprint::bulk.invoice_settings') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('bulkprint::bulk.bulk_print')</a>   
                <a href="#">@lang('bulkprint::bulk.invoice_settings') </a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('invoice-settings-update') }}" method="POST">
                    @csrf
                    <div class="white-box">
                            <div class="row p-0">
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('bulkprint::bulk.invoice_settings')</h3>
                                    <hr>
                                    <input type="hidden" name="id" value="{{$invoiceSettings->id}}">

                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('bulkprint::bulk.showing_page') (@lang('bulkprint::bulk.part'))</p>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="d-flex radio-btn-flex"> 
                                                        <div class="mr-30">
                                                            <input type="checkbox" name="copy_s_per_th" id="c_part1" class="common-radio relationButton copy_per_th" {{@$invoiceSettings->c_signature_p == 1? 'checked':''}}>
                                                            <label for="c_part1" id="copys">{{$invoiceSettings->copy_s}}</label>
                                                        </div>
                                                        <div class="mr-30">
                                                            <input type="checkbox" name="copy_c_per_th" id="c_part2"  class="common-radio relationButton copy_per_th" {{@$invoiceSettings->c_signature_c == 1? 'checked':''}}>
                                                            <label for="c_part2" id="copyc">{{$invoiceSettings->copy_c}}</label>
                                                        </div>
                                                        <div class="mr-30">
                                                            <input type="checkbox" name="copy_o_per_th" id="c_part3"  class="common-radio relationButton copy_per_th" {{@$invoiceSettings->c_signature_o == 1? 'checked':''}}>
                                                            <label for="c_part3" id="copyo">{{$invoiceSettings->copy_o}}</label>
                                                        </div>                                           
                                                    </div>
                                            
                                                @if ($errors->has('per_th'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        <strong>{{ @$errors->first('per_th') }}</span>
                                                @endif
                                                </div>
                                            </div>
                                        </div>

                                      
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field form-control{{ $errors->has('prefix') ? ' is-invalid' : '' }}"   type="text" name="prefix"  id="prefix" value="{{$invoiceSettings->prefix}}">
                                                    <label class="primary_input_label" for="">@lang('bulkprint::bulk.invoice_prefix_format_standard_three_character')<span></span></label>
                                                    
                                                    @if ($errors->has('prefix'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('prefix') }}</span>
                                                    @endif
                                                 </div>

                                             </div>
                                    </div>

                                 

                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <input class="primary_input_field form-control{{ $errors->has('signature_p') ? ' is-invalid' : '' }}" type="text" name="footer_1" value="{{$invoiceSettings->footer_1}}">
                                                <label class="primary_input_label" for="">@lang('common.signature')</label>
                                                
                                                @if ($errors->has('signature_p'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('signature_p') }}
                                                </span>
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
                                                                    <input type="radio" name="signature_p" id="signature_p_on" value="1" class="common-radio relationButton" {{$invoiceSettings->signature_p==1 ? 'checked':''}} >
                                                                    <label for="signature_p_on">@lang('common.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_p" id="participant_video" value="0" class="common-radio relationButton" {{$invoiceSettings->signature_p==0 ? 'checked':''}} >
                                                                    <label for="participant_video">@lang('common.no')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-40 mt-40">

                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <input class="primary_input_field form-control{{ $errors->has('footer_2') ? ' is-invalid' : '' }}" type="text" name="footer_2" value="{{$invoiceSettings->footer_2}}">
                                                <label class="primary_input_label" for="">@lang('common.signature')</label>
                                                
                                                @if ($errors->has('footer_2'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('footer_2') }}
                                                </span>
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
                                                                    <input type="radio" name="signature_c" id="signature_c" value="1" class="common-radio relationButton"  {{$invoiceSettings->signature_c==1 ? 'checked':''}}>
                                                                    <label for="signature_c">@lang('common.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_c" id="join_before_host" value="0" class="common-radio relationButton"  {{$invoiceSettings->signature_c==0 ? 'checked':''}}>
                                                                    <label for="join_before_host">@lang('common.no')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="primary_input ">
                                                <input class="primary_input_field form-control{{ $errors->has('footer_3') ? ' is-invalid' : '' }}" type="text"  name="footer_3" value="{{$invoiceSettings->footer_3}}">
                                                <label class="primary_input_label" for="">@lang('common.signature')</label>
                                                
                                                @if ($errors->has('footer_3'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('footer_3') }}
                                                </span>
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
                                                                    <input type="radio" name="signature_o" id="signature_o_on" value="1" class="common-radio relationButton"  {{$invoiceSettings->signature_o==1 ? 'checked':''}} >
                                                                    <label for="signature_o_on">@lang('common.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="signature_o" id="waiting_room" value="0" class="common-radio relationButton"  {{$invoiceSettings->signature_o==0 ? 'checked':''}} >
                                                                    <label for="waiting_room">@lang('common.no')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row mb-40 mt-40">

                                        <div class="col-lg-4">
                                            <div class="primary_input ">
                                                <input class="primary_input_field form-control{{ $errors->has('copy_s') ? ' is-invalid' : '' }}" type="text" name="copy_s" id="copy_s"  value="{{$invoiceSettings->copy_s}}">
                                                <label class="primary_input_label" for="">@lang('bulkprint::bulk.copy_for')</label>
                                                
                                                @if ($errors->has('copy_s'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('copy_s') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="primary_input ">
                                                <input class="primary_input_field form-control{{ $errors->has('copy_s') ? ' is-invalid' : '' }}" type="text" name="copy_o" id="copy_o" value="{{$invoiceSettings->copy_o}}">
                                                <label class="primary_input_label" for="">@lang('bulkprint::bulk.copy_for')</label>
                                                
                                                @if ($errors->has('copy_s'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('copy_s') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="primary_input ">
                                                <input class="primary_input_field form-control{{ $errors->has('copy_c') ? ' is-invalid' : '' }}" type="text" name="copy_c"  id="copy_c" value="{{$invoiceSettings->copy_c}}">
                                                <label class="primary_input_label" for="">@lang('bulkprint::bulk.copy_for')</label>
                                                
                                                @if ($errors->has('copy_s'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('copy_c') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>                                  

                                    <div class="row mb-40 mt-40">

                                       
                                     
                                    </div>



                                    @if(userPermission('invoice-settings'))
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

