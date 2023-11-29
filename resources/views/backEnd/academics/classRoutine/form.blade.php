{{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'add-new-class-routine-store',
'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'myForm', 'onsubmit' => "return validateAddNewroutine()"]) }}

    <input type="hidden" name="day" value="{{ $day_id }}">
    <input type="hidden" name="class_id" value="{{ $class_id }}">
    <input type="hidden" name="section_id" value="{{ $section_id }}">
        <div class="white-box">
            <div class="">
                <table class="table" id="classRoutineTable">
                   
                    <thead>
                        <tr>
                            <th>@lang('common.subject')</th>
                            <th>@lang('common.teacher')</th>
                            <th>@lang('academics.start_time')</th>
                            <th>@lang('academics.end_time')</th>
                            <th>@lang('academics.is_break')</th>
                            <th>@lang('academics.other_day')</th>
                            <th>@lang('academics.class_room')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    <input type="hidden" id="row_count" value="{{ $class_routines->count() + 1 }}">
                        @foreach ( $class_routines as $routine)

                            @includeIf('backEnd.academics.classRoutine.row', ['row' => $loop->index])
                        @endforeach
                       

                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-lg-12 mt-20 text-center">
            <button class="primary-btn fix-gr-bg" type="submit" id="classRoutineSubmitButton">
             <span class="ti-check"></span>
             @lang('common.save')
            </button>
        </div>
   {{ Form::close() }}