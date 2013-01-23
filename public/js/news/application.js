/**
 * Created with JetBrains PhpStorm.
 * User: adi
 * Date: 10/12/12
 * Time: 11:35 PM
 * To change this template use File | Settings | File Templates.
 */

//===== Wizards =====//
$(function() {

    // Dialog
    $('#detailNews').dialog({
        autoOpen: false,
        width: 500,
        modal: true
    });

});


function detailNews(id){
    console.log('open detail news of id ' + id);
    $('#detailNews').load("/news/detail/" + id);
    $('#detailNews').dialog('open');
    $('#detailNews').dialog("option", "position", ['top', 'center'] );
}

