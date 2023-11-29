<div role="tabpanel" class="tab-pane fade" id="studentFees">

    @foreach ($records as $record)
        <div class="white-box no-search no-paginate no-table-info mb-2">
            <div class="row">
                <div class="col-lg-3">
                    <div class="main-title">
                        <h3 class="mb-10">
                            @if (moduleStatusCheck('University'))
                                {{ $record->semesterLabel->name }} ({{ $record->unSection->section_name }}) -
                                {{ @$record->unAcademic->name }}
                            @else
                                {{ $record->class->class_name }} ({{ $record->section->section_name }})
                            @endif
                        </h3>
                    </div>
                </div>
                <div class="col-lg-6 mb-10">
                    <table class="table school-table-style res_scrol school-table-up-style"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('fees.fees_type')</th>
                                <th>@lang('fees.assigned_date')</th>
                                <th>@lang('fees.amount')</th>
                            </tr>
                        </thead>
                        @php $gt_fees = 0; @endphp
                        <tbody>
                            @foreach ($record->fees as $assign_fees)
                                @php $gt_fees += @$assign_fees->feesGroupMaster->amount; @endphp
                                <tr>
                                    <td>{{ @$assign_fees->feesGroupMaster->feesTypes->name }}</td>
                                    <td>{{ dateConvert($assign_fees->created_at) }}</td>
                                    <td>
                                        {{ currency_format(@$assign_fees->feesGroupMaster->amount) }}</td>
                                </tr>
                            @endforeach

                        <tfoot>
                            <tr>
                                <th>@lang('fees.grand_total') ({{ generalSetting()->currency_symbol }})</th>
                                <th></th>
                                <th>{{ currency_format($gt_fees) }}</th>
                            </tr>
                        </tfoot>

                        </tbody>
                    </table>
                </div>

                <div class="col-lg-3 mb-10">
                    @if (moduleStatusCheck('University'))
                        <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-lg"
                            title="@lang('fees.add_fees')"
                            href="{{ route('university.un-total-fees-modal', [$record->id]) }}"> <i
                                class="ti-plus pr-2"> </i> @lang('fees.add_fees') </a>
                    @elseif(directFees())
                        <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-lg"
                            title="@lang('fees.add_fees')" href="{{ route('direct-fees-total-payment', [$record->id]) }}">
                            <i class="ti-plus pr-2"> </i> @lang('fees.add_fees') </a>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                @if (moduleStatusCheck('University'))
                    @includeIf('university::include.studentFeesTableView')
                @elseif(directFees())
                    @includeIf('backEnd.feesCollection.directFees.studentDirectFeesTableView')
                @else
                    <table class="table school-table-style res_scrol" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('fees.fees_group')</th>
                                <th>@lang('fees.fees_code')</th>
                                <th>@lang('fees.due_date')</th>
                                <th>@lang('fees.Status')</th>
                                <th>@lang('fees.amount') ({{ @$currency }})</th>
                                <th>@lang('fees.payment_ID')</th>
                                <th>@lang('fees.mode')</th>
                                <th>@lang('common.date')</th>
                                <th>@lang('fees.discount') ({{ @$currency }})</th>
                                <th>@lang('fees.fine') ({{ @$currency }})</th>
                                <th>@lang('fees.paid') ({{ @$currency }})</th>
                                <th>@lang('fees.balance') ({{ @$currency }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                @$grand_total = 0;
                                @$total_fine = 0;
                                @$total_discount = 0;
                                @$total_paid = 0;
                                @$total_grand_paid = 0;
                                @$total_balance = 0;
                            @endphp
                            @foreach ($record->fees as $fees_assigned)
                                @if ($fees_assigned->record_id == $record->id)
                                    @php
                                        @$grand_total += @$fees_assigned->feesGroupMaster->amount;
                                    @endphp
                                    @php
                                        @$discount_amount = $fees_assigned->applied_discount;
                                        @$total_discount += @$discount_amount;
                                        @$student_id = @$fees_assigned->student_id;
                                    @endphp
                                    @php
                                        @$paid = App\SmFeesAssign::discountSum(@$fees_assigned->student_id, @$fees_assigned->feesGroupMaster->feesTypes->id, 'amount', $fees_assigned->record_id);
                                        @$total_grand_paid += @$paid;
                                    @endphp
                                    @php
                                        @$fine = App\SmFeesAssign::discountSum(@$fees_assigned->student_id, @$fees_assigned->feesGroupMaster->feesTypes->id, 'fine', $fees_assigned->record_id);
                                        @$total_fine += @$fine;
                                    @endphp

                                    @php
                                        @$total_paid = @$discount_amount + @$paid;
                                    @endphp
                                    <tr>
                                        <td>{{ @$fees_assigned->feesGroupMaster->feesGroups != '' ? @$fees_assigned->feesGroupMaster->feesGroups->name : '' }}
                                        </td>
                                        <td>{{ @$fees_assigned->feesGroupMaster->feesTypes != '' ? @$fees_assigned->feesGroupMaster->feesTypes->name : '' }}
                                        </td>
                                        <td>
                                            @if (!empty(@$fees_assigned->feesGroupMaster))
                                                {{ @$fees_assigned->feesGroupMaster->date != '' ? dateConvert(@$fees_assigned->feesGroupMaster->date) : '' }}
                                            @endif
                                        </td>
                                        @php
                                            $total_payable_amount = $fees_assigned->fees_amount;
                                            $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                            $balance_amount = number_format($rest_amount + $fine, 2, '.', '');
                                            $total_balance += $balance_amount;
                                        @endphp
                                        <td>
                                            @if ($balance_amount == 0)
                                                <button
                                                    class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                                            @elseif($paid != 0)
                                                <button
                                                    class="primary-btn small bg-warning text-white border-0">@lang('fees.partial')</button>
                                            @elseif($paid == 0)
                                                <button
                                                    class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                echo number_format($fees_assigned->feesGroupMaster->amount, 2, '.', '');
                                            @endphp
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td> {{ @$discount_amount }} </td>
                                        <td>{{ @$fine }}</td>
                                        <td>{{ @$paid }}</td>
                                        <td>
                                            @php echo @$balance_amount; @endphp
                                        </td>
                                    </tr>
                                    @php
                                        @$payments = App\SmFeesAssign::feesPayment(@$fees_assigned->feesGroupMaster->feesTypes->id, @$fees_assigned->student_id, $fees_assigned->record_id);
                                        $i = 0;
                                    @endphp
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"><img
                                                    src="{{ asset('public/backEnd/img/table-arrow.png') }}"></td>
                                            <td>
                                                @php
                                                    @$created_by = App\User::find(@$payment->created_by);
                                                @endphp
                                                @if (@$created_by != '')
                                                    <a href="#" data-toggle="tooltip" data-placement="right"
                                                        title="{{ 'Collected By: ' . @$created_by->full_name }}">{{ @$payment->fees_type_id . '/' . @$payment->id }}</a>
                                            </td>
                                    @endif
                                    <td>{{ $payment->payment_mode }}</td>
                                    <td class="nowrap">
                                        {{ @$payment->payment_date != '' ? dateConvert(@$payment->payment_date) : '' }}
                                    </td>
                                    <td>{{ @$payment->discount_amount }}</td>
                                    <td>
                                        {{ $payment->fine }}
                                        @if ($payment->fine != 0)
                                            @if (strlen($payment->fine_title) > 14)
                                                <span class="text-danger nowrap" title="{{ $payment->fine_title }}">
                                                    ({{ substr($payment->fine_title, 0, 15) . '...' }})
                                                </span>
                                            @else
                                                @if ($payment->fine_title == '')
                                                    {{ $payment->fine_title }}
                                                @else
                                                    <span class="text-danger nowrap">
                                                        ({{ $payment->fine_title }})
                                                    </span>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ @$payment->amount }}</td>
                                    <td></td>
                                    </tr>
                                @endforeach
                            @endif
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>@lang('fees.grand_total') ({{ @$currency }})</th>
                        <th></th>
                        <th>{{ @$grand_total }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{ @$total_discount }}</th>
                        <th>{{ @$total_fine }}</th>
                        <th>{{ @$total_grand_paid }}</th>
                        <th>{{ number_format($total_balance, 2, '.', '') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
                </table>
    @endif
</div>
</div>
@endforeach
</div>
