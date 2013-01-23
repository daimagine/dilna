/**
 * Created with JetBrains PhpStorm.
 * User: adi
 * Date: 10/25/12
 * Time: 1:43 AM
 * To change this template use File | Settings | File Templates.
 */

$(function() {

    //===== Ajx Chosen plugin =====//

    $('#receiver-list').ajaxChosen({
        dataType: 'json',
        type: 'GET',
        url:  '/user/find',
        data: {},
        success: function(data, textStatus, jqXHR){
            //doSomething();
            console.log(data);
        }
    },{
        processItems: function(data){
            //console.log(data.results);
            var keypairs = new Array();
            var select = $('#receiver-list');
            console.log($('option:selected', select).not(':empty'));
            var selected = $('option:selected', select).not(':empty');
            $.each(data.results, function (i, key) {
                var valid = true;
                console.log(key.id + ': ' + key.text);
                $.each(selected, function (j, opt) {
                   if(opt.value == key.id) {
                       console.log('duplicate ' + opt.value + ': ' + opt.text);
                       valid = false;
                   }
                });
                if(valid)
                    keypairs.push(key);
            });
            console.log(keypairs);
            return keypairs;
        }
//        useAjax: function(e){ return true; },
//        generateUrl: function(q){
//            console.log('q.');
//            console.log(q);
//            var key = $('.chzn-choices input').val();
//            console.log('key : ' + key);
//            return '/user/find/'+ key;
//            return null;
//        },
//        loadingImg: '/images/elements/loaders/5s.gif'
    });

//    $("#receiver-list").chosen().change(function(e) {
//        console.log(this);
//    });

//    $('.chzn-choices input').autocomplete({
//        source: function( request, response ) {
//            $.ajax({
//                url: "/user/find/"+request.term+"/",
//                dataType: "json",
//                beforeSend: function(){
//                    //console.log('empty option');
//                    $('ul.chzn-results').empty();
//                    $('.chzn-done').empty();
//                },
//                success: function( data ) {
//                    //console.log(data);
//                    response(
//                        $.map( data, function( item ) {
//                            $('ul.chzn-results').append('<li id="receiver-list_chzn_o_2" class="active-result">' + item.name + '</li>');
//                            $('.chzn-done').append('<option value="' + item.id + '">' + item.name + '</option>');
//                            $("#receiver-list").trigger("liszt:updated");
//                            //console.log(item);
//                        })
//                    );
//                }
//            });
//        }
//    });

});


function viewConversation(obj) {
    console.log(obj);
    var id = $(obj).attr('data-id');
    console.log(id);
    var url = '/conversation/view/' + id;
    console.log(url);
    document.location.href = url;
}