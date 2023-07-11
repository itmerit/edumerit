// Add Wallet Student Panel
    $(document).ready(function () {
        $("#addWalletPaymentMethod").on("change", function() {
            let paymentValue = $(this).val();
            if( paymentValue== "Bank"){
                $('.AddWalletChequeBank').removeClass('d-none');
                $('.addWalletBank').removeClass('d-none');
                $('.addWallet').removeClass('d-none');
                $('.stripeInfo').addClass('d-none');
                $('.razorPay').addClass('d-none');
                $('.generalPay').removeClass('d-none');
                $('.mercadoPagoPayment').addClass('d-none');
            }else if(paymentValue == "Cheque"){
                $('.AddWalletChequeBank').removeClass('d-none');
                $('.addWalletBank').addClass('d-none');
                $('.addWallet').removeClass('d-none');
                $('.stripeInfo').addClass('d-none');
                $('.razorPay').addClass('d-none');
                $('.generalPay').removeClass('d-none');
                $('.mercadoPagoPayment').addClass('d-none');
            }else if(paymentValue == "Stripe"){
                $('.AddWalletChequeBank').addClass('d-none');
                $('.addWalletBank').addClass('d-none');
                $('.stripeInfo').removeClass('d-none');
                $('.razorPay').addClass('d-none');
                $('.generalPay').removeClass('d-none');
                $('.mercadoPagoPayment').addClass('d-none');
            }else if(paymentValue == "RazorPay"){
                $('.AddWalletChequeBank').addClass('d-none');
                $('.addWalletBank').addClass('d-none');
                $('.stripeInfo').addClass('d-none');
                $('.mercadoPagoPayment').addClass('d-none');
            }else if(paymentValue == "MercadoPago"){
                $('.mercadoPagoPayment').removeClass('d-none');
                $('.AddWalletChequeBank').addClass('d-none');
                $('.addWalletBank').addClass('d-none');
                $('.stripeInfo').addClass('d-none');
                $('.razorPay').addClass('d-none');
            }else{
                $('.AddWalletChequeBank').addClass('d-none');
                $('.addWalletBank').addClass('d-none');
                $('.stripeInfo').addClass('d-none');
                $('.razorPay').addClass('d-none');
                $('.generalPay').removeClass('d-none');
                $('.mercadoPagoPayment').addClass('d-none');
            }
        });
    });

// Add Wallet Amount
    let form = $('#addWalletAmount');
    form.on('submit', function(e) {
        if($("#addWalletPaymentMethod").val() == 'RazorPay'){
            return;
        }
        e.preventDefault();
        $('.addWallet').attr("disabled","disabled");
        const submit_url = form.attr('action');
        const method = form.attr('method');
    //Start Ajax
    const formData = new FormData(form[0]);
        $.ajax({
            url: submit_url,
            type: method,
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            dataType: 'JSON',
            success: function(response) {
                toastr.success("Save Successfully","Successful", { timeOut: 5000,});
                if(response.goto){
                   window.location.href=response.goto;
                }else{
                    location.reload();
                }
            },
            error:function (xhr){
                $('#walletAmountError').html(xhr.responseJSON.errors.amount);
                $('#paymentMethodError').html(xhr.responseJSON.errors.payment_method);
                $('#bankError').html(xhr.responseJSON.errors.bank);
                $('#fileError').html(xhr.responseJSON.errors.file);
                $('.addWallet').prop("disabled", false);
            }
        });
    });

// Refund Amount
    let refundForm = $('#refundAmount');
    refundForm.on('submit', function(e) {
        e.preventDefault();
        const submit_url = refundForm.attr('action');
        const method = refundForm.attr('method');
    //Start Ajax
    const formData = new FormData(refundForm[0]);
        $.ajax({
            url: submit_url,
            type: method,
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            dataType: 'JSON',
            success: function(response) {
                toastr.success("Submit Successfully","Successful", { timeOut: 5000,});
                location.reload();
            },
            error:function (xhr){
                $('#refundNoteError').html(xhr.responseJSON.errors.refund_note);
                $('#refundFileError').html(xhr.responseJSON.errors.refund_file);
                $('#existsError').html(xhr.responseJSON.errors.exist);
            }
        });
    });

// Input File Name Show
    $(document).ready(function(){
        $(document).on('change','.cutom-photo',function(){
            let v = $(this).val();
            let v1 = $(this).data("id");
            console.log(v,v1);
            getFileName(v, v1);
        });

        function getFileName(value, placeholder){
            if (value) {
                var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
                var filename = value.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                $(placeholder).attr('placeholder', '');
                $(placeholder).attr('placeholder', filename);
            }
        }
    })