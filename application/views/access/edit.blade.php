@section('content')


@include('partial.notification')
<br>

{{ Form::open('access/edit', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Access Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::hidden('id', $access->id) }}

        {{ Form::nginput('text', 'name', $access->name, 'Name *') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $access->status, 'Status *') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'action', $access->action, 'Action') }}

        {{ Form::nginput('text', 'description', $access->description, 'Description') }}

        {{ Form::nyheckbox('parent', 1, $access->parent, 'Parent', 'Action will be a parent for another action') }}

        {{ Form::nyheckbox('visible', 1, $access->visible, 'Visible', 'Action will be visible in main or sub navigation') }}

        {{ Form::nyelect('type', array('M' => 'Main Navigation', 'S' => 'Sub Navigation', 'L' => 'Link or Action'), $access->type, 'Type') }}

        {{ Form::nyelect('parent_id', $parents, $access->parent_id, 'Parent') }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('access/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection
