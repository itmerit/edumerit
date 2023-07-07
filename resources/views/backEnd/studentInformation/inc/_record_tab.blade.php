<div role="tabpanel" class="tab-pane fade {{ Session::get('studentRecord') == 'active' ? 'show active' : '' }}"
    id="studentRecord">
    <div class="white-box">
        <div class="text-right mb-20">
            @if (userPermission(1201))
                <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button" data-toggle="modal"
                    data-target="#assignClass"> <span class="ti-plus pr-2"></span> @lang('common.add')</button>
            @endif
        </div>
        <table id="" class="table simple-table table-responsive school-table" cellspacing="0">
            <thead class="d-block">
                <tr class="d-flex">
                    @php
                        $div = $setting->multiple_roll == 1 ? 'col-3' : 'col-4';
                    @endphp
                    @if (moduleStatusCheck('University'))
                        <th class="col-2">@lang('university::un.session')</th>
                        <th class="col-3">@lang('university::un.faculty_department')</th>
                        <th class="col-3">@lang('university::un.semester(label)')</th>
                    @else
                        <th class="{{ $div }}">@lang('common.class')</th>
                        <th class="{{ $div }}">@lang('common.section')</th>
                    @endif
                    @if ($setting->multiple_roll == 1)
                        <th class="{{ $div }}">@lang('student.id_number')</th>
                    @endif
                    <th class="{{ $div }}">@lang('student.action')</th>
                </tr>
            </thead>
            <tbody class="d-block">
                @foreach ($student_detail->studentRecords as $record)
                    <tr class="d-flex">
                        @if (moduleStatusCheck('University'))
                            <td class="col-2">{{ $record->unSession->name }}</td>
                            <td class="col-3">
                                {{ $record->unFaculty->name . '(' . $record->unDepartment->name . ')' }}
                                @if ($record->is_default)
                                    <span class="badge fix-gr-bg">{{ __('common.default') }}</span>
                                @endif
                            </td>
                            <td class="col-3">{{ $record->unSemester->name . '(' . $record->unSemesterLabel->name . ')' }}
                            </td>
                        @else
                            <td class="{{ $div }}">
                                {{ $record->class->class_name }}
                                @if ($record->is_default)
                                    <span class="badge fix-gr-bg">{{ __('common.default') }}</span>
                                @endif
                            </td>
                            <td class="{{ $div }}">
                                {{ $record->section->section_name }}
                            </td>
                        @endif

                        @if ($setting->multiple_roll == 1)
                            <td class="{{ $div }}">{{ $record->roll_no }}</td>
                        @endif
                        <td class="{{ $div }}">
                            @if ($record->is_promote == 0)
                                <a class="primary-btn icon-only fix-gr-bg modalLink" data-modal-size="small-modal"
                                    title="@if (moduleStatusCheck('University')) @lang('university::un.assign_faculty_department')
                                    @else 
                                        @lang('student.assign_class') @endif"
                                    href="{{ route('student_assign_edit', [@$record->student_id, @$record->id]) }}"><span
                                        class="ti-pencil"></span></a>
                                <a href="#" class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                    data-target="#deleteRecord_{{ $record->id }}">
                                    <span class="ti-trash"></span>
                                </a>
                            @endif
                        </td>
                    </tr>
                    {{-- Record delete --}}
                    <div class="modal fade admin-query" id="deleteRecord_{{ $record->id }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('common.delete')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('student.record.delete') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="text-center">
                                            <h4>@lang('student.Are you sure you want to move the following record to the trash?')</h4>
                                        </div>

                                        <input type="checkbox" id="record{{ @$record->id }}"
                                            class="common-checkbox form-control{{ @$errors->has('record') ? ' is-invalid' : '' }}"
                                            name="type">
                                        <label
                                            for="record{{ @$record->id }}">{{ __('student.Skip the trash and permanently delete the record') }}</label>

                                        <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                                        <input type="hidden" name="record_id" value="{{ $record->id }}">
                                        <div class="mt-40 d-flex justify-content-between">
                                            <button type="button" class="primary-btn tr-bg"
                                                data-dismiss="modal">@lang('common.cancel')</button>
                                            <button type="submit"
                                                class="primary-btn fix-gr-bg">@lang('common.delete')</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
    </div>
    {{-- Record delete --}}
    {{-- edit record --}}
    @endforeach
    {{-- end edit record --}}
    </tbody>
    </table>
</div>
</div>
