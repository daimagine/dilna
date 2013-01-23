/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 11/7/12
 * Time: 3:07 AM
 * To change this template use File | Settings | File Templates.
 */


$(function() {


    $( ".datepicker" ).datepicker({
        showOtherMonths:true,
        autoSize: true,
        appendText: '(dd-mm-yyyy)',
        dateFormat: 'dd-mm-yy',
        minDate: '-5Y'
    });

    //===== Time picker =====//

    $('.timepicker').timeEntry({
        show24Hours: true, // 24 hours format
        showSeconds: true, // Show seconds?
        spinnerImage: '/images/elements/ui/spinner.png', // Arrows image
        spinnerSize: [19, 26, 0], // Image size
        spinnerIncDecOnly: true // Only up and down arrows
    });

    $('.yearpicker').datepicker( {
        changeDate: false,
        changeMonth: false,
        changeYear: true,
        showButtonPanel: true,
        minDate: '-10Y',
        dateFormat: 'yy',
        onClose: function(dateText, inst) {
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, 1));
            console.log($(this).attr('data-mask'));
            var id = $(this).attr('data-mask');
            $('#'+id).val(year );
            console.log($('#'+id));
        }
    });


    //===== Dynamic data table =====//

    oTable = $('.dTableWarehouseItem').dataTable({
        "bJQueryUI": false,
        "bAutoWidth": true,
        "sPaginationType": "full_numbers",
        "sDom":  'T<"clear"><"H"lf>rt<"F"ip>',
//        "sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
        "oTableTools": {
            "sSwfPath": "../../media/swf/copy_csv_xls_pdf.swf",
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
        "sScrollXInner": "160%"
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


    oTableMin = $('.dTableWarehouseItemMin').dataTable({
        "bJQueryUI": false,
        "bAutoWidth": true,
        "sPaginationType": "full_numbers",
        "sDom": '<"H"fl>t<"F"ip>',
        "sScrollX": "100%",
        "sScrollXInner": "100%"
    });

//    console.log(oTableMin.attr('dtable-sortlist'));
    try {
        if(oTableMin) {
            //console.log(oTableMin.attr('dtable-sortlist'));
            if(oTableMin.attr('dtable-sortlist')) {
                oTableMin.fnSort( eval(oTableMin.attr('dtable-sortlist')) );
            }
        }
    } catch (err) {
        console.log(err);
    }

//    $("#itemCategory").change(function(event){
//        $("option:selected", $(this)).each(function(){
//            var obj = document.getElementById('itemCategory').value;
//            console.log('Object value is '+obj);
//            $("#divType").load("lst_unit_type/"+obj);
//        });
//    });

});
