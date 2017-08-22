var eventId     = null;
var fecha       = null;
var fechaFin    = null;
var descripcion = null;
actual  = $.datepicker.formatDate('yy-mm-dd', new Date());

function initAgenda() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
}

function initCalendario(data) {
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
		ruta_js_metodo : '../public/modulos/sped/js/jsagenda.js',
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
	
	/*$('.cal-month-day.cal-day-inmonth.cal-day-today').click(function() {
		alert('agregar nuevo evento...');
	});*/
}

function getDetalleEvento(modalId, aTag, events_sources) {
	$('#'+modalId+' h2.mdl-card__title-text').text(aTag.html());
	var idEvent = aTag.data('event-id');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { idEvent : idEvent },
			url  : 'c_agenda/getDetalleEvento',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					if(data.evaluar == true) {
						$('#btnEvaluar').unbind('click');
						$('#btnEvaluar').prop('disabled', false);
						$('#btnEvaluar').on('click', goToEvaluar);
						$('#evaluacion').val(idEvent);
						modal('evaluarPendiente');
					}
					if(data.borrar == true) {
						$('#openConfirmBorrar').unbind('click');
						$('#openConfirmBorrar').prop('disabled', false);
						$('#openConfirmBorrar').on('click', confirmBorrarVisita);
						
						$('#confirmBorrar').unbind('click');
						$('#confirmBorrar').prop('disabled', false);
						$('#confirmBorrar').on('click', borrarVisita);
					} else {
						$('#openConfirmBorrar').unbind('click');
						$('#openConfirmBorrar').prop('disabled', true);
						
						$('#confirmBorrar').unbind('click');
						$('#confirmBorrar').prop('disabled', true);
					}
				}
			} catch(err) {
				location.reload();
			}
		});
	});
}

function initCalendar(datos) {
	$('#calendar').fullCalendar({
		header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
		editable: true,
		lang: 'es',
    	weekends: false,
    	events: datos,
    	dayRender: function(date, cell) {
            if (moment().diff(date,'days') > 0) {
                cell.css("background-color","#efefef");
            }
            var today = new Date();
        },
    	dayClick: function(date) {
    		var dateFormat = $.datepicker.formatDate('dd/mm/yy', new Date(date.format()));
    		if(date.format() < actual) {
    			msj('warning','La fecha de evaluacion no puede ser menor o igual a la actual');
    		} else {
    			$('#fechaAdd').html('Nueva evaluacion fecha '+ dateFormat);
    			fecha = date.format();
    			modal('modalAddTipoVisita');
    		}
        },
        eventClick: function(event) {
        	if(event.title == 'PENDIENTE' || event.title == 'NO EJECUTADO') {
        		$('#evaluacion').val(event.id);
        		$('#estado').val(event.title);
        		modal('evaluarPendiente');
        	} else if(event.title == 'EJECUTADO') {
        		msj('success' , 'Ya se realizó la evaluación');
        	} else if(event.title == 'INJUSTIFICADO') {
        		msj('warning' , 'La evaluación ya finalizó');
        	}
	    },
	    eventDrop: function(event, delta, revertFunc) {
	    	fechaIOrigen = event.start._i;
	    	eventId      = event.id;
	    	fecha        = event.start.format();
	    	fechaFin     = event.end.format();
	    	descripcion  = event.title;
	    	if(fechaIOrigen < actual || event.title != 'PENDIENTE') {
	    		msj('warning','No se puede editar esta evaluación');
	    		revertFunc();
	    	} else {
	    		if(fecha < actual) {
		    		msj('warning','No se puede cambiar la evaluacion a fechas menores o igual a la actual');
		    		revertFunc();
		    	} else {
		    		var dateFormat = $.datepicker.formatDate('dd/mm/yy', new Date(fecha));
			    	$('#fechaCambio').html('¿Desea mover el evento al '+dateFormat+'?');
			        modal('modalChangeEvent');
		    	}
	    	}
	        //REGRESA A LA FECHA ORIGINAL
	        $("#btncancelar").click(function() {
	        	$(this).data('clicked', true);
			    revertFunc();
			});
	    }
    });
}

function onchangeSelectFiltro(){
	var tipoFiltro = $('#selectFiltro option:selected').val();
	if(tipoFiltro == 'D' || tipoFiltro == 'C' || tipoFiltro == 'A') {
		$("#inputFiltro").prop('disabled', false);
		$("#inputFiltro").parent().removeClass('is-disabled');
		$("#inputFiltro").val(null);
		$("#inputFiltro").focus();
	} else {
		$("#inputFiltro").prop('disabled', true);
		$("#inputFiltro").parent().addClass('is-disabled');
		$("#inputFiltro").val(null);
	}
}

function onChangeInputFiltro() {
	var condicion = $('#selectFiltro option:selected').val();
	var texto     = $('#inputFiltro').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { condicion : condicion,
					 texto     : texto },
		    url  : 'c_agenda/getHorariosByFiltro',
		    type : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbHorarios').html(data.tbHorarios);
			    $('#tb_horarios').bootstrapTable({ });
				componentHandler.upgradeAllRegistered();
				tableEvents('tb_horarios');
				var tableData = $('#tb_horarios').bootstrapTable('getData');
				var horarios = [];
				$.each(tableData, function( key, value ) {
					var idx          = value[0];
					var radio_button = value['radio_button'];
					var docente      = value[2];
					var curso        = value[3];
					var aula         = value[4];
					var horario = { "idx"          : idx,
							        "radio_button" : radio_button,
							        "docente"      : docente,
							        "curso"        : curso,
							        "aula"         : aula };
					horarios.push(horario);
				});
				horarios = JSON.stringify(horarios);
				sessionStorage.setItem('horarios', horarios);
			} else {
				msj('error', data.msj);
			}
		});
	});
}

function clickRadio(radio) {
	var checked  = radio.is(":checked");
	var _idx     = radio.data('idx');
	var horarios = [];
	
	var tableData = $('#tb_horarios').bootstrapTable('getData');
	$.each(tableData, function( key, value ) {
		var obj = $(value['radio_button']).find("input");
		var idx = obj.data('idx');
		var chekk = '';
		if(_idx == idx && checked == true) {
			chekk = 'checked';
		}
		var newCheck = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-b-20" for="'+obj.attr('id')+'" >'+
				   	   '    <input type="radio" '+chekk+' id="'+obj.attr('id')+'" class="mdl-radio__button" name="radioVals"'+ 
				   	   '           value="'+obj.data('id-main')+'" onclick="clickRadio($(this));" '+
				   	   '           data-id-main="'+obj.data('id-main')+'" data-idx="'+idx+'">'+
				       '    <span class="mdl-radio__label"></span>'+
					   '</label>';
		tableData[key]['radio_button'] = newCheck;
		var idx          = (value[0] ? value[0] : value['idx']) ;
		var radio_button = newCheck;
		var docente      = (value[2] ? value[2] : value['docente']);
		var curso        = (value[3] ? value[3] : value['curso']);
		var aula         = (value[4] ? value[4] : value['aula']);
		var horario = { "idx"          : idx,
				        "radio_button" : radio_button,
				        "docente"      : docente,
				        "curso"        : curso,
				        "aula"         : aula };
		horarios.push(horario);
	});
	//
	$('#tb_horarios').bootstrapTable({
        data : tableData
    });
	componentHandler.upgradeAllRegistered();
	horarios = JSON.stringify(horarios);
	sessionStorage.setItem('horarios', horarios);
}

function guardarNuevaEvaluacion() {
	var tipoVisita = $('#selectTipoVisita option:selected').val();
	var horaInicio = $('#horaInicio').val();
	var horaFin    = $('#horaFin').val();
	var horario    = $('input[name=radioVals]:checked').val();
	var fechaVisit = $('#fechaVisitar').val();
	if(!tipoVisita) {
		msj('warning', 'Seleccione el tipo de visita');
		return;
	}
	if(!horaInicio) {
		msj('warning', 'Ingrese la hora de inicio');
		return;
	}
    if(!horaFin) {
    	msj('warning', 'Ingrese la hora fin');
		return;
	}

    var inicHora = new Date();
    var matches = horaInicio.toLowerCase().match(/(\d{1,2}):(\d{2}) ([ap]m)/);
    var hora    = (parseInt(matches[1]) + (matches[3] == 'pm' ? 12 : 0));
    var min     = matches[2];
    inicHora.setHours(hora);
    inicHora.setMinutes(min);
    
    var finHora = new Date();
    var matches = horaFin.toLowerCase().match(/(\d{1,2}):(\d{2}) ([ap]m)/);
    var hora    = (parseInt(matches[1]) + (matches[3] == 'pm' ? 12 : 0));
    var min     = matches[2];
    finHora.setHours(hora);
    finHora.setMinutes(min);
    
	if(finHora < inicHora) {
		msj('warning', 'La hora de inicio debe ser mayor');
		return;
	}
	if(!horario) {
		msj('warning', 'Seleccione el/la docente a evaluar.');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { tipoVisita : tipoVisita,
					 horaInicio : horaInicio,
					 horaFin    : horaFin,
					 horario    : horario,
					 fecha      : fechaVisit },
		    url  : 'c_agenda/guardarNuevaEvaluacion',
		    type : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1 && !data.error_calendar) {
				msj('error' , data.msj);
			} else if(data.error == 0) {
				initCalendario(JSON.parse(data.calendarioData));
				code_calendar = 0;
				var switchHtml = '	<div style="position: absolute; right: 42px; top: 2.5px; color: #FFFFFF">Google Calendar</div>'+
								 '	<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch_aplicar">'+
								 '		<input type="checkbox" id="switch_aplicar" class="mdl-switch__input" onchange="addToCalendar($(this));">'+
								 '	</label>';
				$('#switch_aplicar').closest('.mdl-card__menu').html(switchHtml);
				componentHandler.upgradeAllRegistered();
				resetFields();
				modal('modalAddTipoVisita');
				msj('success' , data.msj);
			} else if(data.error_calendar && data.error == 1) {
				code_calendar = 0;
				var switchHtml = '<div style="position: absolute; right: 42px; top: 2.5px; color: #FFFFFF">Google Calendar</div>'+
							     '    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch_aplicar">'+
							     '        <span class="mdl-switch__label"></span>'+
								 '        <input type="checkbox" id="switch_aplicar" class="mdl-switch__input" onchange="addToCalendar($(this));">'+
								 '    </label>';
				$('#switch_aplicar').closest('.mdl-card__menu').html(switchHtml);
				componentHandler.upgradeAllRegistered();
				msj('error', data.msj);
			}
		});
	});
}

function resetFields() {
	$('#fechaVisitar').val(null);
	$('select[name=selectTipoVisita]').val("");
	$('#selectTipoVisita').selectpicker('refresh');
	$('select[name=selectFiltro]').val("");
	$('#selectFiltro').selectpicker('refresh');
	$('#horaInicio').val('');
	$('#horaFin').val('');
	$('#inputFiltro').val('');
	$('#contTbHorarios').html('');
	removeSessionStorage('fechaVisitar', 'selectTipoVisita', 'horaInicio', 'horaFin',
			'selectFiltro', 'inputFiltro', 'horarios');
}

function changeEvaluacionDate() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { eventId     : eventId,
					 descripcion : descripcion,
				     fechaInicio : fecha,
				     fechaFin    : fechaFin },
		    url  : 'c_agenda/changeEvaluacionDate',
		    type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				msj('warning' , data.msj,'');
			} else {
				modal('modalChangeEvent');
				msj('success' , data.msj,'');
				//ELIMINA Y AGREGA EL EVENTO AL CALENDARIO
				$('#calendar').fullCalendar('removeEvents', eventId);
				$('#calendar').fullCalendar('renderEvent', data);
			}
		});
	});
}

function goToEvaluar() {
	var idEvaluacion = $('#evaluacion').val();
	if(!idEvaluacion) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { idEvaluacion : idEvaluacion },
		    url  : 'c_agenda/goToEvaluarPendiente',
		    type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				msj('warning' , data.msj);
			} else {
				window.location.href = data.url;
			}
		});
	});
}

function confirmBorrarVisita() {
	modal('confirmBorrarVisita');
}

function borrarVisita() {
	var idEvaluacion = $('#evaluacion').val();
	if(!idEvaluacion) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { idEvaluacion : idEvaluacion },
		    url  : 'c_agenda/borrarAgenda',
		    type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				msj('warning' , data.msj);
			} else {
				initCalendario(JSON.parse(data.calendarioData));
				modal('confirmBorrarVisita');
				modal('evaluarPendiente');
				msj('success' , data.msj);
			}
		});
	});
}

function addToCalendar(switchCalendar) {
	var checked = switchCalendar.is(":checked");
	if(!checked) {
		$.ajax({
			url  : 'c_agenda/removeCode',
			type : 'POST'
		}).done(function(data) {
			code_calendar = 0;
		});
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			/*data : { idFactor    : idCriterio,
				     idSubFactor : idIndi },*/
			url  : 'c_agenda/addToGoogleCalendar',
			type : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.url) {
				window.location.replace(data.url);
			}
		});
	});
}

function changeValor(obj, esCombo) {
	if(obj.data('set-datos') == 0) {
		if(!esCombo) {
			sessionStorage.setItem(obj.attr('id'), obj.val());
		} else {
			sessionStorage.setItem(obj.attr('id'), $('#'+obj.attr('id')+' option:selected').val());
		}
	}
}

function setValor(idObj, esCombo) {
    $('#'+idObj).data('set-datos', 1);
    if(!esCombo) {
    	$('#'+idObj).val(sessionStorage.getItem(idObj));	
    } else {
    	setValueCombo(idObj, sessionStorage.getItem(idObj));
    }
	$('#'+idObj).data('set-datos', 0);
}

function tableEvents(idTabla){
	$(function () {
	    $('#'+idTabla).on('all.bs.table', function (e, name, args) {

	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check.bs.table', function (e, row) {

	    })
	    .on('uncheck.bs.table', function (e, row) {

	    })
	    .on('check-all.bs.table', function (e) {

	    })
	    .on('uncheck-all.bs.table', function (e) {

	    })
	    .on('load-success.bs.table', function (e, data) {

	    })
	    .on('load-error.bs.table', function (e, status) {

	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	sessionStorage.setItem('pageNumber', size);
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}