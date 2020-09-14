"use strict";

var KTDashboard = function() {
    var initTable = function () {
        var datatable = $('#tablegrid').KTDatatable({
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: base_url+'/home/datasensus',
                        method: 'GET',
                        map: function(raw) {
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                saveState: {
                    webstorage: false
                },
                pageSize: 10,
                bLengthChange: false,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },
            layout: {
                scroll: false,
                footer: false,
            },
            sortable: true,
            pagination: true,
            search: {
                input: $('#searchTerm'),
            },
            toolbar:{
                items: {
                    pagination: {
                        pageSizeSelect: [10, 20]
                    }
                }
            },
            columns: [
                {
                    field: 'alamat',
                    title: 'Alamat KK',
                    autoHide: false,
                }, {
                    field: 'wilayah',
                    title: 'Wilayah',
                }, { 
                    title: 'Jml Jiwa', 
                    field: 'jml_jiwa',
                }, { 
                    title: 'Tanggal Dibuat', 
                    field: 'create_date',
                }, { 
                    title: 'Status', 
                    field: 'status_sensus',
                    autoHide: false,
                    template: function(row) {
                        var status = {
                              1: {'title': 'Valid', 'class': 'kt-badge--success'},
                              2: {'title': 'NotValid', 'class': 'kt-badge--warning'},
                              3: {'title': 'Anomali', 'class': 'kt-badge--info'},
                              4: {'title': 'Anulir', 'class': ' kt-badge--danger'},
                            };

                          

                         return '<span class="kt-badge ' + status[row.status_sensus_id].class + ' kt-badge--inline">' + row.status_sensus + '</span>';
                    }
                },
            ],
        });
    }
    
    var dailySumChart = function() {
        
        var xhr = $.ajax({
            url: base_url + '/home/dailysumdata',
            method: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
            
            var chartContainer = KTUtil.getByID('dailysumchart');
            if (!chartContainer) {
                return;
            }
            
            var chart = new Chart(chartContainer, {
                type: 'bar',
                data: response,
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Bar Chart'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            // toastr.error('dailySumChart: ' + jqXHR.statusText);
        })
        .always(function() {});

        
    }
    
    var statusSensusChart = function() {
        
        var xhr = $.ajax({
            url: base_url + '/home/statussensus',
            method: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
            // console.log(response);
            if (typeof response.customdata.totaldata !== 'undefined') {
                $('#totaldatasensus').html(response.customdata.totaldata);
            }
            var chartContainer = KTUtil.getByID('statussensuschart');
            if (!chartContainer) {
                return;
            }
            
            var chart = new Chart(chartContainer, {
                type: 'doughnut',
                data: response,
                options: {
                    responsive: true,
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: false
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            // toastr.error('statusSensusChart: ' + jqXHR.statusText);
        })
        .always(function() {});
            
        var chartContainer = KTUtil.getByID('dailysumchart');

        
    }
    
 //    var statPendata = function() {
        
 //        var xhr = $.ajax({
 //            url: base_url + '/home/statpendata',
 //            method: 'GET',
 //            dataType: 'json',
 //        })
 //        .done(function(response) {
 //            if(response) {
 //                console.log(response);
 //                $('#totalpendata').html(response.total);
 //                response.data.forEach(el => {
 //                    if (el.TingkatWilayahID===null) {
 //                        $('#pendatatw0').html(el.total);
 //                    } else {
 //                        $('#pendatatw'+el.TingkatWilayahID).html(el.total);
 //                    }
 //                });
 //            }
 //        })
 //        .fail(function(jqXHR, textStatus, errorThrown) {
 //            console.log('jqXHR', jqXHR);
 //            // toastr.error('dailySumChart: ' + jqXHR.statusText);
 //        })
 //        .always(function() {});

        
    // }


    var chart1 = function() {
        
         KTApp.block("#chart1", {
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Please Wait..."
            });

         KTApp.block("#headerPortlet", {
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Please Wait..."
            });

        var xhr = $.ajax({
            url: base_url + '/home/chart1',
            method: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
             
            KTApp.unblock("#chart1");
            KTApp.unblock("#headerPortlet");

            var div = document.createElement("div");
            var ddv =  document.getElementById("prosens");
            div.style.width = response.prosen;
            div.style.background = "red";

            console.log(ddv);

            if (ddv != null ) {
                document.getElementById("prosens").appendChild(div);    
            } 
            

            $("#targetchart1").html(response.target);
            $("#targetHeader").html(response.target);

            $("#terdatachart1").html(response.terdata);
            $("#terdataHeader").html(response.terdata);

            $("#prosenchart1").html(response.prosen);

            var chartContainer = KTUtil.getByID('kt_chart_order_statistics1');
            if (!chartContainer) {
                return;
            }
            
            var a = Chart.helpers.color,
                            t = {
                                labels: response.labels,
                                datasets: [{
                                    fill: !0,
                                    backgroundColor: a(KTApp.getStateColor("brand")).alpha(.6).rgbString(),
                                    borderColor: a(KTApp.getStateColor("brand")).alpha(0).rgbString(),
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 12,
                                    pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointHoverBackgroundColor: KTApp.getStateColor("brand"),
                                    pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                                    data: response.data1
                                }, {
                                    fill: !0,
                                    backgroundColor: a(KTApp.getStateColor("success")).alpha(.2).rgbString(),
                                    borderColor: a(KTApp.getStateColor("success")).alpha(0).rgbString(),
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 12,
                                    pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointHoverBackgroundColor: KTApp.getStateColor("success"),
                                    pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                                    data: response.data2
                                },]
                            },
                            i = chartContainer.getContext("2d");
                        new Chart(i, {
                            type: "line",
                            data: t,
                            options: {
                                responsive: !0,
                                maintainAspectRatio: !1,
                                legend: !1,
                                scales: {
                                    xAxes: [{
                                        categoryPercentage: .35,
                                        barPercentage: .7,
                                        display: !0,
                                        scaleLabel: {
                                            display: !1,
                                            labelString: "Month"
                                        },
                                        gridLines: !1,
                                        ticks: {
                                            display: !0,
                                            beginAtZero: !0,
                                            fontColor: KTApp.getBaseColor("shape", 3),
                                            fontSize: 13,
                                            padding: 10
                                        }
                                    }],
                                    yAxes: [{
                                        categoryPercentage: .35,
                                        barPercentage: .7,
                                        display: !0,
                                        scaleLabel: {
                                            display: !1,
                                            labelString: "Value"
                                        },
                                        gridLines: {
                                            color: KTApp.getBaseColor("shape", 2),
                                            drawBorder: !1,
                                            offsetGridLines: !1,
                                            drawTicks: !1,
                                            borderDash: [3, 4],
                                            zeroLineWidth: 1,
                                            zeroLineColor: KTApp.getBaseColor("shape", 2),
                                            zeroLineBorderDash: [3, 4]
                                        },
                                        ticks: {
                                            max: response.max,
                                            stepSize: response.stepSize,
                                            display: !0,
                                            beginAtZero: !0,
                                            fontColor: KTApp.getBaseColor("shape", 3),
                                            fontSize: 13,
                                            padding: 10
                                        }
                                    }]
                                },
                                title: {
                                    display: !1
                                },
                                hover: {
                                    mode: "index"
                                },
                                tooltips: {
                                    enabled: !0,
                                    intersect: !1,
                                    mode: "nearest",
                                    bodySpacing: 5,
                                    yPadding: 10,
                                    xPadding: 10,
                                    caretPadding: 0,
                                    displayColors: !1,
                                    backgroundColor: KTApp.getStateColor("brand"),
                                    titleFontColor: "#ffffff",
                                    cornerRadius: 4,
                                    footerSpacing: 0,
                                    titleSpacing: 0
                                },
                                layout: {
                                    padding: {
                                        left: 0,
                                        right: 0,
                                        top: 5,
                                        bottom: 5
                                    }
                                }
                            }
                        });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            // toastr.error('statusSensusChart: ' + jqXHR.statusText);
        })
        .always(function() {  
            KTApp.unblock("#chart1");
            KTApp.unblock("#headerPortlet");            
        });
                    
    }

    

    var chart2 = function() {
        
         KTApp.block("#chart2", {
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Please Wait..."
            });

          KTApp.block("#headerPortlet", {
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Please Wait..."
            });

        var xhr = $.ajax({
            url: base_url + '/home/chart2',
            method: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
             //console.log(response);
            //if (typeof response.customdata.totaldata !== 'undefined') {
            //    $('#totaldatasensus').html(response.customdata.totaldata);
            //}
             KTApp.unblock("#chart2");
             KTApp.unblock("#headerPortlet");
             
            $("#jml_valid").html(response.jml_valid);
            $("#validHeader").html(response.jml_valid);
            
            $("#jml_notvalid").html(response.jml_notvalid);
            $("#jml_anomali").html(response.jml_anomali);
            $("#jml_anulir").html(response.jml_anulir);
            $("#anomaliHeader").html(response.totalanomali);

            var chartContainer = KTUtil.getByID('kt_chart_order_statistics2');
            if (!chartContainer) {
                return;
            }
            
            var a = Chart.helpers.color,
                            t = {
                                labels: response.labels,
                                datasets: [{
                                    fill: !0,
                                    backgroundColor: a(KTApp.getStateColor("success")).alpha(.6).rgbString(),
                                    borderColor: a(KTApp.getStateColor("success")).alpha(0).rgbString(),
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 12,
                                    pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointHoverBackgroundColor: KTApp.getStateColor("success"),
                                    pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                                    data: response.data1
                                }, {
                                    fill: !0,
                                    backgroundColor: a(KTApp.getStateColor("danger")).alpha(.5).rgbString(),
                                    borderColor: a(KTApp.getStateColor("danger")).alpha(0).rgbString(),
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 12,
                                    pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointHoverBackgroundColor: KTApp.getStateColor("danger"),
                                    pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                                    data: response.data2
                                },{
                                    fill: !0,
                                    backgroundColor: a(KTApp.getStateColor("warning")).alpha(.4).rgbString(),
                                    borderColor: a(KTApp.getStateColor("warning")).alpha(0).rgbString(),
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 12,
                                    pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointHoverBackgroundColor: KTApp.getStateColor("warning"),
                                    pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                                    data: response.data3
                                },{
                                    fill: !0,
                                    backgroundColor: a(KTApp.getStateColor("info")).alpha(.2).rgbString(),
                                    borderColor: a(KTApp.getStateColor("info")).alpha(0).rgbString(),
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 12,
                                    pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                                    pointHoverBackgroundColor: KTApp.getStateColor("info"),
                                    pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                                    data: response.data4
                                },]
                            },
                            i = chartContainer.getContext("2d");
                        new Chart(i, {
                            type: "line",
                            data: t,
                            options: {
                                responsive: !0,
                                maintainAspectRatio: !1,
                                legend: !1,
                                scales: {
                                    xAxes: [{
                                        categoryPercentage: .35,
                                        barPercentage: .7,
                                        display: !0,
                                        scaleLabel: {
                                            display: !1,
                                            labelString: "Month"
                                        },
                                        gridLines: !1,
                                        ticks: {
                                            display: !0,
                                            beginAtZero: !0,
                                            fontColor: KTApp.getBaseColor("shape", 3),
                                            fontSize: 13,
                                            padding: 10
                                        }
                                    }],
                                    yAxes: [{
                                        categoryPercentage: .35,
                                        barPercentage: .7,
                                        display: !0,
                                        scaleLabel: {
                                            display: !1,
                                            labelString: "Value"
                                        },
                                        gridLines: {
                                            color: KTApp.getBaseColor("shape", 2),
                                            drawBorder: !1,
                                            offsetGridLines: !1,
                                            drawTicks: !1,
                                            borderDash: [3, 4],
                                            zeroLineWidth: 1,
                                            zeroLineColor: KTApp.getBaseColor("shape", 2),
                                            zeroLineBorderDash: [3, 4]
                                        },
                                        ticks: {
                                            max: response.max,
                                            stepSize: response.stepSize,
                                            display: !0,
                                            beginAtZero: !0,
                                            fontColor: KTApp.getBaseColor("shape", 3),
                                            fontSize: 13,
                                            padding: 10
                                        }
                                    }]
                                },
                                title: {
                                    display: !1
                                },
                                hover: {
                                    mode: "index"
                                },
                                tooltips: {
                                    enabled: !0,
                                    intersect: !1,
                                    mode: "nearest",
                                    bodySpacing: 5,
                                    yPadding: 10,
                                    xPadding: 10,
                                    caretPadding: 0,
                                    displayColors: !1,
                                    backgroundColor: KTApp.getStateColor("brand"),
                                    titleFontColor: "#ffffff",
                                    cornerRadius: 4,
                                    footerSpacing: 0,
                                    titleSpacing: 0
                                },
                                layout: {
                                    padding: {
                                        left: 0,
                                        right: 0,
                                        top: 5,
                                        bottom: 5
                                    }
                                }
                            }
                        });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            // toastr.error('statusSensusChart: ' + jqXHR.statusText);
        })
        .always(function() {
             KTApp.unblock("#chart2");
             KTApp.unblock("#headerPortlet");
        });
            
        var chartContainer = KTUtil.getByID('dailysumchart');

        
    }
    var chart3 = function() {
        KTApp.block("#chart3", {
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Please Wait..."
            });

        var xhr = $.ajax({
            url: base_url + '/home/dailysumdata',
            method: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
            console.log(response.labels);
            
            KTApp.unblock("#chart3");

            var chartContainer = KTUtil.getByID("kt_chart_sales_stats");
            if (!chartContainer) {
                return;
            }
            
            var e = {
                       type: "line",
                       data: {                            
                            labels: response.labels,
                            datasets: [{
                                label: "",
                                borderColor: KTApp.getStateColor("brand"),
                                borderWidth: 2,
                                backgroundColor: KTApp.getStateColor("brand"),
                                pointBackgroundColor: Chart.helpers.color("#ffffff").alpha(0).rgbString(),
                                pointBorderColor: Chart.helpers.color("#ffffff").alpha(0).rgbString(),
                                pointHoverBackgroundColor: KTApp.getStateColor("danger"),
                                pointHoverBorderColor: Chart.helpers.color(KTApp.getStateColor("danger")).alpha(.2).rgbString(),
                                data: response.datasets[0].data,
                            }]
                        },
                        options: {
                            title: {
                                display: !1
                            },
                            tooltips: {
                                intersect: !1,
                                mode: "nearest",
                                xPadding: 10,
                                yPadding: 10,
                                caretPadding: 10
                            },
                            legend: {
                                display: !1,
                                labels: {
                                    usePointStyle: !1
                                }
                            },
                            responsive: !0,
                            maintainAspectRatio: !1,
                            hover: {
                                mode: "index"
                            },
                            scales: {
                                xAxes: [{
                                    display: !1,
                                    gridLines: !1,
                                    scaleLabel: {
                                        display: !0,
                                        labelString: "Month"
                                    }
                                }],
                                yAxes: [{
                                    display: !1,
                                    gridLines: !1,
                                    scaleLabel: {
                                        display: !0,
                                        labelString: "Value"
                                    }
                                }]
                            },
                            elements: {
                                point: {
                                    radius: 3,
                                    borderWidth: 0,
                                    hoverRadius: 8,
                                    hoverBorderWidth: 2
                                }
                            }
                        }
                    };
                    new Chart(KTUtil.getByID("kt_chart_sales_stats"), e)

                    initTableChart3();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            // toastr.error('statusSensusChart: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblock("#chart3");
        });
            
        var chartContainer = KTUtil.getByID('dailysumchart');

        
    }


    var initTableChart3 = function () {
        var datatable = $('#tablegridchart3').KTDatatable({
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: base_url+'/home/dailysumdataTable',
                        method: 'GET',
                        map: function(raw) {
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                saveState: {
                    webstorage: false
                },
                pageSize: 10,
                bLengthChange: false,
                serverPaging: true,
                serverFiltering: false,
                serverSorting: false,
            },
            layout: {
                scroll: true,
                footer: false,
            },
            sortable: true,
            pagination: true,
            search: {
                input: $('#searchTerm'),
            },
            toolbar:{
                items: {
                    pagination: {
                        pageSizeSelect: [10]
                    }
                }
            },
            columns: [
                { 
                    title: 'Tanggal Dibuat', 
                    field: 'create_date',
                }, { 
                    title: 'Data Masuk', 
                    field: 'cnt',
                    autoHide: false,
                },
            ],
        });
    }
    
    return {
        init: function() {
            initTable();
            //initTableChart3();
            dailySumChart();
            statusSensusChart();
            //statPendata();
            chart1();
            chart2();
            chart3();
        }
    };
}();

jQuery(document).ready(function() {
    KTDashboard.init();
});