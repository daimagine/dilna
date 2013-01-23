
@section('content')

@include('partial.notification')

    <ul class="middleNavR">
        <li><a href='{{ url("conversation/new") }}' class="tipN" original-title="Write Message"><img src='{{ asset("images/icons/middlenav/create.png") }}' alt=""></a></li>
        <li><a href='{{ url("conversation/list") }}' class="tipN" original-title="Messages"><img src='{{ asset("images/icons/middlenav/dialogs.png") }}' alt=""></a><strong>8</strong></li>
    </ul>

    <div class="widget">
        <div class="whead">
            <h6>Start Conversation</h6>
            <div class="clear"></div>
        </div>

        <div class="body">

            {{ Form::open('conversation/send') }}

            <div class="messageTo">
                <select name="user[]" data-placeholder="Enter receiver's name" class="fullwidth select" multiple="multiple" tabindex="6" id="receiver-list">
                    <option value=""></option>
                </select>
            </div>
            <textarea rows="5" cols="" name="message" class="auto" placeholder="Write your message" style="overflow: hidden; ">{{ Input::old('message') }}</textarea>
            <div class="mesControls">
                <span><span class="iconb" data-icon=""></span>Don't forget to set <a href="#" title="">Message Receiver</a></span>

                <div class="sendBtn sendwidget">
<!--                    <a href="#" title="" class="attachPhoto"></a>-->
<!--                    <a href="#" title="" class="attachLink"></a>-->
                    <input type="submit" name="sendMessage" class="buttonM bLightBlue" value="Send message">
                </div>
                <div class="clear"></div>
            </div>

            {{ Form::close() }}

            <div style="position: absolute; display: none; word-wrap: break-word; white-space: pre-wrap; border: 1px solid rgb(215, 215, 215); font-weight: normal; width: 239px; font-family: Arial, Helvetica, sans-serif; line-height: normal; font-size: 11px; padding: 6px 7px; ">&nbsp;</div>
        </div>
    </div>

@endsection
