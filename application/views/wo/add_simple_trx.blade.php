@section('content')
<style type="text/css">
    .ui-spinner { width: 3em;}
</style>
@include('partial.notification')
<br>
{{ Form::open('/work_order/add', 'POST', array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}

<div id="inputhid" style="display: none">
    <input type="hidden" id="checkbox-register" name="checkbox-register" value="" />
    <input type="hidden" id="action-button" name="action-button" value="">
    <input type="hidden" id="transaction-type" name="transaction-type" value="{{TransactionType::SIMPLE_TRANSACTION}}">
    <input type="hidden" id="vehiclesid" name="vehiclesid" value="0">
    <input type="hidden" id="customerId" name="customerId" value="0">
</div>
<fieldset>
<div class="widget fluid" style="margin-top: 15px;">
    <div class="whead">
        <h6>Customer</h6>
        <div class="clear"></div>
    </div>

    <div class="formRow">
        <div class="grid3"><label>Customer</label> </div>
        <div class="grid5">
            <div class="searchLine" style="margin-top: 0px">
                <form action="">
                    <input type="text" id="customerName" name="customerName" class="ac ui-autocomplete-input" placeholder="customer name" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$wodata['customerName']}}">
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="formRow">
        <div class="grid3"><label>Vehicle No</label> </div>
        <div class="grid5">
            <div class="searchLine" style="margin-top: 0px">
                    <input type="text" id="vehiclesnumber" name="vehiclesnumber" class="upperCase" placeholder="vehicle no customer" value="{{$wodata['vehiclesnumber']}}">
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>




<div class="widget fluid" style="margin-top: 15px;">
    <div class="whead">
        <h6>Add Service</h6>

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
        <a href="#vehicle-body" id="add-service" class="buttonH bBlue mb10 mt5" title="">Add This Service</a>
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
            @if($wodata['servicesdata'])
            @foreach($wodata['servicesdata'] as $trx_service)
            <tr id="s-rows-{{$no}}">
                <td class="s-no s-num-{{$no}}">{{$no+1}}</td>
                <td class="s-name-{{$no}}">{{@$trx_service['servicename']}}</td>
                <td class="s-price-{{$no}}">{{@$trx_service['serviceprice']}}</td>
                <td class="s-desc-{{$no}}">{{@$trx_service['servicedescription']}}</td>
                <td>
                    <div>
                        <a href="#service-tbody" onclick="WorkOrder.service.remove('s-rows-{{$no}}')">remove</a>
                    </div>
                    <div style="display: none; ">
                        <input class="s-no-hid-{{$no}}" type="hidden" name="services[{{$no}}][service_formula_id]" value="{{@$wodata['services'][$no]['service_formula_id']}}">
                        <input class="s-no-hid-{{$no}}" type="hidden" name="servicesdata[{{$no}}][servicename]" value="{{@$trx_service['servicename']}}">
                        <input class="s-no-hid-{{$no}}" type="hidden" name="servicesdata[{{$no}}][serviceprice]" value="{{@$trx_service['serviceprice']}}">
                        <input class="s-no-hid-{{$no}}" type="hidden" name="servicesdata[{{$no}}][servicedescription]" value="{{@$trx_service['servicedescription']}}">
                    </div>
                </td>
            </tr>
            <?php $no++; ?>
            @endforeach
            @endif
            </tbody>
            <input type="hidden" id="service-rows" value="{{$no}}"/>
            <div id="service-input-wrapper" style="display: none;"></div>
        </table>
    </div>
</div>

<div class="widget fluid" style="margin-top: 15px;">
    <div class="whead">
        <h6>Assign Mechanic</h6>
        <div class="clear"></div>
    </div>

    <div class="formRow">
        <div class="grid3">{{Form::label('mechanic', 'Mechanic')}}</div>
        <div class="grid9">
            {{Form::select('merchanic', $selectionMechanic, isset($mechanic['id']) ? $mechanic['id'] : 0, array('class' => 'validate[required]', 'id' => 'mechanic'))}}
            <a href="#vehicle-body" id="add-mechanic" class="buttonH bBlue mb10 mt5" title="" style="margin-right: 20px;">Assign this mechanic</a>
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
                @if(@$wodata['users'])
                @foreach(@$wodata['usersdata'] as $mechanic)
                <tr id="m-rows-{{$no}}">
                    <td class="m-no m-num-{{$no}}">{{$no}}</td>
                    <td class="m-name-{{$no}}">{{@$mechanic['mechanicname']}}</td>
                    <td>
                        <div><a href="#mechanic-tbody" onclick="WorkOrder.mechanic.remove('m-rows-{{$no}}')">remove</a></div>
                        <div style="display: none; ">
                            <input class="m-no-hid-{{$no}}" type="hidden" name="users[{{$no}}][user_id]" value="{{$wodata['users'][$no]['user_id']}}">
                            <input class="m-no-hid-{{$no}}" type="hidden" name="usersdata[{{$no}}][mechanicname]" value="{{$mechanic['mechanicname']}}">
                        </div>
                    </td>
                </tr>
                <?php $no++; ?>
                @endforeach
                @endif
                </tbody>
            </table>
            <input type="hidden" id="mechanic-rows" value="0"/>
            <div id="mechanic-input-wrapper" style="display: none;"></div>
        </div>
    </div>
</div>

<div class="widget fluid" style="margin-top: 15px;">
    <div class="wheadLight2">
        <h6>Action</h6>
        <div class="clear"></div>
    </div>

    <div class="formRow noBorderB">
        <div class="status" id="status3">
            <div class="grid5">
                <span class="">click button to save & closed this transaction</span>
            </div>
            <div class="grid7">
                <div class="formSubmit">
                    {{ Form::submit('Save & Closed', array( 'class' => 'buttonL bGold mb10 mt5', 'id' => 'buttonSaveClosedWO' )) }} &nbsp;&nbsp;
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
<div id="item-dialog" class="dialog" title="Item list" style="display: none;">

</div>

<!-- Dialog content select items-->
<div id="submit-confirm" class="dialog" title="Submit Confirmation" style="display: none;">

</div>

<!-- Dialog confirmation assign mechanic-->
<div id="mechanic-dialog" class="dialog" title="Item list" style="display: none;">

</div>

<!-- Dialog content select items-->
<div id="notif-dialog" class="dialog" title="Notification" style="display: none;">

</div>

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

<div id="vehicle-dialog" class="dialog" title="Vehicle Registration Form" style="display: none;">
    <form id="vehicle-form" name="vehicle-form">
        <div class="messageTo">
            <span> Register vehicle to <strong><span id="vehicle-customer-name"></span></strong></span>
        </div>
        <div class="divider"><span></span></div>
        <div class="dialogSelect m10">
            <label>Vehicle Number *</label>
            <input type="text" id="vehicle-no" class="upperCase"/>
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

<div id="new-customer-dialog" class="dialog" title="New Customer" style="display: none;">
    <form id="customer-form" name="vehicle-form">
        <div class="dialogSelect m10" id="customer-dialog-notification"></div>
        <div class="divider"><span></span></div>
        <div class="fluid">
            <div class="grid6">
                <div class="dialogSelect m10">
                    <label>Name *</label>
                    <input type="text" id="customer-name"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Address 1 *</label>
                    <input type="text" id="customer-address1"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Address 2</label>
                    <input type="text" id="customer-address2"/>
                </div>
                <div class="dialogSelect m10">
                    <label>City</label>
                    <input type="text" id="customer-city"/>
                </div>
            </div>
            <div class="grid6">
                <div class="dialogSelect m10">
                    <label>Postal Code</label>
                    <input type="text" id="customer-post_code"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Phone 1 *</label>
                    <input type="text" id="customer-phone1"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Phone 2</label>
                    <input type="text" id="customer-phone2"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Additional Info</label>
                    <input type="text" id="customer-additional_info"/>
                </div>
            </div>
        </div>
        <input type="hidden" id="new-customer-method" value="add"/>
    </form>
</div>
<div>

</div>

</fieldset>

{{ Form::close() }}
@endsection