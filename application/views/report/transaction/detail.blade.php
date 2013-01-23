@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.transaction_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

<fieldset>
    <div class="widget fluid">
        <div class="whead">
            <h6>
                Transaction Detail
            </h6>

            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Work Order No</label> </div>
            <div class="grid5">
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->workorder_no}}">
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
                <input type="text"  name="customerName" class="ac ui-autocomplete-input" readonly="true" value="{{$transaction->vehicle->customer->name}}">
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
            @foreach($transaction->transaction_item as $trx_item)
            <tr>
                <td>{{ $trx_item->item_price->item->item_category->name}}</td>
                <td>{{ $trx_item->item_price->item->name}}</td>
                <td>{{ @$trx_item->item_price->item->item_unit->name}}</td>
                <td>{{ $trx_item->quantity}}</td>
                <td>{{ $trx_item->item_price->price}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div class="divider"><span></span></div>
    <span class=""><h6>MECHANIC LIST</h6></span>
    <div class="widget" style="margin-top: 10px;width: 50%;">
        <table cellpadding="0" cellspacing="0" width="100%" class="tDark">
            <thead>
            <tr>
                <td>Id</td>
                <td>Name</td>
            </tr>
            </thead>
            <tbody>
            @foreach($transaction->user_workorder as $mechanic)
            <tr>
                <td>{{ $mechanic->user->id }}</td>
                <td>{{ $mechanic->user->name }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    </div>

</fieldset>


@endsection




