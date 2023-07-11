(function($) {
  $(document).ready(function(){
    // metisMenu

    var metismenu = $("#sidebar_menu");
    if (metismenu.length) {
      metismenu.metisMenu();
    }

    $(".open_miniSide").click(function () {
      $(".sidebar").toggleClass("mini_sidebar");
      $("#main-content").toggleClass("mini_main_content");
    });

    $(".open_miniSide").on("click", function () {
      $(".sidebar").toggleClass("ps ps--active-y");
    });
  });
  $(document).click(function (event) {
    if (!$(event.target).closest(".sidebar,.sidebar_icon  ").length) {
      $("body").find(".sidebar").removeClass("active");
    }
  });
  $(document).click(function(event) {
    if (!$(event.target).closest("#sidebar,#sidebarCollapse").length) {
      $("body").find("#sidebar").removeClass("active");
    }
  });

  $(".open_miniSide").click(function () {
    $(".sidebar").toggleClass("mini_sidebar");
  });



  $(document).click(function(event) {
    if (!$(event.target).closest("#navbarSupportedContent,.client_thumb_btn")
        .length
    ) {
      $("body").find("#navbarSupportedContent").removeClass("show");
    }
  });

  if ($("#table_id, .school-table-data").length) {

    window.table = $("#table_id, .school-table-data").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        title: $("#logo_title").val(),
        titleAttr: window.jsLang('copy_table'),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          margin: [10, 10, 10, 0],
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          title: $("#logo_title").val(),
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
          orientation: "landscape",
          pageSize: "A4",
          margin: [0, 0, 0, 12],
          alignment: "center",
          header: true,
          customize: function(doc) {
            doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
      order: [0, 'desc']
    });

  }


  if ($("#tableWithoutSort").length) {
    $("#tableWithoutSort").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        title: $("#logo_title").val(),
        titleAttr: window.jsLang('copy_table'),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          margin: [10, 10, 10, 0],
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          title: $("#logo_title").val(),
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
          orientation: "landscape",
          pageSize: "A4",
          margin: [0, 0, 0, 12],
          alignment: "center",
          header: true,
          customize: function(doc) {
            doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
      ordering: false,
    });
  }

  if ($("#noSearch").length) {
    $("#noSearch").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        title: $("#logo_title").val(),
        titleAttr: window.jsLang('copy_table'),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          margin: [10, 10, 10, 0],
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          title: $("#logo_title").val(),
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
          orientation: "landscape",
          pageSize: "A4",
          margin: [0, 0, 0, 12],
          alignment: "center",
          header: true,
          customize: function(doc) {
            doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
      ordering: false,
      searching: false,
    });
  }

  //attendance

  /*-------------------------------------------------------------------------------
       Nice Select
     -------------------------------------------------------------------------------*/
  if ($(".primary_select").length) {
    $(".primary_select").niceSelect();
  }
  if ($(".niceSelect").length) {
    $(".nice_Select3").niceSelect();
  }

  function slideToggle(clickBtn, toggleDiv) {
    clickBtn.on("click", function() {
      toggleDiv.stop().slideToggle("slow");
    });
  }

  function removeDiv(clickBtn, toggleDiv) {
    clickBtn.on("click", function() {
      toggleDiv.hide("slow", function() {
        toggleDiv.remove();
      });
    });
  }

  slideToggle($("#barChartBtn"), $("#barChartDiv"));
  removeDiv($("#barChartBtnRemovetn"), $("#incomeExpenseDiv"));
  slideToggle($("#areaChartBtn"), $("#areaChartDiv"));
  removeDiv($("#areaChartBtnRemovetn"), $("#incomeExpenseSessionDiv"));

  /*-------------------------------------------------------------------------------
       Start Primary Button Ripple Effect
     -------------------------------------------------------------------------------*/
  $(".primary-btn").on("click", function(e) {
    // Remove any old one
    $(".ripple").remove();

    // Setup
    var primaryBtnPosX = $(this).offset().left,
        primaryBtnPosY = $(this).offset().top,
        primaryBtnWidth = $(this).width(),
        primaryBtnHeight = $(this).height();

    // Add the element
    $(this).prepend("<span class='ripple'></span>");

    // Make it round!
    if (primaryBtnWidth >= primaryBtnHeight) {
      primaryBtnHeight = primaryBtnWidth;
    } else {
      primaryBtnWidth = primaryBtnHeight;
    }

    // Get the center of the element
    var x = e.pageX - primaryBtnPosX - primaryBtnWidth / 2;
    var y = e.pageY - primaryBtnPosY - primaryBtnHeight / 2;

    // Add the ripples CSS and start the animation
    $(".ripple")
        .css({
          width: primaryBtnWidth,
          height: primaryBtnHeight,
          top: y + "px",
          left: x + "px",
        })
        .addClass("rippleEffect");
  });
  /*-------------------------------------------------------------------------------
       End Primary Button Ripple Effect
     -------------------------------------------------------------------------------*/

  /*-------------------------------------------------------------------------------
       Start Add Earnings
     -------------------------------------------------------------------------------*/
  // $('#addEarnings').on('click', function() {
  // 	$('#addEarningsTableBody').append(
  // 		'<tr>' +
  // 			'<td width="80%" class="pr-30 pt-20">' +
  // 			'<div class="input-effect mt-10">' +
  // 			'<input class="primary_input_field" type="text" id="searchByFileName" name="earningsType[]">' +
  // 			'<label for="searchByFileName">Type</label>' +
  // 			'<span class="focus-border"></span>' +
  // 			'</div>' +
  // 			'</td>' +
  // 			'<td width="20%" class="pt-20">' +
  // 			'<div class="input-effect mt-10">' +
  // 			'<input class="primary_input_field" type="text" id="searchByFileName" name="earningsValue[]">' +
  // 			'<label for="searchByFileName">Value</label>' +
  // 			'<span class="focus-border"></span>' +
  // 			'</div>' +
  // 			'</td>' +
  // 			'<td width="10%" class="pt-30">' +
  // 			'<button class="primary-btn icon-only fix-gr-bg close-earnings">' +
  // 			'<span class="ti-close"></span>' +
  // 			'</button>' +
  // 			'</td>' +
  // 			'</tr>'
  // 	);
  // });

  /*-------------------------------------------------------------------------------
       End Add Earnings
     -------------------------------------------------------------------------------*/

  /*-------------------------------------------------------------------------------
       Start Add Deductions
     -------------------------------------------------------------------------------*/
  $("#addDeductions").on("click", function() {
    $("#addDeductionsTableBody").append(
        "<tr>" +
        '<td width="80%" class="pr-30 pt-20">' +
        '<div class="input-effect mt-10">' +
        '<input class="primary_input_field" type="text" id="searchByFileName">' +
        '<label for="searchByFileName">Type</label>' +
        '<span class="focus-border"></span>' +
        "</div>" +
        "</td>" +
        '<td width="20%" class="pt-20">' +
        '<div class="input-effect mt-10">' +
        '<input class="primary_input_field" type="text" id="searchByFileName">' +
        '<label for="searchByFileName">Value</label>' +
        '<span class="focus-border"></span>' +
        "</div>" +
        "</td>" +
        '<td width="10%" class="pt-30">' +
        '<button class="primary-btn icon-only fix-gr-bg close-deductions">' +
        '<span class="ti-close"></span>' +
        "</button>" +
        "</td>" +
        "</tr>"
    );
  });

  $("#addDeductionsTableBody").on("click", ".close-deductions", function() {
    $(this)
        .closest("tr")
        .fadeOut(500, function() {
          $(this).closest("tr").remove();
        });
  });

  // $('#addEarningsTableBody').on('click', '.close-earnings', function() {
  // 	$(this).closest('tr').fadeOut(500, function() {
  // 		$(this).closest('tr').remove();
  // 	});
  // });

  // });

  /*-------------------------------------------------------------------------------
       End Add Earnings
     -------------------------------------------------------------------------------*/

  /*-------------------------------------------------------------------------------
       Start Upload file and chane placeholder name
     -------------------------------------------------------------------------------*/
  var fileInput = document.getElementById("browseFile");
  if (fileInput) {
    fileInput.addEventListener("change", showFileName);

    function showFileName(event) {
      var fileInput = event.srcElement;
      var fileName = fileInput.files[0].name;
      document.getElementById("placeholderInput").placeholder = fileName;
    }
  }
  var fileInp = document.getElementById("browseFil");
  if (fileInp) {
    fileInp.addEventListener("change", showFileName);

    function showFileName(event) {
      var fileInp = event.srcElement;
      var fileName = fileInp.files[0].name;
      document.getElementById("placeholderIn").placeholder = fileName;
    }
  }

  if ($(".multipleSelect").length) {
    $(".multipleSelect").fastselect();
  }

  /*-------------------------------------------------------------------------------
       End Upload file and chane placeholder name
     -------------------------------------------------------------------------------*/

  /*-------------------------------------------------------------------------------
       Start Check Input is empty
     -------------------------------------------------------------------------------*/
  $(".input-effect input").each(function() {
    if ($(this).val().length > 0) {
      $(this).addClass("read-only-input");
    } else {
      $(this).removeClass("read-only-input");
    }

    $(this).on("keyup", function() {
      if ($(this).val().length > 0) {
        $(this).siblings(".invalid-feedback").fadeOut("slow");
      } else {
        $(this).siblings(".invalid-feedback").fadeIn("slow");
      }
    });
  });

  $(".input-effect textarea").each(function() {
    if ($(this).val().length > 0) {
      $(this).addClass("read-only-input");
    } else {
      $(this).removeClass("read-only-input");
    }
  });

  /*-------------------------------------------------------------------------------
       End Check Input is empty
     -------------------------------------------------------------------------------*/
  $(window).on("load", function() {
    $(".input-effect input, .input-effect textarea").focusout(function() {
      if ($(this).val() != "") {
        $(this).addClass("has-content");
      } else {
        $(this).removeClass("has-content");
      }
    });
  });

  /*-------------------------------------------------------------------------------
       End Input Field Effect
     -------------------------------------------------------------------------------*/
  // Search icon
  $("#search-icon").on("click", function() {
    $("#search").focus();
  });

  $("#start-date-icon").on("click", function() {
    $("#startDate").focus();
  });

  $("#end-date-icon").on("click", function() {
    $("#endDate").focus();
  });

  $(".primary-input.date").datepicker({
    autoclose: true,
    setDate: new Date(),
  });
  $(".primary-input.date").on("changeDate", function(ev) {
    // $(this).datepicker('hide');
    $(this).focus();
  });

  $(".primary-input.time").datetimepicker({
    format: "LT",
  });

  if ($)
    $('.primary-input.datetime').datetimepicker({
      format : 'YYYY-MM-DD H:mm'
    });

  /*-------------------------------------------------------------------------------
                       Start Side Nav Active Class Js
                     -------------------------------------------------------------------------------*/

  $(document).ready(function() {
    $("#sidebarCollapse").on("click", function() {
      $("#sidebar").toggleClass("active");
    });
  });
  $("#sidebar > ul > li > a").on("click", function() {
    $("#sidebar > ul > li > a").removeClass("active");
    $(this).addClass("active");
    jQuery(".collapse").collapse("hide");
  });

  jQuery(".sidebar-header .dropdown-toggle").on("click", function(e) {
    jQuery(".collapse").collapse("hide");
  });

  setNavigation();
  /*-------------------------------------------------------------------------------
       Start Side Nav Active Class Js
     -------------------------------------------------------------------------------*/
  $(window).on("load", function() {
    $(".dataTables_wrapper .dataTables_filter input").on("focus", function() {
      $(".dataTables_filter > label").addClass("jquery-search-label");
    });

    $(".dataTables_wrapper .dataTables_filter input").on("blur", function() {
      $(".dataTables_filter > label").removeClass("jquery-search-label");
    });
  });

  // Student Details
  // $('.close-activity .primary-btn').on('click', function() {
  // 	$(this).closest('.sub-activity-box').remove();
  // });

  $(".single-cms-box .btn").on("click", function() {
    $(this).fadeOut(500, function() {
      $(this).closest(".col-lg-2.mb-30").hide();
    });
  });
  // bootstrap tab for data table
  $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
    $($.fn.dataTable.tables(true))
        .DataTable()
        .columns.adjust()
        .responsive.recalc();
  });
  /*----------------------------------------------------*/
  /*  Magnific Pop up js (Image Gallery)
                  /*----------------------------------------------------*/
  $(".pop-up-image").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
    },
  });

  /*-------------------------------------------------------------------------------
       Jquery Table
     -------------------------------------------------------------------------------*/
  if ($("#table_id_table").length) {
    $("#table_id_table").DataTable({
      language: {
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      bFilter: false,
      bLengthChange: false,
    });
  }
  if ($("#table_id_table_one_page").length) {
    $("#table_id_table_one_page").DataTable({
      paging: false,
      bFilter: false,
      bLengthChange: false,
    });
  }
  if ($("#table_id_table_2").length) {
    $("#table_id_table_2").DataTable({
      paging: false,
      bFilter: false,
      bLengthChange: false,
    });
  }

  if ($("#table_id_table_one").length) {
    $("#table_id_table_one").DataTable({
      select: true,
      language: {
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      bFilter: false,
      bLengthChange: false,
    });
  }
  if ($("#default_table").length) {
    $("#default_table").DataTable({
      select: true,
      language: {
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      destroy: true,
      paging: true,
      bFilter: false,
      bLengthChange: false,
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }

  if ($("#default_table_searching").length) {
    $("#default_table_searching").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }

  if ($("#default_table2").length) {
    $("#default_table2").DataTable({
      select: true,
      bInfo: false,
      destroy: true,
      language: {
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },

      paging: false,
      bFilter: false,
      bLengthChange: false,
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }
  if ($("#table_id_s, .school-table-data").length) {
    $("#table_id_s, .school-table-data").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: window.jsLang('copy_table'),
        title: $("#logo_title").val(),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: window.jsLang('export_to_pdf'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            columnGap: 20,
            alignment: "center",
          },
          orientation: "landscape",
          pageSize: "A5",
          messageTop: function() {
            var t = [
              "Class: " + $("#cls").val(),
              "         ",
              "Section: " + $("#sec").val(),
            ];
            return t;
          },
          alignment: "center",
          header: true,
          margin: 20,
          customize: function(doc) {
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.pageMargins = [70, 20, 10, 20];
            doc.defaultStyle.fontSize = 10;
            doc.styles.tableHeader.fontSize = 11;
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }

  // last child remove n class section
  if ($("#table_id_tt, .school-table-data").length) {
    $("#table_id_tt, .school-table-data").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: window.jsLang('copy_table'),
        title: $("#logo_title").val(),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: window.jsLang('export_to_pdf'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
            columnGap: 20,
          },
          orientation: "landscape",
          pageSize: "A4",
          messageTop: function() {
            var t = [
              "Class: " + $("#cls").val(),
              "         ",
              "Section: " + $("#sec").val(),
            ];

            return t;
          },
          alignment: "center",
          header: true,
          margin: 20,
          customize: function(doc) {
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.pageMargins = [100, 20, 10, 20];
            doc.defaultStyle.fontSize = 10;
            doc.styles.tableHeader.fontSize = 11;
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }
  //last child reomve and class section will keep
  // student attendence
  if ($("#table_id_student, .school-table-data").length) {
    $("#table_id_student, .school-table-data").DataTable({
      bLengthChange: false,
      responsive: false,
      paging: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: window.jsLang('copy_table'),
        title: $("#logo_title").val(),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
            columnGap: 20,
          },
          orientation: "landscape",
          pageSize: "A4",
          fontSize: 10,
          messageTop: function() {
            var t = [
              "Class: " + $("#cls").val(),
              "         ",
              "Section: " + $("#sec").val(),
            ];
            return t;
          },
          alignment: "center",
          header: true,
          customize: function(doc) {
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.pageMargins = [10, 20, 10, 20];
            doc.defaultStyle.fontSize = 6;
            doc.styles.tableHeader.fontSize = 7;
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
          title: $("#logo_title").val(),
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
    });
  }
  //student panel
  if ($("#table_id_student_panel, .school-table-data").length) {
    $("#table_id_student_panel, .school-table-data").DataTable({
      bLengthChange: false,
      responsive: false,
      paging: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: window.jsLang('copy_table'),
        title: $("#logo_title").val(),
        exportOptions: {
          columns: ':visible:not(.not-export-col)',
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
            columnGap: 20,
          },
          orientation: "landscape",
          pageSize: "A4",
          fontSize: 10,
          alignment: "center",
          header: true,
          customize: function(doc) {
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.pageMargins = [10, 20, 10, 20];
            doc.defaultStyle.fontSize = 6;
            doc.styles.tableHeader.fontSize = 7;
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
          title: $("#logo_title").val(),
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
    });
  }
  if ($("#table_ids, .school-table-data").length) {
    $("#table_ids, .school-table-data").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: window.jsLang('copy_table'),
        title: $("#logo_title").val(),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)',
            order: "applied",
            columnGap: 20,
          },
          orientation: "landscape",
          pageSize: "A4",
          messageTop: function() {
            var t = [
              "Class: " + $("#cls").val(),
              "         ",
              "Section: " + $("#sec").val(),
            ];

            return t;
          },
          alignment: "center",
          header: true,
          margin: 20,
          customize: function(doc) {
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.pageMargins = [70, 20, 10, 20];
            doc.defaultStyle.fontSize = 10;
            doc.styles.tableHeader.fontSize = 11;
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
          title: $("#logo_title").val(),
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',
          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }
  //all row keep n without
  if ($("#table_id_al, .school-table-data").length) {
    $("#table_id_al, .school-table-data").DataTable({
      bLengthChange: false,
      bDestroy: true,
      language: {
        search: "<i class='ti-search'></i>",
        searchPlaceholder: window.jsLang('search'),
        paginate: {
          next: "<i class='ti-arrow-right'></i>",
          previous: "<i class='ti-arrow-left'></i>",
        },
      },
      dom: "Bfrtip",
      buttons: [{
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i>',
        title: $("#logo_title").val(),
        titleAttr: window.jsLang('copy_table'),
        exportOptions: {
          columns: ':visible:not(.not-export-col)'
        },
      },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: window.jsLang('export_to_excel'),
          title: $("#logo_title").val(),
          margin: [10, 10, 10, 0],
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: window.jsLang('export_to_csv'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          title: $("#logo_title").val(),
          titleAttr: window.jsLang('export_to_pdf'),
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
          orientation: "landscape",
          pageSize: "A4",
          margin: [0, 0, 0, 12],
          alignment: "center",
          header: true,
          customize: function(doc) {
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              image: "data:image/png;base64," + $("#logo_img").val(),
            });
            doc.defaultStyle = {
              font: 'DejaVuSans'
            }
          },
        },
        {
          extend: "print",
          text: '<i class="fa fa-print"></i>',
          titleAttr: window.jsLang('print'),
          title: $("#logo_title").val(),
          alignment: "center",
          exportOptions: {
            columns: ':visible:not(.not-export-col)'
          },
        },
        {
          extend: "colvis",
          text: '<i class="fa fa-columns"></i>',

          postfixButtons: ["colvisRestore"],
        },
      ],
      columnDefs: [{
        visible: false,
      }, ],
      responsive: true,
    });
  }

  /*-------------------------------------------------------------------------------
     Full Calendar Js
  -------------------------------------------------------------------------------*/
  // if ($('.common-calendar').length) {
  // 	$('.common-calendar').fullCalendar({
  // 		header: {
  // 			left: 'prev,next today',
  // 			center: 'title',
  // 			right: 'month,agendaWeek,agendaDay'
  // 		},
  // 		height: 650
  // 	});
  // }

  /*-------------------------------------------------------------------------------
     Moris Chart Js
  -------------------------------------------------------------------------------*/
  $(document).ready(function() {
    if ($("#commonAreaChart").length) {
      barChart();
    }
    if ($("#commonAreaChart").length) {
      areaChart();
    }
    if ($("#donutChart").length) {
      donutChart();
    }
  });

  function donutChart() {
    var total_collection = document.getElementById("total_collection").value;
    var total_assign = document.getElementById("total_assign").value;

    var due = total_assign - total_collection;

    window.donutChart = Morris.Donut({
      element: "donutChart",
      data: [
        { label: "Total Collection", value: total_collection },
        { label: "Due", value: total_assign },
      ],
      colors: ["var(--gradient_1)", "#c738d8"],
      resize: true,
      redraw: true,
    });
  }

  // CK Editor
  if ($("#ckEditor").length) {
    CKEDITOR.replace("ckEditor", {
      skin: "moono",
      enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      toolbar: [{
        name: "basicstyles",
        groups: ["basicstyles"],
        items: ["Bold", "Italic", "Underline", "-", "TextColor", "BGColor"],
      },
        { name: "styles", items: ["Format", "Font", "FontSize"] },
        { name: "scripts", items: ["Subscript", "Superscript"] },
        {
          name: "justify",
          groups: ["blocks", "align"],
          items: [
            "JustifyLeft",
            "JustifyCenter",
            "JustifyRight",
            "JustifyBlock",
          ],
        },
        {
          name: "paragraph",
          groups: ["list", "indent"],
          items: ["NumberedList", "BulletedList", "-", "Outdent", "Indent"],
        },
        { name: "links", items: ["Link", "Unlink"] },
        { name: "insert", items: ["Image"] },
        { name: "spell", items: ["jQuerySpellChecker"] },
        { name: "table", items: ["Table"] },
      ],
    });
  }

  if ($(".active-testimonial").length) {
    $(".active-testimonial").owlCarousel({
      items: 1,
      loop: true,
      margin: 20,
      dots: true,
      autoplay: true,
      nav: true,
      rtl: _rtl,
      navText: [
        "<img src='public/backEnd/img/client/prev.png' />",
        "<img src='public/backEnd/img/client/next.png' />",
      ],
    });
  }

  // Mpabox
  if ($("#mapBox").length) {
    var $lat = $("#mapBox").data("lat");
    var $lon = $("#mapBox").data("lon");
    var $zoom = $("#mapBox").data("zoom");
    var $marker = $("#mapBox").data("marker");
    var $info = $("#mapBox").data("info");
    var $markerLat = $("#mapBox").data("mlat");
    var $markerLon = $("#mapBox").data("mlon");
    var map = new GMaps({
      el: "#mapBox",
      lat: $lat,
      lng: $lon,
      scrollwheel: false,
      scaleControl: true,
      streetViewControl: false,
      panControl: true,
      disableDoubleClickZoom: true,
      mapTypeControl: false,
      zoom: $zoom,
      styles: [{
        featureType: "water",
        elementType: "geometry.fill",
        stylers: [{
          color: "#dcdfe6",
        }, ],
      },
        {
          featureType: "transit",
          stylers: [{
            color: "#808080",
          },
            {
              visibility: "off",
            },
          ],
        },
        {
          featureType: "road.highway",
          elementType: "geometry.stroke",
          stylers: [{
            visibility: "on",
          },
            {
              color: "#dcdfe6",
            },
          ],
        },
        {
          featureType: "road.highway",
          elementType: "geometry.fill",
          stylers: [{
            color: "#ffffff",
          }, ],
        },
        {
          featureType: "road.local",
          elementType: "geometry.fill",
          stylers: [{
            visibility: "on",
          },
            {
              color: "#ffffff",
            },
            {
              weight: 1.8,
            },
          ],
        },
        {
          featureType: "road.local",
          elementType: "geometry.stroke",
          stylers: [{
            color: "#d7d7d7",
          }, ],
        },
        {
          featureType: "poi",
          elementType: "geometry.fill",
          stylers: [{
            visibility: "on",
          },
            {
              color: "#ebebeb",
            },
          ],
        },
        {
          featureType: "administrative",
          elementType: "geometry",
          stylers: [{
            color: "#a7a7a7",
          }, ],
        },
        {
          featureType: "road.arterial",
          elementType: "geometry.fill",
          stylers: [{
            color: "#ffffff",
          }, ],
        },
        {
          featureType: "road.arterial",
          elementType: "geometry.fill",
          stylers: [{
            color: "#ffffff",
          }, ],
        },
        {
          featureType: "landscape",
          elementType: "geometry.fill",
          stylers: [{
            visibility: "on",
          },
            {
              color: "#efefef",
            },
          ],
        },
        {
          featureType: "road",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#696969",
          }, ],
        },
        {
          featureType: "administrative",
          elementType: "labels.text.fill",
          stylers: [{
            visibility: "on",
          },
            {
              color: "#737373",
            },
          ],
        },
        {
          featureType: "poi",
          elementType: "labels.icon",
          stylers: [{
            visibility: "off",
          }, ],
        },
        {
          featureType: "poi",
          elementType: "labels",
          stylers: [{
            visibility: "off",
          }, ],
        },
        {
          featureType: "road.arterial",
          elementType: "geometry.stroke",
          stylers: [{
            color: "#d6d6d6",
          }, ],
        },
        {
          featureType: "road",
          elementType: "labels.icon",
          stylers: [{
            visibility: "off",
          }, ],
        },
        {},
        {
          featureType: "poi",
          elementType: "geometry.fill",
          stylers: [{
            color: "#dadada",
          }, ],
        },
      ],
    });
  }

  function setNavigation() {
    var current = location.href;

    var url = document.getElementById("url");

    var previousUrl = document.referrer;

    var i = 0;
    $( "#sidebar_menu" ).find( "a" ).removeClass("active");
    $( "#sidebar_menu" ).find( "li" ).removeClass("mm-active");
    $( "#sidebar_menu" ).find( "li ul" ).removeClass("mm-show");

    $("#sidebar ul li ul li a").each(function() {
      var $this = $(this);
      // if the current path is like this link, make it active
      if ($this.attr("href") == current) {
        i++;
        $this.closest(".list-unstyled").addClass("mm-show");
        $this.closest(".list-unstyled").parent().addClass("mm-active");
        // $('#sidebar ul li a').removeClass('active');
        // $this.closest(".list-unstyled")
        // .addClass("active");
        $this.addClass("active");
      }
    });

    if (current == url + "/" + "admin-dashboard") {
      i++;

      $("#admin-dashboard").addClass("active");
    }

    if (i == 0) {
      $("#sidebar ul li ul li a").each(function() {
        var $this = $(this);
        // if the current path is like this link, make it active
        if ($this.attr("href") == previousUrl) {
          i++;
          $this.closest(".list-unstyled").addClass("show");
          // $('#sidebar ul li a').removeClass('active');
          $this
              .closest(".list-unstyled")
              .siblings(".dropdown-toggle")
              .addClass("active");
          $this.addClass("active");
        }
      });
    }

    if (current == url + "/" + "exam-attendance-create") {
      $("#subMenuExam").addClass("show");
      $("#subMenuExam")
          .closest(".list-unstyled")
          .siblings(".dropdown-toggle")
          .addClass("active");
      $("#sidebar a[href='" + url + "/" + "exam-attendance']").addClass(
          "active"
      );
    }

    $("#close_sidebar").on("click", function() {
      $("#sidebar").removeClass("active");
    });
  }
  //remove sidebar
  $(document).click(function(event) {
    if (!$(event.target).closest(".sidebar_icon, .sidebar").length) {
      $("body").find(".sidebar").removeClass("active_sidebar");
    }
  });
  //for check
  $('.radio_question').on('click', function(event) {
    $(this).siblings('.active').removeClass('active');
    $(this).addClass('active');
    // event.preventDefault();
  });

  ("use strict");
})(jQuery);