@section('content')

@include('partial.notification')

<ul class="middleNavA">
    <li><a href='{{ url("account/account_payable") }}' title="All Payable Account"><img src='{{ asset("images/icons/color/refresh.png") }}' alt=""><span>All</span></a></li>
    <li><a href='{{ url("account/invoice_in/C") }}' title="Add invoice"><img src='{{ asset("images/icons/color/plus.png") }}' alt=""><span>Add invoice</span></a></li>
    <li><a href='{{ url("account/account_payable/unpaid") }}' title="Awaiting payment"><img src='{{ asset("images/icons/color/full-time.png") }}' alt=""><span>Awaiting payment</span></a></li>
    <li><a href='{{ url("account/account_payable/paid") }}' title="Paid invoice"><img src='{{ asset("images/icons/color/cost.png") }}' alt=""><span>Paid invoice</span></a></li>
</ul>
<div class="divider"><span></span></div>

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Account Payable List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableAccount">
            <thead>
            <tr>
                <th>Type</th>
                <th>Invoice No<span class="sorting" style="display: block;"></span></th>
                <th>Reference No</th>
                <th>From</th>
                <th>Input Date</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Remaining</th>
                <th>Status</th>
                <th style="min-width: 79px">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
            <tr class="">
                <td>{{ $account->account->name }}&nbsp;</td>
                <td>{{ $account->invoice_no }}&nbsp;</td>
                <td>{{ $account->reference_no }}&nbsp;</td>
                <td>{{ $account->subject }}&nbsp;</td>
                <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $account->input_date)->format('d M Y') }}&nbsp;</td>
                <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $account->invoice_date)->format('d M Y') }}&nbsp;</td>
                <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $account->due_date)->format('d M Y') }}&nbsp;</td>
                <td>{{ $account->paid !== null ? 'IDR' : '' }} {{  number_format($account->paid, 2) }}&nbsp;</td>
                <td>{{ $account->due !== null ? 'IDR' : '' }} {{  number_format($account->due, 2) }}&nbsp;</td>
                <td>IDR {{ number_format($account->due - $account->paid, 2) }}&nbsp;</td>
                <td>{{ $account->paid_date === null ? 'awaiting payment'  : ( ($account->due - $account->paid == 0 ) ? 'paid' : 'partially paid' ) }}&nbsp;</td>
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
                           class='{{ url("appconfirm tablectrl_small bDefault tipS"
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
        <div class="wButton"><a href='{{ url("account/invoice_in/C") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Account Payable</span>
        </a></div>
    </div>
</div>

@endsection
