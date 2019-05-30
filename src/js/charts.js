function convertFunctions(chartArgs)
{
    Object.keys(chartArgs).forEach((key) => {
        if (typeof chartArgs[key] === 'string') {
            if (chartArgs[key].includes('function')) {
                chartArgs[key] = new Function("return " + chartArgs[key])()
            }
        }

        if (typeof chartArgs[key] === 'object'){
            convertFunctions(chartArgs[key]);
        }
    });

    return chartArgs;
}

function convertDataSetOptions(dataset)
{
    dataset.forEach((object) => {
        Object.keys(object).forEach((key) => {
            if (key === 'options') {
                Object.assign(object, object['options']);
            }
        });
    });

    return dataset;
}

function generateNewChart(chartId, chartArgs)
{
    chartArgs = convertFunctions(chartArgs);

    const options = {
        series: convertDataSetOptions(chartArgs.datasets),
        labels: chartArgs.labels,
        grid: chartArgs.options.grid,
        legend: chartArgs.options.legend,
        title: chartArgs.options.title,
        tooltip: chartArgs.options.tooltip,
        xAxis: chartArgs.options.xAxis,
        yAxis: chartArgs.options.yAxis,
    };

    const echart = echarts.init(document.getElementById(chartId));

    echart.setOption(options);
}