<div>
    <ul class="niceList params">
        <li>
            <div class="myInfo">
                <h5>{{ $news->title }}</h5>
                <span class=""></span>
                <span class="myRole followers">{{ date('d F Y', strtotime($news->created_at)) }}</span>
            </div>
            <div class="clear"></div>
        </li>
        <li class="on_off">
            <div>
                {{ $news->content }}
            </div>
            <div class="clear"></div>
        </li>
    </ul>
</div>
<div class="clear"></div>
