@section('content')

    @include('partial.notification')

    <ul class="middleNavR">
        <li><a href='{{ url("conversation/new") }}' class="tipN" original-title="Write Message"><img src='{{ asset("images/icons/middlenav/create.png") }}' alt=""></a></li>
        <li><a href='{{ url("conversation/list") }}' class="tipN" original-title="Messages"><img src='{{ asset("images/icons/middlenav/dialogs.png") }}' alt=""></a><strong>8</strong></li>
    </ul>

    @if(empty($conversation->messages))

        <div class="nNote nInformation">
            <p>You dont have any message yet.</p>
        </div>

    @else

        <div class="widget">
            <div class="whead">
                <h6>{{ $conversation->list_user }}</h6>
                <div class="on_off">
                    <span class="icon-reload-CW"></span>
                </div>
                <div class="clear"></div>
            </div>

            <ul class="messagesOne">

                <?php $last = null ?>

                @foreach($conversation->messages as $message)

                    @if($message->user_id == Auth::user()->id)

                        <?php if($last == null) $last = $message->sender_id; ?>

                        @if($last != $message->sender_id)
                        <li class="divider"><span></span></li>
                        @endif

                        <li class="{{ $message->sender_id }} {{ $message->sender->id }} {{ $message->sender_id == Auth::user()->id ? 'by_user' : 'by_me' }}">
                            <a href="#" title=""><img src='{{ asset("images/userLogin.png") }}' alt="" width="37" height="36" alt=""></a>
                            <div class="messageArea">
                                <span class="aro"></span>
                                <div class="infoRow">
                                    <span class="name"><strong>{{ $message->sender_id == Auth::user()->id ? 'You' : $message->sender->name }}</strong> says:</span>
                                    <span class="time">{{ date('d F Y H:i:s', strtotime( $message->created_at )) }}</span>
                                    <div class="clear"></div>
                                </div>
                                {{ $message->message }}
                            </div>
                            <div class="clear"></div>
                        </li>

                        <?php $last = $message->sender_id ?>

                    @endif

                @endforeach
            </ul>
        </div>

    @endif

    <div class="widget">

        <div class="body">
            {{ Form::open('conversation/send') }}

            {{ Form::hidden('return_url', 'reply') }}
            {{ Form::hidden('conversation_id', $conversation->id) }}
            <div class="messageTo">
                <select name="user[]" data-placeholder="Enter receiver's name" class="fullwidth select" multiple="multiple" tabindex="6" id="receiver-list">
                    <option value=""></option>
                    @foreach($conversation->users as $user)
                        @if($user->id != Auth::user()->id)
                            <option value="{{ $user->id }}" selected="selected">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <textarea rows="5" cols="" name="message" class="auto" placeholder="Write your message" style="overflow: hidden; ">{{ Input::old('message') }}</textarea>
            <div class="mesControls">
                <span><span class="iconb" data-icon=""></span>Don't forget to set <a href="#" title="">Message Receiver</a></span>

                <div class="sendBtn sendwidget">
                    <input type="submit" name="sendMessage" class="buttonM bLightBlue" value="Send message">
                </div>
                <div class="clear"></div>
            </div>

            {{ Form::close() }}

            <div style="position: absolute; display: none; word-wrap: break-word; white-space: pre-wrap; border: 1px solid rgb(215, 215, 215); font-weight: normal; width: 239px; font-family: Arial, Helvetica, sans-serif; line-height: normal; font-size: 11px; padding: 6px 7px; ">&nbsp;</div>
        </div>
    </div>

@endsection