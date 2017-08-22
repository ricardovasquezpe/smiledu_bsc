function init(){
	initButtonLoad('botonGC','botonEC','botonEL');
}

function guardarConcepto() {
	addLoadingButton('botonGC');
	var desc      = $('#desc').val();
	desc          = desc.trim();
	var monto     = $('#monto').val();
	var selectMov = $('#selectMov option:selected').val();
	var selectTipo = $('#selectTipo option:selected').val();
	var selectPadre = $('#selectPadre option:selected').val();
	if(selectTipo.length == 0){
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'Seleccione Tipo de Concepto');
	}
	if(selectMov.length == 0){	
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'Seleccione Tipo de Movimiento');
	}
	if(desc.trim( ) == '' || desc.length == 0 || /^\s+$/.test(desc)){
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'Ingrese una Descripcion');
	}
	
	if(monto.trim() == '' || monto.length == 0 || /^\s+$/.test(monto)){
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'Ingrese una monto');
	}
	if(monto >= 1000000){
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'El monto debe ser menor que 1000000');
	}
	if(monto < 0){
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'El monto debe ser un n&uacute;mero positivo');
	}
	if(monto == 0){
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'El monto no puede ser 0');
	}
	if( isNaN(monto) ) {
		stopLoadingButton('botonGC');
		return mostrarNotificacion('warning', 'Solo n&uacute;meros en monto');
	}
	
	$.ajax({
		data  : {desc        : desc,
				 monto       : monto,
				 selectMov   : selectMov,
				 selectTipo  : selectTipo,
				 selectPadre : selectPadre
				 },
		url   : 'c_mantenimiento/guardarConcept',
		type  : 'POST',
		async : true
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 1) {
			mostrarNotificacion('warning', data.msj);
			stopLoadingButton('botonGC');
		} else {
			mostrarNotificacion('succes', data.msj);
			$('#tableConcept').html(data.tableConceptos);
			$('#tb_concepto').bootstrapTable({});
			initSearchTable();
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_concepto');
			setearCombo('selectTipo', null);
			$('#comboPadre').remove();
			$('#comboMovimiento').remove();
			$('#inputDescripccion').remove();
			$('#inputMonto').remove();
			abrirCerrarModal('modalConfirmar');
			stopLoadingButton('botonGC');
		}
		stopLoadingButton('botonGC');
    });
}

function actualizarConcepto() {
	addLoadingButton('botonEC');
	var desc = $('#descEdit').val();
	var monto = $('#montoEdit').val();
	var selectMov = $('#selectMovEdit option:selected').val();

	var selectTipo = $('#selectTipoEdit option:selected').val();

	var selectPadre = $('#selectPadreEdit option:selected').val();
	if(selectTipo.length == 0){
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'Seleccione un tipo');
	}
	if(selectMov.length == 0){
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'Seleccione un tipo');
	}
	if(desc.trim() == '' || desc.length == 0 || /^\s+$/.test(desc)){
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'Ingrese una descripcion');
	}
	
	if(monto.trim() == '' || monto.length == 0 || /^\s+$/.test(monto)){
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'Ingrese una monto');
	}
	
	if(monto <= 0){
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'Debe ser un n&uacute;mero positivo');
	}
	if(monto >= 1000000){
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'El monto debe ser menor que 1000000');
	}
	if( isNaN(monto) ) {
		stopLoadingButton('botonEC');
		return mostrarNotificacion('warning', 'Debe ser un n%uacute;mero');
	}
	$.ajax({
		data : {desc        : desc,
				monto       : monto,
				selectMov   : selectMov,
				idConcepto  : idConcepto,
				selectTipo  : selectTipo,
				selectPadre : selectPadre
				},
		url   : 'c_mantenimiento/updateConcept',
		type  : 'POST',
		async : true
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 1) {
			stopLoadingButton('botonEC');
			mostrarNotificacion('warning', data.msj);
		} else {
			mostrarNotificacion('succes', data.msj);
			$('#tableConcept').html(data.tableConceptos);
			$('#tb_concepto').bootstrapTable({});
			initSearchTable();
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_concepto');
			abrirCerrarModal('modalEditar');
			stopLoadingButton('botonEC');
		}
		stopLoadingButton('botonEC');
    });
}

function openModalRegistrarConcepto() {
	setearCombo('selectTipo', porDefecto);
	mostrarFormulario();
	abrirCerrarModal('modalConfirmar');
}

var id_concepto=null;

function openModaleliminarConceptos(id) {
	id_concepto= id;
	abrirCerrarModal('modalEliminar');
}

var idConcepto;

function openModaleditarConceptos(id) {
	idConcepto=id;
	$.ajax({
		data  : {id : id},
		url   : 'c_mantenimiento/mostrarDetalle',
		type  : 'POST',
		async : false
	}).done(function(data) {
		data = JSON.parse(data);
		$('#comboTipoEdit').remove();
		$('#comboPadreEdit').remove();
		$('#comboMovimientoEdit').remove();
		$('#inputDescripccionEdit').remove();
		$('#inputMontoEdit').remove();
		$('#formularioEditar').append(data.optTipo);
		$("#selectTipoEdit").selectpicker('render');
		$('#formularioEditar').append(data.optPadre);
		$("#selectPadreEdit").selectpicker('render');
		$('#formularioEditar').append(data.optMov);
		$("#selectMovEdit").selectpicker('render');
		$('#formularioEditar').append(data.descripcion);
		setearInput('descEdit', data.desc_concepto);
		$('#formularioEditar').append(data.monto);
		setearInput('montoEdit', data.monto_referencia);
		componentHandler.upgradeAllRegistered();
		abrirCerrarModal('modalEditar');
		
	});
}

function estadoCambiar(id){
	id_concepto = id;
	$.ajax({
		data  : {id_concepto : id_concepto},
		url   : 'c_mantenimiento/cambiarEstado',
		type  : 'POST',
		async : true
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 1) {
			mostrarNotificacion('warning', data.msj);
		} else {
			mostrarNotificacion('succes', data.msj);
//			$('#tableConcept').html(data.tableConceptos);
			$('#tb_concepto').bootstrapTable({});
			initSearchTable();
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_concepto');
		}
	});
}

function eliminarConceptos(){
	addLoadingButton('botonEL');
	$.ajax({
			data  : {id_concepto : id_concepto},
			url   : 'c_mantenimiento/deleteConcept',
			type  : 'POST',
			async : true
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.error == 1) {
					mostrarNotificacion('warning', data.msj);
					stopLoadingButton('botonEL');
				} else {
					$('#tableConcept').html(data.tableConceptos);
					$('#tb_concepto').bootstrapTable({});
					initSearchTable();
					componentHandler.upgradeAllRegistered();
					tableEventsUpgradeMdlComponentsMDL('tb_concepto');
					stopLoadingButton('botonEL');
					mostrarNotificacion('succes', data.msj);
					abrirCerrarModal('modalEliminar');	
				}					
			});
}

function mostrarFormulario() {
	var selectTipo = $('#selectTipo option:selected').val();
	if(selectTipo.length == 0){
		return mostrarNotificacion('warning', 'Seleccione Tipo de Concepto');
	}
//	if(selectTipo == tipoBloq) {
//		//abrirCerrarModal('modalSubirPaquete');
//		$('#selectTipo').css('display','none');
//		$('#selectTipo').attr('value', porDefecto);
//		$('#selectTipo').selectpicker('render')
//		return;
//	}
	$.ajax({
		data  : {selectTipo : selectTipo},
		url   : 'c_mantenimiento/changeFormulario',
		type  : 'POST',
		async : false
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				mostrarNotificacion('warning', data.msj);
			} else {
				$('#selectTipo').css('display','block');
				$('#comboPadre').remove();
				$('#comboMovimiento').remove();
				$('#inputDescripccion').remove();
				$('#inputMonto').remove();
				$("#selectTipo").selectpicker('render');
				$('#formularioRegistro').append(data.optPadre);
				$("#selectPadre").selectpicker('render');
				$('#formularioRegistro').append(data.optMov);
				$("#selectMov").selectpicker('render');
				$('#formularioRegistro').append(data.descripcion);
				$('#formularioRegistro').append(data.monto);
				componentHandler.upgradeAllRegistered();
			}
		});
}

function cambiarTipo() {
	var selectTipo = $('#selectTipoEdit option:selected').val();
	if(selectTipo.length == 0){
		return mostrarNotificacion('warning', 'Seleccione Tipo de Concepto');
	}
	$.ajax({
		data  : {selectTipo : selectTipo,
			idConcepto : idConcepto},
		url   : 'c_mantenimiento/changeCombo',
		type  : 'POST',
		async : true
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				mostrarNotificacion('warning', data.msj);
			} else {
				$('#selectPadreEdit').remove();
				$('#comboPadreEdit').html(data.optPadre);
				$("#selectPadreEdit").selectpicker('render');
				componentHandler.upgradeAllRegistered();
			}
		});
}

function openModaleditarTipo(id){
	idConcepto=id;
	$.ajax({
			data  : {id : idConcepto},
			url   : 'c_mantenimiento/mostrarTipo',
			type  : 'POST',
			async : false
	}).done(function(data) {
		data = JSON.parse(data);
		$('#comboTipoEdit').remove();
		$('#comboPadreEdit').remove();
		$('#comboMovimientoEdit').remove();
		$('#inputDescripccionEdit').remove();
		$('#inputMontoEdit').remove();
		$('#formularioEditarTipo').append(data.optTipo);
		$("#selectTipoEdit").selectpicker('render');
		$('#formularioEditarTipo').append(data.optPadre);
		$("#selectPadreEdit").selectpicker('render');
		$('#formularioEditarTipo').append(data.optMov);
		$("#selectMovEdit").selectpicker('render');
		$('#formularioEditarTipo').append(data.descripcion);
		setearInput('descEdit', data.desc_concepto);
		$('#formularioEditarTipo').append(data.monto);
		setearInput('montoEdit', data.monto_referencia);
		componentHandler.upgradeAllRegistered();
		abrirCerrarModal('modalEditarTipo');
	});
}

function actualizarConceptoTipo() {
    var selectTipo = $('#selectTipoEdit option:selected').val();
    var selectPadre = $('#selectPadreEdit option:selected').val();
	if(selectTipo.length == 0){
		return mostrarNotificacion('warning', 'Seleccione un tipo');
	}
	$.ajax({
		data : {
				selectTipo  : selectTipo,
				selectPadre : selectPadre,
				idConcepto  : idConcepto
				},
		url   : 'c_mantenimiento/updateConceptTipo',
		type  : 'POST',
		async : false
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 1) {
			mostrarNotificacion('warning', data.msj);
		} else {
			mostrarNotificacion('succes', data.msj);
			$('#tableConcept').html(data.tableConceptos);
			$('#tb_concepto').bootstrapTable({});
			initSearchTable();
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_concepto');
			abrirCerrarModal('modalEditarTipo');
		}
	});	
}