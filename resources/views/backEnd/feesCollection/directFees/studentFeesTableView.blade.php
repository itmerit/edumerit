<table id="" class="table school-table-style-parent-fees" cellspacing="0" width="100%">
      <thead>
          <tr>
              <th class="nowrap">@lang('university::un.installment') </th>
              <th class="nowrap">@lang('fees.amount') ({{@generalSetting()->currency_symbol}})</th>
              <th class="nowrap">@lang('fees.due_date') </th>
              <th class="nowrap">@lang('common.status')</th>
              <th class="nowrap">@lang('fees.mode')</th>
              <th class="nowrap">@lang('university::un.payment_date')</th>
              <th class="nowrap">@lang('fees.discount') ({{@generalSetting()->currency_symbol}})</th>
              <th class="nowrap">@lang('fees.paid') ({{@generalSetting()->currency_symbol}})</th>
              <th class="nowrap">@lang('common.action')</th>
        
              
          </tr>
      </thead>
      <tbody>
            @foreach($record->feesInstallments as $key=> $feesInstallment )
            <tr>
                
                  <td>{{@$feesInstallment->installment->title}}</td>
                  <td> 
                      @if($feesInstallment->discount_amount > 0)
                      <del>  {{$feesInstallment->amount}}  </del>
                        {{$feesInstallment->amount - $feesInstallment->discount_amount}}
                        @else 
                         {{$feesInstallment->amount}}
                      @endif 
                    </td>
                  <td>{{@dateConvert($feesInstallment->due_date)}}</td>
                  <td>
                    @if($feesInstallment->active_status == 1 && $feesInstallment->paid_amount)
                    <button class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                    @else 
                    <button class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
                    @endif 
                  </td>
                  <td>
                      @if(is_null($feesInstallment->payment_mode))
                        -- 
                      @else
                      {{ $feesInstallment->payment_mode}}
                      @endif 
                  </td>
                  <td>{{@dateConvert($feesInstallment->payment_date)}}</td>
                  <td>  {{$feesInstallment->discount_amount}}</td>
                  <td>{{$feesInstallment->paid_amount}}</td>
                  <td>
                      <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                            @lang('common.select')
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if($feesInstallment->active_status !=1)
                            <a data-toggle="modal"
                            data-target="#editInstallment_{{$feesInstallment->id}}" class="dropdown-item">@lang('common.edit')</a>
                            @endif 
                        </div>
                    </div>
                </td>
                 
            </tr>

            <div class="modal fade admin-query" id="editInstallment_{{$feesInstallment->id}}">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                @lang('university::un.fees_installment')
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body"> 
                            {{ Form::open(['class' => 'form-horizontal','files' => true,'route' => 'university.feesInstallmentUpdate','method' => 'POST']) }}
                            <div class="row">
                                <input type="hidden" name="installment_id" value="{{$feesInstallment->id}}">
                                <div class="col-lg-6">
                                    <div class="primary_input ">
                                        <input class="primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="amount" id="amount" value="{{ $feesInstallment->amount}}">
                                        <label class="primary_input_label" for="">@lang('fees.amount') <span class="text-danger"> *</span> </label>
                                        
                                        @if ($errors->has('amount'))
                                        <span class="text-danger" >
                                            <strong>{{ @$errors->first('amount') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input ">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="due_date" value="{{@dateConvert($feesInstallment->installment->due_date)}}" autocomplete="off">
                                                    <label class="primary_input_label" for="">@lang('fees.due_date') <span class="text-danger"> *</span></label>
                                                    
                                                @if ($errors->has('due_date'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('due_date') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-5 text-center">
                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('common.update')
                                </button>
                            </div>
    
                            {{ Form::close() }}
                           
                        </div>
    
                    </div>
                </div>
            </div>


            @endforeach

      </tbody>
  </table>

  @include('backEnd.partials.date_picker_css_js')