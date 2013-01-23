$(function () {

    var selectedUrl = '';
// Dialog confirmation delete
    $('#confirmDelete').dialog({
        autoOpen: false,
        width: 400,
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
        $("#confirmDelete").html('Are you sure want to inactive field <b>' + name + ' </b> ?');
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

/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 9/30/12
 * Time: 1:46 AM
 * To change this template use File | Settings | File Templates.
 */
