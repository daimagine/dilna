@section('content')

@include('partial.notification')
<br>

{{ Form::open('/account/invoice_in', 'POST', array('id' => 'formAccountCore')) }}

{{ Form::hidden('type', $accountTransType) }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Add Invoice {{ $accountTransType === 'D' ? 'Account Receivable' : 'Account Payable' }}</h6>

            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'subject', Input::old('subject'), $accountTransType === 'D' ? 'To *' : ($accountTransType === 'C' ? 'From *' : 'Subject *'), array( 'id' => 'account-name' ) ) }}

        {{ Form::nginput('text', 'invoice_no', Input::old('reference_no'), 'Invoice *') }}

        {{ Form::nginput('text', 'reference_no', $referenceNo, 'Reference', array( 'readonly' => 'readonly' )) }}

        <div class="formRow">
            <div class="grid3"><label>Account Type</label></div>
            <div class="grid9">
                <select name="account_id">
                    @foreach($accountAccountings as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Invoice Date *</label></div>
            <div class="grid9">
                <ul class="timeRange">
                    <li><input name="invoice_date" type="text" class="datepicker" value="{{ Input::old('invoice_date') }}" /></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>


        <div class="formRow">
            <div class="grid3"><label>Due Date *</label></div>
            <div class="grid9">
                <ul class="timeRange">
                    <li><input name="due_date" type="text" class="datepicker" value="{{ Input::old('due_date') }}" /></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), Input::old('subject') !== null ? Input::old('status') : 1, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', Input::old('description'), 'Description') }}

    </div>

    <div class="widget">
        <div id="item-whead" class="whead " >
            <h6>item</h6>
            <a href="#item-body" class="buttonH bBlue" title="" onclick="Account.Item.openDialog();">Add</a>
            <div class="clear"></div>
        </div>
        <div id="item-body" class="body" style="display: block; ">
            @if( Input::old('items')==null )
            <span id="item-addnotice" class="">click add button to register new item</span>
            @endif

            <table id="item-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style=" {{ ( Input::old('items') == null ) ? 'display:none;' : '' }} ">
                <thead>
                    <tr>
                        <td>Item</td>
                        <td>Quantity</td>
                        <td>Account</td>
                        <td>Tax Amount</td>
                        <td>Amount</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody id="item-tbody">

                </tbody>
            </table>
            <input type="hidden" id="item-rows" value="0"/>
            <div id="item-input-wrapper" style="display: none;"></div>

            <div class="divider"></div>
            <div class="fluid">
                <div class="rtl-inputs">
                    <div class="grid7">
                        <ul class="wInvoice">
                            <li><h4 class="blue" id="item-subtotal">0</h4><span>Subtotal</span></li>
                            <li><h4 class="red" id="item-subtotal-tax">0</h4><span>Total Tax</span></li>
                            <li><h4 class="green" id="item-total">0</h4><span>Total Amount</span></li>
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
        </form>
    </div>

</fieldset>

{{ Form::close() }}

@endsection