
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>    @lang('reports.merit_list_report')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
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
            max-width: 80%;
            margin: auto;
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
            grid-template-columns: 120px auto;
            grid-gap: 10px;
        }
        .line_grid span{
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .line_grid span:first-child{
            font-size: 14px;
            font-weight: 400;
            color: #000;
        }
        p.line_grid {
            color: var(--base_color);
            font-size: 14px;
            font-weight: 600;
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
            vertical-align: middle;
            font-size: 12px;
            color: #000;
            font-weight: 400;
            border: 0;
            border-bottom: 1px solid #000 !important;
            text-align: left; 
            padding: 13px 0;
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
            text-transform: uppercase;
            font-size: 12px;
            color: var(--base_color);
            font-weight: 500;
            text-align: left;
            border-bottom: 1px solid #a2a8c5;
            padding: 5px 0px;
            background: transparent !important ;
            border-bottom: 1px solid #000 !important;
            padding-left: 0px !important;
        }
        .gray_header_table {
            border: 0;
        }
        .gray_header_table tbody td, .gray_header_table tbody th {
            border-top: 1px solid rgba(67, 89, 187, 0.15) !important;
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
            color: #000;
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

        .custom_result_print{
            background-image: none;
        }
        .custom_result_print h3, .custom_result_print h5, .custom_result_print p{
            color: black;
        }

        .meritTableBodyCustomReport{
            padding: 30px;
        }
        @if(resultPrintStatus('vertical_boarder'))
        .border_table td, .border_table th{
            border: 1px solid #000 !important;
            padding: 10px !important;
        }
        .gray_header_table thead th{
            padding-left: 10px !important;
        }
        @endif
    </style>
</head>
{{-- <script>
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
</script> --}}
<body onLoad="loadHandler();">
    <div class="invoice_wrapper">
        <div class="invoice_print">
            <div class="container">
                <div class="invoice_part_iner">
                    <table class="table border_bottom m-0 {{(resultPrintStatus('header'))? "tableInfo_header": "tableInfo_header custom_result_print"}}" style="margin: 0" >
                        <thead>
                            <td>
                                <div class="logo_img">
                                    <div class="thumb_logo">
                                        <img  src="{{asset('/')}}{{generalSetting()->logo}}" alt="{{generalSetting()->school_name}}"></div>
                                    <div class="company_info">
                                        <h3>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                        <p>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}</p>
                                        <p>@lang('common.email'):  {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}},   @lang('common.phone'):  {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}} </p>
                                    </div>
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
                           <table class="mb_30 mr_auto">
                               <tbody>
                                    <tr>
                                        <td><h4 class="table_title">@lang('exam.order_of_merit_list')</h4></td>
                                    </tr>
                                    <tr>
                                        <td><p class="line_grid" ><span><span>@lang('common.academic_year')</span><span>:</span></span>{{ @$class->academic->year }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="line_grid" ><span><span>@lang('exam.exam')</span><span>:</span></span>{{$exam_name}}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="line_grid" ><span><span>@lang('common.search')</span><span>:</span></span>{{$class_name}}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="line_grid" ><span><span>@lang('common.section')</span><span>:</span></span>{{$section->section_name}}</p></td>
                                    </tr>
                               </tbody>
                           </table>
                        </td>
                        <td>
                            <table class="mb_30 max-width-500 mr_auto">
                                <tbody>
                                    <tr>
                                        <td><h4 class="table_title">@lang('common.subjects')</h4></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul class="subjectList">
                                                @foreach($assign_subjects as $subject)
                                                    <li>{{$subject->subject->subject_name}}</li>
                                                @endforeach 
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            @php
                $subject_mark= null;
            @endphp
            <table class="table border_table gray_header_table">
                <thead>
                  <tr>
                    <th>@lang('common.name')</th>
                    <th>@lang('student.admission_no')</th>
                    <th>@lang('student.roll_no')</th>
                    <th>@lang('exam.position')</th>
                    {{-- <th>@lang('lang.obtained_marks')</th> --}}
                    <th>@lang('exam.total_mark')</th>
                    @if (isset($optional_subject_setup))
                        <th>@lang('exam.gpa')
                            <hr>
                            <small>@lang('reports.without_additional')</small>
                        </th>
                        <th>@lang('exam.gpa')</th>
                    @else
                        <th>@lang('exam.gpa')</th>
                    @endif
                    @foreach($subjectlist as $subject)
                        <th>{{$subject}}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                    @foreach($allresult_data as $key => $row) 
                    @php
                        $total_student_mark = 0;
                        $total = 0;
                        $markslist = explode(',',$row->marks_string);
                        $get_subject_id = explode(',',$row->subjects_id_string);
                        $count=0;
                        $additioncheck=[];
                        $subject_mark=[];
                        $main_subject_total_gpa= 0;
                    @endphp
                    @foreach($markslist as $mark)   
                        @if(App\SmOptionalSubjectAssign::is_optional_subject($row->student_id,$get_subject_id[$count]))
                            @php
                                $additioncheck[] = $mark;
                            @endphp
                        @endif
                        @php
                            if(App\SmOptionalSubjectAssign::is_optional_subject($row->student_id,$get_subject_id[$count])){
                                $special_mark[$row->student_id]=$mark;
                            }
                            $count++;
                        @endphp 

                        @php
                            $subject_mark[]= $mark;
                            $total_student_mark = $total_student_mark + $mark; 
                            $total = $total + $subject_total_mark;
                        @endphp 
                    @endforeach
                    <tr>
                        <td>{{$row->student_name}}</td>
                        <td>{{$row->admission_no}}</td>
                        <td>{{$row->studentinfo->roll_no}}</td>
                        <td>{{@getStudentMeritPosition($InputClassId, $InputSectionId, $InputExamId, $row->studentinfo->studentRecord->id)}}</td>
                        <td>{{$row->total_marks}}</td>
                        <td>{{$row->gpa_point}}</td>
                        @if(!empty($markslist))
                                @foreach ($markslist as $key => $subject_mark)

                                @php
                                    $total_student_mark = $total_student_mark + $subject_mark;
                                    $total = $total + $subject_total_mark;
                                    $subject_id= $get_subject_id[$key];
                                    $total_subject= count($get_subject_id);
                                    $result = markGpa(@subjectPercentageMark($subject_mark , @subjectFullMark($row->exam_id, $subject_id, $row->class_id, $row->section_id)));
                                    $main_subject_total_gpa += $result->gpa;
                                @endphp
                                <td>{{!empty($subject_mark)? $subject_mark:0}}</td>
                            @endforeach
                            @php
                                $total_gpa= $main_subject_total_gpa/$total_subject;
                            @endphp
                        @endif
                        @if (isset($optional_subject_setup))
                            <td>
                                <?php
                                    if($row->result == $failgpaname->grade_name){
                                        echo $failgpa;
                                    }else{
                                        $total_grade_point = 0;
                                        $number_of_subject = count($subject_mark)-1;
                                        $c=0;
                                        foreach ($subject_mark as $key => $mark) {
                                            if ($additioncheck['0'] != $mark) {                                                                     
                                                $grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$mark)->where('percent_upto','>=',$mark)->where('academic_id', getAcademicId())->first();
                                                $total_grade_point = $total_grade_point + $grade_gpa->gpa;
                                                $c++;
                                            }
                                        }
                                        if($total_grade_point==0){
                                            echo $failgpa;
                                        }else{
                                            if($number_of_subject  == 0){
                                                echo $failgpa;
                                            }else{
                                                echo number_format((float)$total_grade_point/$c, 2, '.', '');
                                            } 
                                        } 
                                    }
                                ?>
                            </td>
                            @if (isset($optional_subject_setup))
                                @php
                                    if(!empty($special_mark[$row->student_id])){
                                                $optional_subject_mark=$special_mark[$row->student_id];
                                            }else{
                                                $optional_subject_mark=0;
                                            }
                                @endphp
                                <td>
                                    <?php 
                                        if($row->result == $failgpaname->grade_name){
                                            echo $failgpa;
                                        }else{
                                            $optional_grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$optional_subject_mark)->where('percent_upto','>=',$optional_subject_mark)->where('academic_id', getAcademicId())->first();
                                            $countable_optional_gpa=0;
                                            if ($optional_grade_gpa->gpa > $optional_subject_setup->gpa_above) {
                                                $countable_optional_gpa=$optional_grade_gpa->gpa - $optional_subject_setup->gpa_above;
                                            } else {
                                                $countable_optional_gpa=0;
                                            }
                                            $total_grade_point = 0;
                                            $number_of_subject = count($subject_mark)-1; 
                                            foreach ($subject_mark as $mark) {

                                                $grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$mark)->where('percent_upto','>=',$mark)->where('academic_id', getAcademicId())->first();
                                                $total_grade_point = $total_grade_point + $grade_gpa->gpa;
            
                                                
                                            }
                                            $gpa_with_optional=$total_grade_point-$optional_grade_gpa->gpa;
                                            $gpa_with_optional=$gpa_with_optional+$countable_optional_gpa;
                                                if($gpa_with_optional==0){
                                                    echo $failgpa;
                                                }else{
                                                    if($number_of_subject  == 0){
                                                        echo $failgpa;
                                                    }else{
                                                        $grade=number_format((float)$gpa_with_optional/$number_of_subject, 2, '.', '');
                                                        if ($grade>$maxGpa) {
                                                            echo $maxGpa;
                                                        } else {
                                                            echo $grade;
                                                        }
                                                    } 
                                                }
                                        }
                                    ?>
                                </td>
                            @else
                                <td>
                                    <?php 
                                    if($row->result == $failgpaname->grade_name){
                                        echo $failgpa;
                                    }else{
                                    $total_grade_point = 0;
                                        $number_of_subject = count($subject_mark)-1; 
                                        foreach ($subject_mark as $mark) {
                                            $grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$mark)->where('percent_upto','>=',$mark)->where('academic_id', getAcademicId())->first();
                                            $total_grade_point = $total_grade_point + $grade_gpa->gpa;
                                        }
                                        if($total_grade_point==0){
                                            echo $failgpa;
                                        }else{
                                            if($number_of_subject  == 0){
                                                echo $failgpa;
                                            }else{
                                                echo number_format((float)$total_grade_point/$number_of_subject, 2, '.', '');
                                            } 
                                        } 
                                    }
                                    ?>
                                </td>
                            @endif
                        @endif
                     </tr> 
                    @endforeach
                </tbody>
          </table>

        </div>
    </div>
</body>
</html>