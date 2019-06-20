function convertFunctions(chartArgs)
{
    Object.keys(chartArgs).forEach((key) => {
        if (typeof chartArgs[key] === "string") {
            if (chartArgs[key].includes("function")) {
                chartArgs[key] = new Function("return " + chartArgs[key])();
            }
        }

        if (typeof chartArgs[key] === "object"){
            convertFunctions(chartArgs[key]);
        }
    });

    return chartArgs;
}

function generateNewChart(chartId, chartArgs)
{
    chartArgs = convertFunctions(chartArgs);

    window[chartId + "Options"] = chartArgs;

    const echart = echarts.init(document.getElementById(chartId));

    echart.setOption(chartArgs);
}
