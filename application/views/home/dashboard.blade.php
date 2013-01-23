@section('content')

@include('partial.notification')

<div class="fluid">

    <div class="grid8">

        <div class="widget">
            <div class="whead">
                <h6>Purchase Order Alert</h6>
                <div class="clear"></div>
            </div>
            <ul class="updates">
                @foreach($payablesExp as $a)
                <li>
                        <span class="uAlert">
                            Invoice <a href="#" title="">{{ $a->invoice_no }}</a> {{ $a->type === 'D' ? 'from' : 'to' }} <b>{{ $a->subject }}</b>
                            <span>
                                <b>Rp. {{ number_format($a->due, 2) }} </b> &nbsp; {{ abs( floor(( strtotime($a->due_date) - time() )/3600/24) ) }} days passed
                                <br>{{ $a->due_date }}
                            </span>
                        </span>
                        <span class="uDate" style="width: 95px;">
                            <a href="{{ url('account/pay_invoice/'.$a->type.'/'.$a->id) }}" title="" class="sideB bLightBlue mt10">pay invoice</a>
                        </span>
                    <span class="clear"></span>
                </li>
                @endforeach
                @if(sizeof($payablesExp)==0)
                    <li>
                        <span class="grey">No expired purchase order</span>
                    </li>
                @else
                    <li>
                        <a href="{{ url('account/account_payable/unpaid') }}">See all {{ ( $totalPayablesExp > 0 ? $totalPayablesExp : '' ) }} expired purchase order</a>
                    </li>
                @endif

                @foreach($payables as $a)
                <li>
                        <span class="uNotice">
                            Invoice <a href="#" title="">{{ $a->invoice_no }}</a> {{ $a->type === 'D' ? 'from' : 'to' }} <b>{{ $a->subject }}</b>
                            <span>
                                <b>Rp. {{ number_format($a->due, 2) }} </b> &nbsp; {{ abs( floor(( time() - strtotime($a->due_date) )/3600/24) ) }} days remaining
                                <br>{{ $a->due_date }}
                            </span>
                        </span>
                        <span class="uDate" style="width: 95px;">
                            <a href="{{ url('account/pay_invoice/'.$a->type.'/'.$a->id) }}" title="" class="sideB bDefault mt10">pay invoice</a>
                        </span>
                    <span class="clear"></span>
                </li>
                @endforeach
                @if(sizeof($payables)==0)
                    <li>
                        <span class="grey">No upcoming purchase order for {{ Config::get('default.scheduler.settlement.day_due') }} following days</span>
                    </li>
                @else
                    <li>
                        <a href="{{ url('account/account_payable/unpaid') }}">See all {{ ( $totalPayables > 0 ? $totalPayables : '' ) }} upcoming due purchase order</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="widget">
            <div class="whead">
                <h6>Account Receivable Alert</h6>
                <div class="clear"></div>
            </div>
            <ul class="updates">
                @foreach($receivablesExp as $a)
                <li>
                        <span class="uAlert">
                            Invoice <a href="#" title="">{{ $a->invoice_no }}</a> {{ $a->type === 'D' ? 'from' : 'to' }} <b>{{ $a->subject }}</b>
                            <span>
                                <b>Rp. {{ number_format($a->due, 2) }} </b> &nbsp; {{ abs( floor(( strtotime($a->due_date) - time() )/3600/24) ) }} days passed
                                <br>{{ $a->due_date }}
                            </span>
                        </span>
                        <span class="uDate" style="width: 95px;">
                            <a href="{{ url('account/pay_invoice/'.$a->type.'/'.$a->id) }}" title="" class="sideB bLightBlue mt10">pay invoice</a>
                        </span>
                    <span class="clear"></span>
                </li>
                @endforeach
                @if(sizeof($receivablesExp)==0)
                    <li>
                        <span class="grey">No expired account receivable</span>
                    </li>
                @else
                    <li>
                        <a href="{{ url('account/account_receivable/unpaid') }}">See all {{ ( $totalReceivablesExp > 0 ? $totalReceivablesExp : '' ) }} expired account receivable</a>
                    </li>
                @endif

                @foreach($receivables as $a)
                <li>
                        <span class="uNotice">
                            Invoice <a href="#" title="">{{ $a->invoice_no }}</a> {{ $a->type === 'D' ? 'from' : 'to' }} <b>{{ $a->subject }}</b>
                            <span>
                                <b>Rp. {{ number_format($a->due, 2) }} </b> &nbsp; {{ abs( floor(( time() - strtotime($a->due_date) )/3600/24) ) }} days remaining
                                <br>{{ $a->due_date }}
                            </span>
                        </span>
                        <span class="uDate" style="width: 95px;">
                            <a href="{{ url('account/pay_invoice/'.$a->type.'/'.$a->id) }}" title="" class="sideB bDefault mt10">pay invoice</a>
                        </span>
                    <span class="clear"></span>
                </li>
                @endforeach
                @if(sizeof($receivables)==0)
                    <li>
                        <span class="grey">No upcoming account receivable for {{ Config::get('default.scheduler.settlement.day_due') }} following days</span>
                    </li>
                @else
                    <li>
                        <a href="{{ url('account/account_receivable/unpaid') }}">See all {{ ( $totalReceivables > 0 ? $totalReceivables : '' ) }} upcoming due account receivable</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="widget">
            <div class="whead">
                <h6>Recent Membership</h6>
                <div class="titleOpt">
                    <a href="#" data-toggle="dropdown"><span class="iconb" data-icon=""></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="member/index" class=""><span class="icon-list"></span>All Membership List</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tAlt">
                <thead>
                <tr>
                    <td width="">Vehicle</td>
                    <td>Customer</td>
                    <td width="">Membership</td>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $m)
                <tr>
                    <td align="center"><a href="#" title="" class="webStatsLink">{{ $m->vehicle->number }}</a></td>
                    <td>{{ $m->customer->name }}</td>
                    <td align=""><span class="icos-postcard"></span>{{ $m->description }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>


    <div class="grid4">
        <!-- updates item price here -->
        <div class="widget">
            <div class="whead">
                <h6>5 Last updates item price</h6>
                <div class="clear"></div>
            </div>
            <ul class="updates">
                @foreach($item_prices as $ip)
                <li>
                 <span class="uAlert">
                     <a href="#" title="">{{$ip->item->name}}</a>
                     <span><b>{{$ip->users->name}}</b> update harga {{$ip->item->name}}, unit type {{$ip->item->item_unit->name}} dari {{$ip->prev_price}} menjadi {{$ip->price}}</span>
<!--                     <a href="#" title="" class="sideB bLightBlue mt10">Add new session</a>-->
                 </span>
                    <span class="uDate"><span>{{ date('d', strtotime($ip->created_at)) }}</span>{{ date('M', strtotime($ip->created_at)) }}</span>
                    <span class="clear"></span>
                </li>
                @endforeach
                <li>
                    <span class="">
                        <a href='{{ url("item/list_history") }}' title="">Show All Update</a>
                    </span>
                    <span class="clear"></span>
                </li>
            </ul>
        </div>

    </div>

</div>

<div id="detailNews" class="dialog" title="Detail News" ></div>

@endsection


@section('sidebar_content')

<div class="widget">
    <div class="whead">
        <h6>Settlement Notices</h6>
        <div class="clear"></div>
    </div>
    <div class="body">
        <ul class="wInvoice">
            <li style="width:50%"><h4 class="red">{{ number_format($settlements[SettlementState::UNSETTLED]) }}</h4><span class="red">Unsettled</span></li>
            <li style="width:50%"><h4 class="blue">{{ number_format($settlements[SettlementState::SETTLED_UNMATCH]) }}</h4><span class="blue">Unmatch</span></li>
        </ul>
        <div class="clear"></div>

        <div class="invList fluid">
            <a href='{{ url("settlement/list") }}' title="" class="floatR buttonS bLightBlue">Process Settlement</a>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="searchLine">
    <div style="padding-left: 5px;">
        <span class="icos-archive"></span>
        <h6 style="margin-bottom: 5px;">Recent News</h6>
    </div>
    <div class="clear"></div>

    <div class="relative">
        <input id="searchInputNews" type="text" name="search" class="ac" placeholder="Enter search text...">
        <button id="searchInputBtn" type="submit" name="find" value="">
            <span class="icos-search"></span>
        </button>
    </div>
    <div class="sResults">
        <span class="arrow"></span>
        <ul class="updates">
            @foreach($news as $n)
            <li class="newsline">
                <span class="uNotice" style="max-width: 72%;">
                    <a href="#detail" onclick="detailNews('{{ $n->id }}')">{{ $n->title }}</a>
                    <span>{{ $n->resume }} ...</span>
                </span>
                <span class="uDate"><span>{{ date('d', strtotime($n->created_at)) }}</span>
                    {{ date('M', strtotime($n->created_at)) }}</span>
                <span class="clear"></span>
            </li>
            @endforeach
            <li>
                <span class="">
                    <a href='{{ url("news/all") }}' title="">Show All News</a>
                </span>
                <span class="clear"></span>
            </li>
        </ul>
    </div>
</div>

@endsection