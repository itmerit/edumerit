

<div class="modal-dialog modal-dialog-centered student-details">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{__('common.Edit')}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">

            <form action="#" method="POST" id="menuEditForm">
                <input type="hidden" value="{{$menu->id}}" name="id" id="">
                
            
             
                       
                <div class="row">
                    <div class="col-xl-12">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label"
                                    for="">{{ __('common.Label') }}

                                <span
                                    class="textdanger">*</span></label>
                            <input class="primary_input_field" placeholder="" type="text" id=""
                                    name="label"
                                    value="">
                        </div>
                    </div>
                </div>
                     
               
                <div class="row">
                    <div class="col-xl-12">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label"
                                   for="">{{__('common.Icon')}}

                                <span
                                    class="textdanger">*</span>
                            </label>
                            <input class="primary_input_field" placeholder="" type="text" id="menuIcon"
                                   name="icon"
                                   value="{{$menu->icon}}">
                        </div>
                    </div>
                </div>


                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.Cancel')</button>

                    <button class="primary-btn fix-gr-bg" id="menuUpdate"
                            type="button">@lang('common.Submit')</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        $(document).on('mouseover', 'body', function () {
            $('#menuIcon').iconpicker({
                animation: true,
                hideOnSelect: true
            });
        });
    </script>
</div>

