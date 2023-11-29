    //subject for lesson plan 


    $(document).ready(function() {
        $("#select_class_lesson").on("change", function() {
            var url = $("#url").val();

            var i = 0;
            var formData = {
                class_id: $(this).val(),

            };
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/lesson/" + "lessonSubject",

                beforeSend: function() {
                    $('#select_subject_loader').addClass('pre_loader');
                    $('#select_subject_loader').removeClass('loader');
                },

                success: function(data) {

                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_subject").find("option").not(":first").remove();
                            $("#select_subject_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, subject) {
                                $("#select_subject").append(
                                    $("<option>", {
                                        value: subject.id,
                                        text: subject.subject_name,
                                    })
                                );

                                var type = subject.subject_type == "T" ? "Theory" : "Practical";

                                $("#select_subject_div ul").append(
                                    "<li data-value='" +
                                    subject.id +
                                    "' class='option'>" +
                                    subject.subject_name +
                                    " (" +
                                    type +
                                    ")" +
                                    "</li>"
                                );

                            });
                        } else {
                            $("#select_subject_div .current").html("SELECT SUBJECT *");
                            $("#select_subject").find("option").not(":first").remove();
                            $("#select_subject_div ul").find("li").not(":first").remove();


                        }

                    });
                },
                error: function(data) {},
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_subject_loader').removeClass('pre_loader');
                        $('#select_subject_loader').addClass('loader');
                    }
                }
            });
        });
    });
    //section for lesson plan
    $(document).ready(function() {
        $("#select_class_lesson_section").on("change", function() {
            var formData = {
                id: $(this).val(),
            };

            var url = $("#url").val();

            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/lesson/" + "lessonSection",
                success: function(data) {

                    $("#multiple-section").empty();

                    var appendRow = "";

                    appendRow += "<div class='col-lg-12'>";
                    appendRow += "<label>Select Section *</label>";
                    $.each(data, function(i, item) {
                        console.log(item);
                        $.each(item, function(i, value) {
                            appendRow += "<div class='input-effect'>";
                            appendRow +=
                                "<input type='checkbox' id='section_" +
                                value.id +
                                "' class='common-checkbox subject-checkbox' onChange='changeSubject(" + value.id + ")'  name='section_ids[]' value='" +
                                value.id +
                                "'>";
                            appendRow +=
                                "<label for='section_" +
                                value.id +
                                "'>" +
                                value.section_name +
                                "</label>";
                            appendRow += "</div>";

                        });
                    });

                    appendRow += "<div class='col-lg-12'>";
                    $("#multiple-section").append(appendRow);
                },
                error: function(data) {},
            });
        });
    });