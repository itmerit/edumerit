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

<body>
<main style="width: 750px; margin: auto;">

    @foreach ($admitcards as $key=> $admitcard)
        <div class="card-item border border-3 p-10 @if($key != 0 & $key % 2 != 0) break @endif ">
            <table class="table w-full border p-10">
                <tr>
                    <td class="flex flex-wrap gap-2 items-center">
                        <div class="logo w-110">
                            <img src="{{ asset(generalSetting()->logo)}}" class="w-full"
                                 alt="{{ asset(generalSetting()->school_name)}}">
                        </div>
                        <div class="content">
                            <p class="fs-22 fw-bold text-black">{{generalSetting()->school_name}}</p>
                            @if($setting->admit_sub_title)
                                <p class="fs-16 fw-bold text-red">{{@$setting->admit_sub_title }}</p>
                            @endif
                            @if($setting->school_address)
                                <p class="fs-14 fw-bold text-black">{{generalSetting()->address}}</p>
                            @endif
                        </div>
                    </td>
                    <td class="text-end" width="35%">
                        <p class="fs-24 fw-bold text-black"
                           style="text-transform: uppercase;">@lang('examplan::exp.admit_card')</p>
                        @if($setting->exam_name || $setting->academic_year)
                            <p class="fs-14 fw-bold text-red" style="font-size: 13px;">
                                @endif
                                @if($setting->exam_name)
                                    {{$admitcard->examType->title}}
                                    @if($setting->academic_year)
                                        -
                                    @endif
                                @endif
                                @if($setting->academic_year)
                                    {{@$admitcard->studentRecord->academic->year}}
                                @endif
                                @if($setting->class_section || $setting->academic_year)
                            </p>
                        @endif
                        @if($setting->exam_name)
                            <p class="fs-14 fw-bold text-red" style="font-size: 13px;">{{$admitcard->examType->title}}
                                - {{@generalSetting()->academic_Year->year}} </p>
                        @endif
                    </td>
                </tr>
            </table>
            <div class="h-10"></div>
            <table class="table w-full border p-10">
                <tr>
                    <td>
                        @if($setting->admission_no)
                            <p class="fs-16 fw-bold text-black">@lang('student.admission_number') : <span
                                        class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->studentDetail->admission_no}}</span>
                            </p>
                        @endif
                    </td>
                    <td class="text-end">
                        <p class="fs-16 fw-bold text-black">@lang('student.date') : <span
                                    class="fs-14 fw-bold text-red">{{@dateConvert($admitcard->created_at)}}</span></p>
                    </td>
                </tr>
            </table>
            <div class="h-10"></div>
            <div class="user border p-10 flex">
                <table class="table w-full border">
                    <tr class="border-bottom">
                        <td>
                            @if($setting->student_name)
                                <p class="fs-16 fw-bold text-black">@lang('student.student_names') : <span
                                            class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->studentDetail->full_name}}</span>
                                </p>
                            @endif
                        </td>
                        <td class="text-start" width="25%" style="border-left: 1px solid #1a1818;">
                            @if($setting->class_section)
                                <p class="fs-16 fw-bold text-black">@lang('student.class') : <span
                                            class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->class->class_name}}</span>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td>
                            @if($setting->gaurdian_name)
                                <p class="fs-16 fw-bold text-black">@lang('student.father_names') : <span
                                            class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->studentDetail->parents->fathers_name}}</span>
                                </p>
                            @endif
                        </td>
                        <td class="text-start" width="25%" style="border-left: 1px solid #1a1818;">
                            @if($setting->class_section)
                                <p class="fs-16 fw-bold text-black">@lang('student.section') : <span
                                            class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->section->section_name}}</span>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if($setting->gaurdian_name)
                                <p class="fs-16 fw-bold text-black">@lang('student.mother_names') : <span
                                            class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->studentDetail->parents->mothers_name}}</span>
                                </p>
                            @endif
                        </td>
                        <td class="text-start" width="25%" style="border-left: 1px solid #1a1818;">
                            @if($setting->admission_no)
                                <p class="fs-16 fw-bold text-black">@lang('student.roll'): <span
                                            class="fs-14 fw-bold text-red">{{@$admitcard->studentRecord->studentDetail->roll_no}}</span>
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="profile flex items-center justify-center border">
                    @if($setting->student_photo)
                        <img src="{{asset(@$admitcard->studentRecord->studentDetail->student_photo != '' ? @$admitcard->studentRecord->studentDetail->student_photo : 'public/uploads/staff/demo/staff.jpg')}}"
                             alt="{{asset(@$admitcard->studentRecord->studentDetail->full_name)}}">
                    @endif
                </div>
            </div>
            <div class="h-10"></div>
            @if(@$setting->description)
                <div class="border p-10 info description_box">
                    {!! @$setting->description !!}
                </div>
            @endif
            {{-- <div class="h-30"></div> --}}
            <div class="signature text-end">
                @if($setting->principal_signature)

                    <div class="singnature_img">
                        @if($setting->principal_signature_photo)
                            <img src="{{asset($setting->principal_signature_photo)}}">
                        @endif
                    </div>

                    <p class="border-top fs-16 fw-normal text-black inline-block"> @lang('examplan::exp.exam_controller') </p>
                @endif
            </div>
        </div>
    @endforeach
</main>

<script>
    // $('.description_box').height();

    $(document).ready(function () {
        resize_to_fit();
    });

    function resize_to_fit() {
        var fontsize = $('.description_box').css('font-size');
        $('.description_box').css('fontSize', parseFloat(fontsize) - 1);

        if ($('.description_box').height() >= $('.description_box').height()) {
            resize_to_fit();
        }
    }
</script>
</body>

</html>