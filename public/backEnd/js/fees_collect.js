
    $(document).ready(function(){
        $("select[name='studentFind']").on('change',function () {
            var ca = $("select[name='studentFind']").val();
            if (ca != "") {
                for(var i=0;i<4;i++){
                $("#smFees").empty();
                $("#smFees").append(`<tr><td>${Math.floor(Math.random() * 10)}</td>
                <td>Spn${Math.floor(Math.random() * 10)}</td>
                <td>$${Math.floor(Math.random() * 10)}</td></tr>`)

                $("#DiscountFe").empty();
                $("#DiscountFe").append(`<tr><td>${Math.floor(Math.random() * 10)}</td>
                <td>Spn${Math.floor(Math.random() * 10)}</td>
                <td>$${Math.floor(Math.random() * 10)}</td></tr>`)

                $("#FineFees").empty();
                $("#FineFees").append(`<tr><td>${Math.floor(Math.random() * 10)}</td>
                <td>Spn${Math.floor(Math.random() * 10)}</td>
                <td>$${Math.floor(Math.random() * 10)}</td></tr>`)

                }
            }
            
        })    
    
    });

    
    $(document).ready(function () {
        var url = $('#url').val() + '/' + 'feescollection/fees-discount-type';        
        fetch(url)
        .then(response => response.json())
        .then(data => {           
            if (data.length) {
                data.forEach(item => {
                    //console.log(item);
                    $('#FessDiscountType').append($('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                    $("#FessDiscountType_div ul").append("<li data-value='" + item.id + "' class='option'>" + item.name + "</li>");
                });
            } 
        })
        .catch(error => console.error(error))
    })

    $(document).ready(function () {
        var url = $('#url').val() + '/' + 'feescollection/fees-fine-type';        
        fetch(url)
        .then(response => response.json())
        .then(data => {           
            if (data.length) {
                data.forEach(item => {
                    //console.log(item);
                    $('#FessFineType').append($('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                    $("#FessFineType_div ul").append("<li data-value='" + item.id + "' class='option'>" + item.name + "</li>");
                });
            }
        })
        .catch(error => console.error(error))
    })

    $(document).ready(function(){
        $("select[name='FessDiscountType']").on('change',function () {
            var ca = $("select[name='FessDiscountType']").val();
                if (ca != 'create_new') {
                    $(".createnewDiscount").hide();
                }else{
                    $(".createnewDiscount").show();

                }

        })
    })

    $(document).ready(function(){
        $("select[name='FessFineType']").on('change',function () {
            var ca = $("select[name='FessFineType']").val();
                if (ca != 'create_new') {
                    $(".createnewFine").hide();
                }else{
                    $(".createnewFine").show();

                }

        })
    })
    $(document).ready(function () {
        $("#_AddDiscount").on("click",function(){       
            var url = $('#url').val();
            var discount_name = $("input[name='discount_name'").val();
            var discount_amount = $("input[name='discount_amount'").val();
            var discount_type = $("select[name='FessDiscountType']").val();
            var fees_term = $("select[name='fees_term']").val();
            var student_id = $("select[name='studentFind']").val();
            var type = $("select[name='type']").val();
            if (discount_type != null && discount_type != 0 ) {
            var formData = {
                discount_name: discount_name,
                discount_amount: discount_amount,
                discount_type:discount_type,
                type : type,
                fees_term : fees_term,
                student_id : student_id,
            };
            $.ajax({
                type: "GET",
                data: formData,
                dataType: 'json',
                url: url + '/' + 'feescollection/add-discount-fees',
                success: function (data) {                    
                    $('#AddDiscount').modal('hide');
                    if (data['success'] == 'success') {
                        toastr.success('Operation success!')
                        $("#DiscountFe").append(`<tr class="deleteTr${data['fees'].id}"><td> ${ data['fees'].id} </td>
                        <td>${ data['fees'].name}</td>
                        <td><span class="mr-2">$${ data['fees'].amount}</span><i class="ti-trash click" onclick="deleteDiscount(${data['fees'].id})"></i></td></tr>`)
                    } else if(data['error'] == 'used') {
                        toastr.error('Already discount used!')
                    } else if(data['error'] == 'error') {
                        toastr.error('Operation faild!')
                    }  

                },
                error: function (data) {
                    toastr.error('Operation faild!')
                }
            });                
          }
        }) 
    })

    deleteDiscount = (val) =>{        
        var url = $('#url').val() + '/' + 'feescollection/fees-discount-delete/'+val;        
        fetch(url)
        .then(response => response.json())
        .then(data => {                     
            if (data['success'] == 'success') {
                toastr.success('Operation success!') 
                $(`.deleteTr${val}`).remove();
            } else if(data['error'] == 'not_fount') {
                toastr.error('Discount not found')
            } else if(data['error'] == 'error') {
                toastr.error('Operation faild!')
            }
        })
        .catch(error => console.log(error))
    }
    $(document).ready(function () {
        $("#_FineAdd").on("click",function(){       
            var url = $('#url').val();
            var fine_name = $("input[name='fine_name'").val();
            var days_after_due_date = $("input[name='days_after_due_date'").val();
            var fine_amount = $("input[name='fine_amount'").val();
            var fine_type = $("select[name='FessFineType']").val();
            var fees_term = $("select[name='fees_term']").val();
            var student_id = $("select[name='studentFind']").val();
            var type = $("select[name='fine_type']").val();
            if (fine_type != null && fine_type != 0 ) {
            var formData = {
                days_after_date: days_after_due_date,
                fine_name: fine_name,
                fine_amount: fine_amount,
                fine_type:fine_type,
                type : type,
                fees_term : fees_term,
                student_id : student_id,
            };
            $.ajax({
                type: "GET",
                data: formData,
                dataType: 'json',
                url: url + '/' + 'feescollection/add-fine-fees',
                success: function (data) {                                        
                    $('#AddFine').modal('hide');
                    if (data['success'] == 'success') {
                        toastr.success('Operation success!')
                        $("#FineFees").append(`<tr class="deleteTr${data['fees'].id}"><td> ${ data['fees'].id} </td>
                        <td>${ data['fees'].name}</td>
                        <td>$${ data['fees'].amount}  <i class="ti-trash click" onclick="deleteFine(${data['fees'].id})"></i></td></tr>`)
                    } else if(data['error'] == 'used') {
                        toastr.error('Already discount used!')
                    } else if(data['error'] == 'error') {
                        toastr.error('Operation faild!')
                    }  

                },
                error: function (data) {
                    toastr.error('Operation faild!')
                }
            });                
          }
        }) 
    })

    deleteFine = (val) =>{        
        var url = $('#url').val() + '/' + 'feescollection/fees-fine-delete/'+val;        
        fetch(url)
        .then(response => response.json())
        .then(data => {                     
            if (data['success'] == 'success') {
                toastr.success('Operation success!') 
                $(`.deleteTr${val}`).remove();
            } else if(data['error'] == 'not_fount') {
                toastr.error('Fine not found')
            } else if(data['error'] == 'error') {
                toastr.error('Operation faild!')
            }
        })
        .catch(error => console.log(error))
    }