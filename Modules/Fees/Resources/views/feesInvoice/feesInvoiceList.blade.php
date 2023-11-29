@extends('backEnd.master')
    @section('title') 
        @lang('fees::feesModule.fees_invoice')
    @endsection
@section('mainContent')
    @include('fees::_allFeesList',['role'=>'admin'])
@endsection
