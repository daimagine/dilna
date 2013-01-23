@section('content')

@include('partial.notification')
<br>

{{ Form::open('/customer/add', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Customer Add</h6>

            <div class="clear"></div>
        </div>
		
        {{ Form::nginput('text', 'name', @$customer['name'], 'Name *', array( 'id' => 'customer-name' ) ) }}

        {{ Form::nginput('text', 'address1', @$customer['address1'], 'Address 1')}}

        {{ Form::nginput('text', 'address2', @$customer['address2'], 'Address 2')}}

        {{ Form::nginput('text', 'city', @$customer['city'], 'City')}}
		
		{{ Form::nginput('text', 'post_code', @$customer['post_code'], 'Postal Code')}}

        {{ Form::nginput('text', 'phone1', @$customer['phone1'], 'Phone 1')}}

        {{ Form::nginput('text', 'phone2', @$customer['phone2'], 'Phone 2')}}

        {{ Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), isset($customer['status']) ? $customer['status'] : 1, 'Status *') }}
		
        {{ Form::nginput('text', 'additional_info', @$customer['additional_info'], 'Additional Info')}}

    </div>

    <div class="widget">
        <div id="vehicle-whead" class="whead " >
            <h6>Vehicle</h6>
            <a href="#vehicle-body" class="buttonH bBlue" title="" onclick="Customer.Vehicle.openDialog();">Add</a>
            <div class="clear"></div>
        </div>
        <div id="vehicle-body" class="body" style="display: block; ">
            @if(!isset($customer['vehicles']))
                <span id="vehicle-addnotice" class="">click add button to register new vehicle</span>
            @endif

            <table id="vehicle-table" cellpadding="0" cellspacing="0" width="100%" class="tDark" style=" {{ !isset($customer['vehicles']) ? 'display:none;' : '' }} ">
                <thead>
                <tr>
                    <td>No</td>
                    <td>Type</td>
                    <td>Color</td>
                    <td>Model</td>
                    <td>Brand</td>
                    <td>Description</td>
                    <td></td>
                </tr>
                </thead>
                <tbody id="vehicle-tbody">

                </tbody>
            </table>
            <input type="hidden" id="vehicle-rows" value="0"/>
            <div id="vehicle-input-wrapper" style="display: none;"></div>
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
                    <span class="">click save button to register this customer or cancel to return</span>
                </div>
                <div class="grid7">
                    <div class="formSubmit">
                        {{ HTML::link('customer/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                        {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <!-- Dialog content -->
    <div id="vehicle-dialog" class="dialog" title="Vehicle Registration Form" style="display: none;">
        <form id="vehicle-form" name="vehicle-form">
            <div class="messageTo">
                <span> Register vehicle to <strong><span id="vehicle-customer-name"></span></strong></span>
            </div>
            <div class="divider"><span></span></div>
            <div class="dialogSelect m10" id="vehicle-dialog-notification"></div>
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
                <label>Vehicle Year</label>
                <input type="text" id="vehicle-year"/>
            </div>
            <div class="dialogSelect m10">
                <label>Vehicle Description</label>
                <input type="text" id="vehicle-description"/>
            </div>
            <input type="hidden" id="vehicle-method" value="add"/>
            <input type="hidden" id="vehicle-addkey" value="-1"/>
        </form>
    </div>

</fieldset>

{{ Form::close() }}

@endsection

