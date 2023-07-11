<div class="" id="viewPayrollPayment">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="text-center d-none" id="deletePayment">@lang('common.are_you_sure_to_delete') <button class="delete primary-btn fix-gr-bg">@lang('common.delete')</button></h4>
                <x-table>
                   
                    <div class="table-responsive">
                    <table id="" class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll">
                                    <label for="checkAll">@lang('common.select_all')</label>
                                </th>
                                <th>@lang('hr.staff_no')</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('hr.role')</th>
                                <th>@lang('accounts.amount')</th>
                                <th>@lang('accounts.payment_method')</th>
                                <th>@lang('hr.payment_date')</th>
                                <th>@lang('common.created_by')</th>                                         
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payrollPayments as $payment)
                                <tr id="payment_{{ $payment->id }}">
                                    <td>
                                        <input data-id="{{ @$payment->id}}" type="checkbox" id="payment_payroll_id{{ @$payment->id}}" class="common-checkbox payment_payroll"  value="{{ @$payment->id}}">
                                        <label for="payment_payroll_id{{@$payment->id}}"></label>
                                    </td>
                                    <td>
                                    <a href="{{ route('viewStaff', $generatePayroll->staffDetails->id) }}" target="_blank" rel="noopener noreferrer">{{ $generatePayroll->staffDetails->staff_no }}</a>
                                    </td>
                                    <td>{{ $generatePayroll->staffDetails->full_name }}</td>
                                    <td>{{ $generatePayroll->staffDetails->roles->name }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ $payment->paymentMethod->method }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ $payment->createdBy->full_name }} </td>
                                    <td>
                                        <x-drop-down>                                         
                                            <a class="dropdown-item" target="_blank"
                                            href="{{ route('print-payroll-payment', $payment->id)}}">@lang('common.print')</a>
                                        </x-drop-down>
                                    </td>
                                </tr>
                               
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </x-table>
            </div>
        </div>
    </div>
</div>
<script>
    $("#checkAll").on("click", function() {
        $("input:checkbox").prop("checked", this.checked);
        $('#deletePayment').removeClass('d-none');
       
    });

    $("input:checkbox").on("click", function() {
        if (!$(this).is(":checked")) {
            $("#checkAll").prop("checked", false);
        }
        var numberOfChecked = $("input:checkbox:checked").length;
        var totalCheckboxes = $("input:checkbox").length;
        var totalCheckboxes = totalCheckboxes - 1;

        if (numberOfChecked == totalCheckboxes) {
            $("#checkAll").prop("checked", true);
        }
        if(numberOfChecked > 0){
            $('#deletePayment').removeClass('d-none');
        }else{
            $('#deletePayment').addClass('d-none');
        }
    });
   
    $(document).on('click', '.delete', function(){
        var ids = [];
        $("input[type=checkbox]").each(function() {
            if (this.checked && $(this).val() !='on') {                
                ids.push($(this).val())
            }
        });
        if(ids.length ==0) {
            toastr.warning('Please Selecte At least One', 'Warning');
            return;
        }
       $.ajax({
            type:'POST',
            data:{ids:ids},
            dataType:"json",
            url:"{{ route('delete-payroll-payment') }}",
            success:function(data){
                toastr.success(data.msg);
                jQuery.each(ids, function(i, val){
                    $('#payment_'+val).remove();
                });
                $('#deletePayment').removeClass('d-none');
            },
            error:function(){

            }
       })
    })
    $(document).on('click', '.cancel', function(){
        // $('input:checkbox').removeAttr('checked');
        $('input:checkbox').attr('checked',false);
        $('#deletePayment').addClass('d-none');
    });
</script>