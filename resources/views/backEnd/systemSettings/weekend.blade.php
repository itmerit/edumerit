@extends('backEnd.master')
@section('title')
@lang('common.weekend')
@endsection 
@section('mainContent')
<style type="text/css">
    #selectStaffsDiv, .forStudentWrapper {
        display: none;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .buttons_div_one{
    /* border: 4px solid #FFFFFF; */
    border-radius:12px;

    padding-top: 0px;
    padding-right: 5px;
    padding-bottom: 0px;
    margin-bottom: 4px;
    padding-left: 0px;
     }
    .buttons_div{
    border: 4px solid #19A0FB;
    border-radius:12px
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.weekend')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('common.weekend')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">      
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title mt_4">
                        <h3 class="mb-30">@lang('system_settings.day_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <table id="" class="table school-table-style" cellspacing="0" width="100%">

                        <thead>
                            
                            <tr>
                                <th>@lang('common.name')</th>
                                <th>@lang('common.weekend')</th>
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>

                    <tbody>
                        @foreach($weekends as $weekend)
                        <tr>
                            <td>{{@$weekend->name}}</td>
                            <td>
                                @if(@$weekend->is_weekend == 1)
                                <button class="primary-btn small fix-gr-bg">
                                    yes
                                </button>
                                @else
                                    {{'No'}}
                                @endif


                            </td>
                            <td>
                                 <label class="switch_toggle">
                                <input type="checkbox" data-id="{{$weekend->id}}"
                                        class="weekend_switch_btn" {{@$weekend->is_weekend == 0? '':'checked'}}>
                                    <span class="slider round"></span>
                                </label>


                            </td>
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
@endsection

@push('script')
<script>
    $(document).ready(function() {
            $(".weekend_switch_btn").on("change", function() {
                var day_id = $(this).data("id");
                
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                
                
                var url = $("#url").val();
                

                $.ajax({
                    type: "POST",
                    data: {'status': status, 'day_id': day_id},
                    dataType: "json",
                    url: url + "/" + "weekend/switch",
                    success: function(data) {
                         location.reload();
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
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
</script>
@endpush
