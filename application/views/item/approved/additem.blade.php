<script type="text/javascript">
    $(function() {
        $("select, .check, .check :checkbox, input:radio, input:file").uniform();

        $('#dialogNewItem').click(function () {
            $('#formDialogNewItem').dialog('close');
            return false;
        });

    var opts = {
        'itemstock': {
            decimal: 1,
            min: 0,
            start: 0
        },
        'itemprice': {
            decimal: 2,
            min: 0,
            start: 0
        },
        'itempurchase_price': {
            decimal: 2,
            min: 0,
            start: 0
        }
    };
//        for (var n in opts) {
//            $("#"+n).spinner(opts[n]);
//        }

    });
</script>
        {{ Form::hidden('item_category_id', $itemCategory->id, array('id' => 'itemcategoryid')) }}
        {{ Form::hidden('status', 1) }}
        <div class="dialogSelect m10" id="newitem-dialog-notification"></div>
        <div class="divider"><span></span></div>
        <div class="fluid">
            <div class="grid6">
                <div class="dialogSelect m10">
                    <label style="margin-bottom: -13px; display: block;">Item Type *</label><br>
                    <select id="item_type_id" name="item_type_id" class="validate[required]">
                        @foreach($itemType as $key => $value)
                        <option id="{{ $key }}" value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="dialogSelect m10">
                    <label>Name *</label><br>
                    <input type="text" id="itemname" name="name" class="required"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Selling Price *</label><br>
                    <input type="text" id="itemprice" name="price" class="required"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Quantity Opname *</label><br>
                    <input type="text" id="itemstock" name="stock" class="required"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Description *</label><br>
                    <input type="text" id="description" name="description" class="required"/>
                </div>
            </div>
            <div class="grid6">
                <div class="dialogSelect m10">
                    <label style="margin-bottom: -13px; display: block;">Unit Type *</label><br>
                    <select id="unit_id" name="unit_id" class="validate[required]">
                        @foreach($unitType as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="dialogSelect m10">
                    <label>Code *</label><br>
                    <input type="text" id="itemcode" name="code" class="required" disabled="true" value="{{$code}}"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Purchase Price *</label><br>
                    <input type="text" id="itempurchase_price" name="purchase_price" class="required" value="{{$purchase_price}}"/>
                </div>
                <div class="dialogSelect m10">
                    <label>Vendor</label><br>
                    <input type="text" id="itemvendor" name="vendor" class="required"/>
                </div>
                <div class="dialogSelect m10">
                    <label style="margin-bottom: -13px; display: block;">Status *</label><br>
                    <select id="itemstatus" class="validate[required]" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
