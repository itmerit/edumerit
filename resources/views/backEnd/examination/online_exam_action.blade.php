<x-drop-down>
    @php
        $is_set_online_exam_questions = DB::table('sm_online_exam_question_assigns')
            ->where('online_exam_id', $row->id)
            ->first();
        $startTime = strtotime($row->date . ' ' . $row->start_time);
        $endTime = strtotime($row->date . ' ' . $row->end_time);
        $now = date('h:i:s');
        $now = strtotime('now');
    @endphp
    @if ($startTime < $now && $row->status == 1)
    @else
        @if (!empty($is_set_online_exam_questions))
            @if (userPermission('manage_online_exam_question'))
                <a class="dropdown-item"
                    href="{{ route('manage_online_exam_question', [$row->id]) }}">@lang('exam.manage_question')</a>
            @endif
        @endif
    @endif

    @if ($startTime < $now && $row->status == 1)
        @if (userPermission('online_exam_marks_register'))
            <a class="dropdown-item"
                href="{{ route('online_exam_marks_register', [$row->id]) }}">@lang('exam.marks_register')</a>
        @endif
    @endif

    @if ($startTime < $now && $row->status == 1)
    @else
        @if (userPermission('online-exam-edit'))
            <a class="dropdown-item"
                href="{{ route('online-exam-edit', $row->id) }}">@lang('common.edit')</a>
        @endif
        @if (userPermission(241))
            <a onclick="examDelete({{ $row->id }})"
                href="javascript:void(0)"
                class="dropdown-item ">@lang('common.delete')</a>
        @endif
    @endif
    @if (!empty($is_set_online_exam_questions))
        @if (userPermission('online-exam-question-view'))
            <a class="dropdown-item"
                href="{{ route('online-exam-question-view', [$row->id]) }}">@lang('common.view_question')</a>
        @endif
    @endif
    @if ($row->end_date_time < date('Y-m-d H:i:s') && $row->status == 1)
        @if (userPermission('online_exam_result'))
            <a class="dropdown-item"
                href="{{ route('online_exam_result', [$row->id]) }}">@lang('exam.result')</a>
        @endif
    @endif
    </div>
    @if (empty($is_set_online_exam_questions))
        @if (userPermission('manage_online_exam_question'))
            <a class="ml-3" href="{{ route('manage_online_exam_question', [$row->id]) }}">
                <button class="primary-btn small bg-success text-white border-0">@lang('exam.set_question')
                </button>
            </a>
        @endif
    @else
        @if ($row->status == 0)
            <a class="ml-3" href="{{ route('online_exam_publish', [$row->id]) }}">
                <button class="primary-btn small bg-success text-white border-0">@lang('exam.published_now')
                </button>
            </a>
        @endif
    @endif
</x-drop-down>
