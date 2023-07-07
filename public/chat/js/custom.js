function auto_height(elem) {
    elem.style.height = "1px";
    elem.style.height = (elem.scrollHeight)+"px";
}

// preview img
function readURL(input,) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {

            $('#blah').attr('src', e.target.result);
        },

            reader.readAsDataURL(input.files[0]);

    }


}

function getFileName(value){
    if (value) {
        var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
        var filename = value.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            filename = filename.substring(1);
        }
        return filename;
    }
}

$("#imgInp").change(function() {
    var filename = getFileName($(this).val());
    var fileType = filename.split('.').pop();
    var image = ['jpg', 'png', 'jpeg'];
    if(image.includes(fileType)){
        $('#blah').removeClass('d-none');
        $('#ar').addClass('d-none');
    } else{
        $('#blah').addClass('d-none');
        $('#ar').removeClass('d-none');
        $('#ar').html(filename);
    }

    $('.preview_imgs').removeClass('d-none');
});

function getFileName(value, placeholder) {
    if (value) {
        var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
        var filename = value.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            filename = filename.substring(1);
        }
        $(placeholder).attr('placeholder', '');
        $(placeholder).attr('placeholder', filename);
    }
}

function imageChangeWithFile(input, srcId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(srcId)
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}


$(".close_preview").on('click',function(){
    $('.preview_imgs').addClass('d-none');
    $('#blah').attr('src','#');
    $('#ar').html('');
})
$(".img_toggle").on('click',function(){
    $('.emoji_box').toggleClass('active');
})



$( document ).ready(function() {
    $('#group_photo').on("change", function (e) {
        var fileInput = document.getElementById("group_photo");
        document.getElementById("placeholderGroupPhoto").placeholder = e.target.files[0].name;
    });
});