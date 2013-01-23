@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Vehicle List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Customer<span class="sorting" style="display: block;"></span></th>
                <th>Number</th>
                <th>Type</th>
				<th>Color</th>
				<th>Model</th>
				<th>Brand</th>
				<th>Description</th>
				<th>Status</th>
				<th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($vehicles as $vehicle)
            <tr class="">
				<td>{{ $vehicle->owner }}&nbsp;</td>
                <td>{{ $vehicle->number }}&nbsp;</td>
                <td>{{ $vehicle->type }}&nbsp;</td>
                <td>{{ $vehicle->color }}&nbsp;</td>
                <td>{{ $vehicle->model }}&nbsp;</td>
                <td>{{ $vehicle->brand }}&nbsp;</td>
                <td>{{ $vehicle->description }}&nbsp;</td>
                <td class="tableActs" align="center">
                    @if($vehicle->status)
                    	<a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("vehicle/edit/$vehicle->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    <a href='{{ url("vehicle/delete/$vehicle->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Remove"
						dialog-confirm-title="Remove Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
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
        <div class="wButton"><a href='{{ url("vehicle/add") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Vehicle</span>
        </a></div>
    </div>
</div>

@endsection


