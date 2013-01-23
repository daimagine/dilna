@section('content')

@include('partial.notification')
<br>

{{ Form::open('/account/pay_invoice/'.$account->type, 'POST', array( 'id' => 'form1' ) ) }}

{{ Form::hidden('id', $account->id) }}

{{ Form::hidden('type', $account->type) }}

<fieldset>

<div class="widget">
    <div class="invoice">
        <div class="inHead">
            <span class="inLogo"><h6>{{ $accountTransType === 'D' ? 'Account Receivable' : 'Account Payable' }}</h6></span>
            <div class="inInfo">
                <span class="invoiceNum">Invoice # {{ $account->invoice_no }}</span>
                <i>{{ date( 'd F Y', strtotime($account->invoice_date) ) }}</i>
            </div>
            <div class="clear"></div>
        </div>

        <div class="inContainer">
            <div class="inFrom">
                <h5>{{$accountTransType === 'D' ? 'From' : 'To' }} <strong class="red">{{ $account->subject }}</strong></h5>
                <span>Ref <strong># {{ $account->reference_no }}</strong></span>
                <span>Invoice create on <strong>{{ date( 'd F Y', strtotime($account->due_date) ) }}</strong></span>
                <span>Payment due by <strong>{{ date( 'd F Y', strtotime($account->due_date) ) }}</strong></span>
                <span class="black">Invoice Status is <a href="#">{{ $account->paid_date == null ? 'Awaiting payment' : ( $account->paid < $account->due ? 'Partially Paid' : 'Paid' )}}</a></span>
            </div>

            <div class="clear"></div>
        </div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tLight">
            <thead>
                <tr>
                    <td width="30%">Item</td>
                    <td width="5%">Quantity</td>
                    <td width="15%">Account</td>
                    <td width="10%">Tax Percentage</td>
                    <td width="20%">Tax Amount</td>
                    <td width="20%">Amount</td>
                </tr>
            </thead>
            <tbody>
                <?php $tax = 0; $amount = 0; ?>
                @for ($i = 0; $i < count($items); $i++)
                <tr id="v-rows-{{ $i }}">
                    <td class="v-no v-num-{{ $i }}">{{ $items[$i]->item }}</td>
                    <td class="v-type-{{ $i }}">{{ $items[$i]->quantity }}</td>
                    <td class="v-color-{{ $i }}">{{ $items[$i]->account->name }}</td>
                    <td class="v-model-{{ $i }}">{{ $items[$i]->tax }}%</td>
                    <td class="v-tax-amount-{{ $i }}">{{ number_format($items[$i]->tax_amount, 2) }}</td>
                    <td class="v-brand-{{ $i }}">{{ number_format($items[$i]->amount, 2) }}</td>
                </tr>
                <?php $tax += $items[$i]->tax_amount; $amount += $items[$i]->amount; ?>
                @endfor

                @if(count($items) < 1)
                <tr>
                    <td colspan="5">No items registered</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div>

            <div class="total">
                <span>Amount Due</span>
                <strong class="greenBack"><?= number_format($amount, 2) ?></strong>
            </div>

            <div class="total">
                <span>Tax Amount</span>
                <strong class="redBack"><?= number_format($tax, 2) ?></strong>
            </div>

            <div class="total">
                <span>Nett Amount</span>
                <strong class="blueBack"><?= number_format($amount - $tax, 2) ?></strong>
            </div>

            @if($amount > $account->paid)
                <div class="total">
                    <span>Remaining Credit</span>
                    <strong class="greyBack"><?= number_format(($account->due - $account->paid), 2) ?></strong>
                </div>
            @endif
            <div class="clear"></div>

        </div>

    </div>
</div>

<div class="widget fluid">
    <div class="whead">
        <h6>Fill belows information to pay invoice.</h6>
        <div class="clear"></div>
    </div>

    <div class="fluid">

        {{ Form::hidden('due', $account->due) }}

        {{ Form::nginput('text', 'subject_payment', $account->subject_payment !== null ? $account->subject_payment : Input::old('subject_payment'), $accountTransType === 'D' ? 'From *' : 'To *' ) }}

        {{ Form::nginput('text', 'paid', $account->paid !== null ? $account->paid : Input::old('paid'), 'Payment Amount *' ) }}

        <div class="formRow">
            <div class="grid3"><label>Payment Date *</label></div>
            <div class="grid9">
                <ul class="timeRange">
                    <li><input name="payment_date" type="text" class="datepicker" value="{{ $payment_date }}" /></li>
                    <li class="sep">-</li>
                    <li><input name="payment_time" type="text" class="timepicker" size="10" value="{{ $payment_time }}" />
                        <span class="ui-datepicker-append">(hh:mm:ss)</span>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div class="widget fluid">
    <div class="wheadLight2">
        <h6>Action</h6>
        <div class="clear"></div>
    </div>

    <div class="formRow noBorderB">
        <div class="status" id="status3">
            <div class="grid8">
                <span class="">click save button to register this {{ $accountTransType === 'D' ? 'Account Receivable' : 'Account Payable' }} or cancel to return</span>
            </div>
            <div class="grid4">
                <div class="formSubmit">
                    {{ HTML::link( $accountTransType === 'D' ? 'account/account_receivable' : 'account/account_payable', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                    <a href='{{ url("account/print/$accountTransType/$account->id") }}'
                       class="buttonL bDefault mb10 mt5"
                       original-title="Print Invoice" target="_blank">Print
                    </a>
                    <input class="appconfirm buttonL bGreen mb10 mt5" type="submit" value="Save"
                           original-title="Pay Invoice"
                           dialog-confirm-title="Payment Confirmation"
                           dialog-confirm-content="Please be assure of information you filled. This will update pay amount. Are you sure?"
                           dialog-confirm-callback="$('#form1').submit();">
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
</div>

</fieldset>

{{ Form::close() }}

@endsection



@section('breadLinks')

    <li class="has">
        <a title="">
            <i class="icos-money3"></i>
            <span>{{ $accountTransType === 'D' ? 'Account Receivable' : 'Account Payable' }}</span>
            <span><img src='{{ asset("images/elements/control/hasddArrow.png") }}' alt="" /></span>
        </a>
        <ul>
            <li><a href='{{ url("account/invoice_in/$accountTransType") }}' title=""><span class="icos-add"></span>Add New</a></li>
            <li><a href='{{ url("account/invoice_edit/$accountTransType/$account->id") }}' title=""><span class="icos-pencil"></span>Edit Information</a></li>
        </ul>
    </li>

@endsection
