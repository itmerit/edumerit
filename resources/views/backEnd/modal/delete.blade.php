
 <div class="text-center">
   <h4>@lang('common.are_you_sure_to_delete')</h4>
 </div>
 
 <div class="mt-40 d-flex justify-content-between">
     <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
      <a href="{{ @$url }}" class="text-light">
       @if (!@$url)
       <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('front_settings.back')</button>
       @else
       <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
       @endif  
      </a>
 </div>
 