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

    //===== init vehicle dialog =====//
    Customer.Vehicle.initDialog();

});

var Customer = {};
Customer.Vehicle = {
    //selector helper
    _method : '#vehicle-method',
    _addkey : '#vehicle-addkey',
    _whead  : '#vehicle-whead',
    _body   : '#vehicle-body',
    _notice : '#vehicle-addnotice',
    _rows   : '#vehicle-rows',
    _table  : '#vehicle-table',
    _tbody  : '#vehicle-tbody',
    _dialog : '#vehicle-dialog',
    _customername : '#vehicle-customer-name',
    _customernamefield : '#customer-name',

    //form selector helper
    _form   : {
        no     : '#vehicle-no',
        type   : '#vehicle-type',
        color  : '#vehicle-color',
        model  : '#vehicle-model',
        brand  : '#vehicle-brand',
        year   : '#vehicle-year',
        desc   : '#vehicle-description',
        notif  : '#vehicle-dialog-notification'
    },

    //function to initialize dialog form
    initDialog : function() {
        $(this._dialog).dialog({
            autoOpen: false,
            modal: true,
            width: 400,
            buttons: {
                "Save": function () {
                    var success = Customer.Vehicle.save();
                    if(success === true)
                        $(this).dialog('close');
                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            },
            close: function () {
                Customer.Vehicle.closeDialog();
            }
        });
    },

    //function to open up dialog form
    openDialog : function() {
        $(this._method).val('add');
        console.log($(this._method));
        var vname = $(this._customername);
        vname.html($(this._customernamefield).val());
        console.log('open up dialog form');
        $(this._dialog).dialog('open');

        //clean up
        $(this._form.no).val('');
		$(this._form.type).val('');
        $(this._form.color).val('');
        $(this._form.model).val('');
        $(this._form.brand).val('');
        $(this._form.year).val('');
        $(this._form.desc).val('');
        $(this._addkey).val('');
    },

    //function to close dialog form
    closeDialog : function() {
        console.log('do closed procedures on dialog form');
        var vnotice = $(this._notice);
        var vtable = $(this._table);
        var vrows = $(this._rows);
        if(vrows.val() == '0') {
            console.log('no vehicle registered, show notice again');
            vnotice.show();
            vtable.hide();
        }
        //clean up
        $(this._customername).html('');
    },

    save : function() {
        var flag = $(this._method).val();
        console.log(flag);
        if(flag == 'add')
            return this.add();
        if(flag == 'edit')
            return this.update();
    },

    //function to add new vehicle
    add : function() {
        console.log('validate forms first');
        if(this._validateNull() !== true)
            return false;

        if(this._validateLength() !== true)
            return false;

        if(this._validateDuplicate() !== true)
            return false;

        console.log('add dynamic rows to vehicle tbody');
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
        var td = $('<td class="v-no v-num-' + nextidx + '">').append($(this._form.no).val());
        td.after($('<td class="v-type-' + nextidx + '">').append($(this._form.type).val()));
        td.after($('<td class="v-color-' + nextidx + '">').append($(this._form.color).val()));
        td.after($('<td class="v-model-' + nextidx + '">').append($(this._form.model).val()));
        td.after($('<td class="v-brand-' + nextidx + '">').append($(this._form.brand).val()));
        td.after($('<td class="v-desc-' + nextidx + '">').append($(this._form.desc).val()));

        var divv = $('<div>').append(
            $('<a>')
                .attr('href', this._tbody)
                .attr('onclick', 'Customer.Vehicle.edit("v-rows-' + nextidx + '", "' + nextidx + '")')
                .text('edit | ')
            .after(
                $('<a>')
                .attr('href', this._tbody)
                .attr('onclick', 'Customer.Vehicle.remove("v-rows-' + nextidx + '")')
                .text('remove')
            )
        );
        td.after($('<td>').append(divv));

        //hidden input
        var hiddiv = $('<div>').css('display', 'none');
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-num-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][number]')
                .val($(this._form.no).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-type-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][type]')
                .val($(this._form.type).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-color-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][color]')
                .val($(this._form.color).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-model-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][model]')
                .val($(this._form.model).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-brand-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][brand]')
                .val($(this._form.brand).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-year-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][year]')
                .val($(this._form.year).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('class', 'v-desc-hid-' + nextidx)
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][description]')
                .val($(this._form.desc).val())
        );
        hiddiv.append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name','vehicles[' + nextidx + '][status]')
                .val(1)
        );
        td.after(hiddiv);

        //insert to tr
        var tr = $('<tr>').attr('id','v-rows-' + nextidx).append(td);
        console.log(tr);
        //add dynamic rows to vehicle tbody based on submitted vehicle form
        var vtbody = $(this._tbody);
        vtbody.append(tr);

        //updating rows
        vrows.val(++nextidx);
        console.log('rows updated to ' + vrows.val());

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
        var msg = null;
        if($(this._form.no).val().trim() === '') {
            msg = 'Vehicle Number';
        }
        var required = 'Following fields are required : ' + msg;
        if(msg === null)
            return true;
        else
            this.notification('error', required);
        return false;
    },

    _validateDuplicate : function(id) {
        var msg = null;
        $(this._tbody).each(function(index) {
            $('tr .v-no').each(function(index) {
                if($(this).text() === $(Customer.Vehicle._form.no).val().trim()) {
                    if($(this).parent().attr('id') !== id)
                        msg = 'Vehicle Number';
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
        if($(this._form.no).val().trim().length < 5) {
            msg = 'Vehicle Number length must be more than 5 characters';
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
        var vname = $(this._customername);
        vname.html($(this._customernamefield).val());
        console.log('open up edit dialog form');
        $(this._dialog).dialog('open');

        console.log(id + ', ' + idx);
        var row = $('#'+id);
        console.log(row);

        $(this._addkey).val(id);

        var num = row.find('.v-num-' + idx);
        var type = row.find('.v-type-' + idx);
        var color = row.find('.v-color-' + idx);
        var model = row.find('.v-model-' + idx);
        var brand = row.find('.v-brand-' + idx);
        var year = row.find('.v-year-' + idx);
        var desc = row.find('.v-desc-' + idx);

        //clean up
        $(this._form.no).val(num.text());
        $(this._form.type).val(type.text());
        $(this._form.color).val(color.text());
        $(this._form.model).val(model.text());
        $(this._form.brand).val(brand.text());
        $(this._form.year).val(year.text());
        $(this._form.desc).val(desc.text());
    },

    //function to add new vehicle
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
        var idx = id.slice(-1);

        var num = row.find('.v-num-' + idx);
        var numhid = row.find('.v-num-hid-' + idx);
        num.html($(this._form.no).val());
        numhid.val($(this._form.no).val());

        var type = row.find('.v-type-' + idx);
        var typehid = row.find('.v-type-hid-' + idx);
        type.html($(this._form.type).val());
        typehid.val($(this._form.type).val());

        var color = row.find('.v-color-' + idx);
        var colorhid = row.find('.v-color-hid-' + idx);
        color.html($(this._form.color).val());
        colorhid.val($(this._form.color).val());

        var model = row.find('.v-model-' + idx);
        var modelhid = row.find('.v-model-hid-' + idx);
        model.html($(this._form.model).val());
        modelhid.val($(this._form.model).val());

        var brand = row.find('.v-brand-' + idx);
        var brandhid = row.find('.v-brand-hid-' + idx);
        brand.html($(this._form.brand).val());
        brandhid.val($(this._form.brand).val());

        var year = row.find('.v-year-' + idx);
        var yearhid = row.find('.v-year-hid-' + idx);
        year.html($(this._form.year).val());
        yearhid.val($(this._form.year).val());

        var desc = row.find('.v-desc-' + idx);
        var deschid = row.find('.v-desc-hid-' + idx);
        desc.html($(this._form.desc).val());
        deschid.val($(this._form.desc).val());

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