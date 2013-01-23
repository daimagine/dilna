@section('content')

@include('partial.notification')
<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>List Service</h6>
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
                <th>Service Status</th>
                <th>Price</th>
                <th>Last update</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lstService as $service)
            <tr class="">
                <td>{{ $service->id }}&nbsp;</td>
                <td class="name">{{ $service->name }}&nbsp;</td>
                <td>{{ $service->description }}&nbsp;</td>
                <td>{{ $service->status ==1 ? 'Active' : 'Inactive' }}</td>
                <td>{{ $service->service_formula()->price }}&nbsp;</td>
                <td>{{ $service->service_formula()->created_at }}&nbsp;</td>
                <td class="tableActs" align="center">
                    <a href='{{ url("service/edit/$service->id") }}' class="tablectrl_small bDefault tipS" original-title="Edit"><span class="iconb" data-icon=""></span></a>
                    <a href='{{ url("service/delete/$service->id") }}' class="classConfirmDelete tablectrl_small bDefault tipS" original-title="Inactive">
                        <span class="iconb" data-icon=""></span>
                        <!-- Dialog modal confirmation delete item-->
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
        <div class="wButton"><a href='{{ url("service/add") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add New</span>
        </a></div>
    </div>
</div>

<div id="confirmDelete" class="dialog" title="Confirmation Delete" ></div>

</div>

@endsection
