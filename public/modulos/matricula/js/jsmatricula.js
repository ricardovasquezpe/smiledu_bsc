var cons_idpariente          = null;
var cons_idpostulante        = null;
var cons_cole                = null;
var cons_estudianteRatificar = null;
var cons_inicio1 = null;
var cons_inicio2 = null;
function init(){
	$('#rootwizard1 ul li').css('pointer-events', 'none');
	$('#rootwizard2 ul li').css('pointer-events', 'none');
	$('#li1').removeAttr('style');
	$('#li1Pos').removeAttr('style');
	initButtonCalendarDays('fechaNacPariente');
	initButtonCalendarDays('fechaNacPostulante');
	initLimitInputs('encargadoContacto', 'evacuacionContacto');
	initMaskInputs('fechaNacPariente', 'fechaNacPostulante');
	componentHandler.upgradeAllRegistered();	
}

function verificacionCombosInputs(wizard){
	if(!wizard){
		wizard = null;
		$("#li2").removeClass("complete");
		$("#li3").removeClass("complete");
		$("#li4").removeClass("complete");
	}
	var countCampos1 = camposObligatoriosStep1();
	if(countCampos1 == 20){
		$("#li1").addClass("complete");
		$('#li2').removeAttr('style');
		$("#step2").attr('onclick','stepWizardPar(2)');
		var countCampos2 = camposObligatoriosStep2();
		if(countCampos2 == 5){
			if(wizard == 'wizard2'){
				$("#li2").addClass("complete");
			}
			$('#li3').removeAttr('style');
			$("#step3").attr('onclick','stepWizardPar(3)');
			var countCampos3 = camposObligatoriosStep3();
			if((countCampos3 == 4 && $("#selectColegioEgreso").val() != 3) || (countCampos3 == 3 && $("#selectColegioEgreso").val() == 3)){
				if(wizard == 'wizard3'){
					$("#li3").addClass("complete");
				}
				$("#step4").attr('onclick','stepWizardPar(4)');
				$('#li4').removeAttr('style');
				var countCampos4 = camposObligatoriosStep4();
				var valSit = $("#selectSituacionLaboralRegFam").val();
				if((countCampos4 == 5 && (valSit != 2 && valSit != 3)) || (countCampos4 == 1 && (valSit == 2 || valSit == 3))){
					if(wizard == 'wizard4'){
						$("#li4").addClass("complete");
					}
					$("#chip"+cons_idpariente).addClass("complete");
				} else {
					$("#li4").removeClass("complete");
					$("#chip"+cons_idpariente).removeClass("complete");
				}
			} else {
				$("#step4").attr('onclick','');
				$("#li3").removeClass("complete");
				$("#li4").removeClass("complete");
				$('#li4').css('pointer-events', 'none');
			}
		} else {
			$("#step3").attr('onclick','');
			$("#step4").attr('onclick','');
			$("#li2").removeClass("complete");
			$("#li3").removeClass("complete");
			$("#li4").removeClass("complete");
			$('#li3').css('pointer-events', 'none');
			$('#li4').css('pointer-events', 'none');
		}
	} else {
		$("#li1").removeClass("complete");
		$("#li2").removeClass("complete");
		$("#li3").removeClass("complete");
		$("#li4").removeClass("complete");
		$("#step2").attr('onclick','');
		$("#step3").attr('onclick','');
		$("#step4").attr('onclick','');
		$('#li2').css('pointer-events', 'none');
		$('#li3').css('pointer-events', 'none');
		$('#li4').css('pointer-events', 'none');
	}
}

function stepMatricula(element){
	pintarTab($(element));
	if($(element).attr('href') == "#tab-1"){
		if(cons_inicio1 == null){
			cons_inicio1 = 1;
			if(cons_idpariente != 0){
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				    $('.selectButtonFam').selectpicker('mobile');
				} else {
					$('.selectButtonFam').selectpicker();
				}
				verDatosFamiliar(cons_idpariente,2);
			}
		}
	} else if ($(element).attr('href') == "#tab-2"){
		if(cons_inicio2 == null){
			cons_inicio2 = 1;
			if(cons_idpostulante != 0){
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				    $('.selectButtonPos').selectpicker('mobile');
				} else {
					$('.selectButtonPos').selectpicker();
				}
				verDatosPostulante(cons_idpostulante,2);
			}
		}
	}
}

function pintarTab(element){
	$(".mdl-layout__tab").removeClass("is-active");
	$(element).addClass("is-active");
}

function changeTipoDoc(element1, element2,tipo){
	var val1 = $('#'+element1+' option:selected').val();
	if(val1.length != 0){
		$('#'+element2).attr("disabled", false);
		if(val1 == 1){
			if(tipo == 1){
				setearInputFam(element2,  $("#"+element2).val(), $("#"+element2).val());
			} else {
				setearInputFam(element2,  null, null);
			}
			$("#"+element2).attr('maxlength','12');
		} else if (val1 == 2){
			if(tipo == 1){
				setearInputFam(element2,  $("#"+element2).val(), $("#"+element2).val());
			} else {
				setearInputFam(element2,  null, null);
			}
			$("#"+element2).attr('maxlength','8');
		}
		if($("#"+element2).val() == null){
			$("#"+element2).parent().parent().addClass("is-invalid");
		}
	} else {
		setearInputFam(element2,  null, null, 1);
	}
}
function changePais(tipo){
	var valorCombo = $("#selectPaisPos").val();
	if(valorCombo == 173){
		if(tipo == 1){
			setearComboFam("selectDepartamentoPostulante", $("#selectDepartamentoPostulante").val(), $("#selectDepartamentoPostulante").val());
			setearComboFam("selectProvinciaPostulante", $("#selectProvinciaPostulante").val(), $("#selectProvinciaPostulante").val());
			setearComboFam("selectDistritoPostulante", $("#selectDistritoPostulante").val(), $("#selectDistritoPostulante").val());
		} else {
			setearComboFam("selectDepartamentoPostulante", null, null);
			setearComboFam("selectProvinciaPostulante", null, null);
			setearComboFam("selectDistritoPostulante", null, null);
		}
	} else {
		setCombo("selectProvinciaPostulante", null);
		setCombo("selectDistritoPostulante", null);
		setearComboFam("selectDepartamentoPostulante", null, null, 1);
		setearComboFam("selectProvinciaPostulante", null, null, 1);
		setearComboFam("selectDistritoPostulante", null, null, 1);
	}
}

function onChangeCampo(wizard ,campo, enc, id){
	Pace.restart();
	Pace.track(function(){
		var countCampos1 = camposObligatoriosStep1();
		var countCampos2 = camposObligatoriosStep2();
		var countCampos3 = camposObligatoriosStep3();
		var countCampos4 = camposObligatoriosStep4();
		var valor = $("#"+id).val();
		if(id.length != 0){	
			initUpdateButton();
			$.ajax({
				type    : 'POST',
				'url'   : 'c_matricula/onChangeCampo',
				data    : {campo       : campo,
				           enc         : enc,
				           valor       : valor,
				           idpariente  : cons_idpariente},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					mostrarNotificacion('success', data.msj, null);
					$("#"+id).attr("val-previo", valor);
					if(id == 'departamentoFam'){
						$("#provinciaFam").attr("val-previo", null);
						$("#distritoFam").attr("val-previo", null);
						$("#provinciaFam").parent().parent().parent().addClass("is-invalid");
						$("#distritoFam").parent().parent().parent().addClass("is-invalid");
					}
					if(id == 'provinciaFam'){
						$("#distritoFam").attr("val-previo", null);
						$("#distritoFam").parent().parent().parent().addClass("is-invalid");
					}
					if(id == 'selectColegioEgreso'){
						$("#yearEgresoRegFam").attr("val-previo", null);
						if($("#selectColegioEgreso").val() != 3){
							$("#yearEgresoRegFam").parent().parent().addClass("is-invalid");
						}
					}
					if(id == 'selectSituacionLaboralRegFam'){
						$("#ocupacionRegFam").attr("val-previo", null);
						$("#centroTrabajoRegFam").attr("val-previo", null);
						$("#direccionTrabajoRegFam").attr("val-previo", null);
						$("#cargoRegFam").attr("val-previo", null);
						if($("#selectSituacionLaboralRegFam").val() != 2 && $("#selectSituacionLaboralRegFam").val() != 3){
							$("#ocupacionRegFam").parent().parent().addClass("is-invalid");
							$("#centroTrabajoRegFam").parent().parent().addClass("is-invalid");
							$("#direccionTrabajoRegFam").parent().parent().addClass("is-invalid");
							$("#cargoRegFam").parent().parent().addClass("is-invalid");
						}
					}
					verificacionCombosInputs(wizard);
					$("#"+id).parent().parent().parent().removeClass("is-invalid");
					$("#"+id).parent().parent().removeClass("is-invalid");
				} else if (data.error == 1){
					var disabled = 1;
					if(id == 'departamentoFam'){
						setearComboFam('provinciaFam', $("#provinciaFam").attr("val-previo"));
						setearComboFam('distritoFam', $("#distritoFam").attr("val-previo"));
					}
					if(id == 'provinciaFam'){
						setearComboFam('distritoFam', $("#distritoFam").attr("val-previo"));
					}
					if(id == 'selectTipoDocumento'){
						setearInputFam('nroDocumento', $("#nroDocumento").attr("val-previo"), $("#nroDocumento").attr("val-previo"));
					}
					if(id == 'selectColegioEgreso'){
						var valPrevio = $("#selectColegioEgreso").attr("val-previo");
						if(valPrevio != 3){
							disabled = null;
						}
						setearInputFam('yearEgresoRegFam', $("#yearEgresoRegFam").attr("val-previo"), $("#yearEgresoRegFam").attr("val-previo"),disabled);
					}
					if(id == 'selectSituacionLaboralRegFam'){
						var valPrevio = $("#selectSituacionLaboralRegFam").attr("val-previo");
						if(valPrevio != 2 && valPrevio != 3){
							disabled = null;
						}
						setearInputFam("ocupacionRegFam",        $("#ocupacionRegFam").attr("val-previo"), $("#ocupacionRegFam").attr("val-previo"), disabled, 'dato4');
						setearInputFam("centroTrabajoRegFam",    $("#centroTrabajoRegFam").attr("val-previo"), $("#centroTrabajoRegFam").attr("val-previo"), disabled, 'dato4');
						setearInputFam("direccionTrabajoRegFam", $("#direccionTrabajoRegFam").attr("val-previo"), $("#direccionTrabajoRegFam").attr("val-previo"), disabled, 'dato4');
						setearInputFam("cargoRegFam",            $("#cargoRegFam").attr("val-previo"), $("#cargoRegFam").attr("val-previo"), disabled, 'dato4');
					}
					
					setearInputFam(id, $("#"+id).attr("val-previo"));
					setearComboFam(id, $("#"+id).attr("val-previo"));
					mostrarNotificacion('success', data.msj, null);
				}				
			});
			stopUpdateButton('btn-update-data');
		}
	});
}

function camposObligatoriosStep1(){
	var camposObligatorios = 0;
	if($("#selectParentesco").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#apellidoPaternoPariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#apellidoMaternoPariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#nombrePariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectTipoDocumento").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#nroDocumento").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectApoderado").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectResponsableEconomico").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectVive").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectSexoPariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#fechaNacPariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectPais").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#telefonoPariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#celularPariente").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectMovilDatos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectSO").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#correoPersona").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectReligion").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectIdioma").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectEstadoCivil").val().length >= 1) {
		camposObligatorios++;
	}
	return camposObligatorios;
}

function camposObligatoriosStep2(){
	var camposObligatorios = 0;
	if($("#departamentoFam").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#provinciaFam").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#distritoFam").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#direccion").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#referencia_domicilio").val().length >= 1) {
		camposObligatorios++;
	}
	return camposObligatorios;
}

function camposObligatoriosStep3(){
	var camposObligatorios = 0;
	if($("#nivelInstrFam").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectColegioEgreso").val().length >= 1) {
		camposObligatorios++;
		if($("#selectColegioEgreso").val() != 3){
			if($("#yearEgresoRegFam").val().length >= 1) {
				camposObligatorios++;
			}
		}
	}
	if($("#selectNivelDominioIngles").val().length >= 1) {
		camposObligatorios++;
	}
	return camposObligatorios;
}

function camposObligatoriosStep4(){
	var camposObligatorios = 0;
	if($("#selectSituacionLaboralRegFam").val().length >= 1) {
		camposObligatorios++;
		
		if($("#selectSituacionLaboralRegFam").val() != 2 && $("#selectSituacionLaboralRegFam").val() != 3){
			if($("#ocupacionRegFam").val().length >= 1) {
				camposObligatorios++;
			}
			if($("#cargoRegFam").val().length >= 1) {
				camposObligatorios++;
			}
			if($("#centroTrabajoRegFam").val().length >= 1) {
				camposObligatorios++;
			}
			if($("#direccionTrabajoRegFam").val().length >= 1) {
				camposObligatorios++;
			}
		}
	}
	return camposObligatorios;
}

function getProvinciaPorDepartamento(idcomboDep, idComboProv, idComboDist, tipo){
	var valorDep = $("#"+idcomboDep).val();
	if(valorDep != null && valorDep.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_matricula/getUbigeoByTipo',
			data    : {idubigeo : valorDep,
					   tipo     : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboProv, data.comboUbigeo, "Provincia actual");
			setCombo(idComboDist, null, "Distrito actual");
		});
	}else{
		//setCombo(idComboProv, null, "Provincia actual");
		//setCombo(idComboDist, null, "Distrito actual");
	}
}

function getDistritoPorProvincia(idcomboDep, idComboProv, idComboDist, tipo){
	var valorDep  = $("#"+idcomboDep).val();
	var valorProv = $("#"+idComboProv).val();
	if(valorProv != null && valorProv.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_matricula/getUbigeoByTipo',
			data    : {idubigeo  : valorDep,
				       idubigeo1 : valorProv,
					   tipo      : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboDist, data.comboUbigeo, "Distrito actual");
		});
	}else{
		//setCombo(idComboDist, null, "Distrito actual");
	}
}

function abrirModalCrearColegio(opc){
	setearInputFam("nombreColegioCrear",null);
	modal("modalRegistrarColegio");
	cons_cole = opc;
}

function registrarColegio(opc){
	Pace.restart();
	Pace.track(function(){
		nombreColegio = $("#nombreColegioCrear").val();
		if(nombreColegio.length != 0){
			$.ajax({	
				type    : 'POST',
				'url'   : 'c_matricula/registrarColegio',
				data    : {colegio  : nombreColegio},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					if(cons_cole == 1){
						setCombo("selectColegioEgreso", data.comboColegios, "&iquest;En qu&eacute; colegio egresaste?");
						setearComboFam("selectColegioEgreso", data.colegio);
					} else if(cons_cole == 2){
						setCombo("selectColegioProcedencia", data.comboColegios, "Centro educativo de procedencia");
						setearComboFam("selectColegioProcedencia", data.colegio);
					}
					modal('modalRegistrarColegio');
					msj('success', data.msj, null);
				}else{
					msj('warning', data.msj, null);
				}
			});
		}
	});
}

function changeSituacionLaboral(tipo){
	var valorCombo = $("#selectSituacionLaboralRegFam").val();

	if(valorCombo == 1 || valorCombo == 4){
		if(tipo == 1){
			setearInputFam("ocupacionRegFam",        $("#ocupacionRegFam").val(), $("#ocupacionRegFam").val(), null, 'dato4');
			setearInputFam("centroTrabajoRegFam",    $("#centroTrabajoRegFam").val(), $("#centroTrabajoRegFam").val(), null, 'dato4');
			setearInputFam("direccionTrabajoRegFam", $("#direccionTrabajoRegFam").val(), $("#direccionTrabajoRegFam").val(), null, 'dato4');
			setearInputFam("cargoRegFam",            $("#cargoRegFam").val(), $("#cargoRegFam").val(), null, 'dato4');
		} else {
			setearInputFam("ocupacionRegFam",        null, null, null, 'dato4');
			setearInputFam("centroTrabajoRegFam",    null, null, null, 'dato4');
			setearInputFam("direccionTrabajoRegFam", null, null, null, 'dato4');
			setearInputFam("cargoRegFam",            null, null, null, 'dato4');
		}
	} else {
		setearInputFam("ocupacionRegFam",        null, null, 1);
		setearInputFam("centroTrabajoRegFam",    null, null, 1);
		setearInputFam("direccionTrabajoRegFam", null, null, 1);
		setearInputFam("cargoRegFam",            null, null, 1);
	}
}

function onChangeCampoPostulante(wizard, campo, enc, id){
	Pace.restart();
	Pace.track(function(){
		var valor = $("#"+id).val();
		if(id.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_matricula/onChangeCampoPostulante',
				data    : {campo         : campo,
				           enc           : enc,
				           valor         : valor,
				           idpostulante  : cons_idpostulante},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					$("#"+id).attr("val-previo", valor);
					if(id == 'selectPaisPos'){
						$("#selectDepartamentoPostulante").attr("val-previo", null);
						$("#selectProvinciaPostulante").attr("val-previo", null);
						$("#selectDistritoPostulante").attr("val-previo", null);
					}
					if(id == 'selectDepartamentoPostulante'){
						$("#selectProvinciaPostulante").attr("val-previo", null);
						$("#selectDistritoPostulante").attr("val-previo", null);
					}
					if(id == 'selectProvinciaPostulante'){
						$("#selectDistritoPostulante").attr("val-previo", null);
					}
					if(id == 'selectTieneAlergia'){
						$("#alergiasObs").attr("val-previo", null);
					}
					if(id == 'selectTipoDocumentoPos'){
						$("#nroDocumentoPos").attr("val-previo", null);
					}
					$("#"+id).parent().parent().parent().removeClass("is-invalid");
					$("#"+id).parent().parent().removeClass("is-invalid");
					verificacionCombosInputsPostulantes(wizard);
					mostrarNotificacion('success', data.msj, null);
				} else if (data.error == 1){
					setearInputFam(id, $("#"+id).attr("val-previo"));
					setearComboFam(id, $("#"+id).attr("val-previo"));
					if(id == 'selectPaisPos'){
						setearComboFam("selectDepartamentoPostulante", $("#selectDepartamentoPostulante").attr("val-previo"), $("#selectDepartamentoPostulante").attr("val-previo"));
						setearComboFam("selectProvinciaPostulante", $("#selectProvinciaPostulante").attr("val-previo"), $("#selectProvinciaPostulante").attr("val-previo"));
						setearComboFam("selectDistritoPostulante", $("#selectDistritoPostulante").attr("val-previo"), $("#selectDistritoPostulante").attr("val-previo"));
						changePais(1);
					}
					if(id == 'selectDepartamentoPostulante'){
						setearComboFam('selectProvinciaPostulante', $("#selectProvinciaPostulante").attr("val-previo"), $("#selectProvinciaPostulante").attr("val-previo"));
						setearComboFam('selectDistritoPostulante', $("#selectDistritoPostulante").attr("val-previo"), $("#selectDistritoPostulante").attr("val-previo"));
					}
					if(id == 'selectProvinciaPostulante'){
						setearComboFam('selectDistritoPostulante', $("#selectDistritoPostulante").attr("val-previo"), $("#selectDistritoPostulante").attr("val-previo"));
					}
					if(id == 'selectTipoDocumentoPos'){
						setearInputFam('nroDocumentoPos', $("#nroDocumentoPos").attr("val-previo"), $("#nroDocumentoPos").attr("val-previo"));
					}
					if(id == 'selectTieneAlergia'){
						setearInputFam('alergiasObs', $("#alergiasObs").attr("val-previo"), $("#alergiasObs").attr("val-previo"));
						changeTieneAlergia();
					}
					
					mostrarNotificacion('success', data.msj, null);
				}
			});
		}
	});
}

function verDatosFamiliar(idfamiliar,tipo){
	Pace.restart();
	Pace.track(function(){
		if(idfamiliar.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_matricula/verDatosFamiliar',
				data    : {idfamiliar	     :	 idfamiliar,
					       cons_idpariente   :   cons_idpariente,
				           tipo              :   tipo},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					if(tipo == 2){
						$("#cont_parientes").html(data.parientes);
					}
					$("#progressBar").css("width", "0%");
					firstPaneParientes();
					cons_idpariente = data.familiar;
					$('.chip-parientes').removeClass("active", true);
					$('#chip'+data.familiar).addClass("active", true);
					$("#nroDocumento").attr("val-previo", null);
					$("#provinciaFam").attr("val-previo", null);
					$("#distritoFam").attr("val-previo", null);
					$("#yearEgresoRegFam").attr("val-previo", null);
					$("#ocupacionRegFam").attr("val-previo", null);
					$("#cargoRegFam").attr("val-previo", null);
					$("#centroTrabajoRegFam").attr("val-previo", null);
					$("#direccionTrabajoRegFam").attr("val-previo", null);
					
					setearComboFam('selectParentesco',              data.parentesco, data.parentesco, 1);
		            setearComboFam('selectTipoDocumento',           data.tipo_doc_identidad, data.tipo_doc_identidad);
		            setearComboFam('selectApoderado',               data.flg_apoderado, data.flg_apoderado);
		            setearComboFam('selectResponsableEconomico',    data.flg_resp_economico, data.flg_resp_economico);
		            setearComboFam('selectVive',                    data.flg_vive, data.flg_vive);
		            setearComboFam('selectSexoPariente',            data.sexo, data.sexo);
		            setearComboFam('selectPais',                    data.nacionalidad, data.nacionalidad);
		            setearComboFam('selectReligion',                data.religion, data.religion);
		            setearComboFam('selectIdioma',                  data.idioma, data.idioma);
		            setearComboFam('selectEstadoCivil',             data.estado_civil, data.estado_civil);
		            setearComboFam('selectMovilDatos',              data.movil_datos, data.movil_datos);
		            setearComboFam('selectSO',                      data.so_movil, data.so_movil);
		            
		            if((data.departamento.length) != 0 ){
			            setearComboFam('departamentoFam',               data.departamento, data.departamento);
			            getProvinciaPorDepartamento("departamentoFam", "provinciaFam", "distritoFam", 2);
			            if(data.provincia.length != 0 ){
				            setearComboFam('provinciaFam',                  data.provincia, data.provincia);
				            getDistritoPorProvincia("departamentoFam", "provinciaFam", "distritoFam", 3);
				            setearComboFam('distritoFam',                   data.distrito, data.distrito);
			            } else {
			            	setearComboFam('provinciaFam',    null, null);
			            	setearComboFam('distritoFam',    null, null);
			        		setCombo('distritoFam', null, "Distrito actual");
			            }
		            } else {
		            	setearComboFam('departamentoFam',    null, null);
		            	setearComboFam('provinciaFam',    null, null);
		            	setearComboFam('distritoFam',    null, null);
		        		setCombo('provinciaFam', null, "Provincia actual");
		        		setCombo('distritoFam', null, "Distrito actual");
		            }
		            setearComboFam('nivelInstrFam',                 data.nivel_instruccion, data.nivel_instruccion);
		            setearComboFam('selectColegioEgreso',           data.colegio_egreso, data.colegio_egreso);
		            setearComboFam('selectNivelDominioIngles',      data.flg_nivel_dom_ingles, data.flg_nivel_dom_ingles);
		            setearComboFam('selectSituacionLaboralRegFam',  data.situacion_laboral, data.situacion_laboral);

		            setearInputFam('nroDocumento',                  data.nro_doc_identidad, data.nro_doc_identidad);
		            setearInputFam('apellidoPaternoPariente',       data.ape_paterno, data.ape_paterno);
		            setearInputFam('apellidoMaternoPariente',       data.ape_materno, data.ape_materno);
		            setearInputFam('nombrePariente',                data.nombres, data.nombres);
		            setearInputFam('fechaNacPariente',              data.fec_naci, data.fec_naci);
		            setearInputFam('telefonoPariente',              data.telf_fijo, data.telf_fijo);
		            setearInputFam('celularPariente',               data.telf_celular, data.telf_celular);
		            setearInputFam('correoPersona',                 data.email1, data.email1);
		            setearInputFam('direccion',                     data.direccion_hogar, data.direccion_hogar);
		            setearInputFam('referencia_domicilio',          data.refer_domicilio, data.refer_domicilio);
		            setearInputFam('yearEgresoRegFam',              data.year_egreso, data.year_egreso);
		            setearInputFam('ocupacionRegFam',               data.ocupacion, data.ocupacion);
		            setearInputFam('cargoRegFam',                   data.cargo, data.cargo);
		            setearInputFam('centroTrabajoRegFam',           data.centro_trabajo, data.centro_trabajo);
		            setearInputFam('direccionTrabajoRegFam',        data.direccion_trabajo, data.direccion_trabajo);
		            
		            changeSituacionLaboral(1);
		            changeColegioEgreso(1);
		            changeTipoDoc('selectTipoDocumento','nroDocumento',1);
		            verificacionCombosInputs();
				} else if (data.error == 1){
					mostrarNotificacion('success', data.msj, null);
				}
			});
		}
	});
}

function verDatosPostulante(idpostulante,tipo){
	Pace.restart();
	Pace.track(function(){
		if(idpostulante.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_matricula/verDatosPostulante',
				data    : {idpostulante      :	idpostulante,
					       cons_idpostulante :  cons_idpostulante,
					       tipo              :  tipo},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					if(tipo == 2){
						$("#cont_postulantes").html(data.postulantes);
					}
					$("#cont_postulantes").css("display", "block");
					$("#progressBarPos").css("width", "0%");
					firstPanePostulantes();
					cons_idpostulante = data.postulante;
					$('.chip-postulantes').removeClass("active", true);
					$('#chip'+data.postulante).addClass("active", true);
					if(data.disabled.length == 0){
						disabled = null;
						$('#btnAgregarColegio').attr("disabled", false);
					} else {
						disabled = 1;
						$('#btnAgregarColegio').attr("disabled", true);
					}
					
					$("#nroDocumentoPos").attr("val-previo", null);
					$("#selectDepartamentoPostulante").attr("val-previo", null);
					$("#selectProvinciaPostulante").attr("val-previo", null);
					$("#selectDistritoPostulante").attr("val-previo", null);
					$("#alergiasObs").attr("val-previo", null);
					
					setearComboFam('selectLenguaMaterna',              data.lenguamaterna_pos, data.lenguamaterna_pos,disabled);
		            setearComboFam('selectPadresJuntos',               data.flg_padres_juntos, data.flg_padres_juntos,disabled);
					setearComboFam('selectSexoPostulante',             data.sexo_pos, data.sexo_pos,disabled);
		            setearComboFam('selectTipoDocumentoPos',           data.tipoDoc_pos, data.tipoDoc_pos,disabled);
		            setearComboFam('selectReligionPos',                data.religionPos, data.religionPos,disabled);
		            setearComboFam('selectPaisPos',                    data.paisPos, data.paisPos,disabled);
		            setearComboFam('selectColegioProcedencia',         data.colegio_proc, data.colegio_proc,disabled);
		            setearComboFam('selectNacRegistrado',              data.flg_nac_registrado, data.flg_nac_registrado,disabled);
		            setearComboFam('selectNacComplicacion',            data.nac_complicaciones, data.nac_complicaciones,disabled);
		            //setearComboFam('selectGradoNivel',                 data.gradoNivel, data.gradoNivel);
		            setearComboFam('selectDiscapacidad',               data.tipo_discapacidad, data.tipo_discapacidad,disabled);
		            setearComboFam('selectTieneAlergia',               data.flg_alergia, data.flg_alergia,disabled);
		            setearComboFam('selectPermisoDatos',               data.flg_permiso_datos, data.flg_permiso_datos,disabled);
		            setearComboFam('selectPermisoFotos',               data.flg_permiso_fotos, data.flg_permiso_fotos,disabled);
		            setearComboFam('tipoSangrePostulante',             data.tipo_sangre, data.tipo_sangre,disabled);
		            
		            if((data.departamentoPos.length) != 0 ){
		            	setearComboFam('selectDepartamentoPostulante',         data.departamentoPos, data.departamentoPos,disabled);
			            getProvinciaPorDepartamento("selectDepartamentoPostulante", "selectProvinciaPostulante", "selectDistritoPostulante", 2);
			            if(data.provinciaPos.length != 0 ){
			            	setearComboFam('selectProvinciaPostulante',        data.provinciaPos, data.provinciaPos,disabled);
				            getDistritoPorProvincia("selectDepartamentoPostulante", "selectProvinciaPostulante", "selectDistritoPostulante", 3);
				            setearComboFam('selectDistritoPostulante',         data.distritoPos, data.distritoPos,disabled);
			            } else {
			            	setearComboFam('selectProvinciaPostulante',    null, null,disabled);
			            	setearComboFam('selectDistritoPostulante',     null, null,disabled);
			        		setCombo('selectDistritoPostulante', null, "Distrito actual");
			            }
		            } else {
		            	setearComboFam('selectDepartamentoPostulante',    null, null,disabled);
		            	setearComboFam('selectProvinciaPostulante',       null, null,disabled);
		            	setearComboFam('selectDistritoPostulante',        null, null,disabled);
		        		setCombo('selectProvinciaPostulante', null, "Provincia actual");
		        		setCombo('selectDistritoPostulante', null, "Distrito actual");
		            }
		            
		            setearInputFam('nroDocumentoPos',           		   data.nroDoc_pos, data.nroDoc_pos,disabled);
		            setearInputFam('apellidoPaternoPostulante',           data.ape_paterno_pos, data.ape_paterno_pos,disabled);
		            setearInputFam('apellidoMaternoPostulante',           data.ape_materno_pos, data.ape_materno_pos,disabled);
		            setearInputFam('nombrePostulante',        			   data.nombres_pos, data.nombres_pos,disabled);
		            setearInputFam('totalHermanos',        			   data.total_hermano, data.total_hermano,disabled);
		            setearInputFam('lugarHermanos',       		           data.nro_hermano, data.nro_hermano,disabled);
		            setearInputFam('convivencia',       		           data.convivencia, data.convivencia,disabled);
		            setearInputFam('familiarFrecuente',       		       data.familiar_frecuente, data.familiar_frecuente,disabled);
		            setearInputFam('fechaNacPostulante',                  data.fec_naci_pos, data.fec_naci_pos,disabled);
		            setearInputFam('pesoPostulante',       		       data.peso, data.peso,disabled);
		            setearInputFam('tallaPostulante',       		       data.talla, data.talla,disabled);
		            setearInputFam('alergiasObs',                         data.alergia, data.alergia,disabled);
		            setearInputFam('evacuacionContacto',                  data.evacuacion_contacto, data.evacuacion_contacto,disabled);
		            setearInputFam('encargadoContacto',                   data.encargado_contacto, data.encargado_contacto,disabled);
		            
		            changePais(1);
		        	changeTieneAlergia();
		        	changeTipoDoc('selectTipoDocumentoPos','nroDocumentoPos',1);
		        	verificacionCombosInputsPostulantes();
				} else if (data.error == 1){
					if(data.noMsj == 2){
						mostrarNotificacion('success', data.msj, null);
					}
				}
			});
		}
	});
}

function firstPaneParientes(){
	$(".pane-par").removeClass('active');
	$("#tab1").addClass('active');

	$("#li1").addClass("active");
	$("#li2").removeClass("active");
	$("#li3").removeClass("active");
	$("#li4").removeClass("active");

	$("#step1").attr("aria-expanded","true");
	$("#step2").attr("aria-expanded","false");
	$("#step3").attr("aria-expanded","false");
	$("#step4").attr("aria-expanded","false");
}

function camposObligatoriosStep1Postulantes(){
	var camposObligatorios = 0;
	if($("#apellidoPaternoPostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#apellidoMaternoPostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#nombrePostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectSexoPostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectTipoDocumentoPos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#nroDocumentoPos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#totalHermanos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#lugarHermanos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectReligionPos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectPaisPos").val().length >= 1) {
		camposObligatorios++;
		if($("#selectPaisPos").val() == 173){
			if($("#selectDepartamentoPostulante").val().length >= 1) {
				camposObligatorios++;
			}
			if($("#selectProvinciaPostulante").val().length >= 1) {
				camposObligatorios++;
			}
			if($("#selectDistritoPostulante").val().length >= 1) {
				camposObligatorios++;
			}
		}
	}
	if($("#selectColegioProcedencia").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectLenguaMaterna").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectPadresJuntos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#convivencia").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#familiarFrecuente").val().length >= 1) {
		camposObligatorios++;
	}
	/*if($("#selectGradoNivel").val().length >= 1) {
		camposObligatorios++;
	}*/
	return camposObligatorios;
}

function camposObligatoriosStep2Postulantes(){
	var camposObligatorios = 0;
	if($("#fechaNacPostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectNacRegistrado").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectNacComplicacion").val().length >= 1) {
		camposObligatorios++;
	}
	return camposObligatorios;
}

function camposObligatoriosStep3Postulantes(){
	var camposObligatorios = 0;
	if($("#selectDiscapacidad").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#tipoSangrePostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#pesoPostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#tallaPostulante").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectTieneAlergia").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectTieneAlergia").val() != 2){
		if($("#alergiasObs").val().length >= 1) {
			camposObligatorios++;
		}
	}
	return camposObligatorios;
}

function camposObligatoriosStep4Postulantes(){
	var camposObligatorios = 0;
	if($("#evacuacionContacto").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#encargadoContacto").val().length >= 1) {
		camposObligatorios++;
	}
	return camposObligatorios;
}

function camposObligatoriosStep5Postulantes(){
	var camposObligatorios = 0;
	if($("#selectPermisoDatos").val().length >= 1) {
		camposObligatorios++;
	}
	if($("#selectPermisoFotos").val().length >= 1) {
		camposObligatorios++;
	}
	return camposObligatorios;
}

function verificacionCombosInputsPostulantes(wizard){
	if(!wizard){
		wizard = null;
		$("#li2Pos").removeClass("complete");
		$("#li3Pos").removeClass("complete");
		$("#li4Pos").removeClass("complete");
		$("#li5Pos").removeClass("complete");
		$("#li6Pos").removeClass("complete");
	}
	var countCampos1 = camposObligatoriosStep1Postulantes();
	if((countCampos1 == 18 && $("#selectPaisPos").val() == 173) || (countCampos1 == 15 && $("#selectPaisPos").val() != 173)){
		$("#li1Pos").addClass("complete");
		$('#li2Pos').removeAttr('style');
		$("#step2Pos").attr('onclick','stepWizardPos(2)');
		var countCampos2 = camposObligatoriosStep2Postulantes();
		if(countCampos2 == 3){
			if(wizard == 'wizard2pos'){
				$("#li2Pos").addClass("complete");
			}
			$('#li3Pos').removeAttr('style');
			$("#step3Pos").attr('onclick','stepWizardPos(3)');
			var countCampos3 = camposObligatoriosStep3Postulantes();
			if( ($("#selectTieneAlergia").val() == 1 && countCampos3 == 6) || ($("#selectTieneAlergia").val() == 2 && countCampos3 == 5)){
				if(wizard == 'wizard3pos'){
					$("#li3Pos").addClass("complete");
				}
				$("#step4Pos").attr('onclick','stepWizardPos(4)');
				$('#li4Pos').removeAttr('style');
				var countCampos4 = camposObligatoriosStep4Postulantes();
				if(countCampos4 == 2){
					if(wizard == 'wizard4pos'){
						$("#li4Pos").addClass("complete");
					}
					$("#step5Pos").attr('onclick','stepWizardPos(5)');
					$('#li5Pos').removeAttr('style');
					var countCampos5 = camposObligatoriosStep5Postulantes();
					if(countCampos5 == 2){
						if(wizard == 'wizard5pos'){
							$("#li5Pos").addClass("complete");
						}
						$('#li6Pos').removeAttr('style');
						$("#step6Pos").attr('onclick','stepWizardPos(6)');
					} else {
						$("#li5Pos").removeClass("complete");
						$("#li6Pos").removeClass("complete");
						$("#step6Pos").attr('onclick','');
						$('#li6Pos').css('pointer-events', 'none');
					}
				} else {
					$("#li4Pos").removeClass("complete");
					$("#li5Pos").removeClass("complete");
					$("#li6Pos").removeClass("complete");
					$("#step5Pos").attr('onclick','');
					$("#step6Pos").attr('onclick','');
					$('#li5Pos').css('pointer-events', 'none');
					$('#li6Pos').css('pointer-events', 'none');
				}
			} else {
				$("#li3Pos").removeClass("complete");
				$("#li4Pos").removeClass("complete");
				$("#li5Pos").removeClass("complete");
				$("#li6Pos").removeClass("complete");
				$("#step4Pos").attr('onclick','');
				$("#step5Pos").attr('onclick','');
				$("#step6Pos").attr('onclick','');
				$('#li4Pos').css('pointer-events', 'none');
				$('#li5Pos').css('pointer-events', 'none');
				$('#li6Pos').css('pointer-events', 'none');
			}
		} else {
			$("#li2Pos").removeClass("complete");
			$("#li3Pos").removeClass("complete");
			$("#li4Pos").removeClass("complete");
			$("#li5Pos").removeClass("complete");
			$("#li6Pos").removeClass("complete");
			$("#step3Pos").attr('onclick','');
			$("#step4Pos").attr('onclick','');
			$("#step5Pos").attr('onclick','');
			$("#step6Pos").attr('onclick','');
			$('#li3Pos').css('pointer-events', 'none');
			$('#li4Pos').css('pointer-events', 'none');
			$('#li5Pos').css('pointer-events', 'none');
			$('#li6Pos').css('pointer-events', 'none');
		}
	} else {
		$("#li1Pos").removeClass("complete");
		$("#li2Pos").removeClass("complete");
		$("#li3Pos").removeClass("complete");
		$("#li4Pos").removeClass("complete");
		$("#li5Pos").removeClass("complete");
		$("#li6Pos").removeClass("complete");
		$("#step2Pos").attr('onclick','');
		$("#step3Pos").attr('onclick','');
		$("#step4Pos").attr('onclick','');
		$("#step5Pos").attr('onclick','');
		$("#step6Pos").attr('onclick','');
		$('#li2Pos').css('pointer-events', 'none');
		$('#li3Pos').css('pointer-events', 'none');
		$('#li4Pos').css('pointer-events', 'none');
		$('#li5Pos').css('pointer-events', 'none');
		$('#li6Pos').css('pointer-events', 'none');
	}
}

function stepWizardPar(step){
	if(step == 1){
		$("#li1").nextAll().removeClass('complete');
	} else if(step == 2){
		$("#li2").prevAll().addClass('complete');
		$("#li2").nextAll().removeClass('complete');
		var countCampos2 = camposObligatoriosStep2();
		if(countCampos2 == 5){
			$("#li2").addClass("complete");
		}
	} else if(step == 3){
		$("#li3").prevAll().addClass('complete');
		$("#li3").nextAll().removeClass('complete');

		var countCampos3 = camposObligatoriosStep3();
		if((countCampos3 == 4 && $("#selectColegioEgreso").val() != 3) || (countCampos3 == 3 && $("#selectColegioEgreso").val() == 3)){
			$("#li3").addClass("complete");
		}
	} else if(step == 4){
		var countCampos4 = camposObligatoriosStep4();
		var valSit = $("#selectSituacionLaboralRegFam").val();
		if((countCampos4 == 5 && (valSit != 2 && valSit != 3)) || (countCampos4 == 1 && (valSit == 2 || valSit == 3))){
			$("#li4").addClass("complete");
		}
		$("#li4").prevAll().addClass('complete');
		$("#li4").nextAll().removeClass('complete');
		
	}
}

function stepWizardPos(step){
	if(step == 1){
		$("#li1Pos").nextAll().removeClass('complete');
	} else if(step == 2){
		$("#li2Pos").prevAll().addClass('complete');
		$("#li2Pos").nextAll().removeClass('complete');
		var countCampos2 = camposObligatoriosStep2Postulantes();
		if(countCampos2 == 3){
			$("#li2Pos").addClass("complete");
		}
	} else if(step == 3){
		$("#li3Pos").prevAll().addClass('complete');
		$("#li3Pos").nextAll().removeClass('complete');
		var countCampos3 = camposObligatoriosStep3Postulantes();
		if( ($("#selectTieneAlergia").val() == 1 && countCampos3 == 6) || ($("#selectTieneAlergia").val() == 2 && countCampos3 == 5)){
			$("#li3Pos").addClass("complete");
		}
	} else if(step == 4){
		$("#li4Pos").prevAll().addClass('complete');
		$("#li4Pos").nextAll().removeClass('complete');
		var countCampos4 = camposObligatoriosStep4Postulantes();
		if(countCampos4 == 2){
			$("#li4Pos").addClass("complete");
		}
	} else if(step == 5){
		$("#li5Pos").prevAll().addClass('complete');
		$("#li5Pos").nextAll().removeClass('complete');
		var countCampos5 = camposObligatoriosStep5Postulantes();
		if(countCampos5 == 2){
			$("#li5Pos").addClass("complete");
		}
	} else if(step == 6){
		$("#li6Pos").prevAll().addClass('complete');
		$("#li6Pos").nextAll().removeClass('complete');
		verCompromisosAlumno();
		//$("#li6Pos").addClass("complete");
	}
}

function firstPanePostulantes(){
	$(".pane-pos").removeClass('active');
	$("#tab1Pos").addClass('active');

	$("#li1Pos").addClass("active");
	$("#li2Pos").removeClass("active");
	$("#li3Pos").removeClass("active");
	$("#li4Pos").removeClass("active");
	$("#li5Pos").removeClass("active");
	$("#li6Pos").removeClass("active");

	$("#step1Pos").attr("aria-expanded","true");
	$("#step2Pos").attr("aria-expanded","false");
	$("#step3Pos").attr("aria-expanded","false");
	$("#step4Pos").attr("aria-expanded","false");
	$("#step5Pos").attr("aria-expanded","false");
	$("#step6Pos").attr("aria-expanded","false");
}

function changeTieneAlergia(){
	var valorCombo = $("#selectTieneAlergia").val();
	if(valorCombo == 1){
		$("#alergiasObs").parent().parent().css("display", "block");
	} else {
		setearInputFam("alergiasObs",        null, null);
		$("#alergiasObs").parent().parent().css("display", "none");
	}
}

function verCompromisosAlumno(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_matricula/mostrarCompromisosYearAlumnoGenerar",
			data: {idpostulante  : cons_idpostulante},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $("#cont_compromiso").html(data.table);
			    $('#tb_compromisoCalendarAlu-'+data.codigo).bootstrapTable({});
//			    if(data.confirmoDatos == 1){
//					$("#li6Pos").addClass("complete");
//		    		$('#btnGenerarCompromisos').attr("disabled", true);
//					$('#btnGenerarCompromisos').text("Compromisos aceptados");
		    	$("#li6Pos").removeClass("complete");
	    		$('#btnGenerarCompromisos').attr("disabled", false);
				$('#btnGenerarCompromisos').text("Generar compromisos");
		    	if(data.btn == 1 || data.btn == 2 || data.btn == 3){
		    		$('#btnGenerarCompromisos').attr("disabled", true);
		    		if(data.btn == 1){
		    			$('#btnGenerarCompromisos').text('RATIFICACI\u00D3N GENERADA');
		    		} else if(data.btn == 2){
		    			$('#btnGenerarCompromisos').text('MATR\u00CDCULA GENERADA');
		    		} else if(data.btn == 3){
		    			$('#btnGenerarCompromisos').text('COMPROMISOS GENERADOS');
		    		}
					
					if(data.complete == 1){
						$("#li6Pos").addClass("complete");
					}
		    	}
			} else if(data.error == 1) {
			    $("#cont_compromiso").html(null);
			    
			    mensaje = data.msj;
			    if(mensaje.length == 0){
			    	mensaje = 'A&uacute;n no se configur&aacute;n las cuotas, comunicarse con el colegio';
			    }
			    $("#cont_compromiso").html('<div class="text-center p-t-30">'+mensaje+'</div>');
				//$("#btnGenerarCompromisos").addClass("disabled");

				$('#btnGenerarCompromisos').attr("disabled", true);
				$('#btnGenerarCompromisos').text("No puede generar compromisos");
				$("#li6Pos").removeClass("complete");
				//$("#btnAlumnoDatosCompletos").css("display", "none");
			}
		});
	});	
}

function registroCompromisoAlumno(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_matricula/saveCompromisosAlu",
			data: {idpostulante  : cons_idpostulante,
		           idfamiliar    : cons_idpariente},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				//$("#btnGenerarCompromisos").addClass("disabled");;

				$('#btnGenerarCompromisos').attr("disabled", true);
				$('#btnGenerarCompromisos').text("Compromisos aceptados");
				//$("#btnAlumnoDatosCompletos").css("display", "inline-block");
				$("#li6Pos").addClass("complete");
			    $("#cont_compromiso").html(null);
			    $("#cont_compromiso").html(data.table);
			    $('#tb_compromisoCalendarAlu-'+data.codigo).bootstrapTable({});
			    
				verDatosPostulante(cons_idpostulante,2);
				
				$("#cont_alumnos_pincipal").html(data.tablaAlumnos);
				componentHandler.upgradeAllRegistered();
				
				mostrarNotificacion('warning' , data.msj,'');
				abrirCerrarModal('modalConfirmarDatos');
				
			} else if(data.error == 1) {
				mostrarNotificacion('warning' , data.msj,'');
			}
		});
	});	
}

function abrirModalConfirmarGenerarRatificacion(idestudiante,proceso){
	cons_estudianteRatificar = idestudiante;
//	Pace.restart();
//	Pace.track(function() {
//		$.ajax({
//			type    : 'POST',
//			'url'   : 'c_matricula/abrirModalConfirmarGenerarRatificacion',
//			data    : {idpostulante : cons_estudianteRatificar},
//			'async' : true
//		}).done(function(data){
//			data = JSON.parse(data);			
//			if(data.error == 0){
//				//document.getElementById('msjConfirmaRatificar').innerHTML = "Generar&aacute;s la ratificaci&oacute;n correspondiente a: "+data.gradoNivel+".";
//				abrirCerrarModal('modalConfirmarRatificacion');
//			} else {
//				mostrarNotificacion('warning' , data.msj,'');
//			}
//		});
//	});
	$('#msjMatricula').html("Para iniciar el proceso de "+proceso+" debes enviar la declaraci&oacute;n jurada al " +
			"colegio. Puedes descargarla desde aqu&iacute; o usar la que recibiste en la agenda de tu hijo/a.");
	
	abrirCerrarModal('modalConfirmarRatificacion');
}

function generarRatificacion1(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_matricula/generarCuotas",
			data: {idpostulante  : cons_idpostulante,
				   idfamiliar    : cons_idpariente},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#cont_alumnos_pincipal").html(data.tablaAlumnos);
				componentHandler.upgradeAllRegistered();
				stepWizardPos(6);
				$('#btnGenerarCompromisos').attr("disabled", true);
				$('#btnGenerarCompromisos').text("Compromisos aceptados");
				mostrarNotificacion('warning' , data.msj,'');
				abrirCerrarModal('modalConfirmarDatos');
				
				abrirCerrarModal('modalRatificacionCulminada');
			} else if(data.error == 1) {
				mostrarNotificacion('warning' , data.msj,'');
			}
		});
	});
}

function abrirModalCompromisos(idestudiante,tipo){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_matricula/mostrarCompromisosYearAlumno",
			data: {idpostulante  : idestudiante,
				   tipo          : tipo},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $("#calendarCompromisos").html(data.table);
			    $('#tb_compromisoCalendarAlu-'+data.codigo).bootstrapTable({});
				abrirCerrarModal('modalCompromisosEstudiante');
			} else if(data.error == 1) {
			    $("#cont_compromiso").html(null);
			}			
		});
	});	
}

function changeColegioEgreso(tipo){
	var valorColegio = $("#selectColegioEgreso").val();
	if (valorColegio == 1 || valorColegio == 2) {
		if(tipo == 1){
			setearInputFam("yearEgresoRegFam", $("#yearEgresoRegFam").val(), $("#yearEgresoRegFam").val(), null, 'dato5');
		} else {
			setearInputFam("yearEgresoRegFam", null, null, null, 'dato5');
		}
	} else {
		setearInputFam("yearEgresoRegFam", null, null, 1);
	}
}

function changePais(tipo){
	var valorCombo = $("#selectPaisPos").val();
	if(valorCombo == 173){
		if(tipo == 1){
			setearComboFam("selectDepartamentoPostulante", $("#selectDepartamentoPostulante").val(), $("#selectDepartamentoPostulante").val());
			setearComboFam("selectProvinciaPostulante", $("#selectProvinciaPostulante").val(), $("#selectProvinciaPostulante").val());
			setearComboFam("selectDistritoPostulante", $("#selectDistritoPostulante").val(), $("#selectDistritoPostulante").val());
		} else {
			setearComboFam("selectDepartamentoPostulante", null, null);
			setearComboFam("selectProvinciaPostulante", null, null);
			setearComboFam("selectDistritoPostulante", null, null);
		}
	} else {
		setCombo("selectProvinciaPostulante", null);
		setCombo("selectDistritoPostulante", null);
		setearComboFam("selectDepartamentoPostulante", null, null, 1);
		setearComboFam("selectProvinciaPostulante", null, null, 1);
		setearComboFam("selectDistritoPostulante", null, null, 1);
	}
}

function allCompromisos(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_matricula/allCompromisos",
			data: {idpostulante  : cons_idpostulante},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#textCompromisos').text("Este es el listado de los compromisos a generar antes de empezar el a\u00f1o lectivo "+data.year+", son los montos base si Ud tiene alg\u00fan tipo de descuento este va a figurar antes de ser generados.");
			    $("#compromisosCompleto").html(data.table);
			    $('#tb_compromisoCalendarAlu-'+data.codigo).bootstrapTable({});
				abrirCerrarModal('modalCompromisosCompleto');
			} else if(data.error == 1) {
				$('#textCompromisos').text("");
			    $("#compromisosCompleto").html(null);
			    $("#compromisosCompleto").html('<div class="text-center p-t-30">A&uacute;n no se configur&aacute;n las cuotas, comunicarse con el colegio</div>');
				abrirCerrarModal('modalCompromisosCompleto');

//				mostrarNotificacion('warning' , data.msj,'');
			}
		});
	});
}

function setearInputFam(idInput, val, previo, disabled, clase){
	if(!val){
		val = null;
	}
	if(!previo){
		previo = null;
	}
	if(!disabled){
		disabled = null;
	}
	if(!clase){
		clase = 'divInput'
	}
	$("#"+idInput).val(val);
	if(val != null && val != ""){
		$("#"+idInput).parent().addClass("is-dirty");
		$("#"+idInput).parent().parent().removeClass("is-invalid");
	} else {
		$("#"+idInput).parent().removeClass("is-dirty");
		$("#"+idInput).parent().parent().addClass("is-invalid");
	}
	if(previo != null){
		$("#"+idInput).attr("val-previo", previo);
	}
	if(disabled != null){
		$('#'+idInput).attr("disabled", true);
		$("#"+idInput).css('cursor', 'not-allowed');
		$("#"+idInput).parent().parent().removeClass("is-invalid");
	} else {
		$('#'+idInput).attr("disabled", false);
		$("#"+idInput).css('cursor', '');
	}
}

function setearComboFam(idCombo, val, previo, disabled){
	if(!previo){
		previo = null;
	}
	if(!disabled){
		disabled = null;
	}
	if(previo != null){
		$("#"+idCombo).attr("val-previo", previo);
	}
	$("#"+idCombo).val(val);
	$("#"+idCombo).selectpicker('render');

	if(val != null && val != ""){
		$("#"+idCombo).parent().addClass("is-dirty");
		$("#"+idCombo).parent().parent().parent().removeClass("is-invalid");
	} else {
		$("#"+idCombo).parent().removeClass("is-dirty");
		$("#"+idCombo).parent().parent().parent().addClass("is-invalid");
	}

	if(disabled != null){
		$("#"+idCombo).parent().parent().parent().removeClass("is-invalid");
		disableEnableCombo(idCombo, true);
	} else if (disabled == null){
		disableEnableCombo(idCombo, false);
	}
}

function abrirModalDeclaracionPDF(){
	var ruta = window.location.origin;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_matricula/abrirModalDeclaracionPDF",
			data: {idpostulante  : cons_estudianteRatificar},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $("#doc_declaracion").html('<iframe src="'+ruta+'/smiledu/public/modulos/matricula/files/'+data.doc+'#zoom=80"></iframe>');
				abrirCerrarModal('modalPDF');
			}
		});
	});
}
//window.location.href = '';