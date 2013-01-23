@section('content')

@include('partial.notification')

<div class="widget">
    <div class="whead">
        <h6>List Item Approved</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Action</th>
                <th>Invoice<span class="sorting" style="display: block;"></span></th>
                <th>Ref No</th>
                <th>Item</th>
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
                    <a href='{{ url("item/detail_approved/$ate->id") }}' class="tablectrl_small bRed tipS" original-title="Process"><span class="iconb" data-icon=""></span></a>
<!--                    <a href="detail_approved/{{ $ate->id }}" class="" original-title="Process">Process</span></a>-->
                </td>
                <td>{{ $ate->account_transaction->invoice_no }}</td>
                <td>{{ $ate->account_transaction->reference_no }}</td>
                <td>{{ $ate->item }}</td>
                <td>{{ $ate->quantity }}</td>
                <td>{{ $ate->description }}</td>
                <td>{{ $ate->account_transaction->input_date }}</td>
                <td>{{ $ate->account_transaction->due_date }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>
@endsection
