

/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 9/30/12
 * Time: 11:51 AM
 * To change this template use File | Settings | File Templates.
 */
$(function() {

    var selectedUrl = '';
    var methodType='';
    var isvalid =false;
    //-====================== FORM DIALOG LIST CUSTOMER ========================//
    $('form#formEBengkel').submit(function(e){
        return false;
    });

    $('#buttonSaveWO').click(function () {
        console.log(':: SUBMIT FORM ACTION');
        var msg = '';
        var customer = $('#customerName').val();
        var vehicle = $('#vehicle-rows').val();
        var service = $('#service-rows').val();
        if (customer.trim() === '')
            msg += 'Customer, ';
        if (vehicle.trim() === '0')
            msg += 'Vehicle, ';
        if (service.trim() === '0')
            msg += 'Services, ';
        if (msg === '') {
            console.log(':: Show confirmation add wo');
            var customerName = $('#customerName').val();
            var vehicleNumber = $('#vehiclesnumber').val();
            msg ='<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>Are you sure do this action ?</p>' +
                '<p>Customer Name : '+customerName+'</p>' +
                '<p>Vehcile No : '+vehicleNumber+'</p>';
            $('#action-button').val('save');
            $("#submit-confirm").html(msg);
            $('#submit-confirm').dialog('open');
            isvalid=true;
        } else {
            console.log(':: Show alert data null');
            msg ='Data ' + msg + ' cant be empty..!!';
            isvalid=false;
            $("#notif-dialog").html(msg);
            $('#notif-dialog').dialog('open');
        }

    });
    $('#buttonSaveClosedWO').click(function () {
        var msg = '';
        var customer = $('#customerName').val();
        var vehicle = $('#vehiclesnumber').val();
        var service = $('#service-rows').val();

        if (customer.trim() === '')
            msg += 'Customer, ';
        if (vehicle.trim() === '')
            msg += 'Vehicle, ';
        if (service.trim() === '0')
            msg += 'Services, ';

        console.log(':: MSG => '+msg);
        if(msg==='') {
            $('#action-button').val('saveandclosed');
            methodType = 'closed';
            $("#msg-closed").html('Are you sure want to save and closed this wo ?, if yes please select Payment method first and then press button yes ');
            $('#closed_confirmation').dialog('open');
            isvalid=true;
        } else {
            msg ='Data ' + msg + ' cant be empty..!!';
            $("#notif-dialog").html(msg);
            $('#notif-dialog').dialog('open');
            isvalid=false;
        }
    });

    $('#submit-confirm').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        resizable: false,
        buttons: {
            "Yes": function() {
                if (isvalid){
                    document.formEBengkel.submit();
                }
                $(this).dialog("close");
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    });

    $('#notif-dialog').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        resizable: false,
        buttons: {
            "Closed": function() {
                $(this).dialog("close");
            }
        }
    });



    $("#serviceType").change(function(event){
        //alert("Click event on Select has occurred!");
        $("option:selected", $(this)).each(function(){
            var obj = document.getElementById('serviceType').value;
            //            alert("selected value"+obj);
            $("textarea.desc-service").css("display", "none");
            $("#desc-"+obj).css("display", "inline");
        });
    });


    //===== Image gallery control buttons =====//
    $(".gallery ul li").hover(
        function() { $(this).children(".actions").show("fade", 200); },
        function() { $(this).children(".actions").hide("fade", 200); }
    );

    //===== Sortable columns =====//
    $("table").tablesorter();


    //===== Resizable columns =====//
    $("#resize, #resize2").colResizable({
        liveDrag:true,
        draggingClass:"dragging"
    });


    //===== Dynamic table toolbars =====//
    $('#dyn .tOptions').click(function () {
        $('#dyn .tablePars').slideToggle(200);
    });
    $('#dyn2 .tOptions').click(function () {
        $('#dyn2 .tablePars').slideToggle(200);
    });
    $('.tOptions').click(function () {
        $(this).toggleClass("act");
    });

    //===== Tabs =====//
    $.fn.contentTabs = function(){
        $(this).find(".tab_content").hide(); //Hide all content
        $(this).find("ul.tabs li:first").addClass("activeTab").show(); //Activate first tab
        $(this).find(".tab_content:first").show(); //Show first tab content

        $("ul.tabs li").click(function() {
            $(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
            $(this).addClass("activeTab"); //Add "active" class to selected tab
            $(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            $(activeTab).show(); //Fade in the active content
            return false;
        });

    };
    $("div[class^='widget']").contentTabs(); //Run function on any div with class name of "Content Tabs"

    //=========dialog confirmation=========//
    $('#closed_confirmation').dialog({
        autoOpen: false,
        width: 550,
        modal: true,
        buttons: {
            "Yes Sure": function() {
                if (methodType === 'closed'){
                    var paymentMethod = $('#option_payment_method').val();
                    console.log(':: Payment method '+paymentMethod);
                    if (paymentMethod.trim() === '') {
                        alert('Please select payment method.!');
                    } else {
                        $('#inputhid').append(
                            $('<input>')
                                .attr('type', 'hidden')
                                .attr('id','payment_method')
                                .attr('name','payment_method')
                                .val(paymentMethod)
                        )
                        document.formEBengkel.submit();
                        $( this ).dialog( "close" );
                    }
                } else if (methodType === 'edit') {
                    window.location.href = selectedUrl;
                }
            },
            "Close": function() {
                $( this ).dialog( "close" );
            }
        }
    });

    $('input.buttonAction').click(function () {
        //edit/transactionId
        console.log('open confirmation');
        var mechanicField = $("#mechanicField").val();
        var serviceField = $("#serviceField").val();
        var workorderno = $("#workorderno").val();
        var transactionId = $("#transactionId").val();
        selectedUrl = $(this).attr('href');
        console.log("selectedUrl value "+selectedUrl);
        console.log("transactionId value "+transactionId);
        console.log("workorderno value "+workorderno);
        console.log("mechanicField value "+mechanicField);
        console.log("serviceField value "+serviceField);
        if (serviceField == 0) {
            $("#closed_confirmation").html('Sorry you cant closed this work order, since no<strong> mechanic </strong>assigned for this work order, press button <strong>Yes</strong> to edit this work order ?');
            selectedUrl = '../edit/'+transactionId;
            methodType = 'edit';
        } else if (mechanicField == 0) {
            $("#closed_confirmation").html('Sorry you cant closed this work order, since no <strong> service </strong>assigned for this work order, press button <strong>Yes</strong> to edit this work order ?');
            selectedUrl = '../edit/'+transactionId;
            methodType = 'edit';
        } else {
            $("#msg-closed").html('Are you sure want to closed workorder <strong> '+workorderno+' </strong> ?, if yes please select Payment method first and then press button yes ');
            methodType = 'closed';
        }
        name = jQuery.trim(name);
        $("#closed_confirmation").dialog('open');
        return false;
    });

    //spinner
    var itemrows = $('#item-rows').val();
    console.log(':: Item rows is '+itemrows);
    var i;
    for (i = 0; i < parseInt(itemrows); ++i) {
         $('#item-quantity-'+i).spinner({min:0, max:100, showOn:'both'});
     }

    //===== init customer dialog =====//
    WorkOrder.customer.initDialog();
    WorkOrder.service.initDialog();
    WorkOrder.items.initDialog();
    WorkOrder.mechanic.initDialog();
});


var WorkOrder = {};
WorkOrder.customer = {
    //selector helper
    _action : '#action-button',
    _method : '#customer-method',
    _addkey : '#customer-addkey',
    _whead  : '#customer-whead',
    _body   : '#customer-body',
    _select : 'a.select',
    _notice : '#vehicle-addnotice',
    _rows   : '#vehicle-rows',
    _table  : '#vehicle-table',
    _tbody  : '#vehicle-tbody',
    _dialogvehicle : '#vehicle-dialog',
    _dialog : '#customer-dialog',
    _dialognewcustomer : '#new-customer-dialog',
    _divcustomerdatahid : '#customerdatahid',
    _vehiclecustomername : '#vehicle-customer-name',
    _customernamefield : '#customer-name',
    _customerregistercheckbox : '#checkbox-register',
    _addvehicle : '#add-new-vehicle',

    //form selector helper
    _form   : {
        customerid : '#customerId',
        vehicleid : '#vehicleId',
        customername : '#customerName',
        memberstatus : '#memberStatus',
        no     : '#vehicle-no',
        type   : '#vehicle-type',
        color  : '#vehicle-color',
        model  : '#vehicle-model',
        brand  : '#vehicle-brand',
        desc   : '#vehicle-description',
        //for new customer dialog
        newcustomername : '#customer-name',
        newcustomeraddress1 : '#customer-address1',
        newcustomeraddress2 : '#customer-address2',
        newcustomercity : '#customer-city',
        newcustomerpost_code : '#customer-post_code',
        newcustomerphone1 : '#customer-phone1',
        newcustomerphone2 : '#customer-phone2',
        newcustomeradditional_info : '#customer-additional_info',
        notif  : '#customer-dialog-notification'
    },
    //function to initialize dialog form
    initDialog : function() {
        $(this._dialog).dialog({
            autoOpen: false,
            modal: true,
            width: 900,
            height: 681,
            buttons: {
                "Cancel": function () {
                    $('#tablecustomer').dataTable({
                        "bDestroy": true,
                        "bRetrieve": true
                    });
                    $( this ).dialog( "close" );
                }
            }
        });

        $(WorkOrder.customer._select).click(function () {
//            var confirmesi= confirm("Do you want select this customer ??");
            //-------------get value from table-----------------
            var vehicle_id = $(this).parent().parent().children('th.id').html();
            var vehicle_no = $(this).parent().parent().children('td.vehicleNo').html();
            var type = $(this).parent().parent().children('th.type').html();  // a.delete -> td -> tr -> td.name
            var color = $(this).parent().parent().children('th.color').html();
            var model = $(this).parent().parent().children('th.model').html();
            var brand = $(this).parent().parent().children('th.brand').html();
            var description = $(this).parent().parent().children('th.description').html();
            var customerName = $(this).parent().parent().children('td.customerName').html();
            var customerId = $(this).parent().parent().children('th.customerId').html();
            var customerStatus = $(this).parent().parent().children('th.status').html();


            $("#dialog-confirm-content").html("Do you want select customer "+customerName+" ??");
            $("#dialog-confirm").dialog({
                modal: true,
                buttons : {
                    "Confirm" : function() {
                       //----------------cleanup & hide checkbox register------------
                        $(WorkOrder.customer._customerregistercheckbox).parent().removeAttr('class')
                        $(WorkOrder.customer._customerregistercheckbox).removeAttr('checked');
                        $('.check').hide();
                        $(WorkOrder.customer._method).val('list');
                        var flag = $(WorkOrder.customer._method).val();
                        console.log('============');
                        console.log(customerName);
                        console.log('============');
                        WorkOrder.customer._putVehicle(vehicle_id,vehicle_no,type,color,model,brand,description);
                        WorkOrder.customer._putCustomer(customerId,customerName,customerStatus);
                        //display table
                        var vnotice = $(WorkOrder.customer._notice);
                        var vtable = $(WorkOrder.customer._table);
                        var vaddlink = $(WorkOrder.customer._addvehicle);
                        vnotice.hide();
                        vaddlink.hide();
                        vtable.show();
                        $(this).dialog("close");
                        $(WorkOrder.customer._dialog).dialog( "close" );
                        console.log('close dialog');
                    },
                    "Cancel" : function() {
                        console.log('customer not confirm');
                        $(this).dialog("close");
                    }
                }
            });
            $("#dialog-confirm").dialog("open");
            return false;
        });

        $(this._dialogvehicle).dialog({
            autoOpen: false,
            modal: true,
            width: 400,
            buttons: {
                "Save": function () {
                    var success = WorkOrder.customer.save();
                    if(success === true)
                        $(this).dialog('close');
                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            },
            close: function () {
                WorkOrder.customer.closeDialog();
            }
        });

        $(this._dialognewcustomer).dialog({
            autoOpen: false,
            modal: true,
            width: 872,
            buttons: {
                "Save": function () {
                    var success = WorkOrder.customer.save_new_customer();
                    if(success === true)
                        $(this).dialog('close');
                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            }
        });
    },

    //function to open up dialog form for
    openDialog_lst_customer : function(menu) {
        console.log('open up dialog list customer from menu '+menu);
        if (menu=='add') {
            $(this._dialog).load("lst_customer");
        } else if (menu=='edit') {
            $(this._dialog).load("../lst_customer");
        }
        $(this._dialog).dialog('open');
        return false;
    },

    //function to open up dialog form
    openDialog_vehicle : function() {
        $(this._method).val('add');
        console.log($(this._method));
        var vname = $(this._customername);
        vname.html($(this._customernamefield).val());
        console.log('open up dialog form');
        $(this._dialogvehicle).dialog('open');

        //clean up
        $(this._form.no).val('');
        $(this._form.type).val('');
        $(this._form.color).val('');
        $(this._form.model).val('');
        $(this._form.brand).val('');
        $(this._form.desc).val('');
        $(this._addkey).val('');
    },

    selectCustomer : function() {
        var confirmesi= confirm("Do you want select this customer ??");
        if (confirmesi== true) {
            this._putVehicle();
            this._putCustomer();
            this.run_again();
            //display table
            var vnotice = $(this._notice);
            var vtable = $(this._table);
            vnotice.hide();
            vtable.show();
            $(this._dialog).dialog('close');
            console.log('close dialog');
        }else {
            console.log('customer not confirm');
        }
    },
    _putCustomer : function(customerid,customername, customerstatus) {
        //put customer name and status
        $(this._form.customerid).val(customerid);
        $(this._form.customername).val(customername);
        $(this._form.memberstatus).val(customerstatus);
    },
    remove : function() {
        $('#v-rows').remove();
        var vnotice = $(WorkOrder.customer._notice);
        var vtable = $(WorkOrder.customer._table);
        var vrows = $(WorkOrder.customer._rows);
        var vaddlink = $(WorkOrder.customer._addvehicle);
        vrows.val(0);
        vnotice.show();
        vaddlink.show();
        vtable.hide();
    },
    //function to close dialog form
    closeDialog : function() {
        console.log('do closed procedures on dialog form');
        $(this._dialog).dialog('close');
    },
    _putVehicle : function(
        vehicle_id,
        vehicle_no,
        type,
        color,
        model,
        brand,
        description
        ) {
        this.remove();
        //next idx
        var vrows = $(this._rows);
        var vtable = $(this._table);
        var nextidx = vtable.find('tr').length - 1;
        if(vrows.val().trim() !== '0')
            nextidx = parseInt(vrows.val());
        console.log(nextidx);

        //warning : sequence is really important following thead order
        var td = $('<td class="v-no v-num">').append(vehicle_no);
        td.after($('<td class="v-type">').append(type));
        td.after($('<td class="v-color">').append(color));
        td.after($('<td class="v-model">').append(model));
        td.after($('<td class="v-brand">').append(brand));
        td.after($('<td class="v-desc">').append(description));

        var divv = '';
        var flag = $(this._method).val();
        if(flag=='add') {
            divv = $('<div>').append(
                $('<a>')
                    .attr('href', this._tbody)
                    .attr('onclick', 'WorkOrder.customer.edit("v-rows-' + nextidx + '", "' + nextidx + '")')
                    .text('edit | ')
                    .after(
                    $('<a>')
                        .attr('href', this._tbody)
                        .attr('onclick', 'WorkOrder.customer.remove()')
                        .text('remove')
                )
            );
        } else if (flag=='list') {
            divv = $('<div>').append(
                $('<a>')
                    .attr('href', this._tbody)
                    .attr('onclick', 'WorkOrder.customer.remove()')
                    .text('remove')
            );
        }

        td.after($('<td>').append(divv));

        //hidden input
        var hiddiv = $('<div>').css('display', 'none');
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-id-hid')
                .attr('type', 'hidden')
                .attr('name','vehiclesid')
                .val(vehicle_id)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-num-hid')
                .attr('type', 'hidden')
                .attr('id','vehiclesnumber')
                .attr('name','vehiclesnumber')
                .val(vehicle_no)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-type-hid')
                .attr('type', 'hidden')
                .attr('name','vehiclestype')
                .val(type)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-color-hid')
                .attr('type', 'hidden')
                .attr('name','vehiclescolor')
                .val(color)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-brand-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehiclesbrand')
                .val(brand)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-desc-hid')
                .attr('type', 'hidden')
                .attr('name','vehiclesdescription')
                .val(description)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-model-hid')
                .attr('type', 'hidden')
                .attr('name','vehiclesmodel')
                .val(model)
        );
        hiddiv.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','vehiclesstatus')
                .val(1)
        );
        td.after(hiddiv);

        //insert to tr
        var tr = $('<tr>').attr('id','v-rows').append(td);
        console.log(tr);
        //add dynamic rows to vehicle tbody based on submitted vehicle form
        var vtbody = $(this._tbody);
        vtbody.append(tr);

        //updating rows
        vrows.val(++nextidx);
        console.log('rows updated to ' + vrows.val());

    },


    //will be replace as select action
    save : function() {
        var flag = $(this._method).val();
        console.log(flag);
        if(flag == 'add')
            return this.add();
        if(flag == 'edit')
            return this.update();
    },

    add : function() {
        console.log('validate forms first');
        if(this._validateNull() !== true)
            return false;

        var vehicle_no =$(this._form.no).val();
        var type =$(this._form.type).val();
        var color = $(this._form.color).val();
        var model =$(this._form.model).val();
        var brand = $(this._form.brand).val();
        var description =$(this._form.desc).val();

        console.log('add dynamic rows to vehicle tbody');
        this._putVehicle(0,vehicle_no, type, color,model,brand,description);

        //display table
        var vnotice = $(this._notice);
        var vtable = $(this._table);
        var vaddlink = $(this._addvehicle);
        vnotice.hide();
        vaddlink.hide();
        vtable.show();

        return true;
    },

    _validateNull : function() {
        var msg = null;
        if($(this._form.no).val().trim() === '') {
            msg = 'Vehicle Number';
        }
        var required = 'Following fields are required : ' + msg;
        if(msg === null)
            return true;
        else
            alert(required);
        return false;
    },

    //function to open up edit dialog form
    edit : function(id, idx) {
        $(this._method).val('edit');
        var vname = $(this._customername);
        vname.html($(this._customernamefield).val());
        console.log('open up edit dialog form');
        $(this._dialogvehicle).dialog('open');

        console.log(id + ', ' + idx);
        var row = $('#'+id);
        $(this._addkey).val(id);

        //clean up
        $(this._form.no).val('');
        $(this._form.type).val('');
        $(this._form.color).val('');
        $(this._form.model).val('');
        $(this._form.brand).val('');
        $(this._form.desc).val('');
        $(this._addkey).val('');

        var num = $('td.v-no').html();
        var type = $('td.v-type').html();
        var color = $('td.v-color').html();
        var model = $('td.v-model').html();
        var brand = $('td.v-brand').html();
        var desc = $('td.v-desc').html();
        console.log('type '+type);

        //clean up
        $(this._form.no).val(num);
        $(this._form.type).val(type);
        $(this._form.color).val(color);
        $(this._form.model).val(model);
        $(this._form.brand).val(brand);
        $(this._form.desc).val(desc);
    },
    //function to add new vehicle
    update : function() {
        var id = $(this._addkey).val();
        console.log('update rows : ' + id);

        console.log('validate forms first');
        if(this._validateNull() !== true)
            return false;

        var row = $('#'+id);
        console.log(row);
//        var idx = id.slice(-1);

        $('td.v-num').html($(this._form.no).val());
        $('input.v-num-hid').val($(this._form.no).val());

        $('td.v-type').html($(this._form.type).val());
        $('input.v-type-hid').val($(this._form.type).val());

        $('td.v-color').html($(this._form.color).val());
        $('input.v-color-hid').val($(this._form.color).val());

        $('td.v-model').html($(this._form.model).val());
        $('input.v-model-hid').val($(this._form.model).val());

        $('td.v-brand').html($(this._form.brand).val());
        $('input.v-brand-hid').val($(this._form.brand).val());

        $('td.v-desc').html($(this._form.desc).val());
        $('input.v-desc-hid').val($(this._form.desc).val());

        return true;
    },

    run_again : function() {
        //===== Form elements styling =====//
        $("select, .check, .check :checkbox, input:radio, input:file").uniform();
        console.log('rerun select option');

    },
    //function to open up dialog form
    openDialog_newcustomer : function() {
        $(this._method).val('add');
        console.log('open up dialog form new customer');
        $(this._dialognewcustomer).dialog('open');

        //clean up
        $(this._form.newcustomername).val('');
        $(this._form.newcustomeraddress1).val('');
        $(this._form.newcustomeraddress2).val('');
        $(this._form.newcustomercity).val('');
        $(this._form.newcustomerpost_code).val('');
        $(this._form.newcustomerphone1).val('');
        $(this._form.newcustomerphone2).val('');
        $(this._form.newcustomeradditional_info).val('');
    },
    //will be replace as select action
    save_new_customer : function() {
        var msg = '';
        if($(this._form.newcustomername).val().trim() === '') {
            msg += 'Name, ';
        }
        if($(this._form.newcustomeraddress1).val().trim() === '') {
            msg += 'Address 1, ';
        }
        if($(this._form.newcustomerphone1).val().trim() === '') {
            msg += 'Phone 1, ';
        }
        console.log(msg);
        var required = 'Following fields are required : ' + msg;
        if(msg === '') {
            return this.add_new_customer();
        } else{
            this.notification('error', required);
        }
    },
    add_new_customer : function() {
        this.remove();
        var name =$(this._form.newcustomername).val();
        var address1 =$(this._form.newcustomeraddress1).val();
        var address2 = $(this._form.newcustomeraddress2).val();
        var city =$(this._form.newcustomercity).val();
        var post_code = $(this._form.newcustomerpost_code).val();
        var phone1 =$(this._form.newcustomerphone1).val();
        var phone2 =$(this._form.newcustomerphone2).val();
        var additional_info =$(this._form.newcustomeradditional_info).val();

        console.log('add dynamic rows to new customer');
        $(this._form.customerid).val('');
        $(this._form.customername).val(name);
        $(this._form.memberstatus).val('non member');
        var customerData = $(this._divcustomerdatahid);
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','address1')
                .val(address1)
        );
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','address2')
                .val(address2)
        );
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','city')
                .val(city)
        );
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','post_code')
                .val(post_code)
        );
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','phone1')
                .val(phone1)
        );
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','phone2')
                .val(phone2)
        );
        customerData.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','additional_info')
                .val(additional_info)
        );

        //----checked and show register checkbox-----
        $(this._customerregistercheckbox).parent().attr('class', 'checked')
        $(this._customerregistercheckbox).prop('checked','checked');
        $('.check').show();

        return true;
    },
    notification : function(type, message) {
        var div = $(this._form.notif);
        var classNotif = 'nInformation';
        if(type === 'error')
            classNotif = 'nFailure';
        var html = '<div class="nNote ' + classNotif + '" style="margin-top: 0; margin-bottom: 15px;"><p>' + message + '</p></div>';
        div.html(html);
    }
};

WorkOrder.service = {
    //selector helper
    _isselect : new Boolean(),
    _servicetype : '#serviceType',
    _rows   : '#service-rows',
    _table  : '#service-table',
    _tbody  : '#service-tbody',
    _dialogservice : '#service-dialog',
    _addservice : '#add-service',

    //form selector helper
    _form_service   : {
        no     : '#service-no',
        name   : '#service-name',
        price  : '#service-price-',
        desc  : '#desc-'
    },

    initDialog : function() {
        $(this._dialogservice).dialog({
            autoOpen: false,
            modal: true,
            width: 400,
            buttons: {
                "Save": function () {
                    var s_id = $('#serviceType').val();
                    console.log('service type id is '+s_id);
                    var success = WorkOrder.service._addRow(s_id);
                    if(success === true)
                        $(this).dialog('close');
                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            }
        });

        $(WorkOrder.service._addservice).click(function () {
            var s_id = $('#serviceType').val();
            if (s_id > 0) {
                if(WorkOrder.service._validateDuplicate(s_id) !== true)
                    return false;
                var serviceName = $("#serviceType option:selected").text();
                $(WorkOrder.service._dialogservice).html('Are you sure want add service '+serviceName+' ?');
                $(WorkOrder.service._dialogservice).dialog('open');
            } else {
                alert('please select service first');
            }
            return false;
        });
    },
    _validateDuplicate : function(id) {
        var msg = null;
        console.log('id is '+id);
        $(this._tbody).each(function(index) {
            console.log('masuk loop 1');
            $('tr .s-no').each(function(index) {
                console.log('masuk loop '+index);
                    var test = 'input.s-no-hid-'+index;
                    console.log('value '+test);
                    if($(test).val() === id) {
                        msg = 'Service Number';
                    }
            });
        });
        var required = 'Following fields are unique : ' + msg;
        if(msg === null)
            return true;
        else
            alert(required);
        return false;
    },
   _addRow : function(s_id) {
       //next idx
       var vrows = $(this._rows);

       var vtable = $(this._table);
       var nextidx = vtable.find('tr').length - 1;
       if(vrows.val().trim() !== '0'){
           nextidx = parseInt(vrows.val());}
       console.log(nextidx);
       var no = nextidx+1;

       var servicename = $(this._servicetype+' option:selected').text();
       var serviceprice = $(this._form_service.price+s_id).val();
       var servicedesc = $(this._form_service.desc+s_id).val();

       //warning : sequence is really important following thead order
       var td = $('<td class="s-no s-num-' + nextidx + '">').append(no);
       td.after($('<td class="s-name-' + nextidx + '">').append(servicename));
       td.after($('<td class="s-price-' + nextidx + '">').append(serviceprice));
       td.after($('<td class="s-desc-' + nextidx + '">').append(servicedesc));

       var divv = $('<div>').append(
           $('<a>')
               .attr('href', this._tbody)
               .attr('onclick', 'WorkOrder.service.remove("s-rows-' + nextidx + '")')
               .text('remove')
       );
       td.after($('<td>').append(divv));

       //hidden input
       var hiddiv = $('<div>').css('display', 'none');
       hiddiv.append(
           $('<input>')
               .attr('class', 's-no-hid-' + nextidx)
               .attr('type', 'hidden')
               .attr('name','services[' + nextidx + '][service_formula_id]')
               .val(s_id)
       );
       hiddiv.append(
           $('<input>')
               .attr('class', 's-no-hid-' + nextidx)
               .attr('type', 'hidden')
               .attr('name','servicesdata[' + nextidx + '][servicename]')
               .val(servicename)
       );
       hiddiv.append(
           $('<input>')
               .attr('class', 's-no-hid-' + nextidx)
               .attr('type', 'hidden')
               .attr('name','servicesdata[' + nextidx + '][serviceprice]')
               .val(serviceprice)
       );
       hiddiv.append(
           $('<input>')
               .attr('class', 's-no-hid-' + nextidx)
               .attr('type', 'hidden')
               .attr('name','servicesdata[' + nextidx + '][servicedescription]')
               .val(servicedesc)
       );
       td.after(hiddiv);

       //insert to tr
       var tr = $('<tr>').attr('id','s-rows-' + nextidx).append(td);
       console.log(tr);
       //add dynamic rows to vehicle tbody based on submitted vehicle form
       var vtbody = $(this._tbody);
       vtbody.append(tr);

       //updating rows
       vrows.val(++nextidx);
       console.log('rows updated to ' + vrows.val());
       return true;
   },
    remove : function(id) {
        $('#'+id).remove();
        var row = $(this._table).find('tr').length - 1;
        var srowsValue = $(this._rows).val();
        $(this._rows).val(parseInt(srowsValue)-1);
    }
};


WorkOrder.items = {
    //selector helper
    _addkey : '#item-addkey',
    _whead  : '#item-whead',
    _body   : '#item-body',
    _select : 'a.select-item',
    _notice : '#item-addnotice',
    _rows   : '#item-rows',
    _table  : '#item-table',
    _tbody  : '#item-tbody',
    _dialogitems : '#item-dialog',

    //function to initialize dialog form
    initDialog : function() {
        $(this._dialogitems).dialog({
            autoOpen: false,
            modal: true,
            width: 1110,
            height: 681,
            buttons: {
                "Done": function () {
                    $( this ).dialog( "close" );
                }
            }
        });

        $(WorkOrder.items._select).click(function () {
            var isvalid = true;
            var confirm_title = 'Confirmation';
            var confirm_content = 'Your action cannot be undone. Are you sure?';


            //-------------get value from table-----------------
            var type = $(this).parent().parent().children('td.type').html();  // a.delete -> td -> tr -> td.name
            var unit = $(this).parent().parent().children('td.unit').html();
            var code = $(this).parent().parent().children('td.code').html();
            var name = $(this).parent().parent().children('td.name').html();
            var vendor = $(this).parent().parent().children('td.vendor').html();
            var price = $(this).parent().parent().children('td.price').html();
            var total = $(this).parent().parent().children('th.total').html();
            var item_id = $(this).parent().parent().children('th.item-id').html();
            var stock = $(this).parent().parent().children('td.stock').html();
            console.log(':: stock => '+stock);
            if(WorkOrder.items._validateDuplicate(item_id) !== true) {
                isvalid = false;
                confirm_content = 'Sorry this item has been select !';
            }
            if (stock == 0) {
                isvalid = false;
                confirm_content = 'Sorry currently stock this item empty !';
            }
            $("#dialog-confirm").attr('title', confirm_title);
            $("#dialog-confirm-content").html(confirm_content);

            $("#dialog-confirm").dialog({
                modal: true,
                buttons : {
                    "Confirm" : function() {
                        if (isvalid === true) {
                            console.log('============');
                            console.log(name);
                            console.log('============');
                            WorkOrder.items._addRow(item_id,type,unit,code,name,vendor,price,total);
                            var vnotice = $(WorkOrder.items._notice);
                            var vtable = $(WorkOrder.items._table);
                            vnotice.hide();
                            vtable.show();
                            $(this).dialog("close");
                            alert('success add items '+name);
                        } else {
                            $(this).dialog("close");
                        }
                    },
                    "Cancel" : function() {
                        $(this).dialog("close");
                    }
                }
            });
            $("#dialog-confirm").dialog("open");
        });
    },

    //function to open up dialog items list
    openDialog_lst_items : function(menu) {
        console.log('open up dialog form list items');
        if (menu=='add') {
            $(this._dialogitems).load("lst_items");
        } else if (menu=='edit') {
            $(this._dialogitems).load("../lst_items");
        }
        $(this._dialogitems).dialog('open');
        return false;
    },

    _validateDuplicate : function(id) {
        var msg = null;
        console.log('id is '+id);
        $(this._tbody).each(function(index) {
            console.log('masuk loop 1');
            $('tr .i-no').each(function(index) {
                console.log('masuk loop '+index);
                var test = 'input.i-no-hid-'+index;
                console.log('value '+test);
                if($(test).val() === id) {
                    msg = 'Service Number';
                }
            });
        });
        var required = 'Following fields are unique : ' + msg;
        if(msg === null)
            return true;
        else
//            alert(required);
        return false;
    },

    _addRow : function(item_id,type,unit,code,name,vendor,price,total) {
        //next idx
        var irows = $(this._rows);
        var stable = $(this._table);
        var nextidx = stable.find('tr').length - 1;
        if(irows.val().trim() !== '0'){
            nextidx = parseInt(irows.val());}
        console.log(nextidx);
        //warning : sequence is really important following thead order
        var td = $('<td class="i-no i-type-' + nextidx + '">').append(type);
        td.after($('<td class="i-unit-' + nextidx + '">').append(unit));
        td.after($('<td class="i-code-' + nextidx + '">').append(code));
        td.after($('<td class="i-name-' + nextidx + '">').append(name));
        td.after($('<td class="i-vendor-' + nextidx + '">').append(vendor));
        td.after($('<td class="i-price-' + nextidx + '">').append(price));
        td.after($('<td class="i-total-' + nextidx + '">').append(
            $('<input>')
                .attr('style', 'width: 30px;')
                .attr('type', 'text')
                .attr('id','item-quantity-' + nextidx)
                .attr('name','items[' + nextidx + '][quantity]')
                .val(1)
        )); //just temporary

        var divv = $('<div>').append(
            $('<a>')
                .attr('href', this._tbody)
                .attr('onclick', 'WorkOrder.items.remove("i-rows-' + nextidx + '")')
                .text('remove')
        );
        td.after($('<td>').append(divv));

        //hidden input
        var hiddiv = $('<div>').css('display', 'none');
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][item_id]')
                .val(item_id)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','itemsdata[' + nextidx + '][itemtype]')
                .val(type)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','itemsdata[' + nextidx + '][itemunit]')
                .val(unit)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','itemsdata[' + nextidx + '][itemcode]')
                .val(code)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','itemsdata[' + nextidx + '][itemname]')
                .val(name)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','itemsdata[' + nextidx + '][itemvendor]')
                .val(vendor)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','itemsdata[' + nextidx + '][itemprice]')
                .val(price)
        );
        td.after(hiddiv);

        //insert to tr
        var tr = $('<tr>').attr('id','i-rows-' + nextidx).append(td);
        console.log(tr);
        //add dynamic rows to vehicle tbody based on submitted vehicle form
        var vtbody = $(this._tbody);
        vtbody.append(tr);

        $('#item-quantity-'+nextidx).spinner({min:0, max:100, showOn:'both'});
        console.log('Total ID Spinner ' + '#item-quantity-'+nextidx);
        //updating rows
        irows.val(++nextidx);
        console.log('rows updated to ' + irows.val());
        return true;
    },
    remove : function(id) {
        $('#'+id).remove();
        var row = $(this._table).find('tr').length - 1;
        var irowsValue = $(this._rows).val();
        $(this._rows).val(parseInt(irowsValue)-1);
    }
};

WorkOrder.mechanic = {
    //selector helper
    _isselect : new Boolean(),
    _mechanic : '#mechanic',
    _mechanicname : '#mechanic option:selected',
    _rows   : '#mechanic-rows',
    _table  : '#mechanic-table',
    _tbody  : '#mechanic-tbody',
    _dialogmechanic : '#mechanic-dialog',
    _addmechanic : '#add-mechanic',


    initDialog : function() {
        $(this._dialogmechanic).dialog({
            autoOpen: false,
            modal: true,
            width: 400,
            buttons: {
                "Save": function () {
                    var s_id =  $(WorkOrder.mechanic._mechanic).val();
                    console.log('mechanic id is '+s_id);
                    var success = WorkOrder.mechanic._addRow(s_id);
                    if(success === true)
                        $(this).dialog('close');
                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            }
        });

        $(WorkOrder.mechanic._addmechanic).click(function () {
            var s_id = $(WorkOrder.mechanic._mechanic).val();
            if (s_id > 0) {
                if(WorkOrder.mechanic._validateDuplicate(s_id) !== true)
                    return false;
                var mechanicName = $(WorkOrder.mechanic._mechanicname).text();
                $(WorkOrder.mechanic._dialogmechanic).html('Are you sure want assign mechanic '+mechanicName+' ?');
                $(WorkOrder.mechanic._dialogmechanic).dialog('open');
            } else {
                alert('please select Mechanic first');
            }
            return false;
        });
    },
    _validateDuplicate : function(id) {
        var msg = null;
        console.log('id is '+id);
        $(this._tbody).each(function(index) {
            console.log('masuk loop 1');
            $('tr .m-no').each(function(index) {
                console.log('masuk juga loop '+index);
                var test = 'input.m-no-hid-'+index;
                console.log('value '+test);
                if($(test).val() === id) {
                    msg = 'Mechanic Number';
                }
            });
        });
        var required = 'Following fields are unique : ' + msg;
        if(msg === null)
            return true;
        else
            alert(required);
        return false;
    },
    _addRow : function(s_id) {
        //next idx
        var mrows = $(this._rows);
        var mtable = $(this._table);
        var nextidx = mtable.find('tr').length - 1;
        if(mrows.val().trim() !== '0'){
            nextidx = parseInt(mrows.val());}
        console.log(nextidx);
        var no = nextidx+1;

        var mechanicName = $(this._mechanicname).text();

        //warning : sequence is really important following thead order
        var td = $('<td class="m-no m-num-' + nextidx + '">').append(no);
        td.after($('<td class="m-name-' + nextidx + '">').append(mechanicName));

        var divv = $('<div>').append(
            $('<a>')
                .attr('href', this._tbody)
                .attr('onclick', 'WorkOrder.mechanic.remove("m-rows-' + nextidx + '")')
                .text('remove')
        );
        td.after($('<td>').append(divv));

        //hidden input
        var hiddiv = $('<div>').css('display', 'none');
        hiddiv.append(
            $('<input>')
                .attr('class', 'm-no-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','users[' + nextidx + '][user_id]')
                .val(s_id)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'm-no-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','usersdata[' + nextidx + '][mechanicname]')
                .val(mechanicName)
        );
        td.after(hiddiv);

        //insert to tr
        var tr = $('<tr>').attr('id','m-rows-' + nextidx).append(td);
        console.log(tr);
        //add dynamic rows to vehicle tbody based on submitted vehicle form
        var vtbody = $(this._tbody);
        vtbody.append(tr);

        //updating rows
        mrows.val(++nextidx);
        console.log('rows updated to ' + mrows.val());
        return true;
    },
    remove : function(id) {
        $('#'+id).remove();
        var row = $(this._table).find('tr').length - 1;
    }
}