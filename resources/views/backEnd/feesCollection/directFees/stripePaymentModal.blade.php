<script src="{{asset('public/backEnd/')}}/js/main.js"></script>

<style type="text/css">
    .hide{
        display: none;
    }
</style>
<div class="container-fluid">
    <form method="POST" class="" action="{{route('fees-payment-stripe-store')}}" id="subscription-payment" data-cc-on-file="false" data-stripe-publishable-key="{{ @$stripe_info->gateway_publisher_key }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" name="installment_id" value="{{$installment->id}}">
                <input type="hidden" name="student_id" value="{{$installment->student_id}}">
                <input type="hidden" name="amount" value="{{$installment->amount}}">
                <input type="hidden" name="record_id" value="{{$installment->record_id}}">
                <input type="hidden" name="payment_method" value="5">
                <div class="row mt-25">
                    <div class="col-lg-12">
                         <div class="row">                                              
                                    <div class="col-lg-6 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control has-content name_on_card"
                                                type="text" name="name_on_card" id="name_on_card" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('accounts.name_on_card')  <span class="text-danger"> *</span></label>
                                             
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control has-content card-number"
                                                type="text" name="card-number" id="card-number" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('accounts.card_number') <span class="text-danger"> *</span></label>
                                             
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">                                              
                                    <div class="col-lg-4 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control has-content card-cvc"
                                                type="text" name="card-cvc" id="card-cvc" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('accounts.cvc')  <span class="text-danger"> *</span></label>
                                             
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control has-content card-expiry-month"
                                                type="text" name="card-expiry-month" id="card-expiry-month" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('accounts.expiration_month')  <span class="text-danger"> *</span></label>
                                             
                                        </div>
                            
                                    </div>
                                    <div class="col-lg-4 mt-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control has-content card-expiry-year"
                                                type="text" name="card-expiry-year" id="card-expiry-year" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('accounts.expiration_year')  <span class="text-danger"> *</span></label>
                                             
                                        </div>                            
                                    </div>
                                     
                                </div>
                                <div class="row mt-20"> 
                                    <div class='primary_input'>
                                            <div class='col-md-12 error form-group hide'>
                                                <div class='alert-danger alert'>Please correct the errors and try
                                                    again.</div>
                                            </div>
                                        </div>
                                </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-40">
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                    <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save_information')</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">

$(function() {
    var $form = $("form#subscription-payment");
    $('form#subscription-payment').on('submit', function(e) {
            if (!$form.data('cc-on-file')) {

            e.preventDefault();

            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);

          }
      });

      function stripeResponseHandler(status, response) {
  
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();

                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
      
    });

    </script>
