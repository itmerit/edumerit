<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('examplan::exp.admit_card')</title>
    <!-- All Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Free Web tutorials">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="og:title" property="og:title" content="The Title of Your Article">
    <meta name="twitter:card" content="summary">
    <meta name="robots" content="noindex, nofollow">



    <!-- Main css -->
    <link rel="stylesheet" href="{{asset('Modules/ExamPlan/Public/assets/css/style.min.css')}}">

    <!--[if lt IE 9]>
        <script src="https://www.microsoft.com/en-us/download/details.aspx?id=38270"></script>
    <![endif]-->

</head>

<body id="pdf">
    <main style="width: 750px; margin: auto;">
   
        <div class="card-item border border-3 p-10">
            <table class="table w-full border p-10">
                <tr>
                    <td class="flex flex-wrap gap-2 items-center">
                        <div class="logo w-110">
                            <img src="{{ asset(generalSetting()->logo)}}" class="w-full" alt="{{ asset(generalSetting()->school_name)}}">
                        </div>
                        <div class="content">
                            <p class="fs-22 fw-bold text-black">{{generalSetting()->school_name}}</p>
                            @if($setting->admit_sub_title)
                                <p class="fs-16 fw-bold text-red">{{@$setting->admit_sub_title }}</p>
                            @endif
                            <p class="fs-14 fw-bold text-black">{{generalSetting()->address}}</p>
                        </div>
                    </td>
                    <td class="text-end">
                        <p class="fs-24 fw-bold text-black">@lang('examplan::exp.admit_card')</p>
                        <p class="fs-14 fw-bold text-red">{{$admit->examType->title}} - {{@generalSetting()->academic_Year->year}} </p>
                    </td>
                </tr>
            </table>
            <div class="h-10"></div>
            <table class="table w-full border p-10">
                <tr>
                    <td>
                        @if($setting->admission_no)
                        <p class="fs-16 fw-bold text-black">@lang('student.admission_number') : <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->studentDetail->admission_no}}</span></p>
                        @endif 
                    </td>
                    <td class="text-end">
                        <p class="fs-16 fw-bold text-black">@lang('student.date') : <span class="fs-14 fw-bold text-red">{{@dateConvert($admit->created_at)}}</span></p>
                    </td>
                </tr>
            </table>
            <div class="h-10"></div>
            <div class="user border p-10 flex">
                <table class="table w-full border">
                    <tr class="border-bottom">
                        <td>
                            @if($setting->student_name)
                            <p class="fs-16 fw-bold text-black">@lang('student.student_name') : <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->studentDetail->full_name}}</span></p>
                            @endif 
                        </td>
                        <td class="text-end">
                            @if($setting->class_section)
                            <p class="fs-16 fw-bold text-black">@lang('student.class') : <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->class->class_name}}</span></p>
                            @endif 
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td>
                            @if($setting->gaurdian_name)
                            <p class="fs-16 fw-bold text-black">@lang('student.father_name') : <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->studentDetail->parents->guardians_name}}</span></p>
                            @endif 
                        </td>
                        <td class="text-end">
                            @if($setting->class_section)
                            <p class="fs-16 fw-bold text-black">@lang('student.section') : <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->section->section_name}}</span></p>
                            @endif 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if($setting->gaurdian_name)
                            <p class="fs-16 fw-bold text-black">@lang('student.mother_name') : <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->studentDetail->parents->mothers_name}}</span></p>
                            @endif 
                        </td>
                        <td class="text-end">
                            @if($setting->admission_no)
                            <p class="fs-16 fw-bold text-black">@lang('student.roll'): <span class="fs-14 fw-bold text-red">{{@$admit->studentRecord->studentDetail->roll_no}}</span></p>
                            @endif 
                        </td>
                    </tr>
                </table>
                <div class="profile flex items-center justify-center border">
                    @if($setting->student_photo)
                    <img src="{{asset(@$admit->studentRecord->studentDetail->student_photo ?? 'public/uploads/staff/demo/staff.jpg')}}" alt="{{asset(@$admit->studentRecord->studentDetail->full_name)}}">
                    @endif 
                </div>
            </div>
            <div class="h-10"></div>
            <div class="border p-10 info">
                {!! @$setting->description !!}
                
            </div>
            <div class="h-30"></div>
            <div class="signature text-end">
                @if($setting->principal_signature)
                <div class="singnature_img">
                <img src="{{asset($setting->principal_signature_photo)}}">
                </div>
                <p class="border-top fs-16 fw-normal text-black inline-block"> @lang('examplan::exp.exam_controller') </p>
                @endif 
            </div>
        </div>
        
    </main>
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