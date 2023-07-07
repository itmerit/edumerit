<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('reports.progress_card_report_100_percent')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
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

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        @media print {
            .invoice {
                height: 1350px;
            }
        }

        .invoice_wrapper {
            width: 1100px;
            height: 100%;
            margin: auto;
            background: #fff;
            padding: 20px;
        }

        .meritTableBody, .meritTableBodyCustomReport {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .border_none {
            border: 0px solid transparent;
            border-top: 0px solid transparent !important;
        }

        .invoice_part_iner {
            background-color: #fff;
        }

        .invoice_part_iner h4 {
            font-size: 30px;
            font-weight: 500;
            margin-bottom: 40px;

        }

        .invoice_part_iner h3 {
            font-size: 25px;
            font-weight: 500;
            margin-bottom: 5px;

        }

        .table_border thead {
            background-color: #F6F8FA;
        }

        .table td, .table th {
            padding: 5px 0;
            vertical-align: top;
            border-top: 0 solid transparent;
            color: #000;
        }

        .table td, .table th {
            padding: 5px 0;
            vertical-align: top;
            border-top: 0 solid transparent;
            color: #000;
        }

        .table_border tr {
            border-bottom: 1px solid #000 !important;
        }

        th p span, td p span {
            color: #212E40;
        }

        .table th {
            font-weight: bold;
            border-bottom: 1px solid #f1f2f3 !important;
        }

        p {
            font-size: 14px;
        }

        h5 {
            font-size: 12px;
            font-weight: 500;
        }

        h6 {
            font-size: 10px;
            font-weight: 300;
        }

        .mt_40 {
            margin-top: 40px;
        }

        .table_style th, .table_style td {
            padding: 20px;
        }

        .invoice_info_table td {
            font-size: 10px;
            padding: 0px;
        }

        .invoice_info_table td h6 {
            color: #6D6D6D;
            font-weight: 400;
        }

        .text_right {
            text-align: right;
        }

        .virtical_middle {
            vertical-align: middle !important;
        }

        .thumb_logo {
            max-width: 120px;
        }

        .thumb_logo img {
            width: 100%;
        }

        .line_grid {
            display: grid;
            grid-template-columns: 140px auto;
            grid-gap: 10px;
        }

        .line_grid span {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .line_grid span:first-child {
            font-weight: 600;
            color: #000;
        }

        p {
            margin: 0;
        }

        .font_18 {
            font-size: 18px;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb_30 {
            margin-bottom: 30px !important;
        }

        .border_table thead tr th {
            padding: 5px;
        }

        .border_table tbody tr td {
            border-bottom: 1px solid rgba(0, 0, 0, .05);
            text-align: left;
            padding: 5px;
        }

        .logo_img {
            display: flex;
            align-items: center;
        }

        .logo_img h3 {
            font-size: 25px;
            margin-bottom: 5px;
            color: #79838b;
        }

        .logo_img h5 {
            font-size: 16px;
            margin-bottom: 0;
            color: #fff;
        }

        .company_info {
            width: 100%;
            text-align: center;
        }

        .table_title {
            text-align: center;
        }

        .table_title h3 {
            font-size: 35px;
            font-weight: 600;
            text-transform: uppercase;
            padding-bottom: 3px;
            display: inline-block;
            margin-bottom: 40px;
            color: #79838b;
        }

        .line_grid {
            display: flex;
            grid-gap: 10px;
            font-weight: 500;
            font-weight: 600;
            color: #000;
            font-size: 14px
        }

        .line_grid span {
            font-weight: 500;
        }

        .line_grid span {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex: 150px 0 0;
        }

        .line_grid span:first-child {
            font-weight: 500;
            color: #000;
            font-size: 14px;
        }


        .max-width-400 {
            width: 400px;
        }

        .max-width-500 {
            width: 500px;
        }

        .ml_auto {
            margin-left: auto;
            margin-right: 0;
        }

        .mr_auto {
            margin-left: 0;
            margin-right: auto;
        }

        .logo_img {
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

        .logo_img h3 {
            font-size: 25px;
            margin-bottom: 5px;
            color: #fff;
        }


        .gray_header_table thead th {
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 500;
            text-align: left;
            border-bottom: 1px solid #a2a8c5;
            padding: 5px 0px;
            background: transparent !important;
            border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            padding-left: 0px !important;
        }

        .gray_header_table {
            border: 0;
        }

        .gray_header_table tbody td, .gray_header_table tbody th {
            border-bottom: 1px solid #000 !important;
            text-align: left;
        }

        .max-width-400 {
            width: 400px;
        }

        .max-width-500 {
            width: 500px;
        }

        .ml_auto {
            margin-left: auto;
            margin-right: 0;
        }

        .mr_auto {
            margin-left: 0;
            margin-right: auto;
        }

        .margin-auto {
            margin: 0 auto;
        }

        .thumb.text-right {
            text-align: right;
        }

        .tableInfo_header {
            background: url({{asset('public/backEnd/')}}/img/report-admit-bg.png) no-repeat center;
            background-size: cover;
            border-radius: 5px 5px 0px 0px;
            border: 0;
            padding: 30px 30px;
        }

        .tableInfo_header td {
            padding: 30px 40px;
        }

        .company_info p {
            font-size: 14px;
            color: #fff;
            font-weight: 400;
            margin-bottom: 10px;
        }

        .company_info h3 {
            font-size: 30px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 0px
        }

        .meritTableBody {
            padding: 30px;
            background: -webkit-linear-gradient(
                    90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: -moz-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: -o-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: linear-gradient(
                    90deg, #d8e6ff 0%, #ecd0f4 100%);
        }

        .subject_title {
            font-size: 18px;
            font-weight: 600;
            font-weight: 500;
            color: var(--base_color);
            line-height: 1.5;
        }

        .subjectList {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: 40px;
            grid-row-gap: 9px;
            margin: 0;
            padding: 0;

        }

        .subjectList li {
            list-style: none;
            color: #828bb2;
            font-size: 14px;
            font-weight: 400
        }

        .table_title {
            font-weight: 500;
            color: var(--base_color);
            line-height: 1.5;
            font-size: 18px;
            text-align: left
        }

        .gradeTable_minimal.border_table tbody tr td {
            text-align: left !important;
            border: 0;
            color: #000;
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
            border-bottom: 1px solid #000 !important;
        }

        .single-report-admit table tr td {
            vertical-align: middle;
            font-size: 12px;
            color: #000;
            font-weight: 400;
            border: 0;
            border-bottom: 1px solid #000 !important;
            text-align: left;
        }

        .border_table thead tr:first-of-type th:first-child,
        .border_table thead tr:first-of-type th:last-child {

        }

        .student_name_highlight {
            font-weight: 500;
            color: #000;
            line-height: 1.5;
            font-size: 20px;
            text-transform: uppercase;

        }

        .custom_result_print {
            background-image: none;
        }

        .custom_result_print h3, .custom_result_print h5 {
            color: black;
        }

        .meritTableBodyCustomReport {
            padding: 30px;
        }

        .mt_40 {
            margin-top: 40px !important;
        }

        .border_table thead tr th {
            font-weight: bold;
        }
    </style>
    @if(resultPrintStatus('vertical_boarder'))
        <style>
            .gray_header_table tbody td, .gray_header_table tbody th {
                border: 1px solid #000 !important;
            }

            .single-report-admit table tr td {
                border: 1px solid #000 !important;
            }

            .border_table tbody tr td {
                border: 1px solid #000 !important;
            }

            .border_table thead tr th {
                border: 1px solid #000;
                font-weight: bold;
            }

            .gray_header_table thead tr:first-child th {
                border: 1px solid #000 !important;
            }

            .gray_header_table thead th {
                padding-left: 10px !important;
            }

            .align-center thead th {
                text-align: center !important;
            }

            .align-center tbody td {
                text-align: center !important;
            }

            .align-center tbody th {
                text-align: center !important;
            }

            .align-center thead tr:first-child th:first-child {
                text-align: left !important;
            }

            .align-center tbody tr:last-child td {
                text-align: left !important;
            }

            .align-center tbody tr th:first-child {
                text-align: left !important;
                padding-left: 10px;
            }

            .align-center tbody tr:last-child td:first-child {
                padding-left: 10px;
            }

            .border_table thead tr th {
                vertical-align: middle !important;
            }
        </style>
    @endif
</head>
{{-- <script>
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
</script> --}}
<body>
<div class="invoice">
    <div class="invoice_wrapper">
        <div class="invoice_print mb-0">
            <div class="container">
                <div class="invoice_part_iner">
                    <table class="table border_bottom mb-0">
                        <thead>
                        <td style="padding: 0">
                            <div class="{{(resultPrintStatus('header'))? "logo_img": "logo_img custom_result_print"}}">
                                <div class="thumb_logo">
                                    <img src="{{asset('/')}}{{generalSetting()->logo }}"
                                         alt="{{generalSetting()->school_name}}">
                                </div>
                                <div class="company_info">
                                    <h3>{{isset(generalSetting()->school_name)? generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                    <h5>{{isset(generalSetting()->address)? generalSetting()->address:'Infix School Address'}}</h5>
                                    <h5>
                                        @lang('common.email')
                                        : {{isset(generalSetting()->email)?generalSetting()->email:'admin@infixedu.com'}}
                                        @lang('common.phone')
                                        : {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}}
                                    </h5>
                                </div>
                                @if(resultPrintStatus('image'))
                                    <div class="profile_thumb">
                                        <img src="{{ file_exists(@$student_detail->studentDetail->student_photo) ? asset($student_detail->studentDetail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                             alt="{{$student_detail->studentDetail->full_name}}" height="100"
                                             width="100">
                                    </div>
                                @endif
                            </div>
                        </td>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="{{(resultPrintStatus('body'))? "meritTableBody": "meritTableBodyCustomReport"}}">
            <table class="table">
                <tbody>
                <tr>
                    <td>
                        <!-- single table  -->
                        <table class="mb_30 mr_auto" style="width: 100%;">
                            <tbody>

                            <tr>
                                <td>
                                    <div class="student_name_highlight">
                                        <h4> {{$student_detail->studentDetail->full_name}}</h4>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="line_grid">
                                                <span>
                                                    <span>@lang('common.academic_year')</span>
                                                    <span>:</span>
                                                </span>
                                        {{ @$student_detail->academic->year }}
                                    </p>
                                </td>

                            </tr>


                            <tr>
                                <td>
                                    <p class="line_grid">
                                                <span>
                                                    <span>@lang('common.class')</span>
                                                    <span>:</span>
                                                </span>
                                        {{@$student_detail->class->class_name}}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="line_grid">
                                                <span>
                                                    <span>@lang('common.section')</span>
                                                    <span>:</span>
                                                </span>
                                        {{ $student_detail->section->section_name }}
                                    </p>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <p class="line_grid">
                                                <span>
                                                    <span>@lang('student.admission_no')</span>
                                                    <span>:</span>
                                                </span>
                                        {{$student_detail->studentDetail->admission_no}}
                                    </p>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <p class="line_grid">
                                                <span>
                                                    <span>@lang('student.roll_no')</span>
                                                    <span>:</span>
                                                </span>
                                        {{$student_detail->roll_no}}
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <p class="line_grid line_grid2">
                                                <span>
                                                    <span>@lang('common.date_of_birth')</span>
                                                    <span>:</span>
                                                </span>
                                        {{$student_detail->studentDetail->date_of_birth != ""? dateConvert($student_detail->studentDetail->date_of_birth):''}}
                                    </p>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <!--/ single table  -->
                    </td>
                    <td>
                        <!-- single table  -->
                        @if(@$marks_grade)
                            <table class="table border_table gray_header_table mb_30 max-width-400 ml_auto gradeTable_minimal">
                                <thead>
                                <tr>
                                    <th>@lang('exam.starting')</th>
                                    <th>@lang('reports.ending')</th>
                                    @if (@generalSetting()->result_type != 'mark')
                                        <th>@lang('exam.gpa')</th>
                                        <th>@lang('exam.grade')</th>
                                    @endif
                                    <th>@lang('homework.evalution')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($marks_grade as $d)
                                    <tr>
                                        <td>{{$d->percent_from}}</td>
                                        <td>{{$d->percent_upto}}</td>
                                        @if (@generalSetting()->result_type != 'mark')
                                            <td>{{$d->gpa}}</td>
                                            <td>{{$d->grade_name}}</td>
                                        @endif
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
            <div class="student_name_highlight" style="text-align: center; margin-bottom: 20px;">
                <h4><span style="border-bottom: 3px double; font-size: 20px; ">@lang('reports.progress_report')</span>
                </h4>
            </div>

            <table class="table border_table align-center gray_header_table mb-0">
                <thead>
                <tr>
                    <th rowspan="{{ @generalSetting()->result_type == 'mark' ? '1' : '2' }}"
                        style="border-bottom: 1px solid #000 !important; ">@lang('common.subjects')</th>
                    @foreach($assinged_exam_types as $assinged_exam_type)
                        @php
                            $exam_type = App\SmExamType::examType($assinged_exam_type);
                        @endphp
                        <th colspan="{{(@generalSetting()->result_type == 'mark')? 2: 1}}">{{$exam_type->title}}</th>
                    @endforeach
                    @if(resultPrintStatus('vertical_boarder'))
                        <th rowspan="{{ @generalSetting()->result_type == 'mark' ? '1' : '2' }}">@lang('exam.total')</th>
                        <th rowspan="{{ @generalSetting()->result_type == 'mark' ? '1' : '2' }}">@lang('exam.average')</th>
                        <th rowspan="{{ @generalSetting()->result_type == 'mark' ? '1' : '2' }}">@lang('exam.letter_grade')</th>
                    @endif
                </tr>
                <tr>
                    @if(@generalSetting()->result_type == 'mark')
                        <th rowspan="2"></th>
                    @endif
                    @foreach($assinged_exam_types as $assinged_exam_type)
                        <th>@lang('exam.marks')</th>
                    @endforeach
                    @if(!resultPrintStatus('vertical_boarder'))
                        <th>@lang('exam.total')</th>
                        <th>@lang('exam.average')</th>
                        <th>@lang('exam.letter_grade')</th>
                    @endif
                </tr>
                </thead>
                @php
                    $total_fail = 0;
                    $total_marks = 0;
                    $gpa_with_optional_count = 0;
                    $gpa_without_optional_count = 0;
                    $value = 0;
                    $all_exam_type_full_mark = 0;
                    $student_id = $student_detail->id;
                    $totalavgMarkAddition = 0;
                @endphp
                <tbody>
                @foreach($subjects as $data)
                    <tr>
                        @if ($optional_subject_setup!='' && $student_optional_subject!='')
                            @if ($student_optional_subject->subject_id==$data->subject->id)
                                <th>
                                    {{$data->subject !=""?$data->subject->subject_name:""}} (@lang('common.optional'))
                                </th>
                            @else
                                <th>
                                    {{$data->subject !=""?$data->subject->subject_name:""}}
                                </th>
                            @endif
                        @else
                            <th>
                                {{$data->subject !=""?$data->subject->subject_name:""}}
                            </th>
                        @endif
                            <?php
                            $totalSumSub = 0;
                            $totalSubjectFail = 0;
                            $TotalSum = 0;
                        foreach ($assinged_exam_types as $assinged_exam_type){
                            $mark_parts = App\SmAssignSubject::getNumberOfPart($data->subject_id, $class_id, $section_id, $assinged_exam_type);
                            $result = App\SmResultStore::GetResultBySubjectId($class_id, $section_id, $data->subject_id, $assinged_exam_type, $student_id);
                            if (!empty($result)) {
                                $final_results = App\SmResultStore::GetFinalResultBySubjectId($class_id, $section_id, $data->subject_id, $assinged_exam_type, $student_id);
                            }
                            $subject_full_mark = subjectFullMark($assinged_exam_type, $data->subject_id, $class_id, $section_id);
                        if ($result->count() > 0){
                            ?>
                        @php
                            if(@generalSetting()->result_type == 'mark'){
                                $all_exam_type_full_mark += subject100PercentMark();
                            }else{
                                $all_exam_type_full_mark += 100;
                            }
                        @endphp
                        <td>
                            @php
                                if($final_results != ""){
                                    if(@generalSetting()->result_type == 'mark'){
                                        $totalMark = subjectPercentageMark(@$final_results->total_marks, $subject_full_mark);
                                        echo $totalMark;
                                        $totalSumSub += $totalMark;
                                        $total_marks += $totalMark;
                                    }else{
                                        echo subjectPercentageMark(@$final_results->total_marks, $subject_full_mark);
                                        $totalSumSub += subjectPercentageMark(@$final_results->total_marks, $subject_full_mark);
                                        $total_marks += subjectPercentageMark(@$final_results->total_marks, $subject_full_mark);
                                    }
                                }else{
                                    echo 0;
                                }
                            @endphp
                        </td>
                            <?php
                        }else{ ?>
                        <td>-</td>
                            <?php
                        }
                        }
                            ?>
                        <td>
                            {{$totalSumSub}}
                        </td>
                        <td>
                            {{ number_format($totalSumSub / count($assinged_exam_types), 2)}}
                            @php
                                $totalavgMarkAddition += number_format($totalSumSub / count($assinged_exam_types), 2);
                            @endphp
                        </td>
                        <td>{{getGrade(number_format($totalSumSub / count($assinged_exam_types), 2))}}</td>

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
                    @endforeach
                </tr>

                </tbody>
            </table>
            <table class="table border_table gray_header_table max-width-400 ml_auto margin-auto report_table"
                   @if(resultPrintStatus('vertical_boarder')) style="margin-top: 20px; margin-bottom: 20px;" @endif>
                <tbody>
                <tr>
                    <td colspan="{{$colspan / $col_for_result - 1}}" class="text_center">
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
                @if (@generalSetting()->result_type != 'mark')
                    <tr>
                        @if($optional_subject_setup!='' && $student_optional_subject!='')
                            <td colspan="{{$colspan / $col_for_result - 1}}" class="text_center">
                                @lang('exam.optional_total_gpa')
                                <hr>
                                @lang('reports.gpa_above') {{@$optional_subject_setup->gpa_above}}
                            </td>
                            <td colspan="{{$colspan / $col_for_result + 7}}" class="text_center">
                                {{$optional_subject_total_gpa}}
                                <hr>
                                {{$optional_subject_total_above_gpa}}
                            </td>
                        @endif
                    </tr>
                    @php
                        if ($optional_subject_mark) {
                            $total_marks_without_optional=$total_marks-$optional_subject_mark;
                            $op_subject_count=count($subjects)-1;
                        }else{
                            $total_marks_without_optional=$total_marks;
                            $op_subject_count=count($subjects);
                        }
                    @endphp
                    <tr>
                        <td colspan="{{$colspan / $col_for_result - 1}}" class="text_center">
                            @lang('reports.gpa')
                        </td>
                        @if ($optional_subject_setup!='' && $student_optional_subject!='')
                            <td colspan="4">
                                {{gradeName(number_format($total_with_optional_percentage,2,'.',''))}}
                            </td>
                            <td colspan="3" class="text_center">
                                @lang('reports.without_additional_gpa')
                            </td>
                            <td colspan="2">
                                {{gradeName(number_format($with_percent_average_gpa,2,'.',''))}}
                            </td>
                        @else
                            <td colspan="{{$colspan / $col_for_result + 9}}">
                                {{markGpa(number_format($totalavgMarkAddition / $op_subject_count))->gpa}}
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="{{$colspan / $col_for_result - 1}}" class="text_center">
                            @lang('exam.grade')
                        </td>
                        @if ($optional_subject_setup!='' && $student_optional_subject!='')
                            <td colspan="4">
                                {{number_format($total_with_optional_percentage,2,'.','')}}
                            </td>
                            <td colspan="3" class="text_center">
                                @lang('reports.without_additional_grade')
                            </td>
                            <td colspan="2">
                                {{number_format($with_percent_average_gpa,2,'.','')}}
                            </td>
                        @else
                            <td colspan="{{$colspan / $col_for_result + 9}}">
                                {{getGrade(number_format($totalavgMarkAddition / $op_subject_count))}}
                            </td>
                        @endif
                    </tr>
                    {{-- Remark Start --}}
                    <tr>
                        @if($optional_subject_setup!='' && $student_optional_subject!='')
                            <td colspan="{{$colspan / $col_for_result - 1}}" class="text_center">
                                @lang('reports.position')
                            </td>
                            <td colspan="{{$colspan / $col_for_result + 7}}">
                                {{getStudentAllExamMeritPosition($class_id, $section_id, $student_id)}}
                            </td>
                        @else
                            <td colspan="{{$colspan / $col_for_result - 1}}" class="text_center">
                                @lang('reports.position')
                            </td>
                            <td colspan="{{$colspan / $col_for_result + 9}}">
                                {{getStudentAllExamMeritPosition($class_id, $section_id, $student_id)}}
                            </td>
                        @endif
                    </tr>
                    <tr>
                        @if($optional_subject_setup!='' && $student_optional_subject!='')
                            <td colspan="{{$colspan / $col_for_result - 1}}">
                                @lang('homework.evaluation')
                            </td>
                            <td colspan="{{$colspan / $col_for_result + 7}}">
                                {{remarks(number_format($total_with_optional_percentage,2,'.',''))}}
                            </td>
                        @else
                            <td colspan="{{$colspan / $col_for_result - 1}}">
                                @lang('homework.evaluation')
                            </td>
                            <td colspan="{{$colspan / $col_for_result + 9}}">
                                {{remarks(markGpa(number_format($totalavgMarkAddition / $op_subject_count))->gpa)}}
                            </td>
                        @endif
                    </tr>
                    {{-- Remark End --}}

                @endif
                </tbody>
            </table>
            @php
                $examReportSignature = examReportSignatures();
            @endphp
            @if($examReportSignature->count() > 1 || !$exam_content)
                <div style="margin-top:auto; margin-bottom:20px; display: flex; justify-content: {{$examReportSignature->count() == 1 ? 'flex-end' : 'space-between'}}; flex-wrap: wrap; align-items: center;">
                    @foreach ($examReportSignature as $signature)
                        <div style="text-align: right; display: flex; align-items: center; justify-content: center; margin-right: 24px; flex-direction: column">
                            <div>
                                <img src="{{asset($signature->signature)}}" width="150px" height="40px"
                                     alt="{{$signature->title}}">
                            </div>
                            <p style="margin-top:8px; text-align: center; width: 100%; border-top: 1px solid rgba(67, 89, 187, 0.15) !important">
                                {{$signature->title}}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
            @if($exam_content)
                <table style="width:100%;  @if($examReportSignature->count() > 1) border-top: 1px solid #000 !important; @else  margin-top: auto; @endif"
                       @if($examReportSignature->count() == 1)class="border-0 mt-auto" @endif>
                    <tbody>
                    <tr>
                        <td class="border-0" style="border: 0 !important;">
                            <p class="result-date"
                               style="text-align:left; float:left; @if($examReportSignature->count() == 1)  margin-top:50px; @endif display:inline-block; padding-left: 0; color: #000;">
                                @lang('exam.date_of_publication_of_result') :
                                <strong>
                                    {{dateConvert(@$exam_content->publish_date)}}
                                </strong>
                            </p>
                        </td>
                        <td class="border-0" style="border: 0 !important;">
                            @if($examReportSignature->count() == 1)
                                <div class="text-right d-flex flex-column justify-content-end">
                                    <div class="thumb text-right">
                                        <img src="{{asset(@$examReportSignature->first()->signature)}}" width="100px">
                                    </div>
                                    <p style="text-align:right; float:right; display:inline-block; margin-top:5px;">
                                        ({{@$examReportSignature->first()->title}})
                                    </p>
                                </div>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            @endif

        </div>
    </div>
</div>
</body>
</html>