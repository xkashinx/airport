/**
 * Created by jiachensun on 10/13/16.
 */
$(function () {

    // Set Highcharts UTC timezone to false
    Highcharts.setOptions({
        global: {
            timezoneOffset: 5 * 60
        }
    });

    // Initialize ambient chart
    $('#ambientChart').highcharts({
        credits: false,
        chart: {
            type: 'spline'
        },
        title: {
            text: "Ambient Temperature - Past 7 Days"
        },
        legend: {
            enabled: false
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {
                // hour: '%H:%M',
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Date'
            },
            minTickInterval: 24*3600*1000
        },
        yAxis: {
            title: {
                text: 'Temperature (℉)'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%H:%M %e. %b}: {point.y:.2f} F'
        },
        plotOptions: {
            spline: {
                marker: {
                    enabled: false
                }
            }
        },
        exporting: {
            buttons: {
                contextButton: {
                    menuItems: [{
                        textKey: 'printChart',
                        onclick: function () {
                            this.print();
                        }
                    }, {
                        separator: true
                    }, {
                        textKey: 'downloadPNG',
                        onclick: function () {
                            this.exportChart();
                        }
                    }, {
                        textKey: 'downloadJPEG',
                        onclick: function () {
                            this.exportChart({
                                type: 'image/jpeg'
                            });
                        }
                    }, {
                        textKey: 'downloadPDF',
                        onclick: function () {
                            this.exportChart({
                                type: 'application/pdf'
                            });
                        }
                    }]
                }
            }
        }
    });

    // Set series to ambient chart
    $( document ).ready( function() {
        $.getJSON("weekData.php?code=00", function(jsonDataAmbient) {
            var size = jsonDataAmbient[0].data.length;
            var min = 1000;
            var max = -1000;
            for (var i = 0; i < size; i++) {
                var temp = jsonDataAmbient[0].data[i][1];
                if(temp !== null) {
                    if(temp > max) {
                        max = temp;
                    }
                    if(temp < min) {
                        min = temp;
                    }
                }
            }
            var ambientChart = $("#ambientChart").highcharts();
            ambientChart.addSeries(jsonDataAmbient[0]);
            ambientChart.yAxis[0].addPlotLine({
                value: min,
                color: 'blue',
                dashStyle: 'shortdash',
                width: 2,
                label: {
                    text: "Minimum temperature = " + min + " ℉",
                    style: {
                        fontSize: '1.2em'
                    }
                }
            });
            ambientChart.yAxis[0].addPlotLine({
                value: max,
                color: 'red',
                dashStyle: 'shortdash',
                width: 2,
                label: {
                    text: "Maximum temperature = " + max + " ℉",
                    style: {
                        fontSize: '1.2em'
                    }
                }
            });
        });
    });

    // Initialize custom chart
    $('#customChart').highcharts({
        credits: false,
        chart: {
            type: 'spline'
        },
        title: {
            text: "Slab X, LED X Temperatures - Past 7 Days"
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                hour: '%H:%M',
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Date'
            },
            minTickInterval: 24*3600*1000
        },
        yAxis: {
            title: {
                text: 'Temperature (℉)'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%H:%M %e. %b}: {point.y:.2f} ℉'
        },
        plotOptions: {
            spline: {
                marker: {
                    enabled: false
                }
            }
        },
        exporting: {
            buttons: {
                contextButton: {
                    menuItems: [{
                        textKey: 'printChart',
                        onclick: function () {
                            this.print();
                        }
                    }, {
                        separator: true
                    }, {
                        textKey: 'downloadPNG',
                        onclick: function () {
                            this.exportChart();
                        }
                    }, {
                        textKey: 'downloadJPEG',
                        onclick: function () {
                            this.exportChart({
                                type: 'image/jpeg'
                            });
                        }
                    }, {
                        textKey: 'downloadPDF',
                        onclick: function () {
                            this.exportChart({
                                type: 'application/pdf'
                            });
                        }
                    }]
                }
            }
        }
    });

    // Set custom chart series on demand
    $(".slabElement").click(function() {
        $("#hiddenDiv").slideDown(700);
        $(".slabElement").removeClass("slabElementWhite");
        $(this).addClass("slabElementWhite");
        var code = $(this).attr("id").split("_")[1];
        $.getJSON("weekData.php?code=" + code, function(jsonData) {
            console.log(jsonData);
            var customChart = $('#customChart').highcharts();
            while(customChart.series.length > 0) {
                customChart.series[0].remove(true);
            }
            customChart.setTitle({text: "Slab " + code[0] + ", LED " + code[1] + " Temperatures - Past 7 Days"});
            customChart.addSeries(jsonData[0]);
            customChart.addSeries(jsonData[1]);
            customChart.addSeries(jsonData[2]);
            customChart.addSeries(jsonData[3]);
        });
    });

    // Close custom chart
    $("#closePlotBtn").click(function() {
        $("#hiddenDiv").slideUp(700);
        $(".slabElement").removeClass("slabElementWhite");
    });

    // Solve resize problem of custom chart
    $(window).resize(function() {
        width = $("#hiddenDiv").width();
        $("#customChart").css("width",width);
    });

    // Initialize Datetime Picker
    $.datetimepicker.setLocale("en");

    // // Datetime Picker Normal Version
    // $(".dateTime").datetimepicker({
    //     format: "Y-m-d H:i",
    //     step: 10
    // });

    // Datetime Picker Range Version
    $("#tableForm #startDateTime").datetimepicker({
        format: "Y-m-d H:i",
        step: 15,
        onShow: function (ct) {
            this.setOptions({
                maxDate: $("#tableForm #endDateTime").val()?$("#tableForm #endDateTime").val():false
            })
        },
        timePicker: false
    });
    $("#tableForm #endDateTime").datetimepicker({
        format: "Y-m-d H:i",
        step: 15,
        onShow: function (ct) {
            this.setOptions({
                minDate:$("#tableForm #startDateTime").val()?$("#tableForm #startDateTime").val():false
            })
        },
        timePicker: false
    });
    $("#chartForm #startDateTime").datetimepicker({
        format: "Y-m-d H:i",
        step: 15,
        onShow: function (ct) {
            this.setOptions({
                maxDate: $("#chartForm #endDateTime").val()?$("#chartForm #endDateTime").val():false
            })
        },
        timePicker: false
    });
    $("#chartForm #endDateTime").datetimepicker({
        format: "Y-m-d H:i",
        step: 15,
        onShow: function (ct) {
            this.setOptions({
                minDate:$("#chartForm #startDateTime").val()?$("#chartForm #startDateTime").val():false
            })
        },
        timePicker: false
    });
    // Datetime Picker Range Version END


    /**
     * validates if table form is valid
     * @param id
     * @returns {boolean}
     */
    function formValidation(id) {
        var valid = true;

        // Check if any code is selected
        var checkedCode = $(id + " input[name*='code']:checked").length;
        if(checkedCode === 0) {
            $(id+" .code_warning").show();
            $(id+" .code_warning").html("Please select at least one measurement source.");
            valid = false;
        }

        // Check if start date time is set
        if ($(id + " input[name='startDateTime']").val() === "") {
            $(id+" .startDateTime_warning").show();
            $(id+" .startDateTime_warning").html("Please set start date and time in YYYY-MM-DD hh:mm format.");
            valid = false;
        }

        // Check if end date time is set
        if ($(id + " input[name='endDateTime']").val() === "") {
            $(id + " .endDateTime_warning").show();
            $(id + " .endDateTime_warning").html("Please set end date and time in YYYY-MM-DD hh:mm format.");
            valid = false;
        } else if ($(id + " input[name='endDateTime']").val() < $(id + " input[name='startDateTime']").val()) {
            $(id + " .endDateTime_warning").show();
            $(id + " .endDateTime_warning").html("End time should be after start time.");
            valid = false;
        }

        // Check if any temp type is selected
        var checkedType = $(id + " input[name*='tempType']:checked").length;
        if(checkedType === 0) {
            $(id+" .tempType_warning").show();
            $(id + " .tempType_warning").html("Please select at least one temperature type.");
            valid = false;
        }

        return valid;
    }

    // Submit form and Set Datatables data
    var form = $("#tableForm");
    $(form).submit(function(event) {
        event.preventDefault();
        $("#tableForm .warning").html("");
        $("#tableForm .warning").hide();
        if (formValidation("#tableForm")) {
            var formData = $(form).serialize();
            $.ajax({
                url: "tableData.php",
                data: formData,
                type: "GET",
                success: function(returnData) {
                    var browser = detectBrowser().split(" ")[0];
                    if(browser == "Safari") {
                        $("#tableWarning").css("height", "30px");
                        $("#formTable").css("margin-bottom", "20px");
                        $("#tableWarning").fadeIn();
                    }
                    var data = JSON.parse(returnData);
                    var tempType = data["tempType"];
                    var tempData = data["tempData"];
                    var columnString = "[{'columns': [{ title: 'Date (yyyy-mm-dd)'}, {title: 'Time'}, {title: 'Read From'}";
                    if ($.inArray( "T1", tempType ) !== -1) {
                        columnString += ", {title: 'T1 (℉)'}";
                    }
                    if ($.inArray( "T2", tempType ) !== -1) {
                        columnString += ", {title: 'T2 (℉)'}";
                    }
                    if ($.inArray( "T3", tempType ) !== -1) {
                        columnString += ", {title: 'T3 (℉)'}";
                    }
                    if ($.inArray( "T4", tempType ) !== -1) {
                        columnString += ", {title: 'T4 (℉)'}";
                    }
                    columnString += "]}]";
                    var columnObject = eval(columnString);
                    if ( $.fn.DataTable.isDataTable( "#exampleTable" )) {
                        $("#exampleTable").DataTable().destroy();
                        $("#exampleTable").empty();
                    }
                    $("#exampleTable").DataTable({
                        "data": tempData,
                        "columns" : columnObject[0].columns,
                        lengthMenu: [
                            [ 10, 25, 50, 100],
                            [ '10 rows', '25 rows', '50 rows', '100 rows' ]
                        ],
                        dom: "Bfrtip",
                        buttons: [
                            "pageLength", "excel", "pdf", "csv", "print"
                        ],
                        autoWidth: false
                    });
                }
            });
        }
    });

    /**
     * Detects which browser is in use
     * @returns {string} Browser name
     */
    function detectBrowser(){
        var ua= navigator.userAgent, tem,
            M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1])){
            tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
            return 'IE '+(tem[1] || '');
        }
        if(M[1]=== 'Chrome'){
            tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
            if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
        }
        M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
        if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
        return M.join(' ');
    }

    // Initialize form custom chart
    $('#formChart').highcharts({
        credits: false,
        chart: {
            type: 'spline'
        },
        title: {
            text: "Custom Plot"
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                hour: '%H:%M',
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Temperature (℉)'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%H:%M %e. %b}: {point.y:.2f} ℉'
        },
        plotOptions: {
            spline: {
                marker: {
                    enabled: false
                }
            }
        },
        exporting: {
            buttons: {
                contextButton: {
                    menuItems: [{
                        textKey: 'printChart',
                        onclick: function () {
                            this.print();
                        }
                    }, {
                        separator: true
                    }, {
                        textKey: 'downloadPNG',
                        onclick: function () {
                            this.exportChart();
                        }
                    }, {
                        textKey: 'downloadJPEG',
                        onclick: function () {
                            this.exportChart({
                                type: 'image/jpeg'
                            });
                        }
                    }, {
                        textKey: 'downloadPDF',
                        onclick: function () {
                            this.exportChart({
                                type: 'application/pdf'
                            });
                        }
                    }]
                }
            }
        }
    });

    // Submit form and Set custom chart data
    var chartForm = $("#chartForm");
    $(chartForm).submit(function(event) {
        event.preventDefault();
        $("#chartForm .warning").html("");
        $("#chartForm .warning").hide();
        if(formValidation("#chartForm")) {
            $("#hiddenDiv2").slideDown(700);
            var formData = $(chartForm).serialize();
            $.ajax({
                url: "chartData.php",
                data: formData,
                type: "GET",
                success: function(returnData) {
                    var formChart = $("#formChart").highcharts();
                    while(formChart.series.length > 0) {
                        formChart.series[0].remove(true);
                    }
                    var data = JSON.parse(returnData);
                    for (var i = 0; i < data.length; i++) {
                        formChart.addSeries(data[i]);
                    }
                }
            });
        }
    });

    // Close form chart
    $("#closePlotBtn2").click(function() {
        $("#hiddenDiv2").slideUp(700);
    });
    
    // Back to Top
    $("#go-top-div").click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 700);
    })
});