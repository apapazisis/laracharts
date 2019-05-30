onmessage = (e) =>
{
    var chartId = e.data.chartId;
    var chartClass = e.data.chartClass;
    var route = e.data.route;
    var token = e.data.csrf;
    var filters = e.data.filters;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", route, true);
    xhr.setRequestHeader("X-CSRF-TOKEN", token);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(e)
    {
        if (xhr.status === 200 && xhr.readyState === 4) {
            var response = JSON.parse(xhr.response);

            postMessage({"chartArgs": response});
        }
    };

    xhr.send("chartClass=" + chartClass + "&" + filters);
};
