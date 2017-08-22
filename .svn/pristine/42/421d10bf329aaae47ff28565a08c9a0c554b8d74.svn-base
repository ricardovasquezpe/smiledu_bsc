/*CONSTANTES*/
var array_preguntas = [];
var cons_i = 0;
var estadoActual = 0;
$("#options_4").next().css("display", "none");
/*FIN CONSTANTES*/
function getEncuestasByTipo4(){
	addLoadingButton('btnMFGEP');
	var tipo_encuesta = $('#selectTipoEncuestaGrafico4 option:selected').val();
	$.ajax({
		data  : {tipo_encuesta : tipo_encuesta},
		url   : 'c_g_pregunta/getEncuestaByTipoEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#grafico4 #cont_filter_empty').css('display', 'none');
		$('#container_grafico_4').html(ruta_not_data_fab);
		$('#grafico4 .mdl-card').removeAttr('style');		
		$('#contNivelesByTipoEnc').css('display','none');
		$('#cont_selectTipoEncuestadoGrafico4').css('display','none');
		setMultiCombo('selectEncuestaGrafico4',data.optEnc);
		setMultiCombo('selectPreguntaGrafico4',null);
		$('#selectTipoEncuestadoGrafico4').selectpicker('val', null);
		$("#cont_selectTipoEncuestadoGrafico4").css('display','none');
		stopLoadingButton('btnMFGEP');
	});
}

function getPreguntasByEncuesta4(){
	addLoadingButton('btnMFGEP');
	Pace.restart();
	Pace.track(function() {
		var encuesta = $('#selectEncuestaGrafico4').val();
		var tipo_encuesta = $('#selectTipoEncuestaGrafico4 option:selected').val();
		$("#cont_btn_descargar_all_pdf").css('display', 'none');
		if(encuesta != null){
			$.ajax({
				data  : {encuesta 	   : encuesta,
					     tipo_encuesta : tipo_encuesta},
				url   : 'c_g_pregunta/getPreguntasByEncuesta',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#container_grafico_4').html(ruta_not_data_found);
				setMultiCombo('selectPreguntaGrafico4',data.optPreg);
				$('#selectTipoEncuestadoGrafico4').selectpicker('val', null);
				$("#container_grafico_4").empty();
				createGraficosTotal(data.preguntas);
				array_preguntas = [];
				$("#cont_btn_descargar_all_pdf").css('display', 'block');
				if(data.tipoencuestado == 1){
					$("#cont_selectTipoEncuestadoGrafico4").css('display','block');
					setCombo('selectTipoEncuestadoGrafico4', data.optEncTipo, 'tipo de encuestado');
				}else{
					$('#contNivelesByTipoEnc').html(data.optNiveles);
					$("#contNivelesByTipoEnc").css('display','block');
					$('#selectSedeGrafico4').selectpicker({noneSelectedText: 'Seleccione Sede'});
		    		$('#selectNivelGrafico4').selectpicker({noneSelectedText: 'Seleccione Nivel'});
		    		$('#selectGradoGrafico4').selectpicker({noneSelectedText: 'Seleccione Grado'});
		    		$('#selectAulaGrafico4').selectpicker({noneSelectedText: 'Seleccione Aula'});
		    		$('#selectAreaGrafico4').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
				}
				stopLoadingButton('btnMFGEP');
			});
		} else {
			$('#container_grafico_4').html(ruta_not_data_fab);
			setCombo('selectPreguntaGrafico4', null, ' una pregunta');
			$('#cont_selectTipoEncuestadoGrafico4').css('display','none');
			stopLoadingButton('btnMFGEP');
		}
	});
}

function getGraficoEncuestaPregunta4(){
	addLoadingButton('btnMFGEP');
	Pace.restart();
	Pace.track(function() {
		var pregunta  = $('#selectPreguntaGrafico4').val();
		if(pregunta != null){
			if(pregunta.length == 1 && Object.keys(array_preguntas).length == 0){
		        $("#container_grafico_4").empty();
		        chart = $('.container_grafico_4').highcharts();
		        if(chart != null){
		        	$('.container_grafico_4').highcharts().destroy();
		        }
			}
			if($.selectTodo == true && estadoActual == 0){
				$("#container_grafico_4").empty();
		        chart = $('.container_grafico_4').highcharts();
		        if(chart != null){
		        	$('.container_grafico_4').highcharts().destroy();
		        }
		        $.selectTodo = undefined;
		        estadoActual = 1;
			}
			if(pregunta.length > array_preguntas.length){
				var encuesta  = $('#selectEncuestaGrafico4').val();
				var tipo_encu = $('#selectTipoEncuestaGrafico4 option:selected').val();
				$.ajax({
					data  : {pregunta  : pregunta.diff(array_preguntas),
						     encuesta  : encuesta,
						     tipo_encu : tipo_encu},
					url   : 'c_g_pregunta/getGraficoEncuestaByPregunta',
					type  : 'POST',
					async : true
				})
				.done(function(data){
					data = JSON.parse(data);
					if(Object.keys(data.preguntas).length == 0){
						$('#container_grafico_4').html(ruta_not_data_fab);
					}
					createGraficosTotal(data.preguntas);
					array_preguntas = pregunta;
					$('#selectTipoEncuestadoGrafico4').selectpicker('val', null);
				});
			}else{
				$('div[data-pregunta="' + array_preguntas.diff(pregunta)[0] + '"]').remove();
				array_preguntas = pregunta;
			}
			stopLoadingButton('btnMFGEP');
		}else{
			estadoActual = 0;
			getPreguntasByEncuesta4();
			stopLoadingButton('btnMFGEP');
		}
	});
}

function getGraficoTipoEncuestado4(){
	Pace.restart();
	Pace.track(function() {
		var encuesta    = $('#selectEncuestaGrafico4').val();
		var tencuestado = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var pregunta    = $('#selectPreguntaGrafico4').val();
		if(tencuestado.length != 0 && encuesta != null){
			$.ajax({
				data  : {tencuestado : tencuestado,
					     encuesta    : encuesta,
					     pregunta    : pregunta},
				url   : 'c_g_pregunta/getGraficobyTipoEncuestado',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#contNivelesByTipoEnc').html(data.combos);
				if(data.combos != null){
					$('#contNivelesByTipoEnc').css('display','block');
					$('#selectSedeGrafico4').selectpicker({noneSelectedText: 'Seleccione Sede'});
		    		$('#selectNivelGrafico4').selectpicker({noneSelectedText: 'Seleccione Nivel'});
		    		$('#selectGradoGrafico4').selectpicker({noneSelectedText: 'Seleccione Grado'});
		    		$('#selectAulaGrafico4').selectpicker({noneSelectedText: 'Seleccione Aula'});
		    		$('#selectAreaGrafico4').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
				}
				$("#container_grafico_4").empty();
		        chart = $('.container_grafico_4').highcharts();
		        if(chart != null){
		        	$('.container_grafico_4').highcharts().destroy();
		        }
				createGraficosTotal(data.preguntas);
			});
		}else{
			$("#container_grafico_4").empty();
	        chart = $('.container_grafico_4').highcharts();
	        if(chart != null){
	        	$('.container_grafico_4').highcharts().destroy();
	        }
			array_preguntas = [];
			getGraficoEncuestaPregunta4();
		}
	});
}

Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

function createGraficosTotal(data){
	data = JSON.parse(data);
	if(Object.keys(data).length == 0){
		$("#options_4").next().find("ul").css("display", "none");
		$( "#options_4" ).attr("disabled", "disabled");
		$(".btn_cambio_vista_4").addClass('btn_opacity');
	}else{
		$("#options_4").next().find("ul").css("display", "block");
		$(".btn_cambio_vista_4").removeClass('btn_opacity');
		$( "#options_4" ).removeAttr("disabled");
	}
	for(var i = 0;i < Object.keys(data).length; i++){
		var nameCont = "container_grafico_4_"+cons_i;
		$("#container_grafico_4").append("<div id='"+nameCont+"' data-pregunta='"+data[i].pregunta+"' class='col-sm-12 separator-questions container_grafico_4'></div>");
		initGraficoUnaOpcion(data[i], nameCont);
		cons_i++;
	}
}

function initGraficoUnaOpcion(data, container){
	$("#grafico4 .mdl-card__menu").css("display", "block");
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
            type : data.tipo_grafico
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
            enabled : false
        },
        title: {
            text: data.titulo,
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
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            max: 100
        }],
        tooltip: {
        	shared: false,
            formatter: function () {
//            	var num = data.titulo.match(/[\d\.]+/g);
//            	var number = num.toString();
            	n = Math.round((this.y*(data.total))/100);
            	return 'Cantidad: ' + n;
            }
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
        }
	}
	$('#'+container).highcharts(options);
	var chart = $('#'+container).highcharts();
	for(i = 0; i < arrayCount.length; i++){
		chart.addSeries({
			colorByPoint: true,
	        name  : arrayName[i],
	        data  : arrayCount[i],
	        zIndex: 1,
	        pointWidth: 25
	    });
	}
}

function downloadAllGraficosPDF(){
	var numItems = $('.container_grafico_4').length;
	if(numItems != 0){
		var doc = new jsPDF();
	    var chartHeight = 100;
	    var currentdate = new Date(); 
	    var datetime    = currentdate.getDate() + "-"
	                    + (currentdate.getMonth()+1)  + "-" 
	                    + currentdate.getFullYear() + " "  
	                    + currentdate.getHours() + ":"  
	                    + currentdate.getMinutes() + ":" 
	                    + currentdate.getSeconds();
	    doc.setFontType("bold");
	    doc.setFontSize(10);
	    doc.setTextColor(149,149,149);

	    var count = $("#selectEncuestaGrafico4 :selected").length;
	    if(count == 1){
	    	titulo = $("#selectEncuestaGrafico4 option:selected").text();
        	var textWidth = doc.getStringUnitWidth(titulo) * doc.internal.getFontSize() / doc.internal.scaleFactor;
		    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
		    doc.text(textOffset, 30, titulo);
	    }else{
	    	y = 10;
		    $('#selectEncuestaGrafico4').children('option:selected').each( function() {
		        var $this = $(this);
		        var textWidth = doc.getStringUnitWidth($this.text()) * doc.internal.getFontSize() / doc.internal.scaleFactor;
			    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
			    doc.text(textOffset, y, $this.text());
			    y = y + 5;
		    });
	    }
	    
	    doc.setFontType("normal");
	    var sedes = $("#selectSedeGrafico4 option:selected").text();
	    if(sedes.length != 0){
	    	sedesText = "";
	    	i = 0;
	    	$('#selectSedeGrafico4').children('option:selected').each( function() {
	    		var $this = $(this);
	    		if(i == 0){
	    			sedesText = $this.text();
	    		}else{
	    			sedesText += ", " + $this.text(); 
	    		}
	    		i++;
	    	});
	        var textWidth = doc.getStringUnitWidth(sedesText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
		    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
		    doc.text(textOffset, 10, "( "+sedesText+" )");
	    }
	    /*var base64 = getBase64Image(document.getElementById("logo_avantgard_none"));
	    doc.addImage(base64, 'JPEG', 10, 5, 30, 30);*/
//	    base64 = getBase64Image(document.getElementById("logo_merced_none"));
//	    doc.addImage(base64, 'JPEG', 140, 5, 50, 20);
	    
	    var i = 0;
	    var numItems = $('.container_grafico_4').length;
		$(".container_grafico_4").each(function(){
			var chart = $(this).highcharts();
		    if(chart){
		        var imageData = chart.createCanvas();	
		        doc.setDrawColor(255,193,7);
		        if (i % 2 == 0){
		        	doc.rect(23, 45, 160, 110);
		        	doc.addImage(imageData, 'JPEG', 25, 50, 155, 100);	
		        }else{
		        	doc.rect(23, 160, 160, 110);
		        	doc.addImage(imageData, 'JPEG', 25, 165, 155, 100);	
		        	if((i+1) != numItems){
		        		doc.addPage();
		        	}
		        }
		        i++;
		    }
		});
		
		doc.save( "Grafico_"+datetime+".pdf");
	}
}

 function downloadAllGraficosPDF_1(){
	 var numItems = $('.container_grafico_4').length;
		if(numItems != 0){
			var doc = new jsPDF('landscape');
		    var chartHeight = 100;
		    var currentdate = new Date(); 
		    var datetime =    currentdate.getDate() + "-"
		                    + (currentdate.getMonth()+1)  + "-" 
		                    + currentdate.getFullYear() + " "  
		                    + currentdate.getHours() + ":"  
		                    + currentdate.getMinutes() + ":" 
		                    + currentdate.getSeconds();
		    doc.setFontType("bold");
		    doc.setFontSize(10);
		    doc.setTextColor(149,149,149);
		    
		    var count = $("#selectEncuestaGrafico4 :selected").length;
		    if(count == 1){
		    	titulo = $("#selectEncuestaGrafico4 option:selected").text();
	        	var textWidth = doc.getStringUnitWidth(titulo) * doc.internal.getFontSize() / doc.internal.scaleFactor;
			    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
			    doc.text(textOffset, 30, titulo);
		    }else{
		    	y = 10;
			    $('#selectEncuestaGrafico4').children('option:selected').each( function() {
			        var $this = $(this);
			        var textWidth = doc.getStringUnitWidth($this.text()) * doc.internal.getFontSize() / doc.internal.scaleFactor;
				    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
				    doc.text(textOffset, y, $this.text());
				    y = y + 5;
			    });
		    }
		    doc.setFontType("normal");
		    var sedes = $("#selectSedeGrafico4 option:selected").text();
		    if(sedes.length != 0){
		    	sedesText = "";
		    	i = 0;
		    	$('#selectSedeGrafico4').children('option:selected').each( function() {
		    		var $this = $(this);
		    		if(i == 0){
		    			sedesText = $this.text();
		    		}else{
		    			sedesText += ", " + $this.text(); 
		    		}
		    		i++;
		    	});
		        var textWidth = doc.getStringUnitWidth(sedesText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
			    var textOffset = (doc.internal.pageSize.width - textWidth) / 2;
			    doc.text(textOffset, y, "( "+sedesText+" )");
		    }
		    
		    /*var base64 = getBase64Image(document.getElementById("logo_avantgard_none"));
		    doc.addImage(base64, 'JPEG', 30, 5, 30, 30);*/
//		    base64 = getBase64Image(document.getElementById("logo_merced_none"));
//		    doc.addImage(base64, 'JPEG', 210, 5, 50, 20);
		    
		    var i = 0;
		    var numItems = $('.container_grafico_4').length;
			$(".container_grafico_4").each(function(){
				var chart = $(this).highcharts();
			    if(chart){
			        var imageData = chart.createCanvas();	
			        if (i % 2 == 0){
			        	doc.addImage(imageData, 'JPEG', 25, 50, 120, 90);	
			        }else{
			        	doc.addImage(imageData, 'JPEG', 145, 50, 120, 90);	
			        	if((i+1) != numItems){
			        		doc.addPage();
			        	}
			        }
			        i++;
			    }
			});
			
			doc.save( "Grafico_"+datetime+".pdf");
		}
}
 
 function donwloadExcelEncuesta(){
	 var encuesta    = $('#selectEncuestaGrafico4').val();
	 var pregunta    = $('#selectPreguntaGrafico4').val();
     
	 
	 $("#jsonencuestas").val(JSON.stringify(encuesta));
	 $("#jsonpreguntas").val(JSON.stringify(pregunta));
	 $("#myForm_1")[0].submit();
 }
 
function getNivelesBySedeGrafico4(){
	addLoadingButton('btnMFGEP');
	Pace.restart();
	Pace.track(function() {
		var encuesta      = $('#selectEncuestaGrafico4').val();
		var tencuestado   = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var pregunta      = $('#selectPreguntaGrafico4').val();
		var sedes         = $('#selectSedeGrafico4').val();
		var tipo_encuesta = $('#selectTipoEncuestaGrafico4 option:selected').val();
		if(encuesta != null){
			$.ajax({
				data  : {tencuestado   : tencuestado,
					     encuesta      : encuesta,
					     pregunta      : pregunta,
					     sedes         : sedes,
					     tipo_encuesta : tipo_encuesta},
				url   : 'c_g_pregunta/getGraficobySede',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$("#container_grafico_4").empty();
		        chart = $('.container_grafico_4').highcharts();
		        if(chart != null){
		        	$('.container_grafico_4').highcharts().destroy();
		        }
				createGraficosTotal(data.preguntas);
				stopLoadingButton('btnMFGEP');
			});
		}else{
			$("#container_grafico_4").empty();
	        chart = $('.container_grafico_4').highcharts();
	        if(chart != null){
	        	$('.container_grafico_4').highcharts().destroy();
	        }
			array_preguntas = [];
			stopLoadingButton('btnMFGEP');
			getGraficoEncuestaPregunta4();
		}
	});
}
 
function getGradosByNivelSedeGrafico4(){
	 
 }

function getAulasByNivelGrafico4(){
	
}

function getGraficoByAula4(){
	
}

function getAreasBySedeGrafico4(){
	Pace.restart();
	Pace.track(function() {
		var encuesta      = $('#selectEncuestaGrafico4').val();
		var tencuestado   = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var pregunta      = $('#selectPreguntaGrafico4').val();
		var sedes         = $('#selectSedeGrafico4').val();
		var tipo_encuesta = $('#selectTipoEncuestaGrafico4 option:selected').val();
		$.ajax({
			data  : {sedes     		: sedes,
				     pregunta  		: pregunta,
				     encuesta  		: encuesta,
				     tipo_encuesta 	: tipo_encuesta,
				     tencuestado    : tencuestado},
			url   : 'c_g_pregunta/getAreasBySedeGraficos',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
//			setMultiCombo('selectAreaGrafico1', data.comboAreas);
			$("#container_grafico_4").empty();
	        chart = $('.container_grafico_4').highcharts();
	        if(chart != null){
	        	$('.container_grafico_4').highcharts().destroy();
	        }
			createGraficosTotal(data.preguntas);
		});
	});
}

function refreshSection4(){
	
}