var eventId     = null;
var fechaCambio = null;
var actual  = $.datepicker.formatDate('yy-mm-dd', new Date());
var actualYear  = (new Date).getFullYear();

function initCalendar(datos) {
	var options = {
		events_source : datos,
		language      : 'es-ES',
		tmpl_path     : "../public/general/plugins/bootstrap-calendar-master/tmpls/",
		onAfterViewLoad : function(view) {
			$('#fechaCalendar').text(this.getTitle());
			$('button.mdl-button').removeClass('active');
			$('button.mdl-button[data-calendar-view="' + view + '"]').addClass('active');
			$('li.mdl-menu__item').removeClass('active');
			$('li.mdl-menu__item[data-calendar-view="' + view + '"]').addClass('active');
		},
		modal : "#modalEditEvent",
		ruta_js_metodo : '../public/general/js/jsmantenimiento/jscalendario.js',
		funcion_name : 'abrirModalEditEvento'
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
}

function abrirModalAddEvento() {
	$('#descripcion').parent().removeClass('is-dirty');
	$('#descripcion').val(null);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_calendario/checkDiaNoLaborable', 
			data : {fecha : fechaCambio},
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				modal('modalNewDiaNoLab');
			}
		});
	});
}

function addNewDiaNoLaborable() {
	var descripcion = $('#descripcion').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_calendario/agregarNuevoDiaNoLaborable', 
			data : {descripcion : descripcion,
					fecha		: fechaCambio},
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				msj('success', data.msj, data.cabecera);
			} else{
				//AGREGA EL EVENTO AL CALENDARIO
				$('#calendar').fullCalendar('renderEvent', data);
				modal('modalNewDiaNoLab');
				mostrarNotificacion('success', data.msj, data.cabecera);
			}
		});
	});
}

function abrirModalEditEvento(modalId, aTag, events_sources) {
	$('#descripcionEdit').val($.trim(aTag.data('original-title')));
	if($('#descripcionEdit').val().length > 0) {
		$('#descripcionEdit').parent().addClass('is-focused');
	}
	fechaCambio = aTag.data('event-id');
	/*if(event.laborable == 1){
		$("#chkNoLabo").prop('checked', false);
	}else{
		$("#chkNoLabo").prop('checked', true);
	}*/
	modal(modalId);
	$('#modalEditEvent').modal({
	    backdrop: 'static',
	    keyboard: false
	});
}

function editarDiaNoLaborable() {
	if($.trim($('#descripcionEdit').val()) == "" || $('#descripcionEdit').val() == null ) {
		mostrarNotificacion('error', 'Registre la descripción','');
		return;
	}
	var borrarLaborable = $('#chkNoLabo').is(':checked');
	$.ajax({
		url  : 'c_calendario/editarDiaNoLaborable', 
		data : {descripcion     : $.trim($('#descripcionEdit').val()),
				fecha		    : fechaCambio,
				borrarLaborable : borrarLaborable},
		async: false,
		type : 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 1){
			msj('error', data.msj, data.cabecera);
		} else {
			initCalendar(JSON.parse(data.calendarioData));
			modal('modalEditEvent');
			msj('success', data.msj, data.cabecera);
			fechaCambio = null;
		}
	});
}