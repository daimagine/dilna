@section('content')

@include('partial.notification')
<!-- Rounded buttons -->
<ul class="middleNavA">
    @foreach($item_category as $item_category)
    <li><a href='{{ url("item/list?category=$item_category->id") }}' title="{{$item_category->name}}" style="width: 100px;height: 65px;"><img src='{{ asset("images/icons/color/$item_category->picture") }}' alt="" /><span style="@if($category->name == $item_category->name) color:red @endif">{{$item_category->name}}</span></a></li>
    @endforeach
</ul>
<div class="divider"><span></span></div>

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>List Item {{$category->name}}</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Name<span class="sorting" style="display: block;"></span></th>
                <th>Code</th>
                <th>Stock</th>
                <th>Stock Minimum</th>
                <th>Description</th>
                <th>Price</th>
                <th>Vendor</th>
                <th>Create Date</th>
<!--                <th>Expiry Date</th>-->
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($item as $item)
            <tr class="">
                <td class="name">{{ $item->name }}&nbsp;</td>
                <td>{{ $item->code }}&nbsp;</td>
                <td>{{ $item->stock }}&nbsp;</td>
                <td>{{ $item->stock_minimum }}&nbsp;</td>
                <td>{{ $item->description }}&nbsp;</td>
                <td>{{ $item->price }}&nbsp;</td>
                <td>{{ $item->vendor }}&nbsp;</td>
                <td>{{ $item->created_at }}&nbsp;</td>
<!--                <td>{{ $item->expiry_date }}</td>-->
                <td class="tableActs" align="center">
                    <a href='{{ url("item/edit/$item->id") }}' class="classConfirmEdit tablectrl_small bDefault tipS" original-title="Edit"><span class="iconb" data-icon=""></span></a>
                    <a href='{{ url("item/delete/$item->id") }}' class="classConfirmDelete tablectrl_small bDefault tipS" original-title="Remove">
                        <span class="iconb" data-icon=""></span>
                        <!-- Dialog modal confirmation delete item-->
                    </a>
                    &nbsp;
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<div class="fluid">
    <div class="grid2">
        <div class="wButton"><a href='{{ url("item/add?category=$category->id") }}' title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add New</span>
        </a></div>
    </div>
</div>

<div id="confirmDelete" class="dialog" title="Confirmation Delete" ></div>

</div>

@endsection