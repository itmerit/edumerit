@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.wallet_reject_request')
    @endsection
@section('mainContent')
    @include('wallet::_walletRequest',['status'=>'reject'])
@endsection