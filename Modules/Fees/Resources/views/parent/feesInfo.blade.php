@extends('backEnd.master')
    @section('title') 
        @lang('fees.fees_invoice')
    @endsection
@section('mainContent')
    @include('fees::_allFeesList',['role'=> 'parent'])
@endsection