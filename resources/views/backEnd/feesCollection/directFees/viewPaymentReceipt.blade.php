<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <title> @lang('fees.receipt')_{{@smFeesInvoice($feesInstallment->invoice_no)}}</title>
  <style>
    *{
      margin: 0;
      padding: 0;
    }
    body{
      font-size: 12px;
      font-family: 'Poppins', sans-serif;
    }
    .student_marks_table{
      width: 95%;
      margin: 10px auto 0 auto;
    }
    .text_center{
      text-align: center;
    }
    p{
      margin: 0;
      font-size: 12px;
      text-transform: capitalize;
    }
    ul{
      margin: 0;
      padding: 0;
    }
    li{
      list-style: none;
    }
    td {
    border: 1px solid var(--border_color);
    padding: .3rem;
    text-align: center;
    }
      th{
        border: 1px solid var(--border_color);
        text-transform: capitalize;
        text-align: center;
        padding: .5rem;
      }
      thead{
        font-weight:bold;
        text-align:center;
        color: #222;
        font-size: 10px
      }
      .custom_table{
        width: 100%;
      }
      table.custom_table thead th {
        padding-right: 0;
        padding-left: 0;
      }
      table.custom_table thead tr > th {
        border: 0;
        padding: 0;
      }
      table.custom_table thead tr th .fees_title{
        font-size: 12px;
        font-weight: 600;
        border-top: 1px solid #726E6D;
        padding-top: 10px;
        margin-top: 10px;
      }
      .border-top{
        border-top: 0 !important;
      }
        .custom_table th ul li {
          display: flex;
          justify-content: space-between;
        }
        .custom_table th ul li p {
          margin-bottom: 5px;
          font-weight: 500;
          font-size: 12px;
      }
      tbody td p{
        text-align: right;
      }
      tbody td{
        padding: 0.3rem;
      }
      table{
        border-spacing: 10px;
        width: 95%;
        margin: auto;
      }
      .fees_pay{
        text-align: center;
      }
      .border-0{
        border: 0 !important;
      }
      .copy_collect{
        text-align: center;
        font-weight: 500;
        color: #000;
      }

      .copyies_text{
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
      }
      .copyies_text li{
        text-transform: capitalize;
        color: #000;
        font-weight: 500;
        border-top: 1px dashed #ddd;
      }
      .school_name{
        font-size: 14px;
        font-weight: 600;
        }
      .print_btn{
        float:right;
        padding: 20px;
        font-size: 12px;
      }
      .fees_book_title{
        display: inline-block;
        width: 100%;
        text-align: center;
        font-size: 12px;
        margin-top: 5px;
        border-top: 1px solid #ddd;
        padding: 5px;
      }
      .footer{
        width: 95%;
        margin: auto;
        display: flex;
        justify-content: space-between;
        position: fixed;
        bottom: 30px;
        margin: auto;
        left: 0;
        right: 0;
      }
      .footer .footer_widget{
        width: 30%;
      }
      .footer .footer_widget .copyies_text{
        justify-content: space-between;
      }
  </style>
  <style type="text/css" media="print">
      @page { size: A4 landscape; }
  </style>
  </head>
  <script>
    var is_chrome = function () { return Boolean(window.chrome); }
      if(is_chrome){
           window.print();
      //      setTimeout(function(){window.close();}, 10000);
      //      give them 10 seconds to print, then close
        }else{
           window.print();
        }
  </script>
  <body onLoad="loadHandler();">
        @php  
          $setting = generalSetting();
        @endphp
      <div class="student_marks_table print" >
      <table class="custom_table">
        <thead>
          <tr>
            <!-- first header  -->
            <th colspan="2">
              <div style="float:left; width:30%">
                      @if (file_exists($setting->logo))
                      <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                    @endif
              </div>
              <div style="float:right; width:70%; text-aligh:left">
                      <h4 class="school_name">{{$setting->school_name}}</h4>
                      <p>{{$setting->address}}</p>
              </div>
                <h4 class="fees_book_title" style="display:inline-block"></h4>
              <ul>
                <li>
                  <p>
                    @lang('student.admission_no'): {{@$student->studentDetail->admission_no}}
                  </p> 
                  <p>
                    @lang('common.date'): {{date('d/m/Y')}}
                  </p>
                </li>
                <li>
                  <p>
                    @lang('student.student_name'): {{@$student->studentDetail->full_name}} 
                  </p>
                  <p>
                    @lang('student.roll'):{{@$student->studentDetail->roll_no}}
                    
                  </p>
                </li>
                

                <li>
                  <p>
                    @if(moduleStatusCheck('University'))
                    @lang('university::un.department'): {{@$student->unDepartment->name}}
                    @else 
                    @lang('common.class_section'): {{@$student->class->class_name}} ({{@$student->section->section_name}})
                    @endif 
                  </p> 
                  <p><strong>
                    @lang('fees.payment_date'): {{dateConvert($feesInstallment->paid_date)}}
                  </strong></p>
                </li>
                <li>
                  @if(@moduleStatusCheck('University'))
                  <p>
                    @lang('common.section'): {{@$student->section->section_name}}
                  </p> 
                  @endif 
                  
                   <p><strong>@lang('fees.payment_id'): {{@smFeesInvoice($feesInstallment->invoice_no)}} </strong> </p> 
                </li>
              </ul>
            </th>
            <!-- space  -->
            <th class="border-0" rowspan="9"></th>

            <!-- 2nd header  -->
            <th colspan="2">
                  <div style="float:left; width:30%">
                          @if (file_exists($setting->logo))
                          <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                        @endif
                  </div>
                  <div style="float:right; width:70%; text-aligh:left">
                          <h4 class="school_name">{{$setting->school_name}}</h4>
                          <p>{{$setting->address}}</p>
                  </div>
                    <h4 class="fees_book_title" style="display:inline-block"></h4>
                  <ul>
                    <li>
                      <p>
                        @lang('student.admission_no'): {{@$student->studentDetail->admission_no}}
                      </p> 
                      <p>
                        @lang('common.date'): {{date('d/m/Y')}}
                      </p>
                    </li>
                    <li>
                      <p>
                        @lang('student.student_name'): {{@$student->studentDetail->full_name}} 
                      </p>
                      <p>
                        @lang('student.roll'):{{@$student->studentDetail->roll_no}}
                        
                      </p>
                    </li>
                    
    
                    <li>
                      <p>
                       @if(moduleStatusCheck('University'))
                    @lang('university::un.department'): {{@$student->unDepartment->name}}
                    @else 
                    @lang('common.class_section'): {{@$student->class->class_name}} ({{@$student->section->section_name}})
                    @endif  
                      </p> 
                      <p><strong>
                        @lang('fees.payment_date'): {{dateConvert($feesInstallment->paid_date)}}
                      </strong></p>
                    </li>
                    <li>
                      @if(@moduleStatusCheck('University'))
                      <p>
                        @lang('common.section'): {{@$student->section->section_name}}
                      </p> 
                      @endif 
                      
                       <p><strong>@lang('fees.payment_id'): {{@smFeesInvoice($feesInstallment->invoice_no)}} </strong> </p> 
                    </li>
                  </ul>
                </th>

            <th class="border-0" rowspan="9"></th>
            <!-- space  -->

            <!-- 3rd header  -->
            <th colspan="2">
                  <div style="float:left; width:30%">
                          @if (file_exists($setting->logo))
                          <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                        @endif
                  </div>
                  <div style="float:right; width:70%; text-aligh:left">
                          <h4 class="school_name">{{$setting->school_name}}</h4>
                          <p>{{$setting->address}}</p>
                  </div>
                    <h4 class="fees_book_title" style="display:inline-block"></h4>
                  <ul>
                    <li>
                      <p>
                        @lang('student.admission_no'): {{@$student->studentDetail->admission_no}}
                      </p> 
                      <p>
                        @lang('common.date'): {{date('d/m/Y')}}
                      </p>
                    </li>
                    <li>
                      <p>
                        @lang('student.student_name'): {{@$student->studentDetail->full_name}} 
                      </p>
                      <p>
                        @lang('student.roll'):{{@$student->studentDetail->roll_no}}
                        
                      </p>
                    </li>
                    
    
                    <li>
                      <p>
                        @if(moduleStatusCheck('University'))
                    @lang('university::un.department'): {{@$student->unDepartment->name}}
                    @else 
                    @lang('common.class_section'): {{@$student->class->class_name}} ({{@$student->section->section_name}})
                    @endif 
                      </p> 
                      <p><strong>
                        @lang('fees.payment_date'): {{dateConvert($feesInstallment->paid_date)}}
                      </strong></p>
                    </li>
                    <li>
                      @if(@moduleStatusCheck('University'))
                      <p>
                        @lang('common.section'): {{@$student->section->section_name}}
                      </p> 
                      @endif 
                       <p><strong>@lang('fees.payment_id'): {{@smFeesInvoice($feesInstallment->invoice_no)}} </strong> </p> 
                    </li>
                  </ul>
                </th>

          </tr>
        </thead>
        <tbody>
            <tr>
              <!-- first header  -->
                <th>@lang('fees.fees_details')</th>
                <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>
                <!-- space  -->
                <th class="border-0" rowspan="8" ></th>
                <!-- 2nd header  -->
                <th>@lang('fees.fees_details')</th>
                <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>
                <th class="border-0" rowspan="8" ></th>
                <!-- 3rd header  -->
                <th>@lang('fees.fees_details')</th>
                <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>
            </tr>
        @php
          $grand_total = discountFees($feesInstallment->direct_fees_installment_assign_id);
          $total_fine = 0;
          $total_discount = 0;
          $total_paid = $feesInstallment->paid_amount + $oldPayments;
          $total_grand_paid = $feesInstallment->paid_amount + $oldPayments;
          $total_balance =  $grand_total - $feesInstallment->paid_amount;
          $totalpayable= $total_balance;
          $discount_amount = 0;
          $fine  = 0;
          $paid  = 0;
        @endphp


      <tr>
          {{-- 1st warp start here  --}}
            <td class="border-top">
            <strong>
                <p>
                  {{@$feesInstallment->installmentAssign->installment->title }}
                   
                </p>
                
                  @if($oldPayments > 0)
                  <p>
                    @lang('fees.previously_paid')(+)
                  </p>
                  @endif
                
                
            </strong>

                @if ($feesInstallment->installmentAssign->discount_amount>0)
                  <p>
                    <strong>
                      @lang('fees.discount')(-)
                    </strong> 
                  </p>
                @endif
                @if ($fine>0)
                  <p> 
                    <strong>
                      @lang('fees.fine')(+)
                    </strong> 
                  </p>
                @endif
                @if ($feesInstallment->paid_amount != 0)
                  <p> 
                    @if(discountFees($feesInstallment->direct_fees_installment_assign_id) == $feesInstallment->paid_amount)
                        @lang('fees.paid') (+)
                    @else 
                        @lang('fees.partial_payemnt') (+)
                    @endif    
                  </p>
                  @if($feesInstallment->active_status == 1)
                  <p> 
                    <strong>
                      [{{$feesInstallment->payment_mode}}]
                    </strong> 
                  </p>
                  @endif
                  @else 
                  <p> 
                    <strong>
                      [@lang('fees.unpaid')]  
                    </strong> 
                  </p>

                @endif

                  
            </td>
            <td class="border-top" style="text-align: right">
                {{@number_format($feesInstallment->installmentAssign->amount, 2, '.', '') }} 
                @if($oldPayments > 0)
                <p> {{@number_format($oldPayments, 2, '.', '') }} </p>
                @endif
                @if ($feesInstallment->installmentAssign->discount_amount>0)
                <p>
                {{@$feesInstallment->installmentAssign->discount_amount}}
                </p>
                @endif 
            
                @if ($feesInstallment->paid_amount != 0 )
                <p>
                  {{number_format($feesInstallment->paid_amount, 2, '.', '')}}
                </p>
                @endif
                <br>
            </td>
          {{-- 1st warp End  --}}
                    {{-- 1st warp start here  --}}
                    <td class="border-top">
                      <strong>
                          <p>
                            {{@$feesInstallment->installmentAssign->installment->title }}
                             
                          </p>
                          @if($oldPayments > 0)
                          <p>
                            @lang('fees.previously_paid')(+)
                          </p>
                          @endif
                          
                      </strong>
          
                          @if ($feesInstallment->installmentAssign->discount_amount>0)
                            <p>
                              <strong>
                                @lang('fees.discount')(-)
                              </strong> 
                            </p>
                          @endif
                          @if ($fine>0)
                            <p> 
                              <strong>
                                @lang('fees.fine')(+)
                              </strong> 
                            </p>
                          @endif
                          @if ($feesInstallment->paid_amount != 0)
                            <p> 
                              @if(discountFees($feesInstallment->direct_fees_installment_assign_id) == $feesInstallment->paid_amount)
                                  @lang('fees.paid') (+)
                              @else 
                                  @lang('fees.partial_payemnt') (+)
                              @endif    
                            </p>
                            @if($feesInstallment->active_status == 1)
                            <p> 
                              <strong>
                                [{{$feesInstallment->payment_mode}}]
                              </strong> 
                            </p>
                            @endif
                            @else 
                            <p> 
                              <strong>
                                [@lang('fees.unpaid')]  
                              </strong> 
                            </p>
          
                          @endif
          
                            
                      </td>
                      <td class="border-top" style="text-align: right">
                          {{@number_format($feesInstallment->installmentAssign->amount, 2, '.', '') }} 
                          @if($oldPayments > 0)
                          <p> {{@number_format($oldPayments, 2, '.', '') }} </p>
                          @endif
                          @if ($feesInstallment->installmentAssign->discount_amount>0)
                          <p>
                          {{@$feesInstallment->installmentAssign->discount_amount}}
                          </p>
                          @endif 
                      
                          @if ($feesInstallment->paid_amount != 0 )
                          <p>
                            {{number_format($feesInstallment->paid_amount, 2, '.', '')}}
                          </p>
                          @endif
                          <br>
                      </td>
                {{-- 1st warp End  --}}
                          {{-- 1st warp start here  --}}
                          <td class="border-top">
                            <strong>
                                <p>
                                  {{@$feesInstallment->installmentAssign->installment->title }}
                                   
                                </p>
                                @if($oldPayments > 0)
                                <p>
                                  @lang('fees.previously_paid')(+)
                                </p>
                                @endif
                                
                            </strong>
                
                                @if ($feesInstallment->installmentAssign->discount_amount>0)
                                  <p>
                                    <strong>
                                      @lang('fees.discount')(-)
                                    </strong> 
                                  </p>
                                @endif
                                @if ($fine>0)
                                  <p> 
                                    <strong>
                                      @lang('fees.fine')(+)
                                    </strong> 
                                  </p>
                                @endif
                                @if ($feesInstallment->paid_amount != 0)
                                  <p> 
                                    @if(discountFees($feesInstallment->direct_fees_installment_assign_id) == $feesInstallment->paid_amount)
                                        @lang('fees.paid') (+)
                                    @else 
                                        @lang('fees.partial_payemnt') (+)
                                    @endif    
                                  </p>
                                  @if($feesInstallment->active_status == 1)
                                  <p> 
                                    <strong>
                                      [{{$feesInstallment->payment_mode}}]
                                    </strong> 
                                  </p>
                                  @endif
                                  @else 
                                  <p> 
                                    <strong>
                                      [@lang('fees.unpaid')]  
                                    </strong> 
                                  </p>
                
                                @endif
                
                                  
                            </td>
                            <td class="border-top" style="text-align: right">
                                {{@number_format($feesInstallment->installmentAssign->amount, 2, '.', '') }} 
                                @if($oldPayments > 0)
                                <p> {{@number_format($oldPayments, 2, '.', '') }} </p>
                                @endif
                                @if ($feesInstallment->installmentAssign->discount_amount>0)
                                <p>
                                {{@$feesInstallment->installmentAssign->discount_amount}}
                                </p>
                                @endif 
                            
                                @if ($feesInstallment->paid_amount != 0 )
                                <p>
                                  {{number_format($feesInstallment->paid_amount, 2, '.', '')}}
                                </p>
                                @endif
                                <br>
                            </td>
        {{-- 1st warp End  --}}
      </tr>
      {{-- University End Here  --}}       
          @php
              $totalpayable=$totalpayable;
              if ($totalpayable<0) {
                  $totalpayable=0.00;
              } else {
                $totalpayable=$totalpayable;
              }
          @endphp
          <tr>
            <td>
              <p>
                <strong>
                  @lang('fees.grand_total')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $grand_total, 2, '.', '')}} </strong>
             </td>
            <td>
              <p>
                <strong>
                  @lang('fees.grand_total')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $grand_total, 2, '.', '')}} </strong>
             </td>
            <!-- 3rd td wrap  -->
            <td>
              <p>
                <strong>
                  @lang('fees.grand_total')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $grand_total, 2, '.', '')}} </strong>
             </td>
          </tr>


          <tr>
            <td>
              <p>
                <strong>
                  @lang('fees.total_paid')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $total_grand_paid, 2, '.', '')}} </strong>
             </td>
            <td>
              <p>
                <strong>
                  @lang('fees.total_paid')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $total_grand_paid, 2, '.', '')}} </strong>
             </td>
            <!-- 3rd td wrap  -->
            <td>
              <p>
                <strong>
                  @lang('fees.total_paid')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $total_grand_paid, 2, '.', '')}} </strong>
             </td>
          </tr>

          <tr>
            <td>
              <p>
                <strong>
                  @lang('fees.total_payable_amount')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) ($grand_total - $total_grand_paid), 2, '.', '')}} </strong>
             </td>
            <td>
              <p>
                <strong>
                  @lang('fees.total_payable_amount')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) ($grand_total - $total_grand_paid), 2, '.', '')}} </strong>
             </td>
            <!-- 3rd td wrap  -->
            <td>
              <p>
                <strong>
                  @lang('fees.total_payable_amount')
                </strong>
              </p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) ($grand_total - $total_grand_paid), 2, '.', '')}} </strong>
             </td>
          </tr>
          
          <tr>
          </tr>

          <tr>
                <td colspan="2" >
                  @lang('fees.if_unpaid_admission_will_be_cancelled_after')
                </td>
                <!-- 2nd td wrap  -->
                <td colspan="2" >
                  @lang('fees.if_unpaid_admission_will_be_cancelled_after')
                </td>
                <!-- 3rd td wrap  -->
                <td colspan="2" >
                  @lang('fees.if_unpaid_admission_will_be_cancelled_after')
                </td>
          </tr>

          <tr>
                <td colspan="2">
                  <p class="parents_num text_center"> 
                    @lang('fees.parents_phone_number') : 
                    <span>
                      {{@$parent->guardians_mobile}}
                    </span> 
                  </p>
                </td>
                
                <!-- 2nd td wrap  -->
                <td colspan="2">
                  <p class="parents_num text_center"> 
                    @lang('fees.parents_phone_number') : 
                    <span>
                      {{@$parent->guardians_mobile}}
                    </span> 
                  </p>
                </td>
                <!-- 2nd td wrap  -->
                <td colspan="2">
                  <p class="parents_num text_center"> 
                    @lang('fees.parents_phone_number') : 
                    <span>
                      {{@$parent->guardians_mobile}}
                    </span> 
                  </p>
                </td>
          </tr>
        </tbody>
      </table>
    </div>
<footer class="footer" >
  <div class="footer_widget">
    <ul class="copyies_text">
      <li>@lang('fees.parent/student')</li>
      <li>@lang('fees.cashier')</li>
      <li>@lang('fees.officer')</li>
    </ul>
    <p class="copy_collect">
      @lang('fees.parent/student_copy')
    </p>
  </div>
  <div class="footer_widget">
      <ul class="copyies_text">
        <li>@lang('fees.parent/student')</li>
        <li>
          @lang('fees.cashier')
        </li>
        <li>
          @lang('fees.officer')
        </li>
      </ul>
      <p class="copy_collect">
        @lang('fees.parent/student_copy')
      </p>
    </div>
    <div class="footer_widget">
        <ul class="copyies_text">
          <li>@lang('fees.parent/student')</li>
          <li>
            @lang('fees.cashier')
          </li>
          <li>
            @lang('fees.officer')
          </li>
        </ul>
        <p class="copy_collect">
          @lang('fees.parent/student_copy')
        </p>
      </div>
</footer>
  <script>
    function printInvoice() {
      window.print();
    }
  </script>
  <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/jquery-3.2.1.slim.min.js"></script>
  <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/popper.min.js"></script>
  <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/bootstrap.min.js"></script>
</body>
</html>
