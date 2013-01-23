@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.finance_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


@include('partial.report.finance_part_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Finance Report :: Daily Parts</h6>
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
                        <li style="width:120px;">Part Type</li>
                        <li>
                            <select name="part_type">
                                <option value="">All</option>
                                @foreach($part_type_opt as $key => $val)
                                    <option value="{{ $key }}" {{ $key == $part_type ? 'selected="selected"' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </li>
                    </ul>
                    <div class="clear"></div>
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Unit Type</li>
                        <li>
                            <select name="part_unit">
                                <option value="">All</option>
                                @foreach($part_unit_opt as $key => $val)
                                    <option value="{{ $key }}" {{ $key == $part_unit ? 'selected="selected"' : '' }}>{{ $val }}</option>
                                @endforeach
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
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Part Category</li>
                        <li>
                            <select name="part_category">
                                <option value="">All</option>
                                @foreach($part_category_opt as $key => $val)
                                    <option value="{{ $key }}" {{ $key == $part_category ? 'selected="selected"' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </li>
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
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Vendor</th>
                <th>Unit</th>
                <th>Count</th>
                <th>Amount</th>
            </tr>
            </thead>

            <tbody>
            @foreach($parts as $part)
            <tr class="">
                <td>{{ date('Y-m-d', strtotime($part->part_date)) }}&nbsp;</td>
                <td>{{ $part->part_code }}&nbsp;</td>
                <td>{{ $part->part_desc }}&nbsp;</td>
                <td>{{ $part->part_category }}&nbsp;</td>
                <td>{{ $part->part_vendor }}&nbsp;</td>
                <td>{{ $part->unit_type }}&nbsp;</td>
                <td>{{ $part->part_count }}&nbsp;</td>
                <td>IDR {{  number_format($part->amount, 2) }}&nbsp;</td>
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
    $dates = array();
    foreach($parts as $a) :
        $d = date('Y-m-d', strtotime($a->part_date));
        if(!in_array($d, $dates)) {
            array_push($dates, $d);
        }
    endforeach;

    //var_dump($dates);print "<br>";
    $data = array();
    foreach($graphData as $key => $val) :
        $d = array();
        $d['name'] = $key;
        $d['data'] = array();
        foreach($val as $k => $v) :
            if(in_array($k, $dates)) {
                array_push($d['data'], floatval($v));
            } else {
                array_push($d['data'], 0);
            }
        endforeach;
        array_push($data, $d);
    endforeach;
    //print_r($data);

    ?>

    $(function() {

        var data = <?php echo json_encode($data); ?>;
        console.log(data);

        var chart = new AutoChart({
            chart: {
                renderTo: 'container-chart'
            },
            xAxis: {
                categories: <?php echo utilities\Stringutils::js_array($dates); ?>
            },
            yAxis: {
                title: {
                    text: 'Transaction Amount'
                }
            },
            title: {
                text: 'Finance Part Report'
            },
            subtitle: {
                text: 'Daily Transactions'
            },
            series: data
        });

    });

</script>

@endsection