<div class="text-center">
    <h4>@lang('library.return_this_book')</h4>
</div>

<div class="mt-40 d-flex justify-content-between">
    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.none')</button>
    <a href="{{route('return-book',@$issue_book_id)}}" class="text-light">
    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.yes')</button>
     </a>
</div>
