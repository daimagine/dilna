/**
 * Created with JetBrains PhpStorm.
 * User: adi
 * Date: 10/13/12
 * Time: 10:12 PM
 * To change this template use File | Settings | File Templates.
 */

$(function() {

    $('#searchInputNews').keyup(function(e){
        var code = e.which; // recommended to use e.which, it's normalized across browsers
        if(code==13)e.preventDefault();
        if(code==32||code==13||code==188||code==186){
            searchNews();
        } // missing closing if brace
    });

    $('#searchInputBtn').bind('click', function() {
       searchNews();
    });

    // Dialog
    $('#detailNews').dialog({
        autoOpen: false,
        width: 500,
        modal: true
    });

});


function searchNews() {
    var needle = $('#searchInputNews').val().trim();
    console.log(needle);
    if(needle !== '') {
        $("ul.updates > li.newsline").css('display', 'none');
        $("ul.updates > li:contains('" + needle + "')").css('display', 'block');
    } else {
        $("ul.updates > li.newsline").css('display', 'block');
    }
}

function detailNews(id){
    console.log('open detail news of id ' + id);
    $('#detailNews').load("/news/detail/" + id);
    $('#detailNews').dialog('open');
    $('#detailNews').dialog("option", "position", ['top', 'center'] );
}
