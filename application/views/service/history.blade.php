@section('content')

@include('partial.notification')
<!-- Table with opened toolbar -->
<div class="widget">
    <div class="whead">
        <h6>List History Price Service</h6>
        <div class="clear"></div>
    </div>
    <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options">{{ HTML::image('images/icons/options', '') }}</a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
            <tr>
                <th>Service No<span class="sorting" style="display: block;"></span></th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Price</th>
                <th>Assignment Date</th>
                <th>Expiry Date</th>
                <th>Configured By</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lstSerFor as $serfor)
            <tr class="">
                <td>{{ $serfor->service->id }}</td>
                <td>{{ $serfor->service->name }}</td>
                <td>{{ $serfor->service->description }}</td>
                <td>{{ $serfor->status == 1 ? 'Active' : 'Expired'}}</td>
                <td>{{ $serfor->price }}</td>
                <td>{{ $serfor->created_at }}</td>
                <td>{{ $serfor->updated_at }}</td>
                <td>{{ $serfor->user->name}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>

@endsection
