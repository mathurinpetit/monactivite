$(document).ready( function() {
    $('input.minicolors').minicolors({
        theme: 'bootstrap'
    });

    $('[data-toggle="popover"]').popover();

    $('.iconpicker[data-form-item-relation]').on('change', function(e) {
        var icon = e.icon.replace("glyphicon-", "");
        if(icon == "empty") {

            icon = null;
        }
        $($(this).attr('data-form-item-relation')).val(icon);
    });

});


