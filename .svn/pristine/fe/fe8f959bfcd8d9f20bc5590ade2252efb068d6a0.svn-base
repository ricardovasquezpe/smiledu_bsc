/*CONSTANTES*/
sessionStorage.chartType  = "column";
sessionStorage.satisfType = 0;
var cons_id_pregunta      = null;
var cons_id_tipo_encuesta = null;
var cons_cargar_combos    = 0;
var cons_index_serie      = null;
var cons_index_data       = null;
var cons_year_aula        = null;
var arrayCombos           = [];
var  tituloGrafico = "Todas las respuestas - Satisfacci\u00f3n";
$("#options_2").next().css("display", "none");
/*FIN CONSTANTES*/
$("#btnCambiarTipoGrafico2").toggle(
  function() {
	  changeTypeGrafico("container_grafico_2", "line");
	  $(this).html('Cambiar tipo columna');
	  sessionStorage.chartType = 'line';
  },function() {
	  changeTypeGrafico("container_grafico_2", "column");
	  $(this).html('Cambiar tipo linea');
	  sessionStorage.chartType = 'column';
  }
);

$("#btnCambiarSatisfaccionTipoGrafico2").toggle(
  function() {
	  $(this).find('i').removeClass('mdi-sentiment_dissatisfied');
	  $(this).find('i').addClass('mdi-sentiment_satisfied');
	  $(this).attr("data-original-title", "Cambiar a satisfacci\u00f3n");
	  sessionStorage.satisfType = 1;
	  tituloGrafico = "Todas las respuestas - Insatisfacci\u00f3n";
	  getGraficoByPreguntaGrafico2();
  },function() {
	  $(this).find('i').removeClass('mdi-sentiment_satisfied');
	  $(this).find('i').addClass('mdi-sentiment_dissatisfied');
	  $(this).attr("data-original-title", "Cambiar a insatisfacci\u00f3n");
	  sessionStorage.satisfType = 0;
	  tituloGrafico = "Todas las respuestas - Satisfacci\u00f3n";
	  getGraficoByPreguntaGrafico2();
  }
);

function getPreguntasByTipoEcnuestaGrafico2(){
	addLoadingButton('btnMFGCP');
	var tipo_encuesta = $('#selectTipoEncuestaGrafico2').val();
	if(tipo_encuesta == null){
		setCombo("selectPreguntaGrafico2", null, "Preguntas");
		setMultiCombo("selectYearGrafico2", null);
		$('#container_grafico_2').html(ruta_not_data_fab);
		stopLoadingButton('btnMFGCP');
	}else{
		$.ajax({
			data : {tipo_encuesta : tipo_encuesta},
			url  : 'c_g_comparar_preg/getPreguntasByTipoEncuesta', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			setMultiCombo("selectPreguntaGrafico2", data.preguntas);
			stopLoadingButton('btnMFGCP');
		});
	}
	var chart = $("#container_grafico_2").highcharts();
	if(chart != undefined){
		$('#container_grafico_2').highcharts().destroy();
	}
	$('#grafico2 #cont_filter_empty').css('display', 'none');
	$('#grafico2 .mdl-card').removeAttr('style');
}

function getGraficoByPreguntaGrafico2(){
	addLoadingButton('btnMFGCP');
	Pace.restart();
	Pace.track(function() {
		var all = $('#selectPreguntaGrafico2 option[value=all]');
		var tipo_encuesta = $('#selectTipoEncuestaGrafico2').val();
		var preguntas     = $('#selectPreguntaGrafico2').val();
		if(preguntas == null){
			var chart = $('#container_grafico_2').highcharts()
			if(chart != undefined){
				$('#container_grafico_2').highcharts().destroy();
			}
			$("#contCombosGrafico2").html("");
			$(".btn_cambio_vista_2").addClass('btn_opacity');
			$(".btn_cambio_vista_2").attr("disabled", true);
			$("#options_2").next().find("ul").css("display", "none");
			setMultiCombo("selectYearGrafico2", null);
			stopLoadingButton('btnMFGCP');
		}else{
			$.ajax({
				data : {tipo_encuesta : tipo_encuesta,
					    preguntas     : preguntas,
					    satisfaccion  : sessionStorage.satisfType},
				url  : 'c_g_comparar_preg/getGraficoByPregunta', 
				async: true,
				type : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				arrayCombos.length = 0;
				initGrafico(data);
				setMultiCombo("selectYearGrafico2", data.years);
				stopLoadingButton('btnMFGCP');
			});
		}
	});
}

function getGraficoByYearGrafico2(){
	addLoadingButton('btnMFGCP');
	Pace.restart();
	Pace.track(function() {
		var tipo_encuesta = $('#selectTipoEncuestaGrafico2').val();
		var preguntas     = $('#selectPreguntaGrafico2').val();
		var years         = $('#selectYearGrafico2').val();
		if(years == null){
			$('#container_grafico_2').highcharts().destroy();
			$("#contCombosGrafico2").html("");
			getGraficoByPreguntaGrafico2();
			stopLoadingButton('btnMFGCP');
		} else {
			$.ajax({
				data : {tipo_encuesta : tipo_encuesta,
					    preguntas     : preguntas,
					    years         : years,
					    satisfaccion  : sessionStorage.satisfType},
				url  : 'c_g_comparar_preg/getGraficoByYear', 
				async: true,
				type : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				arrayCombos.length = 0;
				initGrafico(data);
				stopLoadingButton('btnMFGCP');
			});
		}
	});
}

function initGrafico(data, ini){
	var year = data.year;
	arrayYears = JSON.parse(year);
	
	var satisfaccion = data.satisfaccion;
	arraySatisf = JSON.parse(satisfaccion);
	
	if(arrayYears[0] == null){
		var chart = $("#container_grafico_2").highcharts();
		if(chart != undefined){
			$('#container_grafico_2').highcharts().destroy();
		}
		$('#container_grafico_2').html(ruta_not_data_fab);
		$(".btn_cambio_vista_2").addClass('btn_opacity');
		$(".btn_cambio_vista_2").attr("disabled", true);
		$("#options_2").next().find("ul").css("display", "none");
	}else{
		$(".btn_cambio_vista_2").removeClass('btn_opacity');
		$(".btn_cambio_vista_2").attr("disabled", false);
		$("#options_2").next().find("ul").css("display", "block");
		$("#grafico2 .mdl-card__menu").css("display", "block");
		var options = {
			chart: {
	            zoomType: 'xy',
	            type : sessionStorage.chartType
	        },
	        title: {
	            text: tituloGrafico,
	            align: 'left',
	            margin: 75,
	            style: {
                    color: '#757575'
                }
	        },
	        credits: {
	            enabled: false
	        },
	        lang: {
	            printChart:   'Imprimir',
	            downloadPNG:  'Descargar PNG',
	            downloadJPEG: 'Descargar JPEG',
	            downloadPDF:  'Descargar PDF',
	            downloadSVG:  'Descargar SVG',
	            downloadXLS:  'Descargar Excel',
	            downloadPNG:  'Descargar PNG',
	            contextButtonTitle: 'Menu'
	        },
	        exporting: {
	            filename: "Gráfico",
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
                        }, {
                            textKey: 'downloadPDF',
                            onclick: function () {
                            	exportGraficoToPdf("container_grafico_2", 2);
                            }
                        }, {
                            textKey: 'downloadXLS',
                            onclick: function () {
                            	exportGraficoToExcel("container_grafico_2", 2);
                            }
                        }],
                        theme: {
                            zIndex: 100   
                        }
	                }
	            }
	        },
	        subtitle: {
	            text: ''
	        },
	        xAxis: [{
	            categories: arrayYears,
	            crosshair: true
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
	                style: {
	                    color: Highcharts.getOptions().colors[2]
	                }
	            }	
	        }],
	        tooltip: {
	            shared: false,
	            formatter: function () {
	            	return '<b>' + this.x + '</b><br/>' +
	            	this.series.options.stack + '<br/>' +
                    /*'Cantidad: ' 
	            	+ this.series.options.cantidad[0]+'<br/>'+*/
                    'Porcentaje: ' + this.y+'%';
	            }
	        },
	        plotOptions: {
	            column: {
	                stacking: 'normal'
	            },
	            series: {
	                borderWidth: 0,
	                dataLabels: {
	                    enabled: true,
	                    format: '{point.y:.2f}%'
	                },point: {
	                    events: {
	                        click: function() {
	                        	cons_id_pregunta      = this.series.options.id_pregunta;
	                            cons_id_tipo_encuesta = this.series.options.id_tipo_encuesta;
	                            cons_index_serie      = this.series.index;
	                            cons_index_data       = this.index;
	                            cons_year_aula        = this.series.data[this.index].category;
	                            if(cons_cargar_combos == 0){
	                            	$.ajax({
		                    			data : { tipo_encuesta  : cons_id_tipo_encuesta },
		                    			url  : 'c_g_comparar_preg/getCombosByTipoEncuesta', 
		                    			async: true,
		                    			type : 'POST'
		                    		})
		                    		.done(function(data){
		                    			data = JSON.parse(data);
		                    			$("#comboGrafico2").html(data.combos);
		                    			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		                    	    	    $('#selectSedeGrafico2').selectpicker('mobile');
		                    	    	    $('#selectNivelGrafico2').selectpicker('mobile');
		                    	    	    $('#selectGradoGrafico2').selectpicker('mobile');
		                    	    	    $('#selectAulaGrafico2').selectpicker('mobile');
		                    	    	    $('#selectDisciplinaGrafico2').selectpicker('mobile');
		                    	    	    $('#selectAreaGrafico2').selectpicker('mobile');
		                    	    	} else {
		                    	    		$('#selectSedeGrafico2').selectpicker({noneSelectedText: 'Seleccione una sede'});
		                    	    		$('#selectNivelGrafico2').selectpicker({noneSelectedText: 'Seleccione un nivel'});
		                    	    		$('#selectGradoGrafico2').selectpicker({noneSelectedText: 'Seleccione un grado'});
		                    	    		$('#selectAulaGrafico2').selectpicker({noneSelectedText: 'Seleccione una aula'});
		                    	    		$('#selectDisciplinaGrafico2').selectpicker({noneSelectedText: 'Seleccione una disciplina'});
		                    	    		$('#selectAreaGrafico2').selectpicker({noneSelectedText: 'Seleccione un area'});
		                    	    	}
		                    			
		                    			var result = arrayCombos.filter(function( obj ) {
		                    			  return obj.index_serie == cons_index_serie && obj.index_data == cons_index_data;
		                    			});
		                    			
		                    			if(result.length > 0){
		                    				$('#selectSedeGrafico2').selectpicker('val', result[0].sede);
		                    				getNivelesBySedeGrafico2();
			                    			$('#selectNivelGrafico2').selectpicker('val', result[0].nivel);
			                    			
		                    				if(data.tipo == 1){
		                    					getGradosByNivelGrafico2();
		                    					$('#selectGradoGrafico2').selectpicker('val', result[0].grado);
				                    			getAulasByGradoGrafico2();
				                    			$('#selectAulaGrafico2').selectpicker('val', result[0].aula);
				                    			getGraficoByAulaGrafico2();
		                    				}else{
		                    					getAreasGrafico2();
		                    					$('#selectAreaGrafico2').selectpicker('val', result[0].area);
		                    					getGraficoByAreaGrafico2();
		                    				}
		                    			}
		                    			if(data.tipo == 1 || data.tipo == 2){
		                    				abrirCerrarModal("modalCombosGraficoCompararPreguntas");
		                    			}
		                    		});
	                            }
	                        }
	                    }
	                },
	        		cursor: 'pointer'
	            }
	        }
		}
		
		$('#container_grafico_2').highcharts(options);
		var chart = $('#container_grafico_2').highcharts();
		for(var i = 0; i <  arraySatisf.length; i++){
			chart.addSeries({
		        name: arraySatisf[i][1] + " - " +arraySatisf[i][2],
		        data: arraySatisf[i][0],
		        stack: arraySatisf[i][1],
		        id_pregunta:arraySatisf[i][3],
		        id_tipo_encuesta:arraySatisf[i][4],
		        cantidad:arraySatisf[i][5]+'/'+arraySatisf[i][6],
//		        cantidad:arraySatisf[i][7],
		        zIndex: 1,
		        pointWidth: 25
		    });
		}
	}
}

/*LLENAR COMBOS BASE*/
function getNivelesBySedeGrafico2(){
	var id_sede = $('#selectSedeGrafico2').val();
	if(id_sede == null){
		setMultiCombo("selectNivelGrafico2", null);
		setMultiCombo("selectGradoGrafico2", null);
		setMultiCombo("selectAulaGrafico2", null);
		setMultiCombo("selectAreaGrafico2", null);
		getGraficoByPreguntaGrafico2();
	}else{
		$.ajax({
			data : {id_sede       : id_sede,
				    tipo_encuesta : cons_id_tipo_encuesta,
				    pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType,
				    year          : cons_year_aula},
			url  : 'c_g_comparar_preg/getNivelesBySede', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 1){
				setMultiCombo("selectNivelGrafico2", data.niveles);
				setMultiCombo("selectGradoGrafico2", null);
				setMultiCombo("selectAulaGrafico2", null);
				setMultiCombo("selectAreaGrafico2", null);
			}else{
				setMultiCombo("selectNivelGrafico2", null);
				setMultiCombo("selectGradoGrafico2", null);
				setMultiCombo("selectAulaGrafico2", null);
				setMultiCombo("selectAreaGrafico2", null);
			}
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function getGradosByNivelGrafico2(){
	var id_nivel      = $('#selectNivelGrafico2').val();
	var id_sede       = $('#selectSedeGrafico2').val();
	if(id_nivel == null){
		setMultiCombo("selectGradoGrafico2", null);
		setMultiCombo("selectAulaGrafico2", null);
		getNivelesBySedeGrafico2();
	}else{
		$.ajax({
			data : {id_nivel      : id_nivel,
				    id_sede       : id_sede,
				    tipo_encuesta : cons_id_tipo_encuesta,
				    pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType,
				    year          : cons_year_aula},
			url  : 'c_g_comparar_preg/getGradosByNivel', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 1){
				setMultiCombo("selectGradoGrafico2", data.grados);
				setMultiCombo("selectAulaGrafico2", null);
			}else{
				setMultiCombo("selectGradoGrafico2", null);
				setMultiCombo("selectAulaGrafico2", null);
			}
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function getAulasByGradoGrafico2(){
	var id_grado  = $('#selectGradoGrafico2').val();
	var id_nivel  = $('#selectNivelGrafico2').val();
	var preguntas = $('#selectPreguntaGrafico2').val();
	var id_sede   = $('#selectSedeGrafico2').val();
	if(id_grado == null){
		setMultiCombo("selectAulaGrafico2", null);
		getGradosByNivelGrafico2();
	}else{
		$.ajax({
			data : {id_grado : id_grado,
				    id_nivel : id_nivel,
			     	id_sede  : id_sede,
			     	tipo_encuesta : cons_id_tipo_encuesta,
				    pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType,
				    year          : cons_year_aula},
			url  : 'c_g_comparar_preg/getAulasByGrado', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 1){
				setMultiCombo("selectAulaGrafico2", data.aulas);
			}else{
				setMultiCombo("selectAulaGrafico2", null);
			}
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function getGraficoByAulaGrafico2(){
	var id_aula = $('#selectAulaGrafico2').val();
	if(id_aula == null){
		getAulasByGradoGrafico2();
	}else{
		$.ajax({
			data : {id_aula       : id_aula,
				    tipo_encuesta : cons_id_tipo_encuesta,
			        pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType},
			url  : 'c_g_comparar_preg/getGraficoByAulaoAulas', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function getAreasGrafico2(){
	var id_nivel = $('#selectNivelGrafico2').val();
	var id_sede  = $('#selectSedeGrafico2').val();
	if(id_nivel == null){
		setCombo("selectAreaGrafico2", null, "area");
		getNivelesBySedeGrafico2();
	}else{
		$.ajax({
			data : {id_nivel      : id_nivel,
				    id_sede       : id_sede,
				    tipo_encuesta : cons_id_tipo_encuesta,
			        pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType},
			url  : 'c_g_comparar_preg/getAreas', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 1){
				setMultiCombo("selectAreaGrafico2", data.areas);
			}else{
				//setMultiCombo("selectAreaGrafico2", null);
			}
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function getGraficoByAreaGrafico2(){
	var id_nivel = $('#selectNivelGrafico2').val();
	var id_sede  = $('#selectSedeGrafico2').val();
	var id_area  = $('#selectAreaGrafico2').val();
	if(id_area == null){
		getAulasByGradoGrafico2();
	}else{
		$.ajax({
			data : {id_area       : id_area,
				    id_sede       : id_sede,
				    id_nivel      : id_nivel,
				    tipo_encuesta : cons_id_tipo_encuesta,
			        pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType},
			url  : 'c_g_comparar_preg/getGraficoByAreaoAreas', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function updateDataToSerie(data){
	if(data != undefined){
		var chart = $('#container_grafico_2').highcharts();
		chart.series[cons_index_serie].data[cons_index_data].update(parseInt(data));
	}else{
		var chart = $('#container_grafico_2').highcharts();
		chart.series[cons_index_serie].data[cons_index_data].update(parseInt(0));
	}
}

function populateArray(){
	removeObjectFromArray();
	var obj = { 
	    index_serie  : cons_index_serie,
	    index_data   : cons_index_data,
	    sede      : $('#selectSedeGrafico2').val(),
	    nivel     : $('#selectNivelGrafico2').val(),
	    grado     : $('#selectGradoGrafico2').val(),
	    aula      : $('#selectAulaGrafico2').val(),
	    area      : $('#selectAreaGrafico2').val()
	};
	arrayCombos.push(obj);
}

function removeObjectFromArray(){
	for(var i = 0; i < arrayCombos.length; i++){
		if(arrayCombos[i].index_serie == cons_index_serie && arrayCombos[i].index_data == cons_index_data){
			arrayCombos.splice(i, 1);
			break;
		}
	}
}

function exportGraficoToPdf(idChart, nGrafico, email){ 
	var correo_destino = $("#correoDestino").val();
	if(email == 1 && (correo_destino == null || correo_destino.trim() == "")){
		mostrarNotificacion('warning','Ingrese un correo electr&oacute;nico');
	}else{
		var chart = $("#"+idChart).highcharts();
	    if(chart){
	    	var doc = new jsPDF('landscape');
	        var chartHeight = 100;
	        var currentdate = new Date(); 
	        var datetime =    currentdate.getDate() + "-"
	                        + (currentdate.getMonth()+1)  + "-" 
	                        + currentdate.getFullYear() + " "  
	                        + currentdate.getHours() + ":"  
	                        + currentdate.getMinutes() + ":" 
	                        + currentdate.getSeconds();
	        doc.setFontSize(15);
	        doc.setTextColor(149,149,149);
	        doc.setFontType("bold");
	        var titulo = "";
	        if(idChart == "container_grafico_1"){
	        	titulo = $("#selectEncuestaGrafico1 option:selected").text();
	        	var textWidth = doc.getStringUnitWidth(titulo) * doc.internal.getFontSize() / doc.internal.scaleFactor;
			    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
			    doc.text(textOffset, 30, titulo);
	        }else if(idChart == "container_grafico_2"){
	        	y = 10;
	        	var count = $("#selectTipoEncuestaGrafico2 :selected").length;
	        	if(count == 1){
	        		titulo = $("#selectTipoEncuestaGrafico2 option:selected").text();
		        	var textWidth = doc.getStringUnitWidth(titulo) * doc.internal.getFontSize() / doc.internal.scaleFactor;
				    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
				    doc.text(textOffset, 30, titulo);
	        	}else{
	        		$('#selectTipoEncuestaGrafico2').children('option:selected').each( function() {
		    	        var $this = $(this);
		    	        var textWidth = doc.getStringUnitWidth($this.text()) * doc.internal.getFontSize() / doc.internal.scaleFactor;
		    		    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
		    		    doc.text(textOffset, y, $this.text());
		    		    y = y + 15;
		    	    });
	        	}
	        }else if(idChart == "container_grafico_3"){
	        	titulo = $("#selectEncuestaGrafico3 option:selected").text();
	        	var textWidth = doc.getStringUnitWidth(titulo) * doc.internal.getFontSize() / doc.internal.scaleFactor;
			    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
			    doc.text(textOffset, 30, titulo);
	        }
	        
		    doc.setFontType("normal");
	    	var imageData = chart.createCanvas();			      
	        doc.addImage(imageData, 'JPEG', 25, 50, 240, 150);	
	        
	        /*var base64 = getBase64Image(document.getElementById("logo_avantgard_none"));
	        doc.addImage(base64, 'JPEG', 30, 5, 60, 30);*/
	        
	        var arrayFiltros = [];
			if(nGrafico == 1){
				var obj = { 
					    Tipo_Encuesta : $('#selectTipoEncuestaGrafico1 option:selected').val(),
					    Encuesta      : $('#selectEncuestaGrafico1 option:selected').val(),
					    Preguntas     : $('#selectPreguntaGrafico1').val()
					};
				arrayFiltros.push(obj);
			}else if(nGrafico == 2){
				var obj = { 
					    Tipo_Encuesta : $('#selectTipoEncuestaGrafico2').val(),
					    Preguntas     : $('#selectPreguntaGrafico2').val()
					};
				arrayFiltros.push(obj);
			}else if(nGrafico == 3){
				var obj = { 
					    Tipo_Encuesta    : $('#selectTipoEncuestaGrafico3').val(),
					    Encuesta         : $('#selectEncuestaGrafico3').val(),
					    Propuesta_Mejora : $('#selectPropuestaMejoraGrafico3').val()
					};
				arrayFiltros.push(obj);
			}
			especifico = null;
			$.ajax({
				data : {filtroChart           : JSON.stringify(arrayFiltros),
					   filtroChartEspecificos : JSON.stringify(arrayCombos),
					   nGrafico : nGrafico},
				url  : '../c_download_excel/getFiltrosPDF', 
				async: true,
				type : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				$("#tableNone").html(data.tablaFiltros);
				$("#tableNone1").html(data.tablaFiltrosEspecificos);
				especifico = data.especifico;
			});
			
			var table = tableToJson($('#table').get(0));
			var table1 = tableToJson($('#table1').get(0))
			
			doc.addPage();
			doc.text(120, 25, "Filtros");
			doc.setTextColor(0, 0, 0);
			doc.setFontSize(10);
			$.each(table, function (i, row){
	            $.each(row, function (j, cell){
	            	//x , y , width de la celda, height de la celda
	                doc.cell(10, 40, 92, 10, decodeURIComponent(escape(cell)), i);
	            })
	        })
	        
	        doc.addPage();
			doc.text(20, 10, especifico);
			
	        
	        if(nGrafico == 2){
	        	doc.addPage();
				doc.setFontSize(25);
		        doc.setTextColor(149,149,149);
				doc.text(120, 25, "Filtros Especificos");
				doc.setTextColor(0, 0, 0);
				doc.setFontSize(10);
				$.each(table1, function (i, row){
		            $.each(row, function (j, cell){
		            	//x , y , width de la celda, height de la celda
		                doc.cell(10, 40, 92, 10, cell, i);
		            })
		        });
	        }
	        
			if(email == 1){
				enviarEmailPDF(doc, correo_destino);
			}else{
				doc.save( "Grafico_"+datetime+".pdf");
			}
	    }
	}   
}

function enviarEmailPDF(doc, correo_destino){
	pdf = doc.output('datauristring');
    $.ajax({
		data : {docpdf : pdf,
			    correo : correo_destino},
		url  : '../c_download_excel/enviarPDFEmail', 
		async: true,
		type : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			mostrarNotificacion('success',data.msj);
			abrirCerrarModal("enviarEmailModal");
		}else{
			mostrarNotificacion('success',data.msj);
		}
	});
}

function exportGraficoToExcel(idChart, nGrafico, email){
	var correo_destino = $("#correoDestino").val();
	if(email == 1 && (correo_destino == null || correo_destino.trim() == "")){
		mostrarNotificacion('warning','Ingrese un correo electr&oacute;nico');
	}else{
		var chart = $("#"+idChart).highcharts();
		if(chart){
			csv = chart.getCSV();
			var lines = csv.split('\n');
			$("#jsonChart").val(JSON.stringify(lines));
			$("#typeChart").val(sessionStorage.chartType);
			var arrayFiltros = [];
			if(nGrafico == 1){
				var obj = { 
					    Tipo_Encuesta : $('#selectTipoEncuestaGrafico1 option:selected').val(),
					    Encuesta      : $('#selectEncuestaGrafico1 option:selected').val(),
					    Preguntas     : $('#selectPreguntaGrafico1').val()
					};
				arrayFiltros.push(obj);
			}else if(nGrafico == 2){
				var obj = { 
					    Tipo_Encuesta : $('#selectTipoEncuestaGrafico2').val(),
					    Preguntas     : $('#selectPreguntaGrafico2').val()
					};
				arrayFiltros.push(obj);
			}else if(nGrafico == 3){
				var obj = { 
					    Tipo_Encuesta    : $('#selectTipoEncuestaGrafico3').val(),
					    Encuesta         : $('#selectEncuestaGrafico3').val(),
					    Propuesta_Mejora : $('#selectPropuestaMejoraGrafico3').val()
					};
				arrayFiltros.push(obj);
			}else if(nGrafico == 4){
				var obj = { 
					    Tipo_Encuesta : $('#selectTipoEncuestaGrafico4 option:selected').val(),
					    Encuesta      : $('#selectEncuestaGrafico4 option:selected').val()
					};
				arrayFiltros.push(obj);
			}
			$("#filtroChart").val(JSON.stringify(arrayFiltros));
			$("#filtroChartEspecifico").val(JSON.stringify(arrayCombos));
			$("#nGrafico").val(nGrafico);
			$("#enviarEmail").val(email);
			$("#correoDestinoCont").val(correo_destino);
			$("#myForm")[0].submit();
			if(email == 1){
				abrirCerrarModal("enviarEmailModal");
				mostrarNotificacion('success','Se envió correctamente el correo');
			}
		}
	}
}

function openModalCorreoDestino(idChart, nGrafico, email){
	$('#btnEnviarEmail').removeAttr('onclick');
	$('#btnEnviarEmail').attr('onClick', "exportGraficoToExcel('"+idChart+"', "+nGrafico+", "+email+")");
	$("#correoDestino").val("");
	abrirCerrarModal("enviarEmailModal");
}

function openModalCorreoDestino1(idChart, nGrafico, email){
	$('#btnEnviarEmail').removeAttr('onclick');
	$('#btnEnviarEmail').attr('onClick', "exportGraficoToPdf('"+idChart+"', "+nGrafico+", "+email+")");
	$("#correoDestino").val("");
	abrirCerrarModal("enviarEmailModal");
}

function getBase64Image(img) {
	  var canvas = document.createElement("canvas");
	  canvas.width = img.width;
	  canvas.height = img.height;
	  var ctx = canvas.getContext("2d");
	  ctx.drawImage(img, 0, 0);
	  var dataURL = canvas.toDataURL("image/jpeg");
	  return dataURL;
}

function tableToJson(table) {
    var data    = [];
    var headers = [];
    for (var i = 0; i < table.rows[0].cells.length; i++) {
        headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi,'');
    }
    for (var i = 0; i < table.rows.length; i++) {
        var tableRow = table.rows[i];
        var rowData  = {};
        for (var j = 0; j < tableRow.cells.length; j++) {
            rowData[headers[j]] = tableRow.cells[j].innerHTML;
        }
        data.push(rowData);
    }       

    return data;
}

function getAreasBySedeGrafico2(){
	var id_sede = $('#selectSedeGrafico2').val();
	if(id_sede == null){
		setMultiCombo("selectNivelGrafico2", null);
		setMultiCombo("selectGradoGrafico2", null);
		setMultiCombo("selectAulaGrafico2", null);
		setMultiCombo("selectAreaGrafico2", null);
		getGraficoByPreguntaGrafico2();
	} else{
		$.ajax({
			data : {id_sede       : id_sede,
				    tipo_encuesta : cons_id_tipo_encuesta,
				    pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType},
			url  : 'c_g_comparar_preg/getAreasBySede', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.tipo == 1){
				setMultiCombo("selectAreaGrafico2", data.areas);
				setMultiCombo("selectGradoGrafico2", null);
				setMultiCombo("selectAulaGrafico2", null);
				setMultiCombo("selectNivelGrafico2", null);
			}else{
				setMultiCombo("selectNivelGrafico2", null);
				setMultiCombo("selectGradoGrafico2", null);
				setMultiCombo("selectAulaGrafico2", null);
				setMultiCombo("selectAreaGrafico2", null);
			}
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function getGraficoByAreaSedeGrafico2(){
	var id_sede  = $('#selectSedeGrafico2').val();
	var id_area  = $('#selectAreaGrafico2').val();
	if(id_area == null){
		getAreasBySedeGrafico2();
	}else{
		$.ajax({
			data : {id_area       : id_area,
				    id_sede       : id_sede,
				    tipo_encuesta : cons_id_tipo_encuesta,
			        pregunta      : cons_id_pregunta,
				    satisfaccion  : sessionStorage.satisfType},
			url  : 'c_g_comparar_preg/getDataByAreaSede', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			populateArray();
			updateDataToSerie(data.porcentaje);
		});
	}
}

function refreshSection2(){
	
}
