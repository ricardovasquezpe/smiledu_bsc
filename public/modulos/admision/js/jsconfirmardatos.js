var cons_contacto_par = null;
var cons_contacto_pos = null;
var cons_contacto_pos_psico = null;
function init(contacto){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	cons_contacto_par = contacto;
	$(":input").inputmask();
	initButtonCalendarDays('fechaNacPostulante');
	initButtonCalendarDays('fechaNacPariente');
	fechaNacPostulante
}

function abrirModalBuscar(){
	modal("modalFiltrar");
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

function changeMaxlength(tipoDoc, nroDoc){
	var valorTipo = $('#'+tipoDoc+' option:selected').val();
	if(valorTipo == 1){
		$("#"+nroDoc).attr('maxlength','12');
	}else if (valorTipo == 2){
		$("#"+nroDoc).attr('maxlength','8');
	}
}

function getProvinciaPorDepartamento(idcomboDep, idComboProv, idComboDist,referencia, tipo){
	var valorDep = $("#"+idcomboDep).val();
	if(valorDep != null && valorDep.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/getUbigeoByTipo',
			data    : {idubigeo : valorDep,
					   tipo     : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboProv, data.comboUbigeo, "Provincia actual");
			setearCombo(idComboDist, null);
			setearInput(referencia, null);
			$('#'+idComboProv).attr("disabled", false);
			$('#'+idComboDist).attr("disabled", true);
			$('#'+referencia).attr("disabled", true);
			$('.selectButton').selectpicker('refresh');
		});
	}else{
		setearCombo(idComboProv, null);
		setearCombo(idComboDist, null);
		setearInput(referencia, null);
		$('#'+idComboProv).attr("disabled", true);
		$('#'+idComboDist).attr("disabled", true);
		$('#'+referencia).attr("disabled", true);
		$('.selectButton').selectpicker('refresh');
	}
}

function getDistritoPorProvincia(idcomboDep, idComboProv, idComboDist, referencia, tipo){
	var valorDep  = $("#"+idcomboDep).val();
	var valorProv = $("#"+idComboProv).val();
	if(valorProv != null && valorProv.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/getUbigeoByTipo',
			data    : {idubigeo  : valorDep,
				       idubigeo1 : valorProv,
					   tipo      : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$('#'+idComboDist).attr("disabled", false);
			$('#'+referencia).attr("disabled", true);
			$('.selectButton').selectpicker('refresh');
			setCombo(idComboDist, data.comboUbigeo, "Distrito actual");
			setearInput(referencia, null);
		});
	}else{
		setearCombo(idComboDist, null);
		setearInput(referencia, null);
		$('#'+referencia).attr("disabled", true);
		$('#'+idComboDist).attr("disabled", true);
		$('.selectButton').selectpicker('refresh');
	}
}

function cambioCampoFamiliar(element){
	val = $(element).val();
	if(val.length != 0){
		$('#btnGuardarFamiliar').prop("disabled", false);
		$(element).closest(".mdl-input-group").removeClass("is-invalid");
	}else{
		$(element).closest(".mdl-input-group").addClass("is-invalid");
	}
}

function guardarDatosFamiliar(){
	Pace.restart();
	Pace.track(function() {
		parentesco      = $("#selectParentesco").val();
		apellidopaterno = $("#apellidoPaternoPariente").val();
		apellidomaterno = $("#apellidoMaternoPariente").val();
		nombres         = $("#nombrePariente").val();
		
		tipodoc         = $("#selectTipoDocumento").val();
		nrodoc          = $("#nroDocumento").val();
		sexo            = $("#selectSexoPariente").val();
		telffijo        = $("#telefonoPariente").val();
		telfcel         = $("#celularPariente").val();
		correo          = $("#correoPariente").val();
		depart          = $("#departamentoFam").val();
		provincia       = $("#provinciaFam").val();
		distrito        = $("#distritoFam").val();
		referencia      = $("#referencia_domicilio").val();
		fecnaci         = $("#fechaNacPariente").val();
		$.ajax({
			data : { contacto        : cons_contacto_par,
				     parentesco      : parentesco,
				     apellidopaterno : apellidopaterno,
				     apellidomaterno : apellidomaterno,
				     nombres         : nombres,
				     tipodoc         : tipodoc,
				     nrodoc          : nrodoc,
				     sexo            : sexo,
				     telffijo        : telffijo,
				     telfcel         : telfcel,
				     correo          : correo,
				     depart          : depart,
				     provincia       : provincia,
				     distrito        : distrito,
				     referencia      : referencia,
				     fecnaci         : fecnaci},
			url  : 'c_confirmar_datos/guardarDatosPariente',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
			    msj("success", data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});
}

function changeFamiliarDatos(contacto, element){
	Pace.restart();
	Pace.track(function() {
		$(".chip-parientes").removeClass("active");
		$(element).addClass("active");
		$.ajax({
			data : { contacto : contacto},
			url  : 'c_confirmar_datos/getDatosFamiliar',
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			cons_contacto_par = data.contacto;
			setearCombo("selectParentesco", data.parentesco);
			declareInvalid("selectParentesco", data.parentesco);
			$("#apellidoPaternoPariente").val(data.apePaterno);
			declareInvalid("apellidoPaternoPariente", data.apePaterno);
			$("#apellidoMaternoPariente").val(data.apeMaterno);
			declareInvalid("apellidoMaternoPariente", data.apeMaterno);
			$("#nombrePariente").val(data.nombres);
			declareInvalid("nombrePariente", data.nombres);
			setearCombo("selectTipoDocumento", data.tipoDoc);
			declareInvalid("selectTipoDocumento", data.tipoDoc);
			habilitarCampo('selectTipoDocumento','nroDocumento');
			$("#nroDocumento").val(data.nroDoc);
			declareInvalid("nroDocumento", data.nroDoc);
			setearCombo("selectSexoPariente", data.sexo);
			declareInvalid("selectSexoPariente", data.sexo);
			$("#telefonoPariente").val(data.telefonoFijo);
			declareInvalid("telefonoPariente", data.telefonoFijo);
			$("#celularPariente").val(data.telefonoCelular);
			declareInvalid("celularPariente", data.telefonoCelular);
			$("#correoPariente").val(data.correo);
			declareInvalid("correoPariente", data.correo);
			$("#fechaNacPariente").val(data.fecNaci);
			declareInvalid("fechaNacPariente", data.fecNaci);
			
			setearCombo("departamentoFam", data.departamento);
			declareInvalid("departamentoFam", data.departamento);
            if(data.departamento != null){
            	getProvinciaPorDepartamento('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 2);
            	setearCombo("provinciaFam", data.provincia);
            	if(data.provincia != null){
            		getDistritoPorProvincia('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 3);
            		setearCombo("distritoFam", data.distrito);
                }
            }
			
			$("#referencia_domicilio").val(data.referencia);
			declareInvalid("referencia_domicilio", data.referencia);
			$('#btnGuardarFamiliar').prop("disabled", true);
			$('#btnGuardarFamiliar').attr("onclick", "guardarDatosFamiliar()");
		});
	});
}

var datosHijos = 0;
function getDatosHijos(){
	$("#btnGuardarFamiliar").attr("onclick", "guardarDatosHijos()");
	$("#btnGuardarFamiliar").attr("id", "btnGuardarPostulante");
	
	$("#btnGuardarPostulantePsico").attr("onclick", "guardarDatosHijos()");
	$("#btnGuardarPostulantePsico").attr("id", "btnGuardarPostulante");
	if(datosHijos == 0){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				url  : 'c_confirmar_datos/getDatosHijos',
				type : 'POST'
			})
			.done(function(data) {
				try {
					data = JSON.parse(data);
					$("#cont_cabe_hijos").html(data.cabeHij);
					$("#cont_cabe_hijos").append('<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Nuevo Pariente"'+
						                            'onclick="agregarPostulante()">'+
						                            '<i class="mdi mdi-add"></i>'+
										  	      '</button>');
					datosHijos = 1;
					cons_contacto_pos = data.contacto;
					$("#apellidoPaternoPostulante").val(data.apePaterno);
					declareInvalid("apellidoPaternoPostulante", data.apePaterno);
					$("#apellidoMaternoPostulante").val(data.apeMaterno);
					declareInvalid("apellidoMaternoPostulante", data.apeMaterno);
					$("#nombrePostulante").val(data.nombres);
					declareInvalid("nombrePostulante", data.nombres);
					$("#fechaNacPostulante").val(data.fecNaci);
					declareInvalid("fechaNacPostulante", data.fecNaci);
					setearCombo("selectTipoDocumentoPostulante", data.tipoDoc);
					declareInvalid("selectTipoDocumentoPostulante", data.tipoDoc);
					habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante');
					$("#nroDocumentoPostulante").val(data.nroDoc);
					declareInvalid("nroDocumentoPostulante", data.nroDoc);
					setearCombo("selectSexoPostulante", data.sexo);
					declareInvalid("selectSexoPostulante", data.sexo);
					setearCombo("selectGradoNivel", data.gradoNivel);
					declareInvalid("selectGradoNivel", data.gradoNivel);
					$('#btnGuardarPostulante').prop("disabled", true);
				} catch(err) {
					location.reload();
				}
			});
		});
	}
}

function changeTabFamiliar(){
	$("#btnGuardarPostulante").attr("onclick", "guardarDatosFamiliar()");
	$("#btnGuardarPostulante").attr("id", "btnGuardarFamiliar");
	
	$("#btnGuardarPostulantePsico").attr("onclick", "guardarDatosFamiliar()");
	$("#btnGuardarPostulantePsico").attr("id", "btnGuardarFamiliar");
}

function cambioCampoPostulante(element){
	val = $(element).val();
	if(val.length != 0){
		$('#btnGuardarPostulante').prop("disabled", false);
		$(element).closest(".mdl-input-group").removeClass("is-invalid");
	}else{
		$(element).closest(".mdl-input-group").addClass("is-invalid");
	}
}

function changeHijosDatos(contacto, element){
	Pace.restart();
	Pace.track(function() {
		$(".chip-hijos").removeClass("active");
		$(element).addClass("active");
		$.ajax({
			data : { contacto : contacto},
			url  : 'c_confirmar_datos/getDatosPostulante',
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			cons_contacto_pos = data.contacto;
			$("#apellidoPaternoPostulante").val(data.apePaterno);
			declareInvalid("apellidoPaternoPostulante", data.apePaterno);
			$("#apellidoMaternoPostulante").val(data.apeMaterno);
			declareInvalid("apellidoMaternoPostulante", data.apeMaterno);
			$("#nombrePostulante").val(data.nombres);
			declareInvalid("nombrePostulante", data.nombres);
			$("#fechaNacPostulante").val(data.fecNaci);
			declareInvalid("fechaNacPostulante", data.fecNaci);
			setearCombo("selectTipoDocumentoPostulante", data.tipoDoc);
			declareInvalid("selectTipoDocumentoPostulante", data.tipoDoc);
			habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante');
			$("#nroDocumentoPostulante").val(data.nroDoc);
			declareInvalid("nroDocumentoPostulante", data.nroDoc);
			setearCombo("selectSexoPostulante", data.sexo);
			declareInvalid("selectSexoPostulante", data.sexo);
			setearCombo("selectGradoNivel", data.gradoNivel);
			declareInvalid("selectGradoNivel", data.gradoNivel);
			$('#btnGuardarPostulante').prop("disabled", true);
		});
	});
}

function guardarDatosHijos(){
	Pace.restart();
	Pace.track(function() {
		apellidopaterno = $("#apellidoPaternoPostulante").val();
		apellidomaterno = $("#apellidoMaternoPostulante").val();
		nombres         = $("#nombrePostulante").val();
		fecnaci         = $("#fechaNacPostulante").val();
		tipodoc         = $("#selectTipoDocumentoPostulante").val();
		numdoc          = $("#nroDocumentoPostulante").val();
		gradnive        = $("#selectGradoNivel").val();
		sexo            = $("#selectSexoPostulante").val();
		$.ajax({
			data : { contacto        : cons_contacto_pos,
				     apellidopaterno : apellidopaterno,
				     apellidomaterno : apellidomaterno,
				     nombres         : nombres,
				     fecnaci         : fecnaci,
				     tipodoc         : tipodoc,
				     numdoc          : numdoc,
				     gradnive        : gradnive,
				     sexo         	 : sexo},
			url  : 'c_confirmar_datos/guardarDatosHijos',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
			    msj("success", data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});
}

function buscarFamilia(){
	busqueda = $("#busquedaFamiia").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { busqueda : busqueda},
			url  : 'c_confirmar_datos/buscarFamilia',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
                $('#selectFamiia').attr('disabled', false);
            	$('#selectFamiia').selectpicker('refresh');
				setCombo("selectFamiia", data.combo, "Familia");
			} catch(err) {
				location.reload();
			}
		});
	});
}

function traeInfoFamilia(){
	familia = $("#selectFamiia").val();
	if(familia.length == 0){
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { familia : familia},
			url  : 'c_confirmar_datos/traeInfoFamilia',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				cons_contacto_par = data.contacto;
				datosHijos = 0;
				datosHijosPsico = 0;
				$("#cont_cabe_familiares").html(data.cabeFam);
				$("#cont_cabe_familiares").append('<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Nuevo Pariente"'+
                                                       'onclick="agregarPariente()">'+
                                                       '<i class="mdi mdi-add"></i>'+
                           				  	      '</button>');
				$(document).ready(function(){
	         	       $('[data-toggle="tooltip"]').tooltip();
	             });
				setearCombo("selectParentesco", data.parentesco);
				declareInvalid("selectParentesco", data.parentesco);
				$("#apellidoPaternoPariente").val(data.apePaterno);
				declareInvalid("apellidoPaternoPariente", data.apePaterno);
				$("#apellidoMaternoPariente").val(data.apeMaterno);
				declareInvalid("apellidoMaternoPariente", data.apeMaterno);
				$("#nombrePariente").val(data.nombres);
				declareInvalid("nombrePariente", data.nombres);
				setearCombo("selectTipoDocumento", data.tipoDoc);
				declareInvalid("selectTipoDocumento", data.tipoDoc);
				habilitarCampo('selectTipoDocumento','nroDocumento');
				$("#nroDocumento").val(data.nroDoc);
				declareInvalid("nroDocumento", data.nroDoc);
				setearCombo("selectSexoPariente", data.sexo);
				declareInvalid("selectSexoPariente", data.sexo);
				$("#telefonoPariente").val(data.telefonoFijo);
				declareInvalid("telefonoPariente", data.telefonoFijo);
				$("#celularPariente").val(data.telefonoCelular);
				declareInvalid("celularPariente", data.telefonoCelular);
				$("#correoPariente").val(data.correo);
				declareInvalid("correoPariente", data.correo);
				$("#fechaNacPariente").val(data.fecNaci);
				declareInvalid("fechaNacPariente", data.fecNaci);
				
				setearCombo("departamentoFam", data.departamento);
				declareInvalid("departamentoFam", data.departamento);
	            if(data.departamento != null){
	            	getProvinciaPorDepartamento('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 2);
	            	setearCombo("provinciaFam", data.provincia);
	            	if(data.provincia != null){
	            		getDistritoPorProvincia('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 3);
	            		setearCombo("distritoFam", data.distrito);
	                }
	            }
				$("#referencia_domicilio").val(data.referencia);
				declareInvalid("referencia_domicilio", data.referencia);
				$('#btnGuardarFamiliar').prop("disabled", true);
				
				$("#btnGuardarPostulante").attr("onclick", "guardarDatosFamiliar()");
				$("#btnGuardarPostulante").attr("id", "btnGuardarFamiliar");
				
				$("#btnGuardarPostulantePsico").attr("onclick", "guardarDatosFamiliar()");
				$("#btnGuardarPostulantePsico").attr("id", "btnGuardarFamiliar");
				
				$(".mdl-tabs__tab").removeClass("is-active");
				$("#barTab1").addClass("is-active");
				$(".mdl-tabs__panel").removeClass("is-active");
				$("#tab1").addClass("is-active");
				
				$("#cont_datos_pariente").css("display", "block");
				$("#tab-editperson-fam").css("display", "none");
				$("#btnGuardarFamiliar").attr("onclick", "guardarDatosFamiliar()");
				$("#btnGuardarFamiliar").attr("data-mfb-label", "Guardar");
				$("#btnGuardarFamiliar").find("i").removeClass("mdi-search").addClass("mdi-save");
			} catch(err) {
				location.reload();
			}
		});
	});
}

function declareInvalid(id, val){
	if(val.length == 0){
		$("#"+id).closest(".mdl-input-group").addClass("is-invalid");
	}else{
		$("#"+id).closest(".mdl-input-group").removeClass("is-invalid");
	}
}

function activeDesactiveSearch(){
	var nameadmin = $("#busquedaFamiia").val();
	if($.trim(nameadmin).length>=2){
		$('#btnBuscarFamilia').attr('disabled', false);
		$('#btnBuscarFamilia').addClass('mdl-button--raised');
	} else{
		$('#btnBuscarFamilia').attr('disabled', true);
		$('#btnBuscarFamilia').removeClass('mdl-button--raised');
	}
}

//PSICOLOGIA
var datosHijosPsico = 0;
function getDatosHijosPsico(){
	$("#btnGuardarFamiliar").attr("onclick", "guardarDatosHijosPsico()");
	$("#btnGuardarFamiliar").attr("id", "btnGuardarPostulantePsico");
	
	$("#btnGuardarPostulante").attr("onclick", "guardarDatosHijosPsico()");
	$("#btnGuardarPostulante").attr("id", "btnGuardarPostulantePsico");
	
	if(datosHijosPsico == 0){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				url  : 'c_confirmar_datos/getDatosHijosPsicologia',
				type : 'POST'
			})
			.done(function(data) {
				try {
					data = JSON.parse(data);
					if(data.error == 0){
						$("#cont_cabe_hijos_psico").html(data.cabeHij);
						$("#cont_ficha_psicologica_contacto").html(data.ficha);
						componentHandler.upgradeAllRegistered();
						cons_contacto_pos_psico = data.contacto;
						datosHijosPsico = 1;
					}else{
						location.reload();
					}
					$('#btnGuardarPostulantePsico').prop("disabled", true);
				} catch(err) {
					location.reload();
				}
			});
		});
	}
}

function changeHijosDatosPsico(contacto, element){
	Pace.restart();
	Pace.track(function() {
		$(".chip-hijos-psico").removeClass("active");
		$(element).addClass("active");
		$.ajax({
			data : { contacto : contacto},
			url  : 'c_confirmar_datos/fichaPsicologicaContacto',
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_ficha_psicologica_contacto").html(data.ficha);
				componentHandler.upgradeAllRegistered();
				cons_contacto_pos_psico = contacto;
				$('#btnGuardarPostulantePsico').prop("disabled", true);
			}else{
			    msj("success", data.msj, null);
			}
		});
	});
}

function cambioCampoPostulantePsico(element){
	$('#btnGuardarPostulantePsico').prop("disabled", false);
	val = $(element).val();
	if(val.length != 0){
		$(element).closest(".mdl-input-group").find("p").removeClass("is-invalid-psico");
	}else{
		$(element).closest(".mdl-input-group").find("p").addClass("is-invalid-psico");
	}
}

function guardarDatosHijosPsico(){
	Pace.restart();
	Pace.track(function() {
		var json = {};
		var respuestas = [];
		json.respuesta = respuestas;
		$('#cont_ficha_psicologica_contacto .divPapa').each(function(){ 
		   if($(this).attr("attr-tipo") == 1){//RADIO BUTTON
			   console.log($(this).attr("attr-name"));
			   if($('input[name="'+$(this).attr("attr-name")+'"]:checked').val() !== undefined && $('input[name="'+$(this).attr("attr-name")+'"]:checked').val().length){
				   valor    = $('input[name="'+$(this).attr("attr-name")+'"]:checked').val();
				   pregunta = $(this).attr("attr-pregunta");
				   var respuesta = {"valor"    : valor,
					    		    "pregunta" : pregunta,
					    		    "tipo"     : 1};			
				   json.respuesta.push(respuesta);
			   }
		   }else if($(this).attr("attr-tipo") == 2){//CHECKBOX
			   if($('input[name="'+$(this).attr("attr-name")+'"]:checked').length != 0){
				   var array = [];
				   $.each($("input[name='"+$(this).attr("attr-name")+"']:checked"), function() {
					   valor = $(this).val();
					   array.push(valor);
				   });
				   pregunta = $(this).attr("attr-pregunta");
				   var respuesta = {"valor"    : array,
			    		   		    "pregunta" : pregunta,
					    		    "tipo"     : 2};			
		           json.respuesta.push(respuesta);
			   }
		   }else{//INPUTTEXT
			   valor = $(this).find("input").val();
			   if(valor.trim().length != 0){
				   pregunta = $(this).attr("attr-pregunta");
				   var respuesta = {"valor"    : valor,
						   		    "pregunta" : pregunta,
					    		    "tipo"     : 3};			
				   json.respuesta.push(respuesta);
			   }
		   }
		});
		var jsonStringRespuesta = JSON.stringify(json);
		console.log(jsonStringRespuesta);
		$.ajax({
			data : { contacto   : cons_contacto_pos_psico,
				     respuestas : jsonStringRespuesta},
			url  : 'c_confirmar_datos/guardarFichaPsicoContacto',
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			msj("success", data.msj, null);
		});
	});
}

function agregarPariente(){
	setearCombo("selectParentesco", null);
	$("#apellidoPaternoPariente").val(null);
	$("#apellidoMaternoPariente").val(null);
	$("#nombrePariente").val(null);
	setearCombo("selectTipoDocumento", null);
	habilitarCampo('selectTipoDocumento','nroDocumento');
	$("#nroDocumento").val(null);
	setearCombo("selectSexoPariente", null);
	$("#telefonoPariente").val(null);
	$("#celularPariente").val(null);
	$("#correoPariente").val(null);
	$("#fechaNacPariente").val(null);
	
	setearCombo("departamentoFam", null);
	setCombo("provinciaFam", null, "Provincia actual");
	setCombo("distritoFam", null, "Distrito actual");
	
	$("#referencia_domicilio").val(null);
	
	$("#cont_datos_pariente").find(".is-invalid").removeClass("is-invalid");
	
	$('#btnGuardarFamiliar').prop("disabled", false);
	$('#btnGuardarFamiliar').attr("onclick", "crearFamiliar()");
	$("#btnGuardarPostulante").attr("onclick", "crerFamiliar()");
	$("#btnGuardarPostulante").attr("id", "btnGuardarFamiliar");
	$("#btnGuardarPostulantePsico").attr("onclick", "crerFamiliar()");
	$("#btnGuardarPostulantePsico").attr("id", "btnGuardarFamiliar");
	
	$(".chip-parientes").removeClass("active");
}

function crearFamiliar(){
	Pace.restart();
	Pace.track(function() {
		parentesco      = $("#selectParentesco").val();
		apellidopaterno = $("#apellidoPaternoPariente").val();
		apellidomaterno = $("#apellidoMaternoPariente").val();
		nombres         = $("#nombrePariente").val();
		
		tipodoc         = $("#selectTipoDocumento").val();
		nrodoc          = $("#nroDocumento").val();
		sexo            = $("#selectSexoPariente").val();
		telffijo        = $("#telefonoPariente").val();
		telfcel         = $("#celularPariente").val();
		correo          = $("#correoPariente").val();
		depart          = $("#departamentoFam").val();
		provincia       = $("#provinciaFam").val();
		distrito        = $("#distritoFam").val();
		referencia      = $("#referencia_domicilio").val();
		fecnaci         = $("#fechaNacPariente").val();
		$.ajax({
			data : { contacto        : cons_contacto_par,
				     parentesco      : parentesco,
				     apellidopaterno : apellidopaterno,
				     apellidomaterno : apellidomaterno,
				     nombres         : nombres,
				     tipodoc         : tipodoc,
				     nrodoc          : nrodoc,
				     sexo            : sexo,
				     telffijo        : telffijo,
				     telfcel         : telfcel,
				     correo          : correo,
				     depart          : depart,
				     provincia       : provincia,
				     distrito        : distrito,
				     referencia      : referencia,
				     fecnaci         : fecnaci},
			url  : 'c_confirmar_datos/crearPariente',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0){
					cons_contacto_par = data.idPariente;
					$("#cont_cabe_familiares").prepend(data.chip);
					$('#btnGuardarFamiliar').prop("disabled", true);
					$('#btnGuardarFamiliar').attr("onclick", "guardarDatosFamiliar()");
				}
			    msj("success", data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});	
}

function agregarPostulante(){
	$("#apellidoPaternoPostulante").val(null);
	$("#apellidoMaternoPostulante").val(null);
	$("#nombrePostulante").val(null);
	$("#fechaNacPostulante").val(null);
	setearCombo("selectTipoDocumentoPostulante", null);
	habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante');
	$("#nroDocumentoPostulante").val(null);
	setearCombo("selectSexoPostulante", null);
	setearCombo("selectGradoNivel", null);
	
	$("#tab2").find(".is-invalid").removeClass("is-invalid");
	
	$('#btnGuardarPostulante').prop("disabled", false);
	$('#btnGuardarPostulante').attr("onclick", "crearPostulante()");
	$("#btnGuardarFamiliar").attr("onclick", "crearPostulante()");
	$("#btnGuardarFamiliar").attr("id", "btnGuardarPostulante");
	$("#btnGuardarPostulantePsico").attr("onclick", "crearPostulante()");
	$("#btnGuardarPostulantePsico").attr("id", "btnGuardarPostulante");
	
	$(".chip-hijos").removeClass("active");
}

function crearPostulante(){
	Pace.restart();
	Pace.track(function() {
		apellidopaterno = $("#apellidoPaternoPostulante").val();
		apellidomaterno = $("#apellidoMaternoPostulante").val();
		nombres         = $("#nombrePostulante").val();
		fecnaci         = $("#fechaNacPostulante").val();
		tipodoc         = $("#selectTipoDocumentoPostulante").val();
		numdoc          = $("#nroDocumentoPostulante").val();
		gradnive        = $("#selectGradoNivel").val();
		sexo            = $("#selectSexoPostulante").val();
		$.ajax({
			data : { contacto        : cons_contacto_par,
				     apellidopaterno : apellidopaterno,
				     apellidomaterno : apellidomaterno,
				     nombres         : nombres,
				     fecnaci         : fecnaci,
				     tipodoc         : tipodoc,
				     numdoc          : numdoc,
				     gradnive        : gradnive,
				     sexo         	 : sexo},
			url  : 'c_confirmar_datos/crearPostulante',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0){
					cons_contacto_par = data.idPostulante;
					$("#cont_cabe_hijos").prepend(data.chip);
					$('#btnGuardarPostulante').prop("disabled", true);
					$('#btnGuardarPostulante').attr("onclick", "guardarDatosHijos()");
				}
			    msj("success", data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});	
}