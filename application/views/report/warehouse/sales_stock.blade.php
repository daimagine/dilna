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
        <h6>Monthly Report Sales Count Item :: List</h6>
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
                <td>@if($item->jan_salescount != null) {{ $item->jan_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->feb_salescount != null) {{ $item->feb_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->mar_salescount != null) {{ $item->mar_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->apr_salescount != null) {{ $item->apr_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->may_salescount != null) {{ $item->may_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->jun_salescount != null) {{ $item->jun_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->jul_salescount != null) {{ $item->jul_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->aug_salescount != null) {{ $item->aug_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->sep_salescount != null) {{ $item->sep_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->oct_salescount != null) {{ $item->oct_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->nov_salescount != null) {{ $item->nov_salescount }} @else 0 @endif &nbsp;</td>
                <td>@if($item->dec_salescount != null) {{ $item->dec_salescount }} @else 0 @endif &nbsp;</td>
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


    ?>

    $(function () {
        var chart;
        $(document).ready(function() {
            var colors = Highcharts.getOptions().colors,
//                categories = ["Jan"],
//                name = 'Sales Count Stock',
//                data = [{y: 1,color: colors[0],drilldown: {name: 'Sales Count in January',categories: ["parts","Oli Mesran","Oli dari approved status"],data: [1,0,0],color: colors[2]}}];
                categories = <?php if($data != null) echo utilities\Stringutils::js_array($data['categories'] ); ?>,
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
                    text: <?php echo "'Total Sales Count Stock Item, period (".$startYear."-".$endYear.")'" ?>
                },
                subtitle: {
                    text: 'Click the columns to view details per month.'
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: 'Total sales count item'
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
                                return this.y +' item';
                            }
                        }
                    }
                },
                tooltip: {
                    formatter: function() {
                        var point = this.point,
                            s = this.x +':<b>'+ this.y +' item sold</b><br/>';
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