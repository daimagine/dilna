@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Access List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Name<span class="sorting" style="display: block;"></span></th>
                <th>Action</th>
                <th>Description</th>
                <th>Type</th>
                <th>Parent</th>
                <th>Attribute</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($access as $access)
            <tr class="">
                <td>{{ $access->name }}&nbsp;</td>
                <td>{{ $access->action }}&nbsp;</td>
                <td>{{ $access->description }}&nbsp;</td>
                <td>{{ HTML::access_type($access->type) }}&nbsp;</td>
                <td>{{ $access->parentName() }}&nbsp;</td>
                <td class="tableActs" align="center">
                    @if($access->visible)
                    <a href="#" class="fs1 iconb tipS" original-title="Visible" data-icon=""></a>
                    @endif
                    @if($access->status)
                    <a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @endif
                    @if($access->parent)
                    <a href="#" class="fs1 iconb tipS" original-title="Parent Nav" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("access/edit/$access->id") }}'
						class="appconfirm tablectrl_small bDefault tipS"
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    <a href='{{ url("access/delete/$access->id") }}'
						class="appconfirm tablectrl_small bDefault tipS"
						original-title="Remove"
						dialog-confirm-title="Remove Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    &nbsp;
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
        <div class="wButton"><a href="add" title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Access</span>
        </a></div>
    </div>
</div>

@endsection
