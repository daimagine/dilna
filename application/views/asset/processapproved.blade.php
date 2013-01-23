
@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
{{ Form::open('/asset/approved_action', 'POST',  array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}
{{ Form::hidden('action', '-', array('id' => 'action')) }}

<fieldset>
    <div class="widget fluid">
        <div class="whead"><h6>Detail From Invoice</h6><div class="clear"></div></div>
        {{ Form::nginput('text', '', $subAccountTrx->account_transaction->invoice_no, 'Invoice', array('readonly' => 'readonly')) }}

        {{ Form::nginput('text', '', $subAccountTrx->account_transaction->reference_no, 'Reference No', array('readonly' => 'readonly')) }}

        {{ Form::nginput('text', '', $subAccountTrx->account_transaction->user->name, 'Create By', array('readonly' => 'readonly')) }}

        {{ Form::nginput('text', '', $subAccountTrx->item, 'Name on invoice', array('readonly' => 'readonly')) }}

        {{ Form::nginput('text', 'quantity', $subAccountTrx->quantity, 'Quantity at invoice', array('readonly' => 'readonly')) }}

        {{ Form::nginput('text', 'purchase_price', $subAccountTrx->unit_price, 'Unit Price', array('readonly' => 'readonly')) }}

        {{ Form::nginput('text', '', $subAccountTrx->description, 'Description', array('readonly' => 'readonly')) }}
    </div>

    <div class="widget fluid">
        <div class="whead">
            <h6>Stock Opname Asset</h6>
            <div class="clear"></div>
        </div>

        {{ Form::nyelect('asset_type_id', @$assetTypes, '', 'Asset Type *', array('class' => 'validate[required]')) }}

        {{ Form::nginput('text', 'name', $subAccountTrx->item, 'Name *', array('class' => 'validate[required,minSize[5]]', 'id' => 'assetName')) }}

        {{ Form::nginput('text', 'description', @$asset['description'], 'Description', array('class' => 'validate[required]', 'id' => 'assetDesc')) }}

        {{ Form::nginput('text', 'vendor', @$asset['vendor'], 'Vendor', array('class' => 'validate[required,minSize[6]]', 'id' => 'assetVendor')) }}

        <div class="formRow" style="padding-top: 5px;" id="asset-body">
            <table cellpadding="0" cellspacing="0" width="100%" class="tDark" id="asset-table">
                <thead>
                <tr>
                    <td>No</td>
<!--                    <td>Name</td>-->
                    <td>Serial No</td>
                    <td>Condition</td>
                    <td>Location</td>
                    <td>Comments</td>
                </tr>
                </thead>
                <tbody id="asset-tbody">
                <?php $no=0; ?>
                @while($no < $subAccountTrx->quantity)
                <tr id="i-rows-{{$no}}">
                    <td style="padding: 4px 4px">{{$no+1}}</td>
<!--                    <td style="padding: 4px 4px"><div class="grid2"><input class="cssOnBox reqField" name="assets[{{$no}}][name]" value="{{$subAccountTrx->item}}"></td>-->
                    <td style="padding: 4px 4px"><div class="grid2"><input class="cssOnBox" name="assets[{{$no}}][code]" id="assets[{{$no}}][code]" value=""></div></td>
                    <td style="padding: 4px 4px">
                        <div class="grid2">
                            <select name="assets[{{$no}}][condition]" >
                                <option value="{{AssetCondition::GOOD}}">Good</option>
                                <option value="{{AssetCondition::FAIR}}">Fair</option>
                                <option value="{{AssetCondition::BAD}}">Bad</option>
                            </select>
                        </div>
                    </td>
                    <td style="padding: 4px 4px"><div class="grid2"><input class="cssOnBox reqField validate[required]" name="assets[{{$no}}][location]" id="assets[{{$no}}][location] value=""></div></td>
                    <td style="padding: 4px 4px"><div class="grid2"><input class="cssOnBox" name="assets[{{$no}}][comments]" value=""></div></div></td>
                <?php $no++; ?>
                </tr>
                @endwhile
                </tbody>
            </table>
            <input type="hidden" id="item-rows" value="0"/>
            <div id="item-input-wrapper" style="display: none;"></div>
        </div>

<!--        <div class="formRow">-->
<!--            <div class="grid3"><label>Remarks message :<span class="req">*</span></label></div>-->
<!--            <div class="grid9"><textarea rows="8" cols="" name="remarks" class="validate[required]" id="remarks"></textarea></div><div class="clear"></div>-->
<!--        </div>-->

        <div style="padding: 19px 16px">
            <div class="formSubmit">
<!--                {{ HTML::link('/adfafd', 'Reject', array( 'class' => 'closeApproved buttonL bRed mb10 mt5', 'id' => 'buttonCloseApproved' )) }}-->
                {{ Form::submit('Complete', array( 'class' => 'buttonL bGreen mb10 mt5', 'id' => 'buttonConfirmApproved')) }}
                <div class="clear"></div>
            </div>
            <div class="btn-group dropup" style="display: inline-block; margin-bottom: 0px;">
                {{ HTML::link('item/list_approved', 'Back', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
            </div>

            <div id="formDialogApproved" title="Confirmation Closed Approved Invoice">
            </div>

            <div id="asset-dialog-notif" title="Notification">
            </div>

        </div>
    </div>
</fieldset>

    <style type="text/css">
        .cssOnBox { box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; font-size: 11px; color: #858585; box-shadow: 0 1px 0 #fff; -webkit-box-shadow: 0 1px 0 #fff; -moz-box-shadow: 0 1px 0 #fff; padding: 6px 7px; border: 1px solid #d7d7d7; display: inline-block; background: #fdfdfd; height: 26px;}

    </style>

{{ Form::close() }}
@endsection
