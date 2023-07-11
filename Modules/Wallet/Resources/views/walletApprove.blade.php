@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.wallet_approve_request')
    @endsection
@section('mainContent')
    @include('wallet::_walletRequest',['status'=>'approve'])
@endsection