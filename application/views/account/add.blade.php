@section('content')

@include('partial.notification')
<br>

{{ Form::open('/account/add', 'POST') }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Account Add</h6>

            <div class="clear"></div>
        </div>

        {{ Form::nginput('text', 'name', @$account['name'], 'Name *') }}

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), isset($account['status']) ? $account['status'] : 1, 'Status *') --}}
        {{ Form::hidden('status', 1) }}

        {{ Form::nginput('text', 'description', @$account['description'], 'Description') }}

        {{ Form::nyelect('type', array('D' => 'Debit', 'C' => 'Credit'), isset($account['type']) ? $account['type'] : 'D', 'Type') }}

        {{ Form::nyelect('category', array(AccountCategory::ITEM => 'Item', AccountCategory::ACCOUNTING => 'Accounting', AccountCategory::ASSET => 'Asset',), isset($account['category']) ? $account['category'] : AccountCategory::ACCOUNTING, 'Category') }}

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