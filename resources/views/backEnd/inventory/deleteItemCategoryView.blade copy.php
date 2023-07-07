<p>@lang('common.are_you_sure_to_delete')</p>
<div class="modal-footer compareFooter deleteButtonDiv">
    <button type="button" class="modalbtn btn-primary"><a href="{{route('delete-supplier',$id)}}"
            class="text-light">@lang('common.yes')</a></button>
    <button type="button" class="modalbtn btn-danger" data-dismiss="modal">@lang('common.none')</button>
</div>