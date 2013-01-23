$(function() {

    $( ".datepicker" ).datepicker({
        showOtherMonths:true,
        autoSize: true,
        appendText: '(dd-mm-yyyy)',
        dateFormat: 'dd-mm-yy'
    });

    //===== Time picker =====//

    $('.timepicker').timeEntry({
        show24Hours: true, // 24 hours format
        showSeconds: true, // Show seconds?
        spinnerImage: '/images/elements/ui/spinner.png', // Arrows image
        spinnerSize: [19, 26, 0], // Image size
        spinnerIncDecOnly: true // Only up and down arrows
    });

    //===== init item dialog =====//
    Account.Item.initDialog();

    var opts = {
        'item-tax': {
            stepping: 0.01,
            min: 0,
            suffix: '%'
        }
    };

    for (var n in opts)
        $("#"+n).spinner(opts[n]);


    //===== Dynamic data table =====//

    oTable = $('.dTableAccount').dataTable({
        "bJQueryUI": false,
        "bAutoWidth": true,
        "sPaginationType": "full_numbers",
        "sDom":  'T<"clear"><"H"lf>rt<"F"ip>',
        "oTableTools": {
            "sSwfPath": "../media/swf/copy_csv_xls_pdf.swf",
            "mColumns": "visible",
            "aButtons":    [
                {
                    "sExtends":    "copy",
                    "bSelectedOnly": "true",
                    "sButtonText": "Copy To Cliboard"
                },
                {
                    "sExtends": "xls",
                    "sPdfOrientation": "landscape",
                    "sButtonText": "Save to Excel"
                },
                {
                    "sExtends": "pdf",
                    "sPdfOrientation": "landscape",
                    "sButtonText": "Save to PDF"
                }
            ]
        },
        "sScrollX": "100%",
        "sScrollXInner": "220%"
    });

    console.log(oTable.attr('dtable-sortlist'));
    try {
        if(oTable) {
            //console.log(oTable.attr('dtable-sortlist'));
            if(oTable.attr('dtable-sortlist')) {
                oTable.fnSort( eval(oTable.attr('dtable-sortlist')) );
            }
        }
    } catch (err) {
        console.log(err);
    }

});

var Account = {};
Account.Item = {
    //selector helper
    _method : '#item-method',
    _addkey : '#item-addkey',
    _whead  : '#item-whead',
    _body   : '#item-body',
    _notice : '#item-addnotice',
    _rows   : '#item-rows',
    _table  : '#item-table',
    _tbody  : '#item-tbody',
    _dialog : '#item-dialog',
    _accountname : '#item-account-name',
    _accountnamefield : '#account-name',

    //total
    _subtotal : '#item-subtotal',
    _subtotaltax : '#item-subtotal-tax',
    _total : '#item-total',

    //form selector helper
    _form   : {
        item   : '#item-info',
        desc   : '#item-description',
        qty    : '#item-quantity',
        price  : '#item-unit-price',
        disc   : '#item-discount',
        account: '#item-account-type',
        tax    : '#item-tax',
        taxamount : '#item-tax-amount',
        amount : '#item-amount',
        notif  : '#item-dialog-notification',
        approved_status  : '#item-approved-status',
        id : '#item-id'
    },

    //function to initialize dialog form
    initDialog : function() {
        $(this._dialog).dialog({
            autoOpen: false,
            modal: true,
            width: 700,
            buttons: {
                "Save": function () {
                    var success = Account.Item.save();
                    if(success === true)
                        $(this).dialog('close');
                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            },
            close: function () {
                Account.Item.closeDialog();
            }
        });
    },

    //function to open up dialog form
    openDialog : function() {
        $(this._method).val('add');
        console.log($(this._method));
        var vname = $(this._accountname);
        vname.html($(this._accountnamefield).val());
        console.log('open up dialog form');
        $(this._dialog).dialog('open');

        //clean up
        $(this._form.item).val('');
        $(this._form.desc).val('');
        $(this._form.disc).val('');
        $(this._form.account).val('');
        $(this._form.amount).val('0');
        $(this._form.price).val('');
        $(this._form.qty).val('');
        $(this._form.tax).val('');
        $(this._form.taxamount).val('');
        $(this._form.approved_status).val('');
        $(this._form.id).val('');
        $(this._addkey).val('');
    },

    //function to close dialog form
    closeDialog : function() {
        console.log('do closed procedures on dialog form');
        var vnotice = $(this._notice);
        var vtable = $(this._table);
        var vrows = $(this._rows);
        if(vrows.val() == '0') {
            console.log('no item registered, show notice again');
            vnotice.show();
            vtable.hide();
        }
        //clean up
        $(this._accountname).html('');
    },

    save : function() {
        var flag = $(this._method).val();
        console.log(flag);
        if(flag == 'add')
            return this.add();
        if(flag == 'edit')
            return this.update();
    },

    //function to add new item
    add : function() {
        console.log('validate forms first');
        if(this._validateNull() !== true)
            return false;

        if(this._validateLength() !== true)
            return false;

        if(this._validateDuplicate() !== true)
            return false;

        console.log('add dynamic rows to item tbody');
        this._addRow();

        //display table
        var vnotice = $(this._notice);
        var vtable = $(this._table);
        vnotice.hide();
        vtable.show();

        return true;
    },

    _addRow : function() {
        //next idx
        var vrows = $(this._rows);
        var vtable = $(this._table);
        var nextidx = vtable.find('tr').length - 1;
        if(vrows.val().trim() !== '0')
            nextidx = parseInt(vrows.val());
        console.log(nextidx);

        //warning : sequence is really important following thead order
        var td = $('<td class="v-item v-num-' + nextidx + '">').append($(this._form.item).val());
        //td.after($('<td class="v-desc-' + nextidx + '">').append($(this._form.desc).val()));
        td.after($('<td class="v-qty-' + nextidx + '">').append($(this._form.qty).val()));
        //td.after($('<td class="v-price-' + nextidx + '">').append($(this._form.price).val()));
        //td.after($('<td class="v-disc-' + nextidx + '">').append($(this._form.disc).val()));
        //extract account
        var aidx = $(Account.Item._form.account).find(':selected').attr('id');
        console.log('aidx : ' + aidx);
        aidx = aidx.substring((aidx.lastIndexOf('-')) + 1);
        console.log('id : ' + aidx);
        var account_name = $('#select-account-id-' + aidx).text();
        console.log('Account for item :');
        console.log($('#select-account-id-' + aidx));
        td.after($('<td class="v-account-' + nextidx + '">').append(account_name));
        td.after($('<td class="v-tax-' + nextidx + '">').append($(this._form.tax).val()));
        td.after($('<td class="v-tax-amount-' + nextidx + '">').append($(this._form.taxamount).val()));
        td.after($('<td class="v-amount-' + nextidx + '">').append(toFixed($(this._form.amount).val(),2)));

        var divv = $('<div>').append(
            $('<a>')
                .attr('href', this._tbody)
                .attr('onclick', 'Account.Item.edit("v-rows-' + nextidx + '", "' + nextidx + '")')
                .text('edit | ')
                .after(
                $('<a>')
                    .attr('href', this._tbody)
                    .attr('onclick', 'Account.Item.remove("v-rows-' + nextidx + '")')
                    .text('remove')
            )
        );
        td.after($('<td>').append(divv));

        //hidden input
        var hiddiv = $('<div>').css('display', 'none');
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-item-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][item]')
                .val($(this._form.item).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-desc-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][description]')
                .val($(this._form.desc).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-qty-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][quantity]')
                .val($(this._form.qty).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-unit-price-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][unit_price]')
                .val($(this._form.price).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-disc-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][discount]')
                .val($(this._form.disc).val())
        );
        var aidx = $(Account.Item._form.account).find(':selected').attr('id');
        aidx = aidx.substring((aidx.lastIndexOf('-')) + 1);
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-account-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][account_type_id]')
                .val($('#select-account-id-' + aidx).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-tax v-tax-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][tax]')
                .val($(this._form.tax).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-tax-amount v-tax-amount-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][tax_amount]')
                .val($(this._form.taxamount).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-amount v-amount-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][amount]')
                .val($(this._form.amount).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-approved-status v-approved-status-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][approved_status]')
                .val('O')
        );
        hiddiv.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','items[' + nextidx + '][status]')
                .val(1)
        );
        td.after(hiddiv);

        //insert to tr
        var tr = $('<tr>').attr('id','v-rows-' + nextidx).append(td);
        console.log(tr);
        //add dynamic rows to item tbody based on submitted item form
        var vtbody = $(this._tbody);
        vtbody.append(tr);

        //updating rows
        vrows.val(++nextidx);
        console.log('rows updated to ' + vrows.val());

        this.calculateTotal();

    },

    remove : function(id) {
        $('#'+id).remove();
        var row = $(this._table).find('tr').length - 1;
        if(row <= 0) {
            $(this._notice).show();
            $(this._table).hide();
            $(this._rows).val(0);
        }
    },

    _validateNull : function() {
        var msg = '';
        if($(this._form.item).val().trim() === '') {
            msg += 'Item Information';
        }
        if($(this._form.qty).val().trim() === '') {
            msg += ', Quantity';
        }
        if($(this._form.price).val().trim() === '') {
            msg += ', Price';
        }
        if($(this._form.tax).val().trim() === '') {
            msg += ', Tax Percentage';
        }
        var required = 'Following fields are required : ' + msg;
        if(msg === '')
            return true;
        else
            this.notification('error', required);
        return false;
    },

    _validateDuplicate : function(id) {
        var msg = null;
        $(this._tbody).each(function(index) {
            $('tr .v-item').each(function(index) {
                if($(this).text() === $(Account.Item._form.item).val().trim()) {
                    if($(this).parent().attr('id') !== id)
                        msg = 'Item Information';
                }
            });
        });
        var required = 'Following fields must be unique : ' + msg;
        if(msg === null)
            return true;
        else
            this.notification('error', required);
        return false;
    },

    _validateLength : function() {
        var msg = null;
        if($(this._form.item).val().trim().length < 5) {
            msg = 'Item Information length must be more than 5 characters';
        }
        if(msg === null)
            return true;
        else
            this.notification('error', msg);
        return false;
    },

    //function to open up edit dialog form
    edit : function(id, idx) {
        $(this._method).val('edit');
        var vname = $(this._accountname);
        vname.html($(this._accountnamefield).val());
        console.log('open up edit dialog form');
        $(this._dialog).dialog('open');

        console.log(id + ', ' + idx);
        var row = $('#'+id);
        console.log(row);

        $(this._addkey).val(id);

        var item = row.find('.v-item-hid-' + idx);
        var desc = row.find('.v-desc-hid-' + idx);
        var qty = row.find('.v-qty-hid-' + idx);
        var price = row.find('.v-unit-price-hid-' + idx);
        console.log(price);
        var disc = row.find('.v-disc-hid-' + idx);
        var account = row.find('.v-account-hid-' + idx);
        var tax = row.find('.v-tax-hid-' + idx);
        var taxamount = row.find('.v-tax-amount-hid-' + idx);
        var amount = row.find('.v-amount-hid-' + idx);
        var approved_status = row.find('.v-approved-status-hid-' + idx);
        var item_id = row.find('.v-id-hid-' + idx);

        //clean up
        $(this._form.item).val(item.val());
        $(this._form.desc).val(desc.val());
        $(this._form.qty).val(qty.val());
        $(this._form.price).val(price.val());
        $(this._form.disc).val(disc.val());
        $(this._form.tax).val(tax.val());
        $(this._form.taxamount).val(taxamount.val());
        $(this._form.amount).val(amount.val());
        $(this._form.approved_status).val(approved_status.val());
        $(this._form.id).val(item_id.val());

        console.log($(this._form.account));
        $(this._form.account).find('option').each(function(idx) {
            if(this.value == account.val()) {
                console.log(account);
                var aidx = account.val();
                aidx = aidx.substring((aidx.lastIndexOf('-')) + 1);
                var temp = $('#select-account-id-' + aidx);
                console.log(temp);
                $(this).attr('selected' , 'selected');
                $('#uniform-item-account-type').find('span').html(temp.text());
            }
        });
    },

    //function to add new item
    update : function() {
        var id = $(this._addkey).val();
        console.log('update rows : ' + id);

        console.log('validate forms first');
        if(this._validateNull() !== true)
            return false;

        if(this._validateLength() !== true)
            return false;

        if(this._validateDuplicate(id) !== true)
            return false;

        var row = $('#'+id);
        console.log(row);
        var idx = id.substring((id.lastIndexOf('-')) + 1);;

        var item = row.find('.v-item.v-num-' + idx);
        var itemhid = row.find('.v-item-hid-' + idx);
        item.html($(this._form.item).val());
        itemhid.val($(this._form.item).val());

        var desc = row.find('.v-desc-' + idx);
        var deschid = row.find('.v-desc-hid-' + idx);
        desc.html($(this._form.desc).val());
        deschid.val($(this._form.desc).val());

        var qty = row.find('.v-qty-' + idx);
        var qtyhid = row.find('.v-qty-hid-' + idx);
        qty.html($(this._form.qty).val());
        qtyhid.val($(this._form.qty).val());

//        var price = row.find('.v-unit-price-' + idx);
        var pricehid = row.find('.v-unit-price-hid-' + idx);
//        price.html($(this._form.price).val());
        pricehid.val($(this._form.price).val());

//        var disc = row.find('.v-disc-' + idx);
        var dischid = row.find('.v-disc-hid-' + idx);
//        disc.html($(this._form.disc).val());
        dischid.val($(this._form.disc).val());

        //extract account
        var aidx = $(Account.Item._form.account).find(':selected').attr('id');
        console.log('aidx : ' + aidx);
        aidx = aidx.substring((aidx.lastIndexOf('-')) + 1);
        console.log('id : ' + aidx);
        var account_name = $('#select-account-id-' + aidx).text();
        console.log('Account for item :');
        console.log($('#select-account-id-' + aidx));
        var account_name = $('#select-account-id-' + aidx).text();
        var account = row.find('.v-account-' + idx);
        var accounthid = row.find('.v-account-hid-' + idx);
        account.html(account_name);
        accounthid.val($(this._form.account).val());

        var tax = row.find('.v-tax-' + idx);
        var taxhid = row.find('.v-tax-hid-' + idx);
        tax.html($(this._form.tax).val());
        taxhid.val($(this._form.tax).val());

        var taxamount = row.find('.v-tax-amount-' + idx);
        var taxamounthid = row.find('.v-tax-amount-hid-' + idx);
        taxamount.html($(this._form.taxamount).val());
        taxamounthid.val($(this._form.taxamount).val());

        var amount = row.find('.v-amount-' + idx);
        var amounthid = row.find('.v-amount-hid-' + idx);
        amount.html(toFixed($(this._form.amount).val(),2));
        amounthid.val($(this._form.amount).val());

        var approved_status_hid = row.find('v-approved-status-hid-' + idx);
        approved_status_hid.val($(this._form.approved_status).val());

        var item_id = row.find('v-id-hid-' + idx);
        item_id.val($(this._form.id).val());

        this.calculateTotal();

        return true;
    },

    notification : function(type, message) {
        var div = $(this._form.notif);
        var classNotif = 'nInformation';
        if(type === 'error')
            classNotif = 'nFailure';
        var html = '<div class="nNote ' + classNotif + '" style="margin-top: 0; margin-bottom: 15px;"><p>' + message + '</p></div>';
        div.html(html);
    },

    calculateAmount : function() {
        console.log('DEBUG.....');
        var qty = $(this._form.qty).val().trim() == '' ? 0 : $(this._form.qty).val().trim();
        var disc = $(this._form.disc).val().trim() == '' ? 0 : $(this._form.disc).val().trim();
        var price = $(this._form.price).val().trim() == '' ? 0 : $(this._form.price).val().trim();
        var tax = $(this._form.tax).val().trim() == '' ? 0 : $(this._form.tax).val().trim();
        var amount = ( parseFloat(qty) * parseFloat(price) ) - parseFloat(disc);
        console.log('nett amount : ' + amount);
        var taxamount = (amount * parseFloat(tax) / 100);
        console.log('tax amount : ' + taxamount);
        amount = amount + taxamount;
        amount = toFixed(amount, 2);
        $(this._form.amount).val(amount);
        taxamount = toFixed(taxamount, 2);
        $(this._form.taxamount).val(taxamount);
    },

    calculateTotal : function() {
        var subtotaldiv = $(this._subtotal);
        var subtotaltaxdiv = $(this._subtotaltax);
        var totaldiv = $(this._total);

        var idxall = 0;
        var total = new Array();
        $('.v-amount').each(function(idx){
            var t = this.value.trim() == '' ? 0 : this.value.trim();
            total[idx] = parseFloat(t);
            idxall++;
        });
        console.log(total);

        var tax = new Array();
        var taxamount = new Array();
        $('.v-tax').each(function(idx){
            var t = this.value.trim() == '' ? 0 : this.value.trim();
            tax[idx] = parseFloat(t);
            taxamount[idx] = total[idx] * tax[idx] / 100;
        });
        console.log(tax);

        var subtotal = 0;
        var taxtotal = 0;
        var taxtotalamount = 0;
        var amount = 0;
        for(i=0; i<idxall; i++) {
            console.log('tax['+i+'] : ' + tax[i]);
            console.log('total['+i+'] : ' + total[i]);
            console.log('tax amount['+i+']: ' + taxamount[i]);

            subtotal += total[i];
            taxtotal += tax[i];
            taxtotalamount += taxamount[i];
        }
        amount = subtotal - taxtotalamount;

        taxtotalamount = toFixed(taxtotalamount, 2);
        amount = toFixed(amount, 2);
        subtotal = toFixed(subtotal, 2);

        console.log('tax : ' + taxtotal);
        console.log('tax amount : ' + taxtotalamount);
        console.log('total : ' + subtotal);
        console.log('amount : ' + amount);

        subtotaldiv.html(number_format(amount));
        subtotaltaxdiv.html(number_format(taxtotalamount));
        totaldiv.html(number_format(subtotal));

    }

};

