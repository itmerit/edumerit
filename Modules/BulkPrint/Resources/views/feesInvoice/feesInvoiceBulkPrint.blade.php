<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('fees::feesModule.view_fees_invoice')</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    body{
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        margin: 0;
        padding: 0;
        counter-reset: Serial;
    }

    .big-table table
    {
        border-collapse: separate;
    }

    .big-table tbody tr td:first-child:before
    {
        counter-increment: Serial;
        content: counter(Serial);
    }

    .fees_custom_preview p{
        font-size: 18px;
        font-weight: 500;
    }

    .fees_invoice_type_div{
        border-radius:10px 10px 0 0;
    }

    .fees_invoice_type_table{
        border-radius:0 0 10px 10px;
    }

    .max_modal {
        max-width: 1050px;
    }
    .invoice_text{
        margin-bottom: 0;
        font-size: 16px;
        margin-bottom: 0;
    }
    .invoice_header{
        display: flex;
        align-items: center;
        justify-content: space-between;
        grid-gap: 20px;
        margin-bottom: 30px;
    }
    .invoice_header h3{
        flex:  1 1 auto;
        font-size: 30px;
    }
    .invoice_header img {
       max-width: 250px;
    }
    .school-table-style tfoot tr td {
        padding: 10px 10px 10px 0px;
    }



    /* Invoice View Table */
    table {
        border-collapse: collapse;
    }
    h1,h2,h3,h4,h5,h6{
        margin: 0;
        color: #00273d;
    }
    .invoice_wrapper{
        max-width: 1200px;
        margin: auto;
        background: #fff;
        padding: 70px 30px;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }
    .border_none{
        border: 0px solid transparent;
        border-top: 0px solid transparent !important;
    }
    .invoice_part_iner{
        background-color: #fff;
    }
    .invoice_part_iner h4{
        font-size: 30px;
        font-weight: 500;
        margin-bottom: 40px;

    }
    .invoice_part_iner h3{
        font-size:25px;
        font-weight: 500;
        margin-bottom: 5px;

    }
    .table_border thead{
        background-color: #F6F8FA;
    }
    .table td, .table th {
        padding: 5px 0;
        vertical-align: top;
        border-top: 0 solid transparent;
        color: #79838b;
    }
    .table td , .table th {
        padding: 5px 0;
        vertical-align: top;
        border-top: 0 solid transparent;
        color: #79838b;
    }
    .table_border tr{
        border-bottom: 1px solid #828bb2!important;
    }
    th p span, td p span{
        color: #828bb2;
    }
    .table th {
        color: #00273d;
        font-weight: 300;
        border-bottom: 1px solid #f1f2f3 !important;
        background-color: #fafafa;
    }

    p{
        font-size: 14px;
        color: #828bb2;
    }
    h5{
        font-size: 12px;
        font-weight: 500;
    }
    h6{
        font-size: 10px;
        font-weight: 300;
    }
    .mt_40{
        margin-top: 40px;
    }
    .table_style th, .table_style td{
        padding: 20px;
    }
    .invoice_info_table td{
        font-size: 10px;
        padding: 0px;
    }
    .invoice_info_table td h6{
        color: #6D6D6D;
        font-weight: 400;
        }

    .text_right{
        text-align: right;
    }
    .virtical_middle{
        vertical-align: middle !important;
    }
    .logo_img {
        max-width: 120px;
    }
    .logo_img img{
        width: 100%;
    }
    .border_bottom{
        border-bottom: 1px solid #828bb2;
    }
    p{
        margin: 0;
    }
    .font_18 {
        font-size: 18px;
    }
    .mb-0{
        margin-bottom: 0;
    }
    .mb_30{
        margin-bottom: 30px !important;
    }
    .border_table thead tr th {
        padding: 10px;
    }
    .border_table tbody tr td {
        border-bottom: 0;
        text-align: center;
        padding: 10px;
    }
    .title_header{
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 40px 0 15px 0;
    }
    .address_text p{
        max-width: 200px;
        font-size: 14px;
        margin-bottom: 0;
        color: #828BB2;
        font-weight: 400;
        margin-right: 0;
        margin-left: auto;
    }
    .addressleft_text{
        max-width: 200px;
        font-size: 14px;
        margin-bottom: 0;
        color: #828bb2;
        font-weight: 400;
    }
    .addressleft_text span{
        color: #828bb2;
        display: block;
        white-space: nowrap;
    }
    .addressright_text{
        width: 250px;
        border-radius: 20px;
        padding: 20px;
        margin-right: 0;
        margin-left: auto;
    }
    .addressright_text p span{
        color: #828bb2;
    }
    .description_table th{
        color: #fff;
        font-weight: 300;
        border-bottom: 0;
        background-color: transparent;
        text-align: center;
    }
    .description_table th:first-child{
        border-radius: 10px 0px 0px 10px;
    }
    .description_table th:last-child{
        border-radius: 0px 10px 10px 0px;
    }
    .description_table tbody td{
        background: transparent;
        color: #828bb2;
        border-bottom: 0;
    }
    tfoot .footer_text{
        color: #828bb2;
        margin-top: 10px;
    }
    tfoot .footer_text  span{
        display: block;
        color: #828bb2;
        margin-top: 10px;
    }
    .total_count{
        display: flex;
        justify-content: space-between;
    }
    .mt_20{
        margin-top: 20px;
    }
    .total_amountPay{
        display: flex;
        width: 250px;
        border-radius: 10px;
        background: #374E6B;
        padding: 15px 20px;
        justify-content: space-between;
        margin-left: auto;
        margin-right: 0;
    }
    .total_amountPay span{
        color: #fff;
        font-size: 14px;
    }
    .table_footer p{
        font-size: 14px;
       color: #828BB2;
    }
    .footer_logo p{
        font-size: 14px;
        color: #828BB2;
    }
    .footer_logo img{
        max-width: 100px;
    }
    .footer_logo {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    html, body {
        height: 100%;
    }
    .invoice_wrapper{
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .invoice_footer{
        flex: 1 1 auto;
        display: flex;
        align-items: flex-end;
    }


    .addressright_text p > span {
        flex: 40% 0 0;
    }

    .addressright_text p {
        color: #fff;
        font-size: 14px;
        font-weight: 300;
        display: flex;
        align-items: center;
        white-space: nowrap;
        grid-gap: 20px;
    }

    .addressright_text {
        width: auto;
        border-radius: 20px;
        padding: 20px;
        margin-right: 0;
        margin-left: auto;
    }

    .total_count {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #f1f2f3 !important;
        padding-bottom: 5px;
        padding-right: 0;
        max-width: 280px;
        margin-right: 0;
        margin-left: auto;
    }

    .addressleft_text p {
        display: flex;
        grid-gap: 20px;
    }

    .addressleft_text p > span {
        flex: 61% 0 0;
        white-space: nowrap;
    }

    .table thead th {
        color: var(--base_color);
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        border-top: 0px;
        padding: 12px 12px 12px 0px;
    }
    .text-right-print{
        text-align: right !important;
    }

    .pr-0{
        padding-right: 0px !important;
    }

    .col-lg-10 {
        -ms-flex: 0 0 83.333333%;
        flex: 0 0 83.333333%;
        max-width: 83.333333%;
    }
    </style>
    <script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome)
        {
            window.print();
        // setTimeout(function(){window.close();}, 10000);
        //give them 10 seconds to print, then close
        }
        else
        {
            window.print();
        }
    </script>
    <body onLoad="loadHandler();">
        @foreach($invoices  as $invoice)
            <section class="admin-visitor-area up_st_admin_visitor">
                <div class="invoice_wrapper">
                    <!-- invoice print part here -->
                    <div class="invoice_print">
                        <div class="container">
                            <div class="invoice_part_iner">
                                <table class="table">
                                    <thead>
                                        <td>
                                            <div class="logo_img">
                                                <img  src="{{asset(generalSetting()->logo)}}" alt="{{generalSetting()->school_name}}">
                                            </div>
                                        </td>
                                        <td class="virtical_middle address_text">
                                            <p>{{generalSetting()->school_name}}</p>
                                            <p>{{generalSetting()->phone}}</p>
                                            <p>{{generalSetting()->email}}</p>
                                            <p>{{generalSetting()->address}}</p>
                                        </td>
                                    </thead>
                                </table>
                                <!-- middle content  -->
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                            <!-- single table  -->
                                            <table class="mb_30">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="addressleft_text">
                                                                <p><span>@lang('fees.fees_invoice_issued_to')</p>
                                                                <p><span><strong>@lang('student.student_name') </span> <span class="nowrap">: {{$invoice->studentInfo->full_name}}</span> </strong></p>
                                                                <p><span>@lang('student.class_section')</span> <span>: {{$invoice->recordDetail->class->class_name}} ({{$invoice->recordDetail->section->section_name}})</span> </p>
                                                                <p><span>@lang('student.roll_no')</span> <span>: {{$invoice->recordDetail->roll_no}}</span> </p>
                                                                <p><span>@lang('student.admission_no')</span> <span>: {{$invoice->studentInfo->admission_no}}</span> </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--/ single table  -->
                                            </td>
                                            <td >
                                                <!-- single table  -->
                                                <table class="mb_30" style="margin-left: auto; margin-right: 0;">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                @php
                                                                    $subTotal = $invoice->invoiceDetails->sum('sub_total');
                                                                    $paidAmount = $invoice->invoiceDetails->sum('paid_amount');
                                                                    $paymentStatus = $subTotal - $paidAmount;
                                                                @endphp
                                                                <div class="addressright_text">
                                                                    <p><span><strong>@lang('fees.invoice_number')</span> <span>: {{$invoice->invoice_id}}</span> </strong></p>
                                                                    <p><span>@lang('fees.create_date') </span> <span>: {{dateConvert($invoice->create_date)}}</span> </p>
                                                                    <p><span>@lang('fees.due_date') </span> <span>: {{dateConvert($invoice->due_date)}}</span> </p>
                                                                    <p>
                                                                        <span>
                                                                            @lang('fees.payment_status')
                                                                        </span>
                                                                        <span>:
                                                                            @if ($paymentStatus == 0)
                                                                                @lang('fees.paid')
                                                                            @else
                                                                                @if ($paidAmount > 0)
                                                                                    @lang('fees.partial')
                                                                                @else
                                                                                    @lang('fees.unpaid')
                                                                                @endif
                                                                            @endif
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!--/ single table  -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- invoice print part end -->
                    <table class="table border_table mb_30 description_table" >
                        <thead>
                        <tr>
                            <th>@lang('common.sl')</th>
                            <th>@lang('fees.fees_type')</th>
                            <th>@lang('accounts.amount')</th>
                            <th>@lang('fees.waiver')</th>
                            <th>@lang('fees.fine')</th>
                            <th>@lang('fees::feesModule.paid_amount')</th>
                            <th class="text-right-print">@lang('fees.sub_total')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $amount = 0;
                            $weaver = 0;
                            $paid_amount = 0;
                            $fine = 0;
                            $grand_total = 0;
                            $balance = 0;
                        @endphp
                        @foreach ($invoice->invoiceDetails as $key=>$invoiceDetail)
                            @php
                                $amount += $invoiceDetail->amount;
                                $weaver += $invoiceDetail->weaver;
                                $fine += $invoiceDetail->fine;
                                $paid_amount += $invoiceDetail->paid_amount;

                                $totalAmount = ($invoiceDetail->amount + $invoiceDetail->fine) - $invoiceDetail->weaver;
                                $grand_total += $totalAmount;

                                $total = ($invoiceDetail->amount+ $invoiceDetail->fine) - ($invoiceDetail->paid_amount + $invoiceDetail->weaver);
                                $balance += $total;
                            @endphp
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{@$invoiceDetail->feesType->name}}</td>
                                <td>{{($invoiceDetail)? $invoiceDetail->amount : 0.00}}</td>
                                <td>{{($invoiceDetail->weaver)? $invoiceDetail->weaver : 0}}</td>
                                <td>{{($invoiceDetail->fine)? $invoiceDetail->fine : 0}}</td>
                                <td>{{($invoiceDetail->paid_amount)? $invoiceDetail->paid_amount : 0}}</td>
                                <td class="text-right-print pr-0">{{currency_format($total)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">
                                <p class="total_count"><span>@lang('fees::feesModule.total_amount')</span> <span>{{currency_format($amount)}}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">
                                <p class="total_count"><span>@lang('fees::feesModule.total_waiver')</span> <span>{{currency_format($weaver)}}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">
                                <p class="total_count"><span>@lang('fees::feesModule.total_fine')</span> <span>{{currency_format($fine)}}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">
                                <p class="total_count"><span>@lang('fees::feesModule.total_paid')</span> <span>{{currency_format($paid_amount)}}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">
                                <p class="total_count"><span>@lang('accounts.grand_total')</span> <span>{{currency_format($grand_total)}}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">
                                <p class="total_count"><span><strong>@lang('fees::feesModule.due_balance')</span> <span>
                                       {{currency_format($balance)}}
                                        </strong></span></p>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                    <div class="col-lg-10">
                        <table class="table border_table mb_30 description_table" >
                            <thead>
                                <tr>
                                    <th>@lang('common.sl')</th>
                                    <th>@lang('accounts.bank_name')</th>
                                    <th>@lang('accounts.account_name')</th>
                                    <th>@lang('accounts.account_number')</th>
                                    <th>@lang('accounts.account_type')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banks as $key=>$bank)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$bank->bank_name}}</td>
                                        <td>{{$bank->account_name}}</td>
                                        <td>{{$bank->account_number}}</td>
                                        <td>{{$bank->account_type}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endforeach
    </body>
</html>