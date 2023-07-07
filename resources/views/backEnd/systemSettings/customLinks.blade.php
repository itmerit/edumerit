@extends('backEnd.master')
@section('title')
@lang('front_settings.custom_links')
@endsection 
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.footer_widget')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.front_settings')</a>
                    <a href="#">@lang('front_settings.footer_widget')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">  @lang('front_settings.footer_widget_list') </h3>
                            </div> 
                                @if(userPermission('custom-links-update'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'custom-links-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }} 
                                @endif
                                <div class="white-box">

                                <div class="row">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                @lang('front_settings.operation_success_message')
                                            </div> 
                                        @endif
                                    </div>
                                </div>
                                 <div class="row">
                                                <div class="col-lg-3"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.widget') 01 </label>
                                                        <input class="primary_input_field form-control" type="text" name="title1" autocomplete="off" value="{{isset($links)?@$links->title1:''}}">
                                                        
                                                        
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.widget') 02 </label>
                                                        <input class="primary_input_field form-control" type="text" name="title2" autocomplete="off"  value="{{isset($links)?@$links->title2:''}}" >
                                                     
                                                        
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.widget') 03 </label>
                                                        <input class="primary_input_field form-control" type="text" name="title3" autocomplete="off"  value="{{isset($links)?@$links->title3:''}}" >
                                                      
                                                        
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.widget') 04 </label>
                                                        <input class="primary_input_field form-control" type="text" name="title4" autocomplete="off"  value="{{isset($links)?@$links->title4:''}}" >
                                                      
                                                        
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 01 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label1" autocomplete="off"   value="{{isset($links)?@$links->link_label1:''}}"  >
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  02 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label2" autocomplete="off"   value="{{isset($links)?@$links->link_label2:''}}" >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  03 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label3" autocomplete="off"   value="{{isset($links)?@$links->link_label3:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  04 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label4" autocomplete="off"   value="{{isset($links)?@$links->link_label4:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  

 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 01 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href1" autocomplete="off"   value="{{isset($links)?@$links->link_href1:''}}"  >
                                                        
                                                        
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 02 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href2" autocomplete="off"   value="{{isset($links)?@$links->link_href2:''}}"  >
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 03 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href3" autocomplete="off"   value="{{isset($links)?@$links->link_href3:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 04 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href4" autocomplete="off"   value="{{isset($links)?@$links->link_href4:''}}"  >
                                                     
                                                        
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** ****************** ****************** -->



 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  05 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label5" autocomplete="off"    value="{{isset($links)?@$links->link_label5:''}}"  >
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  06 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label6" autocomplete="off"    value="{{isset($links)?@$links->link_label6:''}}"  >
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  07 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label7" autocomplete="off"    value="{{isset($links)?@$links->link_label7:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label')  08 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label8" autocomplete="off"   value="{{isset($links)?@$links->link_label8:''}}"   >
                                                    
                                                        
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 05 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href5" autocomplete="off"   value="{{isset($links)?@$links->link_href5:''}}"  >
                                                        
                                                        
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 06 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href6" autocomplete="off"   value="{{isset($links)?@$links->link_href6:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 07 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href7" autocomplete="off"   value="{{isset($links)?@$links->link_href7:''}}"  >
                                                      
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 08 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href8" autocomplete="off"  value="{{isset($links)?@$links->link_href8:''}}"   >
                                                       
                                                        
                                                    </div> 
                                                </div>  


                                                <!-- ****************** ****************** ****************** ****************** -->


 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 09 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label9" autocomplete="off"  value="{{isset($links)?@$links->link_label9:''}}" >
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 10 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label10" autocomplete="off"   value="{{isset($links)?@$links->link_label10:''}}">
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 11 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label11" autocomplete="off"  value="{{isset($links)?@$links->link_label11:''}}">
                                                        
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 12 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label12" autocomplete="off"  value="{{isset($links)?@$links->link_label12:''}}">
                                                       
                                                        
                                                    </div> 
                                                </div>  

                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 09 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href9" autocomplete="off"   value="{{isset($links)?@$links->link_href9:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
 
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 10 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href10" autocomplete="off"   value="{{isset($links)?@$links->link_href10:''}}"  >
                                                      
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 11 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href11" autocomplete="off"   value="{{isset($links)?@$links->link_href11:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-20"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_url') 12 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_href12" autocomplete="off"   value="{{isset($links)?@$links->link_href12:''}}"  >
                                                      
                                                        
                                                    </div> 
                                                </div>  
                                                <!-- ****************** ****************** ****************** ****************** -->













 
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 13 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label13" autocomplete="off"   value="{{isset($links)?@$links->link_label13:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 14 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label14" autocomplete="off"   value="{{isset($links)?@$links->link_label14:''}}"  >
                                                      
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 15 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label15" autocomplete="off"   value="{{isset($links)?@$links->link_label15:''}}"  >
                                                       
                                                        
                                                    </div> 
                                                </div>  
                                                <div class="col-lg-3 mt-60"> 
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('front_settings.link_label') 16 </label>
                                                        <input class="primary_input_field form-control" type="text" name="link_label16" autocomplete="off"   value="{{isset($links)?@$links->link_label16:''}}"  >
                                                    
                                                        
                                                    </div> 
                                                </div>  

 
                                            <div class="col-lg-3 mt-20"> 
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('front_settings.link_url') 13 </label>
                                                    <input class="primary_input_field form-control" type="text" name="link_href13" autocomplete="off"   value="{{isset($links)?@$links->link_href13:''}}"  >
                                                  
                                                    
                                                </div> 
                                            </div>  

                                            <div class="col-lg-3 mt-20"> 
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('front_settings.link_url') 14 </label>
                                                    <input class="primary_input_field form-control" type="text" name="link_href14" autocomplete="off"   value="{{isset($links)?@$links->link_href14:''}}"  >
                                                    
                                                    
                                                </div> 
                                            </div>  
                                            <div class="col-lg-3 mt-20"> 
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('front_settings.link_url') 15 </label>
                                                    <input class="primary_input_field form-control" type="text" name="link_href15" autocomplete="off"   value="{{isset($links)?@$links->link_href15:''}}"  >
                                                  
                                                    
                                                </div> 
                                            </div>  
                                            <div class="col-lg-3 mt-20"> 
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('front_settings.link_url') 16 </label>
                                                    <input class="primary_input_field form-control" type="text" name="link_href16" autocomplete="off"   value="{{isset($links)?@$links->link_href16:''}}"  >
                                                  
                                                    
                                                </div> 
                                            </div>  
                                                <!-- ****************** ****************** end social ****************** ****************** -->
                                    </div>
                                    @php 
                                        $tooltip = "";
                                        if(userPermission('custom-links-update')){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($update))
                                                    @lang('front_settings.update')
                                                @else
                                                    @lang('front_settings.save')
                                                @endif
                                            </button>
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