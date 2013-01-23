@section('content')

@include('partial.notification')
<br>

<!--{{ Form::open('user/edit', 'POST') }}-->
{{ Form::open_for_files('user/edit', 'POST') }}
<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>User Edit</h6>

            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'staff_id', $user->staff_id, 'Staff ID', array( 'readonly' => 'readonly' ) ) }}

        {{ Form::hidden('id', $user->id) }}
		
		{{ Form::hidden('staff_id', $user->staff_id) }}

        {{ Form::nginput('text', 'login_id', $user->login_id, 'Login ID *') }}

        {{ Form::nyelect('role_id', @$roles, $user->role_id, 'Role *') }}

        {{ Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $user->status, 'Status *') }}

        {{ Form::nginput('text', 'name', $user->name, 'Name *') }}

        {{ Form::nginput('text', 'address1', $user->address1, 'Address 1')}}

        {{ Form::nginput('text', 'address2', $user->address2, 'Address 2')}}

        {{ Form::nginput('text', 'city', $user->city, 'City')}}

        {{ Form::nginput('text', 'phone1', $user->phone1, 'Phone 1 *')}}

        {{ Form::nginput('text', 'phone2', $user->phone2, 'Phone 2')}}

        <div class="formRow">
            <div class="grid3">
                <label>Photo</label>
            </div>
            <div class="grid5">
                <div>
                    @if($user->picture != null && $user->picture !== '')
                        <img src='{{ asset("images/uploads/user/$user->picture") }}' alt="" width="72" height="70">
                    @else
                        <img src='{{ asset("images/userLogin.png") }}' alt="" width="72" height="70">
                    @endif
                </div>
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