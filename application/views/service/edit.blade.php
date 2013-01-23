@section('content')

@include('partial.notification')
<br>
{{ Form::open('service/edit', 'POST', array('id' => 'formEBengkel', 'name' => 'formEbengkel')) }}
{{ Form::hidden('id', $service->id) }}
<fieldset>

    <div class="widget fluid">
        <div class="whead">
            <h6>Edit Service</h6>

            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Service Name</label></div>
            <div class="grid9">
                <div class="grid5">
                    <input name="name" type="text" id="name" value="{{ $service->name }}" class="validate[required,minSize[]]"/>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Description :<span class="req">*</span></label></div>
            <div class="grid9"><textarea rows="8" cols="" name="description" class="validate[required]" id="description">{{$service->description}}</textarea></div><div class="clear"></div>
        </div>

        <div class="formRow">
            <div class="grid3"><label>Price (Rp.)</label></div>
            <div class="grid9">
                <div class="grid5">
                    <input name="price" type="text" id="price" value="{{ $service->service_formula()->price }}" class="validate[required,custom[number]]"/>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        {{-- Form::nyelect('status', array(1 => 'Active', 0 => 'Inactive'), $service->status, 'Status', array('class' => 'validate[required]')) --}}
        {{ Form::hidden('status', 1) }}

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('service/list', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}
                {{ Form::submit('Save', array( 'class' => 'buttonL bGreen mb10 mt5', 'id' => 'confirmAddButton' )) }}
            </div>
            <div class="clear"></div>
        </div>
    </div>

</fieldset>

<div id="dialogAdd" title="Confirmation Update Service">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span> Are you sure the data is correct ?</p>
    <p>Service Name  : <strong><span id="serviceName"></span></strong></p>
    <p>Service Price : <strong><span id="servicePrice"></span></strong></p>
    <p>If this is correct, click Submit Form. To edit, click Cancel.</p>
</div>

{{ Form::close() }}

@endsection