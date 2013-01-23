/**
 * Created with JetBrains PhpStorm.
 * User: adi
 * Date: 10/21/12
 * Time: 8:12 AM
 * To change this template use File | Settings | File Templates.
 */
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


    var opts = {
        'fraction': {
            stepping: 1,
            min: 0
        }
    };

    for (var n in opts)
        $("."+n).spinner(opts[n]);

    $('.calculate-total').bind('change', function() {
        Settlement.recalculateAmount();
    });

});

var Settlement = {
    selector: {
        total_amount     : '#total-amount',
        amount_cash      : '#amount-cash',
        amount_non_cash  : '#amount-non-cash'
    },
    recalculateAmount: function() {
        console.log('recalculate amount');
        var aCash = parseFloat($(this.selector.amount_cash).val());
        var aNCash = parseFloat($(this.selector.amount_non_cash).val());
        var tAmount = aCash + aNCash;
        console.log('total amount : ' + tAmount);
        $(this.selector.total_amount).html(toFixed(tAmount, 2));
    }
};
