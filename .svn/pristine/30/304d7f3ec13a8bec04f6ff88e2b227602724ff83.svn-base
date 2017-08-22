function init(){
	initButtonLoad('botonFF','botonEE');
}

var imgNot_found ='<div class="img-search"><img src="'+window.location.origin+'/smiledu_basico/public/general/img/smiledu_faces/not_data_found.png"><p><strong>&#161;Ups!</strong></p><p>No se encontraon</p><p>resultados.</p></div>';
function initGraficoBarraLineas(data){
	arrayMora     = JSON.parse(data.arrayMoraG1);
	arrayCobrado  = JSON.parse(data.arrayCobradoG1);
	arrayRestante = JSON.parse(data.arrayRestanteG1);
	arrayTotal    = JSON.parse(data.arrayTotalG1);
	arrayPie      = JSON.parse(data.arrayPieG1);
	series = [{
		        type: 'column',
		        name: 'Mora',
		        data: arrayMora,
		        color : 'red'
		    }, {
		        type: 'column',
		        name: 'Cobrado',
		        data: arrayCobrado,
		        color: 'green'
		    }, {
		        type: 'column',
		        name: 'Restante',
		        data: arrayRestante,
		        color:'orange'
		    },{
		        type: 'spline',
		        name: 'Total',
		        data: arrayTotal,
		    }, {
		        type: 'pie',
		        data: arrayPie,
		        center: [300,20],
		        size: 75,
		        showInLegend: false,
		        dataLabels: {
		            enabled: false
		        }
		    }]
	if(data.flg_entra == 1){
		var options = {
			chart : {
				zoomType: 'xy'
			},
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: JSON.parse(data.arrayCategoriasG1)
	        },
	        exporting : {
	        	enabled: false
	        },
	        yAxis :{
	        	title : {
	                text: ' '
	            }
	        },
	        series: series,
	        
	    };
		$('#container1').highcharts(options);
	} else{
		var chart = $('#container1').highcharts();
		if(chart != undefined){
			$('#container1').highcharts().destroy();
		}
		$('#container1').html(imgNot_found);
	}
}

function initGraficoLinea(data){
	if(data.flg_entra == 1){
		var options = {
		    chart : {
	            zoomType: 'xy'
		    },
	        title: {
	            text: ' '
	        },
	        exporting : {
	        	enabled: false
	        },
	        xAxis: {
	            categories: JSON.parse(data.arrayCateG2)
	        }
	    };
		$('#container2').highcharts(options);
		var chart = $('#container2').highcharts();
		var arraySeriesG2 = JSON.parse(data.arraySeriesG2);
		var arrayNamesG2  = JSON.parse(data.arrayNamesG2);
		var arrayColorG2 = JSON.parse(data.arrayColorG2);
		for(i = 0; i < arraySeriesG2.length ; i++){
			chart.addSeries({
				name       : arrayNamesG2[i],
		        data  	   : arraySeriesG2[i],
		        color      : arrayColorG2[i],
		        zIndex	   : 1,
		        pointWidth : 25
		    });
		}
	} else{
		var chart = $('#container2').highcharts();
		if(chart != undefined){
			$('#container2').highcharts().destroy();
		}
		$('#container2').html(imgNot_found);
	}
}

function initGraficoComparacion(data){
	var arrayPagados    = JSON.parse(data.arrayPagadosG3);
	var arrayPendientes = JSON.parse(data.arrayPedientesG3);
	var arrayCategorias = JSON.parse(data.arrayCateG3);
//	arrayCategorias = ['Mi colegio 1','Mi colegio 2','Mi colegio 3'];
//	arrayPagados    = [-600,-700,-800];
//	arrayPendientes = [400,700,500];
	if(data.flg_entra == 1){
	    $('#container3').highcharts({
	        chart: {
	            type: 'bar',
	            zoomType: 'xy'
	        },
	        title: {
	            text: ' '
	        }, 
	        xAxis: [{
	            categories: arrayCategorias,
	            reversed: false,
	            labels: {
	                step: 1
	            }
	        }, { // mirror axis on right side
	            opposite: true,
	            reversed: false,
	            categories: arrayCategorias,
	            linkedTo: 0,
	            labels: {
	                step: 1
	            }
	        }],
	        yAxis: {
	            title: {
	                text: null
	            },
	            labels: {
	                formatter: function () {
	                    return Math.abs(this.value);
	                }
	            }
	        },
	        exporting : {
	        	enabled: false
	        },
	        plotOptions: {
	            series: {
	                stacking: 'normal',
	                cursor: 'pointer',
	                point: {
	                    events: {
	                        click: function () {
	                        	modal('modalSubirPaquete')
	                        }
	                    }
	                }
	            }
	        },
	
	        tooltip: {
	            formatter: function () {
	                return '<b>' + this.series.name + ', Sede ' + this.point.category + '</b><br/>' +
	                    'S/. ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
	            }
	        },
	        series: [{
	            name  : 'Pendiente',
	            data  : arrayPendientes,
	            color : 'orange'
	        }, {
	            name  : 'Pagado',
	            data  : arrayPagados,
	            color : 'green'
	        }]
	    });
	} else{
		var chart = $('#container3').highcharts();
		if(chart != undefined){
			$('#container3').highcharts().destroy();
		}
		$('#container3').html(imgNot_found);
	}
}

function openModalExportarExcel(){
	abrirCerrarModal('modalExportarExcel');
	setearCombo('selectSede' , '0');
	stopLoadingButton('botonEE');
}

function descargarExcelGerencial(){
	addLoadingButton('botonEE');
	$('#idSede').val($('#selectSede option:selected').val());
	$('#year').val($('#selectYears option:selected').val());
	$('#formExcel').submit();
	modal('modalExportarExcel');
}

function initGraficoSpider(data){
	arrayVencidos   = JSON.parse(data.arrayVencidosG4);
	arrayPuntuales  = JSON.parse(data.arrayPuntualG4);
	arrayNormal     = JSON.parse(data.arrayNormalG4);
	arrayCategorias = JSON.parse(data.arrayCateG4)
	if(data.flg_entra == 1){
	    $('#container4').highcharts({
	        chart: {
	            polar: true,
	            type: 'line',
	            zoomType: 'xy'
	        },
	        lang: {
	            printChart: 'Imprimir',
	            downloadPNG: 'Descargar PNG',
	            downloadJPEG: 'Descargar JPEG',
	            downloadPDF: 'Descargar PDF',
	            downloadSVG: 'Descargar SVG',
	            downloadXLS: 'Descargar Excel',
	            downloadPNG: 'Descargar PNG',
	            contextButtonTitle: 'Menu'
	        },
	        exporting: {
	            enabled: false,
	            buttons: {
	                contextButton: {
	                    	menuItems: [ {
	                            textKey: 'downloadJPEG',
	                            onclick: function () {
	                                this.exportChart({
	                                    type: 'image/jpeg'
	                                });
	                            }
	                        }, {
	                            textKey: 'downloadPNG',
	                            onclick: function () {
	                                this.exportChart({
	                                    type: 'image/png'
	                                });
	                            }
	                        },{
	                            textKey: 'downloadPDF',
	                            onclick: function () {
	                            	exportGraficoToPdf("container_grafico_1", 1);
	                            }
	                        }, {
	                            textKey: 'downloadXLS',
	                            onclick: function () {
	                            	exportGraficoToExcel("container_grafico_1", 1);
	                            }
	                        }]
	                }
	            }
	        },
	        title: {
	            text: ' ',
	            x: -80
	        },

	        pane: {
	            size: '80%'
	        },

	        xAxis: {
	            categories: arrayCategorias,
	            tickmarkPlacement: 'on',
	            lineWidth: 0
	        },

	        yAxis: {
	            gridLineInterpolation: 'polygon',
	            lineWidth: 0,
	            min: 0
	        },

	        tooltip: {
	            shared: true,
	            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
	        },

	        series: [{
	            name  : 'Pagos Vencidos',
	            data  : arrayVencidos,
	            color : 'red',
	            pointPlacement: 'on'
	        }, {
	            name: 'Pronto Pago',
	            data: arrayPuntuales,
	            color : 'green',
	            pointPlacement: 'on'
	        }, {
	            name: 'Pagos Normales',
	            data: arrayNormal,
	            color : 'blue',
	            pointPlacement: 'on'
	        }],
	        plotOptions: {
	            series: {
	            	cursor: 'pointer',
	                point: {
	                    events: {
	                        click: function () {
	                        	modal('modalSubirPaquete')
	                        }
	                    }
	                }
	            }
	        }

	    });
	} else{
		var chart = $('#container4').highcharts();
		if(chart != undefined){
			$('#container4').highcharts().destroy();
		}
		$('#container4').html(imgNot_found);
	}
}

function getGraficosByFechas(){
	addLoadingButton('botonFF');
	var fecInicio = $('#fecInicio').val();
	var fecFin    = $('#fecFin').val();
	$.ajax({
		data  : {fecInicio : fecInicio,
			     fecFin    : fecFin},
        url   : 'c_modulo_gerencial/getGraficosByFiltroFechas',
        type  : 'POST',
        async : true
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			initGraficoBarraLineas(JSON.parse(data.dataG1));
	        initGraficoLinea(JSON.parse(data.dataG2));
//	        initGraficoComparacion(JSON.parse(data.dataG3));
//	        initGraficoSpider(JSON.parse(data.dataG4));
	        modal('modalFiltroFechasGraficos');
		} else{
			msj('error',data.msj);
		}
		stopLoadingButton('botonFF');
	});
}

function exrpotChartJPEG(cont,type){
	var chart = $(cont).highcharts();
    var opt = chart.series[0].options;
    opt.dataLabels.enabled = !opt.dataLabels.enabled;
    chart.series[0].update(opt);
	//PNG
	if(type == 1){
		chart.exportChart();
	} else if(type == 2){
		chart.exportChart({
            type: 'image/jpeg'
        });
	}
}

function initChartConceptos(data){
	arrayCate = JSON.parse(data.arrayCateG5);
	var options = {
        chart: {
            type: 'column'
        },
        title: {
            text: ' '
        },
        xAxis: {
            categories: arrayCate
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: ' '
            }
        },
        exporting : {
        	enabled: false
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    'Monto: ' + 'S/.' + this.y + '<br/>';
            }
        },
        series : [{
            data : JSON.parse(data.arrayMontoG5),
            name : 'Conceptos'
        }],
        plotOptions: {
            series: {
                dataLabels: {
                    enabled : true,
                    format  : 'S/.{point.y:.2f}',
                    align   : 'right',
                },
                colorByPoint: true
            }
        }
    }
	$('#container5').highcharts(options);
}


