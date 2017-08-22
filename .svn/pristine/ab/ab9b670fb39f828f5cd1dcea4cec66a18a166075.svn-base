sessionStorage.chartType2 = "column";
var refreshGrafico1 = null;
//0 SATISFACCION | 1 INSATISFACCION
sessionStorage.satisfTypeG5 = 0;
descAux = null;
desc = null;
tituloGlobales = "Satisfacci\u00f3n"
index_i = 0;
$("#options_1").next().css("display", "none");
$(".btn_cambio_vista_4").attr("disabled", true);
$(".btn_cambio_vista_4").off();

$('#selectPreguntaGrafico1').next().find('.bs-select-all').click(function(){
	alert('hola');
});

function getGraficoEncuestaPregunta(){
	addLoadingButton('btnMFGE');
	var selectAll = $('#selectPreguntaGrafico1').next();
	var pregunta  = $('#selectPreguntaGrafico1').val();
	Pace.restart();
	Pace.track(function() {
		var pregunta  = $('#selectPreguntaGrafico1').val();
		var encuesta  = $('#selectEncuestaGrafico1 option:selected').val();
		var tipo_encu = $('#selectTipoEncuestaGrafico1 option:selected').val();
		if(pregunta != null){
			$.ajax({
				data  : {pregunta  : pregunta,
					     encuesta  : encuesta,
					     tipo_encu : tipo_encu},
				url   : 'c_g_encuesta/getDataGraficoEncuestaByPregunta',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				initGraficoEncuesta(data);
				$('#container_grafico_1 .img-search').css('display', 'none');
				$('#contCombosSubGrafico1').html('');
				$('#contCombosGraficos1').html(data.combos);
				
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		    	    $('.pickerButn').selectpicker('mobile');
		    	} else {
		    		$('#selectSedeGrafico1').selectpicker({noneSelectedText: 'Seleccione Sede'});
		    		$('#selectNivelGrafico1').selectpicker({noneSelectedText: 'Seleccione Nivel'});
		    		$('#selectGradoGrafico1').selectpicker({noneSelectedText: 'Seleccione Grado'});
		    		$('#selectAulaGrafico1').selectpicker({noneSelectedText: 'Seleccione Aula'});
		    		$('#selectAreaGrafico1').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
		    		$('#selectTipoEncuestadoGrafico1').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
		    	}
				stopLoadingButton('btnMFGE');
			});
		} else {
			$(".btn_cambio_vista_1").addClass('btn_opacity');
			$(".btn_cambio_vista_1").attr("disabled", true);
			$("#options_1").next().find("ul").css("display", "none");
			$('#grafico1 .mdl-card, #container_grafico_1 .img-search').removeAttr('style');
		}
	});
}

function initGraficoEncuesta(data){
	if(JSON.parse(data.arrayCount)[0].length == 0){
		refreshGrafico1 = null;
		$(".btn_cambio_vista_1").addClass('btn_opacity');
		$(".btn_cambio_vista_1").attr("disabled", true);
		$("#options_1").next().find("ul").css("display", "none");
		if($('#container_grafico_1').highcharts() != undefined){
			$('#container_grafico_1').highcharts().destroy();
		}
		$('#container_grafico_1').html(ruta_not_data_fab);
	} else{
		refreshGrafico1 = data.refresh;
		$(".btn_cambio_vista_1").removeClass('btn_opacity');
		$(".btn_cambio_vista_1").attr("disabled", false);
		$("#options_1").next().find("ul").css("display", "block");
		$("#grafico1 .mdl-card__menu").css("display", "block");
		arrayCate  = JSON.parse(data.arrayCat);
		arrayCount = JSON.parse(data.arrayCount);
		arrayName  = JSON.parse(data.arrayName);
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
		var name = "Preguntas Encuesta";
		var fileName = name + today;
		var options = {
			colors: ['#4CAF50', '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107'],
			chart: {
	            zoomType: 'xy',
	            type : sessionStorage.chartType2
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
	            filename: fileName,
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
	            text: decodeURIComponent(escape(data.titulo)),
	            align: 'left',
	            margin: 75,
	            style: {
                    color: '#757575'
                }
	        },
	        credits: {
	            enabled: false
	        },
	        xAxis: [{
	            categories: arrayCate,
	            crosshair: true,
	            type: 'category'
	        }],
	        yAxis: [{ // Primary yAxis
	            labels: {
	                format: '{value}',
	                style: {
	                    color: 'BLACK'
	                }
	            },
	            title: {
	                text: '',
	                style: {
	                    color: Highcharts.getOptions().colors[2]
	                }
	            },
	            max: 100
	        }],
	        tooltip: {
	        	pointFormat: '<span style="color:{series.color}">{series.name}</span>: ({point.y:.2f}%)<br/>',
	            shared: true
	        },
	        plotOptions: {
	        	series: {
	                borderWidth: 0,
	                dataLabels: {
	                    enabled: true,
	                    format: '{point.y:.2f}%'
	                },
	        		cursor: 'pointer'
	            },
	            pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
	        },
	        
		}
		$('#container_grafico_1').highcharts(options);
		var chart = $('#container_grafico_1').highcharts();
		for(i = 0; i<arrayCount.length;i++){
			chart.addSeries({
				colorByPoint: true,
		        name  : data.encuesta,
		        data  : arrayCount[i],
		        zIndex: 1,
		        pointWidth: 25
		    });
		}
		
		descAux = data.tituloAux;
		desc    = data.titulo;
		index_i = 0;
		if(data.cPreg == 1){
			$(".highcharts-title").off();
		}else{
			addClickChangeTituloGrafico(chart);
		}
	}
}

function addClickChangeTituloGrafico(chart){
	$(".highcharts-title").css("cursor","pointer");
	$(".highcharts-title").click(function() {
		if(index_i == 0){
			chart.setTitle({text: decodeURIComponent(escape(descAux))});
			index_i = 1;
		}else{
			chart.setTitle({text: decodeURIComponent(escape(desc))});
			index_i = 0;
		}
		addClickChangeTituloGrafico(chart);
	});
}

function getEncuestasByTipo(){
	addLoadingButton('btnMFGE');
	var tipo_encuesta = $('#selectTipoEncuestaGrafico1 option:selected').val();
	$.ajax({
		data  : {tipo_encuesta : tipo_encuesta},
		url   : 'c_g_encuesta/getEncuestaByTipoEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#container_grafico_').html('');
		$('#container_grafico_').html(ruta_not_data_fab);
		$('#grafico1 #cont_filter_empty').css('display', ' none');
		$('#grafico1 .mdl-card').removeAttr('style');
		$('#contCombosGraficos1, #contCombosSubGrafico1').html('');
		setMultiCombo('selectPreguntaGrafico1',data.optPreg);
		setCombo('selectEncuestaGrafico1',data.optEnc,'Encuesta');
		stopLoadingButton('btnMFGE');
	});
}

function getPreguntasByEncuesta(){
	addLoadingButton('btnMFGE');
	var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
	$.ajax({
		data  : {encuesta : encuesta},
		url   : 'c_g_encuesta/getPreguntasByEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contCombosGraficos1, #contCombosSubGrafico1').html('');
		setMultiCombo('selectPreguntaGrafico1',data.optPreg);
		stopLoadingButton('btnMFGE');
	});
}

function getNivelesBySedeGrafico1(){
	addLoadingButton('btnMFGE');
	Pace.restart();
	Pace.track(function() {
		var sedes     = $('#selectSedeGrafico1').val();
		var encuesta  = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta  = $('#selectPreguntaGrafico1').val();
		var tipo_encu = $('#selectTipoEncuestaGrafico1 option:selected').val();
		$.ajax({
			data  : {sedes     : sedes,
				     pregunta  : pregunta,
				     encuesta  : encuesta,
				     tipo_encu : tipo_encu},
			url   : 'c_g_encuesta/getNivelesBySedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectGradoGrafico1', null);
			setMultiCombo('selectAulaGrafico1', null);
			setMultiCombo('selectNivelGrafico1', data.comboNiveles);
			initGraficoEncuesta(data);
			stopLoadingButton('btnMFGE');
		});
	});
}

function getGradosByNivelSedeGrafico1(){
	addLoadingButton('btnMFGE');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var nivel = $('#selectNivelGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		$.ajax({
			data  : {sedes    : sedes,
				     pregunta : pregunta,
				     encuesta : encuesta,
				     nivel    : nivel},
			url   : 'c_g_encuesta/getGradosByNivelSedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAulaGrafico1', null);
			setMultiCombo('selectGradoGrafico1', data.comboGrados);
			initGraficoEncuesta(data);
			stopLoadingButton('btnMFGE');
		});
	});
}

function getAulasByNivelGrafico1(){
	addLoadingButton('btnMFGE');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var nivel = $('#selectNivelGrafico1').val();
		var grado = $('#selectGradoGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		$.ajax({
			data  : {sedes    : sedes,
				     pregunta : pregunta,
				     encuesta : encuesta,
				     nivel    : nivel,
				     grado    : grado},
			url   : 'c_g_encuesta/getAulasByGradoNivelSedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAulaGrafico1', data.comboAulas);
			initGraficoEncuesta(data);
			stopLoadingButton('btnMFGE');
		});
	});
}

function getGraficoByAula(){
	addLoadingButton('btnMFGE');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var nivel = $('#selectNivelGrafico1').val();
		var grado = $('#selectGradoGrafico1').val();
		var aula  = $('#selectAulaGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		$.ajax({
			data  : {sedes    : sedes,
				     pregunta : pregunta,
				     encuesta : encuesta,
				     nivel    : nivel,
				     grado    : grado,
				     aula     : aula},
			url   : 'c_g_encuesta/getGraficoByAula',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);	
			initGraficoEncuesta(data);
			stopLoadingButton('btnMFGE');
		});
	});
}

function getAreasByNivelSedeGrafico1(){
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var nivel = $('#selectNivelGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		var tipo_encu = $('#selectTipoEncuestaGrafico1 option:selected').val();
		$.ajax({
			data  : {sedes     : sedes,
				     pregunta  : pregunta,
				     encuesta  : encuesta,
				     nivel     : nivel,
				     tipo_encu : tipo_encu},
			url   : 'c_g_encuesta/getAreasGraficoByNivel',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAreaGrafico1', data.comboAreas);
			initGraficoEncuesta(data);
		});
	});
}

function getGraficoByAreaNivelSedeGrafico1(){
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var nivel = $('#selectNivelGrafico1').val();
		var area  = $('#selectAreaGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		$.ajax({
			data  : {sedes    : sedes,
				     pregunta : pregunta,
				     encuesta : encuesta,
				     nivel    : nivel,
				     area     : area},
			url   : 'c_g_encuesta/getGraficoByArea',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			initGraficoEncuesta(data);
		});
	});
}

$("#btnCambiarTipoGrafico1").toggle(
  function() {
	  changeTypeGrafico("container_grafico_1", "pie");
	  $(this).html('Cambiar tipo columna');
  }, function() {
	  changeTypeGrafico("container_grafico_1", "column");
	  $(this).html('Cambiar tipo pie');
  }
);

$("#btnCambiarTipoGrafico1").click(function(){
	if(sessionStorage.chartType2 == 'pie'){
		sessionStorage.chartType2 = 'column';
	} else{
		sessionStorage.chartType2 = 'pie';
	}
});

function getAreasBySedeGrafico1(){
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		var tipo_encu = $('#selectTipoEncuestaGrafico1 option:selected').val();
		var tipoEncuestado = $('#selectTipoEncuestadoGrafico1 option:selected').val();
		$.ajax({
			data  : {sedes     		: sedes,
				     pregunta  		: pregunta,
				     encuesta  		: encuesta,
				     tipo_encu 		: tipo_encu,
				     tipoEncuestado : tipoEncuestado},
			url   : 'c_g_encuesta/getAreasBySedeGraficos',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAreaGrafico1', data.comboAreas);
			initGraficoEncuesta(data);
		});
	});
}

function getGraficoByAreaSedeGrafico1(){
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico1').val();
		var area  = $('#selectAreaGrafico1').val();
		var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
		var pregunta = $('#selectPreguntaGrafico1').val();
		$.ajax({
			data  : {sedes    : sedes,
				     pregunta : pregunta,
				     encuesta : encuesta,
				     area     : area},
			url   : 'c_g_encuesta/getGraficoByAreaSede',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			initGraficoEncuesta(data);
		});
	});
}

//////////////////////////////TOP
function getDetallePreguntasGlobal(contenedor,tipo){
	var top = $('#selectTopPreg option:selected').val();
	var chart = $('#' + contenedor).highcharts();
	if(chart != undefined && (tipo == 'pie' || tipo == 'column')){
		changeTypeGrafico(contenedor, tipo);
	} else{
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo : tipo,
					     top  : top},
				url   : 'c_g_encuesta/getDataPreguntasGlobales',
				type  : 'POST',
				async : false
			})
			.done(function(data){
				data = JSON.parse(data);
				if(tipo == 'pie' || tipo == 'column'){
					initGraficoPreguntasGlobales(data,tipo);
				} else{
					if(chart != undefined){
						$('#container_grafico_5').highcharts().destroy();
					}
					$("#container_grafico_5" ).empty();
					
					if(data.flg_table == 1){
						$('#container_grafico_5').html(data.tabla);
						$('#tb_preg_global').bootstrapTable({ });
						initSearchTableById("tb_preg_global");
						$(".btn_cambio_vista_5").removeClass('btn_opacity');
						$(".btn_cambio_vista_5").attr("disabled", false);
					} else{
						$('#container_grafico_5').html(ruta_not_data_found);
						$(".btn_cambio_vista_5").addClass('btn_opacity');
						$(".btn_cambio_vista_5").attr("disabled", true);
					}
				}
			});
		});
	}
}

function initGraficoPreguntasGlobales(data,tipo){
	if(JSON.parse(data.arrayGeneral)[0].length == 0){
		$('#container_grafico_5').html(ruta_not_data_found);
		$(".btn_cambio_vista_5").addClass('btn_opacity');
		$(".btn_cambio_vista_5").attr("disabled", true);
	} else{
		$(".btn_cambio_vista_5").removeClass('btn_opacity');
		$(".btn_cambio_vista_5").attr("disabled", false);
		$("#grafico5 .mdl-card__menu").css("display", "block");
		arrayGeneral  = JSON.parse(data.arrayGeneral);
		arrayCate     = JSON.parse(data.arrayCate);
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
		var name = "Preguntas Globales";
		var fileName = name + today;
		var options = {
			chart: {
	            zoomType: 'xy',
	            type : tipo
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
	            filename: fileName,
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
	            text: tituloGlobales,
	            align: 'left',
	            margin: 75,
	            style: {
                    color: '#757575'
                }
	        },
	        credits: {
	            enabled: false
	        },
	        xAxis: [{
	            categories: [],
	            crosshair: true,
	            type: 'category'
	        }],
	        yAxis: [{ // Primary yAxis
	            labels: {
	                format: '{value}',
	                style: {
	                    color: 'BLACK'
	                }
	            },
	            title: {
	                text: '',
	                style: {
	                    color: Highcharts.getOptions().colors[2]
	                }
	            }
	        }],
	        tooltip: {
	        	pointFormat: '<span style="color:{series.color}">{series.name}</span>: ({point.y:.2f}%)<br/>',
	            shared: true
	        },
	        plotOptions: {
	        	series: {
	                borderWidth: 0,
	                dataLabels: {
	                    enabled: true,
	                    format: '{point.y:.2f}%'
	                },
	        		cursor: 'pointer'
	            },
	            pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
	        },
	        
		}
		$('#container_grafico_5').highcharts(options);
		var chart = $('#container_grafico_5').highcharts();
		for(i = 0; i<arrayGeneral.length;i++){
			chart.addSeries({
				colorByPoint: true,
		        name  : 'ENCUESTA',
		        data  : arrayGeneral[i],
		        zIndex: 1,
		        pointWidth: 25
		    });
		}
	}
}

function getEncuestasByTipoGrafico5(){
	addLoadingButton('btnFTP');
	var tipo_encuesta = $('#selectTipoEncuesta option:selected').val();
	$.ajax({
		data  : {tipo_encuesta : tipo_encuesta},
		url   : 'c_g_encuesta/getEncuestaByTipoEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#container_grafico_5').html(ruta_not_data_found);
		$('#grafico5 #cont_filter_empty').css('display', 'none');
		$('#grafico5 .mdl-card').removeAttr('style');
		setCombo('selectEncuestaGrafico5',data.optEnc,'Encuesta');
		stopLoadingButton('btnFTP');
	});
}
var tbPregutasTop = null;
function getGraficoByEncuestaGrafico5(contenedor,tipo){
	addLoadingButton('btnFTP');
	Pace.restart();
	Pace.track(function() {
		var top = $('#selectTopPreg option:selected').val();
		var idEncuesta = $('#selectEncuestaGrafico5 option:selected').val();
		var chart = $('#' + contenedor).highcharts();
		var existTable = $('#tb_preg_global').html();
		if(chart != undefined && (tipo == 'pie' || tipo == 'column')){
			changeTypeGrafico(contenedor, tipo);
		} else{
			$.ajax({
				data  : {tipo 		: tipo,
					     top  		: top,
					     idEncuesta : idEncuesta,
					     satis      : sessionStorage.satisfTypeG5},
				url   : 'c_g_encuesta/getDataPreguntasGlobales',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					if(tipo == 'pie' || tipo == 'column'){
						initGraficoPreguntasGlobales(data,tipo);					
					} else{
						if(chart != undefined){
							$('#container_grafico_5').highcharts().destroy();
						}
						$("#container_grafico_5" ).empty();
						if(data.flg_table == 1){
							$('#container_grafico_5').html(data.tabla);
							tbPregutasTop = data.tabla;
							$('#tb_preg_global').bootstrapTable({ });
							initSearchTableById("tb_preg_global");
							$(".btn_cambio_vista_5").removeClass('btn_opacity');
							$(".btn_cambio_vista_5").attr("disabled", false);
							$('#contComboTop').css('display','block')
						} else{
							$('#container_grafico_5').html(ruta_not_data_found);
							$(".btn_cambio_vista_5").addClass('btn_opacity');
							$(".btn_cambio_vista_5").attr("disabled", true);
						}
					}
				}
				stopLoadingButton('btnFTP');
			});
		}
	});
}

$("#btnCambiarSatisfaccionGrafico5").toggle(
	function() {
		$(this).find('i').removeClass('mdi-sentiment_dissatisfied');
		$(this).find('i').addClass('mdi-sentiment_satisfied');
		$(this).attr("data-original-title", "Cambiar a satisfacci\u00f3n");
	    sessionStorage.satisfTypeG5 = 1;
	    getGraficoByEncuestaGrafico5('container_grafico_5','tabla');
	    tituloGlobales = "Insatisfacci\u00f3n";
	    var chart = $('#container_grafico_5').highcharts();
	},function() {
		$(this).find('i').removeClass('mdi-sentiment_satisfied');
		$(this).find('i').addClass('mdi-sentiment_dissatisfied');
		$(this).attr("data-original-title", "Cambiar a insatisfacci\u00f3n");
		sessionStorage.satisfTypeG5 = 0;
		tituloGlobales = "Satisfacci\u00f3n";
		getGraficoByEncuestaGrafico5('container_grafico_5','tabla');
	}
);

////////////////////////////////////////////////////////////////////////////////////////////////
function getNivelesByTipoEnc(){
	var pregunta  = $('#selectPreguntaGrafico1').val();
	var encuesta  = $('#selectEncuestaGrafico1 option:selected').val();
	var tipo_encu = $('#selectTipoEncuestaGrafico1 option:selected').val();
	var tipoEncuestado = $('#selectTipoEncuestadoGrafico1 option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {pregunta  		: pregunta,
				     encuesta       : encuesta,
				     tipo_encu      : tipo_encu,
				     tipoEncuestado : tipoEncuestado},
			url   : 'c_g_encuesta/getNivelesGraficoByTipoEnc',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			initGraficoEncuesta(data);
			$('#contCombosSubGrafico1').html(data.combos);
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    	    $('.pickerButn').selectpicker('mobile');
	    	} else {
	    		$('#selectSedeGrafico1').selectpicker({noneSelectedText: 'Seleccione Sede'});
	    		$('#selectNivelGrafico1').selectpicker({noneSelectedText: 'Seleccione Nivel'});
	    		$('#selectGradoGrafico1').selectpicker({noneSelectedText: 'Seleccione Grado'});
	    		$('#selectAulaGrafico1').selectpicker({noneSelectedText: 'Seleccione Aula'});
	    		$('#selectAreaGrafico1').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
	    	}
		});
	});
}

function getEncuestasByTipoGrafico6(){
	addLoadingButton('btnMFT');
	var tipo_encuesta = $('#selectTipoEncuestaTuto option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {tipo_encuesta : tipo_encuesta},
			url   : 'c_g_encuesta/getEncuestaByTipoEncuesta',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#container_grafico_6').html(ruta_not_data_found);
			$('#grafico6 #cont_filter_empty').css('display', 'none');
			$('#grafico6 .mdl-card').removeAttr('style');
			setCombo('selectEncuestaGrafico6',data.optEnc,'Encuesta');
			stopLoadingButton('btnMFT');
		});
	});
}

function getGraficoByEncuestaGrafico6(contenedor){
	addLoadingButton('btnMFT');
	var tipo_enc  = $('#selectTipoEncuestaTuto option:selected').val();
	var encuesta  = $('#selectEncuestaGrafico6 option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {encuesta : encuesta,
				     tipo_enc : tipo_enc},
			url   : 'c_g_encuesta/getGraficoTutoriaByEncuesta',
			type  : 'POST',
			async : false
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contCombosGraficos6').html(data.combos);
			$('#contCombosSubGrafico6').html(null);
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    	    $('.pickerButn').selectpicker('mobile');
	    	} else {
	    		$('#selectTipoEncuestadoGrafico6').selectpicker({noneSelectedText: 'Seleccione Tipo Encuestado'});
	    	}
			log(data.series);
			initGraficoTutoria(JSON.parse(data.series));
			stopLoadingButton('btnMFT');
		});
	});
}

function initGraficoTutoria(data){
	if(!data.hasOwnProperty('arrayCount')){
		$('#container_grafico_6').html(ruta_not_data_found);
		$(".btn_cambio_vista_6").addClass('btn_opacity');
		$(".btn_cambio_vista_6").attr("disabled", true);
	} else{
		if((JSON.parse(data.arrayCount)).length == 0){
			$('#container_grafico_6').html(ruta_not_data_found);
			$(".btn_cambio_vista_6").addClass('btn_opacity');
			$(".btn_cambio_vista_6").attr("disabled", true);
		} else{
			$(".btn_cambio_vista_6").removeClass('btn_opacity');
			$(".btn_cambio_vista_6").attr("disabled", false);
			$("#options_1").next().find("ul").css("display", "block");
			$("#grafico1 .mdl-card__menu").css("display", "block");
			arrayCate  = JSON.parse(data.arrayCategorias);
			arrayCount = JSON.parse(data.arrayCount);
			arrayName  = JSON.parse(data.arrayName);
			arrayColor = JSON.parse(data.arrayColor);
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1; //January is 0!
			var yyyy = today.getFullYear();
			if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
			var name = "Preguntas Encuesta";
			var fileName = name + today;
			var options = {
				colors: ['#4CAF50', '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107'],
				chart: {
		            zoomType: 'xy',
		            type : sessionStorage.chartType2
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
		            filename: fileName,
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
		            text: ((data.titulo == undefined) ? null : data.titulo),
		            align: 'left',
		            margin: 75,
		            style: {
	                    color: '#757575'
	                }
		        },
		        credits: {
		            enabled: false
		        },
		        xAxis: [{
		            categories: arrayCate,
		            crosshair: true,
		            type: 'category'
		        }],
		        yAxis: [{ // Primary yAxis
		            labels: {
		                format: '{value}',
		                style: {
		                    color: 'BLACK'
		                }
		            },
		            title: {
		                text: '',
		                style: {
		                    color: Highcharts.getOptions().colors[2]
		                }
		            },
		            max: 100
		        }],
		        tooltip: {
		        	pointFormat: '<span style="color:{series.color}">{series.name}</span>: ({point.y:.2f}%)<br/>'
		        },
		        plotOptions: {
		        	series: {
		                borderWidth: 0,
		                dataLabels: {
		                    enabled: true,
		                    format: '{point.y:.2f}%'
		                },
		        		cursor: 'pointer'
		            }
		        }
			}
			$('#container_grafico_6').highcharts(options);
			var chart = $('#container_grafico_6').highcharts();
			for(i = 0; i<arrayCount.length;i++){
				chart.addSeries({
			        name  : arrayName[i],
			        data  : arrayCount[i],
			        color : arrayColor[i],
			        zIndex: 1,
			        pointWidth: 17
			    });
			}
			
			descAux = data.tituloAux;
			desc    = data.titulo;
			index_i = 0;
			//SET HREF TO "a" element
			canvg(document.getElementById('canvas'), chart.getSVG())
		    
			var canvas = document.getElementById("canvas");
			var img = canvas.toDataURL("image/png");
			$('#exportImgTuto').attr('href',img);
//			getBaseByGraficoSVG1('container_grafico_6');
			if(data.cPreg == 1){
				$(".highcharts-title").off();
			}else{
				addClickChangeTituloGrafico(chart);
			}
		}
	}
}


function getNivelesByTipoEnc6(){
	var tipo_encu = $('#selectTipoEncuestadoGrafico6 option:selected').val();
	var encuesta  = $('#selectEncuestaGrafico6 option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {tipo_encu : tipo_encu,
				     encuesta  : encuesta},
			url   : 'c_g_encuesta/getGraficoTutoriaByTipoEnc',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contCombosSubGrafico6').html(null);
			$('#contCombosSubGrafico6').html(data.combos);
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    	    $('.pickerButn').selectpicker('mobile');
	    	} else {
	    		$('#selectSedeGrafico6').selectpicker({noneSelectedText: 'Seleccione Sede'});
	    		$('#selectNivelGrafico6').selectpicker({noneSelectedText: 'Seleccione Nivel'});
	    		$('#selectGradoGrafico6').selectpicker({noneSelectedText: 'Seleccione Grado'});
	    		$('#selectAulaGrafico6').selectpicker({noneSelectedText: 'Seleccione Aula'});
	    		$('#selectAreaGrafico6').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
	    	}
			initGraficoTutoria(JSON.parse(data.series));
		});
	});
}

function getNivelesBySedeGrafico6(){
	var tipo_encu = $('#selectTipoEncuestadoGrafico6 option:selected').val();
	var encuesta  = $('#selectEncuestaGrafico6 option:selected').val();
	var sedes     = $('#selectSedeGrafico6').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {tipo_encu : tipo_encu,
				     encuesta  : encuesta,
				     sedes     : sedes},
			url   : 'c_g_encuesta/getGraficoTutoriaBySede',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectNivelGrafico6' , data.comboNiveles);
			setMultiCombo('selectGradoGrafico6' , null);
			setMultiCombo('selectAulaGrafico6'  , null);
			initGraficoTutoria(JSON.parse(data.series));
		});
	});
}

function getGradosByNivelSedeGrafico6(){
	var tipo_encu = $('#selectTipoEncuestadoGrafico6 option:selected').val();
	var encuesta  = $('#selectEncuestaGrafico6 option:selected').val();
	var sedes     = $('#selectSedeGrafico6').val();
	var niveles   = $('#selectNivelGrafico6').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {tipo_encu : tipo_encu,
				     encuesta  : encuesta,
				     sedes     : sedes,
				     niveles   : niveles},
			url   : 'c_g_encuesta/getGraficoTutoriaBySedeNivel',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectGradoGrafico6' , data.comboGrados);
			setMultiCombo('selectAulaGrafico6'  , null);
			initGraficoTutoria(JSON.parse(data.series));
		});
	});
}

function getAulasByNivelGrafico6(){
	var tipo_encu = $('#selectTipoEncuestadoGrafico6 option:selected').val();
	var encuesta  = $('#selectEncuestaGrafico6 option:selected').val();
	var sedes     = $('#selectSedeGrafico6').val();
	var niveles   = $('#selectNivelGrafico6').val();
	var grados    = $('#selectGradoGrafico6').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {tipo_encu : tipo_encu,
				     encuesta  : encuesta,
				     sedes     : sedes,
				     niveles   : niveles,
				     grados    : grados},
			url   : 'c_g_encuesta/getGraficoTutoriaBySedeNivelGrado',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAulaGrafico6', data.comboAulas);
			initGraficoTutoria(JSON.parse(data.series));
		});
	});
}

function getGraficoByAula6(){
	var tipo_encu = $('#selectTipoEncuestadoGrafico6 option:selected').val();
	var encuesta  = $('#selectEncuestaGrafico6 option:selected').val();
	var sedes     = $('#selectSedeGrafico6').val();
	var niveles   = $('#selectNivelGrafico6').val();
	var grados    = $('#selectGradoGrafico6').val();
	var aula      = $('#selectAulaGrafico6').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {tipo_encu : tipo_encu,
				     encuesta  : encuesta,
				     sedes     : sedes,
				     niveles   : niveles,
				     grados    : grados,
				     aulas     : aula},
			url   : 'c_g_encuesta/getGraficoTutoriaBySedeNivelGradoAula',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			initGraficoTutoria(JSON.parse(data.series));
		});
	});
}

function refreshSection6(){
	
}

function refreshSection1(){
	var encuesta = $('#selectEncuestaGrafico1 option:selected').val();
	var pregunta = $('#selectPreguntaGrafico1').val();
	//Niveles
	var sedes 	 	   = $('#selectSedeGrafico1').val();
	var nivel 	 	   = $('#selectNivelGrafico1').val();
	var grados   	   = $('#selectGradoGrafico1').val();
	var aula     	   = $('#selectAulaGrafico1').val();
	var area     	   = $('#selectAreaGrafico1').val();
	var tipoEncuestado = $('#selectTipoEncuestadoGrafico1').val();
	if(refreshGrafico1 != null){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {encuesta 		 : encuesta,
					     pregunta 		 : pregunta,
					     tipoEncuestado  : tipoEncuestado,
					     sedes    		 : sedes,
					     nivel    		 : nivel,
					     grados   		 : grados,
					     aula     		 : aula,
					     area     		 : area,
					     refreshGrafico1 : refreshGrafico1},
				url   : 'c_g_encuesta/refreshGrafico1',
				async : true,
				type  : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				initGraficoEncuesta(data);
			});
		});
	}
}
function refreshSection5(){
	
}