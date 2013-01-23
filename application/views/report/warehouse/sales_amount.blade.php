@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.warehouse_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Monthly Report Sales Amount Item :: List :: In IDR</h6>
        <div class="clear"></div>
    </div>
    <form method="get" id="formList">
        <div class="fluid grid">
            <div class="formRow noBorderB">
                <div class="grid6">
                    <div class="clear" style=""></div>
                    <div class="grid3"><label>Item Name</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input type="text" id="itemName" name="name" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$name}}"></button>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Start Year</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input name="startYearDisplay" type="text" class="yearpicker" value="{{$startYear }}" data-mask="startYear"/>
                            <input type="hidden" id="startYear" name="startYear" value="{{ $startYear }}"/>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                </div>
                <div class="grid6">
                    <div class="clear" style=""></div>
                    <div class="grid3"><label>Category</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            {{Form::select('category', $lstCategory, $category, array('id' => 'itemCategory'))}}
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>End Year</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input name="endYearDisplay" type="text" class="yearpicker" value="{{ $endYear }}" data-mask="endYear"/>
                            <input type="hidden" id="endYear" name="endYear" value="{{ $endYear }}"/>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
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

    <div class="divider"><span></span></div>


    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableWarehouseItem" dtable-sortlist="[[0,'desc']]">
            <thead>
            <tr>
                <th>Item Name</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>May</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Aug</th>
                <th>Sep</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dec</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
            <tr class="">
                <td>{{ $item->name }}</td>
                <td>@if($item->jan_salesamount != null) {{ $item->jan_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->feb_salesamount != null) {{ $item->feb_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->mar_salesamount != null) {{ $item->mar_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->apr_salesamount != null) {{ $item->apr_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->may_salesamount != null) {{ $item->may_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->jun_salesamount != null) {{ $item->jun_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->jul_salesamount != null) {{ $item->jul_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->aug_salesamount != null) {{ $item->aug_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->sep_salesamount != null) {{ $item->sep_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->oct_salesamount != null) {{ $item->oct_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->nov_salesamount != null) {{ $item->nov_salesamount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->dec_salesamount != null) {{ $item->dec_salesamount }} @else 0 @endif &nbsp;</td>
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
        <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
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
    $data = $dataGraph;
    $joka='';
    if(is_array($data) && array_key_exists('data', $data)) {
        $d=$data['data'];
        for($i=0; $i<count($dataGraph['categories']); $i++) {
            $joka = $joka . "{y: ".strval(($d['y'][$i])).",".
                "color: colors[".$d['color'][$i]."],".
                "drilldown: {".
                "name: '".$d['drilldown']['name'][$i]."',".
                "categories: ".utilities\Stringutils::js_array($d['drilldown']['categories'][$i]).",".
                "data: [".implode(',', $d['drilldown']['data'][$i])."],".
                "color: colors[".$d['drilldown']['color'][$i]."],".
                "}".
                "},";
        }
    }

//    dd($joka);
    ?>

    $(function () {
        var chart;
        $(document).ready(function() {
            var colors = Highcharts.getOptions().colors,
                categories = <?php  if($data != null) echo utilities\Stringutils::js_array($data['categories']); ?>,
                name = <?php if($data != null) echo "'".$data['name']."'" ?>,
                data = [ <?php echo $joka ?> ];


            function setChart(name, categories, data, color) {
                chart.xAxis[0].setCategories(categories, false);
                chart.series[0].remove(false);
                chart.addSeries({
                    name: name,
                    data: data,
                    color: color || 'white'
                }, false);
                chart.redraw();
            }

            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    type: 'column'
                },
                title: {
                    text: <?php echo "'Total Sales Amount Stock Item, period (".$startYear."-".$endYear.")'" ?>
                },
                subtitle: {
                    text: 'Click the columns to view details per month.'
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: 'Total sales amount item'
                    }
                },
                plotOptions: {
                    column: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    var drilldown = this.drilldown;
                                    if (drilldown) { // drill down
                                        setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                    } else { // restore
                                        setChart(name, categories, data);
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            color: colors[0],
                            style: {
                                fontWeight: 'bold'
                            },
                            formatter: function() {
                                return 'Rp.'+this.y;
                            }
                        }
                    }
                },
                tooltip: {
                    formatter: function() {
                        var point = this.point,
                            s = this.x +':<b> Sales Amount Rp.'+ this.y +'</b><br/>';
                        if (point.drilldown) {
                            s += 'Click to view detail item sold in '+ point.category;
                        } else {
                            s += 'Click to return to monthly chart';
                        }
                        return s;
                    }
                },
                series: [{
                    name: name,
                    data: data,
                    color: 'white'
                }],
                exporting: {
                    enabled: false
                }
            });
        });

    });

</script>
@endsection