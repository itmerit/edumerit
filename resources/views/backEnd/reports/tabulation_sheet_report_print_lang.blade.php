<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('exam.tabulation_sheet')</title>
    @if (isset($single))
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
                max-width: 100%;
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
                border-bottom: 1px solid #dee2e6  !important;
            }
            th p span, td p span{
                color: #212E40;
            }
            .table th {
                color: #00273d;
                font-weight: 300;
                border-bottom: 1px solid #dee2e6  !important;
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
                display: flex;
                grid-gap: 10px;
                white-space: nowrap
            }
            .line_grid span{
                display: flex;
                align-items: center;
                white-space: nowrap;
            }
            .line_grid span:first-child{
                font-size: 14px;
                font-weight: 500;
                color: #828bb2;
            }
            .line_grid{
                font-weight: 600;
                color: var(--base_color);
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
                vertical-align: middle;
                text-align: center;
            }
            .border_table tbody tr td {
                text-align: center !important;
                color: #828bb2;
                padding: 8px 8px;
                font-weight: 400;
                background-color: #fff;
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
                margin-bottom: 10px;
                color: #fff;
            }
            .company_info{
                margin-left: 20px;
            }

            .company_info {
                margin-left: 20px;
                flex: 1 1 auto;
                text-align: right;
            }

            .table_title{
                text-align: center;
            }
            .table_title h3{
                font-size: 16px;
                text-transform: uppercase;
                margin-top: 15px;
                font-weight: 500;
                display: block;
                border-bottom: 1px solid rgba(0, 0, 0, .1);
                padding-bottom: 7px;
            }

            .gray_header_table{
                /* border: 1px solid var(--border_color); */
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
            .profile_thumb {
                flex-grow: 1;
                text-align: right;
            }
            .line_grid .student_name{
                font-weight: 500;
                font-size: 14px;
                color: var(--base_color);
            }
            .line_grid span {
                display: flex;
                align-items: center;
                flex: 120px 0 0;
            }
            .line_grid.line_grid2 span {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex: 60px 0 0;
            }
            .student_name_highlight{
                font-weight: 500;
                color: var(--base_color);
                line-height: 1.5;
                font-size: 20px;
                text-transform: uppercase;

            }
            .report_table th {
                border: 1px solid #dee2e6;
                color: var(--base_color);
                font-weight: 500;
                text-transform: uppercase;
                vertical-align: middle;
                font-size: 12px;
            }
            .report_table th, .report_table td{
                background: transparent !important;
            }
            .tabu_table.border_table tr td,
            .tabu_table.border_table tr th{
                padding: 5px;
                font-size: 10px;
            }
            .tabu_table.border_table tr th{
                background: transparent !important;
            }
            .tabu_table.border_table td{
                background: #f2f2f2 !important;
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
                /* padding-left: 0px !important; */
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
                text-align: center !important;
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

            .gray_header_table thead tr:first-child th:nth-last-child(-n+3) {
                border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            }











        </style>
    @elseif(isset($allClass))
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
                max-width: 100%;
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
                border-bottom: 1px solid #dee2e6  !important;
            }
            th p span, td p span{
                color: #212E40;
            }
            .table th {
                color: #00273d;
                font-weight: 300;
                border-bottom: 1px solid #dee2e6  !important;
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
                display: flex;
                grid-gap: 10px;
            }
            .line_grid span{
                display: flex;
                align-items: center;
                white-space: nowrap;
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
                text-align: center !important;
                color: #828bb2;
                padding: 8px 8px;
                font-weight: 400;
                background-color: #fff;
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
                margin-bottom: 16px;
                color: #fff;
            }
            .logo_img h5{
                font-size: 14px;
                margin-bottom: 9px;
                color: #fff;
            }
            .company_info{
                margin-left: 20px;
            }

            .company_info {
                margin-left: 20px;
                flex: 1 1 auto;
                text-align: right;
            }

            .table_title{
                text-align: center;
            }
            .table_title h3{
                font-size: 16px;
                text-transform: uppercase;
                margin-top: 15px;
                font-weight: 500;
                display: block;
                border-bottom: 0;
                padding-bottom: 7px;
            }

            .gray_header_table{
                /* border: 1px solid var(--border_color); */
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
            .profile_thumb {
                flex-grow: 1;
                text-align: right;
            }
            .line_grid .student_name{
                font-weight: 500;
                font-size: 14px;
                color: var(--base_color);
            }
            .line_grid span {
                display: flex;
                align-items: center;
                flex: 120px 0 0;
            }
            .line_grid.line_grid2 span {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex: 60px 0 0;
            }
            .student_name_highlight{
                font-weight: 500;
                color: var(--base_color);
                line-height: 1.5;
                font-size: 20px;
                text-transform: uppercase;

            }
            .report_table th {
                border: 1px solid #dee2e6;
                color: var(--base_color);
                font-weight: 500;
                text-transform: uppercase;
                vertical-align: middle;
                font-size: 12px;
            }
            .report_table th, .report_table td{
                background: transparent !important;
            }
            .tabu_table.border_table tr td,
            .tabu_table.border_table tr th{
                padding: 5px;
                font-size: 10px;
            }
            .tabu_table.border_table tr th{
                background: transparent !important;
            }
            .tabu_table.border_table td{
                background: #fff !important;
            }
            .logo_thumb_upper {
                flex: 1 1 auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .company_info {
                margin-left: 20px;
                flex: 1 1 auto;
                text-align: right;
            }
            .logo_img h2 {
                color: #fff;
                font-size: 18px;
                font-weight: 400
            }
            .logo_img h2 p{
                font-size: 13px;
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
                vertical-align: middle;
                text-align: center;
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
                margin-bottom: 17px;
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
                text-align: center !important;
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
            .border_table tr:first-of-type th:nth-child(-n+2){
                border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            }
            .gray_header_table thead tr:first-child th:nth-child(-n+2) {
                border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            }
            .gray_header_table thead tr:first-child th:nth-last-child(-n+2) {
                border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            }

            .gray_header_table thead tr:first-child th:nth-last-child(-n+3) {
                border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
            }
        </style>
    @endif
</head>
    <script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome){
        window.print();
        //    setTimeout(function(){window.close();}, 10000); 
        //give them 10 seconds to print, then close
        }else{
            window.print();
        }
    </script>
    <body onLoad="loadHandler();">
        @if (isset($single))
            <div class="invoice_wrapper">
                <div class="invoice_print mb_30">
                    <div class="container">
                        <div class="invoice_part_iner">
                            <table class="table border_bottom mb_30" >
                                <thead>
                                    <td>
                                        <div class="logo_img">
                                            <div class="thumb_logo">
                                                <img  src="{{asset('/')}}{{generalSetting()->logo }}" alt="{{generalSetting()->school_name}}">
                                            </div>
                                            <div class="company_info">
                                                <h3>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}</h3>
                                                <h5>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}</h5>
                                                <h5>@lang('common.email'): {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}}, @lang('common.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}}</h5>
                                            </div>
                                        </div>
                                    </td>
                                </thead>
                            </table>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <div class="table_title" style="margin-bottom: 20px; text-align: center">
                                            <h3>@lang('reports.tabulation_sheet_of') {{$tabulation_details['exam_term']}} @lang('reports.in') {{$year}}</h3>
                                        </div>
                                        <table class="mb_30 max-width-500 mr_auto">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p class="line_grid" >
                                                            <span>
                                                                <span>@lang('student.student_name')</span><span>:</span>
                                                            </span>
                                        {{$tabulation_details['student_name']}}
                                    </p>
                                </td>
                                <td>
                                    <p class="line_grid" >
                                                            <span>
                                                                <span>@lang('common.search')</span><span>:</span>
                                                            </span>
                                        {{$tabulation_details['student_class']}}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="line_grid" >
                                                            <span>
                                                                <span>@lang('student.roll_no')</span><span>:</span>
                                                            </span>
                                        {{$tabulation_details['student_roll']}}
                                    </p>
                                </td>
                                <td>
                                    <p class="line_grid" >
                                                            <span>
                                                                <span>@lang('common.section')</span><span>:</span>
                                                            </span>
                                        {{$tabulation_details['student_section']}}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="line_grid" >
                                                            <span>
                                                                <span>@lang('student.admission_no')</span><span>:</span>
                                                            </span>
                                        {{$tabulation_details['student_admission_no']}}
                                    </p>
                                </td>
                                <td>
                                    <p class="line_grid" >
                                                            <span>
                                                                <span>@lang('exam.exam')</span><span>:</span>
                                                            </span>
                                                            {{$tabulation_details['exam_term']}}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <table class="table border_table gray_header_table mb-0" >
                    <thead>
                        <tr>
                            @foreach($subjects as $subject)
                                @php
                                    $subject_ID     = $subject->subject_id;
                                    $subject_Name   = $subject->subject->subject_name;
                                    $mark_parts      = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                                @endphp
                                <th colspan="{{count($mark_parts)+1}}" class="subject-list"> {{$subject_Name}}</th>
                            @endforeach
                                <th rowspan="2">@lang('exam.total_mark')</th>
                            @if ($optional_subject_setup!='')
                                <th>@lang('exam.gpa')</th>
                                <th rowspan="2" >@lang('exam.gpa')</th>
                                <th rowspan="2">@lang('reports.result')</th>
                            @else
                                <th rowspan="2">@lang('exam.gpa')</th>
                                <th rowspan="2">@lang('reports.result')</th>
                            @endif
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                @php
                                    $subject_ID     = $subject->subject_id;
                                    $subject_Name   = $subject->subject->subject_name;
                                    $mark_parts     = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                                @endphp
                                @foreach($mark_parts as $sigle_part)
                                    <th>{{$sigle_part->exam_title}} ({{$sigle_part->exam_mark}})</th>
                                @endforeach
                                <th>@lang('exam.result')</th>
                                {{-- <th>@lang('exam.gpa')</th> --}}
                            @endforeach
                            @if ($optional_subject_setup!='')
                                <th><small>@lang('reports.without_additional')</small></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php  
                            $count=1;  
                        @endphp
                        @foreach($students as $student)
                            @php 
                                $this_student_failed=0; 
                                $tota_grade_point= 0; 
                                $tota_grade_point_main= 0; 
                                $marks_by_students = 0; 
                                $main_subject_total_gpa = 0; 
                                $Optional_subject_count = 0; 
                                $optional_subject=App\SmOptionalSubjectAssign::where('student_id','=',$student->id)->where('session_id','=',$student->session_id)->first();
                                $opt_sub_gpa=0;
                                $optional_subject_gpa=0;
                            @endphp
                            <tr>
                                @foreach($subjects as $subject)
                                    @php
                                        $subject_ID     = $subject->subject_id;
                                        $subject_Name   = $subject->subject->subject_name;
                                        $mark_parts     = App\SmAssignSubject::getMarksOfPart($student->id, $subject_ID, $class_id, $section_id, $exam_term_id);
                                        $subject_count= 0;
                                        $optional_subject_marks=DB::table('sm_optional_subject_assigns')
                                        ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
                                        ->where('sm_optional_subject_assigns.student_id','=',$student->id)
                                        ->first();
                                    @endphp
                                    @foreach($mark_parts as $sigle_part)
                                        <td class="total">{{$sigle_part->total_marks}}</td>
                                    @endforeach
                                    <td class="total">
                                        @php
                                            $tola_mark_by_subject = App\SmAssignSubject::getSumMark($student->id, $subject_ID, $class_id, $section_id, $exam_term_id);
                                            $marks_by_students  = $marks_by_students + $tola_mark_by_subject;
                                        @endphp
                                        {{$tola_mark_by_subject}}
                                    </td>
                                    @php
                                                $value=subjectFullMark($exam_term_id, $subject_ID);
                                                $persentage=subjectPercentageMark($tola_mark_by_subject,$value);
                                                $mark_grade = App\SmMarksGrade::where([
                                                            ['percent_from', '<=', $persentage], 
                                                            ['percent_upto', '>=', $persentage]])
                                                            ->where('academic_id', getAcademicId())
                                                            ->where('school_id',Auth::user()->school_id)
                                                            ->first();
                    
                                                $mark_grade_gpa=0;
                                                $optional_setup_gpa=0;
                                                if (@$optional_subject->subject_id==$subject_ID) {
                                                    $optional_setup_gpa= @$optional_subject_setup->gpa_above;
                                                    if ($mark_grade->gpa >$optional_setup_gpa) {
                                                        $mark_grade_gpa = $mark_grade->gpa-$optional_setup_gpa;
                                                        $tota_grade_point = $tota_grade_point + $mark_grade_gpa;
                    
                                                        $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                                    
                                                    } else {
                                                        $tota_grade_point = $tota_grade_point + $mark_grade_gpa;
                                                        $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                                    }
                                                } else {
                                                    $tota_grade_point = $tota_grade_point + $mark_grade->gpa ;
                                                    if($mark_grade->gpa<1){
                                                        $this_student_failed =1;
                                                    }
                                                    $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                                }
                                            @endphp
                                @endforeach
                                <td>{{$marks_by_students}}</td>
                                @php 
                                    $marks_by_students = 0; 
                                @endphp
                                @if ($optional_subject_setup!='')
                                    <td>                          
                                        @if(isset($this_student_failed) && $this_student_failed==1)
                                            @if(!empty($tota_grade_point_main))
                                            <p id="main_subject_total_gpa"></p>
                                            @endif
                                        @else
                                            @php
                                                if (@$optional_subject!='') {
                                                    if(!empty($tota_grade_point_main)){
                                                        $subject = count($subjects)-1;
                                                        $without_optional_subject=($tota_grade_point_main - $opt_sub_gpa) - $optional_subject_gpa;
                                                        $number = number_format($without_optional_subject/ $subject , 2, '.', '');
                                                    }else{
                                                        $number = 0;
                                                    }
                                                } else{
                                                    $subject_count=count($subjects);
                                                    if(!empty($tota_grade_point_main)){
                                                        $number = number_format($tota_grade_point_main/ $subject_count, 2, '.', '');
                                                    }else{
                                                        $number = 0;
                                                    }
                                                }  
                                            @endphp
                                                {{$number==0?'0.00':$number}}
                                            @php 
                                                $subject_count=0;
                                                $tota_grade_point_main= 0; 
                                                $subject_count =count($subjects)-1;
                                            @endphp
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $subject_count=0;
                                            $subject_count =count($subjects)-1;
                                        @endphp
                                        @if(isset($this_student_failed) && $this_student_failed==1)
                                            {{number_format($tota_grade_point/ $subject_count, 2, '.', '')}}
                                        @else
                                            @php
                                                if (@$optional_subject!='') {
                                                    $subject_count=count($subjects)-1;
                                                        if(!empty($tota_grade_point)){
                                                            $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                        }else{
                                                            $number = 0;
                                                        }
                                                } else{
                                                    $subject_count=count($subjects);
                                                        if(!empty($tota_grade_point)){
                                                            $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                        }else{
                                                            $number = 0;
                                                        }
                                                }
                                            @endphp
                                            @if ($number>$max_grade)
                                                {{$max_grade}}
                                            @else
                                                {{$number==0?'0.00':$number}}
                                            @endif
                                            @php 
                                                $tota_grade_point= 0; 
                                            @endphp
                                        @endif
                                    </td>
                                    <td>
                                        
                                        @if(isset($this_student_failed) && $this_student_failed==1)
                                            <span class="text-warning font-weight-bold">{{$fail_grade_name->grade_name}}</span>
                                        @else
                                            @php
                                                if($number >= $max_grade){
                                                    echo gradeName($max_grade);
                                                }else{
                                                    echo gradeName($number);
                                                }
                                            @endphp
                                        @endif
                                    </td>
                                @else
                                    <td>
                                        @if(isset($this_student_failed) && $this_student_failed==1)
                                            {{number_format($tota_grade_point/ count($subjects), 2, '.', '')}}
                                        @else
                                            @php
                                                $subject_count=0;
                                                if (@$optional_subject!='') {
                                                    $subject_count=count($subjects)-1;
                                                        if(!empty($tota_grade_point)){
                                                            $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                        }else{
                                                            $number = 0;
                                                        }
                                                } else{
                                                    $subject_count=count($subjects);
                                                        if(!empty($tota_grade_point)){
                                                            $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                        }else{
                                                            $number = 0;
                                                        }
                                                }
                                            @endphp    
                                                {{$number==0?'0.00':$number}}
                                            @php 
                                                $tota_grade_point= 0; 
                                            @endphp
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($this_student_failed) && $this_student_failed==1)
                                            <span class="text-warning font-weight-bold">{{$fail_grade_name->grade_name}}</span>
                                        @else
                                        @php
                                        $main_subject_total_gpa=0;
                                        $Optional_subject_count=0;
                                            if($optional_subject_mark!=''){
                                                $Optional_subject_count=$subjects->count()-1;
                                            }else{
                                                $Optional_subject_count=$subjects->count();
                                            }
                                        @endphp
                                            {{gradeName($number)}}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </tr>
                    </tbody>
                </table>
            </div>

            <table class="table border_table gray_header_table mb-0" >
                <thead>
                    <tr>
                        @foreach($subjects as $subject)
                            @php
                                $subject_ID     = $subject->subject_id;
                                $subject_Name   = $subject->subject->subject_name;
                                $mark_parts      = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                            @endphp
                            <th colspan="{{count($mark_parts)+1}}" class="subject-list"> {{$subject_Name}}</th>
                        @endforeach
                        <th rowspan="2">@lang('exam.total_mark')</th>
                        @if ($optional_subject_setup!='')
                            <th>@lang('exam.gpa')</th>
                            <th rowspan="2" >@lang('exam.gpa')</th>
                            <th rowspan="2">@lang('exam.result')</th>
                        @else
                            <th rowspan="2">@lang('exam.gpa')</th>
                            <th rowspan="2">@lang('exam.result')</th>
                        @endif
                    </tr>
                    <tr>
                        @foreach($subjects as $subject)
                            @php
                                $subject_ID     = $subject->subject_id;
                                $subject_Name   = $subject->subject->subject_name;
                                $mark_parts     = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                            @endphp
                            @foreach($mark_parts as $sigle_part)
                                <th>{{$sigle_part->exam_title}} ({{$sigle_part->exam_mark}})</th>
                            @endforeach
                            <th>@lang('exam.total')</th>
                            {{-- <th>@lang('exam.gpa')</th> --}}
                        @endforeach
                        @if ($optional_subject_setup!='')
                            <th><small>@lang('exam.without_additional')</small></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count=1;
                    @endphp
                    @foreach($students as $student)
                        @php
                            $this_student_failed=0;
                            $tota_grade_point= 0;
                            $tota_grade_point_main= 0;
                            $marks_by_students = 0;
                            $main_subject_total_gpa = 0;
                            $Optional_subject_count = 0;
                            $optional_subject=App\SmOptionalSubjectAssign::where('student_id','=',$student->id)->where('session_id','=',$student->session_id)->first();
                            $opt_sub_gpa=0;
                            $optional_subject_gpa=0;
                        @endphp
                        <tr>
                            @foreach($subjects as $subject)
                                @php
                                    $subject_ID     = $subject->subject_id;
                                    $subject_Name   = $subject->subject->subject_name;
                                    $mark_parts     = App\SmAssignSubject::getMarksOfPart($student->id, $subject_ID, $class_id, $section_id, $exam_term_id);
                                    $subject_count= 0;
                                    $optional_subject_marks=DB::table('sm_optional_subject_assigns')
                                    ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
                                    ->where('sm_optional_subject_assigns.student_id','=',$student->id)
                                    ->first();
                                @endphp
                                @foreach($mark_parts as $sigle_part)
                                    <td class="total">{{$sigle_part->total_marks}}</td>
                                @endforeach
                                <td class="total">
                                    @php
                                        $tola_mark_by_subject = App\SmAssignSubject::getSumMark($student->id, $subject_ID, $class_id, $section_id, $exam_term_id);
                                        $marks_by_students  = $marks_by_students + $tola_mark_by_subject;
                                    @endphp
                                    {{$tola_mark_by_subject}}
                                </td>
                                @php
                                    $value=subjectFullMark($exam_term_id, $subject_ID);
                                    $persentage=subjectPercentageMark($tola_mark_by_subject,$value);
                                    $mark_grade = markGpa($persentage);

                                    $mark_grade_gpa=0;
                                    $optional_setup_gpa=0;
                                    if (@$optional_subject->subject_id==$subject_ID) {
                                        $optional_setup_gpa= @$optional_subject_setup->gpa_above;
                                        if ($mark_grade->gpa >$optional_setup_gpa) {
                                            $mark_grade_gpa = $mark_grade->gpa-$optional_setup_gpa;
                                            $tota_grade_point = $tota_grade_point + $mark_grade_gpa;

                                            $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;

                                        } else {
                                            $tota_grade_point = $tota_grade_point + $mark_grade_gpa;
                                            $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                        }
                                    } else {
                                        $tota_grade_point = $tota_grade_point + $mark_grade->gpa ;
                                        if($mark_grade->gpa<1){
                                            $this_student_failed =1;
                                        }
                                        $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                    }
                                @endphp
                            @endforeach
                            <td>{{$marks_by_students}}</td>
                            @php
                                $marks_by_students = 0;
                            @endphp
                            @if ($optional_subject_setup!='')
                                <td>
                                    @if(isset($this_student_failed) && $this_student_failed==1)
                                        @if(!empty($tota_grade_point_main))
                                            <p id="main_subject_total_gpa"></p>
                                        @endif
                                    @else
                                        @php
                                            if (@$optional_subject!='') {
                                                if(!empty($tota_grade_point_main)){
                                                    $subject = count($subjects)-1;
                                                    $without_optional_subject=($tota_grade_point_main - $opt_sub_gpa) - $optional_subject_gpa;
                                                    $number = number_format($without_optional_subject/ $subject , 2, '.', '');
                                                }else{
                                                    $number = 0;
                                                }
                                            } else{
                                                $subject_count=count($subjects);
                                                if(!empty($tota_grade_point_main)){
                                                    $number = number_format($tota_grade_point_main/ $subject_count, 2, '.', '');
                                                }else{
                                                    $number = 0;
                                                }
                                            }
                                        @endphp
                                        {{$number==0?'0.00':$number}}
                                        @php
                                            $subject_count=0;
                                            $tota_grade_point_main= 0;
                                            $subject_count =count($subjects)-1;
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $subject_count=0;
                                        $subject_count =count($subjects)-1;
                                    @endphp
                                    @if(isset($this_student_failed) && $this_student_failed==1)
                                        {{number_format($tota_grade_point/ $subject_count, 2, '.', '')}}
                                    @else
                                        @php
                                            if (@$optional_subject!='') {
                                                $subject_count=count($subjects)-1;
                                                    if(!empty($tota_grade_point)){
                                                        $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                    }else{
                                                        $number = 0;
                                                    }
                                            } else{
                                                $subject_count=count($subjects);
                                                    if(!empty($tota_grade_point)){
                                                        $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                    }else{
                                                        $number = 0;
                                                    }
                                            }
                                        @endphp
                                        @if ($number>$max_grade)
                                            {{$max_grade}}
                                        @else
                                            {{$number==0?'0.00':$number}}
                                        @endif
                                        @php
                                            $tota_grade_point= 0;
                                        @endphp
                                    @endif
                                </td>
                                <td>

                                    @if(isset($this_student_failed) && $this_student_failed==1)
                                        <span class="text-warning font-weight-bold">{{$fail_grade_name->grade_name}}</span>
                                    @else
                                        @php
                                            if($number >= $max_grade){
                                                echo gradeName($max_grade);
                                            }else{
                                                echo gradeName($number);
                                            }
                                        @endphp
                                    @endif
                                </td>
                            @else
                                <td>
                                    @if(isset($this_student_failed) && $this_student_failed==1)
                                        {{number_format($tota_grade_point/ count($subjects), 2, '.', '')}}
                                    @else
                                        @php
                                            $subject_count=0;
                                            if (@$optional_subject!='') {
                                                $subject_count=count($subjects)-1;
                                                    if(!empty($tota_grade_point)){
                                                        $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                    }else{
                                                        $number = 0;
                                                    }
                                            } else{
                                                $subject_count=count($subjects);
                                                    if(!empty($tota_grade_point)){
                                                        $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                    }else{
                                                        $number = 0;
                                                    }
                                            }
                                        @endphp
                                        {{$number==0?'0.00':$number}}
                                        @php
                                            $tota_grade_point= 0;
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    @if(isset($this_student_failed) && $this_student_failed==1)
                                        <span class="text-warning font-weight-bold">{{$fail_grade_name->grade_name}}</span>
                                    @else
                                        @php
                                            $main_subject_total_gpa=0;
                                            $Optional_subject_count=0;
                                                if($optional_subject_mark!=''){
                                                    $Optional_subject_count=$subjects->count()-1;
                                                }else{
                                                    $Optional_subject_count=$subjects->count();
                                                }
                                        @endphp
                                        {{gradeName($number)}}
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                function myFunction(value, subject) {
                    if (value != "") {
                        var res = Number(value / subject).toFixed(2);
                    } else {
                        var res = 0;
                    }
                    document.getElementById("main_subject_total_gpa").innerHTML = res;
                }
                myFunction({{ $main_subject_total_gpa }}, {{ $Optional_subject_count }});
            </script>
        @elseif (isset($allClass))
            <div class="invoice_wrapper fullwidth_90">
                <div class="invoice_print mb_30">
                    <div class="container">
                        <div class="invoice_part_iner">
                            <table class="table border_bottom mb_30" >
                                <thead>
                                <td>
                                    <div class="logo_img">
                                        <div class="logo_thumb_upper">
                                            <div class="thumb_logo">
                                                <img  src="{{asset('/')}}{{generalSetting()->logo }}" alt="{{generalSetting()->school_name}}">
                                            </div>
                                            <h2>
                                                @lang('common.search') : {{$tabulation_details['class']}}
                                                <br>
                                                <p>@lang('common.section') : {{$tabulation_details['section']}}</p>
                                            </h2>
                                        </div>
                                        <div class="company_info">
                                            <h3>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}</h3>
                                            <h5>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}</h5>
                                            <h5>@lang('exam.email'): {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}}, @lang('exam.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141 '}}</h5>
                        </div>
                        <div class="invoice_wrapper fullwidth_90">
                            <div class="invoice_print mb_30">
                                <div class="container">
                                    <div class="invoice_part_iner">
                                        <table class="table border_bottom mb_30" >
                                            <thead>
                                                <td>
                                                    <div class="logo_img">
                                                        <div class="logo_thumb_upper">
                                                            <div class="thumb_logo">
                                                                <img  src="{{asset('/')}}{{generalSetting()->logo }}" alt="{{generalSetting()->school_name}}">
                                                            </div>
                                                            <h2>
                                                                @lang('common.class') : {{$tabulation_details['class']}}
                                                                <br>
                                                                <p>@lang('common.section') : {{$tabulation_details['section']}}</p>
                                                            </h2>
                                                        </div>
                                                        <div class="company_info">
                                                            <h3>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}</h3>
                                                            <h5>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}</h5>
                                                            <h5>@lang('common.email'): {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}}, @lang('common.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141 '}}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                            </thead>
                                        </table>
                                        <div class="table_title" style="margin-bottom: 20px; text-align: center">
                                            <h3>
                                                @lang('reports.tabulation_sheet_of') {{$tabulation_details['exam_term']}} @lang('reports.in') {{$year}}
                                            </h3>
                                        </div>
                                    </div>
                                </td>
                                </thead>
                            </table>
                            <div class="table_title" style="margin-bottom: 20px; text-align: center">
                                <h3>
                                    @lang('exam.tabulation_sheet') @lang('exam.of') {{$tabulation_details['exam_term']}} @lang('exam.in') {{$year}}
                                </h3>
                            </div>
                            <table class="table border_table gray_header_table mb-0" >
                                <thead>
                                    <tr>
                                        <th rowspan="3">@lang('common.name')</th>
                                        <th rowspan="3">@lang('student.roll_no')</th>
                                            @foreach($subjects as $subject)
                                                @php
                                                    $subject_ID     = $subject->subject_id;
                                                    $subject_Name   = $subject->subject->subject_name;
                                                    $mark_parts      = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                                                @endphp
                                                <th colspan="{{count($mark_parts)+1}}" class="subject-list"> {{$subject_Name}}</th>
                                            @endforeach
                                            <th rowspan="2"> @lang('exam.total_mark')</th>
                                            @if ($optional_subject_setup!='')
                                                <th>@lang('exam.gpa')</th>
                                                <th rowspan="2">@lang('exam.gpa')</th>
                                                <th rowspan="2">@lang('reports.result')</th>
                                            @else
                                                <th colspan="1" rowspan="3"> @lang('exam.gpa')</th>
                                            @endif
                                    </tr>
                                    <tr>
                                    @if ($optional_subject_setup!='')
                                        @foreach($subjects as $subject)
                                            @php
                                                $subject_ID     = $subject->subject_id;
                                                $subject_Name   = $subject->subject->subject_name;
                                                $mark_parts     = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                                            @endphp
                                            @foreach($mark_parts as $sigle_part)
                                                <th>{{$sigle_part->exam_title}}</th>
                                            @endforeach
                                                <th>@lang('exam.result')</th>
                                        @endforeach
                                        @if ($optional_subject_setup!='')
                                            <th><small>@lang('reports.without_additional')</small></th>
                                        @endif
                                    @else
                                        @php
                                            if (@$optional_subject!='') {
                                                if(!empty($tota_grade_point_main)){
                                                    $subject = count($subjects)-1;
                                                    $without_optional_subject=($tota_grade_point_main - $opt_sub_gpa) - $optional_subject_gpa;
                                                    $number = number_format($without_optional_subject/ $subject , 2, '.', '');
                                                }else{
                                                    $number = 0;
                                                }
                                            } else{
                                                $subject_count=count($subjects);
                                                if(!empty($tota_grade_point_main)){

                                                    $number = number_format($tota_grade_point_main/ $subject_count, 2, '.', '');
                                                }else{
                                                    $number = 0;
                                                }
                                            }
                                        @endphp
                                        @if ($number >= $max_grade)
                                            {{$max_grade}}
                                        @else
                                            {{$number==0?'0.00':$number}}
                                        @endif
                                        @php
                                            $subject_count=0;
                                            $tota_grade_point_main= 0;
                                            $subject_count =count($subjects)-1;
                                        @endphp
                                    @endif
                                </td>
                            @endif

                            <td>
                                @if(isset($this_student_failed) && $this_student_failed == 1)
                                    {{number_format($tota_grade_point/ count($subjects), 2, '.', '')}}
                                @else
                                    @php
                                        $subject_count=0;
                                        if (@$optional_subject!='') {
                                            $subject_count=count($subjects)-1;
                                                if(!empty($tota_grade_point)){
                                                    $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                }else{
                                                    $number = 0;
                                                }
                                        } else{
                                            $subject_count=count($subjects);
                                                if(!empty($tota_grade_point)){
                                                    $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                                }else{
                                                    $number = 0;
                                                }
                                        }
                                    @endphp
                                    @if ($number >= $max_grade)
                                        {{$max_grade}}
                                    @else
                                        {{$number==0?'0.00':$number}}
                                    @endif
                                    @php
                                        $tota_grade_point= 0;
                                    @endphp
                                @endif
                            </td>
                            <td>
                                @if(isset($this_student_failed) && $this_student_failed==1)
                                    <span class="text-warning font-weight-bold">
                                                            {{$fail_grade_name->grade_name}}
                                                        </span>
                                @else
                                    @if($number >= $max_grade)
                                        {{gradeName($max_grade)}}
                                    @else
                                        {{gradeName($number)}}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <script>
                    function myFunction(value, subject) {
                        if (value != "") {
                            var res = Number(value / subject).toFixed(2);
                        } else {
                            var res = 0;
                        }
                        document.getElementById("main_subject_total_gpa").innerHTML = res;
                    }
                    myFunction({{ $main_subject_total_gpa }}, {{ $Optional_subject_count }});
                </script>
            </div>
        @endif
    </body>
</html>
