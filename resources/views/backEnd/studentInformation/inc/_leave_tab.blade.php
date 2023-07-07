<div role="tabpanel" class="tab-pane fade" id="leaves">
    <div class="white-box">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="nowrap">@lang('leave.leave_type')</th>
                                <th class="nowrap">@lang('leave.leave_from') </th>
                                <th class="nowrap">@lang('leave.leave_to')</th>
                                <th class="nowrap">@lang('leave.apply_date')</th>
                                <th class="nowrap">@lang('common.status')</th>
                                <th class="nowrap">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $diff = ''; @endphp
                            @isset($student_detail)
                                @if (count($student_detail->studentLeave) > 0)
                                    @foreach ($student_detail->studentLeave as $value)
                                        <tr>
                                            <td class="nowrap">{{ @$value->leaveType->type }}</td>
                                            <td class="nowrap">
                                                {{ $value->leave_from != '' ? dateConvert($value->leave_from) : '' }}</td>
                                            <td class="nowrap">
                                                {{ $value->leave_to != '' ? dateConvert($value->leave_to) : '' }}</td>
                                            <td class="nowrap">
                                                {{ $value->apply_date != '' ? dateConvert($value->apply_date) : '' }}</td>
                                            <td class="nowrap">
                                                @if ($value->approve_status == 'P')
                                                    <button class="primary-btn small bg-warning text-white border-0">
                                                        @lang('student.pending')</button>
                                                @endif

                                                @if ($value->approve_status == 'A')
                                                    <button class="primary-btn small bg-success text-white border-0">
                                                        @lang('student.approved')</button>
                                                @endif

                                                @if ($value->approve_status == 'C')
                                                    <button class="primary-btn small bg-danger text-white border-0">
                                                        @lang('common.cancelled')</button>
                                                @endif
                                            </td>
                                            <td class="nowrap">
                                                <a class="modalLink" data-modal-size="modal-md"
                                                    title="@lang('student.view') @lang('student.leave') @lang('student.details')"
                                                    href="{{ url('view-leave-details-apply', $value->id) }}"><button
                                                        class="primary-btn small tr-bg"> @lang('student.view') </button></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>@lang('student.not_leaves_data')</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
