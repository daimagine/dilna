@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Customer List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Name<span class="sorting" style="display: block;"></span></th>
				<th>Addres</th>
				<th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
            <tr class="">
                <td>{{ $customer->name }}&nbsp;</td>
				<td>{{ $customer->address1 . ' ' . $customer->address2 }}&nbsp;</td>
                <td class="tableActs" align="center">
                    @if($customer->status)
                        <a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @else
                        <a href="#" class="fs1 iconb tipS" original-title="Inactive" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("customer/edit/$customer->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    <a href='{{ url("customer/delete/$customer->id". ($customer->status == 1 ? "" : "/purge") ) }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Remove"
						dialog-confirm-title="Remove Confirmation"
                        dialog-confirm-content="{{ $customer->status == 1 ? 'This action will make this customer to be inactive. Are you sure?' : 'This customer is inactive. Removing will purge all data and information linked with this customer. Are you sure?' }}">
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
        <div class="wButton"><a href='{{ url("customer/add") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Customer</span>
        </a></div>
    </div>
</div>

@endsection
