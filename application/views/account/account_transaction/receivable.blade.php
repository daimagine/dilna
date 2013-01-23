@section('content')

@include('partial.notification')

<ul class="middleNavA">
    <li><a href='{{ url("account/account_receivable") }}' title="All Receivable Account">{{ HTML::image('images/icons/color/refresh.png', '') }}<span>All</span></a></li>
    <li><a href='{{ url("account/invoice_in") }}' title="Add invoice">{{ HTML::image('images/icons/color/plus.png', '') }}<span>Add invoice</span></a></li>
    <li><a href='{{ url("account/account_receivable/unpaid") }}' title="Awaiting payment">{{ HTML::image('images/icons/color/full-time.png', '') }}<span>Awaiting payment</span></a></li>
    <li><a href='{{ url("account/account_receivable/paid") }}' title="Paid Account Receivable">{{ HTML::image('images/icons/color/cost.png', '') }}<span>Paid invoice</span></a></li>
</ul>
<div class="divider"><span></span></div>

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Account Receivable List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableAccount">
            <thead>
            <tr>
                <th>Type</th>
                <th>Invoice No<span class="sorting" style="display: block;"></span></th>
                <th>Reference No</th>
                <th>To</th>
                <th>Input Date</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Remaining</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
            <tr class="">
                <td>{{ $account->account_name }}</td>
                <td>{{ $account->invoice_no }}</td>
                <td>{{ $account->reference_no }}</td>
                <td>{{ $account->subject }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $account->input_date)->format('d M Y') }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $account->invoice_date)->format('d M Y') }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $account->due_date)->format('d M Y') }}</td>
                <td>{{ $account->paid !== null ? 'IDR' : '' }} {{  number_format($account->paid, 2) }}</td>
                <td>{{ $account->due !== null ? 'IDR' : '' }} {{  number_format($account->due, 2) }}</td>
                <td>IDR {{ number_format($account->due - $account->paid, 2) }}</td>
                <td>{{ $account->paid_date === null ? 'awaiting payment'  : ( ($account->due - $account->paid == 0 ) ? 'paid' : 'partially paid' ) }}</td>
                <td class="tableActs" align="center">
                    <a href='{{ url("account/invoice_edit/$accountTransType/$account->id") }}'
                       class="appconfirm tablectrl_small bDefault tipS"
                       original-title="Edit"
                       dialog-confirm-title="Update Confirmation">
                        <span class="iconb" data-icon=""></span>
                    </a>
                    @if(Auth::user()->id == Config::get('default.role.admin'))
                        <a href='{{ url("account/invoice_delete/$accountTransType/$account->id") }}'
                           class="appconfirm tablectrl_small bDefault tipS"
                           original-title="Remove"
                           dialog-confirm-title="Remove Confirmation">
                            <span class="iconb" data-icon=""></span>
                        </a>
                    @endif
                    @if($account->due == 0 || $account->paid <> $account->due)
                        <a href='{{ url("account/pay_invoice/$accountTransType/$account->id") }}'
                           class="appconfirm tablectrl_small bDefault tipS"
                           original-title="Pay Invoice"
                           dialog-confirm-title="Payment Confirmation">
                            <span class="iconb" data-icon=""></span>
                        </a>
                    @endif
                    <a href='{{ url("account/print/$accountTransType/$account->id") }}'
                       class="tablectrl_small bDefault tipS"
                       original-title="Print Invoice" target="_blank">
                        <span class="iconb" data-icon=""></span>
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<div class="fluid">
    <div class="grid2">
        <div class="wButton"><a href="{{ url('account/invoice_in') }}" title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Account Receivable</span>
        </a></div>
    </div>
</div>

@endsection
