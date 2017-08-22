function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initButtonCalendarDays('fechaNacPostulante');
	initMaskInputs('fechaNacPostulante');
	initButtonLoad('btnGuardarDetalleContacto','botonEC');
	initLimitInputs('observacion');	
	$(":input").inputmask();

}

function getSedesByNivel(nivel,sede){
	var valorNivel = $('#'+nivel+' option:selected').val();
	if(valorNivel != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_contactos/getSedesByNivel',
			data    : {valorNivel : valorNivel},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$('#'+sede).attr("disabled", false);
			setCombo(sede, data.comboSedes,"Sede de inter&eacute;s");
			$('.selectButton').selectpicker('refresh');
			if(valorNivel.length == 0){
				$('#'+sede).attr("disabled", true);
			}
		});
	}else{
		setCombo(sede, null,"Sede de inter&eacute;s");
		$('.selectButton').selectpicker('refresh');
		$('#'+sede).attr("disabled", true);
	}
}

function onChangeCampo(campo, enc, id){
	var valor = $("#"+id).val();
	if(id.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_contactos/onChangeCampo',
			data    : {campo : campo,
			           enc   : enc,
			           valor : valor},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				msj('success', data.msj, null);
			} else if (data.error == 1){
				setearInput(id, $("#"+id).attr("val-previo"));
				msj('success', data.msj, null);
			}
		});
	}
}

var cons_contacto_detalle = null;
function abrirModalDetallePariente(datacontacto, opc){ //(OPC)1 = EDITAR, 2 = DETALLE
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_contactos/detalleContacto',
		data    : {contacto : datacontacto},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		setearInput("nombreContactoDetalle", data.nombres);
		setearInput("apPaternoContactoDetalle", data.apPaterno);
		setearInput("apMaternoContactoDetalle", data.apMaterno);
		setearInput("correoContactoDetalle", data.correo);
		setearInput("celularContactoDetalle", data.celular);
		setearInput("fijoContactoDetalle", data.fijo);
		setearInput("nroDocumentoContactoDetalle", data.nroDoc);
		setearInput("referenciaContactoDetalle", data.refer_dom);
		setearCombo("selectParentescoDetalleContacto", data.parentesco);
		setearCombo("selectSexoDetalleContacto", data.sexo);
		setearCombo("selectTipoDocDetalleContacto", data.tipoDoc);
		setearCombo("selectCanalDetalleContacto", data.canal);
		setearCombo("selectOperadorDetalleContacto", data.operador);
		setearCombo("selectDepartamentoDetalleContacto", data.departamento);
		if(data.departamento.length != 0){
			getProvinciaPorDepartamento("selectDepartamentoDetalleContacto", "selectProvinciaDetalleContacto", "selectDistritoDetalleContacto", 2);
			setearCombo("selectProvinciaDetalleContacto", data.provincia);
		}else{
			setCombo("selectProvinciaDetalleContacto", null, "Seleccione Provincia");
			
		}
		if(data.provincia.length != 0){
			getDistritoPorProvincia("selectDepartamentoDetalleContacto", "selectProvinciaDetalleContacto", "selectDistritoDetalleContacto", 3);
			setearCombo("selectDistritoDetalleContacto", data.distrito);
		}else{
			setCombo("selectDistritoDetalleContacto", null, "Seleccione Distrito");
		}
		cons_contacto_detalle = datacontacto;
		
		if(opc == 2){
			disableEnableCombo("selectParentescoDetalleContacto", true);
			disableEnableCombo("selectSexoDetalleContacto", true);
			disableEnableCombo("selectTipoDocDetalleContacto", true);
			disableEnableCombo("selectCanalDetalleContacto", true);
			disableEnableCombo("selectOperadorDetalleContacto", true);
			disableEnableCombo("selectDepartamentoDetalleContacto", true);
			disableEnableCombo("selectProvinciaDetalleContacto", true);
			disableEnableCombo("selectDistritoDetalleContacto", true);
			
			disableEnableInput("nombreContactoDetalle", true);
			disableEnableInput("apPaternoContactoDetalle", true);
			disableEnableInput("apMaternoContactoDetalle", true);
			disableEnableInput("correoContactoDetalle", true);
			disableEnableInput("celularContactoDetalle", true);
			disableEnableInput("fijoContactoDetalle", true);
			//disableEnableInput("nroDocumentoContactoDetalle", true);
			disableEnableInput("referenciaContactoDetalle", true);
			
			$("#btnGuardarDetalleContacto").hide();
			$("#tituloModalDetalleContacto").text("Detalle pariente");
		}else{
			disableEnableCombo("selectParentescoDetalleContacto", false);
			disableEnableCombo("selectSexoDetalleContacto", false);
			disableEnableCombo("selectTipoDocDetalleContacto", false);
			disableEnableCombo("selectCanalDetalleContacto", false);
			disableEnableCombo("selectOperadorDetalleContacto", false);
			disableEnableCombo("selectDepartamentoDetalleContacto", false);
			disableEnableCombo("selectProvinciaDetalleContacto", false);
			disableEnableCombo("selectDistritoDetalleContacto", false);
			
			disableEnableInput("nombreContactoDetalle", false);
			disableEnableInput("apPaternoContactoDetalle", false);
			disableEnableInput("apMaternoContactoDetalle", false);
			disableEnableInput("correoContactoDetalle", false);
			disableEnableInput("celularContactoDetalle", false);
			disableEnableInput("fijoContactoDetalle", false);
			disableEnableInput("nroDocumentoContactoDetalle", true);
			if(data.tipoDoc.length != 0){
				disableEnableInput("nroDocumentoContactoDetalle", false);
			}
			
			disableEnableInput("referenciaContactoDetalle", false);
			
			$("#btnGuardarDetalleContacto").show();
			$("#btnGuardarDetalleContacto").attr("onclick", "editarContacto()");
			$("#tituloModalDetalleContacto").text("Editar pariente");
		}
	
		modal("modalDetalleContacto");
	});
}

function editarContacto(){
	addLoadingButton('btnGuardarDetalleContacto');
	if(cons_contacto_detalle != null){
		var nombres    = $("#nombreContactoDetalle").val();
		var appaterno  = $("#apPaternoContactoDetalle").val();
		var apmaterno  = $("#apMaternoContactoDetalle").val();
		var correo     = $("#correoContactoDetalle").val();
		var celular    = $("#celularContactoDetalle").val();
		var fijo 	   = $("#fijoContactoDetalle").val();
		var nrodoc     = $("#nroDocumentoContactoDetalle").val();
		var parentesco = $("#selectParentescoDetalleContacto").val();
		var sexo 	   = $("#selectSexoDetalleContacto").val();
		var tipodoc    = $("#selectTipoDocDetalleContacto").val();
		var canal      = $("#selectCanalDetalleContacto").val();
		var operador   = $("#selectOperadorDetalleContacto").val();
		var departamento = $("#selectDepartamentoDetalleContacto").val();
		var provincia    = $("#selectProvinciaDetalleContacto").val();
		var distrito     = $("#selectDistritoDetalleContacto").val();
		var referencia   = $("#referenciaContactoDetalle").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_contactos/editarContacto',
			data    : {contacto   : cons_contacto_detalle,
				       nombres    : nombres,
				       appaterno  : appaterno,
				       apmaterno  : apmaterno,
				       correo     : correo,
				       celular    : celular,
				       fijo       : fijo,
				       nrodoc     : nrodoc,
				       parentesco : parentesco,
				       sexo       : sexo,
				       tipodoc    : tipodoc,
				       canal      : canal,
				       operador   : operador,
				       departamento : departamento,
				       provincia    : provincia,
				       distrito     : distrito,
				       referencia   : referencia},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_parientes").html(data.parientes);
				componentHandler.upgradeAllRegistered();
				stopLoadingButton('btnGuardarDetalleContacto');
				modal("modalDetalleContacto");
				msj('success', data.msj, null);
			}else{
				stopLoadingButton('btnGuardarDetalleContacto');
				msj('success', data.msj, null);
			}
		});
	}
}

function habilitarCampo(element1, element2){
	var val1 = $('#'+element1+' option:selected').val();
	setearInput(element2,null);
	if(val1.length != 0){
		$('#'+element2).attr("disabled", false);
		$('.divInput').removeClass('is-disabled');
	} else {
		$('#'+element2).attr("disabled", true);
	}
}

function clickTabMenu(opc){
	$("#li_menu_1").hide();
	if(opc == 1){
		$("#li_menu_1").show();
	}
}

function abrirModalCrearPariente(){
	setCombo("selectProvinciaDetalleContacto", null, "Seleccione Provincia");
	setCombo("selectDistritoDetalleContacto", null, "Seleccione Distrito");
	
	setearInput("nombreContactoDetalle", null);
	setearInput("apPaternoContactoDetalle", null);
	setearInput("apMaternoContactoDetalle", null);
	setearInput("correoContactoDetalle", null);
	setearInput("celularContactoDetalle", null);
	setearInput("fijoContactoDetalle", null);
	setearInput("nroDocumentoContactoDetalle", null);
	setearInput("referenciaContactoDetalle", null);
	setearCombo("selectParentescoDetalleContacto", null);
	setearCombo("selectSexoDetalleContacto", null);
	setearCombo("selectTipoDocDetalleContacto", null);
	setearCombo("selectCanalDetalleContacto", null);
	setearCombo("selectOperadorDetalleContacto", null);
	setearCombo("selectDepartamentoDetalleContacto", null);
	setearCombo("selectProvinciaDetalleContacto", null);
	setearCombo("selectDistritoDetalleContacto", null);
	
	disableEnableCombo("selectParentescoDetalleContacto", false);
	disableEnableCombo("selectSexoDetalleContacto", false);
	disableEnableCombo("selectTipoDocDetalleContacto", false);
	disableEnableCombo("selectCanalDetalleContacto", false);
	disableEnableCombo("selectOperadorDetalleContacto", false);
	disableEnableCombo("selectDepartamentoDetalleContacto", false);
	disableEnableCombo("selectProvinciaDetalleContacto", false);
	disableEnableCombo("selectDistritoDetalleContacto", false);
	
	disableEnableInput("nombreContactoDetalle", false);
	disableEnableInput("apPaternoContactoDetalle", false);
	disableEnableInput("apMaternoContactoDetalle", false);
	disableEnableInput("correoContactoDetalle", false);
	disableEnableInput("celularContactoDetalle", false);
	disableEnableInput("fijoContactoDetalle", false);
	disableEnableInput("nroDocumentoContactoDetalle", true);
	
	$("#btnGuardarDetalleContacto").show();
	$("#btnGuardarDetalleContacto").attr("onclick", "crearPartiente()");
	$("#tituloModalDetalleContacto").text("Nuevo pariente");
	modal("modalDetalleContacto");
}

function crearPartiente(){
	var nombres    = $("#nombreContactoDetalle").val();
	var appaterno  = $("#apPaternoContactoDetalle").val();
	var apmaterno  = $("#apMaternoContactoDetalle").val();
	var correo     = $("#correoContactoDetalle").val();
	var celular    = $("#celularContactoDetalle").val();
	var fijo 	   = $("#fijoContactoDetalle").val();
	var nrodoc     = $("#nroDocumentoContactoDetalle").val();
	var parentesco = $("#selectParentescoDetalleContacto").val();
	var sexo 	   = $("#selectSexoDetalleContacto").val();
	var tipodoc    = $("#selectTipoDocDetalleContacto").val();
	var canal      = $("#selectCanalDetalleContacto").val();
	var operador   = $("#selectOperadorDetalleContacto").val();
	var departamento = $("#selectDepartamentoDetalleContacto").val();
	var provincia    = $("#selectProvinciaDetalleContacto").val();
	var distrito     = $("#selectDistritoDetalleContacto").val();
	var referencia   = $("#referenciaContactoDetalle").val();
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_contactos/crearContacto',
		data    : {nombres    : nombres,
			       appaterno  : appaterno,
			       apmaterno  : apmaterno,
			       correo     : correo,
			       celular    : celular,
			       fijo       : fijo,
			       nrodoc     : nrodoc,
			       parentesco : parentesco,
			       sexo       : sexo,
			       tipodoc    : tipodoc,
			       canal      : canal,
			       operador   : operador,
			       departamento : departamento,
			       provincia    : provincia,
			       distrito     : distrito,
			       referencia   : referencia},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#cont_parientes").html(data.parientes);
			componentHandler.upgradeAllRegistered();
			modal("modalDetalleContacto");
			msj('success', data.msj, null);
		}else{
			msj('success', data.msj, null);
		}
	});
}

function changeMaxlength(tipoDoc,nroDoc){
	var valorTipo = $('#'+tipoDoc+' option:selected').val();
	if(valorTipo == 1){
		$("#"+nroDoc).attr('maxlength','12');
	}else if (valorTipo == 2){
		$("#"+nroDoc).attr('maxlength','8');
	}
}

function abrirModadalConfirmDeleteContacto(dataContacto){
	cons_contacto_detalle = dataContacto;
	modal("modalConfirmeDeleteContacto");
}

function eliminarContacto(){
	addLoadingButton('botonEC');
	if(cons_contacto_detalle != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_contactos/eliminarContacto',
			data    : {contacto : cons_contacto_detalle},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_parientes").html(data.parientes);
				componentHandler.upgradeAllRegistered();
				modal("modalConfirmeDeleteContacto");
				msj('success', data.msj, null);
			}else{
				msj('success', data.msj, null);
			}
			stopLoadingButton('botonEC');
		});
	}
}

function getProvinciaPorDepartamento(idcomboDep, idComboProv, idComboDist, tipo){
	var valorDep = $("#"+idcomboDep).val();
	if(valorDep != null && valorDep.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_contactos/getUbigeoByTipo',
			data    : {idubigeo : valorDep,
					   tipo     : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboProv, data.comboUbigeo, "Seleccione Provincia");
			setearCombo(idComboDist, null);
		});
	}else{
		setearCombo(idComboProv, null);
		setearCombo(idComboDist, null);
	}
}

function getDistritoPorProvincia(idcomboDep, idComboProv, idComboDist, tipo){
	var valorDep  = $("#"+idcomboDep).val();
	var valorProv = $("#"+idComboProv).val();
	if(valorProv != null && valorProv.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_contactos/getUbigeoByTipo',
			data    : {idubigeo  : valorDep,
				       idubigeo1 : valorProv,
					   tipo      : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboDist, data.comboUbigeo, "Seleccione Distrito");
		});
	}else{
		setearCombo(idComboDist, null);
	}
}

function verRazonInasistencia(idevento){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_contactos/verRazonInasistencia',
		data    : {idevento : idevento},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#razonInasistencia").val(data.razon);
	});
	modal("modalRazonInasistencia");
}


