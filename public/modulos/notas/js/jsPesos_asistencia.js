var idAsistenciaGlobal = null;
function initPesosAsistencia(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	initButtonLoad('btnAP', 'btnLC');
}

function modalAsignarPeso(obj) {
	idAsistenciaGlobal = obj.data('id_asistencia');
	var peso = obj.data('peso');
	$('#peso').val(peso);
	modal('mdAsignarPeso');
}

function agregarPeso() {
	addLoadingButton('btnAP');
	
	var peso = $('#peso').val();
	if(peso == null) {
		stopLoadingButton('btnAP');
		return;
	}
		Pace.restart();
		Pace.track(function() {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_pesos_asistencia/agregarPeso',
		    	data   : { idAsistencia  : idAsistenciaGlobal,
		    			   peso          : peso }
		    }).done(function(data) { 
			       data = JSON.parse(data);
			       if(data.error == 0) {
					   $('#contTbAsistencia').html(data.tabla_asistencia);
					   $('#tbAsistencia').bootstrapTable({ });	
					   componentHandler.upgradeAllRegistered();
					   modal('mdAsignarPeso');
					   } else {
						mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
					}
			       stopLoadingButton('btnAP');
			    });
			});	
}

var idAsistCalifGlobal = null;
function modalAgregarLimiteCalificacion(obj) {
	idAsistCalifGlobal = obj.data('id_asis_calif');
	var limite         = obj.data('limite');
	var notaNumerica   = obj.data('nota_numerica');
	var notaAlfabetica = obj.data('nota_alfabetica');
	$('#limite').val(limite);
	$('#notaNum').val(notaNumerica);
	document.getElementById("cmbNotaAlf").selectedIndex = notaAlfabetica;
	modal('mdLimiteCalificacion');
}

function agregarPesoCalificacion() {
	addLoadingButton('btnLC');
	
	var limite 	= $('#limite').val();
	var notaAlf = $('#cmbNotaAlf').val();
	var notaNum = $('#notaNum').val();
	if(limite == null || notaAlf == null || notaNum == null || idAsistCalifGlobal == null) {
		stopLoadingButton('btnLC');
		return;
	}
		Pace.restart();
		Pace.track(function() {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_pesos_asistencia/agregarAsistCalif',
		    	data   : { idAsistCalif : idAsistCalifGlobal,
		    			   limite       : limite,
		    			   notaAlf 	    : notaAlf,
		    			   notaNum 	    : notaNum }
		    }).done(function(data) { 
			       data = JSON.parse(data);
			       if(data.error == 0) {
        			   $('#contTbAsistenciaCalif').html(data.tabla_asistenciaCalif);
					   $('#tbAsistenciaCalif').bootstrapTable({ });	
					   componentHandler.upgradeAllRegistered();
					   modal('mdLimiteCalificacion');
					   } else {
						mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
					}
			       stopLoadingButton('btnLC');
			    });
			});	
}

function tableEventsAsistenciaPesoVisibility() {
	$(function () {
	    $('#tbAsistencia').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}