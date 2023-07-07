@if (isset($feesGroups))
    @foreach ($feesGroups as $key=>$feesGroup)
        <tr>
            <td></td>
            <td>{{$feesGroup->name}} ({{$feesGroup->fessGroup->name}})</td>
            <input type="hidden" name="groups[{{$key}}][feesType]" value="{{$feesGroup->id}}">
            <input type="hidden" name="groupId" value="{{$feesGroup->fessGroup->id}}">
            <td>
                <div class="primary_input">
                    <input class="primary_input_field form-control amount{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="groups[{{$key}}][amount]" autocomplete="off" value="{{old('amount')}}">
                    
                    @if ($errors->has('amount'))
                    <span class="text-danger" >
                        {{ $errors->first('amount') }}
                    </span>
                    @endif
                </div>
            </td>
            <td>
                <div class="primary_input">
                    <input class="primary_input_field form-control weaver{{ $errors->has('weaver') ? ' is-invalid' : '' }}" type="text" name="groups[{{$key}}][weaver]" autocomplete="off" value="{{old('weaver')}}">
                    
                    @if ($errors->has('weaver'))
                    <span class="text-danger" >
                        {{ $errors->first('weaver') }}
                    </span>
                    @endif
                </div>
            </td>
            <td class="subTotal"></td>
            <input type="hidden" name="groups[{{$key}}][sub_total]" class="inputSubTotal">
            @if(!isset($editData))
                <td>
                    <input class="primary_input_field form-control paidAmount{{ $errors->has('paid_amount') ? ' is-invalid' : '' }}" type="text" name="groups[{{$key}}][paid_amount]" autocomplete="off" disabled>
                </td>
            @endif
            <td>
                <button class="primary-btn icon-only fix-gr-bg" data-toggle="modal" data-target="#addNotesModal{{$feesGroup->id}}" type="button"
                    data-tooltip="tooltip" data-placement="top" title="@lang('common.add_note')">
                    <span class="ti-pencil-alt"></span>
                </button>
                <button class="primary-btn icon-only fix-gr-bg" type="button" data-tooltip="tooltip" title="@lang('common.delete')" id="deleteField">
                    <span class="ti-trash"></span>
                </button>
                {{-- Notes Modal Start --}}
                <div class="modal fade admin-query" id="addNotesModal{{$feesGroup->id}}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('common.add_note')</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control has-content" type="text" name="groups[{{$key}}][note]" autocomplete="off">
                                    <label class="primary_input_label" for="">@lang('common.note')</label>
                                    
                                </div>
                                </br>
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                    <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">@lang('common.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Notes Modal End --}}
                <input type="hidden" class="fees" value="grp{{$feesGroup->fessGroup->id}}">
                <input type="hidden" class="fees" value="typ{{$feesGroup->id}}">
            </td>
        </tr>
    @endforeach
@endif

@if (isset($feesType))
    <tr>
        <td></td>
        <td>{{$feesType->name}}</td>
        <input type="hidden" name="types[{{$feesType->id}}][feesType]" value="{{$feesType->id}}">
        <td>
            <div class="primary_input">
                <input class="primary_input_field form-control amount{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="types[{{$feesType->id}}][amount]" autocomplete="off" value="{{old('amount')}}">
                
                @if ($errors->has('amount'))
                <span class="text-danger" >
                    {{ $errors->first('amount') }}
                </span>
                @endif
            </div>
        </td>
        <td>
            <div class="primary_input">
                <input class="primary_input_field form-control weaver{{ $errors->has('weaver') ? ' is-invalid' : '' }}" type="text" name="types[{{$feesType->id}}][weaver]" autocomplete="off" value="{{old('weaver')}}">
                
                @if ($errors->has('weaver'))
                <span class="text-danger" >
                    {{ $errors->first('weaver') }}
                </span>
                @endif
            </div>
        </td>
        <td class="subTotal"></td>
        <input type="hidden" name="types[{{$feesType->id}}][sub_total]" class="inputSubTotal">
        @if(!isset($editData))
            <td>
                <input class="primary_input_field form-control paidAmount{{ $errors->has('paid_amount') ? ' is-invalid' : '' }}" type="text" name="types[{{$feesType->id}}][paid_amount]" autocomplete="off" disabled>
            </td>
        @endif
        <td>
            <button class="primary-btn icon-only fix-gr-bg" data-toggle="modal" data-target="#addNotesModal{{$feesType->id}}" type="button"
                data-tooltip="tooltip" data-placement="top" title="@lang('common.add_note')">
                <span class="ti-pencil-alt"></span>
            </button>
            <button class="primary-btn icon-only fix-gr-bg" data-tooltip="tooltip" title="@lang('common.delete')" type="button" id="deleteField">
                <span class="ti-trash"></span>
            </button>
            {{-- Notes Modal Start --}}
            <div class="modal fade admin-query" id="addNotesModal{{$feesType->id}}">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">@lang('common.add_note')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <div class="primary_input">
                                <input class="primary_input_field form-control has-content" type="text" name="types[{{$feesType->id}}][note]" autocomplete="off">
                                <label class="primary_input_label" for="">@lang('common.note')</label>
                                
                            </div>
                            </br>
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">@lang('common.save')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Notes Modal End --}}
            <input type="hidden" class="fees" value="typ{{$feesType->id}}">
            <input type="hidden" class="fees" value="grp{{$feesType->fees_group_id}}">
        </td>
    </tr>
@endif
