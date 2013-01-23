/**
 * Created with JetBrains PhpStorm.
 * User: adikurniawan
 * Date: 9/14/12
 * Time: 12:38 AM
 * To change this template use File | Settings | File Templates.
 */

$(function() {

    //===== Left navigation styling =====//

    $('.subNav li a.this').parent('li').addClass('activeli');


    //===== Login pic hover animation =====//
    $(".loginPic").hover(
        function() {

            $('.logleft, .logback').animate({left:10, opacity:1},200);
            $('.logright').animate({right:10, opacity:1},200); },

        function() {
            $('.logleft, .logback').animate({left:0, opacity:0},200);
            $('.logright').animate({right:0, opacity:0},200); }
    );


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

    //===== 2 responsive buttons (320px - 480px) =====//

    $('.iTop').click(function () {
        $('#sidebar').slideToggle(100);
    });

    $('.iButton').click(function () {
        $('.altMenu').slideToggle(100);
    });


    //===== Animated dropdown for the right links group on breadcrumbs line =====//

    $('.breadLinks ul li').click(function () {
        $(this).children("ul").slideToggle(150);
    });
    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("has"))
            $('.breadLinks ul li').children("ul").slideUp(150);
    });

    //===== Dynamic data table =====//

    oTable = $('.dTable').dataTable({
        "bJQueryUI": false,
        "bAutoWidth": true,
        "sPaginationType": "full_numbers",
        "sDom":  'T<"clear"><"H"lf>rt<"F"ip>',
//        "sDom": '<"H"fl>t<"F"ip>',
//        "sDom": 'T<"H"lfr>t<"F"ip>',
//        "sDom": 'T<"clear">lfrtip',
//        "sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
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
        "sScrollXInner": "110%"
    });

    //Try get data datatable for print pdf
    $('#btnSave').button({icons: {primary:'ui-icon-disk'},
        disabled: false
    }).click(function() {
            console.log('Print action');
            var cells = [];
            var rows = oTable.fnGetColumnData();

            for(var i=0;i<rows.length;i++)
            {
                // Get HTML of 3rd column (for example)
                cells.push($(rows[i]).find("td:eq(3)").html());
            }
            console.log(cells);
        });


    //console.log(oTable.attr('dtable-sortlist'));
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


    //== Adding class to :last-child elements ==//

    $(".subNav li:last-child a, .formRow:last-child, .userList li:last-child, table tbody tr:last-child td, .breadLinks ul li ul li:last-child, .fulldd li ul li:last-child, .niceList li:last-child").addClass("noBorderB")


    //===== Add classes for sub sidebar detection =====//

    if ($('div').hasClass('secNav')) {
        $('#sidebar').addClass('with');
        //$('#content').addClass('withSide');
    }
    else {
        $('#sidebar').addClass('without');
        $('#content').css('margin-left','100px');//.addClass('withoutSide');
        $('#footer > .wrapper').addClass('fullOne');
    };


    //===== Collapsible elements management =====//

    $(document).bind('click', function(e) {
        var closeit = false;
        var $clicked = $(e.target);
        var $opened = $('.exp.subOpened');
        $opened.each(function() {
            var a = $(this).html() === $clicked.html();
            if(a === false) {
                //console.log($(this).parent().find(".leftSubMenu"));
                $(this).parent().find(".leftSubMenu").slideUp(200);
                $(this).removeClass('subOpened');
                $(this).addClass('subClosed');
            }
        });
    });

    $('.exp').collapsible({
        //defaultOpen: 'current',
        create: function(event, ui) { console.log('Show when collapse');},
        defaultOpen: 'toggleOpened',
        cookieName: 'navAct',
        cssOpen: 'subOpened',
        cssClose: 'subClosed',
        speed: 200
    });

    $('.opened').collapsible({
        defaultOpen: 'opened,toggleOpened',
        cssOpen: 'inactive',
        cssClose: 'normal',
        speed: 200
    });

    $('.closed').collapsible({
        defaultOpen: '',
        cssOpen: 'inactive',
        cssClose: 'normal',
        speed: 200
    });



    //===== Accordion =====//

    $('div.menu_body:eq(0)').show();
    $('.acc .whead:eq(0)').show().css({color:"#2B6893"});

    $(".acc .whead").click(function() {
        $(this).css({color:"#2B6893"}).next("div.menu_body").slideToggle(200).siblings("div.menu_body").slideUp("slow");
        $(this).siblings().css({color:"#404040"});
    });

    //===== Breadcrumbs =====//

    $('#breadcrumbs').xBreadcrumbs();


    //===== Animated dropdown for the right links group on breadcrumbs line =====//

    $('.breadLinks ul li').click(function () {
        //$(this).children("ul").slideToggle(150);
    });
    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("has"))
            $('.breadLinks ul li').children("ul").slideUp(150);
    });

    //===== User nav dropdown =====//

    $('a.leftUserDrop').click(function () {
        $('.leftUser').slideToggle(200);
    });
    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("leftUserDrop"))
            $(".leftUser").slideUp(200);
    });


    //===== Tooltips =====//

    $('.tipN').tipsy({gravity: 'n',fade: true, html:true});
    $('.tipS').tipsy({gravity: 's',fade: true, html:true});
    $('.tipW').tipsy({gravity: 'w',fade: true, html:true});
    $('.tipE').tipsy({gravity: 'e',fade: true, html:true});


    //===== Add class on #content resize. Needed for responsive grid =====//

    $('#content').resize(function () {
        var width = $(this).width();
        if (width < 769) {
            $(this).addClass('under');
        }
        else { $(this).removeClass('under') }
    }).resize(); // Run resize on window load


    //===== Button for showing up sidebar on iPad portrait mode. Appears on right top =====//

    $("ul.userNav li a.sidebar").click(function() {
        $(".secNav").toggleClass('display');
    });

    //===== Form elements styling =====//
    $("select, .check, .check :checkbox, input:radio, input:file").uniform();

    //===== Notification boxes =====//

    $(".nNote").click(function() {
        $(this).fadeTo(200, 0.00, function(){ //fade
            $(this).slideUp(200, function() { //slide up
                $(this).remove(); //then remove from the DOM
            });
        });
    });


    $( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$('.appconfirm').click(function(e) {
		e.preventDefault();
		var targetUrl = $(this).attr("href");
		var confirm_title = 'Confirmation';
		var confirm_content = 'Your action cannot be undone. Are you sure?';
		var title = $(this).attr("dialog-confirm-title");
		var content = $(this).attr("dialog-confirm-content");
		var callback = $(this).attr("dialog-confirm-callback");
		var t = title || confirm_title;
		$("#dialog-confirm").attr('title', t);
		var c = content || confirm_content;
        $("#dialog-confirm-content").html(c);

		$("#dialog-confirm").dialog({
			modal: true,
            minWidth: 500,
			buttons : {
				"Confirm" : function() {
					console.log(callback);
					if(callback) { 
						var fn = new Function(callback);
						fn(); 
					} else {
						window.location.href = targetUrl;
					}
				},
				"Cancel" : function() {
				  $(this).dialog("close");
				}
			 }
		});

		$("#dialog-confirm").dialog("open");
	});

    //============ upper case input when keyUp ======================
    $(".upperCase").bind('keyup', function (e) {
        if (e.which >= 97 && e.which <= 122) {
            var newKey = e.which - 32;
            // I have tried setting those
            e.keyCode = newKey;
            e.charCode = newKey;
        }
        var idTarget = $(e.target).attr('id');
//        console.log('ID Target '+idTarget);
        $("#"+idTarget).val(($(".upperCase").val()).toUpperCase());
    });
	
});


function toFixed(value, precision) {
    var precision = precision || 0,
        neg = value < 0,
        power = Math.pow(10, precision),
        value = Math.round(value * power),
        integral = String((neg ? Math.ceil : Math.floor)(value / power)),
        fraction = String((neg ? -value : value) % power),
        padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');

    return precision ? integral + '.' +  padding + fraction : integral;
}


function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function AutoChart(config) {
    var defaultOption = {
        customize: {
            autoRender: true
        },
        chart: {
            renderTo: 'container-chart',
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [],
            labels: {
                rotation: -45,
                align: 'right',
                style: {
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            layout: 'vertical',
            backgroundColor: '#FFFFFF',
            align: 'left',
            verticalAlign: 'top',
            x: 100,
            y: 70,
            floating: true,
            shadow: true
        },
        tooltip: {
            formatter: function() {
                return ''+ this.series.name +': '+ this.y +' millions';
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: []
    }

    this.chart = {};

    //merge user option with default
    $.extend(true, defaultOption, config);

    this.render = function() {
        console.log(defaultOption);
        this.chart = new Highcharts.Chart(defaultOption);
    }

    //render if auto
    if(defaultOption.customize.autoRender === true)
        this.render();

}

