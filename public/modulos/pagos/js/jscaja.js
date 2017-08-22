var path = null;
var flgCerrada = null;

function init(){
	initButtonLoad( 'botonFC');
	initButtonCalendarDays('fechaInicio');
	initButtonCalendarDays('fechaFin');
    initMaskInputs('fechaInicio,fechaFin');
	$('#tb_caja').bootstrapTable({});
//	$('#tab2').css('pointer-events', 'none');
	
	if(flgAperturada == "complete") {
		$('#tab2').removeAttr('style');
	} else {
		$('#tab2').css('pointer-events', 'none');
	}
	initButtonLoad('botonApC','btnDev');
}

function descargarPdfCaja() {
	$('#fechaInicioForm').val($('#fechaInicio').val());
	$('#fechaFinForm').val($('#fechaFin').val());
	$('#formPdfDownload').submit();
}

function borrar(path) {
	$.ajax({
		data : { ruta : path },
        url : "c_caja/borrarPDF",
        async : false,
        type: 'POST'
	})
	.done(function(data) {
	});
}

function filtroCajaByFechas() {
	addLoadingButton('botonFC');
	var fechaInicio = $('#fechaInicio').val();
	var fechaFin    = $('#fechaFin').val();
	$.ajax({
		data  : {fechaInicio : fechaInicio,
			     fechaFin    : fechaFin},
		url   : 'c_caja/refreshCajaByDates',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#tbGeneral').html(data.tbCaja);
		$('#tableIngresos').html(data.tbIngresos);
		$('#tableEgresos').html(data.tbEgresos);
		$('#cabeceraDetalle').html('Detalle ' + data.fechaFiltro);
		$('#tb_caja').bootstrapTable({});
		$('#tb_ingresos').bootstrapTable({});
		$('#tb_egresos').bootstrapTable({});
		tableEventsCaja('tb_colaborador');	
		stopLoadingButton('botonFC');
		$(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
		modal('modalFiltrarCaja');
	})
}

function cerrarCaja() {
	var optionSelected  = $('#selectTipo option:selected').val();
	var persSolicitar   = $('#selectSecretaria option:selected').val();
	var tipo_incidencia = $('#selectTipoIncidencia option:selected').val();
	var monto           = $('#montoIncidencia').val();
	var observacion     = $('#observacionInci').val();
//	var secretarias     = totalSecretarias;
	if(optionSelected == ""){
		msj('error', 'Selecciona un tipo');
		return;
	}
	if(tipo_incidencia == ""){
		msj('error', 'Selecciona un tipo de incidencia');
		return;
	}
	var index = $('#selectTipo')[0].selectedIndex;
	if(totalSecretarias == 0) {
		return;
	} else {
		if(index == 1 && persSolicitar == ""){
			msj('error', 'Selecciona un apoyo');
			return;
		}
	}
	$.ajax({
		data  : {tipo 	 	 	 : optionSelected,
				 persona 	 	 : persSolicitar,
				 observacion 	 : observacion,
				 tipo_incidencia : tipo_incidencia,
				 monto           : monto,
				 secretarias     : totalSecretarias},
		url   : 'c_caja/cerrarCaja',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			stopLoadingButton();
			abrirCerrarModal('modalCerrarCaja');
			$('#tab2').addClass(data.clase);
			$('#tab2').find('a').unbind("click");
			$('.progress-bar.progress-bar-primary').css('width',data.width);
			width 	   = data.width;
			flgCerrada = data.clase;
			$('#tbGeneral').html(data.tbCaja);
			$('#tableIngresos').html(data.tbIngresos);
			$('#tableEgresos').html(data.tbEgresos);
			$('#tb_caja').bootstrapTable({});
			$('#tb_ingresos').bootstrapTable({});
			$('#tb_egresos').bootstrapTable({});
		}
		mostrarNotificacion('warning',data.msj);
		$("#tab2").unbind('click',false);
	});
}

function redirectDevoluciones() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_caja/redirectDevoluciones",
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			location.reload();
		});
	});
}

var sedeGlobal = null;
function getColaboradoresBySede() {
	addLoadingButton('btnMF');
	var idSede =  $('#selectSede option:selected').val();
	sedeGlobal = idSede;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_caja/getTableColaboradores",
	        data: {idSede 	 : sedeGlobal},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			stopLoadingButton('btnMF');
			$('#tableColaboradores').html(data.tableColaborador);
			$('#tb_colaborador').bootstrapTable({});
			tableEventsCaja('tb_colaborador');
			$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
			componentHandler.upgradeAllRegistered();
			initSearchTable();
		});
	});
}

var movimientoGlobal = null;
var checkGlobal = null;
var montoGlobal = null;
function openModalDevolver(id_movimiento, colaborador, check, monto) {
	checkGlobal = check;
	montoGlobal = monto;
	movimientoGlobal = id_movimiento;
	$('#colaborador').html(colaborador);
	abrirCerrarModal('modalDevolver');
}

function quitarCheck() {
	padre = checkGlobal.parent();
	padre.removeClass('is-checked');
	checkGlobal.attr('checked', false);
}

function estadoCambiar() {
	addLoadingButton('btnDev');
	var monto = $('#montoD').val();
	var observacion = $('#observacionD').val();
	if(observacion.trim( ) == '' || observacion.length == 0 || /^\s+$/.test(observacion)){
		return mostrarNotificacion('warning', 'Ingrese una Observacion');
	}
	
	if(monto.trim() == '' || monto.length == 0 || /^\s+$/.test(monto)){
		return mostrarNotificacion('warning', 'Ingrese una monto');
	}
	if(monto > montoGlobal){
		return mostrarNotificacion('warning', 'El monto debe ser menor a '+montoGlobal);
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_caja/cambiarEstado",
	        data: {id_movimiento : movimientoGlobal,
	        	   idSede 	     : sedeGlobal,
	        	   monto         : monto,
	        	   observacion   : observacion,
	        	   montoGlobal   : montoGlobal},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if (data.error == 1) {
				mostrarNotificacion('warning', data.msj);
			} else {
				mostrarNotificacion('succes', data.msj);
				$('#tableColaboradores').html(data.tableColaborador);
				$('#tb_egresos').bootstrapTable({});
				tableEventsCaja('tb_egresos');
				componentHandler.upgradeAllRegistered();
				initSearchTable();
				stopLoadingButton('btnDev');
				setearInput('montoD'       , null);
				setearInput('observacionD' , null);
				$(document).ready(function(){
		    	    $('[data-toggle="tooltip"]').tooltip(); 
		    	    $('[data-toggle="popover"]').popover();
		        });
				modal('modalDevolver');
			}
		});
	});
}

function tableEventsCaja(idTable) {
	$(function () {
	    $('#'+idTable).on('all.bs.table', function (e, name, args) {
	    	componentHandler.upgradeAllRegistered();
	    	$(document).ready(function(){
	    	    $('[data-toggle="tooltip"]').tooltip(); 
	    	    $('[data-toggle="popover"]').popover();
	        });
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
			componentHandler.upgradeAllRegistered();
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
			componentHandler.upgradeAllRegistered();
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    	$(document).ready(function(){
	    	    $('[data-toggle="tooltip"]').tooltip(); 
	    	    $('[data-toggle="popover"]').popover();
	        });
	    })
	    .on('check.bs.table', function (e, row) {
	    })
	    .on('uncheck.bs.table', function (e, row) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check-all.bs.table', function (e) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('uncheck-all.bs.table', function (e) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('load-success.bs.table', function (e, data) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('load-error.bs.table', function (e, status) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    	$(document).ready(function(){
	    	    $('[data-toggle="tooltip"]').tooltip(); 
	    	    $('[data-toggle="popover"]').popover();
	        });
	    })
	    .on('search.bs.table', function (e, text) {
	    	$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

$('header .mdl-button__return').click(function() {
	location.reload();
});

function aperturarCaja() {
	addLoadingButton('botonApC');
	$.ajax({
		url   : 'c_caja/aperturarCaja',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$('#tab1').addClass(data.clase);
			flgAperturada = data.clase;
			$('#tab1').find('a').unbind("click");
			$('#tab2').removeAttr('style');
			stopLoadingButton('botonApC');
			abrirCerrarModal('modaAperturarCaja');
			$('#tbGeneral').html(data.tbCaja);
			$('#tb_caja').bootstrapTable({});
			
		} else {
			$("#tab2").unbind('click',false);
		}
		stopLoadingButton('botonApC');
		msj('warning',data.msj);
		
	});
}

function openModalAperturar(idmodal) {
	setTimeout(function(){
		$('.progress-bar.progress-bar-primary').css('width',width);
	},400);
	if(flgAperturada != 'complete'){
		abrirCerrarModal('modaAperturarCaja');
	}
}

function openModalCerrar(){
	setTimeout(function(){
		$('.progress-bar.progress-bar-primary').css('width',width);
	},400);
	if(flgCerrada != 'complete' && flgAperturada == 'complete'){
	    abrirCerrarModal('modalCerrarCaja');
	}
}

$('#modaAperturarCaja').on('hidden.bs.modal', function () {
	$('#tab1').removeClass('active');
});

$('#modalCerrarCaja').on('hidden.bs.modal', function () {
	$('#tab2').removeClass('active');
});

var totalSecretarias = null;
function searchSecretaria(){
	var nombres = $('#nombreSecretaria').val();
	$.ajax({
		data  : {nombres : nombres},
		url   : 'c_caja/searchSecretaria',
		async : false,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		totalSecretarias = data.cont;
		setCombo('selectSecretaria', data.opt, 'una secretaria(o)');	
	});
}

function selectTipoCerrado(id){
	var flg_tipo = $(id).find(':selected').data('flg');
	if(flg_tipo == 1){//HABILIAR SELECCION
		searchSecretaria();
		$('#selectSecretaria').removeAttr('disabled');
		$('#selectSecretaria').parent().find('button').removeClass('disabled');
	} else{//INHABILIAR SELECCION
		$('#selectSecretaria').attr('disabled','disabled');
		$('#selectSecretaria').parent().find('button').addClass('disabled');
	}
}

var personaSolicitar = null;
function abrirModalSolicitar(persona,nombre){
	$('#infoSolicitar').text(nombre);
	personaSolicitar = persona;
	modal('modalAceptarPersonal');
}

$('#selectTipoIncidencia').change(function(){
	var index = $("#selectTipoIncidencia")[0].selectedIndex;
	if(index == 3){
		$('#montoIncidencia').attr('disabled','disabled');
		$('#montoIncidencia').parent().addClass('is-disabled');
		$('#observacionInci').attr('disabled','disabled');
		$('#observacionInci').parent().addClass('is-disabled');
	} else{
		$('#montoIncidencia').removeAttr('disabled');
		$('#montoIncidencia').parent().removeClass('is-disabled');
		$('#observacionInci').removeAttr('disabled');
		$('#observacionInci').parent().removeClass('is-disabled');
	}
});

function detalleCaja (persona) {
	window.location.href = 'c_caja?persona='+persona;
}

function redirectIncidencias(persona) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : 'c_caja/redirectIncidencias',
	        data: {persona     : persona},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			window.location.href = data.url;
		});
	});
}

function redirectMisIncidencias(persona) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : 'c_caja/redirectMisIncidencias',
	        data: {persona : persona},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			window.location.href = data.url;
		});
	});
}

function acptarRechazarCaja(flg,caja){
	if(flg != 1 && flg != 0){
		msj('warning','Acci&oacute;n no permitida');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : 'c_caja/aceptaRechazaCaja',
	        data: {flg  : flg,
	        	   caja : caja},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			msj('warning',data.msj);
		});
	});
}


