
//===== Wizards =====//
$(function() {

    // Dialog
    $('#formDialog').dialog({
        autoOpen: false,
        width: 500,
        buttons: {
            "Save": function () {
                var confirm_title = 'Confirmation';
                var confirm_content = 'Your action cannot be undone. Are you sure?';
                $("#dialog-confirm").attr('title', confirm_title);
                $("#dialog-confirm-content").html(confirm_content);

                $("#dialog-confirm").dialog({
                    modal: true,
                    buttons : {
                        "Confirm" : function() {
                            $('#memberAssignForm').submit();
                            $(this).dialog("close");
                        },
                        "Cancel" : function() {
                            $(this).dialog("close");
                        }
                    }
                });

                $("#dialog-confirm").dialog("open");
            }
        }
    });

    // Dialog Link
    $('.formDialog_open').click(function () {
		var data = $(this).attr('additional-value').split(';');
		console.log(data);
        $('#vehicleId').val($(this).attr('data-value-vehicle'));
        $('#customerId').val($(this).attr('data-value-customer'));
        $('#customerName').html(data[1]!='' ? data[1]+'\'s' : 'Customer');
        $('#customerVehicle').html(data[0]!='' ? data[0] : 'vehicle');
		$('#customerSince').html(data[2]);
        $('#formDialog').dialog({
            modal: true,
            autoOpen: true
        });
        return false;
    });

    // Dialog
    $('#detailMember').dialog({
        autoOpen: false,
        width: 500,
        modal: true
    });

});


function detailMember(id){
    console.log('open detail membership of id ' + id);
    $('#detailMember').load("/member/detail/" + id);
    $('#detailMember').dialog('open');
    $('#detailMember').dialog("option", "position", ['center', 'center'] );
}

