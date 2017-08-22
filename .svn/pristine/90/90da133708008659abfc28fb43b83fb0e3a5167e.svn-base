function initHorario() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_horario').bootstrapTable({ });	
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
}

function checkIfHorarioExiste() {
	$('#btnHorario').text('Aceptar');
	var idProfe = $('#selectDocente option:selected').val();
	var idCurso = $('#selectCurso option:selected').val();
	var idAula  = $('#selectAula option:selected').val();
	if(idProfe == null || $.trim(idProfe) == "" || idCurso == null || $.trim(idCurso) == "" || idAula == null || $.trim(idAula) == "") {
		return;
	}
	$.ajax({
		url: "c_horario/checkIfHorarioExiste",
        data: { idProfe  : idProfe,
        	    idAula   : idAula ,
        	    idCurso  : idCurso},
        async : false,
        type: 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			if(data.cantHorario == 0) {
				$('#btnHorario').text('Insertar');
				$("#btnHorario").on("click", function(){ insertarHorario(); });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
			} else {
				$('#msjHorario').text('Ya existe el horario');
				$('#btnHorario').text('ACEPTAR');
				$( "#btnHorario" ).unbind("click");
			}
		} else if(data.error == 1) {
			mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
		} else {
			
		}
	});
}

function cargarHorarios() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_horario/cargarHorarios",
	        async : false,
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success', data.msj, data.cabecera);
				$('#contTablaHorario').html(data.tbHorario);
				$('#tb_horario').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
			} else if(data.error == 1) {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			} else {
				
			}
		});
	});
}

function insertarHorario() {
	var idProfe = $('#selectDocente option:selected').val();
	var idCurso = $('#selectCurso option:selected').val();
	var idAula  = $('#selectAula option:selected').val();
	if(idProfe == null || $.trim(idProfe) == "" || idCurso == null || $.trim(idCurso) == "" || idAula == null || $.trim(idAula) == "") {
		return;
	}
	$.ajax({
		url: "c_horario/insertarHorario",
        data: { idProfe  : idProfe,
        	    idAula   : idAula ,
        	    idCurso  : idCurso},
        async : false,
        type: 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			mostrarNotificacion('success', data.msj, data.cabecera);
			$('#contTablaHorario').html(data.tbHorario);
			$('#tb_horario').bootstrapTable({ });			
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			initSearchTableNew();
			abrirCerrarModal('modalFiltro');
			
		} else if(data.error == 1) {
			mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
		} else {
			
		}
	});	
}

function abrirModalFiltros() {
	$('select[name=selectDocente]').val("");
	$('#selectDocente').selectpicker('refresh');
	$('select[name=selectCurso]').val("");
	$('#selectCurso').selectpicker('refresh');
	$('select[name=selectAula]').val("");
	$('#selectAula').selectpicker('refresh');
	$('#msjHorario').text('');
	$('#btnHorario').text('ACEPTAR');
	$( "#btnHorario" ).unbind("click");
	abrirCerrarModal('modalFiltro');
}

function deleteHorarioConfirma(idHorario) {
	$('#hidIdHorario').val(idHorario);
	$("#btnDeleteHorario").on("click", function(){ deleteHorario(); });
	abrirCerrarModal('modalConfirmDeleteHorario');
}

function deleteHorario() {
	var idHorario = $('#hidIdHorario').val();
	if($.trim(idHorario) == "" || idHorario == null) {
		mostrarNotificacion('error', 'Error inesperado', null);
		return;
	}
	$.ajax({
		url: "c_horario/borrarHorario",
        data: { idHorario  : idHorario},
        async : false,
        type: 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			mostrarNotificacion('success', data.msj, null);
			$('#contTablaHorario').html(data.tbHorario);
			$('#tb_horario').bootstrapTable({ });			
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			initSearchTableNew();
			abrirCerrarModal('modalConfirmDeleteHorario');
		} else if(data.error == 1) {
			mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
		}
		$( "#btnDeleteHorario" ).unbind("click");
	});
}