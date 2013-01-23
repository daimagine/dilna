@section('content')

@include('partial.notification')
<br>

{{ Form::open('role/access', 'POST') }}
{{ Form::hidden('id', $role->id) }}

<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Role Access Assignment</h6>
            <div class="clear"></div>
        </div>

        <div class="inContainer">
            <div class="inFrom">
                <h5>{{ $role->name }}</h5>
                <span>{{ $role->description }}</span>
            </div>

            <div class="clear"></div>
        </div>

        <div class="whead"><span class="icon-glass"></span><h6>Access settings</h6><div class="clear"></div></div>
        <div class="body">
            <div class="leftBox">
                <input type="text" id="box1Filter" class="boxFilter" placeholder="Filter entries..." /><button type="button" id="box1Clear" class="dualBtn fltr">x</button><br />
                <select id="box1View" multiple="multiple" class="multiple" style="height:300px;">
                    @foreach($availableAccess as $access)
                        <option value="{{ $access->id }}">{{ $access->name }}</option>
                    @endforeach
                </select><br/>
                <button id="up1" type="button" class="dualBtn">&nbsp;up&nbsp;</button>
                <button id="down1" type="button" class="dualBtn">&nbsp;down&nbsp;</button>
                <span id="box1Counter" class="countLabel"></span>

                <div class="displayNone"><select id="box1Storage"></select></div>
            </div>

            <div class="dualControl">
                <button id="to2" type="button" class="dualBtn mr5 mb15">&nbsp;&gt;&nbsp;</button>
                <button id="allTo2" type="button" class="dualBtn">&nbsp;&gt;&gt;&nbsp;</button><br />
                <button id="to1" type="button" class="dualBtn mr5">&nbsp;&lt;&nbsp;</button>
                <button id="allTo1" type="button" class="dualBtn">&nbsp;&lt;&lt;&nbsp;</button>
            </div>

            <div class="rightBox">
                <input type="text" id="box2Filter" class="boxFilter" placeholder="Filter entries..." /><button type="button" id="box2Clear" class="dualBtn fltr">x</button><br />
                <select name="selectedAccess[]" id="box2View" multiple="multiple" class="multiple" style="height:300px;">
                    @foreach($selectedAccess as $access)
                        <option value="{{ $access->id }}">{{ $access->name }}</option>
                    @endforeach
                </select><br/>
                <button id="up2" type="button" class="dualBtn">&nbsp;up&nbsp;</button>
                <button id="down2" type="button" class="dualBtn">&nbsp;down&nbsp;</button>
                <span id="box2Counter" class="countLabel"></span>

                <div class="displayNone"><select id="box2Storage"></select></div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('role/select', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5', 'onclick' => 'ensureSelectAccess()' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

{{ Form::close() }}

@endsection