@extends('backEnd.master')
@section('title')
    @lang('academics.class_routine')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Class Routine </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('academics.class_routine')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="mt-20">
        <div class="container-fluid p-0">
            <div class="row">
                @include('backEnd.studentPanel._class_routine_content', ['sm_weekends' => $sm_weekends, 'records' => $records, 'routineDashboard' => $routineDashboard = false])
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
