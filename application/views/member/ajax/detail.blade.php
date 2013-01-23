<div>
    <ul class="niceList params">
        <li>
            <div class="myInfo">
                <h5>{{ $member->customer->name }}</h5>
                <span class=""></span>
                <span class="myRole followers">membership since {{ date('d F Y', strtotime($member->registration_date)) }}</span>
            </div>
            <div class="clear"></div>
        </li>
        <li class="on_off">
            <label><span class="icos-car"></span>Vehicle Number &nbsp; {{ $member->vehicle->number }}</label>
            <div class="clear"></div>
        </li>
        <li class="on_off">
            <label><span class="icos-postcard"></span>Membership Number &nbsp; {{ $member->number }}</label>
            <div class="clear"></div>
        </li>
        <li class="on_off">
            <label><span class="icos-dates"></span>Membership valid until &nbsp; {{ date('d F Y', strtotime($member->expiry_date)) }} </label>
            <div class="clear"></div>
        </li>
        <li class="on_off">
            <label><span class="icos-tags"></span>{{ $member->description }}</label>
            <div class="clear"></div>
        </li>
        <li class="on_off noBorderB">
            <div style="float: right">
                <a href='{{ url("member/delete/$member->id") }}'
                   class="appconfirm buttonM bRed"
                   original-title="Remove"
                   dialog-confirm-title="Remove Confirmation">
                    <span class="icol-exit"></span>
                    <span style="color: white">Revoke Membership</span>
                </a>
            </div>
            <div class="clear"></div>
        </li>
    </ul>
</div>
<div class="clear"></div>

<script type="text/javascript">

    $(function() {

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

            $("#dialog-confirm").dialog({
                modal: true,
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

    });
</script>