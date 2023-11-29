<script src="{{asset('public/backEnd/')}}/js/main.js"></script>
 <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('hr.staff_list')</h3>
                    </div>
                </div>
            </div>

         <div class="row">
                <div class="col-lg-12">
                    <table id="table_id" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('hr.staff_no').</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('hr.role')</th>
                                <th>@lang('hr.departments')</th>
                                <th>@lang('hr.designation')</th>
                                <th>@lang('common.mobile')</th>
                                <th>@lang('common.email')</th>
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($staffs as $value)
                            <tr>
                                <td>{{$value->staff_no}}</td>
                                <td>{{$value->first_name}}&nbsp;{{$value->last_name}}</td>
                                <td>{{$value->roles!=""?$value->roles->name:""}}</td>
                                <td>{{$value->departments!=""?$value->departments->name:""}}</td>
                                <td>{{$value->designations!=""?$value->designations->title:""}}</td>
                                <td>{{$value->mobile}}</td>
                                <td>{{$value->email}}</td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            @lang('common.edit')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('viewStaff', $value->id)}}">@lang('common.view')</a>
                                            <a class="dropdown-item" href="{{route('editStaff', $value->id)}}">@lang('common.edit')</a>
                                            <a class="dropdown-item" href="#">@lang('common.delete')</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('backEnd.partials.data_table_js')