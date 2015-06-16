$(document).ready( function() {
    $('input.minicolors').minicolors({
        theme: 'bootstrap'
    });
});

$('.iconpicker[data-form-item-relation]').on('change', function(e) {
    console.log(e.icon);
    var icon = e.icon.replace("glyphicon-", "");
    if(icon == "empty") {

        icon = null;
    }
    $($(this).attr('data-form-item-relation')).val(icon);
});

