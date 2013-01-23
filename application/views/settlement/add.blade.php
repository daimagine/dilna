@section('content')

@include('partial.notification')
<br>

{{ Form::open('settlement/add', 'POST', array( 'name' => 'formSettlement' ) ) }}
<fieldset>
    <div class="widget fluid">
        <div class="whead">
            <h6>NewTransaction Settlement</h6>

            <div class="clear"></div>
        </div>

        <div class="inContainer">
            <div class="fluid">
                <div class="grid6">
                    <div class="inFrom" style="width: 90%; margin-bottom: 0px;">
                        <h5>Settlement Date : <strong class="red">{{ date('d F Y', strtotime($datesettle)) }}</strong></h5>
                        <span class="black">Total from Transaction <a href="#">IDR {{ number_format($total_transaction, 2) }}</a></span>
                        <span>Settlement is <strong id="settlement-state">Unmatch</strong></span>
                    </div>

                    <div class="total-left">
                        <span>Total Amount</span>
                        <strong class="greenBack" id="total-amount">{{ number_format(Input::old('amount_cash') != null ? Input::old('amount_cash') : 0, 2) }}</strong>
                        <em><a href="#recalculate" onclick="recalculateAmount();">recalculate</a></em>
                    </div>
                </div>
                <div class="grid6">
                    <div class="inFrom" style="width: 90%;">

                        {{ Form::hidden('settlement_date', date('Y-m-d', strtotime($datesettle))) }}

                        {{ Form::nginput('text', 'amount_cash',  Input::old('amount_cash') != null ? Input::old('amount_cash') : 0, 'Amount in Cash', array( 'class' => 'calculate-total' )) }}

                        {{ Form::nginput('text', 'amount_non_cash',  Input::old('amount_non_cash') != null ? Input::old('amount_non_cash') : 0, 'Amount non Cash', array( 'class' => 'calculate-total' )) }}

                        <em>Amount information above will be summarized into total transaction on the left side. Click <strong>recalculate</strong> if total transaction is not updated automatically</em>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>

        <div class="divider"></div>

        <div class="fluid">
            <div class="grid6">

                <div class="formRow">
                    <div class="grid3"><label>IDR 100,000</label></div>
                    <div class="grid9">
                        <input name="fraction_100000" type="text" class="fraction" value="{{ Input::old('fraction_100000') != null ? Input::old('fraction_100000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 50,000</label></div>
                    <div class="grid9">
                        <input name="fraction_50000" type="text" class="fraction" value="{{ Input::old('fraction_50000') != null ? Input::old('fraction_50000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 20,000</label></div>
                    <div class="grid9">
                        <input name="fraction_20000" type="text" class="fraction" value="{{ Input::old('fraction_20000') != null ? Input::old('fraction_20000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 10,000</label></div>
                    <div class="grid9">
                        <input name="fraction_10000" type="text" class="fraction" value="{{ Input::old('fraction_10000') != null ? Input::old('fraction_10000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 5,000</label></div>
                    <div class="grid9">
                        <input name="fraction_5000" type="text" class="fraction" value="{{ Input::old('fraction_5000') != null ? Input::old('fraction_5000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

            </div>

            <div class="grid6">

                <div class="formRow">
                    <div class="grid3"><label>IDR 2,000</label></div>
                    <div class="grid9">
                        <input name="fraction_2000" type="text" class="fraction" value="{{ Input::old('fraction_2000') != null ? Input::old('fraction_2000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 1,000</label></div>
                    <div class="grid9">
                        <input name="fraction_1000" type="text" class="fraction" value="{{ Input::old('fraction_1000') != null ? Input::old('fraction_1000') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 500</label></div>
                    <div class="grid9">
                        <input name="fraction_500" type="text" class="fraction" value="{{ Input::old('fraction_500') != null ? Input::old('fraction_500') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 100</label></div>
                    <div class="grid9">
                        <input name="fraction_100" type="text" class="fraction" value="{{ Input::old('fraction_100') != null ? Input::old('fraction_100') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid3"><label>IDR 50</label></div>
                    <div class="grid9">
                        <input name="fraction_50" type="text" class="fraction" value="{{ Input::old('fraction_50') != null ? Input::old('fraction_50') : 0 }}" />
                    </div>
                    <div class="clear"></div>
                </div>

            </div>
        </div>

<!--        <div class="divider"></div>-->
<!---->
<!--        <div class="fluid">-->
<!---->
<!--            <div class="formRow">-->
<!--                <div class="grid2"><label>Settlement Date *</label></div>-->
<!--                <div class="grid10">-->
<!--                    <ul class="timeRange">-->
<!--                        <li><input name="settlement_date" type="text" class="datepicker" value="{{ Input::old('settlement_date') }}" /></li>-->
<!--                        <li class="sep">-</li>-->
<!--                        <li><input name="settlement_time" type="text" class="timepicker" size="10" value="{{ Input::old('settlement_time') }}" />-->
<!--                            <span class="ui-datepicker-append">(hh:mm:ss)</span>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--                <div class="clear"></div>-->
<!--            </div>-->
<!---->
<!--        </div>-->

        <div class="formRow noBorderB">
            <div class="status" id="status3"></div>
            <div class="formSubmit">
                {{ HTML::link('settlement/index', 'Cancel', array( 'class' => 'buttonL bDefault mb10 mt5' )) }}

                <input class="appconfirm buttonL bGreen mb10 mt5" type="submit" value="Save"
                       original-title="Confirmation"
                       dialog-confirm-title="Settlement Confirmation"
                       dialog-confirm-content="Please reassure that information is valid. Any unmatching data is your current responsibilities and may cause you to pay the for mismatch amount"
                       dialog-confirm-callback="document.forms.formSettlement.submit()">
            </div>
            <div class="clear"></div>
        </div>

    </div>
</fieldset>
{{ Form::close() }}

@endsection