
var $idSedeGlobal = null;
function verDetalleAulas(btn) {
	if(!$_idEncuestaGlobal) {
		return;
	}
	var idSede = btn.closest('tr').find('.claseIdentif').data('id_sede');
	$idSedeGlobal = idSede;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getDetalleAvanceAula',
			data : { idSede : idSede , 
				     idEncuesta : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contTbAulas').html(data.tablaAulas);
		            $('#tbAulasAvance').bootstrapTable({ });
		            $.each($("#tbSedesAvance tr"), function() {
						$(this).css('background-color','white');
					});
		            $('.detalle_card2').html('&Aacute;ulas');
		            $('#btnRefreshDeta').unbind('click');
		            $('#btnRefreshDeta').on('click', refreshAulas);
					var newRow = $("#tbSedesAvance tr").filter(function() {
								    return $(this).data('index') == btn.closest('tr').data('index');
								 });
					newRow.css('background-color','rgba(255,146,0,0.2)');
					$('.empty_state_img').css('display', 'none');
				}
			} catch(err) {
				location.reload();
			}
		});
	});
}

function verDetalleSedes(btn) {
	if(!$_idEncuestaGlobal) {
		return;
	}
	var idSede = btn.closest('tr').find('.claseIdentif').data('id_sede');
	$idSedeGlobal = idSede;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getDetalleAvanceArea',
			data : { idSede : idSede , 
				     idEncuesta : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			//try {
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contTbAulas').html(data.tablaAreas);
		            $('#tbAreasAvance').bootstrapTable({ });
		            $.each($("#tbSedesAvance tr"), function() {
						$(this).css('background-color','white');
					});
		            $('#btnRefreshDeta').unbind('click');
		            $('#btnRefreshDeta').on('click', refreshAreas);
		            $('.detalle_card2').html('&Aacute;reas');
					var newRow = $("#tbSedesAvance tr").filter(function() {
								    return $(this).data('index') == btn.closest('tr').data('index');
								 });
					newRow.css('background-color','rgba(255,146,0,0.2)');
					$('.empty_state_img').css('display', 'none');
				}
			/*} catch(err) {
				location.reload();
			}*/
		});
	});
}

function refreshSedes() {
	if(!$_idEncuestaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/refreshSedes',
			data : { idEncuesta : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#contTbSedes').html(data.sedes_tabla);
				$('#contTbAulas').html(null);
				$('.empty_state_img').css('display', 'block');
	            $('#tbSedesAvance').bootstrapTable({ });
	            $('#btnRefreshDeta').unbind('click');
	            componentHandler.upgradeAllRegistered();
			} catch(err) {log(err);
				//location.reload();
			}
		});
	});
}

function refreshAulas() {
	if(!$idSedeGlobal || !$_idEncuestaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getDetalleAvanceAula',
			data : {idSede : $idSedeGlobal ,
				    idEncuesta : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contTbAulas').html(data.tablaAulas);
		            $('#tbAulasAvance').bootstrapTable({ });
		            $('.empty_state_img').css('display', 'none');
				}
			} catch(err) {log(err);
				//location.reload();
			}
		});
	});
}

function refreshAreas() {
	if(!$idSedeGlobal || !$_idEncuestaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getDetalleAvanceArea',
			data : {idSede : $idSedeGlobal ,
				    idEncuesta : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contTbAulas').html(data.tablaAreas);
		            $('#tbAreasAvance').bootstrapTable({ });
		            $('.empty_state_img').css('display', 'none');
				}
			} catch(err) {log(err);
				//location.reload();
			}
		});
	});
}

function imprimirEncuestaFisica(btn) {
	var idSede = btn.closest('tr').find('.claseIdentif').data('id_sede');
	$('<input type="hidden" name="idSede" id="idSede"/>').val(idSede).appendTo('#formPdfEncu');
	$('<input type="hidden" name="idEncu" id="idEncu"/>').val($_idEncuestaGlobal).appendTo('#formPdfEncu');
    $('#formPdfEncu').submit();
}

function imprimirEncuestaFisicaSede(btn) {
	var idSede = btn.closest('tr').find('.claseIdentif').data('id_sede');
    $('<input type="hidden" name="idSede" id="idSede"/>').val(idSede).appendTo('#formPdfEncuSede');
    $('#formPdfEncuSede').submit();
}

function imprimirEncuestaPersonalAvanceSede(btn) {
	var idSede = btn.closest('tr').find('.claseIdentif').data('id_sede');
    $('<input type="hidden" name="idSede" id="idSede"/>').val(idSede).appendTo('#formPdfEncuPers');
    $('<input type="hidden" name="idEncu" id="idEncu"/>').val($_idEncuestaGlobal).appendTo('#formPdfEncuPers');
    $('#formPdfEncuPers').submit();
}

function openModalPickEncuestaEFQM() {
	modal('modalSelectEncuEFQM');
}

var $_idEncuestaGlobal = null;
function verEncuestaSeguimiento() {
	var idEncu = $('input[name="radioEncus"]:checked').val();
	if(!idEncu) {
		return;
	}
	$_idEncuestaGlobal = idEncu;
	addLoadingButton('btnMSEEFQM');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getSedesByEncuesta',
			data : { idEncu : idEncu },
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			$('.cards').css('display', 'block');
			$('#divEmptySte').css('display', 'none');
			$('#contTbSedes').html(data.sedes_tabla);
			$('#tbSedesAvance').bootstrapTable({ });
			$('.titulo_encuesta_sedes').html(data.encuesta);
			
			$('#contTbAulas').html(null);
            $('.empty_state_img').css('display', 'block');
            
            $idSedeGlobal = null;
        	stopLoadingButton('btnMSEEFQM');
			modal('modalSelectEncuEFQM');
			componentHandler.upgradeAllRegistered();
		});
	});
}

var $_idAulaGlobal = null;
function verChecksAula(btn) {
	var idAula = btn.closest('tr').find('.claseIdentifAula').data('id_aula');
	if(!idAula) {
		return;
	}
	$_idAulaGlobal = idAula;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getEstudiantesChecks',
			data : { idAula : idAula },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#contTbEstu').html(data.estudiantes);
	            $('#tbEstudiantes').bootstrapTable({ });
	            componentHandler.upgradeAllRegistered();
	            modal('modalEstuChecks');
			} catch(err) {
				location.reload();
			}
		});
	});
}

var $_idAreaGlobal = null;
function verPersonalEncu(btn) {
	var idArea = btn.closest('tr').find('.claseIdentifArea').data('id_area');
	var idSede = $idSedeGlobal;
	if(!idArea || !$idSedeGlobal || !$_idEncuestaGlobal) {
		return;
	}
	$_idAreaGlobal = idArea;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getPersonalEncuestado',
			data : { idArea : idArea,
				     idSede : idSede,
				     idEncu : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#contTbPers').html(data.personal);
	            $('#tbPersoEncu').bootstrapTable({ });
	            modal('modalPersonalEncu');
			} catch(err) {
				location.reload();
			}
		});
	});
}

function refreshPersonal() {
	if(!$_idAreaGlobal || !$idSedeGlobal || !$_idEncuestaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getPersonalEncuestado',
			data : { idArea : $_idAreaGlobal,
				     idSede : $idSedeGlobal,
				     idEncu : $_idEncuestaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#contTbPers').html(data.personal);
	            $('#tbPersoEncu').bootstrapTable({ });
			} catch(err) {
				location.reload();
			}
		});
	});
}

/********************* ENC. FIFICAS *************************/
function initEncFisicasEFQM() {
	$('#tbEstudiantes').bootstrapTable({ });
}

function refreshEstudiantes() {
	if(!$_idAulaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/getEstudiantesChecks',
			data : { idAula : $_idAulaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$('#contTbEstu').html(data.estudiantes);
	            $('#tbEstudiantes').bootstrapTable({ });
	            componentHandler.upgradeAllRegistered();
			} catch(err) {
				location.reload();
			}
		});
	});
}

function cambiarEntregaEncuesta(chk) {
	var idEstu  = chk.closest('tr').find('.claseId').data('id_estu');
	var checked = chk.is(":checked");
	if(!$_idAulaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/marcarEncuestaEntregada',
			data : { idEstu : idEstu,
				     idAula : $_idAulaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				var idCheck  = chk.attr('id');
				var chekado  = checked == true ? 'checked' : null;
				var indexRow = chk.closest('tr').data('index');
				var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
						   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="cambiarEntregaEncuesta($(this));">'+
						       '    <span class="mdl-checkbox__label"></span>'+
							   '</label>';
				$('#tbEstudiantes').bootstrapTable('updateCell', {
					rowIndex   : indexRow,
					fieldName  : 'checkbox_1',
					fieldValue : newCheck
				});
				var disabled = 'disabled';
				var onChange = 'onclick="cambiarRecibidaEncuesta($(this));"';
				if(data.result == 'checked') {
					disabled = null;
				} else {
					disabled = 'disabled';
					onChange = null;
				}
				var chkBoxRecibido = chk.closest('tr').find('.claseRecibido').find('.mdl-checkbox__input').attr('id');
				var newCheck = '<label for="'+chkBoxRecibido+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
						   	   '    <input type="checkbox" class="mdl-checkbox__input" '+disabled+' id="'+chkBoxRecibido+'" '+onChange+' >'+
						       '    <span class="mdl-checkbox__label"></span>'+
							   '</label>';
				$('#tbEstudiantes').bootstrapTable('updateCell', {
					rowIndex   : indexRow,
					fieldName  : 'checkbox_2',
					fieldValue : newCheck
				});
				componentHandler.upgradeAllRegistered();
			} catch(err) {
			    msj('error', err);
			    console.log(err);
			}
		});
	});
}

function cambiarRecibidaEncuesta(chk) {
	var idEstu  = chk.closest('tr').find('.claseId').data('id_estu');
	var checked = chk.is(":checked");
	if(!$_idAulaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_avance_efqm/marcarEncuestaRecibida',
			data : { idEstu : idEstu,
				     idAula : $_idAulaGlobal },
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				var idCheck  = chk.attr('id');
				var chekado  = checked == true ? 'checked' : null;
				var indexRow = chk.closest('tr').data('index');
				var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
						   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="cambiarRecibidaEncuesta($(this));">'+
						       '    <span class="mdl-checkbox__label"></span>'+
							   '</label>';
				$('#tbEstudiantes').bootstrapTable('updateCell', {
					rowIndex   : indexRow,
					fieldName  : 'checkbox_2',
					fieldValue : newCheck
				});
				var disabled = null;
				var onChange = null;
				var entregado = data.resultEntregado;
				if(data.result == 'checked') {
					disabled = 'disabled';
					onChange = null;
				} else {
					disabled = null;
					onChange = 'onclick="cambiarEntregaEncuesta($(this));"';
				}
				var chkBoxEntregado = chk.closest('tr').find('.claseEntregado').find('.mdl-checkbox__input').attr('id');
				var newCheck = '<label for="'+chkBoxEntregado+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
						   	   '    <input type="checkbox" '+entregado+' class="mdl-checkbox__input" '+disabled+' id="'+chkBoxEntregado+'" '+onChange+' >'+
						       '    <span class="mdl-checkbox__label"></span>'+
							   '</label>';
				$('#tbEstudiantes').bootstrapTable('updateCell', {
					rowIndex   : indexRow,
					fieldName  : 'checkbox_1',
					fieldValue : newCheck
				});
				componentHandler.upgradeAllRegistered();
			} catch(err) {
				msj('error', err);
			}
		});
	});
}
