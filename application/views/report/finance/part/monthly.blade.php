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
        <h6>Finance Report :: Monthly Parts</h6>
        <div class="clear"></div>
    </div>

    <form method="get" id="formList">
        <div class="fluid grid">
            <div class="formRow noBorderB">
                <div class="grid6">
                    <ul class="timeRange">
                        <li style="width:120px; margin-top:2px;">Start Date</li>
                        <li>
                            <input name="startdateDisplay" type="text" class="monthpicker" value="{{ date('F Y', strtotime($startdate)) }}" data-mask="startdate"/>
                            <input type="hidden" id="startdate" name="startdate" value="{{ $startdate }}"/>
                        </li>
<!--                        <li><input name="startdate" type="text" class="datepicker" value="{{ $startdate }}" /></li>-->
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
<!--                        <li><input name="enddate" type="text" class="datepicker" value="{{ $enddate }}" /></li>-->
                        <li>
                            <input name="enddateDisplay" type="text" class="monthpicker" value="{{ date('F Y', strtotime($enddate)) }}" data-mask="enddate"/>
                            <input type="hidden" id="enddate" name="enddate" value="{{ $enddate }}"/>
                        </li>
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
                <th>Year<span class="sorting" style="display: block;"></span></th>
                <th>Month<span class="sorting" style="display: block;"></span></th>
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
                <td>{{ $part->year }}&nbsp;</td>
                <td>{{ $part->monthname }}&nbsp;</td>
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

<style type="text/css">
    .ui-datepicker-calendar {
        display: none;
    }
</style>

@endsection


@section('additional_js')

<script type="text/javascript">

    <?php
    $parts = array_reverse($parts);
    $xAxis = array();
    $label = array();
    foreach($parts as $a) :
        $lbl = "$a->monthname $a->year";
        if(!in_array($lbl, $xAxis)) {
            array_push($xAxis, $lbl);
        }
        if(!in_array($a->part_desc, $label)) {
            array_push($label, $a->part_desc);
        }
    endforeach;
//            print "\n## Axis\n".print_r($xAxis, true)."\n";
//            print "\n## Legend\n".print_r($label, true)."\n";

    $series = array();
    foreach($xAxis as $axis) :
        foreach($label as $legend) :
            $val = 0;
            foreach($parts as $a) :
                $pr  = "$a->monthname $a->year";
                $lbl = $a->part_desc;
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
                text: 'Finance Part Report'
            },
            subtitle: {
                text: 'Monthly Transactions'
            },
            series: data
        });

    });

</script>

@endsection