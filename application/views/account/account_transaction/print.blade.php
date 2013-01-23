<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>SpringWizard eBengkel - {{ $accountTransType === 'D' ? 'Tagihan Uang Masuk' : 'Purchase Order' }}</title>
    <?php echo Asset::container('print')->styles(); ?>
    <?php echo Asset::container('print')->scripts(); ?>

</head>
<body onload="javascript:window.print();">

    <div style="margin-top: 10px;text-align: center;"></div>
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
            <table id="meta">
                <tr>
                    <td class="meta-head">Tanggal</td>
                    <td style="text-align: right">:</td>
                    <td id="currentTime"></td>
                </tr>
                <tr>
                    <td class="meta-head">Nomor Invoice</td>
                    <td style="text-align: right">:</td>
                    <td><textarea>{{ $account->invoice_no }}</textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Nomor Referensi</td>
                    <td style="text-align: right">:</td>
                    <td>
                        <div class="due">{{ $account->reference_no }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="clear:both"></div>

        <div style="margin-top: 40px;">
            <div class="table-name">
                <h5>{{ $accountTransType === 'D' ? 'Tagihan Uang Masuk' : 'Purchase Order' }}</h5>
            </div>
            <table class="table-info" style="margin-top: 0px">
                <?php $tax = 0; $amount = 0; for($i = 0; $i < count($items); $i++) { $tax += $items[$i]->tax_amount; $amount += $items[$i]->amount; } ?>

                <tr>
                    <td class="table-detail-left">{{ $accountTransType === 'D' ? 'Dari' : 'Untuk' }}</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">{{ strtoupper($account->subject) }}</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Tanggal Invoice</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">{{ date( 'd F Y', strtotime($account->invoice_date) ) }}</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Jatuh Tempo Pembayaran</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">{{ date( 'd F Y', strtotime($account->due_date) ) }}</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Status Pembayaran</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">{{ $account->paid_date == null ? 'Belum Lunas' : ( $account->paid < $account->due ? 'Belum Lunas' : 'LUNAS' )}}</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Total Pajak</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">Rp. {{ number_format($tax, 2) }},-</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Tagihan Non Pajak</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">Rp. {{ number_format(( $amount - $tax ), 2) }},-</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Total Tagihan</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">Rp. {{ number_format($amount, 2) }},-</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Pembayaran Saat Ini</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">Rp. {{ number_format(( $account->paid ), 2) }},-</td>
                </tr>

                <tr>
                    <td class="table-detail-left">Sisa Tagihan</td>
                    <td class="table-detail-mid">:</td>
                    <td class="table-detail-right">Rp. {{ number_format(( $account->due - $account->paid ), 2) }},-</td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>

        <div style="margin-top: 20px;">
            <div class="table-name">
                <h5>DAFTAR BARANG</h5>
            </div>
            <table id="service" style="margin-top: 0px">

                <tr>
                    <td style="text-align: center">No</td>
                    <td style="text-align: center">Barang/Jasa</td>
                    <td style="text-align: center">Jumlah</td>
                    <td style="text-align: center">Kategori</td>
                    <td style="text-align: center">Persen Pajak</td>
                    <td style="text-align: center">Pajak</td>
                    <td style="text-align: center">Subtotal</td>
                </tr>

                <?php $idx=1; ?>
                @for ($i = 0; $i < count($items); $i++)
                    <tr class="service-row">
                        <td class="other" style="width: 63px" align="center">{{ $idx }}</td>
                        <td class="description other" style="width: 220px">{{ $items[$i]->item }}</td>
                        <td class="item-name other" align="center">{{ $items[$i]->quantity }}</td>
                        <td class="qty other" align="center" style="width: 140px">{{ $items[$i]->account->name }}</td>
                        <td class="cost other" style="text-align: right; width: 30px">{{ $items[$i]->tax }}%</td>
                        <td style="text-align: right;" class="other">Rp. {{ number_format($items[$i]->tax_amount, 2) }},-</td>
                        <td style="text-align: right;" class="other">Rp. {{ number_format($items[$i]->amount, 2) }},-</td>
                    </tr>
                    <?php $idx++; ?>
                @endfor

            </table>
        </div>
        <div class="clear"></div>

        <div style="padding: 50px">
            <table id="signed">
                <tr>
                    <td align="center"><h5 style="border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; width: 180px;"></h5></td>
                    <td align="center"><h5 style="border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; width: 180px;"></h5></td>
                </tr>
                <tr>
                    <td align="center"> {{$account->subject}}, <br> ({{ $accountTransType === 'D' ? 'Customer' : 'Vendor' }}) </td>
                    <td align="center">{{ Config::get('default.print.account.signed.name') }}, <br> ({{ Config::get('default.print.account.signed.position') }})</td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>