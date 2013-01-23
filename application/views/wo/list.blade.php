@section('content')

@include('partial.notification')
<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>List Work Order</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th class="sorting_desc">Service Time</th>
                <th>WO Id</th>
                <th>Customer Name</th>
                <th>Vehicle Name</th>
                <th>Vehicle No</th>
                <th>WO Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $trx)
            <tr class="">
                <td>{{ $trx->date }}&nbsp;</td>
                <td class="name">{{ $trx->workorder_no }}&nbsp;</td>
                <!--                <td>{{ $trx->vehicle->customer->name }}</td>-->
                <td>{{ $trx->customer_name }}&nbsp;</td>
                <td>{{ $trx->vehicle->model }}&nbsp;</td>
                <td>{{ $trx->vehicle->number }}&nbsp;</td>
                <td>{{ ($trx->status == 'O' ? 'Open' : ($trx->status == 'D' ? 'Closed' : 'Canceled')) }}</td>
                <td class="tableActs" align="center">
                    <a href='{{ url("work_order/detail/$trx->id?type=D") }}' class=" tablectrl_small bBlue tipS" original-title="Detail" style="margin-top: 5px;"><span class="iconb" data-icon=""></span></a>
                    @if($trx->status == 'O')
                    <a href='{{ url("work_order/edit/$trx->id") }}'
                       class="appconfirm tablectrl_small bRed tipS"
                       original-title="Update"
                       dialog-confirm-title="Edit WO Confirmation"
                       dialog-confirm-content="You want edit this WO ?">
                        <span class="iconb" data-icon=""></span>
                    </a>
                    <a href='{{ url("work_order/to_invoice/$trx->id?type=C") }}' class="tablectrl_small bGreen tipS" original-title="Close" style="margin-top: 5px;"><span class="iconb"  data-icon=""></span></a>
                    <a href='{{ url("work_order/do_canceled/$trx->id") }}'
                       class="appconfirm tablectrl_small bGreyish tipS"
                       style="margin-top: 5px;"
                       original-title="Cancel"
                       dialog-confirm-title="Canceled WO Confirmation"
                       dialog-confirm-content="Are you sure want to cancel this work order, this action can't be undone ?">
                        <span class="iconb"  data-icon=""></span>
                    </a>

                    @endif
                    @if($trx->status == 'D' or $trx->status == 'O')
                    <a href='{{ url("work_order/to_invoice/$trx->id") }}' class="tablectrl_small bGold tipS" original-title="Invoice"><span class="iconb"  data-icon="" style="margin-top: 5px;" ></span></a>
                    @endif

                    <a href='{{ url("work_order/print_wo/$trx->id") }}'
                       class="tablectrl_small bDefault tipS"  original-title="Print WO" target="_blank" style="margin-top: 5px;">
                        <span class="iconb"  data-icon=""></span>
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
        <div class="wButton">
            <a href='{{ url("work_order/add") }}' title="" class="buttonL bLightBlue first">
                <span class="icol-add"></span>
                <span>Add WO</span>
            </a>
        </div>
    </div>
</div>

<div id="confirmDelete" class="dialog" title="Confirmation Delete" ></div>

</div>

@endsection