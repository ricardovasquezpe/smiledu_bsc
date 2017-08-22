///////////// INIT ALL ////////////////////
function initDetalleCurso() {
	$('#cmbCompetencias').mobileSelect({
		onClose: function() {
			getCapacidadesByCompetencia();
	    }
	});
	$('#cmbCapacidad').mobileSelect({
		onClose: function() {
			getIndicadoresByCapacidad();
	    }
	});
	$('#cmbIndicador').mobileSelect({
		onClose: function() {
			getInstrumentosByIndicador();
	    }
	});
	$('#cmbInstrumento').mobileSelect({
		onClose: function() {
			usarInstrumento();
		}
	});
	
	$('#tbEstus').bootstrapTable({ });
	tableEventsEstudiantes();
	
	Highcharts.setOptions({
		lang: {
			months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',  'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			weekdays: ['Domingo', 'Lunes', 'Martes', 'Mi\u00e9rcoles', 'Jueves', 'Viernes', 'S\u00e1bado'],
			shortMonths : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'],
			shortWeekdays: ['Dom', 'Lun', 'Mar', 'Mi\u00e9', 'Jue', 'Vie', 'S\u00e1b'],
			resetZoom    : 'Quitar Zoom',
			noData       : 'No hay informaci\u00f3n',
			rangeSelectorFrom: "del",
            rangeSelectorTo: "al",
		}
	});
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}

	initButtonCalendarDays( 'fecIni' );
	initButtonCalendarDays( 'fecFin' );
	initMaskInputs( 'fecIni', 'fecFin' );
	initButtonLoad( 'btnMAI', 'btnMSE', 'btnMFF' );
}

function initCalendarioAsistencia() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/getAsistenciasEvents',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				var options = {
					events_source : data,
					language      : 'es-ES',
					tmpl_path     : "../public/general/plugins/bootstrap-calendar-master/tmpls/",
					onAfterViewLoad : function(view) {
						$('#fechaCalendar').text(this.getTitle());
						$('button.mdl-button').removeClass('active');
						$('button.mdl-button[data-calendar-view="' + view + '"]').addClass('active');
						$('li.mdl-menu__item').removeClass('active');
						$('li.mdl-menu__item[data-calendar-view="' + view + '"]').addClass('active');
					},
					modal : "#events-modal",
					ruta_js_metodo : '../public/modulos/notas/js/jsdetalleCurso.js',
					funcion_name : 'getDetalleEvento'
				};
				var calendar = $('#calendar').calendar(options);
				$('button.mdl-button[data-calendar-nav], li.mdl-menu__item[data-calendar-nav]').each(function() {
					var $this = $(this);
					$this.click(function() {
						calendar.navigate($this.data('calendar-nav'));
					});
				});
				$('button[data-calendar-view], li.mdl-menu__item[data-calendar-view]').each(function() {
					var $this = $(this);
					$this.click(function() {
						calendar.view($this.data('calendar-view'));
					});
				});
			} catch(err) {
				location.reload();
			}
		});
	});
}

function getDetalleEvento(modalId, aTag, events_sources) {
	$('#'+modalId+' h2.mdl-card__title-text').text(aTag.html());
	var fecha = null;
	$.each(events_sources, function(idx, value) {
		if(value.id == aTag.data('event-id')) {
			fecha = value.start;
			return false;
		}
	});
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { fecha : fecha },
			url  : 'c_detalle_curso/getAsistenciaByFecha',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contAsistentes').html(data.tbAsistentes);
					$('#tbAsistFech').bootstrapTable({ });
					modal(modalId);
				}
			} catch(err) {
				location.reload();
			}
		});
	});
}

/*$('.mdl-card.mdl-student').click(function() {
	$(this).toggleClass('flipped');
});*/

var idAlumnoAsistGlobal = null;
function openModalForAsistencia(liObj) {
	idAlumnoAsistGlobal = liObj.data('alu_id');
	var ape = liObj.find('.nom_alum').html();
	var nom = liObj.find('.nom_alum2').html();
	$('#modalAsistencia').find('.mdl-card__title-text').html(ape+' '+nom);
	modal('modalAsistencia');
}

function getStyleValue(className, style) {
    var elementId = 'test-'+className,
    testElement = document.getElementById(elementId), 
    val;
	
	if (testElement === null) {
		testElement = document.createElement('div');
	    testElement.className = className;
	    testElement.style.display = 'none';
	    document.body.appendChild(testElement);
	}
	
	val = $(testElement).css(style);
	document.body.removeChild(testElement);
    return val;
}

function guardarAsistencia(asistObj) {
	if(idAlumnoAsistGlobal == null || idAlumnoAsistGlobal == undefined || $.trim(idAlumnoAsistGlobal) == '') {
		return;
	}
	var tipoAsist = asistObj.data('asistencia');
	if(tipoAsist == null || tipoAsist == undefined || $.trim(tipoAsist) == '') {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { tipoAsist : tipoAsist,
				     idAlumno  : idAlumnoAsistGlobal },
			url  : 'c_detalle_curso/guardarAsistencia',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
		    	if(data.error == 0) {//
		    		var obj = $('*[data-alu_id="'+idAlumnoAsistGlobal+'"]');
		    		
		    		var oldCss = obj.find('.estado_color').attr('data-estado_aux');
		    		obj.find('.estado_color').removeClass(oldCss).addClass(data.newEstado);
		    		obj.find('.estado_color').attr('data-estado_aux', data.newEstado);

		    		var colorr = getStyleValue('bg-'+data.newEstado, 'background-color');
		    		obj.find('.estado_color').find('.mdl-card__supporting-text').effect("highlight", {color : colorr }, 1300);
		    		modal('modalAsistencia');
		    	}
			} catch(err) {
				location.reload();
			}
		});
	});
}

function setAsistenciasTemprano() {
	if(asistenciaMarcadaGlobal != null) {
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				url  : 'c_detalle_curso/getEstudiantesForAsistencia',
				type : 'POST'
			})
			.done(function(data) {
				data = JSON.parse(data);
				$('#cont_estudiantes').html(data.estuAsist);
				$estadoFabGlobal = 'CALIFICAR';
				changeMainFab();
			});
		});
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/marcarAsistenciaGeneral',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					asistenciaMarcadaGlobal = 1;
					$estadoFabGlobal        = 'CALIFICAR';
					changeMainFab();
					
					$('#cont_estudiantes').html(data.estuAsist);
					msj('success', data.msj);
				} else {
					msj('error', data.msj);
				}
				/*$('a.mdl-layout__tab').removeClass('is-active');
				$('.mdl-layout__tab-panel').removeClass('is-active');
				$('a[href="#tab-1"]').addClass('is-active');
				$('#tab-1').addClass('is-active');*/
			} catch(err) {
				location.reload();
			}
		});
	});
}

function getCapacidadesByCompetencia() {
	var idCompetencia = getComboVal('cmbCompetencias');
	if(idCompetencia == null || idCompetencia == undefined || $.trim(idCompetencia) == '') {
		setComboFull('cmbCapacidad'  , null, 'Capacidad');
		setComboFull('cmbIndicador'  , null, 'Indicador');
		setComboFull('cmbInstrumento', null, 'Instrumento');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/getCapacidadesByCompetencia',
			data : { idCompetencia : idCompetencia },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				setComboFull('cmbCapacidad', data.optCapacidad, 'Capacidad');
				setComboFull('cmbIndicador', null, 'Indicador');
				setComboFull('cmbInstrumento', null, 'Instrumento');
			} catch(err) {console.log(err);
				//location.reload();
			}
		});
	});
}

function getIndicadoresByCapacidad() {
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	if(idCompetencia == null || idCompetencia == undefined || $.trim(idCompetencia) == '' ||
	   idCapacidad == null || idCapacidad == undefined || $.trim(idCapacidad) == '') {
		setComboFull('cmbIndicador', null, 'Indicador');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/getIndicadoresByCapacidad',
			data : { idCompetencia : idCompetencia ,
				     idCapacidad   : idCapacidad  },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				setComboFull('cmbIndicador', data.optIndicador, 'Indicador');
				setComboFull('cmbInstrumento', null, 'Instrumento');
			} catch(err) {
				location.reload();
			}
		});
	});
}

function getInstrumentosByIndicador() {
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	if(idCompetencia == null || idCompetencia == undefined || $.trim(idCompetencia) == '' ||
	   idCapacidad == null || idCapacidad == undefined || $.trim(idCapacidad) == '' || 
	   idIndicador == null || idIndicador == undefined || $.trim(idIndicador) == '') {
		setComboFull('cmbInstrumento', null, 'Instrumento');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/getInstrumentosByIndicador',
			data : { idCompetencia : idCompetencia ,
				     idCapacidad   : idCapacidad   ,
				     idIndicador   : idIndicador  },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				setComboFull('cmbInstrumento', data.optInstrumentos, 'Instrumento');
			} catch(err) {
				location.reload();
			}
		});
	});
}

var $idInstrumentoGlobal = null;
function usarInstrumento() {
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	var idInstrumento = getComboVal('cmbInstrumento');
	if(!idInstrumento || !idCompetencia || !idIndicador || !idCapacidad) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/usarInstrumento',
			data : {idCompetencia : idCompetencia,
				    idCapacidad   : idCapacidad,
				    idIndicador   : idIndicador,
				    idInstrumento : idInstrumento },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#cont_estudiantes').html(data.estuInstru);
				$idInstrumentoGlobal = idInstrumento;
				$estadoFabGlobal     = 'ASISTENCIA';
				
				changeMainFab();
				
				/*$('a.mdl-layout__tab').removeClass('is-active');
				$('.mdl-layout__tab-panel').removeClass('is-active');
				$('a[href="#tab-1"]').addClass('is-active');
				$('#tab-1').addClass('is-active');*/
			} catch(err) {
				location.reload();
			}
		});
	});
}

var $_idEstuEvaluar = null;
function openModalVerInstrumento(liObj) {
	var idInstrumento = getComboVal('cmbInstrumento');
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	var idEstudiante  = liObj.data('alu_id');
	$_idEstuEvaluar = idEstudiante;
	if(!idInstrumento || !idEstudiante || !idCompetencia || !idCapacidad || !idIndicador ) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/getInstrumentoToEvaluarByEstu',
			data : {idInstrumento : idInstrumento,
				    idEstudiante  : $_idEstuEvaluar,
				    idCompetencia : idCompetencia,
				    idCapacidad   : idCapacidad,
				    idIndicador   : idIndicador },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#contInstrum').html(data.instrumento);
				$('#notaFinal').html(data.notaInstru);
	            $('#notaFinal').attr('class', data.colorGeneral);
				
				$('.collapse-card').paperCollapse();
				modal('modalInstrumento');
				componentHandler.upgradeAllRegistered();
			} catch(err) {
				location.reload();
			}
		});
	});
}

function registRptaInstru(radioObj) {
	var idInstrumento = getComboVal('cmbInstrumento');
	var idApecto      = radioObj.parent().data('id_aspecto');
	var idOpcion      = radioObj.parent().data('id_opcion');
	var opcion        = radioObj.val();
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	if(idCompetencia == '' || idCapacidad == '' || idIndicador == '') {
		msj('error', 'Seleccione un indicador');
		return;
	}
	if(!idInstrumento || !$_idEstuEvaluar) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_detalle_curso/registrarRptaInstruByEstu',
			data : {idInstrumento : idInstrumento,
				    idEstudiante  : $_idEstuEvaluar,
				    idApecto      : idApecto,
				    idOpcion      : idOpcion,
				    opcion        : opcion,
				    idCompetencia : idCompetencia,
				    idCapacidad   : idCapacidad,
				    idIndicador   : idIndicador},
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				radioObj.closest('.collapse-card').find('.estado_rpta').html('('+radioObj.parent().data('abvr')+') ');
				$('*[data-alu_id="'+$_idEstuEvaluar+'"]').find('.mdl-value').html(data.promedioResult);
				$('*[data-alu_id="'+$_idEstuEvaluar+'"]').parent().find('.nota_css').html(Number(data.notaInstru).round(1));
				$('*[data-alu_id="'+$_idEstuEvaluar+'"]').parent().attr('class', 'mdl-card mdl-note card_award '+data.colorGeneral);
				$('#notaFinal').html(data.notaInstru);
	            $('#notaFinal').attr('class', data.colorGeneral);
				msj('success', data.msj);
			} catch(err) {
				log(err);
				//location.reload();
			}
		});
	});
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////   GRAFICOS   ///////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

function mostrarGraficoPieAsistencia(json) {
	var options = {
			legend: {
	            itemStyle: {
	                color: '#959595'
	            }
	        },
            chart: {
                renderTo: 'cont_graf_asist',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                backgroundColor: "white",
                color:"#959595"
            },
            title: {
                text: 'Gr\u00e1fico de asistencias',
                style: {
                    color: '#959595'
                 }
            },
            tooltip: {
                formatter: function() {
                	return '<b style="color:#959595">'+ this.point.name +'</b>: <p style="color:#959595">'+ this.point.y +'</p>';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    showInLegend: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                        	return '<b style="color:#959595">'+ this.point.name +'</b>: <p style="color:#959595">'+ Math.round(this.point.percentage) +' %</p>';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: []
            }]
        }
    options.series[0].data = json;
    chart = new Highcharts.Chart(options);
    $.each(options.series[0].data, function (key, value){
    	value.events.click = function (){ console.log(this.name); };
    });
}

function mostrarGraficoLineaAsistencia(json, jsonCate) {
	$('#cont_graf_linea_asist').highcharts({
        title: {
            text: 'Gr\u00e1fico Lineal',
            x: -20 //center
        }/*,
        subtitle: {
            text: 'Source: WorldClimate.com',
            x: -20
        }*/,
        xAxis: {
            categories: jsonCate
        },
        yAxis: {
            title: {
                text: 'Cantidad de asistencias'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
            }
        }/*
        legend: {
            layout: 'horizontal',
            align: 'bottom',
            verticalAlign: 'bottom',
            borderWidth: 0
        }*/,
        series: json
    });
}

function mostrarGraficoBarraSexoAsistencia(jsonData) {
	$('#cont_graf_barr_sexo').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gr\u00e1fico de barras'
        },
        xAxis: {
            categories: ['Falta', 'Falta Justif', 'Presente', 'Tarde', 'Tardanza Justif']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad de asistencias'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: jsonData
    });
}

function mostrarGraficoHeatMap(jsonFechas, jsonData) {
	$('#cont_heat_map').highcharts({
        chart: {
            type: 'heatmap',
            marginTop: 40,
            marginBottom: 80,
            plotBorderWidth: 1
        },
        title: {
            text: 'Asistencias por d\u00eda de la semana'
        },

        xAxis: {
            categories: ['Falta', 'Falta Justif', 'Presente', 'Tarde', 'Tarde Justif']
        },
        yAxis: {
            categories: jsonFechas,
            title: null
        },
        colorAxis: {
            min: 0,
            minColor: '#FFFFFF',
            maxColor: Highcharts.getOptions().colors[0]
        },
        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 280
        },
        tooltip: {
            formatter: function () {
                return this.point.value+'<b> ' + this.series.xAxis.categories[this.point.x] + '</b><br> el <b>' +
                       this.series.yAxis.categories[this.point.y] + '</b>';
            }
        },
        series: [{
            name: 'Sales per employee',
            borderWidth: 1,
            data:  jsonData ,
            dataLabels: {
                enabled: true,
                color: '#000000'
            }
        }]
    });
}

function calificarFab() {
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	var idInstrumento = getComboVal('cmbInstrumento');
	if(!idCompetencia) {
		$('#cmbCompetencias').mobileSelect('show');
		return;
	}
	if(!idCapacidad) {
		$('#cmbCapacidad').mobileSelect('show');
		return;
	}
	if(!idIndicador) {
		$('#cmbIndicador').mobileSelect('show');
		return;
	}
	if(!idInstrumento) {
		$('#cmbInstrumento').mobileSelect('show');
		return;
	}
	usarInstrumento();
	return;
}

function changeMainFab() {
	$('*[data-mfb-label="Asistencia"]').remove();
	$('*[data-mfb-label="Calificar"]').remove();
	$('*[data-plus-minifab="1"]').after(getMainFabHTML());
}

function getMainFabHTML() {
	if($estadoFabGlobal == 'CALIFICAR') {
		return '<button class="mfb-component__button--main is-up" data-toggle="modal" data-mfb-label="Calificar" onclick="calificarFab();">'+
               '    <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>'+
               '</button>';
	} else if($estadoFabGlobal == 'ASISTENCIA') {
		return '<button class="mfb-component__button--main is-up" data-mfb-label="Asistencia" onclick="setAsistenciasTemprano();">'+
               '    <i class="mfb-component__main-icon--active mdi mdi-assignment_parent"></i>'+
               '</button>';
	}
	return null;
}

function tabActionsChange() {
	$('.mdl-layout__tab').click(function() {
		var yaActivo = $(this).hasClass('is-active');
		if(yaActivo) {
			return;
		}
		if($(this).attr('href') == '#tab-1') {
			var miniTabs = '<li>'+
				           '    <button class="mfb-component__button--child" data-mfb-label="Avances calificaciones" data-toggle="modal" data-target="#modalAvances">'+
				           '        <i class="mfb-component__child-icon mdi mdi-insert_chart"></i>'+
				           '    </button>'+
				           '</li>';
			
			/*if(asistenciaMarcadaGlobal == null) {
				miniTabs += '<li>'+
				            '    <button class="mfb-component__button--child" data-mfb-label="Asistencia" onclick="setAsistenciasTemprano();">'+
				            '        <i class="mfb-component__child-icon mdi mdi-assignment_parent"></i>'+
				            '    </button>'+
				            '</li>';
			}
			$('.mfb-component__list').html(miniTabs);
			$('*[data-mfb-label="Filtrar"]').remove();
			$('*[data-mfb-label="Selec. Estudiante"]').remove();
			var buttons = '<button class="mfb-component__button--main" data-toggle="modal" data-mfb-label="Calificar" onclick="calificarFab();">'+
			              '    <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>'+
			              '</button>';
			$('*[data-plus-minifab="1"]').after(buttons);*/
			$('*[data-mfb-label="Filtrar"]').remove();
			$('*[data-mfb-label="Selec. Estudiante"]').remove();
			changeMainFab();
			$('.mfb-component__list').html(miniTabs);
			$('*[data-plus-minifab="1"]').removeAttr('style');
			$('#menu').removeAttr('style');
			$('.mfb-component__wrap').removeAttr('style');
		} else if($(this).attr('href') == '#tab-2') {
			$('.mfb-component__list').children().remove();
			$('*[data-mfb-label="Selec. Estudiante"]').remove();
			$('*[data-mfb-label="Filtrar"]').remove();
			$('*[data-mfb-label="Calificar"]').remove();
			
			/*var buttons = '<button class="mfb-component__button--main is-up" data-toggle="modal" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltroFechas">'+
				          '    <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>'+
				          '</button>';
			$('*[data-plus-minifab="1"]').after(buttons);*/
			
			if($('.miniTabCalend').hasClass('active')) {
				$('*[data-plus-minifab="1"]').css('display', 'none');
				$('#menu').css('display', 'none');
				$('.mfb-component__wrap').css('display', 'none');
			} else if($('.miniTabAsist').hasClass('active')) {
				$('*[data-plus-minifab="1"]').removeAttr('style');
				$('#menu').removeAttr('style');
				$('.mfb-component__wrap').removeAttr('style');
				
				var buttons = '<button class="mfb-component__button--main is-up" data-toggle="modal" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltroFechas">'+
					          '    <i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>'+
					          '</button>';
				$('*[data-plus-minifab="1"]').after(buttons);
				
			}
			//$('#menu').css('z-index', -11);
			/*var buttons = '<button class="mfb-component__button--main" data-toggle="modal" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltroFechas">'+
				          '    <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>'+
				          '</button>';
			$('*[data-plus-minifab="1"]').after(buttons);*/

			if(firstLoad == null) {
				initCalendarioAsistencia();
				miniTabGraficaAsistencia();
				firstLoad = 1;
			}
		} else if($(this).attr('href') == '#tab-3') {
			$('*[data-plus-minifab="1"]').removeAttr('style');
			$('#menu').removeAttr('style');
			$('.mfb-component__wrap').removeAttr('style');
			
			$('.mfb-component__list').children().remove();
			$('*[data-mfb-label="Asistencia"]').remove();
			$('*[data-mfb-label="Filtrar"]').remove();
			$('*[data-mfb-label="Calificar"]').remove();
			var buttons = '<button class="mfb-component__button--main" data-toggle="modal" data-mfb-label="Selec. Estudiante" data-toggle="modal" data-target="#modalSelecEstudiante">'+
				          '    <i class="mfb-component__main-icon--active mdi mdi-students"></i>'+
				          '</button>';
			$('*[data-plus-minifab="1"]').after(buttons);
		}
	});
}

function miniTabGraficaAsistencia() {
	$('#btnTabGrafiAsit').click(function () {
		if(firstLoadGrafAsist == null) {
			Pace.restart();
			Pace.track(function() {
				$.ajax({
					url  : 'c_detalle_curso/getGraficosAsistencia',
					type : 'POST'
				})
				.done(function(data) {
					try {
						data = JSON.parse(data);
						mostrarGraficoPieAsistencia(JSON.parse(data.pie));
						mostrarGraficoLineaAsistencia(JSON.parse(data.linea), JSON.parse(data.lineaCateg));
						mostrarGraficoBarraSexoAsistencia(JSON.parse(data.sexoBarras));
						mostrarGraficoHeatMap(JSON.parse(data.fechasHeat), JSON.parse(data.heatMapData));
						$('#fecIni').val(data.fecIni);
						$('#fecFin').val(data.fecFin);
						giveEventsGraficosRefresh();
						firstLoadGrafAsist = 1;
						$('*[data-plus-minifab="1"]').css('display', 'block');
						$('#menu').css('display', 'block');
						$('.mfb-component__wrap').css('display', 'block');
						
						var buttons = '<button class="mfb-component__button--main is-up" data-toggle="modal" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltroFechas">'+
							          '    <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>'+
							          '</button>';
						$('*[data-plus-minifab="1"]').after(buttons);
					} catch(err) {
						location.reload();
					}
				});
			});
		} else {
			$('*[data-plus-minifab="1"]').css('display', 'block');
			$('#menu').css('display', 'block');
			$('.mfb-component__wrap').css('display', 'block');
			
			var buttons = '<button class="mfb-component__button--main is-up" data-toggle="modal" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltroFechas">'+
				          '    <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>'+
				          '</button>';
			$('*[data-plus-minifab="1"]').after(buttons);
		}
	});
	$('.miniTabCalend').click(function () {
		$('.mfb-component__list').children().remove();
		$('*[data-mfb-label="Selec. Estudiante"]').remove();
		$('*[data-mfb-label="Filtrar"]').remove();
		$('*[data-mfb-label="Calificar"]').remove();
		
		$('*[data-plus-minifab="1"]').css('display', 'none');
		$('#menu').css('display', 'none');
		$('.mfb-component__wrap').css('display', 'none');
	});
}

function filtrarAsistenciasGraphs() {
	Pace.restart();
	Pace.track(function() {
		/*if(!isDate($('#fecIni').val()) || !isDate($('#fecFin').val()) ) {
			msj('error', 'El formato de las fechas es incorrecto');
			return;
		}*/
		addLoadingButton('btnMFF');
		$.ajax({
			data : { fecIni : $('#fecIni').val() ,
				     fecFin : $('#fecFin').val() },
			url  : 'c_detalle_curso/getGraficosAsistenciaFiltroFechas',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					mostrarGraficoPieAsistencia(JSON.parse(data.pie));
					mostrarGraficoLineaAsistencia(JSON.parse(data.linea), JSON.parse(data.lineaCateg));
					mostrarGraficoBarraSexoAsistencia(JSON.parse(data.sexoBarras));

					firstLoadGrafAsist = 1;
					stopLoadingButton('btnMFF');
					modal('modalFiltroFechas');
				} else {
					msj('error', data.msj);
				}
			} catch(err) {console.log(err);
			    msj('error', CONFIG.get('MSJ_ERR')+' - '+err.message);
				//location.reload();
			}
		});
	});
}

function giveEventsGraficosRefresh() {
	$('.refresh_graf').click(function() {
		var graf = $(this).data('graf');
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data : { fecIni : $('#fecIni').val() ,
					     fecFin : $('#fecFin').val() ,
					     graf   : graf},
				url  : 'c_detalle_curso/getGraficosAsistenciaFiltroFechas',
				type : 'POST'
			})
			.done(function(data) {
				try {
					data = JSON.parse(data);
					if(data.error == 0) {
						if(graf == '1') {
							mostrarGraficoPieAsistencia(JSON.parse(data.pie));
						} else if(graf == '2') {
							mostrarGraficoLineaAsistencia(JSON.parse(data.linea), JSON.parse(data.lineaCateg));
						} else if(graf == '3') {
							mostrarGraficoBarraSexoAsistencia(JSON.parse(data.sexoBarras));
						} else {
							mostrarGraficoHeatMap(JSON.parse(data.fechasHeat), JSON.parse(data.heatMapData));
						}
						firstLoadGrafAsist = 1;
					} else {
						msj('error', data.msj);
					}
				} catch(err) {
				    msj('error', CONFIG.get('MSJ_ERR')+' - '+err.message);
					//location.reload();
				}
			});
		});
	});
}

var $_idEstu  = null;
var $_nomEstu = null;
var $_clickAsitEstu = null;
function selectEstuData() {
	var idEstu = $('input[name="radioVals"]:checked').data('id_estu');
	if(idEstu == $_idEstu) {
		modal('modalSelecEstudiante');
		return;
	}
	$('.barra_info_estu').css('display', 'block');
	$('.img-search').css('display', 'none');
	$_idEstu = idEstu;
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnMSE');
		$.ajax({
			data : { idEstu : idEstu},
			url  : 'c_detalle_curso/getDetalleEstudiante',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					$_nomEstu = (data.nom_persona.split(' ')[0]+' '+data.ape_pate_pers);
					$("#tab-profile :input").val(null);
					$("#tab-profile :input").parent().removeClass('is-dirty');
					$('#nombrePersona').val(data.nom_persona);
					$('#apePaterno').val(data.ape_pate_pers);
					$('#apeMaterno').val(data.ape_mate_pers);
					$('#dniPersona').val(data.dni);
					$('#docExtranjeriaPersona').val(data.carnet_extranjeria);
					$('#fecNacPersona').val(data.fec_naci);
					$('#tlfPersona').val(data.telf_pers);
					$('#pais').val(data.pais);
					$('#lugar_naci').val(data.ubicacion);
					$('#sexoEstu').val(data.sexo);
					$('#correoPersona').val(data.correo);
					$('#religion').val(data.religion);
					$('#estado_civil').val(data.estado_civil);
					$('#fotoPersonaImg').attr('src', data.foto_persona);
					
					$.each($("#tab-profile :input"), function( index, value ) {
						if($(this).val().length > 0) {
							$(this).parent().addClass('is-dirty');
						}
					});
					$('#divContHistoriaEstu').html(data.tablaHistoria);
					$('#tbEstusHistoria').bootstrapTable({ });
					$('#divContFamiliares').html(data.tablaFamiliares);
					$('#tbEstusFamiliares').bootstrapTable({ });
					$('#contCursosEstu').html(data.tbCursosEstu);
					$('#tbCursosEstu').bootstrapTable({ });
					
					$_clickAsitEstu = null;
					$('a[href="#tab-assitence"]').click(function() {
						if($_clickAsitEstu == null) {
							cargarMiniTabAsistenciaEstudiante();	
						}
					});
					stopLoadingButton('btnMSE');
					
					modal('modalSelecEstudiante');
				} else {
					msj('error', data.msj);
				}
			} catch(err) {
			    msj('error', CONFIG.get('MSJ_ERR')+' - '+err.message);
				//location.reload();
			}
		});
	});
}

function cargarMiniTabAsistenciaEstudiante() {
	if(!$_idEstu) {
		return;
	};
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { idEstu : $_idEstu},
			url  : 'c_detalle_curso/getDetalleEstudianteAsistencia',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					cargarLineaRegresionAsistByEstu(JSON.parse(data.dataLineRegre));
					cargarRadarGraphByEstu(JSON.parse(data.dataRadar), $_nomEstu);
					
					mostrarGraficoPieByEstuAsistencia(JSON.parse(data.pieEstu));
					
					$('#divContRankEstu').html(data.rankEstu);
					$('#tbRankEstuAsist').bootstrapTable({ });
					$("#tbRankEstuAsist tbody>tr").filter(function() {
					    return $(this).find('.row_index').data('activo') !== '';
					}).css('background-color','rgba(255,146,0,0.2)');
					
					$_clickAsitEstu = 1;
				}
			} catch(err) {
			    msj('error', CONFIG.get('MSJ_ERR')+' - '+err.message);
			}
		});
	});
}

function cargarLineaRegresionAsistByEstu(jsonData) {
	$('#cont_linea_regre').highcharts('StockChart', {
		/*chart: {
            type: 'scatter',
            zoomType: 'xy'
        },*/
		rangeSelector : {
			selected : 1,
			//inputEnabled: $('#cont_linea_regre').width() > 480,
			buttons: [{
				type: 'month',
				count: 1,
				text: '1mes'
			}, {
				type: 'month',
				count: 3,
				text: '3mes'
			}, {
				type: 'month',
				count: 6,
				text: '6mes'
			}, {
				type: 'ytd',
				text: 'YTD'
			}, {
				type: 'year',
				count: 1,
				text: '1 a\u00f1o'
			}, {
				type: 'all',
				text: 'Todo'
			}]
		},
		title : {
			text : 'Historial de asistencias (Temprano)'
		},
		series : [{
			name : 'Temprano',
            pointWith: 3,
			data : jsonData,
			tooltip: {
				valueDecimals: 0
			},
            lineWidth : 0,
            marker : {
                enabled : true,
                radius: 4
            }
            
		}]
	});
}

function cargarRadarGraphByEstu(dataRadar, nombres) {
	$('#cont_radar').highcharts({
        chart: {
            polar: true,
            type: 'line'
        },
        title: {
            text: 'Desempe\u00f1o del estudiante',
            x: -80
        },
        pane: {
            size: '80%'
        },
        xAxis: {
            categories: ['Temprano', 'Tarde Justif', 'Falta Justif', 'Falta', 'Tarde'],
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
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y}</b><br/>'
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            y: 0,
            layout: 'horizontal'
        },
        series: [{
            name: nombres.initCap(),
            data: dataRadar,
            pointPlacement: 'on'
        }]
    });
}

function mostrarGraficoPieByEstuAsistencia(jsonData) {
	var options = {
			legend: {
	            itemStyle: {
	                color: '#959595'
	            }
	        },
            chart: {
                renderTo: 'cont_graf_asist_estu',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                backgroundColor: "white",
                color:"#959595"
            },
            title: {
                text: 'Gr\u00e1fico de asistencias',
                style: {
                    color: '#959595'
                 }
            },
            tooltip: {
                formatter: function() {
                	return '<b style="color:#959595">'+ this.point.name +'</b>: <p style="color:#959595">'+ this.point.y +'</p>';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    showInLegend: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                        	return '<b style="color:#959595">'+ this.point.name +'</b>: <p style="color:#959595">'+ Math.round(this.point.percentage) +' %</p>';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: []
            }]
        }
    options.series[0].data = jsonData;
    chart = new Highcharts.Chart(options);
    $.each(options.series[0].data, function (key, value){
    	value.events.click = function (){ console.log(this.name); };
    });
}

function openModalInstrumentos() {
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	if(idCompetencia == '' || idCapacidad == '' || idIndicador == '') {
		msj('error', 'Seleccione un indicador');
		return;
	}
	modal('modalAsigInstrumento');
}

function buscarInstrumentos(e) {
	var buscar = $('#buscar').val();
	if(e != undefined) {
		if(e.keyCode != 13) {//TECLA ENTER
			return;
		}
		if(buscar.length == 0) {
			$('#contBusqInst').html(null);
			return;
		}
	}
	if(buscar.length < 3) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_detalle_curso/buscarInstrumentos',
	    	data   : { buscar  : buscar }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contBusqInst').html(data.tablaInstru);
	    	   $('#tbInstru').bootstrapTable({ });	 
	    	   componentHandler.upgradeAllRegistered(); 		
	       } else {
	    	   msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function asignarInstrumento() {
	var idInstru = $("input[name=radioInstru]:checked").data('id_instru');
	var concepto = $.trim($('#concepto').val());
	var idCompetencia = getComboVal('cmbCompetencias');
	var idCapacidad   = getComboVal('cmbCapacidad');
	var idIndicador   = getComboVal('cmbIndicador');
	var idBimestre    = getComboVal('cmbCicloAcad');
	if(!concepto) {
		msj('error', 'Debe ingresar el concepto y seleccionar la herramienta');
		return;
	}
	if(!idCompetencia || !idCapacidad || !idIndicador) {
		msj('error', 'Seleccione un indicador');
		return;
	}
	if(!idBimestre) {
		msj('error', 'Seleccione un ciclo acad&eacute;mico');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnMAI');
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_detalle_curso/asignarInstrumento',
	    	data   : { idInstru      : idInstru ,
	    		       concepto      : concepto ,
	    		       idCompetencia : idCompetencia ,
	    		       idCapacidad   : idCapacidad   ,
	    		       idIndicador   : idIndicador   ,
	    		       idBimestre    : idBimestre }
	    }).done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   setComboFull('cmbInstrumento', data.optInstrumentos, 'Instrumento');
	    	   $('#concepto').val(null);
	    	   $('#contFavoritosTb').html(null);
	    	   $('#cont_estudiantes').html(null);
	    	   msj('success', data.msj);
	    	   stopLoadingButton('btnMAI');
	    	   modal('modalAsigInstrumento');
	       } else {
	    	   msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

$('#modalAwards').on('hidden.bs.modal', function () {
	$('*[data-alu_id2="'+$idEstuAward+'"]').find('.awards-points').removeClass('mdl-bounce');
	$idEstuAward = null;
})

var $idEstuAward = null;
function openModalExtras(btn) {
	var idEstu = btn.closest('.card_award').find('.aluID').data('alu_id');
	if($idEstuAward != idEstu) {
		$idEstuAward = idEstu;
		Pace.restart();
		Pace.track(function() {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_detalle_curso/getAwards',
		    	data   : { id_estudiante : $idEstuAward }
		    }).done(function(data) {
		    	data = JSON.parse(data);
		    	if(data.error == 0) {
		    		btn.parent().find('.awards-points').addClass('mdl-bounce');
		    		$('#cont_awards_positive').html(data.positivos);
		    		$('#cont_awards_negatives').html(data.negativos);
		    		$('#contTabHistAward').html(data.awards_estu);
		    		$('#tbAwardsEstu').bootstrapTable({ });
		    	}
		    });
		});
	}
	modal('modalAwards');
}

function giveAward(divObj) {
	var idAward = divObj.data('id_award');
	if(!$idEstuAward || !idAward) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_detalle_curso/giveAward',
	    	data   : { id_estu : $idEstuAward,
	    		       idAward : idAward }
	    }).done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	    		msj('success', data.msj);
	    		modal('modalAwards');
	    		$('*[data-alu_id2="'+$idEstuAward+'"]').find('.awards-points').html(data.cant_awards);
	    		$('*[data-alu_id2="'+$idEstuAward+'"]').find('.awards-points').removeClass('mdl-bounce');
	    		$idEstuAward = null;
	    	}
	    });
	});
}

function refreshHistorAward() {
	if(!$idEstuAward) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_detalle_curso/refreshGetAwards',
	    	data   : { id_estu : $idEstuAward }
	    }).done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	    		$('#contTabHistAward').html(data.awards_estu);
	    		$('#tbAwardsEstu').bootstrapTable({ });
	    	}
	    });
	});
}

function tableEventsEstudiantes() {
	$(function () {
	    $('#tbEstus').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

/* ANIMACION DE MERITOS*/

function addMdlAwards(){
	$('.mdl-awards').removeClass('transformed');
	setTimeout(function() {
		$('.mdl-awards').addClass('transformed');
	},300);
}
