
$(document).on("click", function(e) {
    if (!$(e.target).closest('#serching').length)  {
        $("#livesearch").hide();
    }
});
