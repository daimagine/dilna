@section('content')

@include('partial.notification')
<br>
<ul class="middleNavA">
    @foreach($allItemCategory as $category)
    <li><a href='{{ url("item/add?category=$category->id") }}' title="{{$category->name}}" style="width: 100px;height: 65px;"><img src='{{ asset("images/icons/color/config.png") }}' alt="" /><span style="@if($itemCategory->name == $category->name) color:red @endif">{{$category->name}}</span></a></li>
    @endforeach
</ul>
<div class="divider"><span></span></div>
<!--, 'onsubmit' => 'return confirmSubmit()'-->
{{ Form::open('/item/add', 'POST', array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Add Item {{$itemCategory->name}}</h6>

            <div class="clear"></div>
        </div>

        {{ Form::hidden('item_category_id', $itemCategory->id) }}

        {{ Form::nyelect('item_type_id', @$itemType, isset($item['item_type_id']) ? $item['item_type_id'] : 1, 'Item Type', array('class' => 'validate[required]')) }}

        {{ Form::nyelect('unit_id', @$unitType, isset($item['unit_id']) ? $item['unit_id'] : 1, 'Unit Type', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'name', @$item['name'], 'Name', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'code', @$code, 'Code', array('class' => 'validate[required]', 'readonly' => 'true')) }}

        {{ Form::nginput('text', 'stock_minimum', @$item['stock_minimum'], 'Stock Minimum', array('class' => 'validate[required,custom[number]]')) }}

<!--        @if($accountTransaction!=null)-->
<!--        {{ Form::nyelect('account_transaction_id', @$accountTransaction, isset($item['account_transaction_id']) ? $item['account_transaction_id'] : null, 'Account Transaction', array('class' => 'validate[required]')) }}-->
<!---->
<!--        <div class="formRow">-->
<!--            <div class="grid3"><label>Stock</label></div>-->
<!--            <div class="grid9">-->
<!--                <div class="grid5">-->
<!--                    <input name="stock" type="text" id="stockValue" value="{{ @$item['stock'] }}" class="validate[required,custom[number]]"/>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="clear"></div>-->
<!--        </div>-->
<!--        @endif-->

        {{ Form::nginput('text', 'description', @$access['description'], 'Description', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'price', @$item['price'], 'Selling Price', array('class' => 'validate[required,custom[number]]', 'id' => 'sellingPrice')) }}

        {{ Form::nginput('text', 'purchase_price', @$item['purchase_price'], 'Purchase Price', array('class' => 'validate[required,custom[number]]', 'id' => 'purchasePrice')) }}

        {{ Form::nginput('text', 'vendor', @$item['vendor'], 'Vendor', array('class' => 'validate[required]')) }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), isset($item['status']) ? $item['status'] : 1, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('item/list?category='.$itemCategory->id, 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5', 'id' => 'confirmAddButton' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

<div id="confirm-dialog" title="Confirmation Add New Item {{$itemCategory->name}}">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span> Are you sure the data is correct ?</p>
    <p>Item Name : <strong><span id="itemName"></span></strong></p>
    <p>Item Code : <strong><span id="itemCode"></span></strong></p>
    <p>Item Sale Price  : <strong><span id="itemPrice"></span></strong></p>
    <p>Item Purchase Price : <strong><span id="itemPurchasePrice"></span></strong></p>
    <p>If this is correct, click Submit Form.</p>
    <p>To edit, click Cancel.<p>
</div>

{{ Form::close() }}

@endsection