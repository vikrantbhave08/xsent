// DASHBOARD STATASTICS TEST COUNT GRAPH

var options = {
    series: [{
        name: 'Food',
        data: [40, 50, 55, 55, 60, 55, 65, 75, 89, 55, 45, 60]
    }, {
        name: 'Stationery',
        data: [53, 32, 33, 52, 13, 44, 32, 56, 32, 87, 90, 67]
    },
    {
        name: 'Library',
        data: [45, 67, 98, 34, 67, 53, 32, 33, 52, 13, 44, 32]
    }],
    chart: {
        type: 'bar',
        height: 430
    },
    plotOptions: {
        bar: {
            vertical: true,
            dataLabels: {
                position: 'top',
            },
        }
    },
    dataLabels: {
        enabled: false,
        offsetX: -6,
        style: {
            fontSize: '12px',
            colors: ['#fff']
        }
    },
    stroke: {
        show: true,
        width: 1,
        colors: ['#fff']
    },
    tooltip: {
        shared: true,
        intersect: false

    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: {
        title: {
            text: 'AED'
        }
    },
};

var chart = new ApexCharts(document.querySelector("#financeStatistic"), options);
chart.render();


