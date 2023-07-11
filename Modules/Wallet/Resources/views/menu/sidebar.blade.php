{{-- Wallet Start --}}
@if(auth()->user()->role_id == 1)
    @if(userPermission('1109') && menuStatus(1109))
        <li data-position="{{menuPosition(1109)}}" class="sortable_li">
            <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="ti-wallet"></span>
                </div>
                <div class="nav_title">
                    <span> @lang('wallet::wallet.wallet_deposit')</span>
                   
                </div>
            </a>
            <ul class="list-unstyled" id="subMenuWallet">
                @if(userPermission(1110) && menuStatus(1110))
                    <li data-position="{{menuPosition(1110)}}">
                        <a href="{{route('wallet.pending-diposit')}}"> @lang('wallet::wallet.pending_deposit')</a>
                    </li>
                @endif
                @if(userPermission(1114) && menuStatus(1114))
                    <li data-position="{{menuPosition(1114)}}">
                        <a href="{{route('wallet.approve-diposit')}}"> @lang('wallet::wallet.approve_deposit')</a>
                    </li>
                @endif
                @if(userPermission(1116) && menuStatus(1116))
                    <li data-position="{{menuPosition(1116)}}">
                        <a href="{{route('wallet.reject-diposit')}}"> @lang('wallet::wallet.reject_deposit')</a>
                    </li>
                @endif
                @if(userPermission(1118) && menuStatus(1118))
                    <li data-position="{{menuPosition(1118)}}">
                        <a href="{{route('wallet.wallet-transaction')}}"> @lang('wallet::wallet.wallet_transaction')</a>
                    </li>
                @endif
                @if(userPermission(1119) && menuStatus(1119))
                    <li data-position="{{menuPosition(1119)}}">
                        <a href="{{route('wallet.wallet-refund-request')}}"> @lang('wallet::wallet.refund_request')</a>
                    </li>
                @endif
                @if(userPermission(1123) && menuStatus(1123))
                    <li data-position="{{menuPosition(1123)}}">
                        <a href="{{route('wallet.wallet-report')}}"> @lang('wallet::wallet.wallet_report')</a>
                    </li>
                @endif
            </ul>
        </li>
    @endif
@elseif(auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
    @if(userPermission('wallet.my-wallet') && menuStatus('wallet.my-wallet') || userPermission('	
wallet.my-wallet') && menuStatus('	
wallet.my-wallet'))
        <li data-position="
        @if(userPermission('wallet.my-wallet'))
            {{menuPosition('wallet.my-wallet')}}
        @elseif(userPermission('	
wallet.my-wallet'))
            {{menuPosition('wallet.my-wallet')}}
        @endif
        " class="sortable_li">
            <a href="{{route('wallet.my-wallet')}}">                
                <div class="nav_icon_small">
                    <span class="flaticon-wallet"></span>
                </div>
                <div class="nav_title">
                    @lang('wallet::wallet.my_wallet')
                </div>
            </a>
        </li>
    @endif
@endif
{{-- Wallet End --}}