function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initButtonLoad('botonNC');
	initButtonCalendarDays('fecEnvio', 'fecEnvioEdit');
    initMaskInputs('fecEnvio', 'fecEnvioEdit');
	$("body").click(function(e){
		var target = $(e.target);
		if($(e.target).attr("id") != "popoverRoles" && !$(e.target).hasClass("popover") && $(".popover").length && $(".popover").hasClass("in")){
			console.log($(".popover").parent().html());
			$(".popover").removeClass("in");
			$(".popover").remove();
			$("button[data-toggle='popover']").trigger();
			$("button[data-toggle='popover']").removeAttr("aria-describedby");
		}
	});
}

function initCalendar(events){
	$("#btn-group-dates").fadeIn();
	initCalendarComponents();
	var calendar = $("#calendar").calendar({
	        events_source: events,
	        language: 'es-ES',
	        tmpl_path     : "../public/general/plugins/bootstrap-calendar-master/tmpls/",
	        onAfterViewLoad: function(view) {
	        	$('#fechaCalendar').text(this.getTitle());
				$('button.mdl-button, li.mdl-menu__item').removeClass('active');
				$('button.mdl-button[data-calendar-view="' + view + '"], li.mdl-menu__item[data-calendar-view="' + view + '"]').addClass('active');
			},
			modal : "#events-modal",
			ruta_js_metodo : '../public/modulos/pagos/js/jscorreos.js',
			funcion_name : 'getDetalleEvento'
         });
	$('button.mdl-button[data-calendar-nav], li.mdl-menu__item[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});
	$('button.mdl-button[data-calendar-view], li.mdl-menu__item[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});
}

function initCalendarComponents(){
	$('#calendarioCorreos').html('<div id="calendar"></div>');
	componentHandler.upgradeAllRegistered();
}

function getDetalleEvento(modalId, aTag, events_sources){
	var fecha = null;
	$.each(events_sources, function(idx, value) {
		if(value.id == aTag.data('event-id')) {
			fecha = value.start;
			return false;
		}
	});
	$('#'+modalId+' h2.mdl-card__title-text').text(aTag.data('original-title'));
	$.ajax({
		data  : {fecha : fecha},
		url   : 'c_correos/getInfoCorreo',
		async : false,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		fechaGlobal = data.fecha;
		$('#fecEnvioEdit').val(data.fecha);
		setCombo("selectTipoEdit", data.optTipoCorreo, ' Tipo de Correos',true);
	});
	abrirCerrarModal(modalId);
}
var fechaGlobal = null;
function abrirModalCrearEvento(){
	$(":input").inputmask();
    var hoy = getFechaHoy_dd_mm_yyyy();
	$("#fecEnvio").val(hoy);
	$("#fecEnvio").parent().addClass('is-dirty');
	$('select[name=selectTipo]').val("");
	$('#selectTipo').selectpicker('refresh');
	abrirCerrarModal('modalCrearEvento');
	stopLoadingButton('botonNC');
}

function guardarFechaEnvio(){
	addLoadingButton('botonNC');
	var fecha_envio = $('#fecEnvio').val();
	var selected    = $("#selectTipo option:selected").val();
	var checked     = $('#replicar').is(':checked');
	$.ajax({
		data  : {fecha_envio : fecha_envio,
			     selected    : selected,
			     checked     : checked},
		url   : 'c_correos/saveFechaEnvio',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			abrirCerrarModal('modalCrearEvento');
			$('#replicar').parent().removeClass('is-checked');
			initCalendar(JSON.parse(data.events));	
			stopLoadingButton('botonNC');
		}
		stopLoadingButton('botonNC');
		mostrarNotificacion('warning',data.msj);
	});
	
}
function editarFechaEnvio (){
	var fecha_envio = $('#fecEnvioEdit').val();
	var selected    = $("#selectTipoEdit option:selected").val();
	$.ajax({
		data  : {fecha_envio : fecha_envio,
			     selected    : selected,
			     fechaGlobal : fechaGlobal},
		url   : 'c_correos/editFechaEnvio',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			mostrarNotificacion('warning',data.msj);
			initCalendar(JSON.parse(data.events));
			abrirCerrarModal('events-modal');
		}else{
			stopLoadingButton('botonNC');
			mostrarNotificacion('warning',data.msj);
		}
	});
}
function eliminarFechaEnvio(){

	var checked     = $('#replicarElim').is(':checked');
	$.ajax({
		data  : {fechaGlobal : fechaGlobal,
				 checked     : checked},
		url   : 'c_correos/deleteFechaEnvio',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			mostrarNotificacion('warning',data.msj);
			initCalendar(JSON.parse(data.events));
			$('#replicarElim').parent().removeClass('is-checked');
			abrirCerrarModal('events-modal');
		}else{
			mostrarNotificacion('warning',data.msj);
		}
	});
}

function sendCorreos(){
	$.ajax({
		url   : 'c_correos/sendCorreosRecVencimiento',
		async : false,
		type  : 'POST'
	})
	.done(function(data){
	});
}