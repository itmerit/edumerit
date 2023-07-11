
// student section select sction for sibling
// $(document).ready(function() {
//     $("form#parent-registration #select-school").on("change", function() {
//         var url = $("#url").val();

//         var formData = {
//             id: $(this).val(),
//         };
//         // get section for student
//         $.ajax({
//             type: "GET",
//             data: formData,
//             dataType: "json",
//             url: url + "/" + "parentregistration/get-class-academicyear",
//             success: function(data) {
//                 console.log(data);

//                 var a = "";

//                 if (data[1].length) {
//                     $("#select-academic-year").find("option").not(":first").remove();
//                     $("#academic-year-div ul").find("li").not(":first").remove();

//                     $.each(data[1], function(i, academicYear) {
//                         $("#select-academic-year").append(
//                             $("<option>", {
//                                 value: academicYear.id,
//                                 text: academicYear.year,
//                                 text: academicYear.title,
//                             })
//                         );

//                         $("#academic-year-div ul").append(
//                             "<li data-value='" +
//                             academicYear.id +
//                             "' class='option'>" +
//                             academicYear.year +
//                             " [" +
//                             academicYear.title +
//                             "]" +
//                             "</li>"
//                         );
//                     });
//                 } else {
//                     $("#academic-year-div .current").html("SELECT ACADEMIC YEAR *");
//                     $("#select-academic-year").find("option").not(":first").remove();
//                     $("#academic-year-div ul").find("li").not(":first").remove();
//                 }
//             },
//             error: function(data) {
//                 // console.log('Error:', data);
//             },
//         });
//     });
// });

// student section select sction for sibling
// $(document).ready(function() {
//     $("form#parent-registration #select-class").on("change", function() {
//         var url = $("#url").val();
//         var i = 0;

//         var formData = {
//             id: $(this).val(),
//         };
//         // get section for student
//         $.ajax({
//             type: "GET",
//             data: formData,
//             dataType: "json",
//             url: url + "/" + "parentregistration/get-section",

//             beforeSend: function() {
//                 $('#select_section_loader').addClass('pre_loader');
//                 $('#select_section_loader').removeClass('loader');
//             },

//             success: function(data) {
//                 console.log(data);

//                 var a = "";
//                 // $.each(data[0], function (i, item) {

//                 if (data.length) {
//                     $("#select-section").find("option").not(":first").remove();
//                     $("#section-div ul").find("li").not(":first").remove();

//                     $.each(data, function(i, className) {
//                         $("#select-section").append(
//                             $("<option>", {
//                                 value: className.id,
//                                 text: className.section_name,
//                             })
//                         );

//                         $("#section-div ul").append(
//                             "<li data-value='" +
//                             className.id +
//                             "' class='option'>" +
//                             className.section_name +
//                             "</li>"
//                         );
//                     });
//                 } else {
//                     $("#section-div .current").html("SELECT SECTION *");
//                     $("#select-section").find("option").not(":first").remove();
//                     $("#section-div ul").find("li").not(":first").remove();
//                 }
//             },
//             error: function(data) {
//                 // console.log('Error:', data);
//             },
//             complete: function() {
//                 i--;
//                 if (i <= 0) {
//                     $('#select_section_loader').removeClass('pre_loader');
//                     $('#select_section_loader').addClass('loader');
//                 }
//             }
//         });
//     });
// });

// student section select sction for sibling
// $(document).ready(function() {
//     $("form#parent-registration #select-academic-year").on("change", function() {
//         var url = $("#url").val();
//         var i = 0;
//         var school_id = '';
//         if ($("#select-school").length) {
//             school_id = $("#select-school").val();
//         }

//         var formData = {
//             id: $(this).val(),
//             school_id: school_id,
//         };
//         // get section for student
//         $.ajax({
//             type: "GET",
//             data: formData,
//             dataType: "json",
//             url: url + "/" + "parentregistration/get-classes",

//             beforeSend: function() {
//                 $('#select_class_loader').addClass('pre_loader');
//                 $('#select_class_loader').removeClass('loader');
//             },

//             success: function(data) {
//                 console.log(data);

//                 var a = "";
//                 // $.each(data[0], function (i, item) {

//                 if (data[0].length) {
//                     $("#select-class").find("option").not(":first").remove();
//                     $("#class-div ul").find("li").not(":first").remove();

//                     $.each(data[0], function(i, className) {
//                         $("#select-class").append(
//                             $("<option>", {
//                                 value: className.id,
//                                 text: className.class_name,
//                             })
//                         );

//                         $("#class-div ul").append(
//                             "<li data-value='" +
//                             className.id +
//                             "' class='option'>" +
//                             className.class_name +
//                             "</li>"
//                         );
//                     });
//                 } else {
//                     $("#class-div .current").html("SELECT CLASS *");
//                     $("#select-class").find("option").not(":first").remove();
//                     $("#class-div ul").find("li").not(":first").remove();
//                 }
//             },
//             error: function(data) {
//                 // console.log('Error:', data);
//             },
//             complete: function() {
//                 i--;
//                 if (i <= 0) {
//                     $('#select_class_loader').removeClass('pre_loader');
//                     $('#select_class_loader').addClass('loader');
//                 }
//             }
//         });
//     });
// });

// student section select sction for sibling
// $(document).ready(function() {
//     $("form#parent-registration #select-academic-year-school").on(
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
//                 url: url + "/" + "parentregistration/get-classes",

//                 beforeSend: function() {
//                     $('#select_class_loader').addClass('pre_loader');
//                     $('#select_class_loader').removeClass('loader');
//                 },

//                 success: function(data) {
//                     var a = "";
//                     // $.each(data[0], function (i, item) {

//                     if (data[0].length) {
//                         $("#select-class").find("option").not(":first").remove();
//                         $("#class-div ul").find("li").not(":first").remove();

//                         $.each(data[0], function(i, className) {
//                             $("#select-class").append(
//                                 $("<option>", {
//                                     value: className.id,
//                                     text: className.class_name,
//                                 })
//                             );

//                             $("#class-div ul").append(
//                                 "<li data-value='" +
//                                 className.id +
//                                 "' class='option'>" +
//                                 className.class_name +
//                                 "</li>"
//                             );
//                         });
//                     } else {
//                         $("#class-div .current").html("SELECT CLASS *");
//                         $("#select-class").find("option").not(":first").remove();
//                         $("#class-div ul").find("li").not(":first").remove();
//                     }
//                 },
//                 error: function(data) {
//                     // console.log('Error:', data);
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

function getAge(dob) {
    return ~~((new Date() - new Date(dob)) / 31556952000);
}
$("dob").val();
$("input.mydob").change(function() {
    $("#age").val(getAge($(this).val()));
});

// student email unique check

$(document).ready(function() {
    $("form#parent-registration #student_email").on("keyup", function() {
        var url = $("#url").val();

        if ($(this).val() == "") {
            $("form#parent-registration #student_email_error").html("");
            return false;
        } else {
            if ($("#select-school").length) {
                if ($("#select-school").val() == "") {
                    alert("Please select school");
                    $(this).val("");
                    return false;
                } else {
                    school_id = $("#select-school").val();
                }
            } else {
                school_id = 1;
            }
        }

        var formData = {
            id: $(this).val(),
            school_id: school_id,
        };
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "parentregistration/check-student-email",
            success: function(data) {
                console.log(data);
                if (data == 1) {
                    $("form#parent-registration #student_email_error").html(
                        "The email already used."
                    );
                } else {
                    $("form#parent-registration #student_email_error").html("");
                }
            },
            error: function(data) {
                // console.log('Error:', data);
            },
        });
    });
});

// student email unique check

$(document).ready(function() {
    $("form#parent-registration #student_mobile").on("keyup", function() {
        var url = $("#url").val();

        if ($(this).val() == "") {
            $("form#parent-registration #student_mobile_error").html("");
            return false;
        } else {
            if ($("#select-school").length) {
                if ($("#select-school").val() == "") {
                    alert("Please select school");
                    $(this).val("");
                    return false;
                } else {
                    school_id = $("#select-school").val();
                }
            } else {
                school_id = 1;
            }
        }

        var formData = {
            id: $(this).val(),
            school_id: school_id,
        };
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "parentregistration/check-student-mobile",
            success: function(data) {
                console.log(data);
                if (data == 1) {
                    $("form#parent-registration #student_mobile_error").html(
                        "The mobile no already used."
                    );
                } else {
                    $("form#parent-registration #student_mobile_error").html("");
                }
            },
            error: function(data) {
                // console.log('Error:', data);
            },
        });
    });
});

// guardian email unique check

$(document).ready(function() {
    $("form#parent-registration #guardian_email").on("keyup", function() {
        var url = $("#url").val();

        if ($(this).val() == "") {
            $("form#parent-registration #guardian_email_error").html("");
            return false;
        } else {
            if ($("#select-school").length) {
                if ($("#select-school").val() == "") {
                    alert("Please select school");
                    $(this).val("");
                    return false;
                } else {
                    school_id = $("#select-school").val();
                }
            } else {
                school_id = 1;
            }
        }

        var formData = {
            id: $(this).val(),
            school_id: school_id,
        };
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "parentregistration/check-guardian-email",
            success: function(data) {
                console.log(data);
                if (data == 1) {
                    $("form#parent-registration #guardian_email_error").html(
                        "The email no already used."
                    );
                } else {
                    $("form#parent-registration #guardian_email_error").html("");
                }
            },
            error: function(data) {
                // console.log('Error:', data);
            },
        });
    });
});

// guardian email unique check

$(document).ready(function() {
    $("form#parent-registration #guardian_mobile").on("keyup", function() {
        var url = $("#url").val();

        if ($(this).val() == "") {
            $("form#parent-registration #guardian_mobile_error").html("");
            return false;
        } else {
            if ($("#select-school").length) {
                if ($("#select-school").val() == "") {
                    alert("Please select school");
                    $(this).val("");
                    return false;
                } else {
                    school_id = $("#select-school").val();
                }
            } else {
                school_id = 1;
            }
        }

        var formData = {
            id: $(this).val(),
            school_id: school_id,
        };
        // get section for student
        $.ajax({
            type: "GET",
            data: formData,
            dataType: "json",
            url: url + "/" + "parentregistration/check-guardian-mobile",
            success: function(data) {
                console.log(data);
                if (data == 1) {
                    $("form#parent-registration #guardian_mobile_error").html(
                        "The mobile no already used."
                    );
                } else {
                    $("form#parent-registration #guardian_mobile_error").html("");
                }
            },
            error: function(data) {
                // console.log('Error:', data);
            },
        });
    });
});

// parent registration submit validation check

// $(document).ready(function() {
//     $("form#parent-registration").on("submit", function() {
//         var count = 0;
//
//         if ($("#student_email").val() != "") {
//             if ($("#student_email_error").text() == "") {} else {
//                 console.log("s e");
//                 count++;
//             }
//         }
//
//         if ($("#student_mobile").val() != "") {
//             if ($("#student_mobile_error").text() == "") {} else {
//                 console.log("s m");
//                 count++;
//             }
//         }
//
//         if ($("#guardian_email").val() != "") {
//             if ($("#guardian_email_error").text() == "") {} else {
//                 console.log("G e");
//                 count++;
//             }
//         }
//         // }else{
//         //     count++;
//         // $('#guardian_email_error').html('Guardian email is required.');
//         // }
//
//         if ($("#guardian_mobile").val() != "") {
//             if ($("#guardian_mobile_error").text() == "") {} else {
//                 console.log("G b");
//                 count++;
//             }
//         }
//         // }else{
//         //     count++;
//         //     $('#guardian_mobile_error').html('Guardian mobile is required.');
//         // }
//
//         console.log(count);
//
//         if (count > 0) {
//             return false;
//         }
//     });
// });