<x-drop-down>
    @if ((isset($role) && $role == 'admin') || $role == 'lms')
        @if (userPermission('fees.fees-view-payment'))
            <a class="dropdown-item" onclick="viewPaymentDetailModal({{ $row->id }})">@lang('inventory.view_payment')</a>
        @endif
        @if ($balance == 0)
            @if (userPermission('fees.fees-invoice-view'))
                <a class="dropdown-item"
                    href="{{ route('fees.fees-invoice-view', ['id' => $row->id, 'state' => 'view']) }}">@lang('common.view')</a>
            @endif
        @else
            @if ($paid_amount > 0)
                @if (userPermission('fees.fees-invoice-view'))
                    <a class="dropdown-item"
                        href="{{ route('fees.fees-invoice-view', ['id' => $row->id, 'state' => 'view']) }}">@lang('common.view')</a>
                @endif
                @if (userPermission('fees.add-fees-payment'))
                    <a class="dropdown-item"
                        href="{{ route('fees.add-fees-payment', $row->id) }}">@lang('inventory.add_payment')</a>
                @endif
            @else
                @if (userPermission('fees.fees-invoice-view'))
                    <a class="dropdown-item"
                        href="{{ route('fees.fees-invoice-view', ['id' => $row->id, 'state' => 'view']) }}">@lang('common.view')</a>
                @endif
                @if (userPermission('fees.add-fees-payment'))
                    <a class="dropdown-item"
                        href="{{ route('fees.add-fees-payment', $row->id) }}">@lang('inventory.add_payment')</a>
                @endif

                @if (userPermission('fees.fees-invoice-edit'))
                    <a class="dropdown-item"
                        href="{{ route('fees.fees-invoice-edit', $row->id) }}">@lang('common.edit')</a>
                @endif

                @if (userPermission('fees.fees-invoice-delete'))
                    <a class="dropdown-item" onclick="feesInvoiceDelete({{ $row->id }})" data-toggle="modal"
                        data-target="#deleteFeesPayment{{ $row->id }}"
                        href="#">@lang('common.delete')</a>
                @endif
            @endif
        @endif
    @endif
</x-drop-down>
