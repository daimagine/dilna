@section('content')

@include('partial.notification')
<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>List Item </h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Type<span class="sorting" style="display: block;"></span></th>
                <th>Name</th>
                <th>SN</th>
                <th>Condition</th>
                <th>Location</th>
                <th>Unit Price</th>
                <th>Asset Status</th>
                <th>Vendor</th>
                <th>Add Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($assets as $asset)
            <tr>
                <td class="type">{{ $asset->asset_type->name }}&nbsp;</td>
                <td class="name">{{ $asset->name }}&nbsp;</td>
                <td class="code">{{ $asset->code }}&nbsp;</td>
                <td>{{ ($asset->condition == AssetCondition::GOOD ? 'Good' : ($asset->condition == AssetCondition::FAIR ? 'Fair' : ($asset->condition == AssetCondition::BAD ? 'Bad' : '-')))}}&nbsp;</td>
                <td>{{ $asset->location }}&nbsp;</td>
                <td>{{ $asset->purchase_price }}&nbsp;</td>
                <td>{{ $asset->status ==1 ? 'Active' : 'Inactive' }}&nbsp;</td>
                <td>{{ $asset->vendor }}&nbsp;</td>
                <td>{{ $asset->created_at }}&nbsp;</td>
                <td class="tableActs" align="center">
                    <a href='{{ url("asset/edit/$asset->id") }}' class="appconfirm tablectrl_small bDefault tipS"
                       original-title="Edit"
                       dialog-confirm-title="Update Confirmation">
                        <span class="iconb" data-icon=""></span>
                    </a>
                    <a href='{{ url("asset/delete/$asset->id") }}'
                       class="appconfirm tablectrl_small bDefault tipS"
                       original-title="Remove"
                       dialog-confirm-title="Remove Confirmation">
                        <span class="iconb" data-icon=""></span>
                        <!-- Dialog modal confirmation delete item-->
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

<div id="confirmDelete" class="dialog" title="Confirmation Delete" ></div>

</div>

@endsection