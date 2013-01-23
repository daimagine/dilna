$(function() {

    //===== Chosen plugin =====//
    $(".select").chosen();

    //===== Dual select boxes =====//
    $.configureBoxes();

});


function roleSelect() {
    var id = $('#role-select').find(':selected').val();
    var uri = '/role/access/' + id;
    if(id !== '0')
        location.href = uri;
}

function ensureSelectAccess() {
    var multi = $('#box2View');
    $('#' + multi + ' option').attr('selected', 'selected');
}