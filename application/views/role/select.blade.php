@section('content')

@include('partial.notification')
<br>

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Role Access</h6>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Choose Role</label></div>
            <div class="grid9 searchDrop">
                <select data-placeholder="Choose a Role..." class="select" style="min-width:350px;" id="role-select">
                    <option value="0"></option>
                    @foreach($roles as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="" align="center">
                {{ HTML::link('role/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                <a href="#next" class="buttonL bGreen mb10 mt5" onclick="roleSelect();">next</a>
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

@endsection