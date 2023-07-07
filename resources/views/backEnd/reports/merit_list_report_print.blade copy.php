<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('exam.mark_sheet_report')</title>
    <style>
        body{
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact;
        }
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
            padding: 20px;
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
            border-bottom: 1px solid #000 !important;
        }
        th p span, td p span{
            color: #212E40;
        }
        .table th {
            color: #00273d;
            font-weight: 300;
            border-bottom: 1px solid #f1f2f3 !important;
            background-color: #fafafa;
        }
        p{
            font-size: 14px;
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
        .thumb_logo {
            max-width: 120px;
        }
        .thumb_logo img{
            width: 100%;
        }
        .line_grid{
            display: grid;
            grid-template-columns: 140px auto;
            grid-gap: 10px;
        }
        .line_grid span{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .line_grid span:first-child{
            font-weight: 600;
            color: #79838b;
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
            padding: 12px 10px;
        }
        .border_table tbody tr td {
            border-bottom: 1px solid rgba(0, 0, 0,.05);
            text-align: center;
            padding: 10px;
        }
        .logo_img{
            display: flex;
            align-items: center;
        }
        .logo_img h3{
            font-size: 25px;
            margin-bottom: 5px;
            color: #79838b;
        }
        .logo_img h5{
            font-size: 14px;
            margin-bottom: 0;
            color: #79838b;
        }
        .company_info{
            margin-left: 20px;
        }
        .table_title{
            text-align: center;
        }
        .table_title h3{
            font-size: 35px;
            font-weight: 600;
            text-transform: uppercase;
            padding-bottom: 3px;
            display: inline-block;
            margin-bottom: 40px;
            color: #79838b;
        }
        .gray_header_table thead th{
            background: #515151 !important;
            color: #fff;
            border: 1px solid #515151;
        }
        .gray_header_table{
            border: 1px solid var(--border_color);
        }
        .gray_header_table tbody td, .gray_header_table tbody th {
            border: 1px solid var(--border_color);
        }
        .gray_header_table tbody tr:nth-of-type(2n+1) td {
            background-color: #EEEEEE !important;
        }
        .max-width-400{
            width: 400px;
        }
        .max-width-500{
            width: 500px;
        }
        .ml_auto{
            margin-left: auto;
            margin-right: 0;
        }
        .mr_auto{
            margin-left: 0;
            margin-right: auto;
        }
        .margin-auto{
          margin: auto;
        }

        .thumb.text-right {
        text-align: right;
    }
    </style>
</head>
@php 
    $generalSetting= generalSetting(); 
    if(!empty($generalSetting)){
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
    } 
@endphp
<script>
    // var is_chrome = function () { return Boolean(window.chrome); }
    // if(is_chrome) 
    // {
    //    window.print();
    // // setTimeout(function(){window.close();}, 10000); 
    // //give them 10 seconds to print, then close
    // }
    // else
    // {
    //    window.print();
    // }
</script>
<body onLoad="loadHandler();">
    <div class="invoice_wrapper">
        <!-- invoice print part here -->
        <div class="invoice_print mb_30">
            <div class="container">
                <div class="invoice_part_iner">
                    <table class="table border_bottom mb_30" >
                        <thead>
                            <td>
                                <div class="logo_img">
                                    <div class="thumb_logo">
                                        <img  src="{{asset('/')}}{{generalSetting()->logo }}" alt="{{$school_name}}">
                                    </div>
                                    <div class="company_info">
                                        <h3>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                        <h5>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}</h5>
                                    </div>
                                </div>
                            </td>
                        </thead>
                    </table>
                    <div class="table_title">
                        <h3>@lang('lang.merit_list_report')</h3>
                    </div>
                    <!-- middle content  -->
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                   <!-- single table  -->
                                   <table class="mb_30 max-width-500 mr_auto">
                                       <tbody>
                                           <tr>
                                               <td>
                                                <p class="line_grid" >
                                                    <span>
                                                        <span>@lang('common.academic_year')</span>
                                                        <span>:</span>
                                                    </span>
                                                    {{generalSetting()->session_year}}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="line_grid" >
                                                    <span>
                                                        <span>@lang('common.class')</span>
                                                        <span>:</span>
                                                    </span>
                                                    {{$class_name}}
                                                </p>
                                            </td>
                                           </tr>
                                           <tr>
                                                <td>
                                                    <p class="line_grid" >
                                                        <span>
                                                            <span>@lang('exam.exam')</span>
                                                            <span>:</span>
                                                        </span>
                                                        {{$exam_name}}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="line_grid" >
                                                        <span>
                                                            <span>@lang('common.section')</span>
                                                            <span>:</span>
                                                        </span>
                                                        {{$section->section_name}}
                                                    </p>
                                                </td>
                                           </tr>
                                           <tr>
                                                
                                                <td>
                                                    <p class="line_grid" >
                                                        <span>
                                                            <span>@lang('exam.exam')</span>
                                                            <span>:</span>
                                                        </span>
                                                        @foreach($assign_subjects as $subject)
                                                             {{$subject->subject->subject_name}}, 
                                                        @endforeach 
                                                    </p>
                                                </td>
                                                
                                           </tr>
                                       </tbody>
                                   </table>
                                   <!--/ single table  -->
                                </td>
                                <td>
                                    <!-- single table  -->
                                    
                                    @if(@$grades)
                                    <table class="table border_table gray_header_table mb_30 max-width-400 ml_auto" >
                                        <thead>
                                            <tr>
                                              <th>@lang('lang.starting')</th>
                                              <th>@lang('reports.ending')</th>
                                              <th>@lang('exam.gpa')</th>
                                              <th>@lang('exam.grade')</th>
                                              <th>@lang('homework.evalution')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($grades as $grade_d)
                                          <tr>
                                             <td>{{$grade_d->percent_from}}</td>
                                             <td>{{$grade_d->percent_upto}}</td>
                                             <td>{{$grade_d->gpa}}</td>
                                             <td>{{$grade_d->grade_name}}</td>
                                             <td>{{$grade_d->description}}</td>
                                          </tr>
                                         @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- invoice print part end -->
        
        <table class="table border_table gray_header_table mb_30" >
            <thead>
              <tr>
                <th>@lang('lang.merit_position')</th>
                <th>@lang('student.admission_no')</th>
                <th>@lang('common.student')</th>
                @foreach($subjectlist as $subject)
                <th>{{$subject}}</th>
                @endforeach

                <th>@lang('exam.total_mark')</th>
                <th>@lang('lang.obtained_marks')</th>

                
                @if ($optional_subject_setup!='')
                <th>@lang('exam.gpa')
                    <hr>
                    <small>@lang('reports.without_additional')</small>  
                
                </th>
                {{-- <th>@lang('reports.result')</th> --}}
                <th>@lang('exam.gpa')</th>
                @else
                    <th>@lang('exam.gpa')</th>
                    <th>@lang('reports.result')</th>
                @endif
              </tr>
            </thead>
            <tbody>
                @foreach($allresult_data as $key => $row) 
                @php
                    $total_student_mark = 0;
                    $total = 0;
                    $markslist = explode(',',$row->marks_string);
                @endphp 
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$row->admission_no}}</td>
                    <td style="text-align:left !important;" nowrap >{{$row->student_name}}</td>
                    @if(!empty($markslist))
                        @foreach($markslist as $mark)
                            @php 
                                $subject_mark[]= $mark;
                                $total_student_mark = $total_student_mark + $mark; 
                                $total = $total + $subject_total_mark;
                            @endphp 
                            <td> {{!empty($mark)?$mark:0}}</td> 
                        @endforeach
                    @endif
                    <td>{{$total}}</td>
                    <td> {{$row->total_marks}}</td>
                    <td> {{$row->gpa_point}}</td>
                    <td>{{$row->result}}</td>
                 </tr> 
                @endforeach
            </tbody>
      </table>
    </div>
</body>
</html>
    
