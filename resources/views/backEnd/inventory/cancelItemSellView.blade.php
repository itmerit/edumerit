<div class="text-center">
    <h4>@lang('common.cancel_the_record')</h4>
</div>

<div class="mt-40 d-flex justify-content-between">
    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.none')</button>
    <a href="{{route('cancel-item-sell',$id)}}" class="text-light">
    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.yes')</button>
     </a>
</div>
