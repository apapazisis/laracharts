<div id="{{ $chart->id }}" style="position:relative;display:block;width: 100%; height:400px;">
</div>

<script>
    window.onresize = function() {
        setTimeout(function () {
            window['{{ $chart->id }}'].resize();
        }, 200);
    };
    
    window.addEventListener('load', function(e)
    {
        {{ $chart->id }}CreateOrUpdateChart();
    });

    function {{ $chart->id }}CreateOrUpdateChart(filters = {})
    {
        var chartArgs = {};
        var chartWorker = new Worker('vendor/charts/worker.js');
        filters = Object.keys(filters).map(key => `${key}=${encodeURIComponent(filters[key])}`).join('&');

        chartWorker.addEventListener('message', function (e)
        {
            chartArgs = e.data.chartArgs;

            generateNewChart('{{ $chart->id }}', chartArgs);
            chartWorker.terminate();
            chartWorker = undefined;
        }, false);

        chartWorker.postMessage({
            'chartClass': '{{ $chart->class }}',
            'route': '{{ $chart->route }}',
            'chartArgs': chartArgs,
            'csrf': '{{ csrf_token() }}',
            'filters': filters
        });
    }
</script>
