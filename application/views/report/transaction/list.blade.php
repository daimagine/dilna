@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.transaction_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Transaction Report :: List</h6>
        <div class="clear"></div>
    </div>

    <form method="get" id="formList">
        <div class="fluid grid">
            <div class="formRow">
                <div class="grid6">
                    <ul class="timeRange">
                        <li style="width:120px; margin-top:2px;">Start Date</li>
                        <li><input name="startdate" type="text" class="datepicker" value="{{ $startdate }}" /></li>
                    </ul>
                    <div class="clear"></div>
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Workorder ID</li>
                        <li>
                            <input type="text" name="wo_id" value="{{ $wo_id }}"/>
                        </li>
                    </ul>
                    <div class="clear"></div>
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Customer Name</li>
                        <li>
                            <input type="text" name="customer_name" value="{{ $customer_name }}"/>
                        </li>
                    </ul>
                    <div class="clear"></div>
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Invoice Number</li>
                        <li>
                            <input type="text" name="invoice_no" value="{{ $invoice_no }}"/>
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
                    <ul class="timeRange fixoptTime">
                        <li style="width:120px;">Vehicle Number</li>
                        <li>
                            <input type="text" name="vehicle_no" value="{{ $vehicle_no }}"/>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
        </div>
        <div class="fluid grid">
            <div class="formRow">
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
        <table cellpadding="0" cellspacing="0" border="0" class="dTableTransaction" dtable-sortlist="[[0,'desc']]">
            <thead>
            <tr>
                <th>Action</th>
                <th>Invoice<span class="sorting" style="display: block;"></span></th>
                <th>Workorder Number</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Workorder Status</th>
                <th>Transaction Date</th>
                <th>Total Services</th>
                <th>Total Parts</th>
                <th>Amount</th>
                <th>Discounts</th>
                <th>Payment Type</th>
            </tr>
            </thead>

            <tbody>
            @foreach($transactions as $transaction)
                <tr class="">
                    <td class="tableActs" align="center">
                        <a href='{{ url("report/transaction/detail/$transaction->transaction_id") }}' class="tablectrl_small bDefault tipS" original-title="Detail"
                           data-id="{{ $transaction->transaction_id }}">
                            <span class="iconb" data-icon=""></span>
                        </a>
                        &nbsp;
                    </td>
                    <td>{{ $transaction->invoice_no }}&nbsp;</td>
                    <td>{{ $transaction->workorder_no }}&nbsp;</td>
                    <td>{{ $transaction->customer_name }}&nbsp;</td>
                    <td>{{ $transaction->vehicle_no }}&nbsp;</td>

                    @if($transaction->workorder_status === statusWorkOrder::OPEN)
                        <td>Open</td>
                    @elseif($transaction->workorder_status === statusWorkOrder::CLOSE)
                        <td>Close</td>
                    @elseif($transaction->workorder_status === statusWorkOrder::CANCELED)
                        <td>Canceled</td>
                    @else
                        <td>&nbsp;</td>
                    @endif

                    <td>{{ date('Y-m-d', strtotime($transaction->transaction_date)) }}&nbsp;</td>
                    <td>{{ $transaction->total_services }}&nbsp;</td>
                    <td>{{ $transaction->total_parts }}&nbsp;</td>
                    <td>IDR {{  number_format($transaction->total_transactions, 2) }}&nbsp;</td>
                    <td>IDR {{  number_format($transaction->discount, 2) }}&nbsp;</td>

                    @if($transaction->payment_type === paymentType::CASH)
                        <td>Cash</td>
                    @elseif($transaction->payment_type === paymentType::EDC)
                        <td>Cash</td>
                    @elseif($transaction->payment_type === paymentType::MONTHLY)
                        <td>Monthly</td>
                    @elseif($transaction->payment_type === paymentType::CORPORATE)
                        <td>Corporate</td>
                    @else
                        <td>&nbsp;</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<!--<!-- Bars chart -->
<!--<div class="widget grid6 chartWrapper">-->
<!--    <div class="whead"><h6>Statistics Overview</h6><div class="clear"></div></div>-->
<!--    <div class="body"><div class="bars" id="placeholder1"></div></div>-->
<!--</div>-->


@endsection

