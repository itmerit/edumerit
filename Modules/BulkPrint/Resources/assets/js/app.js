$(document).on("click", ".bulk-generate-certificate-print-all", function(event) {
    var url = $("#url").val();
    var certificate = $("#certificate").val();
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
    if (sList != "") {
        $("#bulk-genearte-certificate-print-button").attr(
            "href",
            url + "/bulk-generate-certificate-print/" + sList + "/" + certificate
        );
        $("#bulk-genearte-certificate-print-button").attr("target", "_blank");
    } else {
        $("#bulk-genearte-certificate-print-button").attr("href", "javascript:;");
        $("#bulk-genearte-certificate-print-button").removeAttr("target");
    }
});