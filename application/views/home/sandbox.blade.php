@section('content')

@include('partial.notification')

<div class="fluid">

    <div class="grid">

        <div class="widget">
            <div class="whead">
                <h6>Chart Example</h6>
                <div class="clear"></div>
            </div>
            <div class="body">
                <div id="container-chart" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>

        <div class="widget">
            <div class="whead">
                <h6>XML Example</h6>
                <div class="clear"></div>
            </div>
            <div class="body">
                <div>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection


@section('additional_js')
    <script type="text/javascript">
        (function() {

            <?php
                $data = array(
                    array(
                        'name'  =>  'Tokyo',
                        'data'  =>  array(10, 20, 30, 40, 20, 15, 5, null, 9)
                    ),
                    array(
                        'name'  =>  'New York',
                        'data'  =>  array(5, 10, 15, 20, 10, 5, 5, 7, null)
                    )
                );
            ?>

            var data = <?php echo json_encode($data); ?>;
            console.log(data);

            var chart = new AutoChart({
                chart: {
                    renderTo: 'container-chart'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                yAxis: {
                    title: {
                        text: 'Y Axis'
                    }
                },
                title: {
                    text: 'Chart Title'
                },
                subtitle: {
                    text: 'SubTitle'
                },
                series: data
            });

        })();

    </script>
@endsection




