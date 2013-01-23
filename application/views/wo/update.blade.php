@section('content')

@include('partial.notification')
<br>

{{ Form::open('/work_order/edit', 'POST', array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}
<fieldset>
<div class="widget fluid">
    <div class="whead">
        <h6>UPDATE WORKORDER {{$transaction->workorder_no}}</h6>

        <div class="clear"></div>
    </div>

    <div class="formRow">
        <div class="grid3"><label>Customer</label> </div>
        <div class="grid5">
            <div class="searchLine" style="margin-top: 0px">
                <form action="">
                    <input type="text" id="customerName" name="customerName" class="ac ui-autocomplete-input" placeholder="Enter search name..." autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$transaction->vehicle->customer->name}}">
                    <a onclick="WorkOrder.customer.openDialog_lst_customer('edit')"><span class="icos-search" style="position: absolute;top: 0;right: 0;"></span></a>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="formRow">
        <div class="grid3"><label>Status</label> </div>
        <div class="grid5">
            <div class="searchLine" style="margin-top: 0px">
                <form action="">
                    <input type="text" id="memberStatus" name="search" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" disabled="true" @if($transaction->vehicle->customer->membership) value="Member" @else value="Non Member" @endif></button>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div>
        <input type="hidden" id="customerId" name="customerId" value="{{$transaction->vehicle->customer->id}}">
        <input type="hidden" id="trxId" name="id" value="{{$transaction->id}}">
    </div>
</div>


<div class="widget fluid">
    <div id="vehicle-whead" class="whead " >
        <h6>Vehicle</h6>
        <a href="#vehicle-body" id="add-new-vehicle" class="buttonH bBlue" title="" onclick="WorkOrder.customer.openDialog_vehicle();" style="display: none;">Add</a>
        <div class="clear"></div>
    </div>
    <div id="vehicle-body" class="body" style="display: block; ">
        @if(!$transaction->vehicle)
        <span id="vehicle-addnotice" class="">Search customer member first <br/> or click add button to add new vehicle for customer non member</span>
        @endif

        <table id="vehicle-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style=" {{ !isset($transaction->vehicle) ? 'display:none;' : '' }} ">
            <thead>
            <tr>
                <td>Vehicle No</td>
                <td>Type</td>
                <td>Color</td>
                <td>Model</td>
                <td>Brand</td>
                <td>Description</td>
            </tr>
            </thead>
            <tbody id="vehicle-tbody">
            <tr id="v-rows">
                <td class="v-no v-num">{{$transaction->vehicle->number}}</td>
                <td class="v-type">{{$transaction->vehicle->type}}</td>
                <td class="v-color">{{$transaction->vehicle->color}}</td>
                <td class="v-model">{{$transaction->vehicle->model}}</td>
                <td class="v-brand">{{$transaction->vehicle->brand}}</td>
                <td class="v-desc">{{$transaction->vehicle->description}}</td>
                <td>
                    <div><a href="#vehicle-tbody" onclick="WorkOrder.customer.remove()">remove</a></div>
                    <div style="display: none; ">
                        <input class="v-id-hid" type="hidden" name="vehiclesid" value="{{$transaction->vehicle->id}}">
                        <input class="v-id-hid" type="hidden" name="vehiclesnumber" value="{{$transaction->vehicle->number}}">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" id="vehicle-rows" value="1"/>
        <div id="vehicle-input-wrapper" style="display: none;"></div>
    </div>
</div>

<div class="widget fluid">
    <div class="whead">
        <h6>Edit Service</h6>

        <div class="clear"></div>
    </div>

    {{ Form::nyelect('unit_id', @$selectionService, isset($service['id']) ? $service['id'] : 0, 'Service Type', array('class' => 'validate[required]', 'id' => 'serviceType')) }}

    <div class="formRow">
        <div class="grid3"><label>Description</label></div>
        <div class="grid9">
            @if($lstService!=null)
            @foreach($lstService as $service)
            <textarea rows="8" cols="" name="description" class="desc-service" id="desc-{{$service->id}}" readonly="true" style="display: none">{{$service->description}}</textarea>
            {{ Form::hidden('price', $service->service_formula()->price, array('id'=>'service-price-'.$service->id)) }}
            @endforeach
            @endif
            <textarea rows="8" cols="" name="description" class="desc-service" id="desc-0" readonly="true"></textarea>
        </div>
        <a href="#vehicle-body" id="add-service" class="buttonH bBlue mb10 mt5" title="">Add New Service</a>
        <div class="clear"></div>
    </div>


    <div id="service-body" class="body" style="display: block; ">
        <table id="service-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style="">
            <thead>
            <tr>
                <td>No</td>
                <td>Service Name</td>
                <td>Service Price</td>
                <td>Description</td>
            </tr>
            </thead>
            <tbody id="service-tbody">
            <?php $no=0; ?>
            @foreach($transaction->transaction_service as $trx_service)
            <tr id="s-rows-{{$no}}">
                <td class="s-no s-num-{{$no}}">{{$no+1}}</td>
                <td class="s-name-{{$no}}">{{$trx_service->service_formula->service->name}}</td>
                <td class="s-price-{{$no}}">{{$trx_service->service_formula->price}}</td>
                <td class="s-desc-{{$no}}">{{$trx_service->service_formula->service->description}}</td>
                <td>
                    <div>
                        <a href="#service-tbody" onclick="WorkOrder.service.remove('s-rows-{{$no}}')">remove</a>
                    </div>
                    <div style="display: none; ">
                        <input class="s-no-hid-{{$no}}" type="hidden" name="services[{{$no}}][service_formula_id]" value="{{$trx_service->service_formula->id}}">
                    </div>
                </td>
            </tr>
            <?php $no++; ?>
            @endforeach
            </tbody>
            <div id="service-input-wrapper" style="display: none;"></div>
            <input type="hidden" id="service-rows" value="{{$no}}"/>
        </table>
    </div>
</div>

<div class="widget fluid">
    <div id="item-whead" class="whead " >
        <h6>Edit Item</h6>
        <a href="#vehicle-body" class="buttonH bBlue" title="" onclick="WorkOrder.items.openDialog_lst_items('edit');">Add New Item</a>
        <div class="clear"></div>
    </div>
    <div id="item-body" class="body" style="display: block; ">
        @if(!isset($transaction->transaction_item))
        <span id="item-addnotice" class=""><em>click add button to include item for this work order</em></span>
        @endif

        <table id="item-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style=" {{!isset($transaction->transaction_item) ? 'display:none;' : '' }} ">
            <thead>
            <tr>
                <td>Type</td>
                <td>Unit</td>
                <td>Code</td>
                <td>Name</td>
                <td>Vendor</td>
                <td>Price</td>
                <td>Total</td>
            </tr>
            </thead>
            <tbody id="item-tbody">
            <?php $no=0; ?>
            @foreach($transaction->transaction_item as $trx_item)
                <tr id="i-rows-{{$no}}">
                    <td class="i-no i-type-{{$no}}">{{$trx_item->item_price->item->item_type->name}}</td>
                    <td class="i-unit-{{$no}}">{{$trx_item->item_price->item->item_unit->name}}</td>
                    <td class="i-code-{{$no}}">{{$trx_item->item_price->item->code}}</td>
                    <td class="i-name-{{$no}}">{{$trx_item->item_price->item->name}}</td>
                    <td class="i-vendor-{{$no}}">{{$trx_item->item_price->item->vendor}}</td>
                    <td class="i-price-{{$no}}">{{$trx_item->item_price->price}}</td>
                    <td class="i-total-{{$no}}">
                        <input id="item-quantity-{{$no}}" class="i-qty-hid-{{$no}}" style="width: 30px;" name="items[{{$no}}][quantity]" value="{{$trx_item->quantity}}">
                    </td>
                    <td>
                        <div><a href="#item-tbody" onclick="WorkOrder.items.remove('i-rows-{{$no}}')">remove</a></div>
                        <div style="display: none; ">
                            <input class="i-no-hid-{{$no}}" type="hidden" name="items[{{$no}}][item_id]" value="{{$trx_item->item_price->item->id}}">
                        </div>
                    </td>
                </tr>
            <?php $no++; ?>
            @endforeach
            </tbody>
        </table>
        <input type="hidden" id="item-rows" value="{{$no}}"/>
        <div id="item-input-wrapper" style="display: none;"></div>
    </div>
</div>

<div class="widget fluid">
    <div class="whead">
        <h6>Assign Mechanic</h6>
        <div class="clear"></div>
    </div>

    <div class="formRow">
        <div class="grid3">{{Form::label('mechanic', 'Mechanic')}}</div>
        <div class="grid9">
            {{Form::select('merchanic', $selectionMechanic, isset($mechanic['id']) ? $mechanic['id'] : 0, array('class' => 'validate[required]', 'id' => 'mechanic'))}}
            <a href="#vehicle-body" id="add-mechanic" class="buttonH bBlue mb10 mt5" title="" style="margin-right: 20px;">Assign New mechanic</a>
        </div>
        <div class="clear"></div>
    </div>


    <div class="grid7">
        <div id="mechanic-body" class="body" style="display: block; ">
            <table id="mechanic-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style="">
                <thead>
                <tr>
                    <td>No</td>
                    <td>Mechanic Name</td>
                </tr>
                </thead>
                <tbody id="mechanic-tbody">
                <?php $no=0; ?>
                @foreach($transaction->user_workorder as $mechanic)
                    <tr id="m-rows-{{$no}}">
                        <td class="m-no m-num-{{$no}}">{{$no}}</td>
                        <td class="m-name-{{$no}}">{{$mechanic->user->name}}</td>
                        <td>
                            <div><a href="#mechanic-tbody" onclick="WorkOrder.mechanic.remove('m-rows-{{$no}}')">remove</a></div>
                            <div style="display: none; "><input class="m-no-hid-{{$no}}" type="hidden" name="users[{{$no}}][user_id]" value="5"></div>
                        </td>
                    </tr>
                <?php $no++; ?>
                @endforeach
                </tbody>
            </table>
            <input type="hidden" id="mechanic-rows" value="{{$no}}"/>
            <div id="mechanic-input-wrapper" style="display: none;"></div>
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
            <div class="grid5">
                <span class="">click Update button to update this work order </br> or press button back to cancel</span>
            </div>
            <div class="grid7">
                <div class="formSubmit">
                    {{ HTML::link('work_order/list', 'Back', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                    {{ Form::submit('Update', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
</div>


<!-- Dialog add service confirmation -->
<div id="service-dialog" class="dialog" title="Add Service Confirmation" style="display: none;">
</div>

<!-- Dialog content select customer member -->
<div id="customer-dialog" class="dialog" title="Customer list" style="display: none;">

</div>

<!-- Dialog content select items-->
<div id="submit-confirm" class="dialog" title="Submit Confirmation" style="display: none;">

</div>

<!-- Dialog content select items-->
<div id="item-dialog" class="dialog" title="Item list" style="display: none;">

</div>

<!-- Dialog confirmation assign mechanic-->
<div id="mechanic-dialog" class="dialog" title="Item list" style="display: none;">

</div>

<div id="vehicle-dialog" class="dialog" title="Vehicle Registration Form" style="display: none;">
    <form id="vehicle-form" name="vehicle-form">
        <div class="messageTo">
            <span> Register vehicle to <strong><span id="vehicle-customer-name"></span></strong></span>
        </div>
        <div class="divider"><span></span></div>
        <div class="dialogSelect m10">
            <label>Vehicle Number *</label>
            <input type="text" id="vehicle-no"/>
        </div>
        <div class="dialogSelect m10">
            <label>Vehicle Type</label>
            <input type="text" id="vehicle-type"/>
        </div>
        <div class="dialogSelect m10">
            <label>Vehicle Color</label>
            <input type="text" id="vehicle-color"/>
        </div>
        <div class="dialogSelect m10">
            <label>Vehicle Model</label>
            <input type="text" id="vehicle-model"/>
        </div>
        <div class="dialogSelect m10">
            <label>Vehicle Brand</label>
            <input type="text" id="vehicle-brand"/>
        </div>
        <div class="dialogSelect m10">
            <label>Vehicle Description</label>
            <input type="text" id="vehicle-description"/>
        </div>
        <input type="hidden" id="customer-method" value="add"/>
    </form>
</div>

<div>

</div>

</fieldset>

{{ Form::close() }}

@endsection




