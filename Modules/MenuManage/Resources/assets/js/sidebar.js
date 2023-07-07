let csrf_token = $('meta[name="csrf-token"]').attr('content');

function initSortable() {
    $('#itemDiv').sortable({
        scroll: true,
        scrollSensitivity: 100,
        cursor: "move", containment: "parent", update: function (event, ui) {
            let ids = $(this).sortable('toArray', {attribute: 'data-id'});
            let data = {
                ids: ids, _token: csrf_token
            };
            $.post($('#section_sort_url').val(), data, function (response) {
                reloadAfterChange(response)
            }).fail(function (response) {
                if (response.responseJSON.error) {
                    toastr.error(response.responseJSON.error);
                    hidePreloader()
                    return false;
                }
            });

        }
    })
    checkEmptyChild()
    // .disableSelection();
}

function reloadAfterChange(response) {
    toastr.success(window.jsLang('Operation successful'));
    $('#menu_idv').html(response.menus);
    $('#available_menu_div').html(response.available_list);
    $('#live_preview_div').html(response.live_preview);
    $("#previewMenu").metisMenu();
    hidePreloader();
    initSortable();
}


$(document).ready(function () {

    let url = $('#order_change_url').val();
    $(document).on('mouseover', 'body', function () {
        let demoMode = $('#demoMode').val();

        if (demoMode) {
            return false;
        }

        $('.menu-list').nestable({
            maxDepth: 2,
            // expandBtnHTML: '<button class="primary-btn radius_30px mr-10 fix-gr-bg" data-action="expand"></button>',
            // collapseBtnHTML: '<button class="primary-btn radius_30px mr-10 fix-gr-bg" data-action="collapse"></button>',
            callback: function (l, e) {


                try {
                    let order = null;
                    // let order = JSON.stringify($('.menu-list').nestable('serialize'));
                    let order_value = l.nestable('serialize') ?? null;
                    if (typeof order_value === 'object') {
                        order = JSON.stringify(order_value);
                    }
                    let listItem = $(".used_menu").find('li');
                    let unusedItem = $("#available_menu_div").find('li');
                    let un_used = $(this).data('type')?? null;
                    let ids = [];
                    let unused_ids = [];
                    $.each(listItem, function (index) {
                        ids.push($(this).data('id'));
                    });
                    $.each(unusedItem, function (index) {
                        unused_ids.push($(this).data('id'));
                    });
                    let data = {
                        'order': order,
                        '_token': csrf_token,
                        'ids': ids,
                        'unused_ids': unused_ids,
                        'un_used': un_used,
                        'menu_status': 1,
                        'section': l.data('section') ?? 1
                    }
                    showPreloader()
                    $.post(url, data, function (response) {                        
                        reloadAfterChange(response)

                    });
                } catch (err) {

                }


            }
        });
    });
});

function checkDemo() {
    let demoMode = $('#demoMode').val();

    if (demoMode) {
        toastr.warning("For the demo version, you cannot change this", "Warning");
        return false;
    } else {
        return true;
    }
}

function hidePreloader() {
    $('.preloader').fadeOut('slow');
}

function showPreloader() {
    $('.preloader').fadeIn('slow');
}





$(document).ready(function () {
    $("#previewMenu").metisMenu();

    initSortable();


    $(document).on('click', '.remove_menu', function () {
        var $item = $(this).closest(".dd-item");
        let id = $item.data('id');
        $item.remove();
        let data = {
            id: id,
            _token: csrf_token
        }
        showPreloader()
        $.post($('#menu_remove_url').val(), data, function (response) {
            reloadAfterChange(response)
        });

    });


    $(document).on('click', '.toggle_up_down', function (event) {
        if ($(this).hasClass('ti-angle-up')) {
            $(this).removeClass('ti-angle-up');
            $(this).addClass('ti-angle-down');
            $(this).closest('.closed_section').find('.card').addClass('d-none');

        } else if ($(this).hasClass('ti-angle-down')) {
            $(this).removeClass('ti-angle-down');
            $(this).addClass('ti-angle-up');

            $(this).closest('.closed_section').find('.card').removeClass('d-none');
        }
    });
    // addSectionBtn
    $(document).on('click', '#addSectionBtn', function (event) {
        event.preventDefault();
        showPreloader();
        let formElement = $('#addSectionForm').serializeArray()
        let formData = new FormData();
        formElement.forEach(element => {
            formData.append(element.name, element.value);
        });

        formData.append('_token', csrf_token);

        $.ajax({
            url: $('#section_store_url').val(),
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                hidePreloader();
                $('.section_name').val('');
                reloadAfterChange(response)
            },
            error: function (response) {
                hidePreloader()

                $.each(response.responseJSON.errors, function (k, v) {
                    toastr.error(v);
                });
            }
        });

    });

    $(document).on('click', '#addMenuBtn', function (event) {
        event.preventDefault();
        showPreloader();
        var formElement = $('#addMenuForm').serializeArray()
        var formData = new FormData();
        formElement.forEach(element => {
            formData.append(element.name, element.value);
        });


        formData.append('_token', csrf_token);

        $.ajax({
            url: $('#menu_delete_url').val(),
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                reloadAfterChange(response)
                $('.menu_name').val('');
                $('.route_name').val('');
            },
            error: function (response) {
                hidePreloader();

                $.each(response.responseJSON.errors, function (k, v) {
                    toastr.error(v);
                });

            }
        });

    });

    $(document).on('click', '.delete_section', function () {
        let id = $(this).data('id');
        let data = {
            id: id, _token: csrf_token
        }
        showPreloader();
        $.post($('#section_delete_url').val(), data, function (response) {
            reloadAfterChange(response)
        });
    });


})


function checkEmptyChild() {
    $('.dd-list').each(function (i, obj) {
        if ($(this).children('.dd-item').length > 0) {

        } else {
            $(this).closest('.dd-list').remove();
        }
    });
}
