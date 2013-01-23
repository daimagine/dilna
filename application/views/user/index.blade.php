@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>User List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Staff ID<span class="sorting" style="display: block;"></span></th>
				<th>Name</th>
				<th>Login ID</th>
				<th>Phone Number</th>
				<th>Role</th>
                <th style="display: none;">User Status</th>
                <th>Status</th>
				<th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr class="">
                <td>{{ $user->staff_id }}&nbsp;</td>
				<td class="name">{{ $user->name }}&nbsp;</td>
				<td>{{ $user->login_id }}&nbsp;</td>
				<td>{{ $user->phone1 }}&nbsp;</td>
				<td>{{ $user->role->name }}&nbsp;</td>
                <td style="display: none;">{{$user->status == 1 ? 'Active' : 'Inactive'}}</td>
                <td class="tableActs" align="center">
                    @if($user->status)
                        <a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @else
                        <a href="#" class="fs1 iconb tipS" original-title="Inactive" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("user/edit/$user->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    @if($user->status)
                        <a href="#"
                           class="linkUpdatePswd tablectrl_small bDefault tipS"
                           ref-num="{{ $user->id }}">
                            <span class="iconb" data-icon=""></span>
                        </a>
                        @if($user->id != Config::get('default.role.admin'))
                            <a href='{{ url("user/delete/$user->id". ($user->status == 1 ? "" : "/purge") ) }}'
                               class="appconfirm tablectrl_small bDefault tipS"
                               original-title="Remove"
                               dialog-confirm-title="Remove Confirmation"
                               dialog-confirm-content="{{ $user->status == 1 ? 'This action will make this user to be inactive. Are you sure?' : 'This user is inactive. Removing will purge all data and information linked with this user. Are you sure?' }}">
                                <span class="iconb" data-icon=""></span>
                            </a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<div class="fluid">
    <div class="grid2">
        <div class="wButton"><a href="/user/add" title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add User</span>
        </a></div>
    </div>
</div>

<!-- Dialog content -->
<div id="update-password-dialog" class="dialog" title="Update Password Form" style="display: none;">
    <form id="formUpdatePassword" name="formUpdatePassword" method="POST" action="/user/update_password">
        <div class="messageTo">
            <span> Change Password for Login Id <strong><span id="user-name"></span></strong></span>
        </div>
        <div class="divider"><span></span></div>
        <div class="dialogSelect m10" id="update-pswd-notification"></div>
        <div class="dialogSelect m10">
            <label>Password *</label>
            <input type="password" id="password" name="password"/>
        </div>
        <div class="dialogSelect m10">
            <label>Retype Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation"/>
        </div>
        <input type="hidden" id="userId" name="id"/>
    </form>
</div>

<!-- Dialog content select items-->
<div id="submit-confirm" class="dialog" title="Submit Confirmation" style="display: none;">
</div>

@endsection

onclick="return App.confirm(this);" 