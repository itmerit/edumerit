@extends('backEnd.master')
@section('title')
@lang('front_settings.contact_page')
@endsection
@section('mainContent')
    <style>
        .input-right-icon button.primary-btn-small-input {
            position: absolute;
            top: 7px;
            right: 10px;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.contact_page')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.front_settings')</a>
                    <a href="#">@lang('front_settings.contact_page')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            @if(isset($update))
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if(isset($update))
                                        @lang('common.edit')
                                    @else
                                        @lang('common.add')
                                    @endif
                                </h3>
                            </div>
                            @if(isset($update))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'contactPageStore',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'visitor_store',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            <div class="white-box">
                               
                                <div class="add-visitor {{isset($update)? '':'isDisabled'}}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.title')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field "
                                                            type="text" name="title" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->title:''):''}}">
                                                      
                                                        
                                                        @if ($errors->has('title'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('title') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                
                                                <div class="col-lg-4">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.button_text') <span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('button_text') ? ' is-invalid' : '' }}"
                                                            type="text" name="button_text" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->button_text:''):'' }}">
                                                       
                                                        
                                                        @if ($errors->has('button_text'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('button_text') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.button_url')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('button_text') ? ' is-invalid' : '' }}"
                                                            type="text" name="button_url" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->button_url:''):'' }}">
                                                        
                                                        
                                                        @if ($errors->has('button_url'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('button_url') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                
                                                
                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('common.address')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                            type="text" name="address" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->address:''):'' }}">
                                                        
                                                        
                                                        @if ($errors->has('address'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('address') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.address_text')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('address_text') ? ' is-invalid' : '' }}"
                                                            type="text" name="address_text" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->address_text:''):'' }}">
                                                       
                                                        
                                                        @if ($errors->has('address_text'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('address_text') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('common.phone')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                            type="text" name="phone" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->phone:''):'' }}">
                                                        
                                                        
                                                        @if ($errors->has('phone'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('phone') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        
                                        <div class="col-lg-12">
                                            <div class="row">
                                                
                                                
                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.phone_text') <span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('phone_text') ? ' is-invalid' : '' }}"
                                                            type="text" name="phone_text" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->phone_text:''):'' }}">
                                                      
                                                        
                                                        @if ($errors->has('phone_text'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('phone_text') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('common.email')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                            type="text" name="email" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->email:''):'' }}">
                                                        
                                                        
                                                        @if ($errors->has('email'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('email') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.email_text') <span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('email_text') ? ' is-invalid' : '' }}"
                                                            type="text" name="email_text" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->email_text:''):'' }}">
                                                        
                                                        
                                                        @if ($errors->has('email_text'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('email_text') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>  
                                        <div class="col-lg-12">
                                            <div class="row">
                                                
                                                
                                                <div class="col-lg-3">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.latitude')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}"
                                                            type="text" name="latitude" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->latitude:''):'' }}">
                                                       
                                                        
                                                        @if ($errors->has('latitude'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('latitude') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.longitude')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}"
                                                            type="text" name="longitude" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->longitude:''):'' }}">
                                                       
                                                        
                                                        @if ($errors->has('longitude'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('longitude') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.zoom_level')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('zoom_level') ? ' is-invalid' : '' }}"
                                                            type="text" name="zoom_level" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->zoom_level:''):'' }}">
                                                      
                                                        
                                                        @if ($errors->has('zoom_level'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('zoom_level') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>



                                                <div class="col-lg-4">
                                                    <div class="primary_input mt-25">
                                                        <label class="primary_input_label" for="">@lang('front_settings.google_map_address')<span class="text-danger"> *</span></label>
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('google_map_address') ? ' is-invalid' : '' }}"
                                                            type="text" name="google_map_address" autocomplete="off"
                                                            value="{{isset($update)? ($contact_us != ''? $contact_us->google_map_address:''):'' }}">
                                                      
                                                        
                                                        @if ($errors->has('google_map_address'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('google_map_address') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>


                                            </div>
                                        </div>      
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input mt-25">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span> </label>
                                                    <textarea class="primary_input_field form-control" cols="0" rows="5" name="description" id="description">{{isset($update)? ($contact_us != ''? $contact_us->description:''):'' }}</textarea>
                                                    
                                                    @if($errors->has('description'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        
                                    <div class="row no-gutters input-right-icon mt-35">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <input class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" id="placeholderInput" type="text"
                                                           {{-- placeholder="Image" --}}
                                                           placeholder="{{ isset($update) and $contact_us ? ($contact_us->image !="") ? getFilePath3($contact_us->image) : trans('front_settings.image') .' *' : trans('front_settings.image') .' *' }}"
                                                           readonly>
                                                    
                                                    @if($errors->has('image'))
                                                        <span class="text-danger mb-10" role="alert">
                                                            {{ $errors->first('image') }}
                                                        </span>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                           for="browseFile">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" id="browseFile" name="image">
                                                </button>
                                            </div>
                                            

                                        </div>
                                    <span class="mt-10">@lang('front_settings.image')(1420px*450px)</span>



                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('front_settings.update') </button></span>
                                            @else

                                                <button class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    @if(isset($update))
                                                        @lang('front_settings.update')
                                                    @else
                                                        @lang('front_settings.save')
                                                    @endif
                                                </button>
                                            @endif    
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
            </div>
            @endif

            <div class="row">

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mt-30 mb-30">@lang('front_settings.info')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 scroll_table">

                            <table class="table school-table-style" cellspacing="0" width="100%">

                                <thead>
                                <tr>
                                    <th width="10%">@lang('front_settings.title')</th>
                                    <th width="20%">@lang('common.description')</th>
                                    <th width="10%">@lang('front_settings.button_text')</th>
                                    <th width="10%">@lang('front_settings.button_url') </th>
                                    <th width="10%">@lang('front_settings.image')</th>
                                    <th width="10%">@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                
                                    <tr>
                                        <td width="10%">{{$contact_us != ""? $contact_us->title:""}}</td>
                                        <td width="20%">{{$contact_us != ""? $contact_us->description:""}}</td>
                                        <td width="10%">{{$contact_us != ""? $contact_us->button_text:""}}</td>
                                        <td width="10%">{{$contact_us != ""? $contact_us->button_url:""}}</td>
                                        
                                        <td width="10%">
                                            @if($contact_us != "")
                                                @if(userPermission('contactPageStore'))
                                                    <a class="primary-btn small fix-gr-bg" data-toggle="modal" data-target="#showimageModal"  href="#">@lang('common.view')</a>
                                                @endif
                                            @endif
                                        </td>
                                        @if(userPermission('contactPageEdit'))
                                            <td width="10%"><a href="{{route('contactPageEdit')}}" class="primary-btn small fix-gr-bg">@lang('common.edit')</a></td>
                                        @endif
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>


    <div class="modal fade admin-query" id="showimageModal">
    <div class="modal-dialog modal-dialog-centered max_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.view_image')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body p-0">
                <div class="container student-certificate">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 text-center">
                            <div class="mb-5">
                                <img class="img-fluid" src="{{asset($contact_us != ''? $contact_us->image:'')}}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
