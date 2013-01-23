<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>Work Order Invoice</title>

    <link rel='stylesheet' type='text/css' href='{{ asset("css/print/style.css") }}' />
    <link rel='stylesheet' type='text/css' href='{{ asset("css/print/print.css") }}' media="print" />
    <script type='text/javascript' src='{{ asset("js/wo/print/jquery-1.3.2.min.js") }}'></script>
    <script type='text/javascript' src='{{ asset("js/wo/print/example.js") }}'></script>
</head>

<body onload="javascript:window.print()">
<!--<body>-->

<div style="margin-top: 10px;text-align: center;">
</div>
<div id="page-wrap">
    <div id="identity">

        <div>
            <div style="width: 300px; float: left; margin-top: 5px;">
                <p>SpringWizard eBengkel</p>
                <p style="font-size: 0.85em;">Jakarta 16910</p>
                <p style="font-size: 0.85em;">Telp. 08567846560</p>
            </div>

            <div style="float: right;vertical-align: top;text-align: right;margin: 0px 0px 30px 0px;">
                <div id="">
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
    <div id="customer">
        <!--            <textarea id="customer-title">To : {{$transaction->vehicle->customer->name}}</textarea>-->
        <textarea id="customer-title"></textarea>

        <table id="meta">
            <tr>
                <td class="meta-head">Date</td>
                <td style="text-align: right">:</td>
                <td id="currentTime"></td>
            </tr>
            <tr>
                <td class="meta-head">WO No</td>
                <td style="text-align: right">:</td>
                <td><textarea>{{$transaction->workorder_no}}</textarea></td>
            </tr>
            <tr>
                <td class="meta-head">Vehicle No</td>
                <td style="text-align: right">:</td>
                <td><div class="due">{{$transaction->vehicle->number}}</div></td>
            </tr>

        </table>

    </div>

    <div class="table-name">
        <h5>SERVICE LIST</h5>
    </div>
    <table id="service" style="margin-top: 0px">
        <tr>
            <td style="text-align: center">No.</td>
            <td style="text-align: center">Description</td>
<!--            <td style="text-align: center">Unit</td>-->
<!--            <td style="text-align: center">Quantity</td>-->
            <td style="text-align: center">Service Price</td>
            <td style="text-align: center">Sub Total</td>
        </tr>

        <?php $i=1; ?>
        @foreach($transaction->transaction_service as  $trx_service)
        <tr class="service-row">
            <td class="other" style="width: 63px" align="center">{{$i}}</td>
            <td class="description other">{{ $trx_service->service_formula->service->name }}</td>
            <!--            <td class="service-name" align="center">-</td>-->
            <!--            <td class="qty" align="center">1</td>-->
            <td class="cost other" style="min-width:120px;text-align: right;">Rp. {{ number_format($trx_service->service_formula->price, 0,",",".") }},-</td>
            <td class="price other" style="min-width:150px;text-align: right;">Rp. {{ number_format(($trx_service->service_formula->price), 0,",",".")}},-</span></td>
        </tr>
        <?php $i++; ?>
        @endforeach
        <?php $j=0; ?>
        @while($j<3)
        <tr class="service-row">
            <td class="other" style="width: 63px" align="center">{{$i}}</td>
            <td class="description other">..............................................................</td>
            <td class="cost other" style="min-width:120px;text-align: right;">................................</td>
            <td class="price other" style="min-width:150px;text-align: right;">................................</td>
        <tr class="service-row">
        <?php $j++; $i++; ?>
        @endwhile
    </table>


    <div class="clear"></div>

    <div class="table-name">
        <h5>ITEM LIST</h5>
    </div>
    <table id="items" style="margin-top: 0px">
        <tr>
            <td style="text-align: center">No</td>
            <td style="text-align: center">Description</td>
            <td style="text-align: center">Unit Name</td>
            <td style="text-align: center">Quantity</td>
            <td style="text-align: center">Unit Price</td>
            <td style="text-align: center">Sub Total</td>
        </tr>


        <?php $i=1; ?>
        @foreach($transaction->transaction_item as  $trx_item)
        <?php $total=number_format((float)(($trx_item->item_price->price) * ($trx_item->quantity)), 2, '.', ''); ?>
        <tr class="item-row">
            <td class="other" style="width: 63px" align="center">{{$i}}</td>
            <td class="description other">{{ $trx_item->item_price->item->name}}</td>
            <td class="item-name other" align="center">{{ $trx_item->item_price->item->item_unit->name}}</td>
            <td class="qty other" align="center">{{ $trx_item->quantity}}</td>
            <td class="cost other" style="text-align: right;">Rp. {{ number_format($trx_item->item_price->price, 0,",",".") }},-</td>
            <td style="text-align: right;" class="other"><span style="">Rp.</span><span class="price"> {{ number_format($total, 0,",",".") }},-</span></td>
        </tr>
        <?php $i++; ?>
        @endforeach
        <?php $j=0; ?>
        @while($j<3)
        <tr class="service-row">
            <td class="other" style="width: 63px" align="center">{{$i}}</td>
            <td class="description other">..............................................................</td>
            <td class="item-name other" align="center">...............</td>
            <td class="qty other" align="center">...............</td>
            <td class="cost other" style="text-align: right;">................................</td>
            <td style="text-align: right;" class="other">................................</span></td>
        <tr class="service-row">
        <?php $j++; $i++; ?>
        @endwhile

        <tr>
        <td colspan="6 "><div class="clear"></div> </td>
        </tr>
    </table>


    <div class="clear"></div>

    <div class="table-name">
        <h5>MECHANIC LIST</h5>
    </div>
    <table id="mechanic" style="margin-top: 0px">
        <tr>
            <td style="text-align: center">No</td>
            <td style="text-align: center">Saff Id</td>
            <td style="text-align: center">Mechanic Name</td>
        </tr>

        <?php $i=1; ?>
        @foreach($transaction->user_workorder as  $mechanic)
        <tr class="item-row">
            <td class="other" style="width: 63px" align="center">{{$i}}</td>
            <td class="staff-id other" align="center">{{ $mechanic->user->staff_id}}</td>
            <td class="mechanic-name other">{{ $mechanic->user->name}}</td>
        </tr>
        <?php $i++; ?>
        @endforeach
        <?php $j=0; ?>
        @while($j<3)
        <tr class="service-row">
            <td class="other" style="width: 63px" align="center">{{$i}}</td>
            <td class="staff-id other" align="center">...............</td>
            <td class="item-name other" align="center">...............</td>
        <tr class="service-row">
            <?php $j++; $i++; ?>
            @endwhile

        <tr>
            <td colspan="6 "><div class="clear"></div> </td>
        </tr>
        <!--        <tr>-->
        <!--            <td colspan="2" class="blank" > </td>-->
        <!--            <td colspan="3" class="total-line" >Subtotal</td>-->
        <!--            <td class="total-value" style="text-align: right; "><div id="subtotal">Rp.  {{ number_format($transaction->amount, 0,",",".") }},-</div></td>-->
        <!--        </tr>-->
        <!--        <tr>-->
        <!--            <td colspan="2" class="blank"> </td>-->
        <!--            <td colspan="3" class="total-line">Pph 0%</td>-->
        <!--            <td class="total-value" style="text-align: right"><div id="pph">Rp. 0.00</div></td>-->
        <!--        </tr>-->
        <!--        <tr>-->
        <!--            <td colspan="2" class="blank"> </td>-->
        <!--            <td colspan="3" class="total-line">-->
        <!--                Discount Service-->
        <!--                @if(isset($transaction->vehicle->membership->discount))-->
        <!--                {{$transaction->vehicle->membership->discount->value}} %-->
        <!--                @endif-->
        <!--            </td>-->
        <!--            <td class="total-value"  style="text-align: right"><div id="discount">Rp. {{ number_format($transaction->discount_amount, 0,",",".") }},-</div></td>-->
        <!--        </tr>-->
        <!--        <tr>-->
        <!--            <td colspan="2" class="blank"> </td>-->
        <!--            <td colspan="3" class="total-line">Total</td>-->
        <!--            <td class="total-value"  style="text-align: right"><div id="total">Rp.  {{ number_format($transaction->paid_amount, 0,",",".") }},-</div></td>-->
        <!--        </tr>-->
        <!--        <tr>-->
        <!--            <td colspan="2" class="blank"> </td>-->
        <!--            <td colspan="3" class="total-line">Amount Paid</td>-->
        <!---->
        <!--            <td class="total-value" style="text-align: right">Rp.  {{ number_format($transaction->paid_amount, 0,",",".") }},-</td>-->
        <!--        </tr>-->
    </table>


    <div style="padding: 50px">
        <table id="signed">
            <tr>
                <td align="center"><h5 style="border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; width: 180px;"></h5></td>
                <td align="center"><h5 style="border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; width: 180px;"></h5></td>
            </tr>
            <tr>
                <td align="center"> {{$transaction->customer_name}} <br>(customer)</td>
                <td align="center">Fahmi Buy, <br> (Kepala Mekanik)</td>
            </tr>
        </table>
    </div>
</div>
</body>

</html>