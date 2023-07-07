<!DOCTYPE html>
<html>
<head>
    <title>@lang('student.student_id_card')</title>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
    <style media="print">
		body{
			background: #fff;
		}


        td{
            border-right: 1px solid #ddd; 
            border-left: 1px solid #ddd;
            border-bottom: 1px solid #ddd; 
            padding-top: 3px; padding-bottom: 3px;
        }
        table tr td{
            border: 0 !important; 
        }

    </style>
    <style>
        .id_card {
            display: grid !important;grid-template-columns: repeat(2,1fr) !important;grid-gap: 10px;justify-content: center;
        }
        input#button {
            margin: 20px 0;
        }
        td {
        font-size: 11px;
        padding: 0 12px;
        line-height: 18px;
        }
body#abc {
    max-width: 900px;
    margin: auto;
}
table {
    width: 100%;
}
    </style>
</head>
<body id="abc">
        <input type="button" onclick="printDiv('abc')" id="button" class="primary-btn small fix-gr-bg" value="print" />
    {{-- <table style="height: 800px">
            <tr>  --}}
                <div class="id_card" id="id_card" style="display: grid !important;grid-template-columns: repeat(3,1fr) !important;grid-gap: 20px;justify-content: center;">
                    @foreach($students as $student)
    			     <table cellpadding="0" cellspacing="0" border="0" width="156" height="241" align="center" style=" border: 1px solid var(--border_color); margin: 0px 0 0 0;" >
                        <tr style="border-right: 1px solid #ddd; border-left: 1px solid #ddd;  height: 0px; ">
                            <td colspan="3" style=" position: relative; text-align: center; background-color: #c738d8; border:1px solid #c738d8">
                               <!--  <center>
                                    <img src="{{asset('public/backEnd/img/student/id-card-bg.png')}}" style="width: 100%; height: auto; padding: 0px; margin: 0px" >
                                </center> -->
                                <h3 style="padding: 5px; text-align: center; margin-bottom: 0px; font-size: 12px;  color: #fff; font-family: 'PT Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; ">@lang('common.view_student_id_card')</h3>
                            </td>
                        </tr>
                        <tr >
                            <td colspan="3" style="text-align: center;   border-right: 1px solid #ddd; border-left: 1px solid #ddd;">
                                <img src="{{ @$student->student_photo != "" ? asset(@$student->student_photo) : asset('public/backEnd/img/student/id-card-img.jpg') }}" alt="" style="width: 30%; margin-top: 5px;">
                            </td>
                        </tr>
                        @if(@$id_card->student_name == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('common.name')</td>
                            
                            <td style="text-align: right; margin-right: 40px !important; border-right: 1px solid #ddd">{{@$student->full_name}}</td>
                        </tr >
                        @endif
                        @if(!empty($id_card->academic_id))
                        <tr >
                            <td colspan="2" style="padding-left: 20px; white-space:nowrap; border-left: 1px solid #ddd">@lang('common.academic_year')</td>
                            
                            <td style="text-align: right; margin-right: 40px !important; border-right: 1px solid #ddd">{{@$student->academicYear->year}} - [{{@$student->academicYear->title}}]</td>
                        </tr >
                        @endif
                        @if(@$id_card->admission_no == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('student.admission_no')</td>
                            
                            <td style="text-align: right; margin-right: 40px !important; border-right: 1px solid #ddd">{{ @$student->admission_no}}</td>
                        </tr>
                        @endif
                        @if(@$id_card->class == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd;">@lang('common.class')</td>                            
                            <td style="text-align: right; margin-right: 40px !important; border-right: 1px solid #ddd">
                                {{ @$student->class!=""?@$student->class->class_name:""}} 
                                ({{ @$student->section!=""?@$student->section->section_name:""}})</td>
                        </tr>
                        @endif
                        @if(@$id_card->father_name == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('student.father_name')</td>                            
                            <td style="text-align: right; margin-right: 40px !important; border-right: 1px solid #ddd">{{@$student->parents !=""?@$student->parents->fathers_name:""}}</td>
                        </tr>
                        @endif

                        @if(@$id_card->mother_name == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; white-space:nowrap; border-left: 1px solid #ddd">@lang('student.mother_name')</td>                            
                            <td style="text-align: right; margin-right: 40px !important; border-right: 1px solid #ddd">{{@$student->parents !=""?@$student->parents->mothers_name:""}}</td>
                        </tr>
                        @endif
                        @if(@$id_card->student_address == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('student.student_address')</td>                           
                            <td style=" border-right: 1px solid #ddd; text-align: right; margin-right: 40px !important;">{{@$student->current_address!=""?@$student->current_address:""}}</td>
                        </tr >
                        @endif
                        @if(@$id_card->blood == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('student.blood_group')</td>                           
                            <td style=" border-right: 1px solid #ddd; text-align: right; margin-right: 40px !important;">{{@$student->bloodGroup!=""?@$student->bloodGroup->base_setup_name:""}}</td>
                        </tr >
                        @endif
                        @if(@$id_card->phone == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('common.phone')</td>
                           
                            <td style=" border-right: 1px solid #ddd; text-align: right; margin-right: 40px !important;">{{@$student->mobile}}</td>
                        </tr >
                        @endif
                        @if(@$id_card->dob == 1)
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">@lang('common.date_of_birth')</td>                            
                            <td style=" border-right: 1px solid #ddd; text-align: right; margin-right: 40px !important;">                              
                                {{@dateConvert($student->date_of_birth)}}
                            </td>
                        </tr>
                        @endif
                        <tr >
                            <td colspan="2" style="padding-left: 20px; border-left: 1px solid #ddd">{{@$id_card->designation}}</td>
                            <td style=" border-right: 1px solid #ddd; text-align: right; padding-right: 20px;"><img src="{{asset($id_card->signature)}}" width="40%" style="margin-right: 20px !important;"></td>
                        </tr>
                        <tr >
                            <td colspan="3" style="text-align: center; padding-top: 20px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd;">
                                <img src="{{asset($id_card->logo)}}" width="50%"  height="40px"
                                ><p>{{ @$id_card->address }}</p></td>
                        </tr>
                     </table>
                     @endforeach
                    </div>
                 {{-- </tr>
             </table> --}}
		
    <script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
    <script>


        
function printDiv(divName) {

    // document.getElementById("button").remove();

     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
            </script>
</body>
</html>

