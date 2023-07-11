@extends('backEnd.master')
    @section('title') 
            @lang('fees::feesModule.add_fees_payment')
    @endsection
@section('mainContent')
    @include('fees::_addFeesPayment',['role'=>'student'])
@endsection