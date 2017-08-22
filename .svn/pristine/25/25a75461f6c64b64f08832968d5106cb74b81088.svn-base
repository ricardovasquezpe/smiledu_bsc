function init() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	initButtonLoad('btnFD', 'buttonDocente', 'btnATC', 'btnCTD', 'btnCDD');
}

var idTallerGlobal = null;
var idGrupoGlobal  = null;
function inicializarComboHtml(id) {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('#'+id).selectpicker('mobile');
	} else {
		$('#'+id).selectpicker();
	}
}

function getCmbGrupos() {
	var idTaller = $('#cmbTaller option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_solicitud_grupo/getComboGrupo',
	 		data  : { idTaller : idTaller }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contCmbGrupo').html(data.cmbGrupo);
				$('#contCmbGrupo').css('display', 'block');
				inicializarComboHtml('cmbGrupo');
				idTallerGlobal = idTaller;
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			
		});
	});
}

function getTbSolicitudes() {
	var idGrupo          = $('#cmbGrupo option:selected').val();
	
	if(idTallerGlobal == '' || idTallerGlobal == null) {
		mostrarNotificacion('error', 'Seleccione un Taller');
		return;
	}
	if(idGrupo == '' || idGrupo == null) {
		mostrarNotificacion('error', 'Seleccione un grupo');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_solicitud_grupo/getTbSolicitudes',
	 		data  : { idTaller : idTallerGlobal,
	 			      idGrupo  : idGrupo        }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				//$('#contCmbGrupo').html(data.cmbGrupo);
				$('#tSolicitudes').css('display', 'block');
				inicializarComboHtml('cmbGrupo');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			
		});
	});
	
}

function getMotivoModal(liObject) {
	var motivo = liObject.closest('tr').find('.btnM').data('motivo');
	$('#contMotivo').html(motivo);
	$('#tSolicitudes').css('display', 'block');
	inicializarComboHtml('cmbGrupo');
	pintarFila(liObject);
	modal('modalMotivo');
}

var idMainGlobal  	= null;
var idMainSolicitudGlobal = null;
function getRechazarAceptarModal(liObject, ident) {
	idMainGlobal = liObject.closest('tr').find('.btnM').data('id_main');

	if(ident == 4) {
		$('.rechAcep').html('&#191;Desea aceptar esta solicitud?');
		$('#comentario').html('Al aceptar la solicitud el alumno sera cambiado al grupo deseado');
		$('#btnCDD').attr('onclick' , 'aceptarSolicitud()');
	} else if(ident == 3) {
	    $('.rechAcep').html('&#191;Grupo lleno, desea rechazar esta solicitud?');
		$('#comentario').html('El grupo ya se encuentra lleno, solo podra rechazar la solicitud');
		$('#btnCDD').attr('onclick' , 'rechazarSolicitud()');
	} else if(ident == 2) {
		$('.rechAcep').html('&#191;Desea rechazar esta solicitud?');
		$('#comentario').html('Al rechazar la solicitud el alumno permanecera en su grupo actual y no sera cambiado al grupo deseado');
		$('#btnCDD').attr('onclick' , 'rechazarSolicitud()');
	}
	modal('mdConfirmAcepRech');
}

function rechazarSolicitud() {
	if(idMainGlobal ==  null || idMainGlobal == '') {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_solicitud_grupo/rechazarSolicitud',
	 		data  : { idMain : idMainGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbSolicitudes').html(data.tbSolicitudes);
				modal('mdConfirmAcepRech');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}		
		});
	});	
}

function aceptarSolicitud() {
	if(idMainGlobal ==  null || idMainGlobal == '') {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_solicitud_grupo/aceptarSolicitud',
	 		data  : { idMain : idMainGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbSolicitudes').html(data.tbSolicitudes);
				modal('mdConfirmAcepRech');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}		
		});
	});	
}

function pintarFila(liObject) {
    $.each($("#tbSolicitudes tbody>tr"), function() {
	    $(this).css('background-color','white');
    });
    $("#tbSolicitudes tr").filter(function() {
        return $(this).data('index') == liObject.closest('tr').data('index');
    }).css('background-color','#EEEEEE');
}