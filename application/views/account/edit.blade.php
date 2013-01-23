@section('content')


@include('partial.notification')
<br>

{{ Form::open('account/edit', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Account Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::hidden('id', $account->id) }}

        {{ Form::nginput('text', 'name', $account->name, 'Name *') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $account->status, 'Status *') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', $account->description, 'Description') }}

        {{ Form::nyelect('type', array('D' => 'Debit', 'C' => 'Credit'), $account->type, 'Type') }}

        {{ Form::nyelect('category', array(AccountCategory::ITEM => 'Item', AccountCategory::ACCOUNTING => 'Accounting', AccountCategory::ASSET => 'Asset',), $account->category, 'Category') }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('account/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection
