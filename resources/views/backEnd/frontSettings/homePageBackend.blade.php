@extends('backEnd.master')
@section('title')
@lang('front_settings.home_page')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.home_settings_page')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.front_settings')</a>
                    <a href="#">@lang('front_settings.home_page')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">  @lang('front_settings.home_page_update') </h3>
                            </div> 
                            @if(userPermission('admin-home-page-update'))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admin-home-page-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }} 
                            @endif
                                <div class="white-box">
                                            <div class="row"> 
                                                <div class="col-lg-6"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.title')</label>
                                                        <input class="primary_input_field form-control" type="text" name="title" autocomplete="off" value="{{isset($links)?@$links->title:''}}">
                                                       
                                                        
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-6"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.heading')</label>
                                                        <input class="primary_input_field form-control" type="text" name="long_title" autocomplete="off"  value="{{isset($links)?@$links->long_title:''}}" >
                                                        
                                                        
                                                    </div> 
                                                </div>
                                            </div>   
                                            <div class="row mt-15">  
                                                <div class="col-lg-12"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.short_description') </label>
                                                        <input class="primary_input_field form-control" type="text" name="short_description" autocomplete="off" value="{{isset($links)?@$links->short_description:''}}">
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                            </div>   

 
                                            <div class="row mt-15">                                                 
                                               <script src="{{asset('public/backEnd/')}}/vendors/js/print/2.1.1_jquery.min.js"></script>
                                                <div class="col-lg-4 mt-40"> 
                                                    <img src="{{isset($links)?@$links->image:''}}" style="width: 100%; height: auto;" alt="{{isset($links)?@$links->title:''}}" id="blahImg">
                                              
                                                    
                                                    <div class="row mt-15">
                                                        
                                                        <div class="col-lg-12 mt-15">
                                                            <div class="primary_input">
                                                                <div class="primary_file_uploader">
                                                                    <input class="primary_input_field" type="text" id="placeholderFileFourName" placeholder="@lang('front_settings.upload_background_image') (1420x670)"
                                                                    readonly="">
                                                                    <button class="" type="button">
                                                                        <label class="primary-btn small fix-gr-bg" for="imgInpBac">{{ __('common.browse') }}</label>
                                                                        <input type="file" class="d-none" name="image" id="imgInpBac">
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($errors->has('image'))
                                                                <strong class="error text-danger">{{ $errors->first('image') }}
                                                        @endif
                                                    </div>
                                                </div> 

                                                <div class="col-lg-4"> </div>
                                                <div class="col-lg-4 mt-25"> 
                                                    <p>@lang('front_settings.set_permission_in_home')</strong></p>

                                       
                                        
                                                    <hr>
                                                    @foreach($permisions as $row)
                                                    <input type="checkbox" id="P{{@$row->id}}" class="common-checkbox form-control{{ $errors->has('permisions') ? ' is-invalid' : '' }}"  name="permisions[]" value="{{@$row->id}}" {{(@$row->is_published==1)? 'checked': ''}}>
                                                    <label for="P{{$row->id}}"> {{@$row->name}} </label> 
                                                    @endforeach
                                                    <span></span>

                                                </div>

                                            </div>    
                                            @php 
                                                $tooltip = "";
                                                if(userPermission('admin-home-page-update')){
                                                        $tooltip = "";
                                                    }else{
                                                        $tooltip = "You have no permission to add";
                                                    }
                                            @endphp
                                            <div class="row mt-25">
                                                <div class="col-lg-12 text-center">
                                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('front_settings.update')</button></span>
                                                        @else
                                                    <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                                        <span class="ti-check"></span> 
                                                            @lang('front_settings.update') 
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>


                            </div>
                            {{ Form::close() }}
                        </div> 
                </div>
 
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(document).on('change', '#imgInpBac', function(event){
            getFileName($(this).val(),'#placeholderFileFourName');
            imageChangeWithFile($(this)[0],'#blahImg');
        });
    </script>
@endpush

