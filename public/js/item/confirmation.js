$(function() {

    var selectedUrl = '';
// Dialog confirmation delete
    $('#confirmDelete').dialog({
        autoOpen: false,
        width: 300,
        modal:true,
        buttons: {
            "Yes, I'm sure": function() {
                $( this ).dialog( "close" );
                if('' != jQuery.trim(selectedUrl)) {
                    window.location = selectedUrl;
                }
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }

    });

    $('a.classConfirmDelete').click(function () {
        console.log('open confirmation delete');
        selectedUrl = $(this).attr('href');
        var name = $(this).parent().parent().children('td.name').html();  // a.delete -> td -> tr -> td.name
        name = jQuery.trim(name);
        $("#confirmDelete").html('Are you sure want to delete item ' + name + ' ?');
        $("#confirmDelete").dialog('open');
        return false;
    });

    $('a.classConfirmEdit').click(function () {
        console.log('open confirmation edit');
        selectedUrl = $(this).attr('href');
        var name = $(this).parent().parent().children('td.name').html();
        name = jQuery.trim(name);
        $("#confirmDelete").html('You want edit item <b>' + name + ' </b> ?');
        $("#confirmDelete").dialog('open');
        return false;
    });

});