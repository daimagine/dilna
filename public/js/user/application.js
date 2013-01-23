/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 11/11/12
 * Time: 9:05 PM
 * To change this template use File | Settings | File Templates.
 */
$(function() {
    $("#formUpdatePassword").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 5
                },
                password_confirmation: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                }
            }
    });

    $("#update-password-dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 453,
        buttons: {
            "Update": function () {
                var msgError = "";
                var status = true;
                var pswd = $("#password").val();
                var retype_pswd = $("#password_confirmation").val();
                if (pswd != null && pswd != '') {
                    if (pswd.trim().length < 5) {
                        status = false;
                        msgError += "Password length must be more than 5 characters, "
                    }
                } else {
                    status = false;
                    msgError += "Password can't be null, "
                }

                if (retype_pswd != null && retype_pswd != '') {
                    if (pswd.trim() != retype_pswd.trim()) {
                        status = false;
                        msgError += "Password not match, "
                    }
                } else {
                    status = false;
                    msgError += "insufficent params, "
                }
                if (status == true) {
                    var name = $("#user-name").html();
                    var confirm_content = 'Your action cannot be undone. Are you sure want to update password user <strong>'+name+'</strong> ?';
                    $("#submit-confirm").html(confirm_content);
                    $("#submit-confirm").dialog({
                        modal: true,
                        minWidth: 500,
                        buttons : {
                            "Confirm" : function() {
                                $('#formUpdatePassword').submit();
                            },
                            "Cancel" : function() {
                                $('#submit-confirm').dialog("close");
                            }
                        }
                    });
                } else {
                    var classNotif = 'nFailure';
                    var html = '<div class="nNote ' + classNotif + '" style="margin-top: 0; margin-bottom: 15px;"><p> Input field not valid</p></div>';
                    $('#update-pswd-notification').html(html);
                }
            },
            "Cancel": function () {
                $( this ).dialog( "close" );
            }
        }
    });

    $("a.linkUpdatePswd").click(function (e) {
        e.preventDefault();
        //==== clear input field =====
        $("#password").val('');
        $("#password_confirmation").val('');
        $('#update-pswd-notification').html('');
        //==== get new user ====
        var userId = $(this).attr("ref-num");
        var name = $(this).parent().parent().children('td.name').html()
//        console.log("User ID ==> "+userId);
        $("#userId").val(userId);
        $("#user-name").html(name);
        $("#update-password-dialog").dialog("open");
    });
});