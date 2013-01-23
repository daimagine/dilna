@section('content')

@include('partial.notification')



<div class="widget">
    <div class="whead"><h6>Membership List</h6><div class="clear"></div></div>
    <ul class="updates">
        @foreach($member as $m)
        <li>
            <div class="exp pointer">
                <div class="wNews">
                    <a href="#" title="" class="headline"></a>
                    <div class="announce">
                        <a href="#" title="">{{ $m->name }}</a>
                        <span>{{ $m->address1 . ' ' . $m->address2 }}</span>
                    </div>
                </div>
                <span class="uDate" style="width: 65px"><span>{{ sizeof($m->vehicles) }}</span>vehicle</span>
            </div>
            <div class="clear">
                <span class="clear"></span>
                <ul class="updates">
                    @foreach($m->vehicles as $vh)
                        <li>
                            <span class="{{ $vh->membership != null ? 'uDone' : 'uAlert' }}" style="width: 100%">
                                Vehicle Number &nbsp; <a href="#" title="">{{ $vh->number }}</a>
                                @if($vh->membership != null)
                                    <ul>
                                        <li class="on_off">
                                            <label>
                                                <span class="icos-postcard"></span>Membership Number &nbsp; <a href="#" class="red">{{ $vh->membership->number }}</a>
                                            </label>
                                            <div class="clear"></div>
                                        </li>
                                        @if(strtotime($vh->membership->expiry_date) < strtotime(date('Y-m-d H:i:s')))
                                            <li class="on_off">
                                                <label>
                                                    <span class="icos-block"></span><span class="red">EXPIRED</span>
                                                </label>
                                                <div class="clear"></div>
                                            </li>
                                            <li class="on_off">
                                                <label>
                                                    <span class="icos-cross"></span>
                                                    <a href='{{ url("member/delete/$vh->membership->id") }}'
                                                       class="appconfirm pointer red"
                                                       dialog-confirm-title="Remove Confirmation">Revoke Membership
                                                    </a>
                                                </label>
                                                <div class="clear"></div>
                                            </li>
                                        @endif

                                        <li class="on_off">
                                            <label><span class="icos-cog2"></span>
                                                <a href="#memberDetail" class="pointer" onclick="detailMember('{{ $vh->membership->id }}')">Show Detail</a>
                                            </label>
                                            <div class="clear"></div>
                                        </li>
                                    </ul>

                                @else
                                    <ul>
                                        <li class="on_off">
                                            <label><span class="icos-cog2"></span>
                                                <a class="formDialog_open" href="#assign"
                                                   data-value-vehicle="{{ $vh->id }}"
                                                   data-value-customer="{{ $m->id }}"
                                                   additional-value="{{$vh->number}};{{$vh->customer->name}};{{$vh->created_at}}">Assign Membership</a>
                                            </label>
                                            <div class="clear"></div>
                                        </li>
                                    </ul>
                                @endif
                            </span>
                            <span class="clear"></span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <span class="clear"></span>
        </li>
        @endforeach
    </ul>
</div>

<!-- Dialog content -->
<div id="formDialog" class="dialog" title="Assign New Membership">
	<form action="/member/assign" id="memberAssignForm" method="post">
		<input type="hidden" id="customerId" value="0" name="customerId"/>
		<input type="hidden" id="vehicleId" value="0" name="id"/>
		<div class="messageTo">
			<span> Assign membership to : &nbsp;<span id="customerName"></span> <strong>&nbsp;<span id="customerVehicle"></span></strong></span>
			<a href="#" class="uEmail">registered since : <span id="customerSince"></span></a>
		</div>
		<div class="divider"><span></span></div>
		<div class="dialogSelect m10">
			<label>Select membership</label>
			<select name="discount_id" >
				@foreach($discounts as $id => $desc)
					<option value="{{ $id }}">{{ $desc }}</option>
				@endforeach
			</select>
		</div>
	</form>
</div>

<!-- Detail Membership -->
<div id="detailMember" class="dialog" title="Detail Membership" ></div>

@endsection
