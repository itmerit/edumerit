@php 
    $total_hours = 0;
    $grand_total = 0;
@endphp
<div class="white-box no-search no-paginate no-table-info mb-2">
    @foreach ($records as $record)
    <button class="primary-btn-small-input primary-btn small fix-gr-bg pull-right" type="button" data-toggle="modal"
    data-target="#assignClass"> <span class="ti-plus pr-2"></span> @lang('university::un.assign_subject')</button> 
            @if ($record->is_promote == 0  && !$record->unSemesterLabel->unExamAttendance)
                <button class="primary-btn-small-input primary-btn small fix-gr-bg pull-right" type="button" data-toggle="modal"
                data-target="#assignClass"> <span class="ti-plus pr-2"></span> @lang('university::un.assign_subject')</button>
            @endif
        <div class="main-title">
            <h3 class="mb-2">{{ @$record->unSemesterLabel->title }}</h3>
        </div>
        
        <table class="table school-table-style res_scrol" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>@lang('exam.subject') </th>
                    <th>@lang('university::un.subject_type')</th>
                    <th>@lang('common.teacher')</th>
                    <th>@lang('university::un.pass_mark')</th>
                    <th>@lang('university::un.hours')</th>
                    <th>@lang('university::un.cost_hours') ({{generalSetting()->currency_symbol}})</th>
                    <th>@lang('exam.total') ({{generalSetting()->currency_symbol}})</th>
                    <th>@lang('common.status')</th>
                    <th>@lang('common.action') </th>
                </tr>
            </thead>
            <tbody>

                @foreach ($record->unStudentSemesterWiseSubjects as $subject)
                    <tr>
                        @php
                            $result = labelWiseStudentResult($record, $subject->un_subject_id);
                            $assignDetail = Modules\University\Entities\UnSubjectAssignStudent::assignDetail($subject->un_subject_id, $subject->un_semester_label_id);
                            $total_hours += $subject->subject->number_of_hours;
                            $grand_total += $assignDetail['amount'];
                        @endphp
                        <td> {{ @$subject->subject->subject_name . '[' . $subject->subject->subject_code . ']' }}</td>
                        <td> {{ $subject->subject->subject_type }}</td>
                        <td> {{ $assignDetail['teacher'] ? $assignDetail['teacher']->teacher->full_name :'' }}</td>
                        <td> {{ $subject->subject->pass_mark ? $subject->subject->pass_mark .'%' :'' }}</td>
                        <td> {{ $subject->subject->number_of_hours }}</td>
                        <td> {{ $assignDetail['cost_per_hours'] }} </td>
                        <td> {{  $assignDetail['amount'] }}</td>
                        <td>{{ $record->is_promote == 0 ? __('common.ongoing') : __('common.completed') }}</td>
                        <td> 
                            @if ($record->is_promote == 0  && !$record->unSemesterLabel->unExamAttendance)
                                <a href="#" class="primary-btn icon-only fix-gr-bg" data-toggle="modal" data-target="#deleteSubject_{{ $subject->id }}">
                                    <span class="ti-trash"></span>
                                </a>  
                            @endif      
                          
                        </td>
                    </tr>
                    @if ($record->is_promote == 0  && !$record->unSemesterLabel->unExamAttendance)
                    <div class="modal fade admin-query" id="deleteSubject_{{ $subject->id }}" >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('common.delete')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <div class="text-center">
                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                    </div>

                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                                        <form action="{{route('university.subject.assign.delete')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="un_subject_id" value="{{ $subject->subject->id }}">
                                            <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                                            <input type="hidden" name="record_id" value="{{ $record->id }}">
                                            <input type="hidden" name="un_semester_label_id" value="{{ $subject->un_semester_label_id }}">
                                          
                                            <button type="submit" class="primary-btn fix-gr-bg">@lang('common.delete')</button>

                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach


                {{-- request subject --}}
                @foreach ($record->unStudentRequestSubjects as $reqSubject)
                <tr>
                    @php
                        $result = labelWiseStudentResult($record, $reqSubject->un_subject_id);
                        $assignDetail = Modules\University\Entities\UnSubjectAssignStudent::assignDetail($reqSubject->un_subject_id)
                    @endphp
                    <td> {{ @$reqSubject->unSubject->subject_name . '[' . $reqSubject->unSubject->subject_code . ']' }}</td>
                    <td> {{ $reqSubject->unSubject->subject_type }}</td>
                    <td> {{ $assignDetail['teacher'] ? $assignDetail['teacher']->teacher->full_name : '' }}</td>
                    <td> {{ $reqSubject->unSubject->pass_mark ? $reqSubject->unSubject->pass_mark .'%' :'' }}</td>
                    <td> {{ $reqSubject->unSubject->number_of_hours }}</td>
                    <td> {{ $assignDetail['cost_per_hours'] }} </td>
                    <td> {{  $assignDetail['amount'] }}</td>
                    <td>{{ $record->is_promote == 0 ? __('common.pending') : __('common.completed') }}</td>
                    <td>                    
                        <a href="#" class="primary-btn icon-only fix-gr-bg" data-toggle="modal" data-target="#deleteReqSubject_{{ $reqSubject->id }}">
                            <span class="ti-trash"></span>
                        </a>  
                        <a href="#" class="primary-btn icon-only fix-gr-bg" data-toggle="modal" data-target="#approveReqSubject_{{ $reqSubject->id }}">
                            <span class="ti-check"></span>
                        </a> 
                    </td>
                </tr>
            
                <div class="modal fade admin-query" id="approveReqSubject_{{ $reqSubject->id }}" >
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ @$reqSubject->unSubject->subject_name . '[' . $reqSubject->unSubject->subject_code . ']' }}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
        
                            <div class="modal-body">
                                <div class="text-center">
                                    <h4>@lang('common.are_you_sure_to_approve')</h4>
                                </div>
        
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
        
                                    <form action="{{route('university.subject.request.approve')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="record_id" value="{{ $record->id }}">
                                        <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                                        <input type="hidden" name="un_semester_label_id" value="{{ $record->un_semester_label_id }}">
                                        <input type="hidden" name="un_subject_id" value="{{ $reqSubject->un_subject_id }}">
                                        <button type="submit" class="primary-btn fix-gr-bg">@lang('common.approve')</button>
        
                                    </form>
        
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div>
          

                <div class="modal fade admin-query" id="deleteReqSubject_{{ $reqSubject->id }}" >
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('common.delete')</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
        
                            <div class="modal-body">
                                <div class="text-center">
                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                </div>
        
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
        
                                    <form action="{{route('university.subject.request.delete')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="req_subject_id" value="{{ $reqSubject->id }}">
                                    
                                        <button type="submit" class="primary-btn fix-gr-bg">@lang('common.delete')</button>
        
                                    </form>
        
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div>
            @endforeach
              

            </tbody>



            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th>@lang('university::un.total_hours')</th>
                    <th></th>
                    <th>{{$total_hours}}</th>
                    <th>@lang('fees.grand_total') ({{@$currency}})</th>
                    <th>{{$grand_total}}</th>
                    <th></th>
                    <th> </th>
                    
                </tr>
            </tfoot>


        </table>


        <div class="modal fade admin-query" id="assignClass">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            @lang('university::un.assign_subject')
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                       
                        {{ Form::open(['class' => 'form-horizontal','files' => true,'route' => 'university.assign-student-subject','method' => 'POST']) }}


                        <input type="hidden" name="record_id" value="{{ $record->id }}">
                        <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                       
                        <div class="col-lg-12 mt-25 pl-0">
                            <div class="col-lg-12 " id="selectSectionsDiv" style="margin-top: -25px;">
                                <label for="checkbox"
                                    class="mb-2">@lang('university::un.assign_more_subject_for_this_student')</label>
                                <select multiple id="selectSectionss" name="subject[]" style="width:300px">
                                    @foreach ($record->withOutPreSubject as $subject)
                                    <option value="{{ $subject->id }}" >
                                        {{ $subject->subject_name 
                                        . '[' . $subject->subject_code . ']' 
                                        .'['. $subject->subject_type.']' 
                                        .'['. $subject->number_of_hours.']' }} </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('subject'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('subject') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12 mt-5 text-center">
                            <button type="submit" class="primary-btn fix-gr-bg" id="student_promote_submit">
                                <span class="ti-check"></span>
                                @lang('common.assign')
                            </button>
                        </div>

                        {{ Form::close() }}
                       
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>
