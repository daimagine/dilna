//===== Wizards =====//
$(function() {

    $('#dialogNewItem').click(function () {
        $('#formDialogNewItem').dialog('close');
        return false;
    });

    //===== closed approved dialog confirmation=====//
    $('#formDialogApproved').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        resizable: false,
        buttons: {
            "Yes I'm sure": function() {
                document.formEBengkel.submit();
                $(this).dialog("close");
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    });

    $('#dialog-confirm').dialog({
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
    //===== Validation engine =====//

    $("#formEBengkel").validationEngine();

    $('#confirm-dialog').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        resizable: false,
        buttons: {
            "Submit Form": function() {
                document.formEBengkel.submit();
                $(this).dialog("close");
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    });

    $('form#formEBengkel').submit(function(){
        var stockopname = $("#item-stock-opname").val();
        var itemrows = $("#item-rows").val();
        if (stockopname!=null && itemrows!=null && stockopname!='' && itemrows!='') {
            $("#formDialogApproved").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>' +
                ' Are you sure the data is correct and you want to closed this approved print ?' +
                '</p>');
            document.formEBengkel.action.value = 'confirm';
            $("#formDialogApproved").dialog('open');
        } else {
            $("#dialog-confirm").attr('title', 'Notification');
            $("#dialog-confirm-content").html('Please select or add new item');
            $("#dialog-confirm").dialog('open');

        }
        return false;
    });


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

    items.approved.initDialog();
});

var items = {};

items.approved = {
    //selector helper
    _addkey : '#item-addkey',
    _stokopname : 0,
    _whead  : '#item-whead',
    _body   : '#item-body',
    _select : 'a.select-item',
    _notice : '#item-addnotice',
    _rows   : '#item-rows',
    _table  : '#item-table',
    _tbody  : '#item-tbody',
    _putitembtn  : '#putitem-button',
    _dialogitems : '#formDialogListItem',
    _dialognewitems : '#formDialogNewItem',
    _msgvalidate : null,
    _method : '',

    _form : {
        notif  : '#newitem-dialog-notification',
        type : '#item_type_id',
        name : '#itemname',
        price : '#itemprice',
        purchaseprice : '#itempurchase_price',
        stock : '#itemstock',
        unit : '#unit_id',
        vendor : '#itemvendor',
        status : '#itemstatus',
        code : '#itemcode',
        desc : '#description',
        itemcategory : '#itemcategoryid'
},
    //function to initialize dialog form
    initDialog : function() {
        $(this._dialogitems).dialog({
            autoOpen: false,
            width: 1110,
            height: 681,
            modal:true,
            buttons: {
                "Close": function() {
                    $( this ).dialog( "close" );
                }
            }
        });

        $(items.approved._select).click(function () {
            var isvalid = true;
            var confirm_title = 'Confirmation';
            var confirm_content = 'You want selet this item, if yes please Type result stock opname first, and then press button yes <br/>' +
                '<input type="text" value="0" id="stockopname"/>';

            //-------------get value from table-----------------
            var type = $(this).parent().parent().children('td.type').html();  // a.delete -> td -> tr -> td.name
            var unit = $(this).parent().parent().children('td.unit').html();
            var code = $(this).parent().parent().children('td.code').html();
            var name = $(this).parent().parent().children('td.name').html();
            var vendor = $(this).parent().parent().children('td.vendor').html();
            var total = $(this).parent().parent().children('td.total').html();
            var item_id = $(this).parent().parent().children('th.item-id').html();
            var stock = $(this).parent().parent().children('td.stock').html();
            console.log(':: stock => '+stock);
            if(items.approved._validateDuplicate(item_id) !== true) {
                isvalid = false;
                confirm_content = 'Sorry this item has been select !';
            }
            $("#dialog-confirm").attr('title', confirm_title);
            $("#dialog-confirm-content").html(confirm_content);

            $("#dialog-confirm").dialog({
                modal: true,
                buttons : {
                    "Confirm" : function() {
                        if (isvalid === true) {
                            var stockOpname = $('#stockopname').val();
                            items.approved._method='list';
                            items.approved._addRow(item_id,type,unit,code,name,vendor,stockOpname,stock,0,0,'','');
                            $(items.approved._dialogitems).dialog("close");
                            $(items.approved._putitembtn).hide();
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


        ///================this for new item===================///
        // Dialog
        $(items.approved._dialognewitems).dialog({
            autoOpen: false,
            width: 850,
            modal:true,
            resizable: false,
            buttons: {
                "Save": function() {
                    if (items.approved.validatenull_newitem()) {
                        items.approved._method='new';
                        var type = $(items.approved._form.type).val();
                        var unit = $(items.approved._form.unit).val();
                        var name = $(items.approved._form.name).val();
                        var code = $(items.approved._form.code).val();
                        var sellPrice = $(items.approved._form.price).val();
                        var purcPrice = $(items.approved._form.purchaseprice).val();
                        var stockOpname = $(items.approved._form.stock).val();
                        var vendor = $(items.approved._form.vendor).val();
                        var desc = $(items.approved._form.desc).val();
                        var status = $(items.approved._form.status).val();
                        var category = $(items.approved._form.itemcategory).val();
                        $(items.approved._putitembtn).hide();
                        items.approved._addRow(0,type,unit,code,name,vendor,stockOpname,0,sellPrice,purcPrice,desc,category);
                        $( this ).dialog( "close" );
                    } else {
                        items.approved.notification('error', items.approved._msgvalidate);
                    }
                },
                "Close": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    },

    //function to open up dialog items list
    openDialog_lst_items : function() {
        console.log('open up dialog form list items');
        $(this._dialogitems).load("../lst_item/");
        $(this._dialogitems).dialog('open');
        return false;
    },
    //function to open up dialog items list
    openDialog_new_items : function(categoryId, categoryName) {
        console.log(':: open up dialog form new items '+categoryName);
        $(this._dialognewitems).load("../putnewitem/"+categoryId);
        $('#formDialogNewItem').attr('title', 'Form New Items '+categoryName);
        $(this._dialognewitems).dialog('open');
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

    _addRow : function(item_id,type,unit,code,name,vendor,stockOpname,stock,sellPrice,purcPrice,desc,categoryId) {
        console.log('------------------------------------');
        console.log(':: vendor  ['+vendor+']');
        console.log(':: stock   ['+stock+']');
        console.log(':: opname  ['+stockOpname+']');
        console.log('------------------------------------');
        //next idx
        var irows = $(this._rows);
        var stable = $(this._table);
        var nextidx = stable.find('tr').length - 1;
        if(irows.val().trim() !== '0'){
            nextidx = parseInt(irows.val());}
        console.log(nextidx);
        //warning : sequence is really important following thead order
        var td = $('<td class="i-no i-type">').append(type);
//        td.after($('<td class="i-unit">').append(unit));
        td.after($('<td class="i-code">').append(code));
        td.after($('<td class="i-name">').append(name));
        td.after($('<td class="i-vendor">').append(vendor));
        td.after($('<td class="i-currentstock">').append(stock));
        td.after($('<td class="i-stokopname">').append(
            $('<input>')
                .attr('type', 'text')
                .attr('id','item-stock-opname')
                .attr('name','stock_opname')
                .val(stockOpname)));

        var divv = $('<div>').append(
            $('<a>')
                .attr('href', this._tbody)
                .attr('onclick', 'items.approved.remove("i-rows-' + nextidx + '")')
                .text('remove')
        );
        td.after($('<td>').append(divv));

        //hidden input
        var hiddiv = $('<div>').css('display', 'none');
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','item_id')
                .val(item_id)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','item_category_id')
                .val(categoryId)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','item_type_id')
                .val(type)
        );

        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','unit_id')
                .val(unit)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','name')
                .val(name)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','code')
                .val(code)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','description')
                .val(desc)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','price')
                .val(sellPrice)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','price')
                .val(sellPrice)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','purchase_price')
                .val(purcPrice)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','vendor')
                .val(vendor)
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'i-no-hid')
                .attr('type', 'hidden')
                .attr('name','status')
                .val(1)
        );
        td.after(hiddiv);

        //insert to tr
        var tr = $('<tr>').attr('id','i-rows-' + nextidx).append(td);
        console.log(tr);
        //add dynamic rows to vehicle tbody based on submitted vehicle form
        var vtbody = $(this._tbody);
        vtbody.append(tr);

        $('#item-stock-opname').spinner({min:0, max:100, showOn:'both'});
        //updating rows
        irows.val(++nextidx);
        console.log('rows updated to ' + irows.val());
        return true;
    },
    remove : function(id) {
        $('#'+id).remove();
        var row = $(this._table).find('tr').length - 1;
        $(this._putitembtn).show();
        var irowsValue = $(this._rows).val();
        $(this._rows).val(parseInt(irowsValue)-1);
    },
    validatenull_newitem : function(){
        var msg = '';
        if ($(this._form.type).val().trim() === '')
            msg += 'Item Type, ';
        if ($(this._form.name).val().trim() === '')
            msg += 'Item Name, ';
        if ($(this._form.price).val().trim() === '')
            msg += 'Selling Price, ';
        if ($(this._form.purchaseprice).val().trim() === '')
            msg += 'Purchase Price, ';
        if ($(this._form.stock).val().trim() === '')
            msg += 'Quantity Opname, ';
        if ($(this._form.unit).val() === '')
            msg += 'Unit Type, ';
        if ($(this._form.code).val() === '')
            msg += 'Code, ';
        if ($(this._form.vendor).val() === '')
            msg += 'Vendor, ';
        if ($(this._form.status).val() === '')
            msg += 'Status, ';
        if (msg === '') {
            return true;
        } else {
            this._msgvalidate='Following field required ['+msg+']';
            return false;
        }
    },
    notification : function(type, message) {
        var div = $(this._form.notif);
        var classNotif = 'nInformation';
        if(type === 'error')
            classNotif = 'nFailure';
        var html = '<div class="nNote ' + classNotif + '" style="margin-top: 0; margin-bottom: 15px;"><p>' + message + '</p></div>';
        div.html(html);
    },
    calculateStock : function(){
        var stockOpname = $('#item-stock-opname').val();
        var currentStock = $('td.i-currentstock').html();
        console.log('----------------------');
        console.log(':: stock opname  ['+stockOpname+']');
        console.log(':: stock current ['+currentStock+']');
        console.log('----------------------');
        $('td.i-total').html(parseInt(stockOpname)+parseInt(currentStock));
    }
};

function getListItem(categoryId){
    $('#formDialogListItem').load("../lst_item/"+categoryId);
    $('#formDialogListItem').dialog('open');

}

function formNewItem(categoryId){
    $('#formDialogNewItem').load("../putnewitem/"+categoryId);
    $('#formDialogNewItem').dialog('open');
}

function confirmApproved(){

}
