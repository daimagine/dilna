
@if (isset($errors) && count($errors->all()) > 0)
    @foreach ($errors->all('<p>:message</p>') as $message)
        <div class="nNote nFailure">
            {{ $message }}
        </div>
    @endforeach

@elseif (!is_null(Session::get('message_error')))
    @if (is_array(Session::get('message_error')))
        @foreach (Session::get('message_error') as $error)
            <div class="nNote nFailure">
                {{ $error }}
            </div>
        @endforeach
    @else
        <div class="nNote nFailure">
            <p>{{ Session::get('message_error') }}</p>
        </div>
    @endif
@endif

@if (!is_null(Session::get('message')))
<div class="nNote nSuccess">
    @if (is_array(Session::get('message')))
    @foreach (Session::get('message') as $success)
    <p>{{ $success }}</p>
    @endforeach
    @else
    <p>{{ Session::get('message') }}</p>
    @endif
</div>
@endif