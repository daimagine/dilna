@section('content')

@include('partial.notification')

<!-- Change Password -->
    <div class="fluid">
        @if(!is_null(Session::get('change_password_error')))
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">×</a>
            <h4 class="alert-heading">Oh Snap!</h4>
            @if (is_array(Session::get('change_password_error')))
                <ul>
                    @foreach (Session::get('change_password_error') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @else
                {{ Session::get('change_password_error') }}
            @endif
        </div>
        @endif


        {{ Form::open('preferences/change_password', 'POST', array( 'name' => 'formChangePassword' )) }}

        <div class="widget fluid">
            <div class="whead">
                <h6>Change Password</h6>
                <div class="clear"></div>
            </div>

            {{ Form::nginput('password', 'password',  null, 'Old Password *') }}

            {{ Form::nginput('password', 'new_password',  null, 'New Password *') }}

            {{ Form::nginput('password', 'new_password_confirmation', null, 'Retype New Password *') }}

            <div class="formRow noBorderB">
                <div class="status" id="status3"></div>
                <div class="formSubmit">
                    <input class="appconfirm buttonL bGreen mb10 mt5" type="submit" value="Save"
                           original-title="Change Password"
                           dialog-confirm-title="Update Confirmation"
                           dialog-confirm-callback="document.forms.formChangePassword.submit()">
                </div>
                <div class="clear"></div>
            </div>
        </div>

        {{ Form::close() }}

    </div>

@endsection