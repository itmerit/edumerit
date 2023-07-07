@extends('backEnd.master')
@section('title') 
        @lang('academics.admin_section')
@endsection

@section('mainContent')
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3>@if(isset($section)) 
                                    @lang('common.edit')
                                @else 
                                    @lang('common.add')
                                @endif 
                                @lang('common.section')
                            </h3>
                        </div>
                        @if(isset($section))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'section_update', 'method' => 'POST']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'section_store', 'method' => 'POST']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row  mt-25">
                                    <div class="col-lg-12">
                                       
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($section)? $section->section_name: ''}}">
                                            <input type="hidden" name="id" value="{{isset($section)? $section->id: ''}}">
                                            <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('name'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @lang('academics.save_content')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3>@lang('common.section_list')</h3>
                        </div>
                    </div>

                    <div class="offset-lg-4 col-md-4 d-flex justify-content-end">
                        <select class="primary_select tr-bg mr-10">
                            <option data-display="Column View">@lang('academics.column_view')</option>
                            <option value="Name">@lang('common.name')</option>
                            <option value="Position">@lang('common.phone')</option>
                            <option value="Source">@lang('academics.source')</option>
                            <option value="Query Date">@lang('academics.source')</option>
                            <option value="Last Follow Up Date">@lang('academics.last_follow_up_date')</option>
                            <option value="Next Follow Up Date">@lang('academics.next_follow_up_date')</option>
                            <option value="Status">@lang('common.status')</option>
                            <option value="Action">@lang('common.action')</option>
                            <option value="Restore Visibility">@lang('academics.restore_visibility')</option>
                        </select>
                        <select class="primary_select tr-bg">
                            <option data-display="Actions">@lang('common.action')</option>
                            <option value="1">@lang('common.print')</option>
                            <option value="2">@lang('academics.export_to_csv')</option>
                            <option value="3">@lang('academics.export_to_excel')</option>
                            <option value="4">@lang('academics.export_to_pdf')</option>
                            <option value="5">@lang('academics.copy_table')</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                               @if(session()->has('message-success-delete') != " " ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="3">
                                         @if(session()->has('message-success-delete'))
                                          <div class="alert alert-success">
                                              {{ session()->get('message-success-delete') }}
                                          </div>
                                        @elseif(session()->has('message-danger-delete'))
                                          <div class="alert alert-danger">
                                              {{ session()->get('message-danger-delete') }}
                                          </div>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>@lang('common.class')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($sections as $section)
                                <tr>
                                    <td>{{@$section->section_name}}</td>
                                    <td>
                                        <x-drop-down/>
                                                <a class="dropdown-item" href="{{route('section_edit', [@$section->id])}}">@lang('common.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteSectionModal{{@$section->id}}"  href="#">@lang('common.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                 <div class="modal fade" id="deleteSectionModal{{@$section->id}}" role="dialog">
                                    <div class="modal-dialog">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content modalContent">
                                        <div class="modal-body removeBtn">
                                          <p>@lang('common.are_you_sure_to_delete')?</p>
                                        </div>
                                        <div class="modal-footer compareFooter deleteButtonDiv">
                                            <button type="button" class="modalbtn btn-primary"><a href="{{route('section_delete', [@$section->id])}}" class="text-light">@lang('academics.yes')</a></button>
                                            <button type="button" class="modalbtn btn-danger" data-dismiss="modal">@lang('academics.no')</button>
                                        </div>
                                      </div>
                                      
                                    </div>
                                  </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')