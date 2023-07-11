
@if(userPermission(1130) && menuStatus(1130))
    <li data-position="{{menuPosition(1130)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="fas fa-money"></span>
            </div>
            <div class="nav_title">
                <span>@lang('fees.fees')</span>
                
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuFees">
            @if(userPermission(1131) && menuStatus(1131))
                <li data-position="{{menuPosition(1131)}}">
                    <a href="{{ route('fees.fees-group') }}">@lang('fees.fees_group')</a>
                </li>
            @endif

            @if(userPermission(1135) && menuStatus(1135))
                <li data-position="{{menuPosition(1135)}}">
                    <a href="{{ route('fees.fees-type') }}">@lang('fees.fees_type')</a>
                </li>
            @endif

            @if(userPermission(1139) && menuStatus(1139))
                <li data-position="{{menuPosition(1139)}}">
                    <a href="{{ route('fees.fees-invoice-list') }}">@lang('fees::feesModule.fees_invoice')</a>
                </li>
            @endif

            @if(userPermission(1148) && menuStatus(1148))
                <li data-position="{{menuPosition(1148)}}">
                    <a href="{{ route('fees.bank-payment') }}">@lang('fees.bank_payment')</a>
                </li>
            @endif

            @if(userPermission(1152) && menuStatus(1152))
                <li data-position="{{menuPosition(1152)}}">
                    <a href="{{ route('fees.fees-invoice-settings') }}">@lang('fees::feesModule.fees_invoice_settings')</a>
                </li>
            @endif

            <li>
                <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                    @lang('reports.report')
                </a>
                <ul class="list-unstyled">
                    @if(userPermission(1155) && menuStatus(1155))
                        <li data-position="{{menuPosition(1155)}}">
                            <a href="{{ route('fees.due-fees') }}">
                                @lang('fees::feesModule.fees_due_report')
                            </a>
                        </li>
                    @endif
                    @if(userPermission(1158) && menuStatus(1158))
                        <li data-position="{{menuPosition(1158)}}">
                            <a href="{{ route('fees.fine-report')}}">
                                @lang('accounts.fine_report')
                            </a>
                        </li>
                    @endif
                    @if(userPermission(1159) && menuStatus(1159))
                        <li data-position="{{menuPosition(1159)}}">
                            <a href="{{ route('fees.payment-report')}}">
                                @lang('fees::feesModule.payment_report')
                            </a>
                        </li>
                    @endif
                    @if(userPermission(1160) && menuStatus(1160))
                        <li data-position="{{menuPosition(1160)}}">
                            <a href="{{ route('fees.balance-report')}}">
                                @lang('fees::feesModule.balance_report')
                            </a>
                        </li>
                    @endif
                    @if(userPermission(1161) && menuStatus(1161))
                        <li data-position="{{menuPosition(1161)}}">
                            <a href="{{ route('fees.waiver-report')}}">
                                @lang('fees::feesModule.waiver_report')
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </li>
@endif