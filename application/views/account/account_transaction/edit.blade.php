@section('content')

@include('partial.notification')
<br>

{{ Form::open('/account/invoice_edit/'.$account->type, 'POST', array('id' => 'formAccountCore')) }}

{{ Form::hidden('id', $account->id) }}

{{ Form::hidden('type', $account->type) }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Edit Invoice {{ $accountTransType === 'D' ? 'Account Receivable' : 'Account Payable' }}</h6>

            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'subject', $account->subject, $accountTransType === 'D' ? 'To *' : ($accountTransType === 'C' ? 'From *' : 'Subject *'), array( 'id' => 'account-name' ) ) }}

        {{ Form::nginput('text', 'invoice_no', $account->invoice_no, 'Invoice *') }}

        {{ Form::nginput('text', 'reference_no', $account->reference_no, 'Reference', array( 'readonly' => 'readonly' )) }}

        <div class="formRow">
            <div class="grid3"><label>Account Type</label></div>
            <div class="grid9">
                <select name="account_id">
                    @foreach($accountAccountings as $key => $value)
                        <option value="{{ $key }}" {{ $account->account_id == $key ? 'selected="selected"' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Invoice Date *</label></div>
            <div class="grid9">
                <ul class="timeRange">
                    <li><input name="invoice_date" type="text" class="datepicker" value="{{ $invoice_date }}" /></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>


        <div class="formRow">
            <div class="grid3"><label>Due Date *</label></div>
            <div class="grid9">
                <ul class="timeRange">
                    <li><input name="due_date" type="text" class="datepicker" value="{{ $due_date }}" /></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $account->status, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', $account->description, 'Description') }}

    </div>

    <div class="widget">
        <div id="item-whead" class="whead " >
            <h6>item</h6>
            <a href="#item-body" class="buttonH bBlue" title="" onclick="Account.Item.openDialog();">Add</a>
            <div class="clear"></div>
        </div>
        <div id="item-body" class="body" style="display: block; ">
            @if( is_array($items) && empty($items) )
            <span id="item-addnotice" class="">click add button to register new item</span>
            @endif

            <table id="item-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style=" {{ ( is_array($items) && empty($items) ) ? 'display:none;' : '' }} ">
                <thead>
                    <tr>
                        <td>Item</td>
                        <td>Quantity</td>
                        <td>Account</td>
                        <td>Tax</td>
                        <td>Tax Amount</td>
                        <td>Amount</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody id="item-tbody">
                    <?php $tax = 0; $amount = 0; ?>
                    @for ($i = 0; $i < count($items); $i++)
                        <tr id="v-rows-{{ $i }}">
                            <td class="v-item v-num-{{ $i }}">{{ $items[$i]->item }}</td>
                            <td class="v-qty-{{ $i }}">{{ $items[$i]->quantity }}</td>
                            <td class="v-account-{{ $i }}">{{ $items[$i]->account->name }}</td>
                            <td class="v-tax-{{ $i }}">{{ $items[$i]->tax }}%</td>
                            <td class="v-tax-amount-{{ $i }}">{{ $items[$i]->tax_amount }}</td>
                            <td class="v-amount-{{ $i }}">{{ $items[$i]->amount }}</td>
                            <td>
                                <div>
                                    @if($items[$i]->approved_status == approvedStatus::CONFIRM_BY_WAREHOUSE)
                                        <a href="#">APPROVED</a>
                                    @else
                                        <a href="#item-tbody" onclick="Account.Item.edit('v-rows-{{ $i }}','{{ $i }}')">edit</a> |
                                        <a href="#item-tbody" onclick="Account.Item.remove('v-rows-{{ $i }}')">remove</a>
                                    @endif
                                </div>
                            </td>
                            <td style="display: none; ">
                                <input type="hidden" class="v-item-hid-{{ $i }}" name="items[{{ $i }}][item]" value="{{ $items[$i]->item }}" />
                                <input type="hidden" class="v-qty-hid-{{ $i }}" name="items[{{ $i }}][quantity]" value="{{ $items[$i]->quantity }}" />
                                <input type="hidden" class="v-account-hid-{{ $i }}" name="items[{{ $i }}][account_type_id]" value="{{ $items[$i]->account_type_id }}" />
                                <input type="hidden" class="v-tax-hid-{{ $i }} v-tax" name="items[{{ $i }}][tax]" value="{{ $items[$i]->tax }}" />
                                <input type="hidden" class="v-tax-amount-hid-{{ $i }} v-tax-amount" name="items[{{ $i }}][tax_amount]" value="{{ $items[$i]->tax_amount }}" />
                                <input type="hidden" class="v-amount-hid-{{ $i }} v-amount" name="items[{{ $i }}][amount]" value="{{ $items[$i]->amount }}" />
                                <input type="hidden" class="v-desc-hid-{{ $i }}" name="items[{{ $i }}][description]" value="{{ $items[$i]->description }}" />
                                <input type="hidden" class="v-unit-price-hid-{{ $i }}" name="items[{{ $i }}][unit_price]" value="{{ $items[$i]->unit_price }}" />
                                <input type="hidden" class="v-disc-hid-{{ $i }}" name="items[{{ $i }}][discount]" value="{{ $items[$i]->discount }}" />
                                <input type="hidden" class="v-approved-status-hid-{{ $i }}" name="items[{{ $i }}][approved_status]" value="{{ $items[$i]->approved_status }}" />
                                <input type="hidden" class="v-id-hid-{{ $i }}" name="items[{{ $i }}][id]" value="{{ $items[$i]->id }}" />
                                <input type="hidden" name="items[{{ $i }}][status]" value="{{ $items[$i]->status }}" />
                                <input type="hidden" name="items[{{ $i }}][approved_status]" value="{{ $items[$i]->approved_status }}" />
                            </td>
                        </tr>
                        <?php $tax += $items[$i]->tax_amount; $amount += $items[$i]->amount; ?>
                    @endfor
                </tbody>
            </table>
            <input type="hidden" id="item-rows" value="{{ empty($items) ? 0 : sizeof($items) }}"/>
            <div id="item-input-wrapper" style="display: none;"></div>

            <div class="divider"></div>
            <div class="fluid">
                <div class="rtl-inputs">
                    <div class="grid7">
                        <ul class="wInvoice">
                            <li><h4 class="blue" id="item-subtotal"><?= number_format($amount - $tax, 2) ?></h4><span>Subtotal</span></li>
                            <li><h4 class="red" id="item-subtotal-tax"><?= number_format($tax, 2) ?></h4><span>Total Tax</span></li>
                            <li><h4 class="green" id="item-total"><?= number_format($amount, 2) ?></h4><span>Total Amount</span></li>
                        </ul>
                    </div>
                </div>
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
                        <input class="appconfirm buttonL bGreen mb10 mt5" type="submit" value="Save"
                               original-title="Save Account"
                               dialog-confirm-title="Save Confirmation"
                               dialog-confirm-content="Please be assure of information you filled. This will save respected account. Are you sure?"
                               dialog-confirm-callback="$('#formAccountCore').submit();">
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <!-- Dialog content -->
    <div id="item-dialog" class="dialog" title="Item Registration Form" style="display: none;">
        <form id="item-form" name="item-form">
            <div class="messageTo">
                <span> Assign item to <strong><span id="item-account-name"></span></strong></span>
            </div>
            <div class="divider"><span></span></div>
            <div class="dialogSelect m10" id="item-dialog-notification"></div>

            <div class="fluid">
                <div class="grid6">
                    <div class="dialogSelect m10">
                        <label>Item Information *</label><br>
                        <input type="text" id="item-info"/>
                    </div>
                    <div class="dialogSelect m10">
                        <label>Item Description</label><br>
                        <input type="text" id="item-description"/>
                    </div>
                    <div class="dialogSelect m10">
                        <label>Item Quantity *</label><br>
                        <input type="text" id="item-quantity" onchange="Account.Item.calculateAmount()"/>
                    </div>
                    <div class="dialogSelect m10">
                        <label>Unit Price *</label><br>
                        <input type="text" id="item-unit-price" onchange="Account.Item.calculateAmount()"/>
                    </div>
                    <div class="dialogSelect m10">
                        <label>Discount</label><br>
                        <input type="text" id="item-discount" onchange="Account.Item.calculateAmount()"/>
                    </div>
                </div>
                <div class="grid6">
                    <div class="dialogSelect m10">
                        <label style="margin-bottom: -13px; display: block;">Account</label><br>
                        <select id="item-account-type">
                            @foreach($accounts as $key => $value)
                                <option id="select-account-id-{{ $key }}" value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="dialogSelect m10">
                        <label style="margin-bottom: -13px; display: block;">Tax Percentage *</label><br>
                        <input type="text" class="fix-ui-spinner" id="item-tax" onchange="Account.Item.calculateAmount()"/>
                        <div class="clear"></div>
                    </div>

                    <div class="dialogSelect m10">
                        <label>Tax Amount</label><br>
                        <input type="text" id="item-tax-amount" readonly="readonly" value="0"/>
                    </div>
                    <div class="dialogSelect m10">
                        <label>Amount</label><br>
                        <input type="text" id="item-amount" readonly="readonly" value="0"/>
                    </div>
                </div>
            </div>
            <input type="hidden" id="item-method" value="add"/>
            <input type="hidden" id="item-addkey" value="-1"/>
            <input type="hidden" id="item-approved-status"/>
            <input type="hidden" id="item-id"/>
        </form>
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
        <li><a href='{{ url("account/pay_invoice/$accountTransType/$account->id") }}' title=""><span class="icos-money2"></span>Do Payment</a></li>
    </ul>
</li>

@endsection
