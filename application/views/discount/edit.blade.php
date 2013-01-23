@section('content')


@include('partial.notification')
<br>

{{ Form::open('discount/edit', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Discount Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::hidden('id', $discount->id) }}

        {{ Form::nginput('text', 'code', $discount->code, 'Code', array( 'readonly' => 'readonly' )) }}

		<div class="formRow">
			<div class="grid3"><label>Value</label></div>
			<div class="grid9"><input name="value" type="text" id="discountValue" value="{{ $discount->value }}" /></div><div class="clear"></div>
		</div>

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $discount->status, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', $discount->description, 'Description') }}

        {{ Form::nginput('text', 'registration_fee', $discount->registration_fee, 'Registration Fee *') }}

		<div class="formRow">
			<div class="grid3"><label>Duration</label></div>
			<div class="grid9">
				<div class="grid5">
					<input name="duration" type="text" id="discountDuration" value="{{ $discount->duration }}" />
				</div>
				<div class="grid7">
                    {{ Form::hidden('duration_period', 'M') }}
					{{-- Form::select('duration_period', array('M' => 'Month', 'Y' => 'Year'), $discount->duration_period) --}}
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('discount/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection
