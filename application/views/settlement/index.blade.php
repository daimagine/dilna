@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Settlement List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable" dtable-sortlist="[[0,'desc']]">
            <thead>
            <tr>
                <th width="20">Actions</th>
                <th width="90">Date<span class="sorting" style="display: block;"></span></th>
                <th width="60">State</th>
                <th width="10">Sales Amount</th>
                <th width="100">Clerk Amount</th>
                <th width="60">Clerk</th>
                <th width="100">Settlement Time</th>
            </tr>
            </thead>
            <tbody>
            @foreach($settlements as $settlement)
            <tr class="{{ $settlement->state == SettlementState::UNSETTLED ? 'redBack' : ($settlement->state == SettlementState::SETTLED_UNMATCH ? 'greyBack' : '') }} ">
                <td class="tableActs" align="center">
<!--                    <a href='{{ url("settlement/detail/$settlement->id") }}' class="tablectrl_small bDefault tipS" original-title="Detail">-->
<!--                        <span class="iconb" data-icon=""></span>-->
<!--                    </a>-->
                    <a href='{{ url("settlement/edit/$settlement->id") }}'
                       class="appconfirm tablectrl_small bDefault tipS"
                       original-title="{{ $settlement->state == SettlementState::UNSETTLED ? 'Settle' : 'Detail' }}"
                       dialog-confirm-title="Settlement Confirmation">
                        <span class="iconb" data-icon="{{ $settlement->state == SettlementState::UNSETTLED ? '' : '' }}"></span>
                    </a>
                </td>
                <td>{{ date('d F Y', strtotime($settlement->open_time)) }}&nbsp;</td>
                <td class="textC">{{ $settlement->state_description }}&nbsp;</td>
                <td class="textR">IDR {{ number_format($settlement->sales_amount, 2) }}&nbsp;</td>
                <td class="textR">IDR {{ number_format($settlement->clerk_amount, 2) }}&nbsp;</td>
                <td>{{ isset($settlement->clerk->name) ? $settlement->clerk->name : '-' }}&nbsp;</td>
                <td>{{ $settlement->state == SettlementState::UNSETTLED ? '-' : date('d F Y H:i:s', strtotime($settlement->close_time)) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

@endsection
