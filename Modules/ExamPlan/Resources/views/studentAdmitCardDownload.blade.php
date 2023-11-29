<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('examplan::exp.admit_card')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #828BB2;
            font-weight: 400;
            margin: 0;
            padding: 0;
            line-height: 1.1;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--base_color);
            margin: 0;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-center {
            justify-content: center;
        }

        .flex-fill {
            flex: 1 1 auto;
        }

        .flex-column {
            flex-direction: column;
        }

        .student_img {
            border: 1px solid var(--base_color);
            width: 100px;
            height: 100px;
            padding: 5px;
            background-size: 100% 100%;
            background-position: center center;
            flex: 100px 0 0;
            position: relative;
        }
        .student_img::before{
              content: '';
              position: absolute;
              left: 1px;
              right: 1px;
              top: 1px;
              bottom: 1px;
                  border: 5px solid #fff;
                  z-index: 1
        }

        

        .logo_img {
            max-width: 150px;
        }

        .student_img img,
        .logo_img img {
            max-width: 100%;
        }

        .admit_card_wrapper {
            width: 180mm;
            margin: auto;
            padding: 20px 0;
        }

        .admit_card_wrapper_content h3 {
            font-size:24px;
            margin-bottom: 0px;

        }
        .single_student_information:not(:last-child) {
            margin-bottom: 4px;
      }
        .admit_card_wrapper_content p {
            font-size: 12px;
            margin: 0 0 3px 0;
        }

        .admit_card_wrapper_content h4 {
            font-size: 16px;
            text-transform: capitalize;
            display: inline-block;
            border-bottom: 1px solid var(--base_color);
            line-height: 1;
            margin: 20px 0 5px 0;
            font-weight: 500;
        }

        .admit_card_wrapper_content span {
            display: block;
            font-size: 14px;
        }

        .sep_name {
            flex: 110px 0 0;
        }

        .student_grid_box {
            grid-gap: 15px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .f_w_400 {
            font-weight: 400;
        }

        .f_w_500 {
            font-weight: 500;
        }

        .m-0 {
            margin: 0;
        }

        .table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }

        .table thead {}

        .table thead th {
            border: 1px solid var(--border_color);
            font-size: 12px;
            font-weight: 400;
            text-transform: uppercase;
            text-align: left;
            padding: 4px 10px;
            font-size: 12px;
        }

        .table tbody tr td {
            padding: 6px 14px;
            font-size: 12px;
            color: #828BB2;
        }

        .table tbody tr td {
            border: 1px solid rgba(130, 139, 178, 0.15);
        }

        .exam_routine {
            font-size: 16px;
            text-align: center;
            margin: 15px 0 5px 0;
        }

        .table {
            margin-bottom: 50px;
        }

        .singrature_boxs {
            padding-top: 20px;
        }

        .single_signature {
            width: 200px;
        }

        .single_signature span {
            border-top: 1px solid #828BB2;
            width: 100%;
            text-align: center;
            padding-top: 3px;
        }

        .admid_card_wrapper_body {
            margin-top: 30px;
        }
        .admit_card_wrapper:nth-of-type(2n+1) {
            border-bottom: 1px dashed #ddd;
      }
      .admit_card_wrapper{
            height: 148.5mm;
      }
      .singrature_boxs {
    padding-top: 20px;
    flex: 1 1 auto;
}
.admid_card_wrapper_body {
    margin-top: 30px;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
}
.admit_card_wrapper {
    height: 148.5mm;
}
.admit_card_wrapper {
    width: 180mm;
    margin: auto;
    padding: 20px 0;
    display: flex;
    flex-direction: column;
}
.singnature_img img{
    height: 55px;
}
.singnature_img {
    text-align: center;
}
    </style>
</head>

<body id="pdf">
    <div class="admit_card_wrapper">
        <!-- admit_card_wrapper_header  -->
        <div class="admit_card_wrapper_header d-flex">
            <div class="logo_img">
                <img src="{{ asset(generalSetting()->logo)}}" alt="{{generalSetting()->school_name}}">
            </div>
            <div class="admit_card_wrapper_content flex-fill d-flex align-items-center flex-column">
                <h3>{{generalSetting()->school_name}}</h3>
                @if($setting->school_address)
                    <p>{{generalSetting()->address}}</p>
                    <p>@lang('common.email') : {{generalSetting()->phone}} , @lang('common.phone') : {{generalSetting()->email}} </p>
                @endif    
                <h4>@lang('examplan::exp.admit_card')</h4>
                @if($setting->exam_name)
                    <span>{{@$admit->examType->title}}</span>
                @endif
            </div>

            @if($setting->student_photo)
                <div class="student_img" style="background-image: url('{{asset(@$studentRecord->studentDetail->student_photo)}}')">
                </div>
            @endif
        </div>
        <div class="admid_card_wrapper_body">
            <div class="student_info">
                <div class="single_student_information d-flex align-items-center">
                    @if($setting->student_name)
                        <div class="student_grid_box d-flex align-items-center flex-fill">
                            <span class="sep_name">@lang('common.name')</span>
                            <span>:</span>
                            <span class="f_w_500 theme_text text-uppercase">{{@$studentRecord->studentDetail->full_name}}</span>
                        </div>
                    @endif

                    @if($setting->class_section)
                      <p class="m-0">@lang('student.class') - {{@$studentRecord->class->class_name}} ({{@$studentRecord->section->section_name}}) {{@$studentRecord->academic->year}}</p>
                    @endif
                    </div>
              
                @if($setting->gaurdian_name)
                <div class="single_student_information d-flex align-items-center">
                    <div class="student_grid_box d-flex align-items-center flex-fill">
                        <span class="sep_name">@lang('student.guardian')</span>
                        <span>:</span>
                        <span class="f_w_400  text-uppercase">{{@$studentRecord->studentDetail->parents->guardians_name}}</span>
                    </div>
                    <p class="m-0">@lang('student.category') - {{@$studentRecord->studentDetail->category->category_name}}</p>
                </div>
                @endif
         
            </div>
            <h4 class="exam_routine">@lang('examplan::exp.exam_routine')</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('examplan::exp.date_time')</th>
                        <th>@lang('examplan::exp.subject')</th>
                        <th>@lang('examplan::exp.date_time')</th>
                        <th>@lang('examplan::exp.subject')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exam_routines as $date => $exam_routine)
                    @if($loop->iteration % 2 == 1 )
                    <tr> 
                        @endif
                            <td>{{ dateConvert($exam_routine->date) }} [{{ date('h:i A', strtotime(@$exam_routine->start_time))  }} - {{ date('h:i A', strtotime(@$exam_routine->end_time))  }}]</td>
                            <td>{{ $exam_routine->subject ? $exam_routine->subject->subject_name :'' }} </strong>  {{ $exam_routine->subject ? '('.$exam_routine->subject->subject_code .')':'' }}</td>
                        @if($loop->last % 2 == 1)
                        <td colspan="2"></td>
                        @endif
                            @if($loop->iteration % 2 == 0 || $loop->last % 2 == 1)
                            <tr> 
                             @endif
                    @endforeach
                </tbody>
            </table>
            <div class="singrature_boxs d-flex justify-content-between align-items-center">
                @if($setting->class_teacher_signature)
                <div class="single_signature d-flex  flex-column">
                    <div class="singnature_img">
                        <img src="{{asset($setting->principal_signature_photo)}}">
                    </div>
                    <span>@lang('examplan::exp.class_teacher')</span>
                </div>
                @endif
                @if($setting->principal_signature)
                <div class="single_signature d-flex  flex-column">
                    <div class="singnature_img">
                        <img src="{{asset($setting->teacher_signature_photo)}}">
                    </div>
                    <span>@lang('examplan::exp.principal_signature') </span>
                </div>
                @endif

            </div>
        </div>
        <!-- end_admit_card_wrapper_header  -->
    </div>
<script src="{{ asset('public/vendor/spondonit/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2canvas.min.js') }}"></script>

@if( auth()->user()->role_id == 2 &&  $setting->student_download)
<script>
      function generatePDF() {
          const element = document.getElementById('pdf');
          var opt = {
              margin:       0.2,
              pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '#page2el' },
              filename:     '{{"Admit Card ".@$admit->examType->title}}',
              image:        { type: 'jpeg', quality: 100 },
              html2canvas:  { scale: 5 },
              jsPDF:        { unit: 'in', format: 'a4'}
          };
          html2pdf().set(opt).from(element).save().then(function(){
          });
      }
  
      $(document).ready(function(){
          generatePDF();
      })
</script>

@endif

@if( auth()->user()->role_id == 3 &&  $setting->parent_download)
<script>
      function generatePDF() {
          const element = document.getElementById('pdf');
          var opt = {
              margin:       0.2,
              pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '#page2el' },
              filename:     '{{"Admit Card ".@$admit->examType->title}}',
              image:        { type: 'jpeg', quality: 100 },
              html2canvas:  { scale: 5 },
              jsPDF:        { unit: 'in', format: 'a4'}
          };
          html2pdf().set(opt).from(element).save().then(function(){
          });
      }
  
      $(document).ready(function(){
          generatePDF();
      })
</script>

@endif

</body>

</html>