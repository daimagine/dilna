@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Role List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Name<span class="sorting" style="display: block;"></span></th>
                <th>Description</th>
                <th>Attribute</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
            <tr class="">
                <td>{{ $role->name }}&nbsp;</td>
                <td>{{ $role->description }}&nbsp;</td>
                <td class="tableActs" align="center">
                    @if($role->visible)
                    <a href="#" class="fs1 iconb tipS" original-title="Visible" data-icon=""></a>
                    @endif
                    @if($role->status)
                    <a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("role/detail/$role->id") }}'
						class="tablectrl_small bDefault tipS">
							<span class="iconb" data-icon=""></span>
					</a>
					<a href='{{ url("role/edit/$role->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    @if($role->id != Config::get('default.role.admin'))
                        <a href='{{ url("role/delete/$role->id") }}'
                            class="appconfirm tablectrl_small bDefault tipS"
                            original-title="Remove"
                            dialog-confirm-title="Remove Confirmation">
                                <span class="iconb" data-icon=""></span>
                        </a>
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
        <div class="wButton"><a href='{{ url("role/add") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Role</span>
        </a></div>
    </div>
</div>

@endsection


