<script src="/js/wo/application.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        iTable = $('#tablecustomer').dataTable({
            "bJQueryUI": false,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"H"fl>t<"F"ip>'
        });


    });
</script>
<!-- javascript library -->
<link href='{{ asset("css/styles.css") }}' media="all" type="text/css" rel="stylesheet">
    <div class="wrapper">
        <div class="widget" style="margin: 10px 0px 10px 0px;">
            <div class="whead">
                <h6>Customer List</h6>
                <div class="clear"></div>
            </div>
            <div id="dyn2" class="shownpars">
                <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="tablecustomer">
                    <thead>
                    <tr>
                        <th>Name<span class="sorting" style="display: block;"></span></th>
                        <th>Vehicle No</th>
                        <th>Addres</th>
                        <th>Status</th>
                        <th>Membership</th>
                        <th>Action</th>
                        <th style="display: none" ></th>
                        <th style="display: none" ></th>
                        <th style="display: none" ></th>
                        <th style="display: none" ></th>
                        <th style="display: none"></th>
                        <th style="display: none"></th>
                        <th style="display: none"></th>
                        <th style="display: none"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $currentDate=date('Y-m-d H:i:s', time()); ?>
                    @foreach($lstVehicle as $vehicle)
                    <tr>
                        <td class="customerName">{{$vehicle->customer->name}}</td>
                        <td  class="vehicleNo">{{$vehicle->number}}</td>
                        <td>{{$vehicle->customer->address1}}</td>
                        <td>{{$vehicle->status ==1 ? 'Active' : 'Inactive'}}</td>
                        <td class="status tableActs" align="center">
                            @if($vehicle->membership and $vehicle->membership->expiry_date>$currentDate)
                            <a href="#" class="fs1 iconb tipS" original-title="Member" data-icon=""></a>
                            @else
                            <a href="#" class="fs1 iconb tipS" original-title="Non Member" data-icon=""></a>
                            @endif
                        </td>
                        <th style="display: none" class="customerId">{{$vehicle->customer->id}}</th>
                        <th style="display: none" class="id">{{$vehicle->id}}</th>
                        <th style="display: none" class="status">{{($vehicle->membership != null and $vehicle->membership->expiry_date>$currentDate) ? 'member' : 'non-member'}}</th>
                        <th style="display: none" class="type">{{$vehicle->type}}</th>
                        <th style="display: none" class="color">{{$vehicle->color}}</th>
                        <th style="display: none" class="model">{{$vehicle->model}}</th>
                        <th style="display: none" class="brand">{{$vehicle->brand}}</th>
                        <th style="display: none" class="description">{{$vehicle->description}}</th>
                        <td align="center" class="tableActs "><a href="#" class="select fs1 iconb tipS" original-title="Select This" data-icon=""></a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="clear"></div>
        </div>
    </div>