function setLineGraph($graph){
    
    $chart_type = (typeof $graph.chart_type !== 'undefined') ? $graph.chart_type : 'column';
    
    var yLabel = '';
    var yPercent = '';
    if($graph.yAxis_label == '%')
    {
        yLabel = '{value}%';
        yPercent = '%';
    }

    var options = {
        chart: {
            type: $chart_type
        },
        title: {
           text: $graph.title
        },
        xAxis: {
            
            categories : $graph.xAxis_category
            
	    },
        yAxis: {
            title: {
                text: $graph.xAxis_title
            },
            labels: {
                format: yLabel
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.y}'+yPercent+'</b>',
                    crop: false,
                    overflow: 'none'
                }
            },
             
        },credits: {
            enabled: false
        }, 
		/*plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },
		
    },*/
	    exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    text: 'Export',
                    symbolFill: '#f88',
                    symbolStroke: '#666666'
                }
            }
        },
        credits: {
            enabled: false
        },
	    series: [{ 
            data: $graph.series_data
        }],
        tooltip: {
            headerFormat: '<b>{point.x}</b> : ',
            pointFormat: '{point.y}'+yPercent
        },

    };

     if($graph.xAxis_category != '' && $graph.xAxis_category != 0 && $graph.xAxis_category != null) {
        $('#'+$graph.div_id).highcharts(options);
    } else {
        $('#'+$graph.div_id).highcharts({
            title: {
                text: $graph.title
            },credits: {
        enabled: false
        },
        //options
        },function(chart) { 
            if (chart.series.length < 1) {
                // labels = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 420, 150)
                //     .css({
                //         color: '#4572A7', 
                //         fontSize: '16px'
                //     })
                // .add();

                text = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 420, 150)
                    .css({
                        color: '#333333', 
                        fontSize: '16px'
                    })
                .add();
                textBBox = text.getBBox();
                x = chart.plotLeft + (chart.plotWidth  * 0.5) - (textBBox.width  * 0.5);
                y = chart.plotTop  + (chart.plotHeight * 0.5) - (textBBox.height * 0.5);
                text.attr({x: x, y: y});
            }
        });
    }
}

function stackedColumnChart($graph, $colorCount){
    var stackedColor='';
    var gnrtColor='';
    if($colorCount){
        var gnrtColor = generateColor('#000074','#a6e6ff',$colorCount);
        var stackedColor = Array.from(gnrtColor);
    }
    Highcharts.chart($graph.div_id, {
        colors: stackedColor,
        chart: {
            type: 'column'
        },
        title: {
            text: $graph.title
        },
        xAxis: {
            categories: $graph.xAxis_category
        },
        yAxis: {
            min: 0,
            title : $graph.yAxis_title,
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },credits: {
        enabled: false
      },
        legend: {
           /* align: 'right',
            x: 0,
            verticalAlign: 'bottom',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false*/
			align: 'right',
			verticalAlign: 'top',
			layout: 'vertical',
			backgroundColor: '#F6F6F6',
			x: 0,
			y: 50,
			padding: 5,
			itemHoverStyle: {
				color: '#FF0000'
			}
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    text: 'Export',
                    symbolFill: '#f88',
                    symbolStroke: '#666666'
                }
            }
        },
		credits: {
            enabled: false
        },
        series : $graph.series_data
    }, function(chart) { // on complete
         if (chart.series.length < 1) {
        //     labels = chart.renderer.text('No Data Available', 420, 150)
        //     .css({
        //         color: '#4572A7',
        //         fontSize: '16px'
        //     })
        //     .add();
            text = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 420, 150)
                        .css({
                            color: '#4572A7', 
                            fontSize: '16px'
                        })
                    .add();
            textBBox = text.getBBox();
            x = chart.plotLeft + (chart.plotWidth  * 0.5) - (textBBox.width  * 0.5);
            y = chart.plotTop  + (chart.plotHeight * 0.5) - (textBBox.height * 0.5);
            text.attr({x: x, y: y});
         }


    });
}


function setMultipleLineGraph($graph, $colorCount){
    var series = [];
    //categories = [];
    var MultipleLineColor='';
    var gnrtColor='';
    $colorCount = (typeof $colorCount=='undefined') ? 3 : $colorCount;
    if($colorCount){
        var gnrtColor = generateColor('#000074','#a6e6ff',$colorCount);
        var MultipleLineColor = Array.from(gnrtColor);
    }

    var xAxis_category_id = (typeof $graph.xAxis_category_id !== 'undefined') ? $graph.xAxis_category_id : new Array();
    
    var objData = $graph.series_data;
    for (var key in objData) {
        var obj = objData[key];
        //categories.push(key);
        for (var item in obj) {
            var targetSeries ;
            var bFound = false;
            for(var i=0; i< series.length; i++){
                if(key == series[i].name){
                    bFound = true;
                   targetSeries = series[i];
                }
            }
            if(!bFound) {
                targetSeries = {'name': key, 'data':[]};
                series.push(targetSeries);
            }
            var val = parseFloat(obj[item]);
            targetSeries.data.push(val);
        }
    }
    var yTitle = '';
    var yLabel = '';
    var yPercent = '';
    if($graph.yAxis_title != '')
    {
        yTitle = $graph.yAxis_title;
    }

    if($graph.yAxis_label == '%')
    {
        yLabel = '{value}%';
        yPercent = '%';
    }
    
    var options = {
        colors: MultipleLineColor,
        chart: {
                type: $graph.chart_type
            },
            title: {
                text: $graph.title
            },
        xAxis: {
                categories: $graph.xAxis_category
            },
        yAxis: {
            min: 0,
            title: {
                text: yTitle
            },
            labels: {
                format: yLabel
            }
        },
        series: series,
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.y}'+yPercent+'</b>',
                }
            },
            series: {
                cursor: 'pointer',
                point: {
                  events: {
                  click: function(e){
                      showGraphDetails($graph.title,this.category,xAxis_category_id[this.x],e.point.series.name);
                  }
                }
              }
            }            
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    text: 'Export',
                    symbolFill: '#f88',
                    symbolStroke: '#666666'
                }
            }
        },credits: {
        enabled: false
      },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br>',
            pointFormat: '{series.name}: {point.y}'+yPercent
        },

    }
    
    //$('#'+$graph.div_id).highcharts(options);
    if($graph.xAxis_category != '' && $graph.xAxis_category != 0) {
        $('#'+$graph.div_id).highcharts(options);
    } else {
        $('#'+$graph.div_id).highcharts({
            title: {
                text: $graph.title
            },
        //options
        },function(chart) { 
            if (chart.series.length < 1) {
                labels = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 425, 150)
                    .css({
                        color: '#4572A7', 
                        fontSize: '16px'
                    })
                .add();
            }
        });  
    }
}

function setPieGraph($graph, $colorCount){
    var PieColor='';
    var gnrtColor='';
    if($colorCount){
        var gnrtColor = generateColor('#000074','#a6e6ff',$colorCount);
        var PieColor = Array.from(gnrtColor);
    }
    var options = {
            colors: PieColor,
            chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: $graph.title
        },
        legend: {
            align: 'right',
            verticalAlign: 'top',
            layout: 'vertical',
            backgroundColor: '#F6F6F6',
            x: 0,
            y: 50,
            padding: 5,
            itemHoverStyle: {
                color: '#FF0000'
            },
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}({point.percentage:.1f}%)</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                showInLegend: true,
            }
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    text: 'Export',
                    symbolFill: '#f88',
                    symbolStroke: '#666666'
                }
            }
        },credits: {
        enabled: false
        },
        series: [{
            name: $graph.xAxis_title,
            colorByPoint: true,
            data: $graph.pie_data
        }]
    }
    
    if($graph.pie_data.length > 0) {
        $('#'+$graph.div_id).highcharts(options);
    } else {
        $('#'+$graph.div_id).highcharts({
            title: {
                text: $graph.title
            },
        //options
        },function(chart) { 
            if (chart.series.length < 1) {
                // labels = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 420, 150)
                //     .css({
                //         color: '#4572A7', 
                //         fontSize: '16px'
                //     })
                // .add();

                text = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 420, 150)
                    .css({
                        color: '#4572A7', 
                        fontSize: '16px'
                    })
                .add();
                textBBox = text.getBBox();
                x = chart.plotLeft + (chart.plotWidth  * 0.5) - (textBBox.width  * 0.5);
                y = chart.plotTop  + (chart.plotHeight * 0.5) - (textBBox.height * 0.5);
                text.attr({x: x, y: y});
            }
        });  
    }
}

  function stackedAndGroupedGraph($graph,$show_image=''){
  var options = {
        chart: {
            type: $graph.chart_type 
        },
        title: {
           text: $graph.title
        },
        xAxis: {
            categories : $graph.xAxis_category,
            title: {
                text: $graph.xAxis_title,
                //enabled: false
            },
            labels: {
                //enabled: false
              },
        },
        yAxis: {
            title: {
                text: $graph.yAxis_title,
                //enabled: false
            },

        },
        legend: {
            enabled: true,

        },
        
        tooltip: {
        formatter: function () {
          // return '<b>' + this.x + '</b><br/>' +
          //   this.series.name + ': ' + this.y + '<br/>' +
          //   'Total: ' + this.point.stackTotal;

            return '<b>' + this.x + '</b><br/>' +
            this.series.name + ': ' + this.y;
        }
      },

      plotOptions: {
        column: {
          stacking: 'normal'
        }
      },

      credits: {
            enabled: false
      }, 

      exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    text: 'Export',
                    symbolFill: '#f88',
                    symbolStroke: '#666666'
                }
            }
        },

       series:$graph.series_data ,
    };
    if($graph.xAxis_category != '' && $graph.xAxis_category != 0 && $graph.xAxis_category != null) {
        $('#'+$graph.div_id).highcharts(options);
    } else {
        $('#'+$graph.div_id).highcharts({
            title: {
                text: $graph.title
            },credits: {
        enabled: false
        },
        },function(chart) { 
            if (chart.series.length < 1) {
                text = chart.renderer.text('<span class="pieMkCntr">No Data Available</span>', 420, 150)
                    .css({
                        color: '#333333', 
                        fontSize: '16px'
                    })
                .add();
                textBBox = text.getBBox();
                x = chart.plotLeft + (chart.plotWidth  * 0.5) - (textBBox.width  * 0.5);
                y = chart.plotTop  + (chart.plotHeight * 0.5) - (textBBox.height * 0.5);
                text.attr({x: x, y: y});
            }
        });  
    }

    if($show_image=='Y'){
        generateGraphImage($graph);
    } 

  }