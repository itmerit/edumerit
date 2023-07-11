@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.my_wallet')
    @endsection
    @push('css')
    <style>
        .input-right-icon button {
            top: 8px !important;
            right: 12px !important;
        }
    </style>
    @endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('wallet::wallet.wallet_details')</h1>
            <div class="bc-pages">
                <a href="{{route('parent-dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('wallet::wallet.my_wallet')</a>
                <a href="#">@lang('wallet::wallet.wallet_details')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor mt-20">
    <div class="container-fluid p-0">
        @include('wallet::_addWallet')
    </div>
</section>
@endsection

@push('script')
        @if(moduleStatusCheck('RazorPay') == TRUE)
            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

            <script>
                var payment = false;
                function demoSuccessHandler(transaction) {
                    payment = true;
                    $('form#addWalletAmount').submit();
                    $('#addWalletPayment').modal('hide');
                }
            </script>

        @endif
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var paymentValue= '';
    $("#addWalletPaymentMethod").on("change", function() {
        paymentValue = $(this).val();
    });
$(function() {
        var $form = $("form#addWalletAmount");
        var publisherKey = '{!!@$stripe_info->gateway_publisher_key !!}';
        var ccFalse= false;
        $(document).on('submit', 'form#addWalletAmount', function(e) {

            if(paymentValue == "Stripe"){
                if (!ccFalse){
                    e.preventDefault();
                    Stripe.setPublishableKey(publisherKey);
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }
            }
            @if(moduleStatusCheck('RazorPay'))
            if (paymentValue == 'RazorPay') {
                if (!payment) {
                    e.preventDefault();
                    let value = parseFloat($('#walletAmount').val());
                    if (isNaN(value)) {
                        value = 0;
                    }
                    value = value * 100;
                    if (value > 0) {
                        var options = {
                            key: "{{ @$razorpay_info->gateway_secret_key }}",
                            amount: value,
                            name: 'Add Wallet Balance',
                            image: 'https://i.imgur.com/n5tjHFD.png',
                            handler: demoSuccessHandler
                        }

                        window.r = new Razorpay(options);
                        r.open();
                    } else {
                        toastr.error('Please make some payment');
                    }
                }
            }
            @endif
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
@endpush