@section('content')

@include('partial.notification')
<br>

{{ Form::open('/work_order/do_closed', 'POST', array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}
<input type="hidden" id="trxId" name="id" value="{{$transaction->id}}">
<div id="inputhid">
    <input type="hidden" id="mechanicField" value="{{sizeof($transaction->user_workorder)}}">
    <input type="hidden" id="serviceField" value="{{sizeof($transaction->transaction_service)}}">
    <input type="hidden" id="transactionId" value="{{$transaction->id}}">
</div>
<fieldset>
    <div class="widget fluid">
        <div class="whead">
            <h6>
                @if($action == 'D')
                Detail Information
                @elseif($action == 'C')
                Closed
                @endif
                Workorder
            </h6>

            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Work Order No</label> </div>
            <div class="grid5">
                <input type="text" id="workorderno" name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->workorder_no}}">
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Status Work Order</label> </div>
            <div class="grid5">
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->status == 'O' ? 'OPEN' : ($transaction->status == 'D' ? 'CLOSED' : ($transaction->status == 'C' ? 'CANCELED' : ''))}}">
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Customer</label> </div>
            <div class="grid5">
<!--                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->vehicle->customer->name}}">-->
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->customer_name}}">
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Vehicle Model</label> </div>
            <div class="grid5">
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->vehicle->model}}">
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Vehicle No</label> </div>
            <div class="grid5">
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->vehicle->number}}">
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Service Time</label> </div>
            <div class="grid5">
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->date}}">
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="divider"><span></span></div>
    <span class=""><h6>SERVICE LIST</h6></span>
    <div class="widget" style="margin-top: 10px;width: 70%;">
        <table cellpadding="0" cellspacing="0" width="100%" class="tDark">
            <thead>
            <tr>
                <td>Service Name</td>
                <td>Price</td>
                <td>Detail</td>
            </tr>
            </thead>
            <tbody>
            @foreach($transaction->transaction_service as $trx_service)
            <tr>
                <td>{{ $trx_service->service_formula->service->name }}</td>
                <td>{{ $trx_service->service_formula->price }}</td>
                <td>{{ $trx_service->service_formula->service->description }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"><span></span></div>
    <span class=""><h6>ITEM LIST</h6></span>
    <div class="widget" style="margin-top: 10px;width: 70%;">
        <table cellpadding="0" cellspacing="0" width="100%" class="tDark">
            <thead>
            <tr>
                <td>Category</td>
                <td>Name</td>
                <td>Unit</td>
                <td>Quantity</td>
                <td>Price</td>
            </tr>
            </thead>
            <tbody>
            @if(isset($transaction->transaction_item) && count($transaction->transaction_item) > 0)
            @foreach($transaction->transaction_item as $trx_item)
            <tr>
                <td>{{ $trx_item->item_price->item->item_category->name}}</td>
                <td>{{ $trx_item->item_price->item->name}}</td>
                <td>{{ $trx_item->item_price->item->item_unit->name}}</td>
                <td>{{ $trx_item->quantity}}</td>
                <td>{{ $trx_item->item_price->price}}</td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>


    <div class="divider"><span></span></div>
    <span class=""><h6>MECHANIC LIST</h6></span>
    <div class="widget" style="margin-top: 10px;width: 50%;">
        <table cellpadding="0" cellspacing="0" width="100%" class="tDark">
            <thead>
            <tr>
                <td>Staff Id</td>
                <td>Name</td>
            </tr>
            </thead>
            <tbody>
            @foreach($transaction->user_workorder as $mechanic)
            <tr>
                <td>{{ $mechanic->user->staff_id }}</td>
                <td>{{ $mechanic->user->name }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"><span></span></div>
    <span class=""><h6>PAYMENT DETAIL</h6></span>
    <div class="widget" style="margin-top: 10px;width: 100%;">
        <table cellpadding="0" cellspacing="0" width="100%" class="tDark">
            <thead>
            <tr>
                <td>Detail</td>
                <td>Unit</td>
                <td>Qty</td>
                <td>Sub Total</td>
                <td>Total</td>
            </tr>
            </thead>
            <tbody>
            @foreach($transaction->transaction_service as  $trx_service)
            <tr>
                <td>{{ $trx_service->service_formula->service->name }}</td>
                <td>-</td>
                <td align="center">1</td>
                <td align="right">IDR {{ $trx_service->service_formula->price }}</td>
                <td align="right">IDR {{ ($trx_service->service_formula->price)}}</td>
            </tr>
            @endforeach
            @if(isset($transaction->transaction_item) && count($transaction->transaction_item) > 0)
            @foreach($transaction->transaction_item as $trx_item)
            <?php $total=number_format((float)(($trx_item->item_price->price) * ($trx_item->quantity)), 2, '.', ''); ?>
            <tr>
                <td>{{ $trx_item->item_price->item->name}}</td>
                <td>{{ $trx_item->item_price->item->item_unit->name}}</td>
                <td align="center">{{ $trx_item->quantity}}</td>
                <td align="right">IDR {{ $trx_item->item_price->price}}</td>
                <td align="right">IDR {{$total}}</td>
            </tr>
            @endforeach
            @endif
            <tr>
                <td></td><td></td><td></td>
                <td style="color: #ff0000;"><strong>Sub Total</strong></td>
                <td align="right" style="color: #ff0000;"><strong>IDR {{$transaction->amount}}</strong></td>
            </tr>
            <tr>
                <td></td><td></td><td></td>
                <td style="color: #ff0000;"><strong>Pph 0%</strong></td>
                <td align="right" style="color: #ff0000;"><strong>IDR 0.00</strong></td>
            </tr>
            <tr>
                <td></td><td></td><td></td>
                <td>
                    <strong style="color: #ff0000;">Member Discount Service
                    @if(isset($transaction->vehicle->membership->discount))
                        {{$transaction->vehicle->membership->discount->value}} %
                    @endif
                    </strong>
                </td>
                <td align="right" style="color: #ff0000;"><strong>IDR {{$transaction->discount_amount}}</strong></td>
            </tr>
            <tr>
                <td></td><td></td><td></td>
                <td style="color: #ff0000;"><strong>Total</strong></td>
                <td align="right" style="color:#ff0000;"><strong>IDR {{$transaction->paid_amount}}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>

    @if($transaction->status == 'O' and $action == 'D')
    <div class="divider"><span></span></div>
    <div class="widget fluid">
        <div class="wheadLight2">
            <h6>Action</h6>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Customer</label> </div>
            <div class="grid5">
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->vehicle->customer->name}}">
            </div>
            <div class="clear"></div>
        </div>
    </div>
    @endif

    <div class="divider"><span></span></div>
    <div class="widget fluid">
        <div class="wheadLight2">
            <h6>Action</h6>
            <div class="clear"></div>
        </div>

        <div class="formRow noBorderB">
            <div class="status" id="status3">
                <div class="grid5">
                    <span class="">{{ HTML::link('work_order/list', 'Back', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}</span>
                </div>
                <div class="grid7">
                    <div class="formSubmit">
                        @if($transaction->status == 'O')
                        {{ Form::submit('Close WO', array( 'class' => 'buttonAction buttonM bGreen' )) }}
                        @endif
                        <a href='{{ url("work_order/print_invoice/$transaction->id") }}' target="_blank" class="buttonM bGold"><span class="iconb" data-icon=""  style="margin-left: 0px;"></span><span>Print Invoice</span></a>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <!-- Dialog add service confirmation -->
    <div id="closed_confirmation" class="dialog" title="Confirmation" style="display: none;">
           <div id="msg-closed"></div>
            <div class="divider"><span></span></div>
            <div class="fluid">
                <div class="grid9">
                    <div class="dialogSelect m10">
                        <label>Payment Method *</label>
                        {{Form::select('option_payment_method', array('' => '--Select This--', 'C' => 'CASH', 'E' => 'EDC', 'M' => 'MONTHLY', 'O' => 'CORPORATE'), '', array('id' => 'option_payment_method'));}}
                    </div>
                </div>
            </div>
    </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection




