
@if(count($subjects))
    @foreach($subjects as $subject)
    <div id="assign-subject_{{$subject->class_id}}">
        <div class="col-lg-12 mb-30" id="assign-subject-4">
            <div class="row mb-20 assignedSubject{{$subject->id}}"> 
                <div class="col-lg-3 mt-30-md"> 
                    <select class="primary_select form-control" name="subjects[]" id="subjects">
                        <option data-display="{{@$subject->subject->subject_name}}" value="{{@$subject->subject->id}}">{{@$subject->subject->subject_name}}</option>
                    </select> 
                </div> 
                <div class="col-lg-3 mt-30-md"> 
                <select class="nice-select primary_select form-control" name="teachers[]" id="teachers">; 
                    <option data-display="@lang('common.select_teacher') *" value="">@lang('common.select_teacher') * </option>
                        @foreach($teachers  as $teacher)
                            <option @if($subject->teacher_id == $teacher->id) selected @endif   value="{{@$teacher->id}}">{{@$teacher->full_name}}</option>
                        @endforeach
                </select> 
                </div>

                <div class="col-lg-4 mt-30-md"> 
                    <select multiple="multiple" class="multypol_check_select active position-relative selectSectionss"  name="exams[]" id="selectSectionss" style="width:300px"> 
                        
                            @foreach(getGlobalExamBySecClsSub($section_id,$class_id, $subject->subject_id)  as $exam)
                                <option value="{{@$exam->id}}">{{@$exam->GetGlobalExamTitle->title}}</option>
                            @endforeach
                    </select> 
                    </div>
                <div class="col-lg-2"> 
                    <button class="primary-btn icon-only fix-gr-bg" type="button">
                        <span class="ti-trash" id="removeSubject" onclick="deleteSubject({{$subject->id}})"></span> 
                    </button> 
                </div>
            </div> 
        </div>
    </div>
    @endforeach

@else 
<div id="assign-subject_{{$class_id}}">
    <div class="col-lg-12 mb-30" id="assign-subject-4">
    </div>
</div>

@endif 
@include('backEnd.partials.multi_select_js')

<script>
     $('.primary_select').niceSelect('destroy');        
     $(".primary_select").niceSelect();
     $(function () {
            $("select[multiple].active.multypol_check_select").multiselect({
                columns: 1,
                placeholder: "Select",
                search: true,
                searchOptions: {
                default: "Select",
                },
                
                selectAll: true,
            });
        });
    
</script>
