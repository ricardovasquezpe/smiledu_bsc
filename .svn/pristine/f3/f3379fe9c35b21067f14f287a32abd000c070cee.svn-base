function initMigracion() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tbMigracion').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	initButtonLoad('btnM','btnMG');
}

function migrar() {
	addLoadingButton('btnM');
	Pace.restart();
	Pace.track(function() {
		var tipo = $('#cmbTipoMigraFin option:selected').val();
		if(tipo == null || tipo == "") {
			stopLoadingButton('btnM');
			return;
		}
		$.ajax({
			url: "c_migracion/migrarDatos",
			data : {tipo : tipo},
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success', data.msj, null);
				setCombo('cmbGrupo',data.comboGrupo, 'Grupo');
				$('select[name=cmbGrupo]').val(data.cod_migracion);
				$('#cmbGrupo').selectpicker('refresh');
				
				$('select[name=cmbTipoMigraFin]').val(tipo);
				$('#cmbTipoMigraFin').selectpicker('refresh');
				
				$('#contMigracion').html(data.tbMigrar);
				$('#tbMigracion').bootstrapTable({ });
				initSearchTableNew();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				modal('modalConfirmMigrar');
				stopLoadingButton('btnM');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
				stopLoadingButton('btnM');
			}
		});
	});
}

function verGruposByTipo() {
	addLoadingButton('btnMG');
	var tipo = $('#cmbTipoMigra option:selected').val();
	$.ajax({
		url: "c_migracion/getGruposByTipo",
		data : {tipo : tipo},
        async : true,
        type: 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('cmbGrupo',data.comboGrupo, 'Grupo');
			$('#contMigracion').html(null);
			$('#tbMigracion').bootstrapTable({ });
			initSearchTableNew();
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			stopLoadingButton('btnMG');
		} else if(data.error == 1) {
			stopLoadingButton('btnMG');
			msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
		}
	});
}

function verHistorialByGrupo() {
	addLoadingButton('btnMG');
	Pace.restart();
	Pace.track(function() {
		var tipo = $('#cmbTipoMigra option:selected').val();
		if(tipo == null || tipo == "") {
			return;
		}
		var grupo = $('#cmbGrupo option:selected').val();
		$.ajax({
			url: "c_migracion/getHistorialByGrupo",
			data : {grupo : grupo,
				    tipo  : tipo},
	        async : true,
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contMigracion').html(data.tbMigrar);
				$('#tbMigracion').bootstrapTable({ });
				initSearchTableNew();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				modal('modalTablaMigracion');
				stopLoadingButton('btnMG');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
				stopLoadingButton('btnMG');
			}
		});
	});
}

function getPersonal() {
	idPersGlobal    = null;
	idPeriodoGlobal = null;
	indexRowGlobal  = null;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_migracion/getPersonalSCIRERH",
	        async : true,
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contMigracion').html(data.tbPersonal);
				$('#titleTb').html(data.titlePersonal);
				$('#tbPersonal').bootstrapTable({ });
				initSearchTable();
				componentHandler.upgradeAllRegistered();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				setCombo2('cmbAreaGeneral', data.areasGenerales);
				setCombo2('cmbAreaEspec', data.areasEspecificas);
				setCombo2('cmbCargo', data.cmbCargo);
				setCombo2('cmbJornLab', data.cmbJornLab);
				setCombo2('cmbSedeCtrl', data.cmbSedesCtrl);
				setCombo2('cmbNivelCtrl', data.cmbNivelCtrl);
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				    $('.pickerButn').selectpicker('mobile');
				} else {
					$('.pickerButn').selectpicker();
				}
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
			}
		});
	});
}

var idPersGlobal    = null;
var idPeriodoGlobal = null;
var indexRowGlobal  = null;
function abrirEditar(btn) {
	$('#correo_pers').val(null);
	$('#correo_inst').val(null);
	$('#correo_admin').val(null);
	
	$('#modalEditDatosTitle').html('Editar datos de:&nbsp; <b>'+btn.data('nombres')+'</b>');
	idPersGlobal    = btn.data('id_pers');
	idPeriodoGlobal = btn.data('id_periodo');
	indexRowGlobal  = btn.closest('tr').data('index');
	if($.trim(btn.data('correo_pers')).length > 0) {
		$('#correo_pers').val(btn.data('correo_pers'));
		$('#correo_pers').parent().addClass('is-dirty');
	}
	if($.trim(btn.data('correo_inst')).length > 0) {
		$('#correo_inst').val(btn.data('correo_inst'));
		$('#correo_inst').parent().addClass('is-dirty');
	}
	if($.trim(btn.data('correo_adm')).length > 0) {
		$('#correo_admin').val(btn.data('correo_adm'));
		$('#correo_admin').parent().addClass('is-dirty');
	}
	setValueCombo('cmbAreaGeneral', btn.data('id_area_gene'));
    //
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : "c_migracion/consAreasEspecifCargos",
			data : { idAreaGeneral : btn.data('id_area_gene') },
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				setCombo('cmbAreaEspec', data.areaEspOpt, 'Área Específica');
				setCombo('cmbCargo', data.cargoOpt, 'Cargo');
				
				setValueCombo('cmbAreaEspec', btn.data('id_area_espe'));
				setValueCombo('cmbCargo', btn.data('id_cargo'));
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
			}
		});
	});
	setValueCombo('cmbJornLab', btn.data('id_jorn_lab'));
	setValueCombo('cmbSedeCtrl', btn.data('id_sede_ctrl'));
	setValueCombo('cmbNivelCtrl', btn.data('id_nivel_ctrl'));
	modal('modalEditDatos');
}

function guardarDatos() {
	Pace.restart();
	Pace.track(function() {
		if(idPersGlobal == null || idPeriodoGlobal == null) {
			return;
		}
		$.ajax({
			url  : "c_migracion/editarDatosSCIRERH",
			data : { idPersGlobal    : idPersGlobal,
				     idPeriodoGlobal : idPeriodoGlobal,
				     correoPers      : $('#correo_pers').val(),
				     correo_inst     : $('#correo_inst').val(),
				     correo_admin    : $('#correo_admin').val(),
				     areaGeneral     : $('#cmbAreaGeneral option:selected').val(),
				     areaEspecif     : $('#cmbAreaEspec option:selected').val(),
				     cargo           : $('#cmbCargo option:selected').val(),
				     jornLabo        : $('#cmbJornLab option:selected').val(),
				     sedeCtrl        : $('#cmbSedeCtrl option:selected').val(),
				     nivelCtrl       : $('#cmbNivelCtrl option:selected').val()
				   },
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#tbPersonal').bootstrapTable('updateRow', {
	                index: indexRowGlobal,
	                row: {
	                	area_general    : data.area_general,
	                	area_especifica : data.area_especifica,
	                	cargo           : data.cargo,
	                	jornada_laboral : data.jornada_laboral,
	                	sede_control    : data.sede_control,
	                	nivel_control   : data.nivel_control,
	                	correo_pers     : data.correo_pers,
	                	correo_inst     : data.correo_inst,
	                	correo_admin    : data.correo_adm,
	                	button          : data.button
	                }
	            });
				if(data.id_area_general == '0') {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_area_general').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_area_general').removeClass('danger');
				}
				if(data.id_area_especifica == '0000000000') {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_area_especifica').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_area_especifica').removeClass('danger');
				}
				if(data.id_cargo_schoowl == '00') {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_cargo').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_cargo').removeClass('danger');
				}
				if(data.id_jornada_laboral == '000000000000000') {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_jornada').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_jornada').removeClass('danger');
				}
				if(data.id_sede_control == '00000000') {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_sede').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_sede').removeClass('danger');
				}
				if(data.id_nivel_control == '00000000') {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_nivel').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_nivel').removeClass('danger');
				}
				if(data.correo_pers.length == 0) {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_correo_pers').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_correo_pers').removeClass('danger');
				}
				if(data.correo_inst.length == 0) {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_correo_inst').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_correo_inst').removeClass('danger');
				}
				if(data.correo_adm.length == 0) {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_correo_adm').addClass('danger');	
				} else {
					$('*[data-index="'+indexRowGlobal+'"]').find('.celda_correo_adm').removeClass('danger');
				}
				msj('success', data.msj, null);
				modal('modalEditDatos');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
			}
		});
	});
}

function getPersonalRecibos() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : "c_migracion/getPersonalRecibos",
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				msj('success', data.msj, null);
				$('#contMigracion').html(data.tbPersonal);
				$('#titleTb').html(data.titlePersonal);
				$('#tbPersonal').bootstrapTable({ });
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
			}
		});
	});
}

function getAreasEspecifCargos() {
	Pace.restart();
	Pace.track(function() {
		var idAreaGeneral = $('#cmbAreaGeneral option:selected').val();
		$.ajax({
			url  : "c_migracion/consAreasEspecifCargos",
			data : { idAreaGeneral : idAreaGeneral },
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				setCombo('cmbAreaEspec', data.areaEspOpt, 'Área Específica');
				setCombo('cmbCargo', data.cargoOpt, 'Cargo');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, null);
			}
		});
	});
}