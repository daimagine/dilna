@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.account_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Account Report :: Weekly</h6>
        <div class="clear"></div>
    </div>

    <form method="get">
        <div class="fluid">
            <div class="formRow">
                <div class="grid">
                    <ul class="timeRange">
                        <li style="margin-top:2px;">Start Date&nbsp;&nbsp;&nbsp;</li>
                        <li><input name="startdate" type="text" class="datepicker" value="{{ $startdate }}" /></li>

                        <li style="margin-top:2px;">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;End Date&nbsp;&nbsp;&nbsp;</li>
                        <li><input name="enddate" type="text" class="datepicker" value="{{ $enddate }}" /></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid">
                    <ul class="timeRange">
                        <li>Type&nbsp;&nbsp;&nbsp;</li>
                        <li>
                            <select name="type">
                                <option value="">All</option>
                                <option value="{{ ACCOUNT_TYPE_DEBIT }}"
                                {{ $type == ACCOUNT_TYPE_DEBIT ? 'selected="selected"' : '' }}>Income</option>
                                <option value="{{ ACCOUNT_TYPE_CREDIT }}"
                                {{ $type == ACCOUNT_TYPE_CREDIT ? 'selected="selected"' : '' }}>Expenditur</option>
                            </select>
                        </li>
                        <li style="margin-left: 50px;"><input type="submit" class="buttonS bLightBlue" value="Search"></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>

    <div class="divider"><span></span></div>

    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableAccountMin" dtable-sortlist="[[0,'desc']]">
            <thead>
            <tr>
                <th>Date Range<span class="sorting" style="display: block;"></span></th>
                <th>Account Name</th>
                <th>Count</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
            <tr class="">
                <td>{{ date('Y-m-d', strtotime($account->week_start)) }} - {{ date('Y-m-d', strtotime($account->week_end)) }}&nbsp;</td>
                <td>{{ $account->name }}&nbsp;</td>
                <td>{{ $account->count }}&nbsp;</td>
                <td>IDR {{  number_format($account->amount, 2) }}&nbsp;</td>
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
        $xAxis = array();
        $label = array();
        foreach($accounts as $a) :
            $lbl = date('Y-m-d', strtotime($a->week_start))." - ".date('Y-m-d', strtotime($a->week_end));
            if(!in_array($lbl, $xAxis)) {
                array_push($xAxis, $lbl);
            }
            if(!in_array($a->name, $label)) {
                array_push($label, $a->name);
            }
        endforeach;
    //            print "\n## Axis\n".print_r($xAxis, true)."\n";
    //            print "\n## Legend\n".print_r($label, true)."\n";

        $series = array();
        foreach($xAxis as $axis) :
            foreach($label as $legend) :
                $val = 0;
                foreach($accounts as $a) :
                    $pr  = date('Y-m-d', strtotime($a->week_start))." - ".date('Y-m-d', strtotime($a->week_end));
                    $lbl = $a->name;
                    if($lbl == $legend && $pr == $axis) {
                        $val = floatval($a->amount);
                    }
                endforeach;

                //store
                $new = true;
                for($i=0; $i<sizeof($series); $i++) {
                    //if not exist
                    if(array_key_exists('name', $series[$i]) && $series[$i]['name'] == $legend) {
    //                            print "\n### Push [$val] to [$legend]\n";
                        array_push($series[$i]['data'], $val);
                        $new = false;
                        break;
                    }
                }
                //store
                if($new) {
                    $dt['name'] = $legend;
                    $dt['data'] = array( $val );
                    array_push($series, $dt);
    //                        print "\n## Push [$val] to [$legend]\n";
                }
            endforeach;
        endforeach;
    ?>

    $(function() {

        var data = <?php echo json_encode($series); ?>;
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
                    text: 'Transaction Amount'
                }
            },
            title: {
                text: 'Account Report'
            },
            subtitle: {
                text: 'Weekly Transactions'
            },
            series: data
        });

    });

</script>

@endsection