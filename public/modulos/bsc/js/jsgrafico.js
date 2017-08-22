var t = 0;
var dy = 0;

function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	initButtonLoad('botonFI');
}

function getGraficoBySedesYear(){
	var year =  $('#selectYear option:selected').val();
	if(t == 1 || t == 2 || t == 4 || t == 5 || t == 6){
		getGraficoBySedes(year);
	}else if(t == 3){
		getGraficoByDisciplinas(year);
	}
}

function getGraficoBySedes(year){
	addLoadingButton('botonFI');
	var sedes = $('#multiSedes').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoComparando',
		data	: {sedes : sedes,
			       tipo  : t,
			       year  : year},
		'async' : true
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			//stopLoadingBUtton('botonGrafico');
			initGrafico(data);
		}
		stopLoadingButton('botonFI');
	});
}

function getGraficoByDisciplinas(year){
	var disciplinas = $('#multiDisciplinas').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoComparando',
		data	: {disciplinas : disciplinas,
			       tipo  : t,
			       year  : year},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
		}
	});
}

function getObjetivosByLinea() {
	addLoadingButton('botonFI');
	var idLinea =  $('#selectLinea option:selected').val();
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_grafico/comboObjetivos',
    	data   : {idLinea : idLinea},
    	'async': true
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			$('#container-card').css("display", "none");
			limpiarCombos(data);
		} else {
			$('#container-card').css("display", "none");
			limpiarCombos(data);
		}
		stopLoadingButton('botonFI');
	});
}

function getCategoriaByObjetivo() {
	addLoadingButton('botonFI');
	var idObjetivo =  $('#selectObjetivo option:selected').val();
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_grafico/comboCategorias',
    	data   : {idObjetivo : idObjetivo},
    	'async': true
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectCategoria', data.comboCategoria, 'Categoria');
			$('#contCombosFiltro').html("");
			$('#contYear').css('display','none');
			$('#cont_not_found_fab').css("display", "block");
			$('#container-card').css("display", "none");
			$('#container').html("");

			//$('#contCombosFiltro').html("");
			//$('#container').html('<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/filter_fab.png" class="imgBuho">');
		} else {
			limpiarCombos(data);
			$('#container-card').css("display", "none");
		}
		stopLoadingButton('botonFI');
	});
}

function getIndicadoresByCategoria() {
	addLoadingButton('botonFI');
	var idCategoria = $('#selectCategoria option:selected').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/comboIndicadores',
		data	: {idCategoria : idCategoria},
		'async' : true
	})
	.done(function(data) {
		addLoadingButton('botonFI');
		data = JSON.parse(data);
		var chart = $("#container").highcharts();
		if(chart != undefined){
			$('#container').highcharts().destroy();
		}
		$("#container").html("");
		if(data.error == 0) {
			setCombo('selectIndicador', data.comboIndicador, 'Indicador');
			$('#contCombosFiltro').html("");
			$('#contYear').css('display','none');
			$('#cont_not_found_fab').css("display", "block");
			$('#container-card').css("display", "none");
			$('#container').html("");
		} else {
			setCombo('selectIndicador', null, 'Indicador');
			$('#contCombosFiltro').html("");
			$('#contYear').css('display','none');
			$('#cont_not_found_fab').css("display", "block");
			$('#container-card').css("display", "none");
			$('#container').html("");
		}
		stopLoadingButton('botonFI');
	});
	componentHandler.upgradeAllRegistered();
}

function getGraficoByIndicador(cod) {
	addLoadingButton('botonFI');
	
	Pace.restart();
	Pace.track(function() {
		var idIndicador;
		if(!cod) {
			idIndicador = $('#selectIndicador option:selected').val();
		} else {
			idIndicador = $('#selectIndi option:selected').val();
		}
		$.ajax({
			url   : "c_grafico/getGraficoByIndicador",
			type  : 'POST',
			data  : {idIndicador : idIndicador},
			'async' : true
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				if(!cod) {
					$('#contCombosFiltroCod').html(null);
					setCombo("selectIndi", null, "Indicador", null);
					setearInput("codigo", null);
					$('#contCombosFiltro').html(data.combosFiltro);
					$('#container-card').css("display", "block");
				} else {
					$('#contCombosFiltro').html(null);
					setCombo("selectIndicador", null, "Indicador", null);
					setCombo("selectCategoria", null, "Categoria", null);
					setCombo("selectObjetivo", null, "Objetivo", null);
					setearCombo("selectLinea", null);
					$("#cont_not_found_fab").css("display", "none");
					$('#containter-card').css("display","none");	
					$('#contCombosFiltroCod').html(data.combosFiltro);
				}
				$('#contYear').css('display','none');
				initGrafico(data);
				init();
				t = data.tipo;
			} else {
				$('#contCombosFiltro').html("");
				$('#containter-card').css("display","none");	
				$("#cont_not_found_fab").css("display", "block");
				//$('#container').html('<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/filter_fab.png" class="imgBuho">');
			}
			stopLoadingButton('botonFI');
			
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			 stopLoadingButton('botonFI');
			 mostrarNotificacion('error','Comun&iacute;quese con alguna persona a cargo :(', 'Error');
	 	})
	 	.always(function() {		      	 
	 	});	
		componentHandler.upgradeAllRegistered();
	});
}

function buscarIndiByCod(e) {
	var ok = false;
	if(e == undefined) {
		ok = true;
	} else {
		if (e.keyCode == 13) {
	        ok = true;
	    }
	}
	if(!ok) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		var codigo = $('#codigo').val();
		$.ajax({
			url   : "c_grafico/getIndicadoresByCodigo",
			type  : 'POST',
			data  : { codigo : codigo }
		})
		.done(function(data) {
			data = JSON.parse(data);
			setCombo('selectIndi', null, 'Indicador', false);
			$('#contCombosFiltro').html(null);
			$('#contCombosFiltroCod').html(null);
			$('#contYear').css('display','none');
			$('#cont_not_found_fab').css("display", "block");
			$('#container').html("");
			if(data.error == 0) {
				setCombo('selectIndi', data.indi_combo, 'Indicador', false);
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			 mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
	 	})
	 	.always(function() {		      	 
	    
	 	});
	});
}

function initGrafico(data) {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1;
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
	var fileName = 'indicador_' + today;
	
	var years = data.years;
	arrayYears = JSON.parse(years);
	
	var metas = data.metas;
	arrayMetas = JSON.parse(metas);
	
	var actuales = data.actuales;
	arrayActuales = JSON.parse(actuales);
	
	var comparativas = data.comparativas;
	comparativas = JSON.parse(comparativas);
	
	//$('#contYear').css('display','none');
	if(arrayYears.length == 0){
		$('#cont_not_found_fab').css("display", "block");
		$('#cont_search_not_filter').css("display", "none");
		$('#container').html(null);
	}else{
		$('#cont_not_found_fab').css("display", "none");
		var titulo = data.titulo;
		var options = {
			chart: {
	            zoomType: 'xy'
	        },
	        title: {
	            text: titulo,
	            align: 'left'
	        },
	        legend: {
	              itemStyle: {
	                  fontSize:'13px',
	                  font: '13pt Trebuchet MS, Verdana, sans-serif',
	                  color: 'black'
	               },
	            
	         },
	        exporting: {
	            filename: fileName,
	            formAttributes: {
	            	encoding: 'iso-utf-8'
            	}
	        },
	        lang: {
	            printChart: 'Imprimir Grafico',
	            downloadPNG: 'Descargar PNG',
	            downloadJPEG: 'Descarga JPEG',
	            downloadPDF: 'Descarga PDF',
	            downloadSVG: 'Descarga SVG',
	            contextButtonTitle: 'Context menu'
	        },
	        subtitle: {
	            text: ''
	        },
	        plotOptions: {
	            column: {
	                dataLabels: {
	                    enabled: true,
	                    overflow: 'none',
	                    padding : -20, 
	                    x : 45,
	                    formatter: function() {return (this.y > 0) ? '<p style="color: '+this.color+'">'+this.y : "";},
	                    style: { fontFamily: '\'Lato\', sans-serif', lineHeight: '18px', fontSize: '20px' }
	                }
	            },
	            spline: {
	                dataLabels: {
	                    enabled: true,
	                    formatter: function() {return (this.y > 0) ? '<p style="color: '+this.color+'">'+this.y : "";},
	                    padding : 5, 
	                    x : -20,
	                    style: { fontFamily: '\'Lato\', sans-serif', lineHeight: '18px', fontSize: '20px' }
	                },
	                marker: {
	                    lineWidth: 2,
	                    lineColor: "black",
	                    fillColor: 'white'
	                }
	            },
	            waterfall: {
	                dataLabels: {
	                    enabled: true,
	                    crop: false,
	                    overflow: 'none',
	                    formatter: function() {return '<p>'+this.series.name[0]+": "+this.y+"</p>"; },
	                    style: { fontFamily: '\'Lato\', sans-serif', lineHeight: '18px', fontSize: '20px' }
	                }
	            }
	        },
	        xAxis: [{
	            categories: arrayYears,
	            crosshair: true,
	            labels: {
	                style: {
	                    color: 'black',
	                    fontSize:'13px'
	                }
	            }
	        }],
	        yAxis: [{
	            labels: {
	                format: '{value}',
	                style: {
	                    color: 'black',
	                    fontSize:'13px'
	                }
	            },
	            title: {
	                text: '',
	                style: {
	                    color: Highcharts.getOptions().colors[2]
	                }
	            },
	            opposite: true,
	            type: 'category',
	            reversed: (data.ppu == 1) ? true : false
	        }],
	        tooltip: {
	            shared: false
	        }
		}
		
		$('#container').highcharts(options);
	
		var chart = $('#container').highcharts();
		var maxVal = null;
		if(data.maxVal.length != 0){
			maxVal = parseInt(data.maxVal);
			chart.yAxis[0].update({
	            max: maxVal
			});
		}
		
	    chart.addSeries({
	        name: 'Actual',
	        color:"#4CAF50",
	        data: arrayActuales,
	        pointWidth: 30
	    });
	    
	    chart.addSeries({
	        name: 'Meta',
	        data: arrayMetas,
	        color:"black"
	    });
	    
	    if(data.ppu == 1){
	    	chart.series[0].update({
		        type: "spline"
		    });
	    }else{
	    	chart.series[0].update({
		        type: "column"
		    });
	    }

	    chart.series[1].update({
	        type: "spline"
	    });
	
	    var c = 2;
	    for(var i = 0; i < comparativas.length; i++) {
			for(var j = 0;j < comparativas[i].length;j++){
				var data = comparativas[i][j].slice(0,(comparativas[i][j].length-1));
				var name = comparativas[i][j].slice((comparativas[i][j].length-1),(comparativas[i][j].length));
				chart.addSeries({
			        name: decode_utf8(name),
			        color: '#E6AF2F',
			        borderWidth: 0,
			        data: data,
			        showInLegend: false,
			        zIndex: 1
			    });
				chart.series[c].update({
			        type: "waterfall"
			    });
				
				c++;
			}
		}  
	    if(comparativas.length > 0){
	    	var comparativa = $($(".highcharts-legend-item")[1]).clone();
		    comparativa.attr("transform", "translate(170,3)");
		    comparativa.find("path").attr("stroke", "#ffc848");
		    comparativa.find("path").attr("fill", "#ffc848");
		    comparativa.find("text").html("Comparativa");
		    $($(".highcharts-legend-item")[1]).after(comparativa);
		    
		    $(".highcharts-legend").attr("transform", "translate(215,354)");
	    }
	}
}

function getGraficoBySede() {
	addLoadingButton('botonFI');
	var idSede = $('#multiSedes').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idSede,
				   tipo             : 0},
		'async' : true
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectNivel', data.comboNivel, 'Nivel');
			setCombo('selectGrado', null, 'Grado');
			setCombo('selectAula', null , 'Aula');
			initGrafico(data);
		} else {
			setCombo('selectNivel', null, 'Nivel');
			setCombo('selectGrado', null, 'Grado');
			setCombo('selectAula', null , 'Aula');
			getGraficoByIndicador();
		}
		stopLoadingButton('botonFI');
	});
}

function getGraficoByNivel() {
	addLoadingButton('botonFI');
	Pace.track(function() {
		var idSede = $('#multiSedes').val();
		var idNivel = $('#selectNivel option:selected').val();
		
		$.ajax({
			type 	: 'POST',
			'url' 	: 'c_grafico/getGraficoByNivelAcademico',
			data	: {idNivelAcademico : idNivel,
					   idSede           : idSede,
					   tipo : 1},
			'async' : true
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				setCombo('selectGrado', data.comboGrado, 'Grado');
				setCombo('selectAula', null, 'Aula');
				initGrafico(data);
			} else {
				setCombo('selectGrado', null, 'Grado');
				setCombo('selectAula', null, 'Aula');
				getGraficoBySede();
			}
			stopLoadingButton('botonFI');
		});
	});
}

function getGraficoByGrado() {
	addLoadingButton('botonFI');
	var idSede = $('#multiSedes').val();
	var idNivel = $('#selectNivel option:selected').val();
	var idGrado = $('#selectGrado option:selected').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idGrado,
				   idSede           : idSede,
				   idNivel          : idNivel,
				   tipo : 2},
		'async' : true
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectAula', data.comboAula, 'Aula');
			
			initGrafico(data);
		} else {
			setCombo('selectAula', null, 'Aula');
			getGraficoByNivel();
		}
		stopLoadingButton('botonFI');
	});
}

function getGraficoByAula() {
	addLoadingButton('botonFI');
	var idSede = $('#multiSedes').val();
	var idNivel = $('#selectNivel option:selected').val();
	var idGrado = $('#selectGrado option:selected').val();
	var idAula  = $('#selectAula option:selected').val();
	if(idAula.length == 0){
		getGraficoByGrado();
	}else{
		$.ajax({
			type 	: 'POST',
			'url' 	: 'c_grafico/getGraficoByNivelAcademico',
			data	: {idNivelAcademico : idAula,
					   idSede           : idSede,
					   idNivel          : idNivel,
					   idGrado          : idGrado,
					   tipo : 3},
			'async' : true
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				initGrafico(data);
			}
			stopLoadingButton('botonFI');
		});
	}
}

function getGraficoByDisciplina(){
	var idDisciplina = $('#multiDisciplinas').val();
	
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idDisciplina,
				   tipo : 4},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
			setCombo('selectNivel', data.comboNivel, 'Nivel');
		}	
	});
}

function getGraficoByNivelDN(){
	var idDisciplina = $('#multiDisciplinas option:selected').val();
	var idNivel      = $('#selectNivel option:selected').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idNivel,
			       idDisciplina     : idDisciplina,
				   tipo : 5},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
		}	
	});
}

function getGraficoBySedeSNA() {
	var idSede = $('#multiSedes').val();
	
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idSede,
				   tipo             : 6},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectNivel', data.comboNivel, 'Nivel');
			initGrafico(data);
		}else{
			$('#cont_not_found_fab').css("display", "block");
			$('#container').html("");
		}	
	});
}

function getGraficoByNivelSNA(){
	var idSede = $('#multiSedes').val();
    var idNivel = $('#selectNivel option:selected').val();
	
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idNivel,
			       idSede           : idSede,
				   tipo             : 7},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectArea', data.comboArea, 'Area');
			initGrafico(data);
		}	
	});
}

function getGraficoByAreaSNA(){
	var idSede = $('#multiSedes').val();
    var idNivel = $('#selectNivel option:selected').val();
    var idArea  = $('#selectArea option:selected').val();
    
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idArea,
			       idSede           : idSede,
			       idNivel          : idNivel,
				   tipo             : 8},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
		}	
	});
}

function getGraficoBySedeSA() {
	var idSede = $('#multiSedes').val();
    
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idSede,
				   tipo             : 9},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
			setCombo('selectArea', data.comboArea, 'Area');
		}	
	});
}

function getGraficoByAreaSA() {
	var idSede = $('#multiSedes').val();
    var idArea  = $('#selectArea option:selected').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idArea,
			       idSede           : idSede,
				   tipo             : 10},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
		}	
	});
}

function getGraficoBySedeSG() {
	var idSede = $('#multiSedes').val();
    
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idSede,
				   tipo             : 11},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
			setCombo('selectGrado', data.comboGrado, 'Grado');
		}	
	});
}

function getGraficoByGradoSG() {
	var idSede = $('#multiSedes').val();
    var idGrado  = $('#selectGrado option:selected').val();
    
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_grafico/getGraficoByNivelAcademico',
		data	: {idNivelAcademico : idGrado,
			       idSede           : idSede,
				   tipo             : 12},
		'async' : false
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			initGrafico(data);
		}	
	});
}

function abrirModalFitros() {
	abrirCerrarModal("modalFiltroGrafico");
}

function logOutGrafico() {
	$.ajax({
		url  : 'c_grafico/logOutGrafico', 
		async: false,
		type : 'POST'
	})
	.done(function(data) {
		location.reload();
	});
}

function limpiarCombos(data) {
	setCombo('selectObjetivo', data.comboObjetivo, 'Objetivo');
	setCombo('selectCategoria', data.comboCategoria, 'Categoria');
	setCombo('selectIndicador', data.comboIndicador, 'Indicador');
	$('#contCombosFiltro').html("");
	$('#contYear').css('display','none');
	$('#cont_not_found_fab').css("display", "block");
	$('#container').html("");
	//$('#container').html('<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/filter_fab.png" class="imgBuho">');
}

function getGraficoBySedeMaster(element){
	addLoadingButton('botonFI');
	var idSede = $('#multiSedes').val();
	if(idSede != null && idSede.length == 1){
		if(t == 1){//SNGA, SNG,SN
			stopLoadingButton('botonFI');
	    	getGraficoBySede();
	    }else if(t == 2){//S
	    	stopLoadingButton('botonFI');
	    	getGraficoBySede();
	    }else if(t == 4){//SNA
	    	stopLoadingButton('botonFI');
	    	getGraficoBySedeSNA();
	    }else if(t == 5){//SA
	    	stopLoadingButton('botonFI');
	    	getGraficoBySedeSA();
	    }else if(t == 6){//SG
	    	stopLoadingButton('botonFI');
	    	getGraficoBySedeSG();
	    }
		dy = 0
		$('#contYear').css('display','none');
    	$('.selectYear').val(new Date().getFullYear());
	    $('.selectYear').selectpicker('render');
	    $('#selectYear').selectpicker('refresh');
	    
	    $('[data-id = "selectNivel"]').removeAttr("disabled");
    	$('[data-id = "selectGrado"]').removeAttr("disabled");
    	$('[data-id = "selectArea"]').removeAttr("disabled");
    	$('[data-id = "selectAula"]').removeAttr("disabled");
    	$('[data-id = "selectNivel"]').removeAttr("disabled");
	}else if(idSede != null && idSede.length > 1){
		var year =  $('#selectYear option:selected').val();
    	if(dy == 0){
    		$('#contYear').css('display','block');
    	}
    	getGraficoBySedes(year);
    	$('[data-id = "selectNivel"]').attr("disabled", true);
    	$('[data-id = "selectGrado"]').attr("disabled", true);
    	$('[data-id = "selectArea"]').attr("disabled", true);
    	$('[data-id = "selectAula"]').attr("disabled", true);
    	$('[data-id = "selectNivel"]').attr("disabled", true);
    	stopLoadingButton('botonFI');
	}else{
		var par = $(element).parent().parent().parent().parent().attr("id");
		if(par == "contCombosFiltroCod"){
			stopLoadingButton('botonFI');
			getGraficoByIndicador('cod');
		}else{
			stopLoadingButton('botonFI');
			getGraficoByIndicador();
			
		}
    	dy = 0
    	$('#contYear').css('display','none');
    	$('.selectYear').val(new Date().getFullYear());
	    $('.selectYear').selectpicker('render');
	    $('#selectYear').selectpicker('refresh');
	}
	componentHandler.upgradeAllRegistered();
}

function getGraficoByDisciplinaMaster(element){
	var idDis = $('#multiDisciplinas').val();
	if(idDis != null && idDis.length > 1){
    	var year =  $('#selectYear option:selected').val();
    	if(dy == 0){
    		$('#contYear').css('display','block');
    	}
    	getGraficoByDisciplinas(year);
    	$('[data-id = "selectNivel"]').attr("disabled", true);
    	$('[data-id = "selectGrado"]').attr("disabled", true);
    	$('[data-id = "selectArea"]').attr("disabled", true);
    	$('[data-id = "selectAula"]').attr("disabled", true);
    	$('[data-id = "selectNivel"]').attr("disabled", true);
    	dy = 1;
    }else if(idDis != null && idDis.length == 1){	
	    if(t == 3){//DN
	    	getGraficoByDisciplina();
        }
	    
	    dy = 0
		$('#contYear').css('display','none');
    	$('.selectYear').val(new Date().getFullYear());
	    $('.selectYear').selectpicker('render');
	    $('#selectYear').selectpicker('refresh');
	    $('[data-id = "selectNivel"]').removeAttr("disabled");
    	$('[data-id = "selectGrado"]').removeAttr("disabled");
    	$('[data-id = "selectArea"]').removeAttr("disabled");
    	$('[data-id = "selectAula"]').removeAttr("disabled");
    	$('[data-id = "selectNivel"]').removeAttr("disabled");
    }else{
    	var par = $(element).parent().parent().parent().parent().attr("id");
		if(par == "contCombosFiltroCod"){
			stopLoadingButton('botonFI');
			getGraficoByIndicador('cod');
		}else{
			stopLoadingButton('botonFI');
			getGraficoByIndicador();
		}
    	dy = 0
    	$('#contYear').css('display','none');
    	$('.selectYear').val(new Date().getFullYear());
	    $('.selectYear').selectpicker('render');
	    $('#selectYear').selectpicker('refresh');
    }
}

function decode_utf8(s) {
	  return decodeURIComponent(escape(s));
	}