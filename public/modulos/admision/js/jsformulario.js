var cons_index_serie       = 0;
var arrayParientes         = [];
var arrayPostulantes       = [];
var indice_editar          = null;
var editarParientes        = null;
var editarPostulantes      = null;
var comprobarCampos        = null;
var comprobarCamposEst     = null;
var idRolUsuario           = null;
var cons_cod_familia       = null;
var eliminarParientes      = null;
var eliminarPostulantes    = null;
var cons_id_familiar_enc   = null;
var verano                 = null;

function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initButtonCalendarDays('fechaNacPostulante');
	initMaskInputs('fechaNacPostulante');
	initButtonCalendarDays('fecha');
	initMaskInputs('fecha');
    initButtonCalendarHours('hora');
	
	$(":input").inputmask();
	
	$('#rootwizard1').bootstrapWizard();
	$('.my-link').bind('click', false);
	initButtonLoad('btnMRC');
	initLimitInputs('observacion_postulante','observacion');
}

function guardarPariente() {
	Pace.restart();
	Pace.track(function(){
		if(arrayParientes.length < 3){
			var obj = {
					editar              : 0,
					index_serie         : cons_index_serie,
				    nombre      		: $('#nombrePariente').val(),
			    	apellidopaterno     : $('#apellidoPaternoPariente').val(),
				    apellidomaterno     : $('#apellidoMaternoPariente').val(),
				    parentesco          : $('#selectParentesco option:selected').val(),
				    sexo                : $('#selectSexoPariente option:selected').val(),
				    correo              : $('#correoPariente').val(),
				    tipodocumento       : $('#selectTipoDocumento option:selected').val(),
				    nrodocumento        : $('#nroDocumento').val(),
				    departamento        : $('#departamentoFam option:selected').val(),
				    provincia           : $('#provinciaFam option:selected').val(),
				    distrito            : $('#distritoFam option:selected').val(),
				    celular             : $('#celularPariente').val(),
				    telfijo             : $('#telefonoPariente').val(),
				    referencia          : $('#referencia_domicilio').val(),
				    mediocolegio        : $('#medioColegio option:selected').val(),
				    operador            : $('#selectOperador option:selected').val(),
				    canal               : $('#selectCanal option:selected').val(),
				    id_familiar         : null,
			};
			$.ajax({
				data : {obj          : obj,
					    parientes    : arrayParientes,
					    postulantes  : arrayPostulantes},
				url  : 'registro/guardarPariente', 
				async: true,
				type : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					arrayParientes.push(obj);
					cons_index_serie++;
//					if(arrayParientes.length == 3){
//						$('#btnNuevoPariente').hide();
//					}
//					$('#step2').unbind('click', false);
					$("#li1").addClass("complete");
					if(arrayPostulantes.length > 0){
						$("#li2").addClass("complete");
					}
//					$('#btnSiguiente').attr("disabled", false);
//					limpiarFormParientes();
					indice_editar = cons_index_serie-1;
					editarParientes = cons_index_serie-1;
					createButton(arrayParientes,1,null,cons_index_serie);
					$("#cont_parientes").show();
//					$("#cont_agregar").addClass("display_none");
//					$('#chip'+data.indice).addClass("active", true);
					if(arrayParientes.length < 3){
						$("#menuFamCrear").css("display","block");
						$("#menuFamSave").css("display","none");
					} else {
						$("#menuFamCrear").css("display","none");
						$("#menuFamSave").css("display","none");
					}
					comprobarCampos = null;
				} else {
					msj('success', data.msj, null);
					return;
				}
			});
		}
	});
}

function guardarPostulante() {
	Pace.restart();
	Pace.track(function(){
		if(arrayPostulantes.length < 3){
			var obj = {
					editar              : 0,
					index_serie         : cons_index_serie,
				    nombre      		: $('#nombrePostulante').val(),
			    	apellidopaterno     : $('#apellidoPaternoPostulante').val(),
				    apellidomaterno     : $('#apellidoMaternoPostulante').val(),
				    sexo                : $('#selectSexoPostulante option:selected').val(),
				    tipodocumento       : $('#selectTipoDocumentoPostulante option:selected').val(),
				    nrodocumento        : $('#nroDocumentoPostulante').val(),
				    colegioprocedencia  : $('#selectColegioProcedencia option:selected').val(),
				    sedeinteres         : $('#selectSedeInteres option:selected').val(),
				    gradonivel          : $('#selectGradoNivel option:selected').val(),
				    fechanac            : $('#fechaNacPostulante').val(),
				    observacion         : $('#observacion_postulante').val(),
				    proceso             : $('#selectProceso').val()
			};
			$.ajax({
				data : {obj             : obj,
					    postulantes     : arrayPostulantes,
					    parientes       : arrayParientes},
				url  : 'registro/guardarPostulante', 
				async: true,
				type : 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					arrayPostulantes.push(obj);
					cons_index_serie++;
					indice_editar = cons_index_serie-1;
					editarPostulantes = cons_index_serie-1;
					createButton(arrayPostulantes,2,null, editarPostulantes);
	
					$("#li2").addClass("complete");
					$('#btnNext').attr("disabled", false);
					$("#cont_postulantes").show();
					$("#cont_agregarPostulantes").css("display","none");

					comprobarCamposEst = null;
					if(arrayPostulantes.length < 3){
						$("#menuEstCrear").css("display","block");
						$("#menuEstSave").css("display","none");
					} else {
						$("#menuEstCrear").css("display","none");
						$("#menuEstSave").css("display","none");
					}
				} else {
					msj('success', data.msj, null);
					return;
				}
			});
		}
	});
}

function createButton(array,tipo,editar,crear){
	$.ajax({
		data : {array  : array,
			    tipo   : tipo,
			    editar : editar,
			    crear  : crear},
		url  : 'registro/createButton', 
		async: true,
		type : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(tipo == 1){
			$("#cont_agregar").addClass("display_none");
			$("#cont_parientes").html(data.form);
		} else if(tipo == 2){
			$("#cont_agregar").addClass("display_none");
			$("#cont_postulantes").html(data.form);
		}
		if(editar != null){
			$('#chip'+editar).addClass("active", true);
		}
		componentHandler.upgradeAllRegistered();
	});
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
	} else {
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

function verFormularioPariente(index){
	var validateInvalid = 1;
	if(index.length != 0){
		Pace.restart();
		Pace.track(function(){
			$.ajax({
				type    : 'POST',
				'url'   : 'registro/retornarIndex',
				data    : {index : index},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				var result = arrayParientes.filter(function( obj ) {
					  return obj.index_serie == data.indice;
				});
				if(result.length != 0){
					if(result[0].id_familiar != null && result[0].id_familiar.length != 0){
						cons_id_familiar_enc = result[0].id_familiar;
					}
					if (idRolUsuario != null){
						validateInvalid = 2;
					}
					setearInputAdm("nombrePariente",result[0].nombre,validateInvalid);
					setearInputAdm("apellidoPaternoPariente",result[0].apellidopaterno,validateInvalid);
					setearInputAdm("apellidoMaternoPariente",result[0].apellidomaterno,validateInvalid);
					setearInputAdm("correoPariente",result[0].correo,validateInvalid);
					setearComboAdm("selectTipoDocumento",result[0].tipodocumento,validateInvalid);
					habilitarCampo('selectTipoDocumento', 'nroDocumento',validateInvalid);
					setearInputAdm("nroDocumento",result[0].nrodocumento,validateInvalid);
					setearInputAdm("celularPariente",result[0].celular,validateInvalid);
					setearInputAdm("selectOperador",result[0].operador,validateInvalid);
					setearInputAdm("telefonoPariente",result[0].telfijo,validateInvalid);
					setearInputAdm("referencia_domicilio",result[0].referencia,validateInvalid);
					setearComboAdm("medioColegio",result[0].mediocolegio,validateInvalid);
					setearComboAdm("selectCanal",result[0].canal,validateInvalid);
					
					setearComboAdm("selectParentesco",result[0].parentesco,validateInvalid);
					setearComboAdm("selectSexoPariente",result[0].sexo,validateInvalid);
					setearComboAdm("departamentoFam",result[0].departamento,validateInvalid);
					$.ajax({
						type    : 'POST',
						'url'   : 'registro/getUbigeoByTipo',
						data    : {idubigeo : result[0].departamento,
								   tipo     : 2},
						'async' : false
					}).done(function(data){
						data = JSON.parse(data);
						setCombo("provinciaFam", data.comboUbigeo, "Provincia actual");
					});
					setearComboAdm("provinciaFam",result[0].provincia,validateInvalid);
					$.ajax({
						type    : 'POST',
						'url'   : 'registro/getUbigeoByTipo',
						data    : {idubigeo  : result[0].departamento,
							       idubigeo1 : result[0].provincia,
								   tipo      : 3},
						'async' : false
					}).done(function(data){
						data = JSON.parse(data);
						setCombo("distritoFam", data.comboUbigeo, "Distrito actual");
					});
					setearComboAdm("distritoFam",result[0].distrito,validateInvalid);
					
					indice_editar = data.indice;
					editarParientes = data.indice;
					$('#provinciaFam').attr("disabled", false);
					$('#distritoFam').attr("disabled", false);
					$('#referencia_domicilio').attr("disabled", false);
					$('.selectButton').selectpicker('refresh');
//					$('#btnGuardar').hide();
					$('.chip-parientes').removeClass("active", true);
					$('#chip'+data.indice).addClass("active", true);
					
//					$("#btnNuevoPariente").css("display", "inline-block");
//					$("#btnGuardarPariente").css("display", "none");

//					$("#tab-addperson-fam").css("display","none");
//					$("#tab-datos-fam").css("display","block");
					if(arrayParientes.length < 3){
						$("#menuFamCrear").css("display","block");
						$("#menuFamSave").css("display","none");
					} else {
						$("#menuFamCrear").css("display","none");
						$("#menuFamSave").css("display","none");
					}
				}
			});
		});
	}
}

function verFormularioPostulante(index){
	var validateInvalid = 1;
	if(index.length != 0){
		Pace.restart();
		Pace.track(function(){
			$.ajax({
				type    : 'POST',
				'url'   : 'registro/retornarIndex',
				data    : {index : index},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				var result = arrayPostulantes.filter(function( obj ){
					  return obj.index_serie == data.indice;
				});
				if(result.length != 0){
					if (idRolUsuario != null){
						validateInvalid = 2;
					}
					setearComboAdm("selectProceso",result[0].proceso,validateInvalid);
					setearInputAdm("nombrePostulante",result[0].nombre,validateInvalid);
					setearInputAdm("apellidoPaternoPostulante",result[0].apellidopaterno,validateInvalid);
					setearInputAdm("apellidoMaternoPostulante",result[0].apellidomaterno,validateInvalid);
					setearComboAdm("selectTipoDocumentoPostulante",result[0].tipodocumento,validateInvalid);
					setearInputAdm("nroDocumentoPostulante",result[0].nrodocumento,validateInvalid);
					setearInputAdm("fechaNacPostulante",result[0].fechanac,validateInvalid);
					setearInputAdm("observacion_postulante",result[0].observacion,validateInvalid);
					
					setearComboAdm("selectSexoPostulante",result[0].sexo,validateInvalid);
					setearComboAdm("selectColegioProcedencia",result[0].colegioprocedencia,validateInvalid);

					setearComboAdm("selectGradoNivel",result[0].gradonivel,validateInvalid);
					
					if((result[0].gradonivel).length != 0){
						$.ajax({
							type    : 'POST',
							'url'   : 'registro/getSedesByNivel',
							data    : {valorNivel : result[0].gradonivel},
							'async' : false
						}).done(function(data){
							data = JSON.parse(data);
							$('#selectSedeInteres').attr("disabled", false);
							setCombo('selectSedeInteres', data.comboSedes,"Sede de inter&eacute;s (*)");
							$('.selectButton').selectpicker('refresh');
						});
						setearComboAdm("selectSedeInteres",result[0].sedeinteres,validateInvalid);
					}
					
					indice_editar = data.indice;
					editarPostulantes = data.indice;
					$('#nroDocumento').attr("disabled", false);
					$('.chip-postulantes').removeClass("active", true);
					$('#chip'+data.indice).addClass("active", true);

//					$("#btnNuevoPostulante").css("display", "inline-block");
//					$("#btnGuardarPostulante").css("display", "none");

					if(arrayPostulantes.length < 3){
						$("#menuEstCrear").css("display","block");
						$("#menuEstSave").css("display","none");
					} else {
						$("#menuEstCrear").css("display","none");
						$("#menuEstSave").css("display","none");
					}
				}
			});
		});
	}
}

function quitarPariente(index){
	$.ajax({
		type    : 'POST',
		'url'   : 'registro/retornarIndex',
		data    : {index : index},
		'async' : true
	}).done(function(data){
		data = JSON.parse(data);
		eliminarParientes = data.indice;
		modal("modalConfirmarEliminarPariente");
	});
}

function confirmarEliminarPariente(){
		for(var i = 0; i < arrayParientes.length; i++){
			if(arrayParientes[i].index_serie == eliminarParientes){
				arrayParientes.splice(i, 1);
				break;
			}
		}
		$(".btnGuardarFamiliar").attr("disabled", true);
		$(".btnGuardarFamiliar").addClass("guardar");

		if(arrayParientes.length == 0){
			indice_editar = null;
			editarParientes = null;
			$("#li1").removeClass("complete");
			$("#li2").removeClass("complete");
			$("#tab-addperson-fam").css("display","block");
			$("#tab-datos-fam").css("display","none");
			$("#cont_parientes").css("display", "none");
		} else {
			for(var i = 0; i < arrayParientes.length; i++){
				if(arrayParientes[i].index_serie != null){
					editarParientes = arrayParientes[i].index_serie;
					break;
				}
			}
			$("#menuFamCrear").css("display","block");
			$("#menuFamSave").css("display","none");
			
			var result = arrayParientes.filter(function( obj ) {
				  return obj.index_serie == editarParientes;
			});
			createButton(arrayParientes,1,editarParientes,null);
			if(result.length != 0){
				setearInputAdm("nombrePariente",result[0].nombre);
				setearInputAdm("apellidoPaternoPariente",result[0].apellidopaterno);
				setearInputAdm("apellidoMaternoPariente",result[0].apellidomaterno);
				setearInputAdm("correoPariente",result[0].correo);
				setearComboAdm("selectTipoDocumento",result[0].tipodocumento);
				habilitarCampo('selectTipoDocumento', 'nroDocumento');
				setearInputAdm("nroDocumento",result[0].nrodocumento);
				setearInputAdm("celularPariente",result[0].celular);
				setearInputAdm("selectOperador",result[0].operador);
				setearInputAdm("telefonoPariente",result[0].telfijo);
				setearInputAdm("referencia_domicilio",result[0].referencia);
				setearComboAdm("medioColegio",result[0].mediocolegio);
				setearComboAdm("selectCanal",result[0].canal);
				
				setearComboAdm("selectParentesco",result[0].parentesco);
				setearComboAdm("selectSexoPariente",result[0].sexo);
				setearComboAdm("departamentoFam",result[0].departamento);
				$.ajax({
					type    : 'POST',
					'url'   : 'registro/getUbigeoByTipo',
					data    : {idubigeo : result[0].departamento,
							   tipo     : 2},
					'async' : false
				}).done(function(data){
					data = JSON.parse(data);
					setCombo("provinciaFam", data.comboUbigeo, "Provincia actual");
				});
				setearComboAdm("provinciaFam",result[0].provincia);
				$.ajax({
					type    : 'POST',
					'url'   : 'registro/getUbigeoByTipo',
					data    : {idubigeo  : result[0].departamento,
						       idubigeo1 : result[0].provincia,
							   tipo      : 3},
					'async' : false
				}).done(function(data){
					data = JSON.parse(data);
					setCombo("distritoFam", data.comboUbigeo, "Distrito actual");
				});
				setearComboAdm("distritoFam",result[0].distrito);
				
//				indice_editar = editarParientes;
				$('#provinciaFam').attr("disabled", false);
				$('#distritoFam').attr("disabled", false);
				$('#referencia_domicilio').attr("disabled", false);
				$('.selectButton').selectpicker('refresh');
//				$('#btnGuardar').hide();
//				$('.chip-parientes').removeClass("active", true);
//				$('#chip'+editarParientes).addClass("active", true);
		    }
		}
		modal("modalConfirmarEliminarPariente");
//	});
}

function quitarPostulante(index){
	$.ajax({
		type    : 'POST',
		'url'   : 'registro/retornarIndex',
		data    : {index : index},
		'async' : true
	}).done(function(data){
		data = JSON.parse(data);
		eliminarPostulantes = data.indice;
		modal("modalConfirmarEliminarEstudiante");
	});
}

function confirmarEliminarEstudiante(){
		for(var i = 0; i < arrayPostulantes.length; i++){
			if(arrayPostulantes[i].index_serie == eliminarPostulantes){
				arrayPostulantes.splice(i, 1);
				break;
			}
		}
		$("#btnGuardarEstudiante").attr("disabled", true);
		$("#btnGuardarEstudiante").addClass("guardar");
		if(arrayPostulantes.length == 0){
			indice_editar = null;
			editarPostulantes = null;
			$("#li2").removeClass("complete");
			$("#tab-addperson-est").css("display","block");
			$("#tab-datos-est").css("display","none");
			$("#cont_postulantes").css("display", "none");
		} else {

			for(var i = 0; i < arrayPostulantes.length; i++){
				if(arrayPostulantes[i].index_serie != null){
					editarPostulantes = arrayPostulantes[i].index_serie;
					break;
				}
			}
			$("#menuFamCrear").css("display","block");
			$("#menuFamSave").css("display","none");
			var result = arrayPostulantes.filter(function( obj ) {
				  return obj.index_serie == editarPostulantes;
			});
			createButton(arrayPostulantes,2,editarPostulantes,null);
			if(result.length != 0){
				setearInputAdm("nombrePostulante",result[0].nombre);
				setearInputAdm("apellidoPaternoPostulante",result[0].apellidopaterno);
				setearInputAdm("apellidoMaternoPostulante",result[0].apellidomaterno);
				setearComboAdm("selectTipoDocumentoPostulante",result[0].tipodocumento);
				setearInputAdm("nroDocumentoPostulante",result[0].nrodocumento);
				setearInputAdm("fechaNacPostulante",result[0].fechanac);
				setearInputAdm("observacion_postulante",result[0].observacion);
				
				setearComboAdm("selectSexoPostulante",result[0].sexo);
				setearComboAdm("selectColegioProcedencia",result[0].colegioprocedencia);

				setearComboAdm("selectGradoNivel",result[0].gradonivel);
				if((result[0].gradonivel).length != 0){
					$.ajax({
						type    : 'POST',
						'url'   : 'registro/getSedesByNivel',
						data    : {valorNivel : result[0].gradonivel},
						'async' : false
					}).done(function(data){
						data = JSON.parse(data);
						$('#selectSedeInteres').attr("disabled", false);
						setCombo('selectSedeInteres', data.comboSedes,"Sede de inter&eacute;s (*)");
						$('.selectButton').selectpicker('refresh');
					});
					setearComboAdm("selectSedeInteres",result[0].sedeinteres);
				}
				$('#nroDocumento').attr("disabled", false);
			}
			
		}
		modal("modalConfirmarEliminarEstudiante");
//	});
}

function limpiarFormParientes(opc){
	var validateInvalid = 1;
	if (idRolUsuario != null){
		validateInvalid = 2;
	}
	setearInputAdm("nombrePariente",null,validateInvalid);
	setearInputAdm("apellidoPaternoPariente",null,validateInvalid);
	setearInputAdm("apellidoMaternoPariente",null,validateInvalid);
	setearInputAdm("correoPariente",null,validateInvalid);
	setearComboAdm("selectTipoDocumento",null,validateInvalid);
	setearInputAdm("nroDocumento",null,validateInvalid);
	$('#nroDocumento').attr("disabled", true);
	setearInputAdm("celularPariente",null,validateInvalid);
	setearInputAdm("telefonoPariente",null,validateInvalid);
	setearComboAdm("medioColegio",null,validateInvalid);
	setearComboAdm("selectCanal",null,validateInvalid);

	setearComboAdm("selectOperador",null,validateInvalid);
	setearComboAdm("selectParentesco",null,validateInvalid);
	setearComboAdm("selectSexoPariente",null,validateInvalid);
	
	if(opc == 1){//LIMPIAR TODO
		setearComboAdm("departamentoFam",null,validateInvalid);
		setCombo("provinciaFam",null,"Provincia actual");
		setearComboAdm("provinciaFam",null,validateInvalid);
		setCombo("distritoFam",null,"Distrito actual");
		setearComboAdm("distritoFam",null,validateInvalid);
		$('#provinciaFam').attr("disabled", true);
		$('#distritoFam').attr("disabled", true);
		setearInputAdm("referencia_domicilio",null,validateInvalid);
		$('#referencia_domicilio').attr("disabled", true);
	}else{
		if($("#distritoFam").val().length <= 0){
			setearComboAdm("departamentoFam",null,validateInvalid);
			setCombo("provinciaFam",null,"Provincia actual");
			setearComboAdm("provinciaFam",null,validateInvalid);
			setCombo("distritoFam",null,"Distrito actual");
			setearComboAdm("distritoFam",null,validateInvalid);
			$('#provinciaFam').attr("disabled", true);
			$('#distritoFam').attr("disabled", true);
			setearInputAdm("referencia_domicilio",null,validateInvalid);
			$('#referencia_domicilio').attr("disabled", true);
		}
	}

	$('.selectButton').selectpicker('refresh');
	
	indice_editar = null;
	editarParientes = null;
	$("#menuFamCrear").css("display","none");
	$("#menuFamSave").css("display","block");
}

function limpiarFormPostulantes(){
	var validateInvalid = 1;
	if (idRolUsuario != null){
		validateInvalid = 2;
	}
	setearComboAdm("selectProceso",null,validateInvalid);
	$("#selectProceso option:selected").prop("selected", false);
	$('#selectProceso').selectpicker('refresh');
	setearInputAdm("nombrePostulante",null,validateInvalid);
	setearInputAdm("apellidoPaternoPostulante",null,validateInvalid);
	setearInputAdm("apellidoMaternoPostulante",null,validateInvalid);
	setearComboAdm("selectTipoDocumentoPostulante",null,validateInvalid);
	setearInputAdm("nroDocumentoPostulante",null,validateInvalid);
	$('#nroDocumentoPostulante').attr("disabled", true);
	setearInputAdm("fechaNacPostulante",null,validateInvalid);
	setearInputAdm("observacion_postulante",null,validateInvalid);
	
	setearComboAdm("selectSexoPostulante",null,validateInvalid);
	setearComboAdm("selectColegioProcedencia",null,validateInvalid);
	setearComboAdm("selectSedeInteres",null,validateInvalid);
	$('#selectSedeInteres').attr("disabled", true);
	setearComboAdm("selectGradoNivel",null,validateInvalid);
	
	indice_editar   = null;
	editarPostulantes = null;
	$("#menuEstCrear").css("display","none");
	$("#menuEstSave").css("display","block");
}

//function nuevoPariente(){
//	limpiarFormParientes(1);
//	$('.chip-parientes').removeClass("active", true);
//	indice_editar = null;
//	editarParientes = null;
//	$("#btnNuevoPariente").css("display", "none");
//	$("#btnGuardarPariente").css("display", "inline-block");
//}
//
//function nuevoPostulante(){
//	limpiarFormPostulantes();
//	$('.chip-postulantes').removeClass("active", true);
//	$("#btnNuevoPostulante").css("display", "none");
//	$("#btnGuardarPostulante").css("display", "inline-block");
//}

function editarPariente(){
//	declareInvalid("selectParentesco", data.parentesco);
	if(editarParientes != null){
		if(arrayParientes.length <= 3){
			Pace.restart();
			Pace.track(function(){
				var obj = {
						editar              : 1,
						index_serie         : cons_index_serie,
					    nombre      		: $('#nombrePariente').val(),
				    	apellidopaterno     : $('#apellidoPaternoPariente').val(),
					    apellidomaterno     : $('#apellidoMaternoPariente').val(),
					    parentesco          : $('#selectParentesco option:selected').val(),
					    sexo                : $('#selectSexoPariente option:selected').val(),
					    correo              : $('#correoPariente').val(),
					    tipodocumento       : $('#selectTipoDocumento option:selected').val(),
					    nrodocumento        : $('#nroDocumento').val(),
					    departamento        : $('#departamentoFam option:selected').val(),
					    provincia           : $('#provinciaFam option:selected').val(),
					    distrito            : $('#distritoFam option:selected').val(),
					    celular             : $('#celularPariente').val(),
					    telfijo             : $('#telefonoPariente').val(),
					    referencia          : $('#referencia_domicilio').val(),
					    mediocolegio        : $('#medioColegio option:selected').val(),
					    operador            : $('#selectOperador option:selected').val(),
					    canal               : $('#selectCanal option:selected').val(),
					    id_familiar         : cons_id_familiar_enc
				};
				$.ajax({
					data : {obj          : obj,
						    parientes    : arrayParientes},
					url  : 'registro/guardarPariente', 
					async: true,
					type : 'POST'
				})
				.done(function(data){
					data = JSON.parse(data);
					if(data.error == 0){
						for(var i = 0; i < arrayParientes.length; i++){
							if(arrayParientes[i].index_serie == editarParientes){
								arrayParientes.splice(i, 1);
								break;
							}
						}
						arrayParientes.push(obj);
						editarParientes = cons_index_serie;
						cons_index_serie++;
						if(arrayParientes.length == 3){
							$("#btnGuardarPariente").attr("disabled", true);
							$("#btnGuardarPariente").addClass("guardar");
						}
//						$('#step2').unbind('click', false);
						createButton(arrayParientes,1,editarParientes);
					} else {
						msj('success', data.msj, null);
					}
				});
			});
		}
	}
}

function editarPostulante(){
	if(editarPostulantes != null){
		if(arrayPostulantes.length <= 3){
			Pace.restart();
			Pace.track(function(){
				var obj = {
						editar              : 1,
						index_serie         : cons_index_serie,
					    nombre      		: $('#nombrePostulante').val(),
				    	apellidopaterno     : $('#apellidoPaternoPostulante').val(),
					    apellidomaterno     : $('#apellidoMaternoPostulante').val(),
					    sexo                : $('#selectSexoPostulante option:selected').val(),
					    tipodocumento       : $('#selectTipoDocumentoPostulante option:selected').val(),
					    nrodocumento        : $('#nroDocumentoPostulante').val(),
					    colegioprocedencia  : $('#selectColegioProcedencia option:selected').val(),
					    sedeinteres         : $('#selectSedeInteres option:selected').val(),
					    gradonivel          : $('#selectGradoNivel option:selected').val(),
					    fechanac            : $('#fechaNacPostulante').val(),
					    observacion         : $('#observacion_postulante').val(),
					    proceso             : $('#selectProceso').val()
				};
				$.ajax({
					data : {obj             : obj,
						    postulantes     : arrayPostulantes},
					url  : 'registro/guardarPostulante', 
					async: true,
					type : 'POST'
				})
				.done(function(data){
					data = JSON.parse(data);
					if(data.error == 0){
						for(var i = 0; i < arrayPostulantes.length; i++){
							if(arrayPostulantes[i].index_serie == editarPostulantes){
								arrayPostulantes.splice(i, 1);
								break;
							}
						}
						arrayPostulantes.push(obj);
						
						editarPostulantes = cons_index_serie;
						cons_index_serie++;
						if(arrayPostulantes.length == 3){
							$("#btnGuardarEstudiante").attr("disabled", true);
							$("#btnGuardarEstudiante").addClass("guardar");
						}
						$("#li2").addClass("complete");
						createButton(arrayPostulantes,2,editarPostulantes);
					} else {
						msj('success', data.msj, null);
					}
				});
			});
		}
	}
}
var envioFormulario = 0;
function enviarFormulario(){
	$('#btnEnviarFormulario').attr("disabled", true);
	Pace.restart();
	Pace.track(function(){
		if(arrayPostulantes.length == 0){
			obj = {
					proceso      		: null,
				    nombre      		: 'POSTULANTE',
			    	apellidopaterno     : 'POSTULANTE',
				    apellidomaterno     : 'POSTULANTE',
				    sexo                : null,
				    tipodocumento       : null,
				    nrodocumento        : null,
				    colegioprocedencia  : null,
				    sedeinteres         : null,
				    gradonivel          : null,
				    fechanac            : null,
				    observacion         : null
		    }
			arrayPostulantes.push(obj);
		}
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/enviarFormulario',
			data    : {postulantes : arrayPostulantes,
				       parientes   : arrayParientes},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_parientes").html(null);
				$("#cont_postulantes").html(null);
				$("#check2").removeClass("is-checked");
				limpiarFormParientes();
				limpiarFormPostulantes();
				envioFormulario = 1;
				abrirModalEnvioFormulario();
				$("#menuEstCrear").css("display", "none");
				$("#menuEstSave").css("display", "none");
				$("#menuFamCrear").css("display","none");
				$("#menuFamSave").css("display","none");
				if(data.countLectivo == 0){
					$("#btnGoToInscribir").css("display", "none");
					$("#txtRegistro").text('Se realiz\u00F3 correctamente tu registro!')
				}
			} else {
				$("#menuEstCrear").css("display", "none");
				$("#menuEstSave").css("display", "none");
				$("#menuFamCrear").css("display","none");
				$("#menuFamSave").css("display","none");
				$('#btnEnviarFormulario').attr("disabled", false);
				msj('success', data.msj, null);
			}
		});
	});
}
var tyc = 0;
function nextStep(id){
	if(id == 2){
		indice_editar = null;
		if(arrayParientes.length != 0) {
			if($.trim($('#nombrePariente').val()) != '' &&
			   $.trim($('#apellidoPaternoPariente').val()) != '' &&
			   $.trim($('#selectParentesco option:selected').val()) != '' &&
			   $.trim($('#selectCanal option:selected').val()) != '' ) {
			    guardarPariente();
			}
			limpiarFormPostulantes();
			$('.chip-postulantes').removeClass("active", true);
			$("#btnNuevoPostulante").css("display", "none");
			$("#btnGuardarPostulante").css("display", "inline-block");
			$("#li1").removeClass("active");
			$("#li2").addClass("active");
		
			$(".tab-pane").removeClass('active');
			$("#tab2").addClass('active');

			$("#step1").attr("aria-expanded","false");
			$("#step2").attr("aria-expanded","true");
			$("#progressBar").css("width", "33%");
		}
	} else if(id == 3){
		if(arrayParientes.length != 0) {
			if($.trim($('#nombrePostulante').val()) != '' &&
		       $.trim($('#apellidoPaternoPostulante').val()) != '') {
				guardarPostulante();
			}
			indice_editar = null;
			limpiarFormPostulantes();
			$('.chip-postulantes').removeClass("active", true);
			$("#btnNuevoPostulante").css("display", "none");
			$("#btnGuardarPostulante").css("display", "inline-block");

			$("#li2").addClass("complete");
			tyc = 1;
			$("#li2").removeClass("active");
			$("#li3").addClass("active");
			$("#li4").removeClass("active");
			$(".tab-pane").removeClass('active');
			$("#tab3").addClass('active');
			$("#step2").attr("aria-expanded","false");
			$("#step3").attr("aria-expanded","true");
			$("#progressBar").css("width", "66%");
		}
	} else if(id == 4){
		if(arrayParientes.length != 0 && arrayPostulantes.length != 0) {
			modal("modalEnvioFormulario");
			$('#step3').bind('click', false);
			$('#step2').bind('click', false);
			$('#step1').bind('click', false);
			$("#li3").addClass("complete");
			$("#li3").removeClass("active");
			$("#li4").addClass("active");
			$(".tab-pane").removeClass('active');
			$("#tab4").addClass('active');
			$("#step3").attr("aria-expanded","false");
			$("#step4").attr("aria-expanded","true");
			$("#progressBar").css("width", "100%");
		}
	}
}

function progressBarByStep(id){
	if(id == 1){
		if(envioFormulario != 1){
			$("#menuFamCrear").css("display", "none");
			$("#menuFamSave").css("display", "block");
			if(comprobarCampos == null){
				if(arrayParientes.length < 3){
					$("#menuFamCrear").css("display","block");
					$("#menuFamSave").css("display","none");
				} else {
					$("#menuFamCrear").css("display","none");
					$("#menuFamSave").css("display","none");
				}
//				$("#menuFamCrear").css("display","block");
//				$("#menuFamSave").css("display","none");
			}
			$("#menuEstCrear").css("display", "none");
			$("#menuEstSave").css("display", "none");
			$("#li1").addClass("active");
			$("#li2").removeClass("active");
			$("#li3").removeClass("active");
			$("#li4").removeClass("active");
			$(".tab-pane").removeClass('active');
			$("#tab1").addClass('active');
		}
	} else if(id == 2){
		if(arrayParientes.length != 0 && envioFormulario != 1){
			if (idRolUsuario == null){
				$.ajax({
					type    : 'POST',
					'url'   : 'registro/retornarIndex',
					data    : {index : null},
					'async' : true
				}).done(function(data){
					data = JSON.parse(data);
					if(data.rol && data.rol != 41){
						idRolUsuario = data.rol;
					}
				});
			}
			
			var faltanCampos = 0;
			if(idRolUsuario != null){
				var result = arrayParientes.filter(function( obj ) {
					canal = obj.canal;
					if(faltanCampos == 0){
						if((obj.parentesco == null) ||(obj.parentesco).length == 0){
							faltanCampos = 1;
							return;
						} else if((obj.apellidopaterno == null) ||(obj.apellidopaterno).length == 0){
							faltanCampos = 1;
							return;
						} else if((obj.nombre == null) ||(obj.nombre).length == 0){
							faltanCampos = 1;
							return;
						} else if((canal == null) || (canal).length == 0){
							faltanCampos = 1;
							return;
						} else if(((obj.telfijo).length == 0) && ((obj.celular).length == 0)){
							faltanCampos = 1;
							return;
						} else {
							faltanCampos = 0;
							return;
						}
					}
				});
			} else {
				result = arrayParientes.filter(function( obj ) {
//					return obj.index_serie == data.indice;
					canal = obj.canal;
					if(faltanCampos == 0){
						if((obj.parentesco == null) ||(obj.parentesco).length == 0){
							faltanCampos = 1;
							return;
						}
						if((obj.apellidopaterno == null) ||(obj.apellidopaterno).length == 0){
							faltanCampos = 1;
							return;
						}
						if((obj.apellidomaterno == null) ||(obj.apellidomaterno).length == 0){
							faltanCampos = 1;
							return;
						}
						if((obj.nombre == null) ||(obj.nombre).length == 0){
							faltanCampos = 1;
							return;
						} else {
							faltanCampos = 0;
							return;
						}
					}
				});
			}
			if(faltanCampos != 1){
				$("#menuEstCrear").css("display", "none");
				$("#menuEstSave").css("display", "block");
				if(comprobarCamposEst == null){
					if(arrayPostulantes.length < 3){
						$("#menuEstCrear").css("display","block");
						$("#menuEstSave").css("display","none");
					} else {
						$("#menuEstCrear").css("display","none");
						$("#menuEstSave").css("display","none");
					}
				}
				$("#menuFamCrear").css("display","none");
				$("#menuFamSave").css("display","none");
				$("#li1").removeClass("active");
				$("#li2").addClass("active");
				$("#li3").removeClass("active");
				$("#li4").removeClass("active");
				$(".tab-pane").removeClass('active');
				$("#tab2").addClass('active');
			} else {
				mostrarNotificacion('success', 'Debe completar los datos obligatorios del pariente', null);
			}
		}
	} else if (id == 3){
		if(arrayParientes.length != 0 && arrayPostulantes.length != 0 && envioFormulario != 1){
			$("#menuEstCrear").css("display", "none");
			$("#menuEstSave").css("display", "none");
			$("#menuFamCrear").css("display","none");
			$("#menuFamSave").css("display","none");
			$("#li1").removeClass("active");
			$("#li2").removeClass("active");
			$("#li3").addClass("active");
			$("#li4").removeClass("active");
			$(".tab-pane").removeClass('active');
			$("#tab3").addClass('active');
		}
	} else if (id == 4){
		if(arrayParientes.length != 0 && arrayPostulantes.length != 0 && envioFormulario == 1){
			$("#menuEstCrear").css("display", "none");
			$("#menuEstSave").css("display", "none");
			$("#menuFamCrear").css("display","none");
			$("#menuFamSave").css("display","none");
			$("#li1").removeClass("active");
			$("#li2").removeClass("active");
			$("#li3").removeClass("active");
			$("#li4").addClass("active");
			$(".tab-pane").removeClass('active');
			$("#tab4").addClass('active');
		}
	}
}

function abrirSelectFotoPersona(){
	$('#fotoPersona').trigger('click'); 
}

function abrirModalEnvioFormulario(){
	$('#modalEnvioFormulario').modal({
	   backdrop: 'static',
	   keyboard: false
	});
}

function habilitarNextStep(number){
	if(number == 1){
		if ($('#checkbox-2').is(':checked')) {
			$('#btnEnviarFormulario').attr("disabled", false);
		} else {
			$('#btnEnviarFormulario').attr("disabled", true);
		}
	} else if(number == 2){
		if(idRolUsuario != null){
			var idevento = $('#selectEvento option:selected').val();
			var opcion   = $('#selectOpcion option:selected').val();
			var fecha    = $('#fecha').val();
			var hora     = $('#hora').val();
			if(verano != null){
				if(idevento.length != 0 && opcion.length != 0  && fecha.length != 0 && hora.length != 0 ){
					$('#btnInscribir').attr("disabled", false);
				} else {
					$('#btnInscribir').attr("disabled", true);
				}
			} else {
				if(idevento.length != 0 && opcion.length != 0){
					$('#btnInscribir').attr("disabled", false);
				} else {
					$('#btnInscribir').attr("disabled", true);
				}
			}
		} else {
			if ($('#checkbox-1').is(':checked')) {
				$('#btnInscribir').attr("disabled", false);
			} else {
				$('#btnInscribir').attr("disabled", true);
			}
		}
	}
}

function inscribirAEvento(idevento){
	$('#btnInscribir').attr("disabled", true);
	Pace.restart();
	Pace.track(function(){
		var check = 0;
		if($('#checkbox-3').is(':checked')){
			check = 1;
		}
		enc = 0;
		opcion = '';
		hora   = null;
		fecha  = null;
		if(idevento == ''){
			idevento = $('#selectEvento option:selected').val();
			opcion   = $('#selectOpcion option:selected').val();
			hora     = $('#hora').val();
			fecha    = $('#fecha').val();
			enc      = 1;
		}
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/inscribirAEvento',
			data    : {idevento    : idevento,
				       opcion      : opcion,
				       check       : check,
				       enc         : enc,
				       tipoevento  : verano,
				       hora        : hora,
				       fecha       : fecha},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_evento").css("display", "none");
				$("#cont_final").css("display", "block");
				if(check == 1){
					var win = window.open(data.url, '_blank');
					win.focus();
				}
			}
		});
	});
}

function finalizarRegistro(){
	cons_index_serie  = 0;
	arrayParientes    = [];
	arrayPostulantes  = [];
	envioFormulario   = 0;
	indice_editar     = null;
}

function habilitarCampo(element1, element2){
	var val1 = $('#'+element1+' option:selected').val();
	setearInputAdm(element2,null);
	if(val1 != null && val1.length != 0){
		$('#'+element2).attr("disabled", false);
		$('.divInput').removeClass('is-disabled');
	} else {
		$('#'+element2).attr("disabled", true);
	}
}

function getSedesByNivel(nivel,sede){
	var valorNivel = $('#'+nivel+' option:selected').val();
	if(valorNivel != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/getSedesByNivel',
			data    : {valorNivel : valorNivel},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$('#'+sede).attr("disabled", false);
			setCombo(sede, data.comboSedes,"Sede de inter&eacute;s (*)");
			$('.selectButton').selectpicker('refresh');
		});
	}else{
		setCombo(sede, null,"Sede de inter&eacute;s");
		$('#'+sede).attr("disabled", true);
		$('.selectButton').selectpicker('refresh');
	}
}

function abrirModalCrearColegio(){
	setearInput("nombreColegioCrear",null);
	modal("modalRegistrarColegio");
}

function registrarColegio(){
	addLoadingButton('btnMRC');
	nombreColegio = $("#nombreColegioCrear").val();
	if(nombreColegio.length != 0){
		addLoadingButton('btnMRC');
		$.ajax({	
			type    : 'POST',
			'url'   : 'registro/registrarColegio',
			data    : {colegio  : nombreColegio},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				setCombo("selectColegioProcedencia", data.comboColegios, "Colegio de procedencia");
				setearCombo("selectColegioProcedencia", data.colegio);
				modal('modalRegistrarColegio');
				msj('success', data.msj, null);
			}else{
				msj('warning', data.msj, null);
			}
			stopLoadingButton('btnMRC');
		});
	}
}

function changeMaxlength(tipoDoc,nroDoc){
	var valorTipo = $('#'+tipoDoc+' option:selected').val();
	if(valorTipo == 1){
		$("#"+nroDoc).attr('maxlength','12');
	}else if (valorTipo == 2){
		$("#"+nroDoc).attr('maxlength','8');
	}
}

function getDatosEvento(){
	var idevento = $('#selectEvento option:selected').val();
	
	if(idevento.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'registro/getDatosEvento',
			data    : {idevento  : idevento},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#checkbox-group").css("display", "block");
				$("#check1").css("display", "none");
				$("#check1").removeClass("is-checked");
				$("#check3").removeClass("is-checked");
				$('#btnInscribir').attr("disabled", true);
				$('#titulo').parent().removeClass("is-disabled");
				$('#observacion').parent().removeClass("is-disabled");
				$('#fecha').parent().removeClass("is-disabled");
				$('#hora').parent().removeClass("is-disabled");
				setearInput("titulo", data.titulo,null,1);
				setearInput("observacion", data.observacion,null,1);
				
				if(data.verano == 0	){
					setearInput("fecha", data.fecha);
					setearInput("hora", data.hora);
					verano = 1;
					$('#iconFecha').attr("disabled", false);
					$('#iconhoraAgendar').attr("disabled", false);
				} else {
					setearInput("fecha", data.fecha,null,1);
					setearInput("hora", data.hora,null,1);
					verano = null;
					$('#iconFecha').attr("disabled", true);
					$('#iconhoraAgendar').attr("disabled", true);
				}
			} else {
				$("#check1").removeClass("is-checked");
				$("#check3").removeClass("is-checked");
				$('#btnInscribir').attr("disabled", true);
				$("#checkbox-group").css("display", "none");
				msj('warning', data.msj, null);
				$('#iconFecha').attr("disabled", true);
				$('#iconhoraAgendar').attr("disabled", true);
			}
		});
	} else {
		$("#check1").removeClass("is-checked");
		$("#check3").removeClass("is-checked");
		$("#checkbox-group").css("display", "none");
		$('#btnInscribir').attr("disabled", true);
		setearInput("titulo", null, null, 1);
		setearInput("observacion", null, null, 1);
		setearInput("fecha", null, null, 1);
		setearInput("hora", null, null, 1);
		verano = null;
	}
}

function nuevoFamiliar(){
	$("#tab-addperson-fam").css("display","none");
	$("#tab-datos-fam").css("display","block");
	$("#menuFamCrear").css("display","none");
	$("#menuFamSave").css("display","block");
	indice_editar = null;
	editarParientes = null;
	comprobarCampos = 1;
	$(".btnGuardarFamiliar").attr("disabled", true);
	$(".btnGuardarFamiliar").addClass("guardar");
	$('.chip-parientes').removeClass("active", true);
	if (idRolUsuario == null){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/retornarIndex',
			data    : {index : null},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.rol && data.rol != 41){
				idRolUsuario = data.rol;
			}
			limpiarFormParientes();
		});
	} else {
		limpiarFormParientes();
	}
}

function nuevoEstudiante(){
	$("#tab-addperson-est").css("display","none");
	$("#tab-datos-est").css("display","block");
	$("#menuEstCrear").css("display","none");
	$("#menuEstSave").css("display","block");
	comprobarCamposEst = 1;
	$("#btnGuardarEstudiante").attr("disabled", true);
	$("#btnGuardarEstudiante").addClass("guardar");
	$('.chip-postulantes').removeClass("active", true);
	if (idRolUsuario == null){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/retornarIndex',
			data    : {index : null},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.rol && data.rol != 41){
				idRolUsuario = data.rol;
			}
			limpiarFormPostulantes();
		});
	} else {
		limpiarFormPostulantes();
	}
}

function camposFam(){
	cantVal  = 0;
	cantElem = 0;
	if (idRolUsuario == null){
		if(comprobarCampos != null){
			$(".datoFam").each(function() {
			    if($(this).get(0).tagName != 'DIV'){
			    	if($(this).attr("id") == 'telefonoPariente' && $(this).val().trim().length != 0 && 
			    	   ($("#celularPariente").val().trim().length == 0)){
			    		cantVal = cantVal + 2;
			    	} else if($(this).attr("id") == 'celularPariente' && $(this).val().trim().length != 0 && 
					    	 ($("#telefonoPariente").val().trim().length == 0)){
			    		cantVal = cantVal + 2;
					} else if($(this).val().trim().length != 0){
			    		cantVal++;
			    	}
			    	cantElem = cantElem + 1;
			    }
			});
			if(cantElem == cantVal){
				$(".btnGuardarFamiliar").attr("disabled", false);
				$(".btnGuardarFamiliar").removeClass("guardar");
			} else {
				$(".btnGuardarFamiliar").attr("disabled", true);
				$(".btnGuardarFamiliar").addClass("guardar");
			}
		}
	} else {
		$(".btnGuardarFamiliar").attr("disabled", false);
		$(".btnGuardarFamiliar").removeClass("guardar");
	}
}

function camposPos(){
	cantVal  = 0;
	cantElem = 0;
	if (idRolUsuario == null){
		if(comprobarCamposEst != null){
			$(".datoEst").each(function() {
			    if($(this).get(0).tagName != 'DIV'){
			    	if($(this).attr("id") != 'selectProceso' && $(this).val().trim().length != 0){
			    		cantVal++;
			    	} else if($(this).attr("id") == 'selectProceso' && $(this).val() != null){
			    		cantVal++;
			    	}
		    		cantElem++;
			    }
			});
			if(cantElem == cantVal){
				$("#btnGuardarEstudiante").attr("disabled", false);
				$("#btnGuardarEstudiante").removeClass("guardar");
			} else {
				$("#btnGuardarEstudiante").attr("disabled", true);
				$("#btnGuardarEstudiante").addClass("guardar");
			}
		}
	} else {
		$("#btnGuardarEstudiante").attr("disabled", false);
		$("#btnGuardarEstudiante").removeClass("guardar");
	}
}

function activeDesactiveSearch(){
	var namePariente = $("#buscarParientes").val();
	if($.trim(namePariente).length >= 3){
		$('#btnBuscar').attr('disabled', false);
		$('#btnBuscar').addClass('mdl-button--raised');
	} else {
		$("#cont_parientes_matricula").html(null);
		$('#btnBuscar').attr('disabled', true);
		$('#btnBuscar').removeClass('mdl-button--raised');
	}
}

function busquedaFamilias(){
	Pace.restart();
	Pace.track(function(){
		var namePariente = $("#buscarParientes").val();
		if(namePariente.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'registro/buscarFamilias',
				data    : {nombre : namePariente},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				$("#cont_parientes_matricula").html(data.tablaParientes);
				$("#tablaParientesBusqueda").bootstrapTable({});
			});
		}
	});
}

function verFamiliares(codFamiliar){
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/verFamiliaresCodFamiliar',
			data    : {codFamiliar : codFamiliar},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#cont_tabla_familiares_by_CodFam").html(data.tablaFamiliares);
			$("#tablaFamiliaresByCodFam").bootstrapTable({});
			abrirCerrarModal("modalVistaFamiliares");
		});
	});
}

function abrirModalConfirmAsignarFamilia(cod, nombre){
	cons_cod_familia = cod;
	$("#nombreFamiliaAsignar").text(nombre);
	abrirCerrarModal("modalConfirmAsignarFamilia");
}

function agregarParientes(){
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			type    : 'POST',
			'url'   : 'registro/agregarParientesMatricula',
			data    : {codigofam : cons_cod_familia},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				nuevoFamiliar();
				arrayParientes = [];
				arrayParientes = JSON.parse(data.parientes);

				if(arrayParientes.length > 0){
					$("#li1").addClass("complete");
					createButton(arrayParientes,1,null,null);
					cons_index_serie = arrayParientes.length;
					indice_editar = null;
					editarParientes = null;
					abrirCerrarModal("modalConfirmAsignarFamilia");
					abrirCerrarModal("modalParientes");
				}
				//AGREGAR TODOS LOS PARIENTES
//				arrayParientes.push(obj);
				// CREAR CHIPS
			} else {
				mostrarNotificacion('success', data.msj, null);
			}
			
			
			
//			$("#cont_tabla_familiares_by_CodFam").html(data.tablaFamiliares);
//			$("#tablaFamiliaresByCodFam").bootstrapTable({});
//			abrirCerrarModal("modalVistaFamiliares");
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

function setearInputAdm(idInput, val, attr){
	if(!val){
		val = null;
	}
	$("#"+idInput).val(val);
	if($('#'+idInput).attr("attr-obli") == attr || $('#'+idInput).attr("attr-obli") == '12'){
		if(val != null && val != ""){
			$("#"+idInput).parent().addClass("is-dirty");
			$("#"+idInput).parent().parent().removeClass("is-invalid");
		} else {
			$("#"+idInput).parent().removeClass("is-dirty");
			$("#"+idInput).parent().parent().addClass("is-invalid");
		}
	}
}

function setearComboAdm(idCombo, val, attr){
	$("#"+idCombo).val(val);
	$("#"+idCombo).selectpicker('render');
	if($('#'+idCombo).attr("attr-obli") == attr || $('#'+idCombo).attr("attr-obli") == '12'){
		if(val != null && val != ""){
			$("#"+idCombo).parent().addClass("is-dirty");
			$("#"+idCombo).parent().parent().parent().removeClass("is-invalid");
		} else {
			$("#"+idCombo).parent().removeClass("is-dirty");
			$("#"+idCombo).parent().parent().parent().addClass("is-invalid");
		}
	}
}