@section('content')

@include('partial.notification')
<!-- Rounded buttons -->
<ul class="middleNavA">
    @foreach($item_category as $c)
    <li><a href='{{ url("item/list_history?category=$c->id") }}' title="{{$c->name}}" style="width: 100px;height: 65px;"><img src='{{ asset("images/icons/color/config.png") }}' alt="" /><span style="@if($category->name == $c->name) color:red @endif">{{$c->name}}</span></a></li>
    @endforeach
</ul>
<div class="divider"><span></span></div>

<!-- Table history price -->
<div class="widget">
    <div class="whead">
        <h6>List History Additional Stock Item {{$category->name}}</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableStockHistory">
            <thead>
            <tr>
                <th>Item Name<span class="sorting" style="display: block;"></span></th>
                <th>Account Trx Id</th>
                <th>Add Quantity</th>
                <th>Created at</th>
                <th>configured by</th>
            </tr>
            </thead>
            <tbody>

            @foreach($listItemStokFlow as $its)

            <tr class="">
                <td>{{ $its->item->name }}&nbsp;</td>
                <td>{{ $its->sub_account_trx->id }}&nbsp;</td>
                <td>{{ $its->quantity }}&nbsp;</td>
                <td>{{ $its->date }}&nbsp;</td>
                <td>{{ $its->user->name }}&nbsp;</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>


<!-- Table history price -->
<div class="widget">
    <div class="whead">
        <h6>List History Price Item {{$category->name}}</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn1" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTablePriceHistory">
            <thead>
            <tr>
                <th>Item Name<span class="sorting" style="display: block;"></span></th>
                <th>Sale Price</th>
                <th>Purchase Price</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Expired at</th>
                <th>configured by</th>
            </tr>
            </thead>
            <tbody>

            @foreach($listItemPrice as $price)
            <tr class="">
                <td>{{ $price->item->name }}</td>
                <td>{{ $price->price }}</td>
                <td>{{ $price->purchase_price }}</td>
                <td>{{ ($price->status == '1' ? 'Active' : 'Expired') }}</td>
                <td>{{ $price->date }}</td>
                <td>{{ $price->expiry_date }}</td>
                <td>{{ $price->users->name }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

@endsection
