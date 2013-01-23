/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 9/30/12
 * Time: 12:35 AM
 * To change this template use File | Settings | File Templates.
 */
$(function() {

    //validation
    $("#formEBengkel").validationEngine();

    $('#dialogAdd').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        resizable: false,
        buttons: {
            "Submit Form": function() {
                document.formEBengkel.submit();
                $("#formEbengkel").validationEngine();
                $(this).dialog("close");
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    });

    $('form#formEBengkel').submit(function(e){
        e.preventDefault();
        var name = $("input#name").val();
        var price = $("input#price").val();
        var desc = $("input#description").val();
        if(name!='' && desc!='' && price!='' ) {
            $("span#serviceName").html(name);
            $("span#servicePrice").html(price);
            $('#dialogAdd').dialog('open');
        }
        return false;
    });


});