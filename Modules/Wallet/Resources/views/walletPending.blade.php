@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.wallet_pending_request')
    @endsection
@section('mainContent')
    @include('wallet::_walletRequest',['status'=>'pending'])
@endsection