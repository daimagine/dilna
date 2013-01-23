@section('content')


@include('partial.notification')
<br>

{{ Form::open('member/edit', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Member Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::hidden('id', $member->id) }}

        {{ Form::nginput('text', 'number', $member->number, 'Number') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $member->status, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        <!--div class="formRow">
            <div class="grid3"><label>Register Date</label></div>
            <div class="grid9">
                <ul class="timeRange">
                    <li><input name="register_date" type="text" class="datepicker" value="{{ $register_date }}" /></li>
                    <li class="sep">-</li>
                    <li><input name="register_time" type="text" class="timepicker" size="10" value="{{ $register_time }}" />
                        <span class="ui-datepicker-append">(hh:mm:ss)</span>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
        </div-->
		
        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('member/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection
