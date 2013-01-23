@section('content')

@include('partial.notification')
<br>

{{ Form::open('role/edit', 'POST') }}

<fieldset>
    <div class="widget fluid">
        <div class="whead">
            <h6>Role Edit</h6>

            <div class="clear"></div>
        </div>


        {{ Form::hidden('id', $role->id) }}

        {{ Form::nginput('text', 'name', $role->name, 'Name *') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $role->status, 'Status *') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', $role->description, 'Description') }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('role/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>
</fieldset>
{{ Form::close() }}

@endsection