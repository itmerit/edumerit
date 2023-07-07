@extends('backEnd.master')

@section('title') 
{{__('student.delete_student_record')}}
@endsection

@section('mainContent')
@php
    $breadCrumbs = [
        'h1'=>__('student.delete_student_record'),
        'bgPages'=>[
            '<a href="#">'.__('student.student_information').'</a>'
        ]
    ];
@endphp
<x-bread-crumb-component :breadCrumbs="$breadCrumbs" />
<section class="admin-visitor-area up_admin_visitor full_wide_table">
    <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                    <thead>
                                    
                                        <tr>
                                            <th>@lang('student.admission_no')</th>
                                            <th>@lang('student.roll_no')</th>
                                            <th>@lang('student.name')</th>
                                            <th>@lang('common.class_sec')</th>
                                            @if(generalSetting()->with_guardian)
                                            <th>@lang('student.father_name')</th>
                                            @endif
                                            <th>@lang('common.date_of_birth')</th>
                                        
                                            <th>@lang('common.phone')</th>
                                            <th>@lang('common.actions')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($studentRecords as $record)
                                        <tr>
                                            <td>{{$record->studentDetail->admission_no}}</td>
                                            <td>{{$record->roll_no}}</td>
                                            <td>{{$record->studentDetail->first_name.' '.$record->studentDetail->last_name}}</td>
                                            <td>{{$record->class !=""? $record->class->class_name:""}} {{ $record->section ? '('.$record->section->section_name.')' : '' }}</td>
                                            @if(generalSetting()->with_guardian)
                                            <td>{{$record->studentDetail->parents !=""?$record->studentDetail->parents->fathers_name:""}}</td>
                                            @endif 
                                            <td  data-sort="{{strtotime($record->studentDetail->date_of_birth)}}" >
                                            {{$record->studentDetail->date_of_birth != ""? dateConvert($record->studentDetail->date_of_birth):''}} 
                                            </td>
                                        
                                        
                                            <td>{{$record->studentDetail->mobile}}</td>
                                            <td>
                                                @php
                                                    $routeList = [
                                                        '<a class="dropdown-item" href="'.route('student-record-restore', [$record->id]).'"> <i class="fa-solid fa-rotate"></i>'.
                                                            __('common.restore').'</a>',
                                                    (userPermission('disable_student_delete')) ?
                                                        '<a onclick="deleteId('.$record->id.');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="'.$record->id.'">'.__('common.delete forever').'</a>' : null,
                                                    
                                                        ];
                                                @endphp
                                                <x-drop-down-action-component :routeList="$routeList" />
                                            </td>
                                        </tr>
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>

<div class="modal fade admin-query" id="deleteStudentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation Required</h4>
                {{-- <h4 class="modal-title">@lang('student.delete') @lang('student.student')</h4> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4 class="text-danger">You are going to remove {{@$record->first_name.' '.@$record->last_name}}. Removed data CANNOT be restored! Are you ABSOLUTELY Sure!</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'delete-student-record-permanently', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" value="" id="student_delete_i">  {{-- using js in main.js --}}
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>





@endsection
@include('backEnd.partials.data_table_js')
