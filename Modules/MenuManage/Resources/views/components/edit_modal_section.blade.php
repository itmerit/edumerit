@php
    $LanguageList = getLanguageList();
@endphp

<div class="modal-dialog modal-dialog-centered student-details">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{__('common.Edit')}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">

            <form action="#" method="POST" id="sectionEditForm">
                <input type="hidden" value="{{$section->id}}" name="id" id="">
                <div class="row pt-0">
                    @if(moduleStatusCheck('FrontendMultiLang') || moduleStatusCheck('Org'))
                        <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ml-3"
                            role="tablist">
                            @foreach ($LanguageList as $key => $language)
                                <li class="nav-item">
                                    <a class="nav-link  @if (auth()->user()->language_code == $language->code) active @endif"
                                       href="#element2{{$language->code}}"
                                       role="tab"
                                       data-toggle="tab">{{ $language->native }}  </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="tab-content">
                    @foreach ($LanguageList as $key => $language)
                        <div role="tabpanel"
                             class="tab-pane fade @if (auth()->user()->language_code == $language->code) show active @endif  "
                             id="element2{{$language->code}}">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label"
                                               for="">{{ __('common.Name') }}

                                            <span
                                                class="textdanger">*</span></label>
                                        <input class="primary_input_field" placeholder="" type="text" id=""
                                               name="name[{{$language->code}}]"
                                               value="{{$section->getTranslation('name',$language->code)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.Cancel')</button>

                    <button class="primary-btn fix-gr-bg" id="sectionUpdate"
                            type="button">@lang('common.Submit')</button>
                </div>
            </form>

        </div>
    </div>
</div>

