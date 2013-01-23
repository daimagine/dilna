@section('content')

@include('partial.notification')

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Asset Update</h6>

            <div class="clear"></div>
        </div>

            <!-- Table with opened toolbar -->
            {{ Form::open('/asset/edit', 'POST',  array('id' => 'formEBengkel', 'name' => 'formEBengkel')) }}

            {{ Form::hidden('id', $asset->id) }}

            {{ Form::nginput('text', 'name', $asset->name, 'Name *', array('class' => 'reqField validate[required]', 'id' => 'assetName')) }}

            {{ Form::nginput('text', 'code', $asset->code, 'Serial Number *', array('class' => 'reqField validate[required]', 'id' => 'assetCode')) }}

            {{ Form::nyelect('asset_type_id', @$assetTypes, $asset->type, 'Asset Type *', array('class' => 'reqField validate[required]')) }}

            {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $asset->status, 'Status *', array('class' => 'reqField validate[required]', 'id' => 'status')) --}}
            {{ Form::hidden('status', 1) }}

            {{ Form::nyelect('condition', array(AssetCondition::GOOD => 'Good', AssetCondition::FAIR => 'Fair', AssetCondition::BAD=> 'Bad'), $asset->condition, 'Condition *') }}

            {{ Form::nginput('text', 'location', $asset->location, 'Location', array('class' => 'reqField validate[required]', 'id' => 'assetLocation')) }}

            {{ Form::nginput('text', 'purchase_price', $asset->purchase_price, 'Unit Price', array('class' => 'reqField validate[required,custom[number]]', 'id' => 'assetPrice')) }}

            {{ Form::nginput('text', 'description', $asset->description, 'Description', array('class' => 'reqField validate[required]', 'id' => 'assetDesc')) }}

            {{ Form::nginput('text', 'comments', $asset->comments, 'Comments', array('class' => '', 'id' => 'assetComment')) }}


        <div class="formRow noBorderB">
                <div class="status" id="status3"></div>
                <div class="formSubmit">
                    {{ HTML::link('asset/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                    {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5', 'id' => 'buttonUpdate' )) }}
                </div>
                <div class="clear"></div>
        </div>

        <div id="formDialogApproved" title="Confirmation Closed Approved Invoice">
        </div>

    </div>

</fieldset>
{{ Form::close() }}
@endsection