/* str to split*/
function helper_confirm(id, title, content, width, btnConfirm, btnClose, callback) {
    var dialog = $("#" + id);  
    dialog.attr('title', title);  
    $("#confirm_text", dialog).html(content);
    var confirm_buttons = {};
    confirm_buttons[btnConfirm] = function() {
        if (callback)
            callback();
        $(this).dialog("close");
    };
    confirm_buttons[btnClose] = function() {
        $(this).dialog("close");
    };

    dialog.dialog({
        resizable: false,
        height: "auto",
        width: width,
        modal: true,
        buttons: confirm_buttons
    });
}


function search_confirm(id, title, width, btnConfirm, btnClose, callback) {
    var dialog = $("#" + id);

    dialog.attr('title', title);
    //$("#confirm_text", dialog).html(content);
    var confirm_buttons = {};
    confirm_buttons[btnConfirm] = function() {
        if (callback)
            callback();
        $(this).dialog("close");
    };
    confirm_buttons[btnClose] = function() {
        $(this).dialog("close");
    };

    dialog.dialog({
        resizable: false,
        height: "auto",
        width: width,
        modal: true,
        buttons: confirm_buttons
    });

}

function input_confirm(id, title, width, btnConfirm, btnClose, callback) {
    var dialog = $("#" + id);
    dialog.attr('title', title);
    var confirm_buttons = {};
    confirm_buttons[btnConfirm] = function() {
        if (callback)
            var ret = callback();
        if (ret == "right") {
            $(this).dialog("close");
            $("#reception_error").css('display', 'none');
        } else $("#reception_error").css('display', 'block');
    };
    confirm_buttons[btnClose] = function() {
        $(this).dialog("close");
        $("#reception_error").css('display', 'none');
    };

    dialog.dialog({
        resizable: false,
        height: "auto",
        width: width,
        modal: true,
        buttons: confirm_buttons
    });

}

