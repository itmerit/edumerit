@extends('backEnd.master')
@push('css')   
    <style>
        .recipientCard {
            display: -webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
        }

        .recipientCards .white-box {
            padding: 20px;
        }

        .recipientCards .primary_input_label:before,
        .common-checkbox:checked~label::after,
        .common-checkbox~label::before {
            left: 0 !important;
        }

        .recipientCards .primary_input_label {
            padding-left: 24px;
        }

        .recipientCards .primary-btn {
            padding: 0 10px;
            line-height: 30px;
        }

        .recipientCards .white-box>.d-flex {
            margin-bottom: 10px;
        }

        .recipientCards {
            gap: 8px;
        }

        .common-checkbox~label {
            padding-left: 25px;
        }
    </style>
@endpush
@section('title')
    @lang('system_settings.notification_settings')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.notification_settings')</h1>
                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.notification_settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('system_settings.notification_settings')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('system_settings.event')</th>
                                    <th>@lang('system_settings.destination')</th>
                                    <th>@lang('system_settings.recipient')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notificationSettings as $data)
                                    <input type="hidden" id="dataId" value="{{ $data->id }}">
                                    <tr>
                                        <td width="15%">
                                            <input type="hidden" name="event" value="1">
                                            {{ str_replace('_', ' ', $data->event) }}
                                        </td>
                                        <td width="15%">
                                            @foreach ($data->destination as $key => $destination)
                                                <div class="col-lg-12">
                                                    <input type="checkbox" id="destination{{ $loop->index }}"
                                                        class="common-checkbox destinationCheckbox"
                                                        {{ $destination == 1 ? 'checked' : '' }} value="{{ $key }}"
                                                        name="destination{{ $loop->index }}" data-id="{{ $data->id }}"> 
                                                    <label class="primary_input_label"
                                                        for="destination{{ $loop->index }}">{{ $key }}</label>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td width="70%">
                                            <div class="d-flex recipientCards">
                                                @foreach ($data->recipient as $key => $recipient)
                                                    <div class="white-box w-100">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between">
                                                            <input type="checkbox" id="recipient{{ $loop->index }}"
                                                                class="common-checkbox recipientCheckbox"
                                                                {{ $recipient == 1 ? 'checked' : '' }} data-id="{{ $data->id }}"
                                                                value="{{ $key }}" name="recipient{{ $loop->index }}">
                                                            <label class="primary_input_label m-0"
                                                                for="recipient{{ $loop->index }}"><b>{{ $key }}</b></label>
                                                            <a class="primary-btn fix-gr-bg modalLink"
                                                                title="{{ str_replace('_', ' ', $data->event) }}[{{ $key }}]"
                                                                data-modal-size="large-modal"
                                                                href="{{ route('notification_event_modal', [$data->id, $key]) }}">@lang('common.edit')</a>
                                                        </div>
                                                        <p class="recipientCard">
                                                            {{ $data->shortcode }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(document).on('click', '.destinationCheckbox', function(e) {           
            let id = $(this).data('id');           
            let destination = $(this).val(); 
            let type = 'destination';       
            if($(this).is(':checked')){
                var status = 1;
            } else {
               var status = 0;
            }
            let formData ={
                id:id,
                destination:destination,
                status:status,
                type:type,
            }
            statusUpdate(formData);
        });
        $(document).on('click', '.recipientCheckbox', function(e) {           
            let id = $(this).data('id');           
            let recipient = $(this).val(); 
            let type = 'recipient-status';       
            if($(this).is(':checked')){
                var status = 1;
            } else {
               var status = 0;
            }
            let formData ={
                id:id,
                recipient:recipient,
                status:status,
                type:type,
            }
            statusUpdate(formData);
        });
        $(document).on('click', '.updateNotificationModal', function(e){
            let id = $('#id').val();
            let key = $('#key').val();
            let email_body = $('#email_body').val();
            let app_body = $('#app_body').val();
            let sms_body = $('#sms_body').val();
            let web_body = $('#web_body').val();
            let subject = $('#subject').val();
            let type = 'recipient';
            let formData ={
                id:id,
                key:key,
                subject:subject,
                email_body:email_body,
                app_body:app_body,
                sms_body:sms_body,
                web_body:web_body,
                status:status,
                type:type,
            }
            statusUpdate(formData);
            $('.modal').modal('hide');
        })
        function statusUpdate(formData) {
            var url = $('#url').val();
            $.ajax({
                type: "POST",
                data:formData,
                url: url+"/notification-settings-update",               
                dataType: "json",
                success: function(response) {
                    toastr.success('Operation Successfully', 'Success');
                },
                error:function(error){
                    toastr.error('Operation failed', 'Error');
                }
            });
        }
    </script>
@endpush
