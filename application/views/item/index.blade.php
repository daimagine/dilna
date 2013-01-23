


@section('content')

@include('partial.notification')



<div class="widget">
    <div class="whead"><h6>Warehouse Management</h6><div class="clear"></div></div>
    <ul class="updates">
        @foreach($item_category as $category)
        <li>
            <div class="exp pointer">
                <div class="wNews">
                    <a href="#" title="" class="headline"><img src='{{ asset("images/icons/color/$category->picture") }}' alt=""></a>
                    <div class="announce">
                        <a href="#" title="">{{ $category->name }}</a>
                        <span>{{ $category->description}}</span>
                    </div>
                </div>
                <span class="uDate" style="width: 65px"><span>{{ sizeof($category->item) }}</span>Item</span>
            </div>
            <div class="clear">
                <span class="clear"></span>
                <ul class="updates">
                    <li>
                            <span class="" style="width: 100%">
                                    <ul>
                                        <li class="on_off">
                                            <label><span class="icos-cog2"></span>
                                                <a class="formDialog_open" href='{{ url("item/list?category=$category->id") }}'>Show List this items</a>
                                            </label>
                                            <div class="clear"></div>
                                        </li>
                                        <li class="on_off">
                                            <label><span class="icos-cog2"></span>
                                                <a class="formDialog_open" href='{{ url("item/add?category=$category->id") }}'>Add New items</a>
                                            </label>
                                            <div class="clear"></div>
                                        </li>
                                        <li class="on_off">
                                            <label><span class="icos-cog2"></span>
                                                <a class="formDialog_open" href='{{ url("item/list_history?category=$category->id") }}'>Show History this items</a>
                                            </label>
                                            <div class="clear"></div>
                                        </li>
                                    </ul>
                            </span>
                        <span class="clear"></span>
                    </li>
                </ul>
            </div>
            <span class="clear"></span>
        </li>
        @endforeach
    </ul>
</div>
@endsection