(function($) {
    function ajax_error(data) {
        "use strict";
        if (data.status === 404) {
            toastr.error("What you are looking is not found", 'Opps!');
            return;
        } else if (data.status === 500) {
            toastr.error('Something went wrong. If you are seeing this message multiple times, please contact Spondon It author.', 'Opps');
            return;
        } else if (data.status === 200) {
            toastr.error('Something is not right', 'Error');
            return;
        }
        let jsonValue = $.parseJSON(data.responseText);
        let errors = jsonValue.errors;
        if (errors) {
            let i = 0;
            $.each(errors, function(key, value) {
                let first_item = Object.keys(errors)[i];
                let error_el_id = $('#' + first_item);
                if (error_el_id.length > 0) {
                    error_el_id.parsley().addError('ajax', {
                        message: value,
                        updateClass: true
                    });
                }
                toastr.error(value, 'Validation Error');
                i++;
            });
        } else {
            toastr.error(jsonValue.message, 'Opps!');
        }
    }

    function jsUcfirst(string) {
        "use strict";
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
            beforeSend: function(){
                // $(".prelaoder_wrapper")
                //     .fadeOut("slow", function () {
                //         $(this).show();
                //     });
            },
            complete: function(){
                // $(".prelaoder_wrapper")
                //     .fadeOut("slow", function () {
                //         $(this).hide();
                //     });
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).ready(function() {
        $(".isDisabled").on("click", function(e) {
            e.preventDefault();
        });

        var review = $(".active-testimonial");
        if (review.length) {
            review.owlCarousel({
                items: 1,
                loop: false,
                margin: 10,
                loop: true,
                autoplay: true,
                smartSpeed: 500,
                navText: [
                    `Prev <img src='public/backEnd/img/prev.png'>`,
                    `Next<img src='public/backEnd/img/next.png'>`,
                ],
            });
        }
    });



    $(document).ready(function() {
        // alert('yes');
        var default_postion = $('#default_position').val();
        document.getElementsByTagName("html")[0].style.visibility = "visible";
        if (default_postion != 0) {
            $("#sidebar_menu .sortable_li").sort(sort_li).appendTo('#sidebar_menu');
            var elements = $("#sidebar_menu").find("ul");



            $.each(elements, function(index, item) {
                let id_name = $(this).attr('id');
                $("#" + id_name + " > li").sort((a, b) => $(a).data("position") - $(b).data("position")).appendTo("#" + id_name);

            });


            function sort_li(a, b) {
                return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1;
            }
        }
    });





    $(document).ready(function() {
        if ($("input#total-attendance").length > 0) {
            var total_attendance = $("input#total-attendance").val();

            var attendanceArray = total_attendance.split("-");

            $("#total_present").html(attendanceArray[0]);
            $("#total_absent").html(attendanceArray[1]);
            $("#total_late").html(attendanceArray[2]);
            $("#total_halfday").html(attendanceArray[3]);
            $("#total_holiday").html(attendanceArray[4]);
        }
    });

    // student section info for student admission
    $(document).ready(function() {
        $("#all_classes").on("change", function() {
            $(".class-checkbox").prop("checked", this.checked);
        });

        $(".class-checkbox").on("change", function() {
            if ($(".class-checkbox:checked").length == $(".class-checkbox").length) {
                $("#all_classes").prop("checked", true);
            } else {
                $("#all_classes").prop("checked", false);
            }
        });
    });

    $(document).ready(function() {
        $("#all_sections").on("change", function() {
            $(".section-checkbox").prop("checked", this.checked);
        });

        $(".section-checkbox").on("change", function() {
            if (
                $(".section-checkbox:checked").length == $(".section-checkbox").length
            ) {
                $("#all_sections").prop("checked", true);
            } else {
                $("#all_sections").prop("checked", false);
            }
        });
    });

    $(document).ready(function() {
        $("#all_subjects").on("change", function() {
            $(".subject-checkbox").prop("checked", this.checked);
        });

        $(".subject-checkbox").on("change", function() {
            if (
                $(".subject-checkbox:checked").length == $(".subject-checkbox").length
            ) {
                $("#all_subjects").prop("checked", true);
            } else {
                $("#all_subjects").prop("checked", false);
            }
        });
    });

    $(document).ready(function() {
        $("#all_exams").on("change", function() {
            $(".exam-checkbox").prop("checked", this.checked);
        });

        $(".exam-checkbox").on("change", function() {
            if ($(".exam-checkbox:checked").length == $(".exam-checkbox").length) {
                $("#all_exams").prop("checked", true);
            } else {
                $("#all_exams").prop("checked", false);
            }
        });
    });

    // student section info for student admission
    $(document).ready(function() {
        $("#classSelectStudent").on("change", function() {
            var url = $("#url").val();
            var i = 0;

          var class_id = $(this).val();

          $("#sectionSelectStudent").empty().append(
            $("<option>", {
                value:  '',
                text: window.jsLang('select_section') + ' *',
            })
        );

        if (!class_id){
            $("#sectionSelectStudent").trigger('change').niceSelect('update');
            return;
        }
            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSectionStudent",

                beforeSend: function() {
                    $('#select_section_loader').addClass('pre_loader');
                    $('#select_section_loader').removeClass('loader');
                },
                success: function(data) {


                    $.each(data, function(i, item) {
                       
                        if (item.length) {
                            $.each(item, function(i, section) {
                                $("#sectionSelectStudent").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );
                                
                            });
                        } 
                    });

                    $("#sectionSelectStudent").trigger('change').niceSelect('update')

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
    });


    // subject from class

    $(document).ready(function() {
        $("#classSelectStudentHomeWork").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            var globalType = $("#globalType").val();
            console.log(globalType);

            var formData = {
                id: $(this).val(),
                globalType: globalType
            };
            console.log(formData);
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSubjectFromClass",

                beforeSend: function() {
                    $('#select_subject_loader').addClass('pre_loader');
                    $('#select_subject_loader').removeClass('loader');
                },
                success: function(data) {

                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#subjectSelect").find("option").not(":first").remove();
                            $("#subjectSelecttHomeworkDiv ul").find("li").not(":first").remove();

                            $.each(item, function(i, subject) {
                                $("#subjectSelect").append(
                                    $("<option>", {
                                        value: subject.id,
                                        text: subject.subject_name,
                                    })
                                );
                                $("#subjectSelecttHomeworkDiv ul").append(
                                    "<li data-value='" +
                                    subject.id +
                                    "' class='option'>" +
                                    subject.subject_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#subjectSelecttHomeworkDiv .current").html("SUBJECT *");
                            $("#subjectSelect").find("option").not(":first").remove();
                            $("#subjectSelecttHomeworkDiv ul").find("li").not(":first").remove();
                        }
                    });
                    console.log(a);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
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

    // student section info for student admission
    $(document).ready(function() {
        $("#classSelectStudent1").on("change", function() {
            var url = $("#url").val();
            console.log(url);

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSectionStudent",
                success: function(data) {
                    // console.log('ttttt');
                    var a = "";
                    // $.loading.onAjax({img:'loading.gif'});
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#sectionSelectStudent1").find("option").not(":first").remove();
                            $("#sectionStudentDiv1 ul").find("li").not(":first").remove();

                            $("#sectionSelectStudent").append(
                                $("<option>", {
                                    value: "all",
                                    text: "All Sections",
                                })
                            );

                            $("#sectionStudentDiv1 ul").append(
                                "<li data-value='" +
                                "all" +
                                "' class='option'>" +
                                "All Section" +
                                "</li>"
                            );

                            $.each(item, function(i, section) {
                                $("#sectionSelectStudent1").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#sectionStudentDiv1 ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#sectionStudentDiv1 .current").html("SECTION *");
                            $("#sectionSelectStudent1").find("option").not(":first").remove();
                            $("#sectionStudentDiv1 ul").find("li").not(":first").remove();
                        }
                    });
                    console.log(a);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });


    // currency info
    $(document).ready(function() {
        $("#currency").on("change", function() {
            var url = $("#url").val();

            var formData = {
                id: $(this).val(),
            };

            // console.log(formData);
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSelectCurrency",
                success: function(data) {
                    var symbol = data[0].symbol;
                    $("#currency_symbol").val(symbol);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // student admission get vehicle driver info
    $(document).ready(function() {
        $("#selectVehicle").on("change", function() {
            var url = $("#url").val();

            if ($(this).val() == "") {
                $("#driver_name").val("");
                $("#driver_phone").val("");
                return false;
            }

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxVehicleInfo",
                success: function(data) {
                    var driver_name = data[0].driver_name;
                    var driver_phone = data[0].driver_contact;
                    $("#driver_name").val(driver_name);
                    $("#driver_phone").val(driver_phone);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // student section info for Room Details
    $(document).ready(function() {
        $("#SelectDormitory").on("change", function() {
            var url = $("#url").val();
            var i = 0;

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxRoomDetails",
                beforeSend: function() {
                    $('#select_dormitory_loader').addClass('pre_loader');
                    $('#select_dormitory_loader').removeClass('loader');
                },
                success: function(data) {
                    console.log(data);
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#selectRoomNumber").find("option").not(":first").remove();
                            $("#roomNumberDiv ul").find("li").not(":first").remove();

                            $.each(item, function(i, room) {
                                $("#selectRoomNumber").append(
                                    $("<option>", {
                                        value: room.id,
                                        text: room.name,
                                    })
                                );

                                $("#roomNumberDiv ul").append(
                                    "<li data-value='" +
                                    room.id +
                                    "' class='option'>" +
                                    room.name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#roomNumberDiv .current").html("Room Number *");
                            $("#selectRoomNumber").find("option").not(":first").remove();
                            $("#roomNumberDiv ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_dormitory_loader').removeClass('pre_loader');
                        $('#select_dormitory_loader').addClass('loader');
                    }
                }
            });
        });
    });

    // student admission onclick address pass
    $(document).ready(function() {
        $("#currentAddressCheck").on("click", function() {
            if ($(this).is(":checked")) {
                if ($("#guardians_address").val() != "") {
                    $("#current_address").html($("#guardians_address").val());
                }
            } else {
                $("#current_address").html("");
            }
        });
    });

    // student admission onclick address pass
    $(document).ready(function() {
        $("#permanentAddressCheck").on("click", function() {
            if ($(this).is(":checked")) {
                if ($("#guardians_address").val() != "") {
                    $("#permanent_address").html($("#guardians_address").val());
                }
            } else {
                $("#permanent_address").html("");
            }
        });
    });

    // student section select sction for sibling
    $(document).ready(function() {
        $("#select_sibling_class").on("change", function() {
            var url = $("#url").val();

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSectionSibling",
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_sibling_section")
                                .find("option")
                                .not(":first")
                                .remove();
                            $("#sibling_section_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, section) {
                                $("#select_sibling_section").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#sibling_section_div ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#sibling_section_div .current").html("SECTION *");
                            $("#select_sibling_section")
                                .find("option")
                                .not(":first")
                                .remove();
                            $("#sibling_section_div ul").find("li").not(":first").remove();
                        }
                    });
                    console.log(a);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // student section sibling info get
    $(document).ready(function() {
        $("#select_sibling_section").on("change", function() {
            var url = $("#url").val();
            var id = $("#id").val();

            if (typeof id === "undefined") {
                id = "";
            } else {
                id = id;
            }

            var formData = {
                id: id,
                section_id: $(this).val(),
                class_id: $("#select_sibling_class").val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSiblingInfo",
                success: function(data) {
                    console.log(data);
                    if (data.length) {
                        $("#select_sibling_name").find("option").not(":first").remove();
                        $("#sibling_name_div ul").find("li").not(":first").remove();

                        $.each(data, function(i, sibling) {
                            $("#select_sibling_name").append(
                                $("<option>", {
                                    value: sibling.id,
                                    text: sibling.first_name + " " + sibling.last_name,
                                })
                            );

                            $("#sibling_name_div ul").append(
                                "<li data-value='" +
                                sibling.id +
                                "' class='option'>" +
                                sibling.first_name +
                                " " +
                                sibling.last_name +
                                "</li>"
                            );
                        });
                    } else {
                        $("#sibling_name_div .current").html("Student *");
                        $("#select_sibling_name").find("option").not(":first").remove();
                        $("#sibling_name_div ul").find("li").not(":first").remove();
                    }
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        });
    });

    // student section sibling info get detail
    $(document).ready(function() {
        $("#save_button_parent").on("click", function() {
            var select_sibling_name = $("#select_sibling_name").val();
            var staff_id = $("#select_staff_parent").val();
            
            if (select_sibling_name == "" && staff_id =="") {
                $(".sibling_required_error div").remove();
                $(".sibling_required_error").append(
                    "<div class='alert alert-danger'>No sibling Selected</div>"
                );
                // alert('ok');
                return false;
            } else {
                $(".sibling_required_error div").remove();
            }

            var url = $("#url").val();

            var formData = {
                id: $("#select_sibling_name").val(),
                staff_id: staff_id,
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSiblingInfoDetail",
                success: function(data) {
                    
                    if(data[3] =='sibling') {
                        var fathers_name = data[1].fathers_name;
                        var parent_id = data[0].parent_id;

                        var guardians_name = data[1].guardians_name;

                        $("#parent_info div").remove();
                        $("#parent_info").append(
                            "<div class='alert primary-btn small parent_remove' id='parent_remove'>×<strong> Guardian: " +
                            guardians_name +
                            ", father: " +
                            fathers_name +
                            "</strong></div>"
                        );
                        $("#parent_info input").val(parent_id);
                        $("#parent_details").hide();
                    } else if(data[3] == 'staff') {
                        $("#parent_info div").remove();
                        $("#parent_info").append(
                            "<div class='alert primary-btn small parent_remove' id='parent_remove'>×<strong> Guardian: " +
                            data[2].full_name +
                            "[" +
                            data[2].roles['name'] +
                            "]</strong></div>"
                        );
                        $("#staff_parent").val(data[2].id);
                        $("#parent_details").hide();

                        
                       
                    }

                    // if($("#sibling_id").val() != 0){
                    //     $("#sibling_id").val(2);
                    // }
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        });
    });

    // student admission onclick sibling remove

    $(document).on("click", "#parent_remove", function(e) {
        
        $("#parent_info div").remove();
        $("#parent_info input").val("");
        $("#staff_parent").val("");
        $("#parent_details").show();
    });

    // student admission onclick address pass
    $(document).ready(function() {
        $(".relationButton").on("click", function() {
            if ($(this).val() == "F") {
                if($("#fathers_name").length){
                    $("#guardians_name").val($("#fathers_name").val());
                }
                if($("#fathers_occupation").length){
                    $("#guardians_occupation").val($("#fathers_occupation").val());
                }
                if($("#fathers_phone").length){
                    $("#guardians_phone").val($("#fathers_phone").val());
                }

                $("#relation").val("Father");

                var fathers_photo = $("#placeholderFathersName").attr("placeholder");
                if (fathers_photo != "") {
                    var sd = $("#placeholderFathersName").attr("placeholder");
                    $("#placeholderGuardiansName").attr("placeholder", sd);
                }
            } else if ($(this).val() == "M") {
                if($("#mothers_name").length){
                    $("#guardians_name").val($("#mothers_name").val());
                }
                if($("#mothers_occupation").length){
                    $("#guardians_occupation").val($("#mothers_occupation").val());
                }
                if($("#mothers_phone").length){
                    $("#guardians_phone").val($("#mothers_phone").val());
                }

                $("#relation").val("Mother");

                var mothers_photo = $("#placeholderMothersName").attr("placeholder");
                if (mothers_photo != "") {
                    var sd = $("#placeholderMothersName").attr("placeholder");
                    $("#placeholderGuardiansName").attr("placeholder", sd);
                }
            } else {

                $("#relation").val("Other");
                $("#placeholderGuardiansName").attr("placeholder", "PHOTO");
            }

            if ($("#guardians_name").val() != "") {
                $("#guardians_name").focus();
            } else {
                $("#guardians_name").focusout();
            }

            if ($("#guardians_occupation").val() != "") {
                $("#guardians_occupation").focus();
            } else {
                $("#guardians_occupation").focusout();
            }

            if ($("#guardians_phone").val() != "") {
                $("#guardians_phone").focus();
            } else {
                $("#guardians_phone").focusout();
            }
        });
    });

    // image or file browse

    var fileInput = document.getElementById( "upload_content_file" );
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderPhoto").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("photo");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderPhoto").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("fathers_photo");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderFathersName").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("mothers_photo");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderMothersName").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("guardians_photo");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById(
                "placeholderGuardiansName"
            ).placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("document_file_1");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderFileOneName").placeholder = fileName;
        }
    }
    var fileInput = document.getElementById("document_file_2");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderFileTwoName").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("document_file_3");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById(
                "placeholderFileThreeName"
            ).placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("document_file_4");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderFileFourName").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("document_file_5");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderFileFiveName").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("certificateBackGroundImage");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("certificateBackgroundImage").placeholder = fileName;
        }
    }

    var fileInput = document.getElementById("document_file_6");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderFileSixName").placeholder = fileName;
        }
    }

    // Student Delete modal

    $(function() {
        $(".deleteStudentModal").on("click", function() {
            var my_id_value = $(this).data("id");
            console.log(my_id_value);
            $(".modal-body #student_delete_id").val(my_id_value);
        });
    });
    // fees group Delete modal

    $(function() {
        $(".deleteFeesGroupModal").on("click", function() {
            var my_id_value = $(this).data("id");
            console.log(my_id_value);
            $(".modal-body #fees_group_id").val(my_id_value);
        });
    });

    // fees master single Delete modal

    $(function() {
        $(".deleteFeesMasterSingle").on("click", function() {
            var my_id_value = $(this).data("id");
            // console.log(my_id_value);
            $(".modal-body #fees_master_single_id").val(my_id_value);
        });
    });

    // fees master single Delete modal

    $(function() {
        $(".deleteFeesMasterGroup").on("click", function() {
            var my_id_value = $(this).data("id");
            // console.log(my_id_value);
            $(".modal-body #fees_master_group_id").val(my_id_value);
        });
    });

    // online exam delete modal

    $(function() {
        $(".deleteOnlineExam").on("click", function() {
            var my_id_value = $(this).data("id");
            // console.log(my_id_value);
            $(".modal-body #online_exam_id").val(my_id_value);
        });
    });

    // online exam Question Delete modal

    $(function() {
        $(".deleteOnlineExamQuestion").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #online_exam_question_id").val(my_id_value);
        });
    });

    // Assign Vehicle

    $(function() {
        $(".deleteAssignVehicle").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #assign_vehicle_id").val(my_id_value);
        });
    });

    // Role delete modal

    $(function() {
        $(".deleteRole").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #role_id").val(my_id_value);
        });
    });
    // delete fees modal

    $(function() {
        $(".deleteFeesPayment").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #feep_payment_id").val(my_id_value);
        });
    });

    // delete base setup

    $(function() {
        $(".deleteBaseSetupModal").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #base_setup_id").val(my_id_value);
        });
    });

    // delete add income

    $(function() {
        $(".deleteAddIncomeModal").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #ncome_id").val(my_id_value);
        });
    });

    // delete Admin Setup

    $(function() {
        $(".deleteSetupAdminModal").on("click", function() {
            var my_id_value = $(this).data("id");
            var url = $("#url").val();

            $(".modal-body a").attr(
                "href",
                url + "/setup-admin-delete/" + my_id_value
            );
        });
    });

    // admission query delete modal

    $(function() {
        $(".deleteAdmissionQueryModal").on("click", function() {
            var my_id_value = $(this).data("id");
            $(".modal-body #query_id").val(my_id_value);
        });
    });

    // remove sibling when student update

    $(document).on("click", "#yesRemoveSibling", function(event) {
        $("#siblingTitle").remove();
        $("#siblingHr").remove();
        $("#siblingInfo").remove();
        $("#sibling_id").val(2);
    });

    // Select section student promote

    // student section sibling info get
    $(document).ready(function() {
        $(".promote_session").on("change", function() {
            var url = $("#url").val();

            var formData = {
                year: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "promote-year",
                success: function(data) {
                    console.log(data);
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_class").find("option").not(":first").remove();
                            $("#select_class_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, session_class) {
                                $("#select_class").append(
                                    $("<option>", {
                                        value: session_class.id,
                                        text: session_class.class_name,
                                    })
                                );

                                $("#select_class_div ul").append(
                                    "<li data-value='" +
                                    session_class.id +
                                    "' class='option'>" +
                                    session_class.class_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_class_div .current").html("SELECT CLASS *");
                            $("#select_class").find("option").not(":first").remove();
                            $("#select_class_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // student promote sesction
    /*$(document).ready(function() {
                                            $("#promote_class").on("change", function() {
                                                var url = $("#url").val();

                                                var formData = {
                                                    id: $(this).val(),
                                                };
                                                // get section for student
                                                $.ajax({
                                                    type: "GET",
                                                    data: formData,
                                                    dataType: "json",
                                                    url: url + "/" + "ajaxStudentPromoteSection",
                                                    success: function(data) {
                                                        console.log(data);
                                                        var a = "";
                                                        $.each(data, function(i, item) {
                                                            if (item.length) {
                                                                $("#promote_section").find("option").not(":first").remove();
                                                                $("#promote_section_div ul").find("li").not(":first").remove();

                                                                $.each(item, function(i, section) {
                                                                    $("#promote_section").append(
                                                                        $("<option>", {
                                                                            value: section.id,
                                                                            text: section.section_name,
                                                                        })
                                                                    );

                                                                    $("#promote_section_div ul").append(
                                                                        "<li data-value='" +
                                                                        section.id +
                                                                        "' class='option'>" +
                                                                        section.section_name +
                                                                        "</li>"
                                                                    );
                                                                });
                                                            } else {
                                                                $("#promote_section_div .current").html(
                                                                    "SELECT PROMOTE SECTION *"
                                                                );
                                                                $("#promote_section").find("option").not(":first").remove();
                                                                $("#promote_section_div ul").find("li").not(":first").remove();
                                                            }
                                                        });
                                                    },
                                                    error: function(data) {
                                                        // console.log("Error:", data);
                                                    },
                                                });
                                            });
                                        });*/

    // Promote Student


    // student promote sesction ->remove comment ->abunayem
    $(document).ready(function() {
        $(".promote_class").on("change", function() {
            var url = $("#url").val();
            var key = $(this).data('key');
            var i=0;
            var formData = {
                id: $(this).val(),
            };

            $("#promote_section"+key).find("option").not(":first").remove();
            $("#promote_section_div"+key+" ul").find("li").not(":first").remove();
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",
                beforeSend: function() {
                    $('#select_section_promote'+key).addClass('pre_loader');
                    $('#select_section_promote'+key).removeClass('loader');
                },
                success: function(data) {
                    console.log(data);
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#promote_section"+key).find("option").not(":first").remove();
                            $("#promote_section_div"+key+" ul").find("li").not(":first").remove();

                            $.each(item, function(i, section) {
                                $("#promote_section"+key).append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#promote_section_div"+key+" ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });

                        } else {
                            $("#promote_section_div .current").html(
                                "SELECT PROMOTE SECTION *"
                            );
                            $("#promote_section"+key).find("option").not(":first").remove();
                            $("#promote_section_div"+key+" ul").find("li").not(":first").remove();
                        }
                    });
                },
                // afterSend: function() {
                //     $('.select_section_promote').removeClass('pre_loader');
                //     $('.select_section_promote').addClass('loader');
                // },
                error: function(data) {
                    // console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_section_promote'+key).removeClass('pre_loader');
                        $('#select_section_promote'+key).addClass('loader');
                    }
                }
            });
        });
    });


// Promote Student
    $("#search_promote").on("submit", function() {
        if ($("#current_session").val() == "") {
            $("#current_session_error").removeClass("d-none");
        } else {
            $("#current_session_error").addClass("d-none");
        }
        if ($("#select_class_student_promote").val() == "") {
            $("#current_class_error").removeClass("d-none");
        } else {
            $("#current_class_error").addClass("d-none");
        }
        if ($("#select_section_student_promote").val() == "") {
            $("#current_section_error").removeClass("d-none");
            return false;
        } else {
            $("#current_section_error").addClass("d-none");
        }
    });

    $("#student_promote_submit").on("submit", function() {
        var i = 0;
        if ($("#promote_session").val() == "") {
            $("#promote_session_error").removeClass("d-none");
            i++;
        } else {
            $("#promote_session_error").addClass("d-none");
        }
        if ($("#promote_class").val() == "") {
            $("#promote_class_error").removeClass("d-none");
            i++;
        } else {
            $("#promote_class_error").addClass("d-none");
        }

        if (i > 0) {
            return false;
        }
    });

    // Date picker
    $("#admission-date-icon").on("click", function() {
        $("#admissionDate").focus();
    });

    // student Attendance
    $(document).ready(function() {
        $("#select_class").on("change", function() {

            var url = $("#url").val();
            var parent = $("#parent").val();
            var i = 0;
            var formData = {
                id: $(this).val(),
                parent : parent
            };

            // console.log("formData");

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",

                beforeSend: function() {
                    $('#select_section_loader').addClass('pre_loader');
                    $('#select_section_loader').removeClass('loader');
                },
                success: function(data) {
                    // console.log(data);
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_section").find("option").not(":first").remove();
                            $("#select_section_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, section) {
                                $("#select_section").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#select_section_div ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_section_div .current").html("SELECT SECTION *");
                            $("#select_section").find("option").not(":first").remove();
                            $("#select_section_div ul").find("li").not(":first").remove();
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
    });

    // subject from class

    $(document).ready(function() {
        $("#class_subject").on("change", function() {
            var url = $("#url").val();
            var i = 0;


            var formData = {
                id: $(this).val(),
            };

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSubjectFromClass",

                beforeSend: function() {
                    $('#select_subject_loader').addClass('pre_loader');
                    $('#select_subject_loader').removeClass('loader');
                },
                success: function(data) {

                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_class_subject").find("option").not(":first").remove();
                            $("#select_class_subject_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, subject) {
                                $("#select_class_subject").append(
                                    $("<option>", {
                                        value: subject.id,
                                        text: subject.subject_name,
                                    })
                                );
                                $("#select_class_subject_div ul").append(
                                    "<li data-value='" +
                                    subject.id +
                                    "' class='option'>" +
                                    subject.subject_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_class_subject_div .current").html("SUBJECT *");
                            $("#select_class_subject").find("option").not(":first").remove();
                            $("#select_class_subject_div ul").find("li").not(":first").remove();
                        }
                    });
                    console.log(a);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
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

    //section from subject
    $(document).ready(function() {
        $("#select_class_subject").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            var formData = {
                class_id: $("#class_subject").val(),
                subject_id: $("#select_class_subject").val(),
            };

            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSubjectSection",

                beforeSend: function() {
                    $('#select_section_loader').addClass('pre_loader');
                    $('#select_section_loader').removeClass('loader');
                },
                success: function(data) {

                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#m_select_subject_section").find("option").not(":first").remove();
                            $("#m_select_subject_section_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, section) {
                                $("#m_select_subject_section").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );
                                $("#m_select_subject_section_div ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#m_select_subject_section_div .current").html("SECTION ");
                            $("#m_select_subject_section").find("option").not(":first").remove();
                            $("#m_select_subject_section_div ul").find("li").not(":first").remove();
                        }
                    });
                    console.log(a);
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

        })
    })

    //add lesson_Plan

    validateLssonPlan = () => {
        var lesson = document.getElementById("select_lesson").value;
        var topic = document.getElementById("select_topic").value;


        var i = 0;
        if (lesson == "") {
            document.getElementById("lesson_error").innerHTML =
                "Lesson field is required";
            i++;
        } else {
            document.getElementById("lesson_error").innerHTML = "";
        }
        if (topic == "") {
            document.getElementById("topic_error").innerHTML =
                "Topic field is required";
            i++;
        } else {
            document.getElementById("topic_error").innerHTML = "";
        }

        if (i > 0) {
            return false;
        }
    };

    // student Promote

    // promote student Attendance
    $(document).ready(function() {
        $("#c_select_class").change(function() {
            var ca = $("select[name='promote_class']").val();
            var url = $("#url").val();
            // console.log($(this).val());
            var formData = {
                id: $(this).val(),
            };

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",
                success: function(data) {
                    console.log(data);
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#c_select_section").find("option").not(":first").remove();
                            $("#c_select_section_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, section) {
                                $("#c_select_section").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#c_select_section_div ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#c_select_section_div .current").html("SELECT SECTION *");
                            $("#c_select_section").find("option").not(":first").remove();
                            $("#select_section_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        });
    });
    // student Promote

    //get student from class section
    $(document).ready(function() {
        $("#select_section").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            var select_class = $("#select_class").val();

            var formData = {
                section: $(this).val(),
                class: $("#select_class").val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSelectStudent",

                beforeSend: function() {
                    $('#select_student_loader').addClass('pre_loader');
                    $('#select_student_loader').removeClass('loader');
                },

                success: function(data) {
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_student").find("option").not(":first").remove();
                            $("#select_student_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, student) {
                                $("#select_student").append(
                                    $("<option>", {
                                        value: student.id,
                                        text: student.full_name,
                                    })
                                );

                                $("#select_student_div ul").append(
                                    "<li data-value='" +
                                    student.id +
                                    "' class='option'>" +
                                    student.full_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_student_div .current").html(jsLang('select_student')+" *");
                            $("#select_student").find("option").not(":first").remove();
                            $("#select_student_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_student_loader').removeClass('pre_loader');
                        $('#select_student_loader').addClass('loader');
                    }
                }
            });
        });
    });

    // add library member section
    $(document).ready(function() {
        $("#class_library_member").on("change", function() {
            var url = $("#url").val();

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_section_member").find("option").not(":first").remove();
                            $("#select_section__member_div ul")
                                .find("li")
                                .not(":first")
                                .remove();

                            $.each(item, function(i, section) {
                                $("#select_section_member").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#select_section__member_div ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_section__member_div .current").html(
                                "SELECT SECTION *"
                            );
                            $("#select_section_member").find("option").not(":first").remove();
                            $("#select_section__member_div ul")
                                .find("li")
                                .not(":first")
                                .remove();
                        }
                    });
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        });
    });

    $(document).ready(function() {
        $("#select_class").on("change", function() {
            var url = $("#url").val();
            var parent = $("#parent").val();

            var formData = {
                id: $(this).val(),
                parent: parent
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_section_member").find("option").not(":first").remove();
                            $("#select_section__member_div ul")
                                .find("li")
                                .not(":first")
                                .remove();

                            $.each(item, function(i, section) {
                                $("#select_section_member").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );

                                $("#select_section__member_div ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_section__member_div .current").html(
                                "SELECT SECTION *"
                            );
                            $("#select_section_member").find("option").not(":first").remove();
                            $("#select_section__member_div ul")
                                .find("li")
                                .not(":first")
                                .remove();
                        }
                    });
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        });
    });
    // library student select
    $(document).ready(function() {
        $("#select_section_member").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            var select_class = $("#select_class").val();
            if(!select_class) {
                var select_class = $("#class_library_member").val();
            }
            var formData = {
                section: $(this).val(),
                class: select_class,
            };
            console.log(formData);
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSelectStudent",

                beforeSend: function() {
                    $('#select_student_loader').addClass('pre_loader');
                    $('#select_student_loader').removeClass('loader');
                },

                success: function(data) {
                    console.log(data);
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_student").find("option").not(":first").remove();
                            $("#select_student_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, student) {
                                $("#select_student").append(
                                    $("<option>", {
                                        value: student.user_id,
                                        text: student.full_name,
                                    })
                                );

                                $("#select_student_div ul").append(
                                    "<li data-value='" +
                                    student.user_id +
                                    "' class='option'>" +
                                    student.full_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_student_div .current").html(jsLang('select_student') +" *");
                            $("#select_student").find("option").not(":first").remove();
                            $("#select_student_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_student_loader').removeClass('pre_loader');
                        $('#select_student_loader').addClass('loader');
                    }
                }
            });
        });
    });

    // Student attendance

    $("#search_student").on("submit", function() {
        var date = $("#startDate")
            .datepicker({
                dateFormat: "dd,MM,yyyy",
            })
            .val();

        if ($("#select_class").val() == "") {
            $("#class_error").removeClass("d-none");
        } else {
            $("#class_error").addClass("d-none");
        }
        if ($("#select_section").val() == "") {
            $("#section_error").removeClass("d-none");
        } else {
            $("#section_error").addClass("d-none");
        }
        if (date == "") {
            $("#date_error").removeClass("d-none");
            return false;
        } else {
            $("#date_error").addClass("d-none");
        }
    });

    // staff photo upload js

    var fileInput = document.getElementById("staff_photo");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderStaffsFName").placeholder = fileName;
        }
    }

    // Fees Assign
    $("#checkAll").on("click", function() {
        $("input:checkbox").prop("checked", this.checked);
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
    });

    // fees group assign
    // $(document).ready(function() {
    //     $("#btn-assign-fees-group").on("click", function() {
    //         var url = $("#url").val();

    //         var checkbox_val = new Array();
    //         var oTable = $("#table_id_table_one").dataTable();
    //         var rowcollection = $("input[name='student_checked[]']:checked");
    //         console.log(rowcollection);

    //         rowcollection.each(function(index, elem) {
    //             var checkbox_value = $(elem).val();
    //             checkbox_val.push(checkbox_value);
    //         });

    //         var id = JSON.stringify(checkbox_val);

    //         var st = new Array();
    //         var oTable = $("#table_id_table_one").dataTable();
    //         var rowcollection = $("input[name='student_checked[]']");

    //         rowcollection.each(function(index, elem) {
    //             var checkbox_value = $(elem).val();
    //             st.push(checkbox_value);
    //         });

    //         var student = JSON.stringify(st);

    //         var formData = {
    //             checked_ids: id,
    //             students: student,
    //             fees_group_id: $("#fees_group_id").val(),
    //         };

    //         console.log(formData);
    //         // get section for student
    //         $.ajax({
    //             type: "GET",
    //             data: formData,
    //             dataType: "json",
    //             url: url + "/" + "btn-assign-fees-group",
    //             success: function(data) {
    //                 console.log(data);
    //                 setTimeout(function() {
    //                     toastr.success(
    //                         "Successfully Assigned Fees Group!",
    //                         "Success Alert", {
    //                             iconClass: "customer-info",
    //                         }, {
    //                             timeOut: 2000,
    //                         }
    //                     );
    //                 }, 500);
    //             },
    //             error: function(data) {
    //                 console.log("Error:", data);
    //                 setTimeout(function() {
    //                     // toastr.error("Somethning went wrong!", "Error Alert", {
    //                     //     timeOut: 5000,
    //                     // });
    //                 }, 500);
    //             },
    //         });
    //     });
    // });

    // fees group assign
    // $(document).ready(function() {
    //     $("#btn-assign-fees-group").click(function(e) {
    //         $('#select_class_routine_loader').removeClass('dloader').addClass('pre_dloader');
    //         var url = $("#url").val();
    //         var assigned_status = $(".assigned_status").val();
    //         var checked_ids = $("input[name='student_checked[]']:checked")
    //         .map(function() {
    //             return $(this).val();
    //         })
    //         .get();
    //         var students = $("input[name='student_checked[]']")
    //         .map(function() {
    //             return $(this).val();
    //         })
    //         .get();
    //         var classId = $("input[name='class[]']").map(function() { return $(this).val();}).get();
    //         var sectionId = $("input[name='section[]']").map(function() {return $(this).val();}).get();
    //         var abc = $("input[name='student_checked[]']:checked")
    //             .map(function() {
    //                 return $(this).val();
    //             })
    //             .get();

    //         if (abc != "" || assigned_status == 1) {
    //             var formData = {
    //                 checked_ids:checked_ids,
    //                 students: students,
    //                 classId: classId,
    //                 sectionId: sectionId,
    //                 fees_group_id: $("#fees_group_id").val(),
    //             };
    //             // get section for student
    //             $.ajax({
    //                 type: "POST",
    //                 data: formData,
    //                 dataType: "json",
    //                 url: url + "/" + "btn-assign-fees-group",
    //                 success: function(data) {
    //                     $(".assigned_status").val(1);
    //                     // location.reload();
    //                     // console.log(data);
    //                     setTimeout(function() {
    //                         toastr.success("Operation successful", "Successful", {
    //                             timeOut: 5000,
    //                         });
    //                     }, 500);
    //                     $('#select_class_routine_loader').removeClass('pre_dloader').addClass('dloader');
    //                 },
    //                 error: function(data) {
    //                     $('#select_class_routine_loader').removeClass('pre_dloader').addClass('dloader');
    //                     //  console.log("Error:", data);
    //                     setTimeout(function() {
    //                         toastr.error("Somethning went wrong!", "Error Alert", {
    //                             timeOut: 5000,
    //                         });
    //                     }, 500);
    //                 },
    //             });
    //         } else {
    //             setTimeout(function() {
    //                 toastr.error("Student not selected", "Error Alert", {
    //                     timeOut: 5000,
    //                 });
    //             }, 500);
    //         }
    //     });
    // });

    // fees group assign
    // $(document).ready(function() {
    //     $("#btn-assign-fees-discount").on("click", function() {
    //         var url = $("#url").val();
    //         var abc = $("input[name='student_checked[]']:checked")
    //             .map(function() {
    //                 return $(this).val();
    //             })
    //             .get();

    //         // console.log(abc);

    //         for (var prop in abc) {
    //             console.log(prop + " = " + abc[prop]);

    //             var e = document.getElementById("fees_master" + abc[prop]);
    //             var get_master_id = e.options[e.selectedIndex].value;
    //             console.log("mas" + get_master_id);
    //             if (get_master_id == "") {
    //                 setTimeout(function() {
    //                     toastr.warning(
    //                         "Please select fees type/group for selected student !",
    //                         "Warning", {
    //                             timeOut: 5000,
    //                         }
    //                     );
    //                 }, 500);
    //                 return false;
    //             }
    //         }

    //         // var e = document.getElementById("fees_master"+abc);
    //         // var get_master_id = e.options[e.selectedIndex].value;

    //         var fm_id = [];
    //         $('select[name="fees_master_id[]"] option:selected').each(function() {
    //             fm_id.push($(this).val());
    //         });

    //         // console.log('master_id'+fm_id);
    //         var formData = {
    //             checked_ids: $("input[name='student_checked[]']:checked")
    //                 .map(function() {
    //                     return $(this).val();
    //                 })
    //                 .get(),
    //             fees_master_ids: $('select[name="fees_master_id[]"] option:selected')
    //                 .map(function() {
    //                     return $(this).val();
    //                 })
    //                 .get(),
    //             students: $("input[name='student_checked[]']")
    //                 .map(function() {
    //                     return $(this).val();
    //                 })
    //                 .get(),
    //             fees_discount_id: $("#fees_discount_id").val(),
    //             // fees_master_id: fm_id,
    //         };
    //         console.log(formData);

    //         // get section for student
    //         $.ajax({
    //             type: "GET",
    //             data: formData,
    //             dataType: "json",
    //             url: url + "/" + "fees-discount-assign-store",
    //             success: function(data) {
    //                 console.log(data.no);
    //                 if (data.no) {
    //                     toastr.error("Student select please!", "Error Alert", {
    //                         timeOut: 5000,
    //                     });
    //                 } else
    //                     setTimeout(function() {
    //                         toastr.success(
    //                             "Successfully assigned Fees Discount!",
    //                             "Success Alert", {
    //                                 iconClass: "customer-info",
    //                             }, {
    //                                 timeOut: 2000,
    //                             }
    //                         );
    //                     }, 500);
    //             },
    //             error: function(data) {
    //                 console.log("Error:", data);
    //                 setTimeout(function() {
    //                     toastr.error("Somethning went wrong!", "Error Alert", {
    //                         timeOut: 5000,
    //                     });
    //                 }, 500);
    //             },
    //         });
    //     });
    // });

    // student section info for student admission

    $(document).on("change", "#discount_group", function(event) {
        var url = $("#url").val();
        var real_amount = $("#real_amount").val();
        var amount = $("#real_amount").val();
        var student_id = $("#student_id").val();
        var get_fees_amount = $("#amount").val();

        if ($(this).val() == "") {
            $("#amount").val(real_amount);
            $("#discount_amount").val("").prop("readonly", false);
            return false;
        }

        var discount_id = $(this).val();
        var discount_id = discount_id.split("-");

        var formData = {
            fees_discount_id: discount_id[0],
            student_id: student_id,
            fees_amount: get_fees_amount,
        };
        console.log(formData);
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "fees-discount-amount-search",
            success: function(data) {
                console.log(parseInt(data));

                if (parseInt(data) > get_fees_amount) {
                    pay_amount = parseInt(data) - get_fees_amount;
                    console.log("dis");
                    // discount_amount=
                } else {
                    pay_amount = get_fees_amount - parseInt(data);
                    console.log("fees");
                }
                console.log("discount:" + data);
                console.log("fees:" + get_fees_amount);
                console.log("payable:" + pay_amount);
                $("#amount").val(pay_amount);
                $("#discount_amount").val(data).prop("readonly", true);
            },
            error: function(data) {
                // console.log("Error:", data);
            },
        });
    });

    validateFormFees = () => {
        var real_amount = parseInt(document.getElementById("real_amount").value);

        var amount = parseInt(document.getElementById("amount").value);

        var discount_amount = parseInt(
            document.getElementById("discount_amount").value
        );

        var amount_dis = amount + discount_amount;


        if (amount < 0) {

            document.getElementById("amount_error").innerHTML =
                "Deposit amount can not be less than zero";
            return false;
        } else if (amount > real_amount) {

            document.getElementById("amount_error").innerHTML =
                "Deposit amount can not be grater than remaining";
            return false;
        } else if (amount_dis > real_amount) {

            document.getElementById("amount_error").innerHTML =
                "Deposit  amount can not be grater than remaining";
            return false;
        }
    };

    // class routine get teacher

    changeSubject = () => {
        var url = $("#url").val();
        var formData = {
            class_id: $("#class_id").val(),
            section_id: $("#section_id").val(),
            subject: $("#subject").val(),
            class_time_id: $("#class_time_id").val(),
            day: $("#day").val(),
            update_teacher_id: $("#update_teacher_id").val(),
        };
        // console.log(formData);
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "get-class-teacher-ajax",
            success: function(data) {
                if (data[0] != "") {
                    $("#teacher_name").val(data[0]["full_name"]);
                    $("#teacher_id").val(data[0]["id"]);
                    $("#teacher_error").html("");
                } else {
                    if (data[1] == 0) {
                        $("#teacher_error").html("No teacher Assigned for the subject");
                    } else {
                        $("#teacher_error").html(
                            "the subject's teacher already assinged for the same time"
                        );
                    }
                    $("#teacher_name").val("");
                    $("#teacher_id").val("");
                }
            },
            error: function(data) {
                // console.log("Error:", data);
            },
        });
    };

    // add new class routine
    validateAddNewroutine = () => {

        var start_time = document.getElementsByClassName("start_time_required").value;
        var end_time = document.getElementsByClassName("end_time_required").value;
        var subject = document.getElementById("subject").value;
        var room = document.getElementById("room").value;
        var teacher_name = document.getElementById("teacher_name").value;

        var i = 0;

        if (start_time == "") {
            document.getElementsByClassName("start_time_error").innerHTML =
                "Start Time field is required";
            i++;
        } else {
            document.getElementById("start_time_error").innerHTML = "";
        }

        if (end_time == "") {
            document.getElementById("end_time_error").innerHTML =
                "End Time field is required";
            i++;
        } else {
            document.getElementById("end_time_error").innerHTML = "";
        }

        if (subject == "") {
            document.getElementById("subject_error").innerHTML =
                "Subject field is required";
            i++;
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
        if (room == "") {
            document.getElementById("room_error").innerHTML =
                "Room field is required";
            i++;
        } else {
            document.getElementById("room_error").innerHTML = "";
        }

        if (teacher_name == "") {
            document.getElementById("teacher_error").innerHTML =
                "Teacher field is required";
            i++;
        } else {
            document.getElementById("teacher_error").innerHTML = "";
        }

        if (i > 0) {
            return false;
        }
    };

    // Assign subject
    $(document).ready(function() {
        $("#addNewSubject").on("click", function() {
            var url = $("#url").val();
            var count = $("#assign-subject").children().length;
            var divCount = count + 1;

            // get section for student
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url + "/" + "assign-subject-get-by-ajax",
                success: function(data) {
                    var subject_teacher = "";
                    subject_teacher +=
                        "<div class='col-lg-12 mb-30' id='assign-subject-" +
                        divCount +
                        "'>";
                    subject_teacher += "<div class='row'>";
                    subject_teacher += "<div class='col-lg-5 mt-30-md'>";
                    subject_teacher +=
                        "<select class='primary_select' name='subjects[]' style='display:none'>";
                    subject_teacher +=
                        "<option data-display='"+window.jsLang('select_subject')+"'  value=''>"+window.jsLang('select_subject')+"</option>";
                    $.each(data[0], function(key, subject) {
                        subject_teacher +=
                            "<option value=" +
                            subject.id +
                            ">" +
                            subject.subject_name +
                            "</option>";
                    });
                    subject_teacher += "</select>";

                    subject_teacher +=
                        "<div class='nice-select primary_select form-control' tabindex='0'>";
                    subject_teacher += "<span class='current'>"+window.jsLang('select_subject')+"</span>";
                    subject_teacher +=
                        "<div class='nice-select-search-box'><input type='text' class='nice-select-search' placeholder='Search...'></div>";
                    subject_teacher += "<ul class='list'>";
                    subject_teacher +=
                        "<li data-value='' data-display='"+window.jsLang('select_subject')+"' class='option selected'>"+window.jsLang('select_subject')+"</li>";
                    $.each(data[0], function(key, subject) {
                        subject_teacher +=
                            "<li data-value=" +
                            subject.id +
                            " class='option'>" +
                            subject.subject_name +
                            "</li>";
                    });
                    subject_teacher += "</ul>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    subject_teacher += "<div class='col-lg-5 mt-30-md'>";
                    subject_teacher +=
                        "<select class='primary_select form-control' name='teachers[]' style='display:none'>";
                    subject_teacher +=
                        "<option data-display='"+window.jsLang('select_teacher')+"' value=''>"+window.jsLang('select_teacher')+"</option>";
                    $.each(data[1], function(key, teacher) {
                        subject_teacher +=
                            "<option value=" +
                            teacher.id +
                            ">" +
                            teacher.full_name +
                            "</option>";
                    });
                    subject_teacher += "</select>";
                    subject_teacher +=
                        "<div class='nice-select primary_select form-control' tabindex='0'>";
                    subject_teacher += "<span class='current'>"+window.jsLang('select_teacher')+"</span>";
                    subject_teacher +=
                        "<div class='nice-select-search-box'><input type='text' class='nice-select-search' placeholder='Search...'></div>";
                    subject_teacher += "<ul class='list'>";
                    subject_teacher +=
                        "<li data-value='' data-display='"+window.jsLang('select_teacher')+"' class='option selected'>"+window.jsLang('select_teacher')+"</li>";
                    $.each(data[1], function(key, teacher) {
                        subject_teacher +=
                            "<li data-value=" +
                            teacher.id +
                            " class='option'>" +
                            teacher.full_name +
                            "</li>";
                    });
                    subject_teacher += "</ul>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    subject_teacher += "<div class='col-lg-2'>";
                    subject_teacher +=
                        "<button class='primary-btn icon-only fix-gr-bg' id='removeSubject' onclick='deleteSubject(" +
                        divCount +
                        ")' type='button'>";
                    subject_teacher += "<span class='ti-trash' ></span>";
                    subject_teacher += "</button>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    $("#assign-subject").append(subject_teacher);
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        });
    });

    // add new class routine
    examRoutineCheck = () => {
        var date = document.getElementById("startDate").value;
        var i = 0;
        if (date == "") {
            document.getElementById("date_error").innerHTML =
                "Date field is required";
            $("#holiday_message").html("");
            i++;
        } else {
            document.getElementById("date_error_count").value = "";
        }

        if (i > 0) {
            return false;
        }

        var url = $("#url").val();

        var formData = {
            class_id: $("#class_id").val(),
            section_id: $("#section_id").val(),
            exam_period_id: $("#exam_period_id").val(),
            exam_term_id: $("#exam_term_id").val(),
            date: $("#startDate").val(),
            assigned_id: $("#assigned_id").val(),
        };

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "check-exam-routine-date",
            success: function(data) {
                if (data[0].length == 0) {
                    $("#date_error").html("");
                    $("#date_error_count").val();
                } else {
                    $("#date_error").html("already one subject assigned");
                    $("#date_error_count").val(1);
                }

                // console.log(data[1]);

                if (data[1] !== null) {
                    $("#holiday_message").html(
                        "Holiday [" +
                        data[1]["holiday_title"] +
                        "   " +
                        data[2] +
                        " to " +
                        data[3] +
                        " ]"
                    );
                } else {
                    $("#holiday_message").html("");
                }
            },
            error: function(data) {
                // console.log("Error:", data);
            },
        });
    };


    // add new exam routine
    validateAddNewExamRoutine = () => {
        var date = document.getElementById("startDate").value;
        var room = document.getElementById("room").value;
        var date_error_count = document.getElementById("date_error_count").value;

        // console.log(date_error_count);

        var i = 0;

        if (date_error_count == "") {
            if (date == "") {
                document.getElementById("date_error").innerHTML =
                    "Date field is required";
                i++;
            } else {
                document.getElementById("date_error").innerHTML = "";
            }
        } else {
            i++;
        }

        if (room == "") {
            document.getElementById("room_error").innerHTML =
                "Room field is required";
            i++;
        } else {
            document.getElementById("room_error").innerHTML = "";
        }

        if (i > 0) {
            return false;
        }

        var url = $("#url").val();

        var formData = {
            class_id: $("#class_id").val(),
            section_id: $("#section_id").val(),
            exam_period_id: $("#exam_period_id").val(),
            exam_term_id: $("#exam_term_id").val(),
            date: date,
        };

        var trueorfalse = true;

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "check-exam-routine-period",
            async: false,
            success: function(data) {
                console.log(data.exam_period_check);
                if (data.exam_period_check != null) {
                    alert("Already assigned, please change date or period");
                    trueorfalse = false;
                } else {
                    trueorfalse = true;
                }
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });

        return trueorfalse;
    };

    deleteSubject = (value) => {
        var assignSubject = document.getElementById("assign-subject");
        var valuea = "assign-subject-" + value;
        var child = document.getElementById(valuea);
        child.remove();
    };

    // get subject from class,Section
    /*$(document).ready(function() {
        $(".select_section").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            var select_class = $(this).val();
            
            var formData = {
                section: $(this).val(),
                class: $("#select_class").val(),
            };

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSelectSubject",

                beforeSend: function() {
                    $('#select_subject_loader').addClass('pre_loader');
                    $('#select_subject_loader').removeClass('loader');
                },

                success: function(data) {
                    console.log(data);
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
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_subjectloader').removeClass('pre_loader');
                        $('#select_subject_loader').addClass('loader');
                    }
                }
            });
        });
    });*/

    $(document).ready(function() {
        $("#select_section").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            var select_class = $("#select_class").val();
            if(!$(this).val()) {
                return ;
            }
            
            if (!select_class) {
                var select_class = $("#lms_select_class").val()
            }
            var formData = {
                section: $(this).val(),
                class: select_class,
            };
            $("#select_subject").empty().append(
                $("<option>", {
                    value:  '',
                    text: window.jsLang('select_subject') + ' *',
                })
            );

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSelectSubject",

                beforeSend: function() {
                    $('#select_subject_loader').addClass('pre_loader');
                    $('#select_subject_loader').removeClass('loader');
                },

                success: function(data) {



                    $('#classSelectStudent').niceSelect('update');
                    $.each(data, function(i, item) {
                        if (item.length) {

                            $.each(item, function(i, subject) {
                                var type = subject.subject_type == "T" ? "Theory" : "Practical";
                                $("#select_subject").append(
                                    $("<option>", {
                                        value: subject.id,
                                        text: subject.subject_name +" (" + type + ")",
                                    })
                                );
                            });
                        }
                    });

                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    $('#select_subject').niceSelect('update');
                    i--;
                    if (i <= 0) {
                        $('#select_subjectloader').removeClass('pre_loader');
                        $('#select_subject_loader').addClass('loader');
                    }
                }
            });
        });
    });
    
    $(document).ready(function() {
        if ($("#exam_schedule_store").length) {
            $("form#exam_schedule_store").on("submit", function(event) {
                //Add validation rule for dynamically generated name fields
                $(".date_input").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Date is required",
                        },
                    });
                });

                $(".passing_input").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Passing mark is required",
                        },
                    });
                });

                $(".start_time_input").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Start time is required",
                        },
                    });
                });

                $(".end_time_input").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "End time is required",
                        },
                    });
                });

                $(".full_marks_input").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Full mark is required",
                        },
                    });
                });

                $(".room_input").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Room is required",
                        },
                    });
                });
            });
            $("#exam_schedule_store").validate();
        }
    });

    $(document).ready(function() {
        if ($("#marks_register_store").length) {
            // $('form#marks_register_store').on('submit', function (event) {
            //     //Add validation rule for dynamically generated name fields
            //     $('.marks_input').each(function () {

            //         $(this).rules("add", {
            //             required: true,
            //             messages: {
            //                 required: "Required",
            //             }
            //         });

            //     });

            // });

            // $("#marks_register_store").validate();

            $("form#marks_register_store").on("submit", function(event) {
                var i = 0;
                var j = 0;
                $(".marks_input").each(function() {
                    if (
                        parseInt($(this).siblings("#part_marks").val()) <
                        parseInt($(this).val())
                    ) {
                        i++;
                    }
                    if ($(this).val() == "") {
                        j++;
                    }
                });

                if (j > 0) {
                    setTimeout(function() {
                        toastr.error("Mark fields are required.!", "Error Alert", {
                            timeOut: 5000,
                        });
                    }, 500);
                    return false;
                }

                if (i > 0) {
                    setTimeout(function() {
                        toastr.error(
                            "Obtained mark can not be higher than distributed mark.!",
                            "Error Alert", {
                                timeOut: 5000,
                            }
                        );
                    }, 500);
                    return false;
                }
            });
        }
    });

    // Add New Room In Exam Section
    $(document).ready(function() {
        $("#addNewRoom").on("click", function() {
            var url = $("#url").val();

            // $('#assign_exam_room tr:last').before("<tr><td>new row</td></tr>");

            var count = $("#assign_exam_room tr").length;
            var rowCount = count + 1 - 1;
            var rowCount = rowCount - 1;

            // get section for student
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url + "/" + "assign-exam-room-get-by-ajax",
                success: function(data) {
                    console.log(data[0]);

                    var appendRow = "";
                    appendRow += "<tr id=" + rowCount + ">";
                    appendRow += "<td></td>";
                    appendRow += "<td></td>";
                    appendRow += "<td></td>";
                    appendRow += "<td>";
                    appendRow += "<div class='row'>";
                    appendRow += "<div class='col'>";
                    appendRow += "<div class='input-effect'>";
                    appendRow +=
                        "<select class='primary_select class_room room_input'  name='room[]' id='room_" +
                        rowCount +
                        "'>";
                    appendRow +=
                        "<option data-display='Select *' value=''>Select *</option>";
                    $.each(data[0], function(key, room) {
                        appendRow +=
                            "<option value='" + room.id + "'>" + room.room_no + "</option>";
                    });
                    appendRow += "</select>";
                    appendRow +=
                        "<span id='room_error-" +
                        rowCount +
                        "' class='text-danger'></span>";
                    appendRow += "</div>";
                    appendRow += "</div>";
                    appendRow += "</div>";
                    appendRow += "</td>";
                    appendRow += "<td>";
                    appendRow += "<div class='row'>";
                    appendRow += "<div class='col'>";
                    appendRow += "<div class='input-effect'>";
                    appendRow +=
                        "<input class='primary-input' type='text' placeholder='Room Capacity' name='capacity[]' id='capacity-" +
                        rowCount +
                        "' readonly>";

                    appendRow +=
                        "<input type='hidden' name='already_assigned' id='already_assigned-" +
                        rowCount +
                        "'>";
                    appendRow +=
                        "<input type='hidden' name='room_capacity' id='room_capacity-" +
                        rowCount +
                        "'>";
                    appendRow += "<span class='focus-border'></span>";
                    appendRow += "</div>";
                    appendRow += " </div>";
                    appendRow += "</div>";
                    appendRow += "</td>";
                    appendRow += "<td>";
                    appendRow += "<div class='row'>";
                    appendRow += "<div class='col'>";
                    appendRow += "<div class='input-effect'>";
                    appendRow +=
                        "<input class='primary-input assign_student' type='text' placeholder='Enter Student No' name='assign_student[]' id='assign_student-" +
                        rowCount +
                        "'>";
                    appendRow += "<span class='focus-border'></span>";
                    appendRow +=
                        "<span id='assign_student_error-" +
                        rowCount +
                        "' class='text-danger'></span>";
                    appendRow += "</div>";
                    appendRow += "</div>";
                    appendRow += "</div>";
                    appendRow += "</td>";
                    appendRow += "<td class='text-right'>";
                    appendRow += "<button class='primary-btn icon-only fix-gr-bg'>";
                    appendRow +=
                        "<span class='ti-trash text-white' onclick='deleteExamRow(" +
                        rowCount +
                        ")'></span>";
                    appendRow += "</button>";
                    appendRow += "</td>";
                    appendRow += "</tr>";
                    $("#assign_exam_room tr:last").before(appendRow);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // assign exam room remove row
    deleteExamRow = (value) => {
        var id = value;
        var el = document.getElementById(id);
        el.parentNode.removeChild(el);
        return false;
    };

    // Add New Room In Exam Section

    $(document).on("change", ".class_room", function(event) {
        var trNo = $(this).parents("tr").attr("id");
        var class_room_id = [];
        $(".class_room").each(function() {
            if ($(this).val() != "") {
                class_room_id.push($(this).val());
            }
        });

        if (find_duplicate_in_array(class_room_id) == 1) {
            $("#room_error-" + trNo).html("Alreday selected the room");
            $("#capacity-" + trNo).val("");
            $("#assign_student-" + trNo).val("");
            return false;
        } else {
            $("#room_error-" + trNo).html("");
        }

        if ($(this).val() == "") {
            $("#capacity-" + trNo).val("");
            $("#already_assigned-" + trNo).val("");
            $("#room_capacity-" + trNo).val("");
            $("#assign_student-" + trNo).val("");
            return false;
        }

        var url = $("#url").val();
        var trNo = $(this).parents("tr").attr("id");

        var abc = $(this).closest("td").siblings().find("input").val();

        var formData = {
            id: $(this).val(),
            date: $("#exam_date").val(),
            start_time: $("#start_time").val(),
            end_time: $("#end_time").val(),
        };

        // get section for student
        $.ajax({
            type: "GET",
            dataType: "json",
            data: formData,
            url: url + "/" + "get-room-capacity",
            success: function(data) {
                console.log(data);
                $("#capacity-" + trNo).val(
                    "Assigned " + data[1] + " of " + data[0].capacity
                );
                $("#already_assigned-" + trNo).val(data[1]);
                $("#room_capacity-" + trNo).val(data[0].capacity);
                $("#assign_student-" + trNo).val("");
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });

    $(document).on("submit", "#seat_plan_store", function(event) {
        var room_validate = [];
        $("table tr .class_room").each(function() {
            if ($(this).find("option:selected").val() == "") {
                room_validate.push($(this).parents("tr").attr("id"));
            }
        });

        var assign_students = [];
        var total_assign_students = 0;
        $("table tr .assign_student").each(function() {
            if ($(this).val() == "") {
                assign_students.push($(this).parents("tr").attr("id"));
            } else {
                $("#assign_student_error-" + $(this).parents("tr").attr("id")).html("");
                total_assign_students = total_assign_students + parseInt($(this).val());
            }
        });

        $.each(room_validate, function(i, val) {
            $("#room_error-" + val).html("Required");
        });

        $.each(assign_students, function(i, val) {
            $("#assign_student_error-" + val).html("Required");
        });

        if (room_validate.length > 0 || assign_students.length > 0) {
            return false;
        }

        var class_room_id = [];
        $(".class_room").each(function() {
            if ($(this).val() != "") {
                class_room_id.push($(this).val());
            }
        });

        if (find_duplicate_in_array(class_room_id) == 1) {
            return false;
        }

        var room_capacisity_validate = [];
        $("table tr .assign_student").each(function() {
            var trNo = $(this).parents("tr").attr("id");
            var already_assign = parseInt($("#already_assigned-" + trNo).val());
            var room_capacity = parseInt($("#room_capacity-" + trNo).val());
            var assign_student = parseInt($(this).val());
            var gap_seat = room_capacity - already_assign;
            console.log(gap_seat);
            if (assign_student > gap_seat) {
                room_capacisity_validate.push($(this).parents("tr").attr("id"));
            } else {
                $("#assign_student_error-" + trNo).html("");
            }
        });

        $.each(room_capacisity_validate, function(i, val) {
            var capacity = $("#room_capacity-" + val).val();
            $("#assign_student_error-" + val).html("Room Capacity is " + capacity);
        });

        if (room_capacisity_validate.length > 0) {
            return false;
        }

        if (total_assign_students > parseInt($("#total_student").val())) {
            $("#assign_student_error-1").html("Assigned More than total students");
            return false;
        } else {
            $("#assign_student_error-1").html("");
        }
    });

    find_duplicate_in_array = (arra1) => {
        const object = {};
        var result = 0;

        arra1.forEach((item) => {
            if (!object[item]) object[item] = 0;
            object[item] += 1;
        });

        for (const prop in object) {
            if (object[prop] >= 2) {
                result = 1;
            }
        }
        return result;
    };


    $(document).ready(function() {
        $("#question_bank div#multiple-choice").hide();
        $("#question_bank div#true-false").hide();
        $("#question_bank div#multiple-image-section").hide();
        $("#question_bank div#fill-in-the-blanks").hide();
        $("#question_bank div#multiple-options").html("");
    });

    $(document).on("change", "#question-type", function(event) {
        var question_type = $("#question-type").val();
        if (question_type == "") {
            $("#question_bank div#multiple-choice").hide();
            $("#question_bank div#multiple-image-section").hide();
            $("#question_bank div#true-false").hide();
            $("#question_bank div#fill-in-the-blanks").hide();
            $("#question_bank div#multiple-options").html("");
        } else if (question_type == "M") {
            $("#question_bank div#multiple-choice").show();
            $("#question_bank div#true-false").hide();
            $("#question_bank div#multiple-image-section").hide();
            $("#question_bank div#fill-in-the-blanks").hide();
        } else if (question_type == "T") {
            $("#question_bank div#multiple-choice").hide();
            $("#question_bank div#true-false").show();
            $("#question_bank div#multiple-image-section").hide();
            $("#question_bank div#fill-in-the-blanks").hide();
            $("#question_bank div#multiple-options").html("");
        } else if (question_type == "MI") {
            $("#question_bank div#multiple-choice").hide();
            $("#question_bank div#true-false").hide();
            $("#question_bank div#multiple-image-section").show();
            $("#question_bank div#fill-in-the-blanks").hide();
            $("#question_bank div#multiple-options").html("");
        } else {
            $("#question_bank div#multiple-choice").hide();
            $("#question_bank div#true-false").hide();
            $("#question_bank div#multiple-image-section").hide();
            $("#question_bank div#fill-in-the-blanks").show();
            $("#question_bank div#multiple-options").html("");
        }
    });

    $(document).on("click", "#create-option", function(event) {
        $("#question_bank div.multiple-options").html("");

        var number_of_option = $("#number_of_option").val();
        if (number_of_option < 2) {
            toastr.warning('Please enter number of options', 'Warning', {
                timeOut: 5000
            })
        }
        for (var i = 1; i <= number_of_option; i++) {
            var appendRow = "";
            appendRow += "<div class='row  mt-25'>";
            appendRow += "<div class='col-lg-10'>";
            appendRow += "<div class='input-effect'>";
            appendRow +=
                "<input class='primary_input_field form-control has-content' placeholder='option " +
                i +
                "' type='text' name='option[]' autocomplete='off' required>";
            appendRow += "</div>";
            appendRow += "</div>";
            appendRow += "<div class='col-lg-2'>";

            appendRow +=
                "<input type='checkbox' id='option_check_" +
                i +
                "' class='common-checkbox' name='option_check_" +
                i +
                "' value='1'>";
            appendRow += "<label for='option_check_" + i + "'></label>";

            appendRow += "</div>";
            appendRow += "</div>";

            $(".multiple-options").append(appendRow);
        }
    });
    $(document).on("click", "#create-image-option", function(event) {
        console.log('clicked');
        $("#question_bank div.multiple-images").html("");

        var number_of_option = $("#number_of_image_option").val();
        for (var i = 1; i <= number_of_option; i++) {
            var appendRow = "";
            appendRow += "<div class='row  mt-25'>";
            appendRow += "<div class='col-lg-10'>";
            appendRow += "<div class='input-effect'>";
            // appendRow +="<input class='primary_input_field form-control has-content'  type='file' name='images[]' autocomplete='off' required>";
            appendRow += "<label class='primary-btn fix-gr-bg multiple_images'><i class='fa fa-image'></i> <span class='show_file_name" + i + "'>No File Chosen [650x450]</span> <input type='file' onChange='uploadImage(" + i + ")' name='images[]' id='question_image" + i + "' style='display: none;'></label>";
            appendRow += "</div>";
            appendRow += "</div>";
            appendRow += "<div class='col-lg-2 mt-10'>";

            appendRow +=
                "<input type='checkbox' id='option_check_" +
                i +
                "' class='common-checkbox' name='option_check_" +
                i +
                "' value='1'>";
            appendRow += "<label for='option_check_" + i + "'></label>";

            appendRow += "</div>";
            appendRow += "</div>";

            $(".multiple-images").append(appendRow);
        }
    });

    $(document).ready(function() {
        $("#route").on("change", function() {
            var url = $("#url").val();
            var i = 0;
            if ($(this).val() == "") {
                $("#select_vehicle_div .current").html("SELECT VEHICLE");
                $("#selectVehicle").find("option").not(":first").remove();
                $("#select_vehicle_div ul").find("li").not(":first").remove();
                return false;
            }

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxGetVehicle",
                beforeSend: function() {
                    $('#select_transport_loader').addClass('pre_loader');
                    $('#select_transport_loader').removeClass('loader');
                },
                success: function(data) {
                    console.log(data);
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#selectVehicle").find("option").not(":first").remove();
                            $("#select_vehicle_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, vehicle) {
                                $("#selectVehicle").append(
                                    $("<option>", {
                                        value: vehicle.id,
                                        text: vehicle.vehicle_no,
                                    })
                                );

                                $("#select_vehicle_div ul").append(
                                    "<li data-value='" +
                                    vehicle.id +
                                    "' class='option'>" +
                                    vehicle.vehicle_no +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_vehicle_div .current").html("SELECT VEHICLE");
                            $("#selectVehicle").find("option").not(":first").remove();
                            $("#select_vehicle_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#select_transport_loader').removeClass('pre_loader');
                        $('#select_transport_loader').addClass('loader');
                    }
                }
            });
        });
    });

    // get roll

    /*$(document).on(
        "change",
        "#student_form #sectionSelectStudent",
        function(event) {
            var url = $("#url").val();

            if ($(this).val() != "" && $("#classSelectStudent").val() != "") {
                $("#student_form #roll_number").prop("readonly", false);
            } else {
                $("#student_form #roll_number").prop("readonly", true);
            }

            var formData = {
                section: $(this).val(),
                class: $("#classSelectStudent").val(),
            };

            // get roll for student
            if($(this).val()){
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "ajax-get-roll-id",
                    success: function(data) {
                        $("#student_form #roll_number").val(data);
                        if ($("#student_form #roll_number").val() != "") {
                            $("#student_form #roll_number").focus();
                        }
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                });
            } else{
                $("#student_form #roll_number").val('').prop("readonly", true);
            }
        }
    );*/

    // $(document).on("keyup", "#student_form #roll_number", function(event) {
    //     var url = $("#url").val();

    //     var formData = {
    //         roll_no: $(this).val(),
    //         section: $("#sectionSelectStudent").val(),
    //         class: $("#classSelectStudent").val(),
    //     };

    //     // get roll for student
    //     $.ajax({
    //         type: "GET",
    //         data: formData,
    //         dataType: "json",
    //         url: url + "/" + "ajax-get-roll-id-check",
    //         success: function(data) {
    //             if (data.length != 0) {
    //                 $("#student_form #roll-error strong").html(
    //                     "The roll no already exist"
    //                 );
    //             } else {
    //                 $("#student_form #roll-error strong").html("");
    //             }
    //         },
    //         error: function(data) {
    //             console.log("Error:", data);
    //         },
    //     });
    // });

    $(document).on("change", "#fees_master_form #fees_group", function(event) {
        if ($(this).val() == 1 || $(this).val() == 2) {
            $("#fees_master_amount").hide();
        } else {
            $("#fees_master_amount").show();
        }
    });

    // fees collect invoice modal
    $(document).ready(function() {
        $("body").on("click", ".modalLinkInvoice", function(e) {
            e.preventDefault();
            $(".modal-backdrop").show();
            $("#showDetaildModalInvoice").show();
            $("div.modal-dialog").removeClass("modal-md");
            $("div.modal-dialog").removeClass("modal-lg");
            $("div.modal-dialog").removeClass("modal-bg");
            var modal_size = $(this).attr("data-modal-size");
            if (
                modal_size !== "" &&
                typeof modal_size !== typeof undefined &&
                modal_size !== false
            ) {
                $("#modalSize").addClass(modal_size);
            } else {
                $("#modalSize").addClass("modal-md");
            }
            var title = $(this).attr("title");
            $("#showDetaildModalTileInvoice").text(title);
            var data_title = $(this).attr("data-original-title");
            $("#showDetaildModalInvoice").modal("show");
            $("div.ajaxLoader").show();
            $.ajax({
                type: "GET",
                url: $(this).attr("href"),
                success: function(data) {
                    $("#showDetaildModalBodyInvoice").html(data);
                    $("#showDetaildModalInvoice").modal("show");
                },
            });
        });
    });

    //  print student fees report
    $(document).on("click", ".fees-groups-print", function(event) {
        var url = $("#url").val();
        var student_id = $("#student_id").val();
        var sList = "";
        $("input[type=checkbox]").each(function() {
            if (this.checked) {
                sList += sList == "" ? $(this).val() : "-" + $(this).val();
            }
        });
        if (sList != "") {
            $("#fees-groups-print-button").attr(
                "href",
                url + "/fees-groups-print/" + sList + "/" + student_id
            );
            $("#fees-groups-print-button").attr("target", "_blank");
        } else {
            $("#fees-groups-print-button").attr("href", "");
        }
    });
    $(document).on(
        "click",
        "#fees_groups_invoice_print_button",
        function(event) {
            var url = $("#url").val();
            var student_id = $("#student_id").val();
            //console.log(student_id);

            var sList = "";
            $("input[type=checkbox]").each(function() {
                if (this.checked) {
                    sList += sList == "" ? $(this).val() : "-" + $(this).val();
                }
            });
            console.log(sList);

            if (sList != "") {
                $("#fees_groups_invoice_print_button").attr(
                    "href",
                    url + "/fees-payment-invoice-print/" + sList + "/" + student_id
                );
                $("#fees_groups_invoice_print_button").attr("target", "_blank");
            } else {
                toastr.error("Please select fees!", "Error");
            }
        }
    );

    // online exam question delete
    // function removeDiv(clickBtn, toggleDiv) {
    //         clickBtn.on('click', function() {
    //             console.log('dfgd');
    //             toggleDiv.hide('slow', function() {
    //                 toggleDiv.remove();
    //             });
    //         });
    //     }
    // removeDiv($('.efd'), $('.abc'));

    //countdown timer

    // Set the date we're counting down to
    if ($("#count_date").length) {
        var count_date = document.getElementById("count_date").value;
    }
    if ($("#count_start_time").length) {
        var count_start_time = document.getElementById("count_start_time").value;
    }
    if ($("#count_end_time").length) {
        var count_end_time = document.getElementById("count_end_time").value;
    }

    var countEndTime = new Date(count_end_time).getTime();

    // Update the count down every 1 second
    var currentTime = setInterval(function() {
        // Get todays date and time
        var countStartTime = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countEndTime - countStartTime;

        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor(
            (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        );
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        if ($("#countDownTimer").length) {
            document.getElementById("countDownTimer").innerHTML =
                "<strong>Remaining Time: </strong>" +
                hours +
                " hour " +
                minutes +
                " minute " +
                seconds +
                " second ";
        }

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(currentTime);
            document.getElementById("countDownTimer").innerHTML =
                "<span class='text-danger'>Exam submittion time expired</span>";
            var element = document.getElementById("online_take_exam_button");
            element.setAttribute("type", "button");
        }
    }, 1000);

    // search account income expense

    $(document).ready(function() {
        $("#search_income_expense div#income_div").hide();
        $("#search_income_expense div#expense_div").hide();
    });

    $(document).ready(function() {
        $("#account-type").on("change", function() {
            if ($(this).val() == "In") {
                $("#search_income_expense div#income_div").show();
                $("#search_income_expense div#expense_div").hide();
                $("#search_income_expense div#filtering_div").hide();
            } else if ($(this).val() == "Ex") {
                $("#search_income_expense div#income_div").hide();
                $("#search_income_expense div#expense_div").show();
                $("#search_income_expense div#filtering_div").hide();
            } else {
                $("#search_income_expense div#income_div").hide();
                $("#search_income_expense div#expense_div").hide();
                $("#search_income_expense div#filtering_div").show();
            }
        });
    });

    // student id card print

    //  generate-id-card-print
    $(document).on("click", ".generate-id-card-print", function(event) {
        var url = $("#url").val();
        var id_card = $("#id_card").val();

        console.log(id_card);

        var sList = "";
        var len = new Array();
        $("input[type=checkbox]").each(function() {
            if (this.checked) {
                sList += sList == "" ? $(this).val() : "-" + $(this).val();
                len.push($(this).val());
            }
        });

        if (len.length > 8) {
            toastr.warning("You can not select more than 8 student!");
            $("#genearte-id-card-print-button").attr("href", "javascript:;");
            $("#genearte-id-card-print-button").removeAttr("target");
        } else if (sList != "") {
            $("#genearte-id-card-print-button").attr(
                "href",
                url + "/generate-id-card-print/" + sList + "/" + id_card
            );
            $("#genearte-id-card-print-button").attr("target", "_blank");
        } else {
            toastr.error('Please select student!');
            $("#genearte-id-card-print-button").attr("href", "javascript:;");
            $("#genearte-id-card-print-button").removeAttr("target");
        }

        /* if (sList != "") {
                                                                                            $("#genearte-id-card-print-button").attr("href", url + "/generate-id-card-print/" + sList + "/" + id_card);
                                                                                            $("#genearte-id-card-print-button").attr("target", '_blank');
                                                                                        } else {
                                                                                            $("#genearte-id-card-print-button").attr("href", '');
                                                                                        } */
    });

    $(document).on("click", ".generate-id-card-print-all", function(event) {
        var url = $("#url").val();
        var id_card = $("#id_card").val();
        var sList = "";
        var len = new Array();
        if ($(this).prop("checked") == true) {
            $("input[type=checkbox]").each(function() {
                if ($(this).val() != "") {
                    sList += sList == "" ? $(this).val() : "-" + $(this).val();
                    len.push($(this).val());
                }
            });
        } else {
            sList = "";
        }

        if (len.length > 8) {
            toastr.warning("You can not select more than 8 student!");
            $("#genearte-id-card-print-button").attr("href", "javascript:;");
            $("#genearte-id-card-print-button").removeAttr("target");
            console.log("p");
        } else if (sList != "") {
            $("#genearte-id-card-print-button").attr(
                "href",
                url + "/generate-id-card-print/" + sList + "/" + id_card
            );
            $("#genearte-id-card-print-button").attr("target", "_blank");
        } else {
            //toastr.error('Please select student!');
            $("#genearte-id-card-print-button").attr("href", "javascript:;");
            $("#genearte-id-card-print-button").removeAttr("target");
        }
    });

    $(document).on("click", "#genearte-id-card-print-button", function(event) {
        var num = $("input[type=checkbox]:checked").length;

        if (num == 0) {
            return false;
        } else {
            $("#myModal").modal();
            return true;
        }
    });

    //  generate-id-card-print
    $(document).on("click", ".generate-certificate-print", function(event) {
        var url = $("#url").val();
        var id_card = $("#certificate").val();
        var sList = "";
        var len = new Array();
        $("input[type=checkbox]").each(function() {
            if (this.checked) {
                sList += sList == "" ? $(this).val() : "-" + $(this).val();
                len.push($(this).val());
            }
        });
        console.log(sList);

        if (len.length > 8) {
            toastr.warning("You can not select more than 8 student!");
            $("#genearte-certificate-print-button").attr("href", "javascript:;");
            $("#genearte-certificate-print-button").removeAttr("target");
        } else if (sList != "") {
            $("#genearte-certificate-print-button").attr(
                "href",
                url + "/generate-certificate-print/" + sList + "/" + id_card
            );
            $("#genearte-certificate-print-button").attr("target", "_blank");
        } else {
            $("#genearte-certificate-print-button").attr("href", "javascript:;");
            $("#genearte-certificate-print-button").removeAttr("target");
        }

        /*  if (sList != "") {
                                                                                            $("#genearte-certificate-print-button").attr("href", url + "/generate-certificate-print/" + sList + "/" + id_card);
                                                                                            $("#genearte-certificate-print-button").attr("target", '_blank');
                                                                                        } else {
                                                                                            $("#genearte-certificate-print-button").attr("href", '');
                                                                                        } */
    });

    $(document).on("click", ".generate-certificate-print-all", function(event) {
        var url = $("#url").val();
        var id_card = $("#certificate").val();
        var sList = "";
        var len = new Array();
        if ($(this).prop("checked") == true) {
            $("input[type=checkbox]").each(function() {
                if ($(this).val() != "") {
                    sList += sList == "" ? $(this).val() : "-" + $(this).val();
                    len.push($(this).val());
                }
            });
        } else {
            sList = "";
        }

        if (len.length > 8) {
            toastr.warning("You can not select more than 8 student!");
            $("#genearte-certificate-print-button").attr("href", "javascript:;");
            $("#genearte-certificate-print-button").removeAttr("target");
        } else if (sList != "") {
            $("#genearte-certificate-print-button").attr(
                "href",
                url + "/generate-certificate-print/" + sList + "/" + id_card
            );
            $("#genearte-certificate-print-button").attr("target", "_blank");
        } else {
            $("#genearte-certificate-print-button").attr("href", "javascript:;");
            $("#genearte-certificate-print-button").removeAttr("target");
        }

        /*  if (sList != "") {
                                                                                            $("#genearte-certificate-print-button").attr("href", url + "/generate-certificate-print/" + sList + "/" + id_card);
                                                                                            $("#genearte-certificate-print-button").attr("target", '_blank');
                                                                                        } else {
                                                                                            $("#genearte-certificate-print-button").attr("href", '');
                                                                                        } */
    });

    $(document).on("click", "#genearte-id-card-print-button", function(event) {
        var num = $("input[type=checkbox]:checked").length;

        if (num == 0) {
            return false;
        } else {
            return true;
        }
    });

    // Accounts Income Start
    // Add
    $(document).ready(function() {
        $("form#add-income select#payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#bankAccount").removeClass('d-none');
            } else {
                $("#bankAccount").addClass('d-none');
            }
        });
    });

    // Update
    $(document).ready(function() {
        let methodType = $('form#add-income-update select#payment_method').find(':selected').data('string');
        if (methodType == "Bank") {
            $("#bankAccount").removeClass('d-none');
        } else {
            $("#bankAccount").addClass('d-none');
        }

        $("form#add-income-update select#payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#bankAccount").removeClass('d-none');
            } else {
                $("#bankAccount").addClass('d-none');
            }
        });
    });
    // Accounts Income End


    //Accounts Expense Start
    //Add
    $(document).ready(function() {
        $("form#add-expense select#payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#bankAccount").removeClass('d-none');
            } else {
                $("#bankAccount").addClass('d-none');
            }
        });
    });

    //Update
    $(document).ready(function() {
        let methodType = $('form#add-expense-update select#payment_method').find(':selected').data('string');
        if (methodType == "Bank") {
            $("#bankAccount").removeClass('d-none');
        } else {
            $("#bankAccount").addClass('d-none');
        }

        $("form#add-expense-update select#payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#bankAccount").removeClass('d-none');
            } else {
                $("#bankAccount").addClass('d-none');
            }
        });
    });
    //Accounts Expense End

    //Inventory Item Receive Start
    // Add
    $(document).ready(function() {
        $("#item_receive_payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#itemReceivebankAccount").removeClass('d-none');
            } else {
                $("#itemReceivebankAccount").addClass('d-none');
            }
        });

        //Update
        let methodType = $('#edit_payment_method').find(':selected').data('string');
        if (methodType == "Bank") {
            $("#edit_item_receive_bankAccount").removeClass('d-none');
        } else {
            $("#edit_item_receive_bankAccount").addClass('d-none');
        }
    });
    //Inventory Item Receive End 

    //Inventory Item Sell Start
    // Add
    $(document).ready(function() {
        $("#item_sell_payment_method_id").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#add_item_sell_bankAccount").removeClass('d-none');
            } else {
                $("#add_item_sell_bankAccount").addClass('d-none');
            }
        });
    });
    // Update
    $(document).ready(function() {
        let methodType = $('#edit_sell_payment_method').find(':selected').data('string');
        if (methodType == "Bank") {
            $("#edit_item_sell_bankAccount").removeClass('d-none');
        } else {
            $("#edit_item_sell_bankAccount").addClass('d-none');
        }

        $("#edit_sell_payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#edit_item_sell_bankAccount").removeClass('d-none');
            } else {
                $("#edit_item_sell_bankAccount").addClass('d-none');
            }
        });
    });
    //Inventory Item Sell End



    // $(document).ready(function() {
    //     if ($("form#add-expense-update select#payment_method").val() == "3") {
    //         $("form#add-expense-update div#bankAccount").show();
    //     } else {
    //         $("form#add-expense-update div#bankAccount").hide();
    //     }
    // });

    // $(document).ready(function() {
    //     $("form#add-expense-update select#payment_method").on(
    //         "change",
    //         function() {
    //             if ($(this).val() == "3") {
    //                 $("form#add-expense-update div#bankAccount").show();
    //             } else {
    //                 $("form#add-expense-update div#bankAccount").hide();
    //             }
    //         }
    //     );
    // });

    // admission query
    $("#admission-query-store").on("submit", function() {
        var count = 0;

        if ($("#admission-query-store #name").val() == "") {
            count++;
            $("#admission-query-store #nameError").html("Name Field is required");
        } else {
            $("#admission-query-store #nameError").html("");
        }

        if ($("#admission-query-store #source").val() == "") {
            count++;
            $("#admission-query-store #sourceError").html("Source is required");
        } else {
            $("#admission-query-store #sourceError").html("");
        }
        if ($("#admission-query-store #assigned").val() == "") {
            count++;
            $("#admission-query-store #assignedError").html("Assigned is required");
        } else {
            $("#admission-query-store #assignedError").html("");
        }
        if ($("#admission-query-store #reference").val() == "") {
            count++;
            $("#admission-query-store #referenceError").html("Reference is required");
        } else {
            $("#admission-query-store #referenceError").html("");
        }
        if ($("#admission-query-store #class").val() == "") {
            count++;
            $("#admission-query-store #classError").html("Class is required");
        } else {
            $("#admission-query-store #classError").html("");
        }
        if ($("#admission-query-store #no_of_child").val() == "") {
            count++;
            $("#admission-query-store #no_of_childError").html(
                "Number of child is required"
            );
        } else {
            $("#admission-query-store #no_of_childError").html("");
        }

        if (count != 0) {
            return false;
        }
    });

    // teacher upload content

    $("#student").on("click", function() {
        if ($(this).is(":checked")) {
            $("#contentDisabledDiv").removeClass("disabledbutton");
            $("#availableClassesDiv").removeClass("disabledbutton");
        } else {
            $("#contentDisabledDiv").addClass("disabledbutton");
            $("#availableClassesDiv").addClass("disabledbutton");
            $("#all_classes").prop("checked", false); // Unchecks it
        }
    });

    $("#all_classes").on("click", function() {
        if ($(this).is(":checked")) {
            $("#contentDisabledDiv").addClass("disabledbutton");
        } else {
            $("#contentDisabledDiv").removeClass("disabledbutton");
        }
    });

    // student attenance
    $("#mark_holiday").on("click", function() {
        if ($(this).is(":checked")) {
            $("input:radio").removeAttr("checked");
        } else {
            $("input.attendanceP[type=radio]").attr("checked", "checked");
        }
    });

    // biometric attendence sell
    $(document).ready(function() {
        if (sessionStorage.getItem("bio_role") == 2) {
            $("#search_by_staff_id").css("display", "none");
            $("#search_by_name").css("display", "none");
            $(".forStudentWrapper").slideDown();
            $("#selectStaffsDiv").slideUp();
            $("#selectStaffs").find("option").not(":first").remove();
            $("#selectStaffsDiv ul").find("li").not(":first").remove();
        }
    });
    $(document).ready(function() {
        $("body").on("change", "form#biometric #buyer_type", function(e) {
            e.preventDefault();
            var role_id = $(this).val();
            sessionStorage.setItem("bio_role", role_id);
            if (role_id == "2") {
                $("#search_by_staff_id").css("display", "none");
                $("#search_by_name").css("display", "none");
                $(".forStudentWrapper").slideDown();
                $("#selectStaffsDiv").slideUp();
                $("#selectStaffs").find("option").not(":first").remove();
                $("#selectStaffsDiv ul").find("li").not(":first").remove();
            } else if (role_id == "") {
                $(".forStudentWrapper").slideUp();
                $("#selectStaffsDiv").slideUp();
            } else {
                $(".forStudentWrapper").slideUp();
                $("#selectStaffsDiv").slideDown();
                $("#search_by_staff_id").css("display", "block");
                $("#search_by_name").css("display", "block");
                $("#select_student").find("option").not(":first").remove();
                $("#select_student_div ul").find("li").not(":first").remove();

                var url = $("#url").val();
                var formData = {
                    id: $(this).val(),
                };

                console.log(formData);
                // get section for student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "staffNameByRole",
                    success: function(data) {
                        var a = "";
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#selectStaffs").find("option").not(":first").remove();
                                $("#selectStaffsDiv ul").find("li").not(":first").remove();

                                if (role_id == "3") {
                                    $.each(item, function(i, staffs) {
                                        $("#selectStaffs").append(
                                            $("<option>", {
                                                value: staffs.id,
                                                text: staffs.fathers_name,
                                            })
                                        );
                                        $("#selectStaffsDiv ul").append(
                                            "<li data-value='" +
                                            staffs.id +
                                            "' class='option'>" +
                                            staffs.fathers_name +
                                            "</li>"
                                        );
                                    });
                                } else {
                                    $.each(item, function(i, staffs) {
                                        $("#selectStaffs").append(
                                            $("<option>", {
                                                value: staffs.id,
                                                text: staffs.full_name,
                                            })
                                        );
                                        $("#selectStaffsDiv ul").append(
                                            "<li data-value='" +
                                            staffs.id +
                                            "' class='option'>" +
                                            staffs.full_name +
                                            "</li>"
                                        );
                                    });
                                }
                            } else {
                                $("#selectStaffsDiv .current").html("SELECT *");
                                $("#selectStaffs").find("option").not(":first").remove();
                                $("#selectStaffsDiv ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                });
            }
        });
    });
    // inventory sell

    $(document).ready(function() {
        $("body").on("change", "form#item-sell-form #buyer_type", function(e) {
            e.preventDefault();
            var role_id = $(this).val();
            if (role_id == "2") {
                $(".forStudentWrapper").slideDown();
                $("#selectStaffsDiv").slideUp();

                $("#selectStaffs").find("option").not(":first").remove();
                $("#selectStaffsDiv ul").find("li").not(":first").remove();
            } else if (role_id == "") {
                $(".forStudentWrapper").slideUp();
                $("#selectStaffsDiv").slideUp();
            } else {
                $(".forStudentWrapper").slideUp();
                $("#selectStaffsDiv").slideDown();

                $("#select_student").find("option").not(":first").remove();
                $("#select_student_div ul").find("li").not(":first").remove();

                var url = $("#url").val();
                var formData = {
                    id: $(this).val(),
                };

                console.log(formData);
                // get section for student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "staffNameByRole",
                    success: function(data) {
                        var a = "";
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#selectStaffs").find("option").not(":first").remove();
                                $("#selectStaffsDiv ul").find("li").not(":first").remove();

                                if (role_id == "3") {
                                    $.each(item, function(i, staffs) {
                                        $("#selectStaffs").append(
                                            $("<option>", {
                                                value: staffs.id,
                                                text: staffs.fathers_name,
                                            })
                                        );
                                        $("#selectStaffsDiv ul").append(
                                            "<li data-value='" +
                                            staffs.id +
                                            "' class='option'>" +
                                            staffs.fathers_name +
                                            "</li>"
                                        );
                                    });
                                } else {
                                    $.each(item, function(i, staffs) {
                                        $("#selectStaffs").append(
                                            $("<option>", {
                                                value: staffs.id,
                                                text: staffs.full_name,
                                            })
                                        );
                                        $("#selectStaffsDiv ul").append(
                                            "<li data-value='" +
                                            staffs.id +
                                            "' class='option'>" +
                                            staffs.full_name +
                                            "</li>"
                                        );
                                    });
                                }
                            } else {
                                $("#selectStaffsDiv .current").html("SELECT *");
                                $("#selectStaffs").find("option").not(":first").remove();
                                $("#selectStaffsDiv ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                });
            }
        });
    });

    // inventory item  edit
    $(document).ready(function() {
        $("body").on(
            "change",
            "form#edit-item-sell-form #buyer_type",
            function(e) {
                e.preventDefault();
                var role_id = $(this).val();
                if (role_id == "2") {
                    $("#student-div").removeClass("displayNone");
                    $("#staff-div").removeClass("displayBlock");

                    $("#student-div").addClass("displayBlock");
                    $("#staff-div").addClass("displayNone");

                    $("#selectStaffs").find("option").not(":first").remove();
                    $("#staff-div ul").find("li").not(":first").remove();
                    $("#staff-div span").html("name *");
                } else if (role_id == "") {
                    $("#student-div").removeClass("displayBlock");
                    $("#staff-div").removeClass("displayBlock");

                    $("#student-div").addClass("displayNone");
                    $("#staff-div").addClass("displayNone");
                    $("#staff-div span").html("name *");
                } else {
                    $("#student-div").removeClass("displayBlock");
                    $("#staff-div").removeClass("displayNone");

                    $("#student-div").addClass("displayNone");
                    $("#staff-div").addClass("displayBlock");

                    $("#select_student").find("option").not(":first").remove();
                    $("#select_student_div ul").find("li").not(":first").remove();

                    $("#selectStaffs").find("option").not(":first").remove();
                    $("#staff-div ul").find("li").not(":first").remove();
                    $("#staff-div span").html("name *");

                    var url = $("#url").val();
                    var formData = {
                        id: $(this).val(),
                    };

                    console.log(formData);
                    // get section for student
                    $.ajax({
                        type: "GET",
                        data: formData,
                        dataType: "json",
                        url: url + "/" + "staffNameByRole",
                        success: function(data) {
                            var a = "";
                            $.each(data, function(i, item) {
                                if (item.length) {
                                    $("#selectStaffs").find("option").not(":first").remove();
                                    $("#staff-div ul").find("li").not(":first").remove();

                                    if (role_id == "3") {
                                        $.each(item, function(i, staffs) {
                                            $("#selectStaffs").append(
                                                $("<option>", {
                                                    value: staffs.id,
                                                    text: staffs.fathers_name,
                                                })
                                            );
                                            $("#staff-div ul").append(
                                                "<li data-value='" +
                                                staffs.id +
                                                "' class='option'>" +
                                                staffs.fathers_name +
                                                "</li>"
                                            );
                                        });
                                    } else {
                                        $.each(item, function(i, staffs) {
                                            $("#selectStaffs").append(
                                                $("<option>", {
                                                    value: staffs.id,
                                                    text: staffs.full_name,
                                                })
                                            );
                                            $("#staff-div ul").append(
                                                "<li data-value='" +
                                                staffs.id +
                                                "' class='option'>" +
                                                staffs.full_name +
                                                "</li>"
                                            );
                                        });
                                    }
                                } else {
                                    $("#staff-div .current").html("SELECT *");
                                    $("#selectStaffs").find("option").not(":first").remove();
                                    $("#staff-div ul").find("li").not(":first").remove();
                                }
                            });
                        },
                        error: function(data) {
                            console.log("Error:", data);
                        },
                    });
                }
            }
        );
    });

    $(document).ready(function() {
        $("#module_id").on("change", function(e) {
            e.preventDefault();
            $('.submit').hide().prop('disabled', true);

            $("table#language_table tr:not(:first)").remove();

            var url = $("#url").val();
            var lu = $("#language_universal").val();
            var formData = {
                id: $(this).val(),
                lu: lu
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "get-translation-terms",
                success: function(data) {
                    var appendRow = "";
                    $.each(data.en_terms, function(key, value) {
                        console.log(data.terms[key])
                        appendRow = "<tr>";
                        appendRow += "<td>" + value + "</td>";
                        appendRow += "<td>";

                        appendRow += "<div class='input-effect'>";
                        appendRow +=
                            "<input class='primary_input_field type='text' name=\"LU[" +
                            key +
                            "]\" value=\"" +
                            checkNul(data.terms[key], value) +
                            "\">";

                        appendRow += "<span class='focus-border'></span>";

                        appendRow += "</div>";

                        appendRow += "</td>";
                        appendRow += "</tr>";
                        $("table#language_table tr:first").after(appendRow);
                    });
                    $('.submit').show().prop('disabled', false);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    function checkNul(value, en_value){
        if(typeof value == "undefined"){
            return en_value;
        }

        return value;
    }

    // to do list

    $(".complete_task").on("click", function() {
        var url = $("#url").val();
        var id = $(this).val();
        var formData = {
            id: $(this).val(),
        };

        console.log(formData);
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "remove-to-do",
            success: function(data) {
                console.log(data);

                setTimeout(function() {
                    toastr.success(
                        "Operation Success!",
                        "Success Alert", {
                            iconClass: "customer-info",
                        }, {
                            timeOut: 2000,
                        }
                    );
                }, 500);

                $("#to_do_list_div" + id + "").remove();

                $("#toDoListsCompleted").children("div").remove();
            },
            error: function(data) {
                console.log("Error:", data);
            },
        });
    });

    $(document).ready(function() {
        $(".toDoListsCompleted").hide();
    });

    $(document).ready(function() {
        $("#toDoList").on("click", function(e) {
            e.preventDefault();

            if ($(this).hasClass("tr-bg")) {
                $(this).removeClass("tr-bg");
                $(this).addClass("fix-gr-bg");
            }

            if ($("#toDoListsCompleted").hasClass("fix-gr-bg")) {
                $("#toDoListsCompleted").removeClass("fix-gr-bg");
                $("#toDoListsCompleted").addClass("tr-bg");
            }

            $(".toDoList").show();
            $(".toDoListsCompleted").hide();
        });
    });

    $(document).ready(function() {
        $("#toDoListsCompleted").on("click", function(e) {
            e.preventDefault();

            if ($(this).hasClass("tr-bg")) {
                $(this).removeClass("tr-bg");
                $(this).addClass("fix-gr-bg");
            }

            if ($("#toDoList").hasClass("fix-gr-bg")) {
                $("#toDoList").removeClass("fix-gr-bg");
                $("#toDoList").addClass("tr-bg");
            }

            $(".toDoList").hide();
            $(".toDoListsCompleted").show();

            var formData = {
                id: 0,
            };

            var url = $("#url").val();

            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "get-to-do-list",
                success: function(data) {
                    console.log(data);

                    // $.each(data, function(i, array) {

                    // console.log(array);
                    $(".toDoListsCompleted").empty();

                    $.each(data, function(i, value) {
                        var appendRow = "";

                        appendRow +=
                            "<div class='single-to-do d-flex justify-content-between'>";
                        appendRow += "<div>";
                        appendRow += "<h5 class='d-inline'>" + value.title + "</h5>";
                        appendRow += "<p>" + value.date + "</p>";
                        appendRow += "</div>";
                        appendRow += "</div>";

                        $(".toDoListsCompleted").append(appendRow);
                    });
                    // });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    $(document).ready(function() {
        $("#errorMessage1").hide();
        $("#errorMessage2").hide();
    });

    $("form#item-receive-form").on("submit", function() {
        var i = 0;
        var u = 0;
        var q = 0;
        var s = 0;
        var st = 0;
        var ei = 0;
        var p = 0;
        var forFalse1 = 0;
        var forFalse2 = 0;
        var forAll = 0;

        if ($("#expense_head_id").val() == "") {
            ei++;
            forFalse1++;
            forAll++;
        }

        if ($("#supplier_id").val() == "") {
            s++;
            forFalse1++;
            forAll++;
        }

        if ($("#store_id").val() == "") {
            st++;
            forFalse1++;
            forAll++;
        }

        $('form#item-receive-form select[name^="item_id"]').each(function() {
            if ($(this).val() == "") {
                i++;
                forFalse2++;
                forAll++;
            }
        });

        $('form#item-receive-form input[name^="unit_price"]').each(function() {
            if ($(this).val() == "") {
                u++;
                forFalse2++;
                forAll++;
            }
        });

        $('form#item-receive-form input[name^="quantity"]').each(function() {
            if ($(this).val() == "") {
                q++;
                forFalse2++;
                forAll++;
            }
        });

        if ($("#item_receive_payment_method").val() == "") {
            p++;
            forFalse1++;
            forAll++;
        }

        if (ei > 0) {
            $("#expenseError").html("The expense field is required.");
        } else {
            $("#expenseError").html("");
        }

        if (s > 0) {
            $("#supplierError").html("The supplier field is required.");
        } else {
            $("#supplierError").html("");
        }

        if (st > 0) {
            $("#storeError").html("The store field is required.");
        } else {
            $("#storeError").html("");
        }

        if (i > 0) {
            $("#itemError").html("The item fields are required");
        } else {
            $("#itemError").html("");
        }

        if (u > 0) {
            $("#priceError").html("The unit Price fields are required");
        } else {
            $("#priceError").html("");
        }

        if (q > 0) {
            $("#quantityError").html("The item quantity fields are required");
        } else {
            $("#quantityError").html("");
        }

        if (forFalse1 > 0) {
            $("#errorMessage1").show();
        } else {
            $("#errorMessage1").hide();
        }

        if (forFalse2 > 0) {
            $("#errorMessage2").show();
        } else {
            $("#errorMessage2").hide();
        }

        if (p > 0) {
            $("#paymentError").html("The payment field is required.");
        } else {
            $("#paymentError").html("");
        }

        if (forAll > 0) {
            return false;
        }
    });

    $("form#item-sell-form").on("submit", function() {
        var v = 0;
        var ih = 0;
        var c = 0;
        var sc = 0;
        var st = 0;
        var stf = 0;
        var p = 0;
        var i = 0;
        var q = 0;
        var u = 0;

        var forFalse1 = 0;
        var forFalse2 = 0;
        var forAll = 0;

        if ($("#income_head_id").val() == "") {
            ih++;
            forFalse1++;
            forAll++;
        }

        if ($("#buyer_type").val() == "") {
            v++;
            forFalse1++;
            forAll++;
        } else if ($("#buyer_type").val() == "2") {
            if ($("#select_class").val() == "") {
                c++;
                forFalse1++;
                forAll++;
            }

            if ($("#select_section").val() == "") {
                sc++;
                forFalse1++;
                forAll++;
            }

            if ($("#select_student").val() == "") {
                st++;
                forFalse1++;
                forAll++;
            }
        } else {
            if ($("#selectStaffs").val() == "") {
                stf++;
                forFalse1++;
                forAll++;
            }
        }

        $('form#item-sell-form select[name^="item_id"]').each(function() {
            if ($(this).val() == "") {
                i++;
                forFalse2++;
                forAll++;
            }
        });

        $('form#item-sell-form input[name^="unit_price"]').each(function() {
            if ($(this).val() == "") {
                u++;
                forFalse2++;
                forAll++;
            }
        });

        $('form#item-sell-form input[name^="quantity"]').each(function() {
            if ($(this).val() == "") {
                q++;
                forFalse2++;
                forAll++;
            }
        });

        if ($("#item_sell_payment_method_id").val() == "") {
            p++;
            forFalse1++;
            forAll++;
        }

        if (forFalse2 > 0) {
            $("#errorMessage2").show();
        } else {
            $("#errorMessage2").hide();
        }

        if (ih > 0) {
            $("#incomeError").html("The income head fields are required");
        } else {
            $("#incomeError").html("");
        }

        if (i > 0) {
            $("#itemError").html("The item fields are required");
        } else {
            $("#itemError").html("");
        }

        if (u > 0) {
            $("#priceError").html("The unit Price fields are required");
        } else {
            $("#priceError").html("");
        }

        if (q > 0) {
            $("#quantityError").html("The item quantity fields are required");
        } else {
            $("#quantityError").html("");
        }

        if ($("#income_head_id").val() == "") {
            $("#incomeError").html("The income head fields are required");
        } else {
            $("#incomeError").html("");
        }

        if ($("#buyer_type").val() == "") {
            $("#buyerError").html("The sale to field is required");
            $("#studentError").html("");
            $("#sectionError").html("");
            $("#classError").html("");
            $("#nameError").html("");
        } else if ($("#buyer_type").val() == "2") {
            $("#buyerError").html("");
            $("#nameError").html("");

            if ($("#select_class").val() == "") {
                $("#classError").html("The class field is required");
            } else {
                $("#classError").html("");
            }

            if ($("#select_section").val() == "") {
                $("#sectionError").html("The section field is required");
            } else {
                $("#sectionError").html("");
            }

            if ($("#select_student").val() == "") {
                $("#studentError").html("The student field is required");
            } else {
                $("#studentError").html("");
            }
        } else {
            $("#buyerError").html("");
            $("#studentError").html("");
            $("#sectionError").html("");
            $("#classError").html("");

            if ($("#selectStaffs").val() == "") {
                $("#nameError").html("The name field is required");
            } else {
                $("#nameError").html("");
            }
        }

        if (p > 0) {
            $("#paymentError").html("The payment field is required.");
        } else {
            $("#paymentError").html("");
        }

        if (forFalse1 > 0) {
            $("#errorMessage1").show();
        } else {
            $("#errorMessage1").hide();
        }

        if (forAll > 0) {
            return false;
        }
    });

    $("#edit-item-sell-form").on("submit", function() {
        var v = 0;
        var c = 0;
        var ih = 0;
        var sc = 0;
        var st = 0;
        var stf = 0;

        var i = 0;
        var q = 0;
        var u = 0;

        var forFalse1 = 0;
        var forFalse2 = 0;
        var forAll = 0;

        if ($("#income_head_id").val() == "") {
            ih++;
            forFalse1++;
            forAll++;
        }

        if ($("#buyer_type").val() == "") {
            v++;
            forFalse1++;
            forAll++;
        } else if ($("#buyer_type").val() == "2") {
            if ($("#select_class").val() == "") {
                c++;
                forFalse1++;
                forAll++;
            }

            if ($("#select_section").val() == "") {
                sc++;
                forFalse1++;
                forAll++;
            }

            if ($("#select_student").val() == "") {
                st++;
                forFalse1++;
                forAll++;
            }
        } else {
            if ($("#selectStaffs").val() == "") {
                stf++;
                forFalse1++;
                forAll++;
            }
        }

        $('form#edit-item-sell-form select[name^="item_id"]').each(function() {
            if ($(this).val() == "") {
                i++;
                forFalse2++;
                forAll++;
            }
        });

        $('form#edit-item-sell-form input[name^="unit_price"]').each(function() {
            if ($(this).val() == "") {
                u++;
                forFalse2++;
                forAll++;
            }
        });

        $('form#edit-item-sell-form input[name^="quantity"]').each(function() {
            if ($(this).val() == "") {
                q++;
                forFalse2++;
                forAll++;
            }
        });

        if (forFalse2 > 0) {
            $("#errorMessage2").show();
        } else {
            $("#errorMessage2").hide();
        }

        if (ih > 0) {
            $("#incomeError").html("The income head fields are required");
        } else {
            $("#incomeError").html("");
        }

        if (i > 0) {
            $("#itemError").html("The item fields are required");
        } else {
            $("#itemError").html("");
        }

        if (u > 0) {
            $("#priceError").html("The unit Price fields are required");
        } else {
            $("#priceError").html("");
        }

        if (q > 0) {
            $("#quantityError").html("The item quantity fields are required");
        } else {
            $("#quantityError").html("");
        }

        if ($("#income_head_id").val() == "") {
            $("#incomeError").html("The income head fields are required");
        } else {
            $("#incomeError").html("");
        }


        if ($("#buyer_type").val() == "") {
            $("#buyerError").html("The sale to field is required");
            $("#studentError").html("");
            $("#sectionError").html("");
            $("#classError").html("");
            $("#nameError").html("");
        } else if ($("#buyer_type").val() == "2") {
            $("#buyerError").html("");
            $("#nameError").html("");

            if ($("#select_class").val() == "") {
                $("#classError").html("The class field is required");
            } else {
                $("#classError").html("");
            }

            if ($("#select_section").val() == "") {
                $("#sectionError").html("The section field is required");
            } else {
                $("#sectionError").html("");
            }

            if ($("#select_student").val() == "") {
                $("#studentError").html("The student field is required");
            } else {
                $("#studentError").html("");
            }
        } else {
            $("#buyerError").html("");
            $("#studentError").html("");
            $("#sectionError").html("");
            $("#classError").html("");

            if ($("#selectStaffs").val() == "") {
                $("#nameError").html("The name field is required");
            } else {
                $("#nameError").html("");
            }
        }

        if (forFalse1 > 0) {
            $("#errorMessage1").show();
        } else {
            $("#errorMessage1").hide();
        }

        if (forAll > 0) {
            return false;
        }
    });

    // student section info for student admission
    $(document).ready(function() {
        $("#background-color").hide();
        $("#background-image").hide();
    });

    $(document).ready(function() {
        $("#background-type").on("change", function() {
            if ($(this).val() == "") {
                $("#background-color").hide();
                $("#background-image").hide();
            } else if ($(this).val() == "color") {
                $("#background-color").show();
                $("#background-image").hide();
            } else if ($(this).val() == "image") {
                $("#background-color").hide();
                $("#background-image").show();
            }
        });
    });

    lol = (id, role) => {
        var x = $(`#ch${id}`).is(":checked");
        if (x) {
            var status = "on";
        } else {
            var status = "off";
        }

        var formData = {
            id: id,
            status: status,
        };

        var url = $("#url").val();

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "login-access-permission",
            success: function(data) {
                console.log(data);

                setTimeout(function() {
                    toastr.success(
                        "Operation Success!",
                        "Success Alert", {
                            iconClass: "customer-info",
                        }, {
                            timeOut: 2000,
                        }
                    );
                }, 500);
            },
            error: function(data) {
                console.log("no");

                setTimeout(function() {
                    toastr.error("Operation Failed!", "Error Alert", {
                        timeOut: 5000,
                    });
                }, 500);
            },
        });
    };

    changePassword = (id, role) => {
        var formData = {
            id: id,
            role: role,
        };

        var url = $("#url").val();

        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "login-password-reset",
            success: function(data) {
                console.log(data);

                setTimeout(function() {
                    toastr.success(
                        "Success! Password has been reset as default 123456",
                        "Success Alert", {
                            iconClass: "customer-info",
                        }, {
                            timeOut: 2000,
                        }
                    );
                }, 500);
            },
            error: function(data) {
                console.log("no");

                setTimeout(function() {
                    toastr.error("Operation Failed!", "Error Alert", {
                        timeOut: 5000,
                    });
                }, 500);
            },
        });
    };

    (function() {
        $(document).ready(function() {
            $(".switch-input").on("change", function() {
                var id = $(this).closest("tr").siblings().find("#id").val();
                var role = $(this).closest("tr").siblings().find("#role").val();
                //   console.log($(this).parents('tr').attr("id"));
                //   console.log(role);

                if ($(this).is(":checked")) {
                    var status = "on";
                } else {
                    var status = "off";
                }

                var formData = {
                    id: $(this).parents("tr").attr("id"),
                    status: status,
                };

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "login-access-permission",
                    success: function(data) {
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                    },
                    error: function(data) {
                        if (data) {
                            setTimeout(function() {
                                toastr.error("Operation Failed!", "Error Alert", {
                                    timeOut: 5000,
                                });
                            }, 500);
                        }
                    },
                });
            });
        });
    })();

    (function() {
        $(document).ready(function() {
            $(".parent-login-disable").on("change", function() {
                var id = $(this).closest("td").find("#ParentID").val();

                if ($(this).is(":checked")) {
                    var status = "on";
                } else {
                    var status = "off";
                }

                var formData = {
                    id: id,
                    status: status,
                };

                console.log(formData);
                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "login-access-permission",
                    success: function(data) {
                        console.log(data);

                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                    },
                    error: function(data) {
                        if (data) {
                            setTimeout(function() {
                                toastr.error("Operation Failed!", "Error Alert", {
                                    timeOut: 5000,
                                });
                            }, 500);
                        }
                    },
                });
            });
        });
    })();

    (function() {
        $(document).ready(function() {
            $(".switch-input2").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "on";
                } else {
                    var status = "off";
                }

                var formData = {
                    status: status,
                };

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "api-permission-update",
                    success: function(data) {
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "toast-success",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Success!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();

    //website button
    (function() {
        $(document).ready(function() {
            $(".switch-website_btn").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                var formData = {
                    status: status,
                };
                // console.log(formData);

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "change-website-btn-status",
                    success: function(data) {
                        location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();
    //dashboard button
    (function() {
        $(document).ready(function() {
            $(".switch_dashboard_btn").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                var formData = {
                    status: status,
                };
                // console.log(formData);

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "change-dashboard-btn-status",
                    success: function(data) {
                        location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();
    //report button
    (function() {
        $(document).ready(function() {
            $(".switch_report_btn").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                var formData = {
                    status: status,
                };
                // console.log(formData);

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "change-report-btn-status",
                    success: function(data) {
                        location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        console.log(data);
                    },
                    error: function(data) {
                        console.log("no");
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();
    //style button
    (function() {
        $(document).ready(function() {
            $(".switch-style_btn").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                var formData = {
                    status: status,
                };
                // console.log(formData);

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "change-style-btn-status",
                    success: function(data) {
                        location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();
    //ltl_rtl button
    (function() {
        $(document).ready(function() {
            $(".switch_ltl_rtl_btn").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                var formData = {
                    status: status,
                };
                // console.log(formData);

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "change-ltl_rtl-btn-status",
                    success: function(data) {
                        location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();
    //language button
    (function() {
        $(document).ready(function() {
            $(".switch_lang_btn").on("change", function() {
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                var formData = {
                    status: status,
                };
                // console.log(formData);

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "change-language-btn-status",
                    success: function(data) {
                        location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
    })();

    (function() {
        $(document).ready(function() {
            $("#exam_class").on("change", function() {
                var globalType = $("#globalType").val();
                var formData = {
                    id: $(this).val(),
                    globalType : globalType,
                };

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "get-class-subjects",
                    success: function(data) {
                        $("#exam_subejct").empty();

                        var appendRow = "";

                        appendRow += "<div class='col-lg-12'>";
                        appendRow += "<label>Select Subject *</label>";
                        $.each(data, function(i, value) {
                            appendRow += "<div class='input-effect'>";
                            appendRow +=
                                "<input type='checkbox' id='subjects_" +
                                value.id +
                                "' class='common-checkbox subject-checkbox' name='subjects_ids[]' value='" +
                                value.id +
                                "' onclick='selectSubject(" +
                                value.id +
                                ")'>";
                            appendRow +=
                                "<label for='subjects_" +
                                value.id +
                                "'>" +
                                value.subject_name +
                                "</label>";
                            appendRow += "</div>";
                        });

                        appendRow += "<div class='col-lg-12'>";

                        console.log(appendRow);
                        $("#exam_subejct").append(appendRow);
                    },
                    error: function(data) {},
                });
            });
        });
    })();

    (function() {
        $(document).ready(function() {
            $(document).on("change", ".switch-input-staff", function() {
                var id = $(this).closest("tr").attr("id");
                var staff_id = $(this).val();
                var id = staff_id;
                if ($(this).is(":checked")) {
                    var status = "on";
                } else {
                    var status = "off";
                }

                var formData = {
                    status: status,
                    id: id,
                };

                var url = $("#url").val();

                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "staff-disable-enable",
                    success: function(data) {
                        if(data.status == false) {
                           toastr.error(data.message);
                           $(".hr_"+id).prop("checked", false);
                        } else {
                            toastr.success("Operation Success!");
                        }
                    },
                    error: function(data) {
                        ajax_error(data);
                    },
                });
            });
        });
    })();

    selectSubject = (a) => {
        var exam_types = $("input[name='exams_types[]']:checked")
            .map(function() {
                return $(this).val();
            })
            .get();

        if (exam_types.length == 0) {
            $("#error-message").empty();
            var div = "";
            div += "<div class='alert alert-danger'>";

            div += "Exam type is required";
            div += "<br>";

            div += "</div>";

            $("#error-message").append(div);

            $("#subjects_" + a).prop("checked", false);
            return false;
        }

        if ($("#subjects_" + a).is(":checked")) {
            var formData = {
                id: a,
                class_id: $("#exam_class").val(),
                exam_types: $("input[name='exams_types[]']:checked")
                    .map(function() {
                        return $(this).val();
                    })
                    .get(),
            };
            console.log(formData);

            var url = $("#url").val();

            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "subject-assign-check",
                success: function(data) {
                    console.log(data.length);
                    $("#error-message").empty();

                    var div = "";
                    div += "<div class='alert alert-danger'>";

                    $.each(data, function(i, value) {
                        div += "This subject already added for " + value + " exam";
                        div += "<br>";
                    });

                    div += "</div>";

                    if (data.length > 0) {
                        $("#error-message").append(div);
                        $("#subjects_" + a).prop("checked", false);
                    }
                },
                error: function(data) {},
            });
        }
    };

    deleteId = (id) => {
        $("#student_delete_i").val(id);
    };

    enableId = (id) => {
        $("#student_enable_i").val(id);
    };

    // delete item
    GlobaldeleteId = (param) => {
        var url = $("a:focus").attr("data-url");
        var html = ` <div class="modal fade admin-query" id="deleteHomework" >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">${jsLang('delete_item')} </h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <h4>${jsLang('are_you_sure_to_delete')}?</h4>
                                    </div>
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">${jsLang('cancel')}</button>
                                        <a href="${url}" class="text-light">
                                        <button class="primary-btn fix-gr-bg" type="submit">${jsLang('delete')}</button>
                                            </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>`;

        $(html).appendTo("body").modal();
    };

    // select class
    $(document).ready(function() {
        $("#select_academic_year").on("change", function() {
            var url = $("#url").val();
            var formData = {
                year: $(this).val(),
            };

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajax-get-class",
                success: function(data) {
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#select_class").find("option").not(":first").remove();
                            $("#select_class_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, class_info) {
                                $("#select_class").append(
                                    $("<option>", {
                                        value: class_info.id,
                                        text: class_info.class_name,
                                    })
                                );

                                $("#select_class_div ul").append(
                                    "<li data-value='" +
                                    class_info.id +
                                    "' class='option'>" +
                                    class_info.class_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_class_div .current").html("SELECT CLASS *");
                            $("#select_class").find("option").not(":first").remove();
                            $("#select_class_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // fees master find type
    $(document).ready(function() {
        $("#infix_class").on("change", function() {
            var url = $("#url").val();
            // console.log($(this).val());
            var formData = {
                id: $(this).val(),
            };

            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "feescollection/fees-master-section",
                success: function(data) {
                    //console.log(data);
                    var a = "";
                    $.each(data, function(i, item) {
                        //console.log(item);
                        if (item.length) {
                            $("#select_fees_type").find("option").not(":first").remove();
                            $("#select_fees_type_div ul").find("li").not(":first").remove();

                            $.each(item, function(i, section) {
                                $("#select_fees_type").append(
                                    $("<option>", {
                                        value: section.assign_id,
                                        text: section.fees_type,
                                    })
                                );

                                $("#select_fees_type_div ul").append(
                                    "<li data-value='" +
                                    section.assign_id +
                                    "' class='option'>" +
                                    section.fees_type +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#select_fees_type_div .current").html("FEES TYPE *");
                            $("#select_fees_type").find("option").not(":first").remove();
                            $("#select_fees_type_div ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });

    // academic year wise get class
    $(document).ready(function() {
        $("#academic_year").on(
            "change",
            function() {
                var url = $("#url").val();
                var i = 0;
                var formData = {
                    id: $(this).val(),
                };
                

                // get section for student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "academic-year-get-class",

                    beforeSend: function() {
                        $('#select_class_loader').addClass('pre_loader');
                        $('#select_class_loader').removeClass('loader');
                    },

                    success: function(data) {
                        $("#classSelectStudent").empty().append(
                            $("<option>", {
                                value:  '',
                                text: window.jsLang('select_class') + ' *',
                            })
                        );

                        if (data[0].length) {
                            $.each(data[0], function(i, className) {
                                $("#classSelectStudent").append(
                                    $("<option>", {
                                        value: className.id,
                                        text: className.class_name,
                                    })
                                );
                            });
                        } 
                        $('#classSelectStudent').niceSelect('update');
                        $('#classSelectStudent').trigger('change');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    },
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#select_class_loader').removeClass('pre_loader');
                            $('#select_class_loader').addClass('loader');
                        }
                    }
                });
            }
        );
    });

    // student section select sction for sibling
    // Comment By Amit Saha For Student Admission Sendig Multiple Request
    // $(document).ready(function() {
    //     $("form#student_form #academic_year, form#infix_form #academic_year").on(
    //         "change",
    //         function() {
    //             var url = $("#url").val();
    //             var i = 0;
    //             var formData = {
    //                 id: $(this).val(),
    //             };
                

    //             // get section for student
    //             $.ajax({
    //                 type: "GET",
    //                 data: formData,
    //                 dataType: "json",
    //                 url: url + "/" + "academic-year-get-class",

    //                 beforeSend: function() {
    //                     $('#select_class_loader').addClass('pre_loader');
    //                     $('#select_class_loader').removeClass('loader');
    //                 },

    //                 success: function(data) {
    //                     $("#classSelectStudent").empty().append(
    //                         $("<option>", {
    //                             value:  '',
    //                             text: window.jsLang('select_class') + ' *',
    //                         })
    //                     );

    //                     if (data[0].length) {
    //                         $.each(data[0], function(i, className) {
    //                             $("#classSelectStudent").append(
    //                                 $("<option>", {
    //                                     value: className.id,
    //                                     text: className.class_name,
    //                                 })
    //                             );
    //                         });
    //                     } 
    //                     $('#classSelectStudent').niceSelect('update');
    //                     $('#classSelectStudent').trigger('change');
    //                 },
    //                 error: function(data) {
    //                     console.log('Error:', data);
    //                 },
    //                 complete: function() {
    //                     i--;
    //                     if (i <= 0) {
    //                         $('#select_class_loader').removeClass('pre_loader');
    //                         $('#select_class_loader').addClass('loader');
    //                     }
    //                 }
    //             });
    //         }
    //     );
    // });

    // currency info
    $(document).ready(function() {
        $("#uqinue_fine_list").on("change", function() {
            var url = $("#url").val();
            console.log($("#uqinue_fine_list").val());
            if ($("#uqinue_fine_list").val() == "create_new") {
                $("#name_create_new").css("display", "flex");
            } else {
                $("#name_create_new").css("display", "none");
            }
        });
    });

    //add lesson_Plan

    validateLssonPlan = () => {
        var lesson = document.getElementById("select_lesson").value;
        var topic = document.getElementById("select_topic").value;


        var i = 0;
        if (lesson == "") {
            document.getElementById("lesson_error").innerHTML =
                "Lesson field is required";
            i++;
        } else {
            document.getElementById("lesson_error").innerHTML = "";
        }
        if (topic == "") {
            document.getElementById("topic_error").innerHTML =
                "Topic field is required";
            i++;
        } else {
            document.getElementById("topic_error").innerHTML = "";
        }

        if (i > 0) {
            return false;
        }
    };



    // 100 Percent Mark Start
    $(document).ready(function() {
        $("#examTypeId").on("change", function() {
            var url = $("#url").val();
            var i = 0;

            var formData = {
                id: $(this).val(),
            };

            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSubjectFromExamType",

                beforeSend: function() {
                    $('#selectExamBaseSubjectLoader').addClass('pre_loader');
                    $('#selectExamBaseSubjectLoader').removeClass('loader');
                },
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#examTypeBaseSubjectList").find("option").not(":first").remove();
                            $("#examTypeBaseSubjectDiv ul").find("li").not(":first").remove();

                            $.each(item, function(i, subjects) {
                                console.log(subjects);
                                $("#examTypeBaseSubjectList").append(
                                    $("<option>", {
                                        value: subjects.subject_details.id,
                                        text: subjects.subject_details.subject_name,
                                    })
                                );
                                $("#examTypeBaseSubjectDiv ul").append(
                                    "<li data-value='" +
                                    subjects.subject_details.id +
                                    "' class='option'>" +
                                    subjects.subject_details.subject_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#examTypeBaseSubjectDiv .current").html("SUBJECT *");
                            $("#examTypeBaseSubjectList").find("option").not(":first").remove();
                            $("#examTypeBaseSubjectDiv ul").find("li").not(":first").remove();
                        }
                    });
                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#selectExamBaseSubjectLoader').removeClass('pre_loader');
                        $('#selectExamBaseSubjectLoader').addClass('loader');
                    }
                }
            });
        });
    });
    // 100 Percent Mark Start



        // Globla Assign subject
        $(document).ready(function() {
            $("#addNewGlobalSubject").on("click", function() {
                var url = $("#url").val();
                var count = $("#assign-subject").children().length;
                var divCount = count + 1;
    
                // get section for student
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: url + "/" + "global-assign-subject-get-by-ajax",
                    success: function(data) {
                        var subject_teacher = "";
                        subject_teacher +=
                            "<div class='col-lg-12 mb-30' id='assign-subject-" +
                            divCount +
                            "'>";
                        subject_teacher += "<div class='row'>";
                        subject_teacher += "<div class='col-lg-5 mt-30-md'>";
                        subject_teacher +=
                            "<select class='primary_select' name='subjects[]' style='display:none'>";
                        subject_teacher +=
                            "<option data-display='"+window.jsLang('select_subject')+"'  value=''>"+window.jsLang('select_subject')+"</option>";
                        $.each(data[0], function(key, subject) {
                            subject_teacher +=
                                "<option value=" +
                                subject.id +
                                ">" +
                                subject.subject_name +
                                "</option>";
                        });
                        subject_teacher += "</select>";
    
                        subject_teacher +=
                            "<div class='nice-select primary_select form-control' tabindex='0'>";
                        subject_teacher += "<span class='current'>"+window.jsLang('select_subject')+"</span>";
                        subject_teacher +=
                            "<div class='nice-select-search-box'><input type='text' class='nice-select-search' placeholder='Search...'></div>";
                        subject_teacher += "<ul class='list'>";
                        subject_teacher +=
                            "<li data-value='' data-display='"+window.jsLang('select_subject')+"' class='option selected'>"+window.jsLang('select_subject')+"</li>";
                        $.each(data[0], function(key, subject) {
                            subject_teacher +=
                                "<li data-value=" +
                                subject.id +
                                " class='option'>" +
                                subject.subject_name +
                                "</li>";
                        });
                        subject_teacher += "</ul>";
                        subject_teacher += "</div>";
                        subject_teacher += "</div>";
                        subject_teacher += "<div class='col-lg-5 mt-30-md'>";
                        subject_teacher +=
                            "<select class='primary_select form-control' name='teachers[]' style='display:none'>";
                        subject_teacher +=
                            "<option data-display='"+window.jsLang('select_teacher')+"' value=''>"+window.jsLang('select_teacher')+"</option>";
                        $.each(data[1], function(key, teacher) {
                            subject_teacher +=
                                "<option value=" +
                                teacher.id +
                                ">" +
                                teacher.full_name +
                                "</option>";
                        });
                        subject_teacher += "</select>";
                        subject_teacher +=
                            "<div class='nice-select primary_select form-control' tabindex='0'>";
                        subject_teacher += "<span class='current'>"+window.jsLang('select_teacher')+"</span>";
                        subject_teacher +=
                            "<div class='nice-select-search-box'><input type='text' class='nice-select-search' placeholder='Search...'></div>";
                        subject_teacher += "<ul class='list'>";
                        subject_teacher +=
                            "<li data-value='' data-display='"+window.jsLang('select_teacher')+"' class='option selected'>"+window.jsLang('select_teacher')+"</li>";
                        $.each(data[1], function(key, teacher) {
                            subject_teacher +=
                                "<li data-value=" +
                                teacher.id +
                                " class='option'>" +
                                teacher.full_name +
                                "</li>";
                        });
                        subject_teacher += "</ul>";
                        subject_teacher += "</div>";
                        subject_teacher += "</div>";
                        subject_teacher += "<div class='col-lg-2'>";
                        subject_teacher +=
                            "<button class='primary-btn icon-only fix-gr-bg' id='removeSubject' onclick='deleteSubject(" +
                            divCount +
                            ")' type='button'>";
                        subject_teacher += "<span class='ti-trash' ></span>";
                        subject_teacher += "</button>";
                        subject_teacher += "</div>";
                        subject_teacher += "</div>";
                        subject_teacher += "</div>";
                        $("#assign-subject").append(subject_teacher);
                    },
                    error: function(data) {
                        // console.log("Error:", data);
                    },
                });
            });
        });

        //get academic year by school id
        $(document).ready(function() {
            $("form#parent-registration #select-school").on("change", function() {
                var url = $("#url").val();
        console.log(url);
                var formData = {
                    id: $(this).val(),
                };
                // get section for student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "ajax-get-class-academicyear",
                    success: function(data) {
                        console.log(data);
        
                        var a = "";
        
                        if (data[1].length) {
                            $("#select-academic-year").find("option").not(":first").remove();
                            $("#academic-year-div ul").find("li").not(":first").remove();
        
                            $.each(data[1], function(i, academicYear) {
                                $("#select-academic-year").append(
                                    $("<option>", {
                                        value: academicYear.id,
                                        text: academicYear.year,
                                        text: academicYear.title,
                                    })
                                );
        
                                $("#academic-year-div ul").append(
                                    "<li data-value='" +
                                    academicYear.id +
                                    "' class='option'>" +
                                    academicYear.year +
                                    " [" +
                                    academicYear.title +
                                    "]" +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#academic-year-div .current").html("SELECT ACADEMIC YEAR *");
                            $("#select-academic-year").find("option").not(":first").remove();
                            $("#academic-year-div ul").find("li").not(":first").remove();
                        }
                    },
                    error: function(data) {
                        // console.log('Error:', data);
                    },
                });
            });
        });

        //get class by academic & school id
        $(document).ready(function() {
            $("form#parent-registration #select-academic-year").on(
                "change",
                function() {
                    var url = $("#url").val();
                    var i = 0;
                    var formData = {
                        id: $(this).val(),
                    };
                    // get section for student
                    $.ajax({
                        type: "GET",
                        data: formData,
                        dataType: "json",
                        url: url + "/" + "ajax-get-classes",
        
                        beforeSend: function() {
                            $('#select_class_loader').addClass('pre_loader');
                            $('#select_class_loader').removeClass('loader');
                        },
        
                        success: function(data) {
                            var a = "";
                            // $.each(data[0], function (i, item) {
        
                            if (data[0].length) {
                                $("#select-class").find("option").not(":first").remove();
                                $("#class-div ul").find("li").not(":first").remove();
        
                                $.each(data[0], function(i, className) {
                                    $("#select-class").append(
                                        $("<option>", {
                                            value: className.id,
                                            text: className.class_name,
                                        })
                                    );
        
                                    $("#class-div ul").append(
                                        "<li data-value='" +
                                        className.id +
                                        "' class='option'>" +
                                        className.class_name +
                                        "</li>"
                                    );
                                });
                            } else {
                                $("#class-div .current").html("SELECT CLASS *");
                                $("#select-class").find("option").not(":first").remove();
                                $("#class-div ul").find("li").not(":first").remove();
                            }
                        },
                        error: function(data) {
                            // console.log('Error:', data);
                        },
                        complete: function() {
                            i--;
                            if (i <= 0) {
                                $('#select_class_loader').removeClass('pre_loader');
                                $('#select_class_loader').addClass('loader');
                            }
                        }
                    });
                }
            );
        });

        // get section by academic , school and class id 

        $(document).ready(function() {
            $("form#parent-registration #select-class").on("change", function() {
                var url = $("#url").val();
                var i = 0;
        
                var formData = {
                    id: $(this).val(),
                };
                // get section for student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "ajax-get-sections",
        
                    beforeSend: function() {
                        $('#select_section_loader').addClass('pre_loader');
                        $('#select_section_loader').removeClass('loader');
                    },
        
                    success: function(data) {
                        console.log(data);
        
                        var a = "";
                        // $.each(data[0], function (i, item) {
        
                        if (data.length) {
                            $("#select-section").find("option").not(":first").remove();
                            $("#section-div ul").find("li").not(":first").remove();
        
                            $.each(data, function(i, className) {
                                $("#select-section").append(
                                    $("<option>", {
                                        value: className.id,
                                        text: className.section_name,
                                    })
                                );
        
                                $("#section-div ul").append(
                                    "<li data-value='" +
                                    className.id +
                                    "' class='option'>" +
                                    className.section_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#section-div .current").html("SELECT SECTION *");
                            $("#select-section").find("option").not(":first").remove();
                            $("#section-div ul").find("li").not(":first").remove();
                        }
                    },
                    error: function(data) {
                        // console.log('Error:', data);
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
        });
        


 

    ("use strict");
})(jQuery);