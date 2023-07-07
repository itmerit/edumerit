<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('examplan::exp.seat_plan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Signika", sans-serif;
            font-weight: 700;
            color: #45395E;
            margin: 0px;
        }

        *,
        ::before,
        ::after {
            margin: 0;
            box-sizing: border-box;
        }

        .text-center {
            text-align: center !important;
        }

        .seat {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            -moz-column-gap: 40px;
            column-gap: 40px;
            row-gap: 20px;
        }

        .seat-item:nth-child(8n+8) {
            page-break-after: always;
        }

        .seat-list {
            font-family: "Signika", sans-serif;
            justify-content: space-between;
            padding: 8px;
            border: 1px solid #156AAF;
            outline: 3px solid #156AAF;
            color: #45395E;
            margin-top: 20px;
            --left-size: 110px;
        }

        .seat-list h3 {
            font-size: 18px;
            line-height: 1.2142857143;
            margin-bottom: 5px;
            color: #6E6D6B;
        }

        .seat-list button {
            background-color: #9ED0F6;
            color: #000000;
            border-radius: 4px;
            padding: 4px 10px;
            border: none;
            transition: all 0.3s ease-in-out;
            font-size: 12px;
            line-height: 1.25;
            display: block;
            margin: auto;
        }

        .seat-user {
            margin-top: 10px;
            display: flex;
        }

        .seat-user h2 {
            font-size: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
            line-height: 1.2222222222;
            height: 37px;
            overflow: hidden;
        }

        .seat-user h4 {
            font-size: 14px;
            line-height: 1.2142857143;
        }

        .seat-user ul {
            padding: 0;
            margin: 0;
            list-style: none;
            border: 1px solid #33ACBF;
        }

        .seat-user ul li {
            text-align: center;
            padding: 5px;
            color: #000000;
            font-size: 14px;
        }

        .seat-user ul li:not(:last-child) {
            border-bottom: 1px solid #33ACBF;
        }

        .seat-img {
            width: var(--left-size);
            height: 116px;
            overflow: hidden;
            border: 2px solid #33ACBF;
            margin-top: auto;
        }

        .seat-img img {
            width: 100%;
            min-height: 100%;
            height: auto;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .seat-left {
            flex: 0 0 100%;
            max-width: calc(100% - var(--left-size));
            padding-right: 20px;
        }

        .seat-right {
            flex: 0 0 100%;
            max-width: var(--left-size);
            margin-top: auto;
        }

        @media print {
            button {
                -webkit-print-color-adjust: exact;
            }
        }

        .d-flex {
            display: flex !important;
            flex-wrap: wrap;
        }


    </style>
</head>


<body style="padding: 20px;padding-top: 0; width: 800px; margin: auto;">
<div class="seat">
    @foreach($seat_plans as $seat_plan)
        <div class="seat-item">
            <div class="seat-list">
                <div class="text-center">
                    @if($setting->school_name )
                        <h3 class="text-center">{{generalSetting()->school_name}}</h3>
                    @endif
                   
                        <button class="btn">
                            @if($setting->exam_name)
                                {{$seat_plan->examType->title}}
                                @endif 
                                @if($setting->academic_year)
                                @if($setting->exam_name)  -  @endif  {{@$seat_plan->academicYear->year}} 
                                @endif
                           
                        
                            @if(($seat_plan->studentRecord->studentDetail->student_category_id))
                                <b> ({{$seat_plan->studentRecord->studentDetail->category->category_name}}) </b>
                            @endif
                            @if(@$seat_plan->studentRecord->studentDetail->category->category_name)
                                    <b> ({{@$seat_plan->studentRecord->studentDetail->category->category_name}}) </b>
                                @endif

                        </button>
                   
                </div>
                <div class="seat-user">
                    <div class="seat-left" @if(!$setting->student_photo) style="max-width: 100%; padding-right: 0px;" @endif>
                        @if($setting->student_name)
                            <h2>{{@$seat_plan->studentRecord->studentDetail->full_name}}</h2>
                        @endif
                        <ul>
                            @if($setting->class_section)
                                <li>{{@$seat_plan->studentRecord->class->class_name}}
                                    ({{@$seat_plan->studentRecord->section->section_name}})
                                </li>
                            @endif
                            @if($setting->roll_no)
                                <li>@lang('student.roll_number')
                                    : {{@$seat_plan->studentRecord->studentDetail->roll_no}}</li>
                            @endif
                            @if($setting->admission_no)
                                <li>@lang('student.admission_no')
                                    : {{@$seat_plan->studentRecord->studentDetail->admission_no}}</li>
                            @endif
                        </ul>
                    </div>
                    @if($setting->student_photo)
                        <div class="seat-right">
                            <div class="seat-img">
                                @if($seat_plan->studentRecord->studentDetail->student_photo)
                                    <img src="{{asset(@$seat_plan->studentRecord->studentDetail->student_photo)}}"
                                         alt="{{@$seat_plan->studentRecord->studentDetail->full_name}}">
                                @else
                                    <img src="{{asset('Modules/ExamPlan/Public/images/profile.jpg')}}"
                                         alt="{{@$seat_plan->studentRecord->studentDetail->full_name}}">

                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--
        <div class="single_seat d-flex">
            <div class="single_seat_left flex-fill">
                <div class="seat_head">
                    @if($setting->school_name )
            <h3 class="text-center">{{generalSetting()->school_name}}</h3>

        @endif
        <div class="exam_name text-center text-capitalize">
@if($setting->exam_name)
            {{$seat_plan->examType->title}}
        @endif
        @if($setting->academic_year)
            {{@$seat_plan->academicYear->year}}
        @endif

        </div>
@if($setting->student_name)
            <h4 class="student_name text-uppercase">{{@$seat_plan->studentRecord->studentDetail->full_name}}</h4>

        @endif
        @if(isset($seat_plan->studentRecord->studentDetail->category))
            <h5 class="student_group">{{@$seat_plan->studentRecord->studentDetail->category->category_name}}</h5>

        @endif
        </div>
    </div>
    <div class="single_seat_right">
        <div class="student_img">
@if($setting->student_photo)
            @if($seat_plan->studentRecord->studentDetail->student_photo)
                <img src="{{asset(@$seat_plan->studentRecord->studentDetail->student_photo)}}" alt="{{@$seat_plan->studentRecord->studentDetail->full_name}}">

            @else
                <img src="{{asset('Modules/ExamPlan/Public/images/profile.jpg')}}" alt="{{@$seat_plan->studentRecord->studentDetail->full_name}}">
                        

            @endif
        @endif
        </div>
        <div class="student_info d-flex flex-column">
@if($setting->class_section)
            <span>{{@$seat_plan->studentRecord->class->class_name}}({{@$seat_plan->studentRecord->section->section_name}})</span>

        @endif
        @if($setting->roll_no)
            <span>@lang('student.roll_number') : {{@$seat_plan->studentRecord->studentDetail->roll_no}}</span>

        @endif
        @if($setting->admission_no)
            <span>@lang('student.admission_no') : {{@$seat_plan->studentRecord->studentDetail->admission_no}}</span>

        @endif
        </div>
    </div>
</div>-->
    @endforeach
</div>
</body>

</html>