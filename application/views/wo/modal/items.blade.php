<!-- Tabs -->
<script src="/js/wo/application.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        console.log(':: define new table items jquery');
        oTable = $('.iTable').dataTable({
            "bJQueryUI": false,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"H"fl>t<"F"ip>'
        });

//        $.uniform.update("select");
    });
</script>
<div class="fluid">
    <div class="widget" style="margin-top: 0px;">
        <ul class="tabs">
            @foreach($lstItemCategory as $category)
                <li><a href="#{{$category->id}}">{{$category->name}}</a></li>
            @endforeach
        </ul>

        <div class="tab_container">
            @foreach($lstItemCategory as $category)
            <div id="{{$category->id}}" class="tab_content">
                <div id="dyn{{$category->id}}" class="shownpars">
                    <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
                    <table cellpadding="0" cellspacing="0" border="0" class="iTable" id="tableitems">
                        <thead>
                        <tr>
                            <th style="display: none" ></th>
                            <th>Name<span class="sorting" style="display: block;"></span></th>
                            <th>Code</th>
                            <th>Stock</th>
<!--                            <th>Description</th>-->
                            <th>Price</th>
                            <th>Vendor</th>
                            <th>Type</th>
                            <th>Unit</th>
                            <th>Exp Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lstItems as $item)
                            @if($item->item_category->id === $category->id)
                                <tr class="">
                                    <th style="display: none" class="item-id">{{$item->id}}</th>
                                    <td class="name">{{ $item->name }}</td>
                                    <td class="code">{{ $item->code }}</td>
                                    @if( $item->stock == 0 || $item->stock < $item->stock_minimum )
                                        <td class="stock redBack">{{ $item->stock }}</td>
                                    @else
                                        <td class="stock">{{ $item->stock }}</td>
                                    @endif
<!--                                    <td class="desc">{{ $item->description }}</td>-->
                                    <td class="price">{{ $item->price }}</td>
                                    <td class="vendor">{{ $item->vendor }}</td>
                                    <td class="type">{{ $item->item_type->name}}</td>
                                    <td class="unit">{{ $item->item_unit->name}}</td>
                                    <td>{{ $item->expiry_date }}</td>
                                    @if( $item->stock > 0 )
                                        <td align="center" class="tableActs "><a href="#" class="select-item fs1 iconb tipS" original-title="Select This" data-icon=""></a></td>
                                    @else
                                        <td>&nbsp;</td>
                                    @endif

                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
        <div class="clear"></div>
    </div>
</div>
</div>