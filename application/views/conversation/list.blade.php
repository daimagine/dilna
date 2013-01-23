@section('content')

    @include('partial.notification')

    <ul class="middleNavR">
        <li><a href='{{ url("conversation/new") }}' class="tipN" original-title="Write Message"><img src='{{ asset("images/icons/middlenav/create.png") }}' alt=""></a></li>
        <li><a href='{{ url("conversation/list") }}' class="tipN" original-title="Messages"><img src='{{ asset("images/icons/middlenav/dialogs.png") }}' alt=""></a></li>
    </ul>

    @if(empty($conversations))

        <div class="nNote nInformation">
            <p>You dont have any conversation yet.</p>
        </div>

    @else

        <div class="widget">
            <div class="whead">
                <h6>Messages</h6>
                <div class="clear"></div>
            </div>

            <ul class="messagesTwo">

                @foreach($conversations as $conv)
                    <?php $pivot = $conv->self(); ?>
                    <li class="{{ $pivot['read'] == true ? 'by_me' : 'by_user' }} pointer tipE"
                        data-id="{{ $conv->id }}"
                        onclick="viewConversation(this);"
                        original-title="view conversation">

                        <a href="#" title=""><img src="{{ url( $conv->list_image() ) }}" alt="" width="37" height="36"></a>
                        <div class="messageArea">
                            <div class="infoRow">
                            <span class="name"><strong>{{ $conv->list_user }}</strong></span>
                            <span class="time">{{ date('d F Y H:i:s', strtotime($conv->updated_at)) }}</span>
                            <div class="clear"></div>
                        </div>
                            {{ $conv->subject }}
                        </div>
                        <div class="clear"></div>
                    </li>
                @endforeach

            </ul>
        </div>

    @endif

@endsection