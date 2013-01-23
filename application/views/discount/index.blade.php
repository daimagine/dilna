@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Discount List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Code<span class="sorting" style="display: block;"></span></th>
                <th>Value</th>
				<th>Charge</th>
				<th>Duration</th>
                <th>Description</th>
                <th>Attribute</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($discount as $discount)
            <tr class="">
                <td>{{ $discount->code }}&nbsp;</td>
                <td>{{ number_format($discount->value,2) }}%&nbsp;</td>
                <td>IDR {{ $discount->registration_fee }}&nbsp;</td>
				<td>{{ $discount->duration }} {{ $discount->duration_period == 'M' ? 'Month' : ( $discount->duration_period == 'Y' ? 'Year' : '' ) }} &nbsp;</td>
                <td>{{ $discount->description }}&nbsp;</td>
                <td class="tableActs" align="center">
                    @if($discount->status)
                    <a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("discount/edit/$discount->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    <a href='{{ url("discount/delete/$discount->id") }}'
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
        <div class="wButton"><a href='{{ url("discount/add") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Discount</span>
        </a></div>
    </div>
</div>

@endsection
