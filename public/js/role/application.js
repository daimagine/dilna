$(function() {

    //===== Chosen plugin =====//
    $(".select").chosen();

    //===== Dual select boxes =====//
    try {
        $.configureBoxes();
    } catch (e) {}

});


function roleSelect() {
    var id = $('#role-select').find(':selected').val();
    var uri = 'access/' + id;
    if(id !== '0') //console.log(uri);
        location.href = uri;
}

function ensureSelectAccess() {
    var multi = $('#box2View');
    $('#' + multi + ' option').attr('selected', 'selected');
}