$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
window.paymentType = $('#paymentStatus').val();
window.paymentMethodValue = $('#paymentMethodName').val();
window.paymentValue = $('#paymentMethodAddFees').val();
    $(document).ready(function() {
        // Select Class Wise Student
            $("#selectClass").on("change", function() {
                let url = $("#url").val();
                let i = 0;
                let classId = $(this).val();
                let editValue = $('.editValue').val();
                $.ajax({
                    method: "get",
                    dataType: "json",
                    url: url + "/fees/" + "select-student",
                    data: {
                        classId: classId,
                    },

                    beforeSend: function() {
                        $('#selectStudentLoader').addClass('pre_loader');
                        $('#selectStudentLoader').removeClass('loader');
                    },

                    success: function(data) {
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#selectStudent").find("option").not(":first").remove();
                                $("#selectStudentDiv ul").find("li").not(":first").remove();
                                if(editValue == null){
                                    $("#selectStudent").append(
                                        $("<option>", {
                                            value: 'all_student',
                                            text: "All Student",
                                        })
                                    );
                                }

                                $.each(item, function(i, allStudent) {
                                    let rollno = allStudent.roll_no;
                                    if(rollno == null){
                                        rollno = '';
                                    }
                                    let section = allStudent.section.section_name;
                                    if(allStudent.student_detail.full_name){
                                        $("#selectStudent").append(
                                            $("<option>", {
                                                value: allStudent.id,
                                                text: allStudent.student_detail.full_name +" "+"("+section+ " - " +rollno+")",
                                            })
                                        );
                                    }
                                });
                                $("#selectStudent").niceSelect('update');
                            } else {
                                $("#selectStudent").find("option").not(":first").remove();
                                $("#selectStudentDiv ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {},
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#selectStudentLoader').removeClass('pre_loader');
                            $('#selectStudentLoader').addClass('loader');
                        }
                    }
                });
            });

        // Select Payment Status
            $("#paymentStatus").on("change", function() {
                window.paymentType = $(this).val();
                paymentStatus(paymentType);
            });
            paymentStatus(paymentType);

        // Select Bank Payment
            $("#paymentMethodName").on("change", function() {
                window.paymentMethodValue = $(this).val();
                paymentMethod(paymentMethodValue);
            });
            paymentMethod(paymentMethodValue);

        //Select Fees Type and Validation
            $(document).on('change', '#selectFeesType', function () {
                let type = $(this).val();
                if (type) {
                    let tr = $(".allFeesTypes").parent().parent();
                    let a = tr.find('.fees');
                    if (a.length === 0) {
                        getFeesType(type);
                    } else {
                        let found = true;
                        $(".fees").each(function () {
                            if ($(this).val() === type) {
                                found = false;
                                toastr.warning("This Fees Group / Type id Already Exists","Warning", {timeOut: 5000,});
                            }
                        })
                        if (found) {
                            getFeesType(type);
                        }
                    }
                }
            });

        // Get Value Form Input Field
            $(document).on('keyup', '.amount', function()
            {
                let paymentStatus = $('#paymentStatus').val();

                if($('#cloneAmount').is(':checked')){
                    let subTotal = parseFloat($(this).val());
                    if(isNaN(subTotal)){
                        subTotal= '';
                    }else{
                        subTotal= subTotal.toFixed(2);
                    }
                    $('.amount').val($(this).val());
                    $('.inputSubTotal').val(subTotal);
                    $('.subTotal').text(subTotal);
                }
                weaverCalculation(this);
                paidAmountCalculation(this);
                if(paymentStatus == 'full'){
                    fullPaid($(this));
                }
                //Total
                    amount();
                    weaver();
                    subTotal();
                    paidAmount();

            });

            $(document).on('keyup', '.weaver', function()
            {
               
                weaverCalculation(this);
               
                //Total
                    amount();
                    weaver();
                    subTotal();
                    
                    let paymentStatus = $('#paymentStatus').val();
                    if(paymentStatus == 'full'){
                        fullPaid($(this));
                    }
                    paidAmountCalculation(this);
                    paidAmount();
            });

            $(document).on('keyup', '.paidAmount', function()
            {
                weaverCalculation(this);
                paidAmountCalculation(this);
                //Total
                    amount();
                    weaver();
                    subTotal();
                    paidAmount();
            });

            $(document).on('click', '#deleteField', function()
            {
                $(this).parent().parent().remove();
                weaverCalculation();
                paidAmountCalculation(this);
                //Total
                    amount();
                    weaver();
                    subTotal();
                    paidAmount();
            });

        // Add Fees Payment
            $(document).on('change', '#paymentMethodAddFees', function(){
                window.paymentValue = $(this).val();
                addFeesPaymentMethod(paymentValue);
            });
            addFeesPaymentMethod(paymentValue);

        //Add Fees Calculation
            $(document).on('keyup', '.addFeesPaidAmount', function(){
                let gateway = $('#paymentMethodAddFees').val();
                if (gateway == '') {
                    toastr.error('Payment Method Select First');
                    
                    $('.addFeesPaidAmount').each(function(i,e){
                        $(this).val('');
                    });
                    return ;
                }
                addFeesCalculation(this);
                totalShowPaidAmount();
            });

            $(document).on('keyup', '.addFeesFine', function(){
                addFeesCalculation(this);
            });

            $(document).on('keyup', '.addFeesWeaver', function(){
                addFeesCalculation(this);
            });

            $(document).on('blur', '.addFeesWeaver', function(){
                validWeaver(this);
            });

            $(document).on('keyup', '.previousWeaver', function(){
                addFeesCalculation(this);
                validWeaver(this);
            });

            $(document).on('keyup', '.showValue', function(){
                addFeesCalculation(this);
            });

        // Fees Invoice Settings
            if($('.selectValue').length)
            {
                $(".selectValue").on("change", function() {
                    let postionValue = $(this).select2('data');
                    selectPosition(postionValue);
                });
                selectPosition($('.selectValue').select2('data'));
            }

        // Fees Invoice Settings Store
            $("#invSetting").on("click", function(event) {
                event.preventDefault();
                let url = $("#url").val();
                let id = $("#ID").val();
                let uniqIdStart = $('#uniq_id_start').val();
                let prefix = $('#prefix').val();
                let classLimit = $('#class_limit').val();
                let sectionLimit = $('#section_limit').val();
                let admissionLimit = $('#admission_limit').val();
                //let weaver = $('#weaver').val();
                let invoicePositionValues = $('.selectValue').select2('data');
                let invoicePositions = [];
                $.each(invoicePositionValues, function(i, invoicePositionValue) {
                    invoicePositions[i] = new Object({
                        id : invoicePositionValue.id,
                        text : invoicePositionValue.text
                    });
                });

                if((invoicePositionValues == '') || (uniqIdStart == '') || (weaver == '')){
                    toastr.warning("Required Field Can Not be Blank","Warning", {timeOut: 5000,});
                }else{
                    $.ajax({
                        method: "post",
                        dataType: "json",
                        url: url + "/fees/" + "fees-invoice-settings-update",
                        data: {
                            id: id,
                            invoicePositions: invoicePositions,
                            uniqIdStart: uniqIdStart,
                            prefix: prefix,
                            classLimit: classLimit,
                            sectionLimit: sectionLimit,
                            admissionLimit: admissionLimit,
                            weaver: weaver,
                        },
                        success: function(data) {
                            toastr.success("Update Successfully","Success", {timeOut: 5000,});
                        },
                        error: function(data) {
                            toastr.error("Update Failed","Error", {timeOut: 5000,});
                        },
                    });
                }
            });

    });

    function fullPaid($this){
        let tr = $this.parents('tr');
        let amount = tr.find('.subTotal').text();
        tr.find('.paidAmount').val(amount);
    }

// Function Part
        function paymentStatus(paymentType)
        {
            if((paymentType == "not") || (paymentType === '')){
                $('#paymentMethod').addClass('d-none');
                $('.paidAmount').attr('disabled', 'disabled');
                $('#bankPayment').addClass('d-none');
                $('.paidAmount').val('');
                amount();
                weaver();
                subTotal();
                paidAmount();
            }else if(paymentType === "full"){
                $('#paymentMethod').removeClass('d-none');
                $('.paidAmount').removeAttr('disabled');
                $('.paidAmount').prop('readonly', true);
                amount();
                weaver();
                subTotal();
                paidAmount();
                $.each($('.amount'), function(){
                    fullPaid($(this));
                });
            }else{
                $('#paymentMethod').removeClass('d-none');
                $('.paidAmount').removeAttr('disabled');
                amount();
                weaver();
                subTotal();
            }
        }

        function paymentMethod(paymentMethodValue){
            if(paymentMethodValue == "Bank"){
                $('#bankPayment').removeClass('d-none');
            }else{
                $('#bankPayment').addClass('d-none');
            }
        }

    //Append Fees Type
        function getFeesType(type)
        {
            let url = $("#url").val();
            let editData = $("#newFeesEditId").val();
            $.ajax({
                url: url + "/fees/" + "select-fees-type",
                method: "POST",
                data: {
                    type: type,
                    editData: editData,
                },
                success: function (response) {
                    let feeType = type.slice(0,3);
                    if(feeType == "grp"){
                        $(".allFeesTypes").html(response);
                    }else{
                        $(".allFeesTypes").append(response);
                    }
                    if((paymentType == "not") || (paymentType === '')){
                        $('.paidAmount').attr('disabled', true);
                    }else{
                        $('.paidAmount').attr('disabled', false);
                    }
                }
            })
        }

    //Create Invoice Calculation

        function amount()
        {
            let showTotalAmount = 0;
                $('.amount').each(function(i,e){
                    let amount= $(this).val()-0;
                    showTotalAmount+=amount;
                });
            $('.showTotalAmount').html(showTotalAmount.toFixed(2));
        }

        function weaver()
        {
            let showTotalWeaver = 0;
                $('.weaver').each(function(i,e){
                    let weaver= $(this).val()-0;
                    showTotalWeaver+=weaver;
                });
            $('.showTotalWeaver').html(showTotalWeaver.toFixed(2));
        }

        function subTotal()
        {
            let showSubTotalDiscount = 0;
                $('.inputSubTotal').each(function(i,e){
                    let subTotal= $(this).val()-0;
                    showSubTotalDiscount += subTotal;
                });
            $('.showSubTotalDiscount').html(showSubTotalDiscount.toFixed(2));
        }

        function paidAmount()
        {
            let showPaidAmount = 0;
                $('.paidAmount').each(function(i,e){
                    let paidAmount= $(this).val()*1;
                    showPaidAmount+=paidAmount;
                });
                console.log(showPaidAmount);
            $('.showPaidAmount').html(showPaidAmount.toFixed(2));
            $('.totalPaidAmount').val(showPaidAmount);
        }

        function weaverCalculation(el)
        {
            let weaverType = $('.weaverType').val();
            let weaver = parseFloat($(el).closest('tr').find($('.weaver')).val()).toFixed(2);
            let amount = parseFloat($(el).closest('tr').find($('.amount')).val());

            if(amount > 0)
            {
                if(weaver > 0){
                    if (weaverType == "amount") {
                        if(amount >= weaver){
                            let totalPayment = amount - weaver;
                            subTotalAmount(el,totalPayment);
                            $(".fmInvoice").attr("disabled", false);
                        }else{
                            toastr.warning("Weaver is less than Amount","Warning", {timeOut: 5000,});
                            subTotalAmount(el,amount);
                            $(el).closest('tr').find($('.weaver')).val(0);
                            $(".fmInvoice").attr("disabled", true);
                        }
                    } else {
                        if(weaver < 101){
                            weaverValue = (amount * weaver) / 100;
                            weaverAmount = amount - weaverValue;
                            subTotalAmount(el,weaverAmount);
                            $(".fmInvoice").attr("disabled", false);
                        }else{
                            toastr.warning("Weaver is not grater than 100","Warning", {timeOut: 5000,});
                            subTotalAmount(el,amount);
                            $(el).closest('tr').find($('.weaver')).val(0);
                            $(".fmInvoice").attr("disabled", true);
                        }
                    }
                }else{
                    subTotalAmount(el,amount);
                }
            }
        }

        function subTotalAmount(el,amount){
            $(el).closest('tr').find($('.subTotal')).html(amount.toFixed(2));
            $(el).closest('tr').find($('.inputSubTotal')).val(amount.toFixed(2));
        }

        function paidAmountCalculation(el)
        {
            if((paymentType == "partial") || (paymentType == 'full')){
                let subTotal = parseFloat($(el).closest('tr').find($('.inputSubTotal')).val());
                if(subTotal == ''){
                    toastr.warning("SubTotal Is Blank","Warning", {timeOut: 5000,});
                    $(el).closest('tr').find($('.paidAmount')).val(0);
                    $(".fmInvoice").attr("disabled", true);
                }else{
                    let paidAmountValue = parseFloat($(el).closest('tr').find($('.paidAmount')).val());
                    let subTotal = parseFloat($(el).closest('tr').find($('.inputSubTotal')).val());
                    if(paidAmountValue > 0){
                        if(subTotal < paidAmountValue){
                            toastr.warning("Paid Amount is not grater than Stub Total ","Warning", {timeOut: 5000,});
                            $(el).closest('tr').find($('.paidAmount')).val(0);
                            paidAmount();
                            $(".fmInvoice").attr("disabled", true);
                        }
                    }
                }
            }
        }

    // Add Fees Payment
        function addFeesPaymentMethod(paymentValue){
            if(paymentValue == "Bank"){
                $('#bankPaymentAddFees').removeClass('d-none');
                $('.chequeBank').removeClass('d-none');
                $('.stripPayment').addClass('d-none');
            }else if(paymentValue == "Cheque"){
                $('#bankPaymentAddFees').addClass('d-none');
                $('.chequeBank').removeClass('d-none');
                $('.stripPayment').addClass('d-none');
            }else if(paymentValue == "Stripe"){
                $('.stripPayment').removeClass('d-none');
                $('#bankPaymentAddFees').addClass('d-none');
                $('.chequeBank').addClass('d-none');
            }else{
                $('#bankPaymentAddFees').addClass('d-none');
                $('.chequeBank').addClass('d-none');
                $('.stripPayment').addClass('d-none');
            }
        }

    //Add Fees Calculation
        function validWeaver(el){
            let weaver = parseFloat($(el).closest('tr').find($('.addFeesWeaver')).val());
            let previousWeaver = parseFloat($(el).closest('tr').find($('.previousWeaver')).val());
            if(previousWeaver > weaver){
                parseFloat($(el).closest('tr').find($('.addFeesWeaver')).val(previousWeaver));
            }
        }   
        function addFeesCalculation(el){
            let weaverType = $('.weaverType').val();
            let showTotalValue = parseFloat($(el).closest('tr').find($('.showTotalValue')).val());
            let dueAmount = parseFloat($(el).closest('tr').find($('.dueAmount')).val());
            let addFeesPaidAmount = parseFloat($(el).closest('tr').find($('.addFeesPaidAmount')).val());
            let fine = parseFloat($(el).closest('tr').find($('.addFeesFine')).val());
            let weaver = parseFloat($(el).closest('tr').find($('.addFeesWeaver')).val());
            let previousWeaver = parseFloat($(el).closest('tr').find($('.previousWeaver')).val());
            let currencySymbol = $('#currencySymbol').val();

            let weaverAmount = 0;
            if(previousWeaver < weaver){
                weaverAmount = weaver - previousWeaver;
            }

            if(isNaN(fine)){
                fine = 0;
            }

            if(isNaN(addFeesPaidAmount)){
                addFeesPaidAmount = 0;
            }

            let total = (dueAmount + fine) - (addFeesPaidAmount + weaverAmount);
                if(dueAmount + fine >= addFeesPaidAmount){
                    $(el).closest('tr').find($('.showTotalValue')).val(total);
                    parseFloat($(el).closest('tr').find($('.extraAmount')).val(0));
                    extraAmountAdd(currencySymbol);
                }else{
                    $(el).closest('tr').find($('.showTotalValue')).val('0');
                    let addInWallet = addFeesPaidAmount - dueAmount - fine;
                    parseFloat($(el).closest('tr').find($('.extraAmount')).val(addInWallet));
                    extraAmountAdd(currencySymbol);
                }
        }

        function extraAmountAdd(currencySymbol)
        {
            let addInWallet = 0;
                $('.extraAmount').each(function(i,e){
                    let totalPaid= $(this).val()-0;
                    addInWallet += totalPaid;
                });

            $('.add_wallet').html(currencySymbol+addInWallet.toFixed(2));
            $('#addWallet').val(addInWallet.toFixed(2));
        }

        function totalShowPaidAmount()
        {
            let showStudentPaidAmount = 0;
                $('.addFeesPaidAmount').each(function(i,e){
                    let amount= $(this).val()-0;
                    showStudentPaidAmount+=amount;
                });
            $('.totalStudentPaidAmount').val(showStudentPaidAmount.toFixed(2));
        }

        function addFeesTotal(el){
            let paidAmount = parseFloat($(el).closest('tr').find($('.addFeesPaidAmount')).val());
            let fine = parseFloat($(el).closest('tr').find($('.addFeesFine')).val());
            let subTotal = parseFloat($(el).closest('tr').find($('.addFeesSubTotal')).val());

            let feesTotal = (subTotal + fine) - paidAmount;
            if(feesTotal){
                $(el).closest('tr').find($('.showValue')).removeClass('d-none');
                $(el).closest('tr').find($('.addFeesSubTotal')).addClass('d-none');
                $(el).closest('tr').find($('.showValue')).val(feesTotal);
            }else{
                $(el).closest('tr').find($('.showValue')).addClass('d-none');
                $(el).closest('tr').find($('.addFeesSubTotal')).removeClass('d-none');
            }
        }

        function fineValidation(el){
            let subTotal = parseFloat($(el).closest('tr').find($('.addFeesSubTotal')).val());
            let fine = parseFloat($(el).closest('tr').find($('.addFeesFine')).val());

            if(subTotal < fine){
                $(el).closest('tr').find($('.addFeesFine')).val(0);
            }
        }

    // Fees Invoice Settings
        function selectPosition(postionValue)
        {
            let showData = '';
            $('#showValue').empty();
            $.each(postionValue, function(i, item) {
                if(showData){
                    showData+= '-';
                }
                    showData += "<p>"+getInvoicePositionValue(item.id)+"</p>";
            });
            $('#showValue').append(showData);
        }

        function getInvoicePositionValue(id){
            let postionValueShow = $('#'+id).val();
            let defaultValue = new Object({
                uniq_id : '0011',
                class : 'One',
                section : 'A',
                prefix : 'infixEdu',
                admission_no : '123',
            })

            if (!postionValueShow){
                postionValueShow = defaultValue[id]
            }

            let limitValue = $('#'+id+'_limit').val();
            if(limitValue && postionValueShow.lenght > limitValue){
                toastr.error('too much')
                postionValueShow = defaultValue[id];

            }
            return postionValueShow;
        }

        $("#cloneAmount").change(function() {
            let cloneAmount = '';
            if(this.checked) {
                cloneAmount = $('.amount').val();
            }
            let newSubTotal = parseFloat(cloneAmount);
            if(isNaN(newSubTotal)){
                newSubTotal= '';
            }else{
                newSubTotal= newSubTotal.toFixed(2);
            }
            $('.amount').val(cloneAmount);
            $('.inputSubTotal').val(newSubTotal);
            $('.subTotal').text(newSubTotal);
            $('.weaver').val('');

            amount();
            subTotal();
            weaver();
        });


        $("#feesSelectClass").on("change", function() {
            var url = $("#classToSectionRoute").val();
            var i = 0;
            var formData = {
                class_id: $(this).val(),
            };
            
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url,

                beforeSend: function() {
                    $('#select_section_loader').addClass('pre_loader');
                    $('#select_section_loader').removeClass('loader');
                },

                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#feesSection").find("option").not(":first").remove();
                            $("#feesSectionDiv ul")
                                .find("li")
                                .not(":first")
                                .remove();

                            $.each(item, function(i, section) {
                                $("#feesSection").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#feesSectionDiv ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#feesSectionDiv .current").html(
                                "SELECT SECTION *"
                            );
                            $("#feesSection").find("option").not(":first").remove();
                            $("#feesSectionDiv ul")
                                .find("li")
                                .not(":first")
                                .remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_section_loader').removeClass('pre_loader');
                        $('#select_section_loader').addClass('loader');
                    }
                }
            });
        });

        $(document).ready(function() {
            $("#feesSection").on("change", function() {
                var url = $("#sectionToStudentRoute").val();
                var i = 0;
                var class_id = $("#feesSelectClass").val();
                var formData = {
                    section_id: $(this).val(),
                    class_id: class_id,
                };

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url,

                    beforeSend: function() {
                        $('#student_section_loader').addClass('pre_loader');
                        $('#student_section_loader').removeClass('loader');
                    },

                    success: function(data) {
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#selectStudent").find("option").not(":first").remove();
                                $("#selectStudentDiv ul").find("li").not(":first").remove();

                                $.each(item, function(i, allStudent) {
                                    let rollno = allStudent.roll_no;
                                    if(rollno == null){
                                        rollno = '';
                                    }
                                    let section = allStudent.section.section_name;
                                    if(allStudent.student_detail.full_name){
                                        $("#selectStudent").append(
                                            $("<option>", {
                                                value: allStudent.id,
                                                text: allStudent.student_detail.full_name +" "+"("+section+ " - " +rollno+")",
                                            })
                                        );
                                    }
                                });
                                $("#selectStudent").niceSelect('update');
                            } else {
                                $("#selectStudent").find("option").not(":first").remove();
                                $("#selectStudentDiv ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#student_section_loader').removeClass('pre_loader');
                            $('#student_section_loader').addClass('loader');
                        }
                    }
                });
            });
        });

        $(document).ready(function() {
            $("#feesSelectClass").on("change", function() {
                var url = $("#classToStudentRoute").val();
                var i = 0;
                var formData = {
                    class_id: $(this).val(),
                };

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url,

                    beforeSend: function() {
                        $('#student_section_loader').addClass('pre_loader');
                        $('#student_section_loader').removeClass('loader');
                    },

                    success: function(data) {
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#selectStudent").find("option").not(":first").remove();
                                $("#selectStudentDiv ul").find("li").not(":first").remove();

                                $.each(item, function(i, allStudent) {
                                    let rollno = allStudent.roll_no;
                                    if(rollno == null){
                                        rollno = '';
                                    }
                                    let section = allStudent.section.section_name;
                                    if(allStudent.student_detail.full_name){
                                        $("#selectStudent").append(
                                            $("<option>", {
                                                value: allStudent.id,
                                                text: allStudent.student_detail.full_name +" "+"("+section+ " - " +rollno+")",
                                            })
                                        );
                                    }
                                });
                                $("#selectStudent").niceSelect('update');
                            } else {
                                $("#selectStudent").find("option").not(":first").remove();
                                $("#selectStudentDiv ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#student_section_loader').removeClass('pre_loader');
                            $('#student_section_loader').addClass('loader');
                        }
                    }
                });
            });
        });
    // Totltip Active
        //$('[data-tooltip="tooltip"]').tooltip();


