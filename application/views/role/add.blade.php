@section('content')

@include('partial.notification')
<br>

{{ Form::open('role/add', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Role Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'name', @$role['name'], 'Name *') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), isset($role['status']) ? $role['status'] : 1, 'Status *') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', @$role['description'], 'Description') }}

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