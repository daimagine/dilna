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
            <textarea id="customer-title">To : {{$transaction->customer_name}}</textarea>

            <table id="meta">
                <tr>
                    <td class="meta-head">Invoice</td>
                    <td style="text-align: right">:</td>
                    <td><textarea>{{$transaction->invoice_no}}</textarea></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td style="text-align: right">:</td>
                    <td id="currentTime"></td>
                </tr>
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td style="text-align: right">:</td>
                    <td><div class="due">Rp. {{number_format($transaction->paid_amount, 0,",",".")}},-</div></td>
                </tr>

            </table>
		
		</div>
		
		<table id="items">
		
		  <tr>
		      <td style="text-align: center">Description</td>
		      <td style="text-align: center">Unit</td>
              <td style="text-align: center">Quantity</td>
              <td style="text-align: center">Unit Price</td>
		      <td style="text-align: center">Sub Total</td>
		  </tr>

          @foreach($transaction->transaction_service as  $trx_service)
		  <tr class="item-row">
              <td class="description">{{ $trx_service->service_formula->service->name }}</td>
              <td class="item-name" align="center">-</td>
              <td class="qty" align="center">1</td>
              <td class="cost" style="min-width:120px;text-align: right;">Rp. {{ number_format($trx_service->service_formula->price, 0,",",".") }},-</td>
		      <td class="price" style="min-width:150px;text-align: right;">Rp. {{ number_format(($trx_service->service_formula->price), 0,",",".")}},-</span></td>
		  </tr>
          @endforeach

            @foreach($transaction->transaction_item as  $trx_item)
            <?php $total=number_format((float)(($trx_item->item_price->price) * ($trx_item->quantity)), 2, '.', ''); ?>
            <tr class="item-row">
                <td class="description">{{ $trx_item->item_price->item->name}}</td>
                <td class="item-name" align="center">{{ $trx_item->item_price->item->item_unit->name}}</td>
                <td class="qty" align="center">{{ $trx_item->quantity}}</td>
                <td class="cost" style="text-align: right;">Rp. {{ number_format($trx_item->item_price->price, 0,",",".") }},-</td>
                <td style="text-align: right;"><span style="">Rp.</span><span class="price"> {{ number_format($total, 0,",",".") }},-</span></td>
            </tr>
            @endforeach
		  <tr style="border-bottom: 1px solid black;">
		    <td colspan="5">Calculation</td>
		  </tr>
		  
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Subtotal</td>
		      <td class="total-value" style="text-align: right"><div id="subtotal">Rp.  {{ number_format($transaction->amount, 0,",",".") }},-</div></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Pph 0%</td>
		      <td class="total-value" style="text-align: right"><div id="pph">Rp. 0.00</div></td>
		  </tr>
            <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">
                  Discount Service
                  @if(isset($transaction->vehicle->membership->discount))
                  {{$transaction->vehicle->membership->discount->value}} %
                  @endif
              </td>
		      <td class="total-value"  style="text-align: right"><div id="discount">Rp. {{ number_format($transaction->discount_amount, 0,",",".") }},-</div></td>
		  </tr>
            <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Total</td>
		      <td class="total-value"  style="text-align: right"><div id="total">Rp.  {{ number_format($transaction->paid_amount, 0,",",".") }},-</div></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Amount Paid</td>

		      <td class="total-value" style="text-align: right">Rp.  {{ number_format($transaction->paid_amount, 0,",",".") }},-</td>
		  </tr>
		</table>
<!--		<div id="terms">-->
<!--		  <h5>Terms</h5>-->
<!--		  <textarea>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</textarea>-->
<!--        </div>-->
	</div>
</body>

</html>