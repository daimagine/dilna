@section('content')

@include('partial.notification')
<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>List Asset Type</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>No<span class="sorting" style="display: block;"></span></th>
                <th>Name</th>
                <th>Description</th>
                <th>Asset Type Status</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no=0; ?>
            @foreach($lstAssetType as $type)
            <tr class="">
                <td>{{ $no+1 }}&nbsp;</td>
                <td class="name">{{ $type->name }}&nbsp;</td>
                <td>{{ $type->description }}&nbsp;</td>
                <td>{{ $type->status ==1 ? 'Active' : 'Inactive' }}&nbsp;</td>
                <td>{{ $type->created_at }}&nbsp;</td>
                <td class="tableActs" align="center">
                    <a href='{{ url("asset/edit_asset_type/$type->id") }}' class="tablectrl_small bDefault tipS" original-title="Edit"><span class="iconb" data-icon=""></span></a>
                    @if($type->status == statusType::ACTIVE)
                    <a href='{{ url("asset/delete_asset_type/$type->id") }}'
                       class="appconfirm tablectrl_small bDefault tipS"
                       original-title="Remove"
                       dialog-confirm-title="Remove Confirmation">
                        <span class="iconb" data-icon=""></span>
                        <!-- Dialog modal confirmation delete item-->
                    </a>
                    @endif
                    &nbsp;
                </td>
                <?php $no++; ?>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<div class="fluid">
    <div class="grid2">
        <div class="wButton"><a href='{{ url("asset/add_asset_type") }}'  title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add New</span>
        </a></div>
    </div>
</div>

<div id="confirmDelete" class="dialog" title="Confirmation Delete" ></div>

</div>

@endsection
