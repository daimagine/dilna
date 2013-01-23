@section('content')

@include('partial.notification')

<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>Account List</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars overflowtable">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Category</th>
                <th>Name<span class="sorting" style="display: block;"></span></th>
                <th>Description</th>
                <th>Type</th>
                <th>Attribute</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($account as $account)
            <tr class="">
                @if($account->category == AccountCategory::ITEM)
                    <td>Item</td>
                @elseif($account->category == AccountCategory::ACCOUNTING)
                    <td>Accounting</td>
                @elseif($account->category == AccountCategory::ASSET)
                    <td>Asset</td>
                @else
                    <td>&nbsp;</td>
                @endif
                <td>{{ $account->name }}&nbsp;</td>
                <td>{{ $account->description }}&nbsp;</td>
                <td>{{ HTML::account_type($account->type) }}&nbsp;</td>
                <td class="tableActs" align="center">
                    @if($account->status)
                    <a href="#" class="fs1 iconb tipS" original-title="Active" data-icon=""></a>
                    @endif
                    &nbsp;
                </td>
                <td class="tableActs" align="center">
                    <a href='{{ url("account/edit/$account->id") }}'
						class="appconfirm tablectrl_small bDefault tipS" 
						original-title="Edit"
						dialog-confirm-title="Update Confirmation">
							<span class="iconb" data-icon=""></span>
					</a>
                    @if(Auth::user()->id == Config::get('default.role.admin'))
                        <a href='{{ url("account/delete/$account->id") }}'
                            class="appconfirm tablectrl_small bDefault tipS"
                            original-title="Remove"
                            dialog-confirm-title="Remove Confirmation">
                                <span class="iconb" data-icon=""></span>
                        </a>
                    @endif
                    &nbsp;
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

<div class="fluid">
    <div class="grid2">
        <div class="wButton"><a href="add" title="" class="buttonL bLightBlue first">
            <span class="icol-add"></span>
            <span>Add Account</span>
        </a></div>
    </div>
</div>

@endsection
