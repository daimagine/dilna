@section('content')

@include('partial.notification')
<br>

{{ Form::open('vehicle/add', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Vehicle Add</h6>

            <div class="clear"></div>
        </div>

		<div class="formRow">
            <div class="grid3"><label>Customer</label></div>
            <div class="grid9 searchDrop">
                <select data-placeholder="Choose a Customer..." class="select" style="min-width:350px;" id="customer-select" name="customer_id">
                    <option value="0" {{ isset($vehicle['customer_id']) && $vehicle['customer_id'] == '0' ? 'selected="selected"' : '' }}></option>
                    @foreach($customers as $key => $value)
                        <option value="{{ $key }}" {{ isset($vehicle['customer_id']) && $vehicle['customer_id'] == $key ? 'selected="selected"' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'number', @$vehicle['number'], 'Number') }}
		
		{{ Form::nginput('text', 'type', @$vehicle['type'], 'Type') }}
		
		{{ Form::nginput('text', 'color', @$vehicle['color'], 'Color') }}
		
		{{ Form::nginput('text', 'model', @$vehicle['model'], 'Model') }}
		
		{{ Form::nginput('text', 'brand', @$vehicle['brand'], 'Brand') }}

		{{ Form::nginput('text', 'year', @$vehicle['year'], 'Year') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), isset($vehicle['status']) ? $vehicle['status'] : 1, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', @$vehicle['description'], 'Description') }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('vehicle/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection

