@section('content')

@include('partial.notification')
<br>

{{ Form::open('/news/add', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>News Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'title', @$news['title'], 'Title *') }}

        {{ Form::nginput('text', 'resume', @$news['resume'], 'Resume') }}

        <div class="formRow">
            <div class="grid3"><label>Content</label></div>
            <div class="grid9"><textarea rows="8" cols="" name="content">{{ @$news['content'] }}</textarea> </div>
            <div class="clear"></div>
        </div>

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), isset($news['status']) ? $news['status'] : 1, 'Status') --}}
        {{ Form::hidden('status', 1) }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('news/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection