sessionStorage.chartType3             = "column";
$("#options_3").next().css("display", "none");
function getGraficoByPropuestaMejoraGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var tipo_encuesta = $('#selectTipoEncuestaGrafico3').val();
		var encuesta      = $('#selectEncuestaGrafico3').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		if(tipo_encuesta == null || propuesta_mejora == null){
			$('#grafico3 #cont_filter_empty').css('display', 'none');
			$("#container_grafico_3").html('');
			$('#container_grafico_3').html(ruta_not_data_fab);
			$(".btn_cambio_vista_3").addClass('btn_opacity');
			$(".btn_cambio_vista_3").attr("disabled", true);
			$("#options_3").next().find("ul").css("display", "none");
			stopLoadingButton('btnMFPM');
		}else{
			$.ajax({
				data : {tipo_encuesta    : tipo_encuesta,
					    encuesta         : encuesta,
					    propuesta_mejora : propuesta_mejora},
				url  : 'c_g_propuesta_mejora/getGraficoByPropuestaMejora', 
				async: true,
				type : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				initGraficoPropuestas(data);
				$('#contCombosGraficos3').html(data.combos);
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		    	    $('.pickerButn').selectpicker('mobile');
		    	} else {
		    		$('#selectSedeGrafico3').selectpicker({noneSelectedText: 'Seleccione Sede'});
		    		$('#selectNivelGrafico3').selectpicker({noneSelectedText: 'Seleccione Nivel'});
		    		$('#selectGradoGrafico3').selectpicker({noneSelectedText: 'Seleccione Grado'});
		    		$('#selectAulaGrafico3').selectpicker({noneSelectedText: 'Seleccione Aula'});
		    		$('#selectAreaGrafico3').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
		    	}
				stopLoadingButton('btnMFPM');
				$('#grafico3 #cont_filter_empt').css('display', 'none');
				$("#container_grafico_3").html('');
				$('#container_grafico_3').html(ruta_not_data_found);
			});
		}
	});	
}

function getEncuestasByTipo3(){
	addLoadingButton('btnMFPM');
	var tipo_encuesta = $('#selectTipoEncuestaGrafico3 option:selected').val();
	$.ajax({
		data  : {tipo_encuesta : tipo_encuesta},
		url   : 'c_g_propuesta_mejora/getEncuestaByTipoEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contCombosGraficos3').html('');
		setMultiCombo('selectPropuestaMejoraGrafico3',null);
		$('#grafico3 #cont_filter_empty').css('display', 'none');
		$('#container_grafico_3').html(ruta_not_data_fab);
		$('#grafico3 .mdl-card').removeAttr('style');
		setCombo('selectEncuestaGrafico3',data.optEnc,'Encuesta');
		stopLoadingButton('btnMFPM');
	});
}

function getPropuestasMejora3(){
	addLoadingButton('btnMFPM');
	var encuesta = $('#selectEncuestaGrafico3').val();
	$.ajax({
		data  : {encuesta : encuesta},
		url   : 'c_g_propuesta_mejora/getPropuestasMejora',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contCombosGraficos3').html(null);
		if(data.error == 1){
			mostrarNotificacion('warning' , data.msj);
			setMultiCombo('selectPropuestaMejoraGrafico3',null);
		} else{
			$("#container_grafico_3").html('');
			$('#container_grafico_3').html(ruta_not_data_found);
			setMultiCombo('selectPropuestaMejoraGrafico3',data.optProp);
		}
		stopLoadingButton('btnMFPM');
	});
}

function initGraficoPropuestas(data){
	if(JSON.parse(data.arrayCount)[0].length == 0){
		$('#container_grafico_3').html('<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/not_data_found.png"><p>No hay informacion disponible.</p>');
		$(".btn_cambio_vista_3").addClass('btn_opacity');
		$(".btn_cambio_vista_3").attr("disabled", true);
		$("#options_3").next().find("ul").css("display", "none");
	} else{
		$('#container_grafico_3 .img-search').css('display', 'none');
		$(".btn_cambio_vista_3").removeClass('btn_opacity');
		$(".btn_cambio_vista_3").attr("disabled", false);
		$("#options_3").next().find("ul").css("display", "block");
		$("#grafico3 .mdl-card__menu").css("display", "block");
		$("#grafico3 .img-search").css("display", "none");
		$('#grafico3 .mdl-card').removeAttr('style');
		arrayCate  = JSON.parse(data.arrayCat);
		arrayCount = JSON.parse(data.arrayCount);
		arrayName  = JSON.parse(data.arrayName);
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1;
		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
		var name = "Preguntas Encuesta";
		var fileName = name + today;
		var options = {
			chart: {
	            zoomType: 'xy',
	            type : 'bar'
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
	                    	menuItems: [{
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
	                            	exportGraficoToPdf("container_grafico_3", 3);
	                            }
	                        }, {
	                            textKey: 'downloadXLS',
	                            onclick: function () {
	                            	exportGraficoToExcel("container_grafico_3", 3);
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
	        yAxis: [{
	            labels: {
	                format: '{value}',
	                style: {
	                    color: 'BLACK'
	                }
	            },
	            title: {
	                text: '',
            		align: 'left',
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
	                },point: {
	                    events: {
	                        click: function() {
	                        	var id = this.series.options.data[this.index].id;
	                        	var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
	                        	$.ajax({
	                        		data  : {id_prop     : id,
	                        			     id_encuesta : encuesta},
	                        		url   : 'c_g_propuesta_mejora/getComentarioByPropuesta',
	                        		type  : 'POST',
	                        		async : true
	                        	})
	                        	.done(function(data){
	                        		data = JSON.parse(data);
	                        		$("#cont_tabla_comentarios").html(data.tablaComentario);
	                        		$('#tituloPropuesta').text(data.desc);
	                        		$('#tb_comentarios').bootstrapTable({ });
	                        		abrirCerrarModal("modalComentarioPropuesta");
	                        	});
	                        }
	                    }
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
	        }
		}
		$('#container_grafico_3').highcharts(options);
		var chart = $('#container_grafico_3').highcharts();
		for(i = 0; i<arrayCount.length;i++){
			chart.addSeries({
				colorByPoint: true,
		        name  : arrayName[i],
		        data  : arrayCount[i],
		        zIndex: 1,
		        pointWidth: 25
		    });
		}
	}
}

function getNivelesBySedeGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		var tipo_encu = $('#selectTipoEncuestaGrafico1 option:selected').val();
		$.ajax({
			data  : {sedes     : sedes,
					 propuesta_mejora  : propuesta_mejora,
				     encuesta  : encuesta,
				     tipo_encu : tipo_encu},
			url   : 'c_g_propuesta_mejora/getNivelesBySedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAulaGrafico3', null);
			setMultiCombo('selectGradoGrafico3', null);
			if(data.tipo == 0){
				setMultiCombo('selectNivelGrafico3', null);
			} else{
				setMultiCombo('selectNivelGrafico3', data.comboNiveles);
			}
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function getGradosByNivelSedeGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var nivel = $('#selectNivelGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		$.ajax({
			data  : {sedes    : sedes,
					 propuesta_mejora : propuesta_mejora,
				     encuesta : encuesta,
				     nivel    : nivel},
			url   : 'c_g_propuesta_mejora/getGradosByNivelSedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo('selectAulaGrafico3', null);
			if(data.tipo == 0){
				setMultiCombo('selectGradoGrafico3', null);
			} else{
				setMultiCombo('selectGradoGrafico3', data.comboGrados);
			}
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function getAulasByNivelGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var nivel = $('#selectNivelGrafico3').val();
		var grado = $('#selectGradoGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		$.ajax({
			data  : {sedes    : sedes,
				     propuesta_mejora : propuesta_mejora,
				     encuesta : encuesta,
				     nivel    : nivel,
				     grado    : grado},
			url   : 'c_g_propuesta_mejora/getAulasByGradoNivelSedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 0){
				setMultiCombo('selectAulaGrafico3', null);
			} else{
				setMultiCombo('selectAulaGrafico3', data.comboAulas);
			}
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function getGraficoByAula3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var nivel = $('#selectNivelGrafico3').val();
		var grado = $('#selectGradoGrafico3').val();
		var aula  = $('#selectAulaGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		$.ajax({
			data  : {sedes    : sedes,
				     propuesta_mejora : propuesta_mejora,
				     encuesta : encuesta,
				     nivel    : nivel,
				     grado    : grado,
				     aula     : aula},
			url   : 'c_g_propuesta_mejora/getGraficoByAula',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);	
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function getAreasByNivelSedeGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var nivel = $('#selectNivelGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		var tipo_encu = $('#selectTipoEncuestaGrafico3 option:selected').val();
		$.ajax({
			data  : {sedes     : sedes,
					 propuesta_mejora  : propuesta_mejora,
				     encuesta  : encuesta,
				     nivel     : nivel,
				     tipo_encu : tipo_encu},
			url   : 'c_g_propuesta_mejora/getAreasGraficoByNivel',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 0){
				setMultiCombo('selectAreaGrafico3', null);
			} else{
				setMultiCombo('selectAreaGrafico3', data.comboAreas);
			}
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function getGraficoByAreaNivelSedeGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var nivel = $('#selectNivelGrafico3').val();
		var area  = $('#selectAreaGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		$.ajax({
			data  : {sedes    : sedes,
					 propuesta_mejora : propuesta_mejora,
				     encuesta : encuesta,
				     nivel    : nivel,
				     area     : area},
			url   : 'c_g_propuesta_mejora/getGraficoByArea',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

$("#btnCambiarTipoGrafico3").toggle(
  function() {
	  changeTypeGrafico("container_grafico_3", "pie");
	  $(this).html('Cambiar tipo columna');
  }, function() {
	  changeTypeGrafico("container_grafico_3", "column");
	  $(this).html('Cambiar tipo pie');
  }
);

$("#btnCambiarTipoGrafico3").click(function(){
	if(sessionStorage.chartType3 == 'pie'){
		sessionStorage.chartType3 = 'column';
	} else{
		sessionStorage.chartType3 = 'pie';
	}
});

function getAreasBySedeGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		$.ajax({
			data  : {sedes    : sedes,
					 propuesta_mejora : propuesta_mejora,
				     encuesta : encuesta},
			url   : 'c_g_propuesta_mejora/getAreasBySedeGrafico',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 0){
				setMultiCombo('selectAreaGrafico3', null);
			} else{
				setMultiCombo('selectAreaGrafico3', data.comboAreas);
			}
			initGraficoEncuesta(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function getGraficoByAreaSedeGrafico3(){
	addLoadingButton('btnMFPM');
	Pace.restart();
	Pace.track(function() {
		var sedes = $('#selectSedeGrafico3').val();
		var nivel = $('#selectNivelGrafico3').val();
		var area  = $('#selectAreaGrafico3').val();
		var encuesta = $('#selectEncuestaGrafico3 option:selected').val();
		var propuesta_mejora = $('#selectPropuestaMejoraGrafico3').val();
		$.ajax({
			data  : {sedes    : sedes,
					 propuesta_mejora : propuesta_mejora,
				     encuesta : encuesta,
				     nivel    : nivel,
				     area     : area},
			url   : 'c_g_propuesta_mejora/getGraficoByAreaSede',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			initGraficoPropuestas(data);
			stopLoadingButton('btnMFPM');
		});
	});
}

function refreshSection3(){
	
}