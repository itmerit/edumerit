@extends('backEnd.master')
@section('mainContent')
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <select class="primary_select ">
                                        <option data-display="Select Class">@lang('student.select_month')</option>
                                        <option value="1"> @lang('student.may') </option>
                                        <option value="2"> @lang('student.june') </option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mt-30-md">
                                    <select class="primary_select ">
                                        <option data-display="Select Class">@lang('common.select_package')</option>
                                        <option value="1">@lang('common.infix_edu')</option>
                                        <option value="2">@lang('common.infix_clasified')</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>

            <div class="row mt-40">
                

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-20">@lang('common.purchase_list')</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                    		<div class="white-box">
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                              
                                    <tr>
                                        <th>@lang('common.sl_no').</th> 
                                        <th>@lang('common.package')</th>
                                        <th>@lang('common.purchase_date')</th>
                                        <th>@lang('common.expaire_date')</th>
                                        <th>@lang('fees.price')</th>
                                        <th>@lang('fees.paid_amount')</th>
                                        <th>@lang('fees.due_amount')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($ProductPurchase as $p)
                                    <tr>
                                        <td>{{ @$p->id}}</td>
                                        <td>{{ @$p->package}}</td> 
                                        <td>{{ @$p->purchase_date}}</td> 
                                        <td>{{ @$p->expaire_date}}</td> 
                                        <td>{{ @$p->price}}</td> 
                                        <td>{{ @$p->paid_amount}}</td> 
                                        <td>{{ @$p->due_amount}}</td> 
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    @lang('common.edit')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">@lang('common.view')</a>
                                                    <a class="dropdown-item" href="#">@lang('common.download')</a> 
                                                </div>
                                            </div>
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

            
    </div>
</section>
  

@endsection
@include('backEnd.partials.data_table_js')