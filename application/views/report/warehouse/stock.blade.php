@section('content')

@include('partial.notification')

@include('partial.report.middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>

@include('partial.report.warehouse_middlenav')

<div class="clear"></div>
<div class="divider"><span></span></div>


<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Warehouse Updated Stock Report :: List</h6>
        <div class="clear"></div>
    </div>

    <form method="get" id="formList">
        <div class="fluid grid">
            <div class="formRow noBorderB">
                <div class="grid6">
                    <div class="clear" style=""></div>
                    <div class="grid3"><label>Start Add Date</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input name="startDate" type="text" class="datepicker" value="{{ $startDate }}" />
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Invoice No</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input type="text" id="invoiceNo" name="invoiceNo" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$invoiceNo}}"></button>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Item Name</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input type="text" id="itemName" name="name" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$name}}"></button>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Category</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            {{Form::select('category', $lstCategory, $category, array('id' => 'itemCategory'))}}
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                </div>

                <div class="grid6">
                    <div class="clear" style=""></div>
                    <div class="grid3"><label>End Add date</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input name="endDate" type="text" class="datepicker" value="{{ $endDate }}" />
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Reference No</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input type="text" id="refNum" name="refNum" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$refNum}}"></button>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Code</label> </div>
                    <div class="grid7">
                        <div class="searchLine" style="margin-top: 0px">
                            <input type="text" id="itemCode" name="code" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$code}}"></button>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                    <div class="grid3"><label>Type</label> </div>
                    <div class="grid7">
                        <div class="grid5 searchLine" style="margin-top: 0px" id="divType">
                            <input type="text" id="itemType" name="type" class="ac ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="{{$type}}"></button>
                        </div>
                    </div>
                    <div class="clear" style="padding-bottom: 15px;"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="fluid grid">
            <div class="formRow noBorderB">
                <div class="grid">
                    <ul class="timeRange">
                        <li><input type="submit" class="buttonS bLightBlue" value="Search"></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>

    <div class="divider"><span></span></div>


    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTableWarehouseItem" dtable-sortlist="[[0,'desc']]">
            <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Unit</th>
                <th>Code</th>
                <th>Add Quantity</th>
                <th>Invoice No</th>
                <th>Reference No</th>
                <th>Created at</th>
                <th>Configured by</th>
            </tr>
            </thead>

            <tbody>
                @foreach($listStockHistory as $d)
                <tr class="">
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->category }}</td>
                    <td>{{ $d->type }}</td>
                    <td>{{ $d->unit }}</td>
                    <td>{{ $d->code }}</td>
                    <td>{{ $d->stock }}</td>
                    <td>{{ $d->invoiceno}}</td>
                    <td>{{ $d->refnum }}</td>
                    <td>{{ $d->createdate }}</td>
                    <td>{{ $d->configuredby}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

@endsection

