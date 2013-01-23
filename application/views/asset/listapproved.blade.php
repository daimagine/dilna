@section('content')

@include('partial.notification')

<div class="widget">
    <div class="whead">
        <h6>List Asset Approved</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Process</th>
                <th>Invoice<span class="sorting" style="display: block;"></span></th>
                <th>Ref No</th>
                <th>Asset name</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Input Date</th>
                <th>Due Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lstSubAte as $ate)
            <tr class="">
                <td class="tableActs" align="center">
                    <a href='{{ url("asset/detail_approved/$ate->id") }} 'class="tablectrl_small bRed tipS" original-title="Process"><span class="iconb" data-icon=""></span></a>
                </td>
                <td>{{ $ate->account_transaction->invoice_no }}&nbsp;</td>
                <td>{{ $ate->account_transaction->reference_no }}&nbsp;</td>
                <td>{{ $ate->item }}&nbsp;</td>
                <td>{{ $ate->qty }}&nbsp;</td>
                <td>{{ $ate->description }}&nbsp;</td>
                <td>{{ $ate->account_transaction->input_date }}&nbsp;</td>
                <td>{{ $ate->account_transaction->due_date }}&nbsp;</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>
@endsection
