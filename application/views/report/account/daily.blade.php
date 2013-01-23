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
            <h6>Account Report :: Daily</h6>
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
            <table cellpadding="0" cellspacing="0" border="0" class="dTableAccount" dtable-sortlist="[[0,'desc']]">
                <thead>
                <tr>
                    <th>Invoice Date<span class="sorting" style="display: block;"></span></th>
                    <th>Account Name</th>
                    <th>Due Date</th>
                    <th>Invoice No</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Input By</th>
                    <th style="min-width: 79px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                <tr class="">
                    <td>{{ date('Y-m-d', strtotime($account->invoice_date)) }}&nbsp;</td>
                    <td>{{ $account->account->name }}&nbsp;</td>
                    <td>{{ date('Y-m-d', strtotime($account->due_date)) }}&nbsp;</td>
                    <td>{{ $account->invoice_no }}&nbsp;</td>
                    <td>IDR {{  number_format($account->paid, 2) }}&nbsp;</td>
                    <td>{{ $account->type === ACCOUNT_TYPE_DEBIT ? 'Debit'  : 'Credit' }}&nbsp;</td>
                    <td>{{ $account->description }}&nbsp;</td>
                    <td>{{ $account->user->name }}&nbsp;</td>
                    <td class="tableActs" align="center">
                        <a href='{{ url("account/invoice_edit/$account->type/$account->id") }}'
                           class="appconfirm tablectrl_small bDefault tipS"
                           original-title="Edit"
                           dialog-confirm-title="Update Confirmation">
                            <span class="iconb" data-icon=""></span>
                        </a>
                        <a href='{{ url("account/pay_invoice/$account->type/$account->id") }}'
                           class="appconfirm tablectrl_small bDefault tipS"
                           original-title="Pay Invoice"
                           dialog-confirm-title="Payment Confirmation">
                            <span class="iconb" data-icon=""></span>
                        </a>
                    </td>
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

array(2) {
    ["Cash In"]=> array(2) {
        ["2012-10-01"]=> float(7900)
        ["2012-10-02"]=> string(7) "9900.00"
    }
    ["Cash Out"]=> array(2) {
        ["2012-10-01"]=> float(31000)
        ["2012-10-02"]=> int(0)
    }
}


@section('additional_js')

    <script type="text/javascript">

        <?php
            $dates = array();
            foreach($accounts as $a) :
                $d = date('Y-m-d', strtotime($a->invoice_date));
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
                    text: 'Account Report'
                },
                subtitle: {
                    text: 'Daily Transactions'
                },
                series: data
            });

        });

    </script>

@endsection