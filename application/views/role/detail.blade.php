@section('content')

@include('partial.notification')

<div class="widget">
	<div class="whead"><h6>Detail Role</h6><div class="clear"></div></div>
	<div class="body">
		<ul>
			<li class="fluid">
				<div class="grid2"><span class="icos-monitor"></span><b>Name</b></div>
                <div class="grid1">:</div>
				<div class="grid9">{{ $role->name }}</div>
				<div class="clear"></div>
			</li>
			<li class="fluid">
				<div class="grid2"><span class="icos-check"></span><b>Status</b></div>
                <div class="grid1">:</div>
				<div class="grid9">{{ $role->status == 1 ? '<span class="green">Active</span>' : '<span class="red">Inactive</span>' }}</div>
				<div class="clear"></div>
			</li>
			<li class="fluid">
				<div class="grid2"><span class="icon-share-3"></span><b>Parent</b></div>
                <div class="grid1">:</div>
				<div class="grid9">{{ $role->parent != null ? $role->parent->name : '' }}</div>
				<div class="clear"></div>
			</li>
			<li class="fluid">
				<div class="grid2"><span class="icon-eye"></span><b>Description</b></div>
                <div class="grid1">:</div>
				<div class="grid9">{{ $role->description }}</div>
				<div class="clear"></div>
			</li>
	</div>
</div>

<div class="fluid">
    <div class="grid2">
        <div class="wButton"><a href='{{ url("role/index") }}' title="" class="buttonL bDefault first">
            <span class="icon-undo"></span>
            <span>Back</span>
        </a></div>
    </div>
</div>

@endsection