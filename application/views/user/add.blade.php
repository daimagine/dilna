@section('content')

@include('partial.notification')
<br>

<!--{{ Form::open('user/add', 'POST') }}-->
{{ Form::open_for_files('user/add', 'POST') }}
<fieldset>
    <div class="widget fluid">
        <div class="whead">
            <h6>User Add</h6>

            <div class="clear"></div>
        </div>
        {{ Form::nginput('text', 'staff_id', $staff_id, 'Staff ID', array( 'readonly' => 'readonly' ) ) }}

        {{ Form::nginput('text', 'login_id', Input::old('login_id'), 'Login ID *') }}

        {{ Form::nginput('password', 'password',  null, 'Password *') }}

        {{ Form::nginput('password', 'password_confirmation', null, 'Retype Password *') }}

        {{ Form::nyelect('role_id', @$roles, Input::old('role_id'), 'Role *') }}

        {{ Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), Input::old('status'), 'Status *') }}

        {{ Form::nginput('text', 'name', Input::old('name'), 'Name *') }}

        {{ Form::nginput('text', 'address1', Input::old('address1'), 'Address 1')}}

        {{ Form::nginput('text', 'address2', Input::old('address2'), 'Address 2')}}

        {{ Form::nginput('text', 'city', Input::old('city'), 'City')}}

        {{ Form::nginput('text', 'phone1', Input::old('phone1'), 'Phone 1 *')}}

        {{ Form::nginput('text', 'phone2', Input::old('phone2'), 'Phone 2')}}

        <div class="formRow">
            <div class="grid3">
                <label>Photo</label>
            </div>
            <div class="grid5">
                {{Form::file('picture', $attributes = array('class' => 'fileInput'))}}
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('user/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>

    </div>
</fieldset>
{{ Form::close() }}

@endsection