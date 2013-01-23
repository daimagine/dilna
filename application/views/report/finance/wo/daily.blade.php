@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.finance_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


@include('partial.report.finance_wo_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Finance Report :: Daily Work Order</h6>
        <div class="clear"></div>
    </div>

    <form method="get" id="formList">
        <div class="fluid grid">
            <div class="formRow noBorderB">
                <div class="grid6">
                    <ul class="timeRange">
                        <li style="width:120px; margin-top:2px;">Start Date</li>
                        <li><input name="startdate" type="text" class="datepicker" value="{{ $startdate }}" /></li>
                    </ul>
                    <div class="clear"></div>
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Workorder Status</li>
                        <li>
                            <select name="wo_status">
                                <option value="">All</option>
                                <option value="{{ statusWorkOrder::OPEN }}"
                                {{ $wo_status == statusWorkOrder::OPEN ? 'selected="selected"' : '' }}>Open</option>
                                <option value="{{ statusWorkOrder::CLOSE }}"
                                {{ $wo_status == statusWorkOrder::CLOSE ? 'selected="selected"' : '' }}>Close</option>
                                <option value="{{ statusWorkOrder::CANCELED }}"
                                {{ $wo_status == statusWorkOrder::CANCELED ? 'selected="selected"' : '' }}>Canceled</option>
                            </select>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="grid6">
                    <ul class="timeRange">
                        <li style="width:120px; margin-top:2px;">End Date</li>
                        <li><input name="enddate" type="text" class="datepicker" value="{{ $enddate }}" /></li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
        </div>
        <div class="fluid grid">
            <div class="formRow noBorderB">
                <div class="grid">
                    <ul class="timeRange">
                        <li><input type="submit" class="buttonS bLightBlue" value="Search"></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
    <div class="clear"></div>

    <div class="divider"><span></span></div>

    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableTransactionMin" dtable-sortlist="[[0,'desc']]">
            <thead>
            <tr>
                <th>Date<span class="sorting" style="display: block;"></span></th>
                <th>Total Work Order</th>
                <th>Total Open</th>
                <th>Total Closed</th>
                <th>Total Canceled</th>
                <th>Amount</th>
<!--                <th>Action</th>-->
            </tr>
            </thead>

            <tbody>
            @foreach($transactions as $transaction)
            <tr class="">
                <td>{{ date('Y-m-d', strtotime($transaction->date)) }}&nbsp;</td>
                <td>{{ $transaction->total_wo }}&nbsp;</td>
                <td>{{ $transaction->total_open }}&nbsp;</td>
                <td>{{ $transaction->total_closed }}&nbsp;</td>
                <td>{{ $transaction->total_canceled }}&nbsp;</td>
                <td>IDR {{  number_format($transaction->total_amount, 2) }}&nbsp;</td>

<!--                <td class="tableActs" align="center">-->
<!--                    <a href="#detail" class="tablectrl_small bDefault tipS" original-title="Detail">-->
<!--                        <span class="iconb" data-icon=""></span>-->
<!--                    </a>-->
<!--                </td>-->
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<!-- Bars chart -->
<div class="widget grid6 chartWrapper">
    <div class="whead"><h6>Statistics Overview</h6><div class="clear"></div></div>
    <div class="body">
        <div id="container-chart" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
</div>

@endsection


@section('additional_js')

<script type="text/javascript">

    <?php
    $transactions = array_reverse($transactions);
    $xAxis = array();
    $data = array(
        array(
            'name' => 'WO Amount',
            'data' => array()
        )
    );
    foreach($transactions as $a) :
        $lbl = date('Y-m-d', strtotime($a->date));
        if(!in_array($lbl, $xAxis)) {
            array_push($xAxis, $lbl);
        }
        array_push($data[0]['data'], floatval($a->total_amount));
    endforeach;

    ?>

    $(function() {

        var data = <?php echo json_encode($data); ?>;
        console.log(data);

        var chart = new AutoChart({
            chart: {
                renderTo: 'container-chart'
            },
            xAxis: {
                categories: <?php echo utilities\Stringutils::js_array($xAxis); ?>
            },
            yAxis: {
                title: {
                    text: 'Work Order Amount'
                }
            },
            title: {
                text: 'Finance Work Order Report'
            },
            subtitle: {
                text: 'Daily Transactions'
            },
            series: data
        });

    });

</script>

@endsection
