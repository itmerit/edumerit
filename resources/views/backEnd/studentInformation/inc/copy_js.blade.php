<form action="https://demo.smart-school.in/student/savemulticlass" method="POST" class="update">
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-body panelheight">

                <input type="hidden" value="42" name="student_id">
                <input type="hidden" value="2" name="nxt_row" class="nxt_row">
                <div class="row">
                    <div class="text-center">

                        <div class="col-xs-12 col-xs-offset-0 col-sm-3 col-sm-offset-9">
                            <button type="button" class="btn btn-default btn-sm pull-right addrow addrow-mb2010">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="append_row pluscolmn">

                    <div class="row">
                        <input type="hidden" name="row_count[]" value="1">
                        <div class="col-sm-5 col-lg-5 col-md-4">
                            <div class="form-group">
                                <label for="email">Class</label>
                                <select name="class_id_1" class="form-control class_id">
                                    <option value="">Select</option>
                                    <option value="1" selected="selected">Class 1</option>
                                    <option value="2">Class 2</option>
                                    <option value="3">Class 3</option>
                                    <option value="4">Class 4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5 col-lg-5 col-md-4">
                            <label for="email">Section</label>
                            <div class="form-group">
                                <select name="section_id_1" class="form-control section_id">
                                    <option value="">Select</option>
                                    <option value='1' selected='selected'>A</option>
                                    <option value='2'>B</option>
                                    <option value='3'>C</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-sm-2 col-lg-2 col-md-4">
                            <div class="form-group"><label for="email" style="opacity: 0;">Action</label>
                                <button class="btn btn-sm btn-danger rmv_row" type="button">Remove</button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="panel-footer panel-fo">
                <div class="row text-center">

                    <div class="col-xs-12 col-xs-offset-0 col-sm-3 col-sm-offset-9">
                        <button type="submit" class="btn btn-default btn-sm pull-right"
                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating...">
                            Update </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    // this is the id of the form

    $(document).on('submit', '.update', function(e) {
        var submit_btn = $(this).find("button[type=submit]");
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            dataType: "json",
            beforeSend: function() {
                submit_btn.button('loading');
            },
            success: function(data) {

                if (data.status == 1) {

                    successMsg(data.message);
                } else {
                    errorMsg(data.message);
                }
                submit_btn.button('reset');
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");

            },
            complete: function() {
                submit_btn.button('reset');
            }
        });


    });
</script>
<script type="text/javascript">
    $(document).on('click', '.rmv_row', function(e) {
        $(this).closest("div.row").remove();
    });

    var class_id = '1';
    var section_id = '1';
    getSectionByClass(class_id, section_id);
    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    $(document).on('change', '.class_id', function(e) {
        var class_id = $(this).val();

        var target_dropdown = $(this).closest("div.row").find('select.section_id');
        target_dropdown.html("");
        var div_data = '<option value="">Select</option>';
        $.ajax({
            type: "GET",
            url: baseurl + "sections/getByClass",
            data: {
                'class_id': class_id
            },
            dataType: "json",
            beforeSend: function() {
                target_dropdown.html("").addClass('dropdownloading');
            },
            success: function(data) {
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.section_id + ">" + obj.section +
                        "</option>";
                });
                target_dropdown.append(div_data);
            },
            complete: function() {
                target_dropdown.removeClass('dropdownloading');
            }
        });
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != 0 && class_id !== "") {
            $('#section_id').html("");
            var div_data = '<option value="">Select</option>';
            $.ajax({
                type: "GET",
                url: baseurl + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj
                            .section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function() {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('click', '.addrow', function() {
        var container = $(this).closest(".panel-body").find('.append_row');
        var nxt_row = $(this).closest(".panel-body").find('.nxt_row').val();
        var new_class_dropdown = $('#class_dropdown').html().replace("class_id", "class_id_" + nxt_row);
        var new_section_dropdown = $('#section_dropdown').html().replace("section_id", "section_id_" + nxt_row);
        var $newDiv = $('<div>').addClass('row').append(
            $('<input>', {
                type: 'hidden',
                name: 'row_count[]',
                val: parseInt(nxt_row)
            })).append(
            $('<div>').addClass('col-sm-5 col-lg-5 col-md-4').append($('<div>').addClass('form-group')
                .append($('<label>').html('Class')).append(new_class_dropdown))
        ).append(
            $('<div>').addClass('col-sm-5 col-lg-5 col-md-4').append($('<div>').addClass('form-group')
                .append($('<label>').html('Section')).append(new_section_dropdown))
        ).append(
            $('<div>').addClass('col-sm-2 col-lg-2 col-md-4').append($('<div>').addClass('form-group')
                .append($('<label>', {
                    css: {
                        'opacity': 0
                    }
                }).html('Action')).append(


                    $('<button>').html('Remove').addClass('btn btn-sm btn-danger rmv_row')
                )));

        $(this).closest(".panel-body").find('.nxt_row').val(parseInt(nxt_row) + 1);
        $newDiv.appendTo(container);

    });
</script>
