<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('alumni::al.certificate')</title>
    <style>
        /* don't need that style css */
        
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        
        body {
            max-width: 800px;
            margin: auto;
            /* padding: 40px; */
        }
        /* don't need that style css */
    </style>
        <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
        <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>

</head>

@foreach($students as $student)
<body id="pdf">
    @php
        $body = App\SmStudentCertificate::certificateBody($certificate->body, 2,$student->student_id,$certificate->id);
        $body_two = App\SmStudentCertificate::certificateBody($certificate->body_two, 2,$student->student_id,$certificate->id);
        $certificateNumber = App\SmStudentCertificate::certificateNumber($certificate->certificate_no,$student->studentDetail->admission_no, @$student->alumni->graduation_date);

    @endphp 
   
    <table style="width: 100%;padding: 30px;">
        <tr>
            <td style="font-size: 16px; font-weight:700;width: 45%;text-transform: uppercase;">{{generalSetting()->school_name}}</td>
            <td style="width: 15%;text-align: center;">
                <div style=" margin-left: auto;">
                    <img src="{{asset(generalSetting()->logo)}}" alt="" style="width: 100%;height: 100%;">
                </div>
            </td>
            <td style="font-size: 18px; font-weight: 700; text-align: center;width: 35%;text-transform: uppercase;">كلية المقاصد الجامعية</td>
        </tr>
        <tr>
            <td colspan="3" height="50px"></td>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 16px; font-weight: 500;line-height: 1.3;">{!!$body!!}</td>
            <td></td>
            <td style="text-align: center; font-size: 16px; font-weight: 500;line-height: 1.3;">{!!$body_two!!}</td>
        </tr>
       
        <tr>
            <td colspan="3" height="20px"></td>
        </tr>
        
        <tr>
            <td colspan="3" height="20px"></td>
        </tr>
       
        
        <tr>
            <td style="text-align: center; font-size: 16x; font-weight: 600;line-height: 1.3;">
                <div style="display: flex;align-items: center;justify-content: space-between; margin-bottom: 30px;">
                    <div>
                        Dean Manager of the college
                        <span style="display: block;height: 1px;background-color: black;margin-top: 35px;"></span>
                    </div>
                    <div>
                        Registrar
                        <span style="display: block;height: 1px;background-color: black;margin-top: 35px;"></span>
                    </div>
                </div>
                <div style="text-align: right;"></div>
            </td>
            <td></td>
            <td style="text-align: center; font-size: 16x; font-weight: 600;line-height: 1.3;">
                <div style="display: flex;align-items: center;justify-content: space-between; margin-bottom: 30px;">
                    <div>
                        عميد مدير الكلية
                        <span style="display: block;height: 1px;background-color: black;margin-top: 35px;"></span>
                    </div>
                    <div>
                        المسجل
                        <span style="display: block;height: 1px;background-color: black;margin-top: 35px;"></span>
                    </div>
                </div>
                <div style="text-align: right;">{{@$certificateNumber}}</div>
            </td>
        </tr>
    </table>

<script src="{{ asset('public/vendor/spondonit/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2canvas.min.js') }}"></script>

<script>
    function generatePDF() {
        const element = document.getElementById('pdf');
        var opt = {
            margin:       0.5,
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '#page2el' },
            filename:     'student-final-certificate.pdf',
            image:        { type: 'jpeg', quality: 100 },
            html2canvas:  { scale: 5 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save().then(function(){
        });
    }
    $(document).ready(function(){

        generatePDF();

    })
</script>


</body>
@endforeach
</html>