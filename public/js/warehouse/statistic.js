axios.get(location.origin + '/axios/statistics/diagram1')
    .then(function (response) {
        var label = [];
        var dataSaleExcludedTotal = [];
        var dataSaleIncludedTotal = [];
        var totalReceipt = [];
        $('#loadingDiagram1').removeAttr("style").hide();
        $('#amlinechart1').show();

        response.data.forEach(function (item) {
            label.push(item.month);
            totalReceipt.push(item.total_receipts);
            dataSaleExcludedTotal.push(item.sale_excluded_total);
            dataSaleIncludedTotal.push(item.sale_included_total);

        });
        var ctx = document.getElementById('amlinechart1').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                    label: 'Sale Excluded Price',
                    data: dataSaleExcludedTotal,
                    backgroundColor: [
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)'
                    ],
                    borderColor: [
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)'
                    ],
                    borderWidth: 1,
                    hoverBorderWidth: 3,
                },
                    {
                        label: 'Sale Included Price',
                        data: dataSaleIncludedTotal,
                        backgroundColor: [
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)',
                            'rgba(255,0,81,0.5)'

                        ],
                        borderColor: [
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)',
                            'rgb(255,0,0)'
                        ],
                        borderWidth: 1,
                        hoverBorderWidth: 3,
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Total Revenue From This Month Last Year To This Month'
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Vnd",
                            fontFamily: "TimeNewRoman",
                            fontColor: "black",
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Month/Year",
                            fontFamily: "TimeNewRoman",
                            fontColor: "black",
                        },
                    }]
                }
            }
        });
    }).catch(function (error) {
        $('#loadingDiagram1').removeAttr("style").hide();
        $('#amlinechart1').show();
    });



axios.get(location.origin + '/axios/statistics/diagram2')
    .then(function (response) {
        $('#loadingDiagram2').removeAttr("style").hide();
        $('#amlinechart2').show();
        var label = [];
        var data = [];
        var color = [];
        for (i = 0; i<10; i++){
            label.push(response.data.data[i].name);
            data.push(response.data.data[i].total_product_percent);
            color.push(getRandomColor());
        }
       /* response.data.data.forEach(function (item) {
            label.push(item.name);
            data.push(item.total_product_percent);
            color.push(getRandomColor());
        });
        */
        var ctx = document.getElementById('amlinechart2').getContext('2d');
        var myChart = new Chart(document.getElementById('amlinechart2'), {
            type: 'pie',
            data: {
                labels: label,
                datasets: [{
                    label: "(%)",
                    backgroundColor: color,
                    data: data,
                    borderWidth : 1,
                    hoverBorderColor: '2FF8F5F5'
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Top 10 - Percent Of Products Selled Last Month'
                },
                legend: {
                    position: 'right'
                }
            }
        });
    }).catch(function (error) {
        $('#loadingDiagram2').removeAttr("style").hide();
        $('#amlinechart2').show();
});


function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}


axios.get(location.origin + '/axios/statistics/diagram3')
    .then(function (response) {
        console.log(response);
        var label = [];
        var dataTotal = [];
        var totalSchedules = [];
        $('#loadingDiagram3').removeAttr("style").hide();
        $('#amlinechart3').show();

        response.data.forEach(function (item) {
            label.push(item.month);
            totalSchedules.push(item.total_schedules);
            dataTotal.push(item.total);
        });
        var ctx = document.getElementById('amlinechart3').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: 'Total Hours',
                    data: dataTotal,
                    backgroundColor: [
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)',
                        'rgba(43,231,13,0.5)'
                    ],
                    borderColor: [
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)',
                        'rgb(43,231,13)'
                    ],
                    borderWidth: 1,
                    hoverBorderWidth: 3,
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Total Hours From This Month Last Year To This Month'
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Hours",
                            fontFamily: "TimeNewRoman",
                            fontColor: "black",
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Month/Year",
                            fontFamily: "TimeNewRoman",
                            fontColor: "black",
                        },
                    }]
                }
            }
        });
    }).catch(function (error) {
    $('#loadingDiagram3').removeAttr("style").hide();
    $('#amlinechart3').show();
});
