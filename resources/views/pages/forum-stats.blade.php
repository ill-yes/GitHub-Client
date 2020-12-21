@extends('main')
@section('content')
<meta name="_token" content="{{csrf_token()}}" />

@if(isset($error))
    <div class="alert alert-danger" role="alert">
        <div style="text-align: center"><b>Error: {{ $error }}</b></div>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div id="dailyStatsDonut-div"></div>
                        <div id="dailyStatsArea-div"></div>
                        <div id="dailyStatsBar-div"></div>
                        <div id="dailyStatsPie-div"></div>
                        <div id="dailyStatsLine-div"></div>
                        {!! $lava->render('DonutChart', 'dailyStatsDonut', 'dailyStatsDonut-div') !!}
                        {!! $lava->render('AreaChart', 'dailyStatsArea', 'dailyStatsArea-div') !!}
                        {!! $lava->render('BarChart', 'dailyStatsBar', 'dailyStatsBar-div') !!}
                        {!! $lava->render('PieChart', 'dailyStatsPie', 'dailyStatsPie-div') !!}
                        {!! $lava->render('LineChart', 'dailyStatsLine', 'dailyStatsLine-div') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js" charset="utf-8"></script>
    <script src="https://code.highcharts.com/highcharts.src.js"></script>--}}
@endsection
