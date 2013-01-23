@section('content')

@include('partial.notification')
<br>


{{ Form::open('item/edit', 'POST', array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Edit Item {{$category->name}}</h6>

            <div class="clear"></div>
        </div>

        {{ Form::hidden('id', $item->id) }}

        {{ Form::hidden('item_category_id', $category->id) }}

        {{ Form::nyelect('item_type_id', $itemType, $item->item_type_id, 'Item Type', array('class' => 'validate[required]')) }}

        {{ Form::nyelect('unit_id', $unitType, $item->unit_id, 'Unit Type', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'name', $item->name, 'Name', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'code', $item->code, 'Code', array('class' => 'validate[required]', 'disabled' => 'true')) }}

        {{ Form::nginput('text', 'stock_minimum', $item->stock_minimum, 'Stock Minimum', array('class' => 'validate[required,custom[number]]')) }}

        {{ Form::nginput('text', 'description', $item->description, 'Description', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'price', $item->price, 'Selling Price', array('class' => 'validate[required,custom[number]]', 'id' => 'sellingPrice')) }}

        {{ Form::nginput('text', 'purchase_price', $item->purchase_price, 'Purchase Price', array('class' => 'validate[required,custom[number]]', 'id' => 'purchasePrice')) }}

        {{ Form::nginput('text', 'vendor', $item->vendor, 'Vendor', array('class' => 'validate[required]')) }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $item->status, 'Status', array('class' => 'validate[required]')) --}}
        {{ Form::hidden('status', 1) }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('item/list?category='.$category->id, 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div id="confirm-dialog" title="Confirmation Update New Item {{$category->name}}">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span> Are you sure the data is correct ?</p>
        <p>Item Name : <strong><span id="itemName"></span></strong></p>
        <p>Item Code : <strong><span id="itemCode"></span></strong></p>
        <p>Item Sale Price  : <strong><span id="itemPrice"></span></strong></p>
        <p>Item Purchase Price : <strong><span id="itemPurchasePrice"></span></strong></p>
        <p>If this is correct, click Submit Form.</p>
        <p>To edit, click Cancel.<p>
    </div>


</fieldset>

{{ Form::close() }}

@endsection