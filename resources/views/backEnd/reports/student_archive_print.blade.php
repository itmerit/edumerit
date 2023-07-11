<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('reports.official_transcript')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
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

        .line_grid{
            display: flex;
            grid-gap: 10px;
            font-weight: 500;
            font-weight: 600;
            color: var(--base_color);
            font-size: 14px
        }
        .line_grid span{
            font-weight: 500;
        }
        .line_grid span{
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex: 150px 0 0;
        }
        .line_grid span:first-child{
            font-weight: 500;
            color: #828bb2;
            font-size: 14px;
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

        .logo_img{
            display: flex;
            align-items: center;
            background: url({{asset('public/backEnd/img/report-admit-bg.png')}}) no-repeat center;
            background-size: auto;
            background-size: cover;
            border-radius: 5px 5px 0px 0px;
            border: 0;
            padding: 20px;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .logo_img h3{
            font-size: 25px;
            margin-bottom: 5px;
            color: #fff;
        }
        .logo_img h5{
            font-size: 14px;
            margin-bottom: 0;
            color: #fff;
        }
        .company_info {
            margin-left: 100px;
            flex: 1 1 0;
        }



        .gray_header_table thead th{
            text-transform: uppercase;
            font-size: 12px;
            color: var(--base_color);
            font-weight: 500;
            text-align: left;
            border-bottom: 1px solid #a2a8c5;
            padding: 5px 0px;
            background: transparent !important ;
            border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            padding-left: 0px !important;
        }
        .gray_header_table {
            border: 0;
        }
        .gray_header_table tbody td, .gray_header_table tbody th {
            border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
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
        .tableInfo_header{
            background: url({{asset('public/backEnd/')}}/img/report-admit-bg.png) no-repeat center;
            background-size: cover;
            border-radius: 5px 5px 0px 0px;
            border: 0;
            padding: 30px 30px;
        }
        .tableInfo_header td{
            padding: 30px 40px;
        }
        .company_info{
            margin-left: 100px;
        }
        .company_info p{
            font-size: 14px;
            color: #fff;
            font-weight: 400;
            margin-bottom: 10px;
        }
        .company_info h3{
            font-size: 18px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 15px;
        }
        .meritTableBody{
            padding: 30px;
            background: -webkit-linear-gradient(
            90deg
            , #d8e6ff 0%, #ecd0f4 100%);
                background: -moz-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
                background: -o-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
                background: linear-gradient(
            90deg
            , #d8e6ff 0%, #ecd0f4 100%);
        }
        .subject_title{
            font-size: 18px;
            font-weight: 600;
            font-weight: 500;
            color: var(--base_color);
            line-height: 1.5;
        }
        .subjectList{
            display: grid;
            grid-template-columns: repeat(2,1fr);
            grid-column-gap: 40px;
            grid-row-gap: 9px;
            margin: 0;
            padding: 0;

        }
        .subjectList li{
            list-style: none;
            color: #828bb2;
            font-size: 14px;
            font-weight: 400
        }
        .table_title{
            font-weight: 500;
            color: var(--base_color);
            line-height: 1.5;   
            font-size: 18px;
            text-align: left
        }
        .gradeTable_minimal.border_table tbody tr td {
            text-align: left !important;
            border: 0;
            color: #828bb2;
            padding: 8px 8px;
            font-weight: 400;
            font-size: 12px;
            padding: 3px 8px;
        }

        .profile_thumb img {
            border-radius: 5px;
        }
        .gray_header_table thead tr:first-child th {
            border: 0 !important;
        }
        .gray_header_table thead tr:last-child th {
            border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
        }
        .single-report-admit table tr td {
            vertical-align: middle;
            font-size: 12px;
            color: #828BB2;
            font-weight: 400;
            border: 0;
            border-bottom: 1px solid rgba(130, 139, 178, 0.15) !important;
            text-align: left;
        }
        .border_table thead tr:first-of-type th:first-child,
        .border_table thead tr:first-of-type th:last-child{
            border-bottom: 1px solid rgba(130, 139, 178, 0.15) !important;
        }
    </style>
</head>
<script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome) 
        {
           window.print();
        //    setTimeout(function(){window.close();}, 10000); 
        }
        else
        {
           window.print();
        //    window.close();
        }
</script>
<body onLoad="loadHandler();">
    <div class="invoice_wrapper">
        <div class="invoice_print mb-0">
            <div class="container">
                <div class="invoice_part_iner">
                    <table class="table border_bottom mb-0" >
                        <thead>
                            <td style="padding: 0">
                                <div class="logo_img">
                                    <div class="thumb_logo">
                                        <img  src="{{asset('/')}}{{generalSetting()->logo }}" alt="{{generalSetting()->school_name}}">
                                    </div>
                                    <div class="company_info">
                                        <h3>{{isset(generalSetting()->school_name)? generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                        <h5>{{isset(generalSetting()->address)? generalSetting()->address:'Infix School Address'}}</h5>
                                        <h5>
                                            @lang('common.email'): {{isset(generalSetting()->email)?generalSetting()->email:'admin@infixedu.com'}} 
                                            @lang('common.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}}
                                        </h5>
                                    </div>
                                    <div class="profile_thumb">
                                        <img src="{{ file_exists(@$studentDetails->student_photo) ? asset($studentDetails->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="{{$studentDetails->full_name}}" height="100" width="100">
                                    </div>
                                </div>
                            </td>
                        </thead>
                    </table>
                </div>
            </div>
        </div>






        <div class="meritTableBody">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                           <!-- single table  -->
                           <table class="mb_30 max-width-500 mr_auto">
                               <tbody>
                                   <tr>
                                       <td colspan="2">
                                            <p class="line_grid_update" style="text-align:center">
                                                @lang('reports.official_transcript')
                                            </p>
                                        </td>
                                   </tr>
                                   <tr>
                                       <td>
                                            <p class="line_grid_update">
                                                {{$studentDetails->full_name}}
                                            </p>
                                        </td>
                                   </tr>
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
                                                {{@$studentDetails->class_name}}
                                            </p>
                                        </td>
                                   </tr>
                                   <tr>
                                        
                                        <td>
                                            <p class="line_grid" >
                                                <span>
                                                    <span>@lang('student.roll_no')</span>
                                                    <span>:</span>
                                                </span>
                                                {{$studentDetails->roll_no}}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="line_grid" >
                                                <span>
                                                    <span>@lang('common.section')</span>
                                                    <span>:</span>
                                                </span>
                                                {{ $studentDetails->section_name }}
                                            </p>
                                        </td>
                                   </tr>
                                   <tr>
                                        
                                        <td>
                                            <p class="line_grid" >
                                                <span>
                                                    <span>@lang('student.admission_no')</span>
                                                    <span>:</span>
                                                </span>
                                                {{$studentDetails->admission_no}}
                                            </p>
                                        </td>
                                        <td>
                                            
                                        </td>
                                   </tr>
                               </tbody>
                           </table>
                           <!--/ single table  -->
                        </td>
                        <td>
                            <!-- single table  -->
                            @if(@$marks_grade)
                            <table class="table border_table gray_header_table mb_30 max-width-400 ml_auto gradeTable_minimal" >
                                <thead>
                                    <tr>
                                        <th>@lang('exam.starting')</th>
                                        <th>@lang('reports.ending')</th>
                                        <th>@lang('exam.gpa')</th>
                                        <th>@lang('exam.grade')</th>
                                        <th>@lang('homework.evalution')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($marks_grade as $d)
                                        <tr>
                                            <td>{{$d->percent_from}}</td>
                                            <td>{{$d->percent_upto}}</td>
                                            <td>{{$d->gpa}}</td>
                                            <td>{{$d->grade_name}}</td>
                                            <td>{{$d->description}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            <!--/ single table  -->
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="single-report-admit">
                <table class="table border_table gray_header_table mb-0" >
                    <thead>
                        <tr>
                            <th rowspan="2">@lang('common.subjects')</th>
                            @foreach($assinged_exam_types as $assinged_exam_type)
                            @php
                                $exam_type = App\SmExamType::examType($assinged_exam_type);
                            @endphp
                            <th colspan="4">{{$exam_type->title}}</th>
                            @endforeach
                            <th rowspan="2">@lang('exam.result')</th>
                        </tr>
                        <tr>
                            @foreach($assinged_exam_types as $assinged_exam_type)
                                <th>@lang('reports.full_mark')</th>
                                <th>@lang('exam.marks')</th>
                                <th>@lang('exam.grade')</th>
                                <th>@lang('exam.gpa')</th>
                            @endforeach
                        </tr>
                    </thead>
                    @php
                        $total_fail = 0;
                        $total_marks = 0;
                        $gpa_with_optional_count=0;
                        $gpa_without_optional_count=0;
                        $value=0;
                        $all_exam_type_full_mark=0;
                    @endphp
                    <tbody>
                        @foreach($subjects as $data)
                        <tr>
                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                    @if ($student_optional_subject->subject_id==$data->subject->id)
                                    <td>
                                        {{$data->subject !=""?$data->subject->subject_name:""}} (@lang('common.optional'))
                                    </td>
                                @else
                                    <td>
                                        {{$data->subject !=""?$data->subject->subject_name:""}} 
                                    </td>
                                @endif
                            @else
                            <td>
                                {{$data->subject !=""?$data->subject->subject_name:""}} 
                            </td>
                            @endif
                            <?php
                                $totalSumSub= 0;
                                $totalSubjectFail= 0;
                                $TotalSum= 0;
                            foreach($assinged_exam_types as $assinged_exam_type){
                                $mark_parts = App\SmAssignSubject::getNumberOfPart($data->subject_id, $class_id, $section_id, $assinged_exam_type);
                                $result = App\SmResultStore::GetResultBySubjectId($class_id, $section_id, $data->subject_id,$assinged_exam_type ,$student_id);
                                if(!empty($result)){
                                    $final_results = App\SmResultStore::GetFinalResultBySubjectId($class_id, $section_id, $data->subject_id,$assinged_exam_type ,$student_id);
                                }
                                $subject_full_mark=subjectFullMark($assinged_exam_type, $data->subject_id);
                                if($result->count()>0){
                                    ?>
                                    <td>
                                        @php
                                            $all_exam_type_full_mark+=$subject_full_mark;
                                        @endphp
                                        {{$subject_full_mark}}
                                    </td>
                                        <td>
                                        @php
                                            if($final_results != ""){
                                                echo $final_results->total_marks;
                                                $totalSumSub = $totalSumSub + $final_results->total_marks;
                                                $total_marks = $total_marks + $final_results->total_marks;
    
                                            }else{
                                                echo 0;
                                            }
    
                                        @endphp
                                    </td>
                                        <td>
                                            @php
                                                if($final_results != ""){
                                                    if($final_results->total_gpa_grade == $fail_grade_name->grade_name){
                                                        $totalSubjectFail++;
                                                        $total_fail++;
                                                    }
                                                    echo $final_results->total_gpa_grade;
                                                }else{
                                                    echo '-';
                                                }
                                                if ($student_optional_subject!='') {
                                                        if ($student_optional_subject->subject_id==$data->subject->id) {
                                                            $optional_subject_mark=$final_results->total_marks;
                                                        }
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            {{number_format($final_results->total_gpa_point,2,'.','')}}
                                        </td>
                                    <?php
                                        }else{ ?>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        <?php
                                        }
                                            }
                                    ?>
                                    <td>
                                        {{$totalSumSub}}
                                    </td>
    
                        </tr>
                        @endforeach
                        @php
                            $colspan = 4 + count($assinged_exam_types) * 2;
                            if ($optional_subject_setup!='') {
                                $col_for_result=3;
                            } else {
                                $col_for_result=2;
                            }
                        @endphp
                        <tr>
                            <td><strong>@lang('reports.result')</strong></td>
                            @php
                                $term_base_gpa  = 0;
                                $average_gpa  = 0;
                                $with_percent_average_gpa  = 0;
                                $optional_subject_total_gpa  = 0;
                                $optional_subject_total_above_gpa  = 0;
                                $without_additional_subject_total_gpa  = 0;
                                $with_additional_subject_addition  = 0;
                                $with_optional_percentage  = 0;
                                $total_with_optional_percentage  = 0;
                                $total_with_optional_subject_extra_gpa  = 0;
                                $optional_subject_mark= 0;
                            @endphp
                            @foreach($assinged_exam_types as $assinged_exam_type)
                            @php
                                $exam_type = App\SmExamType::examType($assinged_exam_type);
                                $term_base_gpa=termWiseGpa($assinged_exam_type, $student_id);
                                $with_percent_average_gpa +=$term_base_gpa;
    
                                $term_base_full_mark=termWiseTotalMark($assinged_exam_type, $student_id);
                                $average_gpa+=$term_base_full_mark;
    
                                if($optional_subject_setup!='' && $student_optional_subject!=''){
    
                                    $optional_subject_gpa = optionalSubjectFullMark($assinged_exam_type,$student_id,@$optional_subject_setup->gpa_above,"optional_sub_gpa");
                                    $optional_subject_total_gpa += $optional_subject_gpa;
    
                                    $optional_subject_above_gpa = optionalSubjectFullMark($assinged_exam_type,$student_id,@$optional_subject_setup->gpa_above,"with_optional_sub_gpa");
                                    $optional_subject_total_above_gpa += $optional_subject_above_gpa;
    
                                    $without_subject_gpa = optionalSubjectFullMark($assinged_exam_type,$student_id,@$optional_subject_setup->gpa_above,"without_optional_sub_gpa");
                                    $without_additional_subject_total_gpa += $without_subject_gpa;
                                    
                                    $with_additional_subject_gpa = termWiseAddOptionalMark($assinged_exam_type,$student_id,@$optional_subject_setup->gpa_above);
                                    $with_additional_subject_addition += $with_additional_subject_gpa;
    
                                    $with_optional_subject_extra_gpa = termWiseTotalMark($assinged_exam_type,$student_id,"optional_subject");
                                    $total_with_optional_subject_extra_gpa += $with_optional_subject_extra_gpa;
    
                                    $with_optional_percentages=termWiseGpa($assinged_exam_type, $student_id,$with_optional_subject_extra_gpa);
                                    $total_with_optional_percentage += $with_optional_percentages;
                                }
                            @endphp
                            <td colspan="4"> 
                                <strong>
                                    @lang('reports.average_gpa') : 
                                    {{number_format($term_base_full_mark,2,'.','')}}
                                    </br>
                                    {{$exam_type->title}} ({{$exam_type->percentage}}%) : {{number_format($term_base_gpa,2,'.','')}}
                                    @if($optional_subject_setup!='' && $student_optional_subject!='')
                                        <hr>
                                        @lang('reports.with_optional') : 
                                        {{number_format($with_optional_subject_extra_gpa,2,'.','')}}
                                        </br>
                                        @lang('reports.with_optional') ({{$exam_type->percentage}}%) : 
                                        {{number_format($with_optional_percentages,2,'.','')}}
                                    @endif
                                </strong>
                            </td>
                            @endforeach
                            <td>
                                <strong>
                                    {{number_format($average_gpa,2,'.','')}}
                                    </br>
                                    {{number_format($with_percent_average_gpa, 2, '.', '')}}
                                    @if($optional_subject_setup!='' && $student_optional_subject!='')
                                        <hr>
                                        {{number_format($total_with_optional_subject_extra_gpa, 2, '.', '')}}
                                        </br>
                                        {{number_format($total_with_optional_percentage, 2, '.', '')}}
                                    @endif
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="{{$colspan / $col_for_result - 1}}">
                                @lang('exam.total_marks')
                            </td>
                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                <td colspan="{{$colspan / $col_for_result + 7}}">
                                    {{$total_marks}} @lang('reports.out_of') {{$all_exam_type_full_mark}}
                                </td>
                            @else
                                <td colspan="{{$colspan / $col_for_result + 9}}">
                                    {{$total_marks}} @lang('reports.out_of') {{$all_exam_type_full_mark}}
                                </td>
                            @endif
                        </tr>
                        <tr>
                            @if($optional_subject_setup!='' && $student_optional_subject!='')
                                <td colspan="{{$colspan / $col_for_result - 1}}">
                                    @lang('exam.optional_total_gpa')
                                        <hr>
                                    @lang('reports.gpa_above') {{@$optional_subject_setup->gpa_above}}
                                </td>
                                <td colspan="{{$colspan / $col_for_result + 7}}">
                                    {{$optional_subject_total_gpa}}
                                        <hr>
                                    {{$optional_subject_total_above_gpa}}
                                </td>
                            @endif
                        </tr>
                        @php
                        if (isset($optional_subject_mark)) {
                            $total_marks_without_optional=$total_marks-$optional_subject_mark;
                            $op_subject_count=count($subjects)-1;
                        }else{
                            $total_marks_without_optional=$total_marks;
                            $op_subject_count=count($subjects);
                        }
                        @endphp
                        <tr>
                            <td colspan="{{$colspan / $col_for_result - 1}}">
                                @lang('reports.total_gpa')
                            </td>
                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                            <td colspan="4">
                                {{number_format($total_with_optional_percentage,2,'.','')}}
                            </td>
                            <td colspan="3">
                                @lang('reports.without_additional_grade')
                            </td>
                            <td colspan="2">
                                {{number_format($with_percent_average_gpa,2,'.','')}}
                            </td>
                            @else
                                <td colspan="{{$colspan / $col_for_result + 9}}">
                                    {{gradeName(number_format(termWiseFullMark($assinged_exam_types,$student_id),2,'.',''))}}
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td colspan="{{$colspan / $col_for_result - 1}}">
                                @lang('exam.total_grade')
                            </td>
                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                <td colspan="4">
                                    {{gradeName(number_format($total_with_optional_percentage,2,'.',''))}}
                                </td>
                            <td colspan="3">
                                @lang('reports.without_additional_gpa')
                            </td>
                            <td colspan="2">
                                {{gradeName(number_format($with_percent_average_gpa,2,'.',''))}}
                            </td>
                            @else
                                <td colspan="{{$colspan / $col_for_result + 9}}">
                                    {{number_format(termWiseFullMark($assinged_exam_types,$student_id),2,'.','')}}
                                </td>
                            @endif
                        </tr>
                        {{-- Remark Start --}}
                        <tr>
                            @if($optional_subject_setup!='' && $student_optional_subject!='')
                                <td colspan="{{$colspan / $col_for_result - 1}}">
                                    @lang('reports.remarks')
                                </td>
                                <td colspan="{{$colspan / $col_for_result + 7}}">
                                    {{remarks(number_format($total_with_optional_percentage,2,'.',''))}}
                                </td>
                            @else
                                <td colspan="{{$colspan / $col_for_result - 1}}">
                                    @lang('reports.remarks')
                                </td>
                                <td colspan="{{$colspan / $col_for_result + 9}}">
                                    {{remarks(number_format(termWiseFullMark($assinged_exam_types,$student_id),2,'.',''))}}
                                </td>
                            @endif
                        </tr>
                        {{-- Remark End --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>