//0 = REGISTRAR, 1 = EDITAR
cons_accion_editarGuardar = 0;
cons_familiar_editar = null;
cons_familiar_desagsinar = null;
cons_cod_familia = null;
cons_id_documento = null;
idCargaInicialPagina = 0;
idCargaInicialPagina2 = 0;
campos = 0;

function init(){
//	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
//	    $('.selectButton').selectpicker('mobile');
//	} else {
//		$('.selectButton').selectpicker();
//	}
//	$("#cont_nombre_familia input, select, textarea").keypress(function(event) {
//		if (event.which == 13) {
//			event.preventDefault();
//			busquedaFamilias();
//		}
//	});
//	$("#cont_nombre_familiar input, select, textarea").keypress(function(event) {
//		if (event.which == 13) {
//			event.preventDefault();
//			busquedaFamiliares();
//		}
//	});
	$('.fixed-table-toolbar').addClass('mdl-card__menu'); 
	initButtonCalendarDays('fecNacPersona');
	initButtonCalendarDays('fecNacPersonaRegFam');
	initButtonCalendarDays('fechaDocumentoEdit');
	initMaskInputs('fecNacPersona', 'fecNacPersonaRegFam', 'fechaDocumentoEdit');
	initLimitInputs('observacion');
	initSearchTable();
	initButtonLoad( 'botonGuardarFamiliar','buttonGenerar', 'buttonDesignar');
    cambiarColorProgreso();
}

$( document ).ready(function() {	
	$('#menu .mfb-component__button--main').css('display', 'none');
	$('#menu .mfb-component__list').css('display', 'none');
});

$('.mdl-layout__tab[href="#tab-1"], .mdl-layout__tab[href="#tab-2"], .mdl-layout__tab[href="#tab-4"]').click(function(){
	/*var id = $(this).attr('href');
	var timeInit = (new Date()).getTime();
	tabLoader(id, timeInit)*/
	$('#menu .mfb-component__button--main').css('display', 'none');
	$('#menu .mfb-component__list').css('display', 'none');
});

$('.mdl-layout__tab[href="#tab-3"]').click(function(){
	/*var id = $(this).attr('href');
	var timeInit = (new Date()).getTime();
	tabLoader(id, timeInit)*/
	$('#menu .mfb-component__button--main').removeAttr('style');
	$('#menu .mfb-component__list').removeAttr('style');
});

function getProvinciaPorDepartamento(idcomboDep, idComboProv, idComboDist, tipo, valor, valor2){
	var valorDep = $("#"+idcomboDep).val();
	if(valorDep != null && valorDep.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_alumno/getUbigeoByTipo',
			data    : {idubigeo : valorDep,
					   tipo     : tipo},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboProv, data.comboUbigeo, "Provincia");
			setCombo(idComboDist, null, "Distrito");
			if(valor != null && valor.length != 0){
				setearSinOpciones(idComboProv, valor);
				getDistritoPorProvincia(idcomboDep, idComboProv, idComboDist, 3, valor2);
			}
			$('.selectButtonWiz').selectpicker('refresh');
		});
	}else{
		setCombo(idComboProv, null, "Provincia");
		setCombo(idComboDist, null, "Distrito");
	}
}

function getDistritoPorProvincia(idcomboDep, idComboProv, idComboDist, tipo, valor){
	var valorDep  = $("#"+idcomboDep).val();
	var valorProv = $("#"+idComboProv).val();
	if(valorProv != null && valorProv.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/getUbigeoByTipo',
			data    : {idubigeo  : valorDep,
				       idubigeo1 : valorProv,
					   tipo      : tipo},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboDist, data.comboUbigeo, "Distrito");
			if(valor != null && valor.length != 0){
				setearSinOpciones(idComboDist, valor);
			}
			$('.selectButtonWiz').selectpicker('refresh');
		});
	}else{
		setCombo(idComboDist, null, "Distrito");
	}
}

function abrirSelectFotoPersona(){
	$('#fotoPersona').trigger('click');
}

cons_val_foto_familair = null;
function abrirSelectFotoFamiliar(ifamiliar, nro){
	$('#fotoFamiliar').trigger('click'); 
	cons_familiar_editar = ifamiliar;
	cons_val_foto_familair = nro;
}

var sizeImagen      = null;
var imagenRecortada = null;
var extImagen       = null
$("#fotoPersona").change(function(e){
	Pace.restart();
	Pace.track(function() {
		var inputFileImage = document.getElementById("fotoPersona");
		var file = inputFileImage.files[0];
		if(file){
			file.convertToBase64(function(base64){
				extImagen = file.name.split('.').pop();
				if(extImagen == 'jpg'){
					extImagen = 'jpeg';
				}
				if(extImagen == 'png' || extImagen == 'jpg' || extImagen == 'jpeg' || extImagen == 'PNG'
				   || extImagen == 'JPG' || extImagen == 'JPEG'){
					$("#fotoPrueba").attr("src", base64);
					if($("#fotoPrueba").height() <= 5000 && $("#fotoPrueba").width() <= 5000){
						if(file.size/1024/1024 < 2){
							sizeImagen = file.size/1024/1024;
							$('.cropper-container.cropper-bg').remove();
							$('#fotoRecortarEstudiante').replaceWith('<img id="fotoRecortarEstudiante">');
							$("#fotoRecortarEstudiante").attr("src", base64);
							imagenRecortada = base64;
							initCropper('fotoRecortarEstudiante');
							abrirCerrarModal("modalEditarFotoEstudiante");
						}else{
							msj('success', "Seleccione una imagen de menos de 1MB", null);
						}
					}else{
						msj('success', "Seleccione una imagen mas peque\u00f1a", null);
					}
				}else{
					msj('success', "Seleccione un archivo de tipo .JPG o .JPEG o .PNG", null);
				}
		     });
		}
	});
});

$("#fotoFamiliar").change(function(e){
	Pace.restart();
	Pace.track(function() {
		var inputFileImage = document.getElementById("fotoFamiliar");
		var file = inputFileImage.files[0];
		if(file){
			file.convertToBase64(function(base64){
				extImagen = file.name.split('.').pop();
				if(extImagen == 'jpg'){
					extImagen = 'jpeg';
				}
				if(extImagen == 'png' || extImagen == 'jpg' || extImagen == 'jpeg' || extImagen == 'PNG'
				   || extImagen == 'JPG' || extImagen == 'JPEG'){
					$("#fotoPrueba").attr("src", base64);
					if($("#fotoPrueba").height() <= 5000 && $("#fotoPrueba").width() <= 5000){
						if(file.size/1024/1024 < 2){
							sizeImagen = file.size/1024/1024;
							$('.cropper-container.cropper-bg').remove();
							$('#fotoRecortarFamiliar').replaceWith('<img id="fotoRecortarFamiliar">');
							$("#fotoRecortarFamiliar").attr("src", base64);
							imagenRecortada = base64;
							initCropper('fotoRecortarFamiliar');
							abrirCerrarModal("modalEditarFotoFamiliar");
						}else{
							msj('success', "Seleccione una imagen de menos de 1MB", null);
						}
					}else{
						msj('success', "Seleccione una imagen mas peque\u00f1a", null);
					}
				}else{
					msj('success', "Seleccione un archivo de tipo .JPG o .JPEG o .PNG", null);
				}
		     });
		}
	});
});

function subirImagenRecortadaFamiliar(){
	Pace.restart();
	Pace.track(function() {
		recortarImagen(this.id, 'fotoRecortarFamiliar');
		var formData = new FormData();
		formData.append('foto', imagenRecortada);
		formData.append('ext', extImagen);
		formData.append('idfamiliar', cons_familiar_editar);
		$.ajax({
			 data    : formData,
			 'url'   : 'c_detalle_alumno/cambiarFotoFamiliar',
			 cache   : false,
			 contentType : false,
			 processData : false,
			 type : 'POST',
			 'async' : true	    
		}).done(function(data){
			try {
				data = JSON.parse(data);
				$("#fotoFamiliar"+cons_val_foto_familair).attr('src', imagenRecortada);
				abrirCerrarModal("modalEditarFotoFamiliar");
				msj('success', data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});
}

function subirImagenRecortadaEstudiante(){
	Pace.restart();
	Pace.track(function() {
		recortarImagen(this.id, 'fotoRecortarEstudiante');
		var formData = new FormData();
		formData.append('foto', imagenRecortada);
		formData.append('ext', extImagen);
		$.ajax({
			 data    : formData,
			 'url'   : 'c_detalle_alumno/cambiarFotoEstudiante',
			 cache   : false,
			 contentType : false,
			 processData : false,
			 type : 'POST',
			 'async' : true	    
		}).done(function(data){
			try {
				data = JSON.parse(data);
				if(data.error == 0){
					if(data.tab == 1){
						$(".tabEscondido").css("opacity", "1");
						$(".tabEscondido").css("pointer-events", "");
					}
					$("#fotoPersonaImg").attr('src', imagenRecortada);
					abrirCerrarModal("modalEditarFotoEstudiante");
				} else {
					msj('success', data.msj, null);
				}
			} catch(err) {
				location.reload();
			}
		});
	});
}

function onSaveCampo(element, abc, def, enc){
	var valor = $(element).val();
	$.ajax({	
		type    : 'POST',
		'url'   : 'c_detalle_alumno/updateCampoCambio',
		data    : {valor : valor,
			       abc   : abc,
			       def   : def,
			       enc   : enc},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			if(data.tab == 1 ){
				$(".tabEscondido").css("opacity", "1");
				$(".tabEscondido").css("pointer-events", "");
			}
			if(data.estado != null){
				$("#estadoPersona").addClass(data.estado);
				$(".mdl-tooltip").find("font").html("datos-incompletos");
				
				$("#cantidad_maxima_estudiantes").text(data.cantEstudiantes);
				$("#progreso").attr("aria-valuenow", data.porcentajeEstudiantes);
				$("#progreso").css("width", data.porcentajeEstudiantes+"%");
				cambiarColorProgreso();
			}
			$(element).attr("val-previo", valor);
			mostrarNotificacion('success', data.msj, null);
		}else if(data.error == 1){
			setearInput($(element).attr('id'), $(element).attr("val-previo"))
			mostrarNotificacion('warning', data.msj, null);
		}else if(data.error == 10){
			$("#modalSubirPaquete").find(".mdl-card__title-text").html("Agregar mas estudiantes");
			modal("modalSubirPaquete");
		}
	});
}

function abrirModalRegistrarColegio(){
	abrirCerrarModal('modalRegistrarColegio');
}

function buscarColegio(){
	nombreColegio = $("#nombreColegio").val();
	if(nombreColegio != ""){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_alumno/buscarColegio',
			data    : {nombreCole : nombreColegio},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#cont_tb_colegios").html(data.tablaColegios);
			$("#tablaColegios").bootstrapTable({});
		});
	}
}

function registrarColegio(){
	nombreColegio = $("#nombreColegio").val();
	if(nombreColegio.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/insertColegio',
			data    : {colegio  : nombreColegio},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				setCombo("coleProcedencia", data.comboColegios, "Colegio Procedencia");
				setCombo("coleEgresoRegFam", data.comboColegios, "Colegio");
				setearInput("nombreColegio",null);
				$("#cont_tb_colegios").html(null)
				abrirCerrarModal('modalRegistrarColegio');
				mostrarNotificacion('success', data.msj, null);
			}else{
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	}
}

function abrirConfirmDesagsinarFamiliar(idFamiliar, nombreFamiliar){
	cons_familiar_desagsinar = idFamiliar;
	$("#nombreDesagsinarFamiliar").text("&#191;Deseas desasignar "+nombreFamiliar+"?");
	abrirCerrarModal("modalConfirmDesagsinarFamiliar");
}

function desagsinarFamiliar(){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_alumno/desagsinarFamiliar',
		data    : {familiar : cons_familiar_desagsinar},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			busquedaFamilias();
			$('#cont_familiares').html(data.vistaFamiliares);
			componentHandler.upgradeAllRegistered();
			abrirCerrarModal("modalConfirmDesagsinarFamiliar");
			stopLoadingButton();
			mostrarNotificacion('success', data.msj, null);
		}else{
			stopLoadingButton();
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

function busquedaFamilias(){
	Pace.restart();
	Pace.track(function(){
		var nombreFamilia = $("#nombreFamilia").val();
		if(nombreFamilia.length != 0){
			$.ajax({	
				type    : 'POST',
				'url'   : 'c_detalle_alumno/buscarFamilias',
				data    : {nombre : nombreFamilia},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				$("#cont_tabla_familias_busqueda").html(data.tablaFamiliar);
				$("#tablaFamiliasBusqueda").bootstrapTable({});
			});
		}
	});	
}

function abrirModalAsignarFamiliar(){
	abrirCerrarModal('modalAsignarFamiliar');
}

function abrirModalAsignarFamilia(){
	Pace.restart();
	Pace.track(function(){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_alumno/abrirModalRegistrarFamiliar',
			data    : {},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				abrirCerrarModal('modalAsignarFamilia');
			} else {
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	});
}

function abrirModalRegistrarFamiliar(){
	$('#rootwizard1 ul li').css('pointer-events', 'none');
	$('#li1').removeAttr('style');
	$('.wizard-label').removeClass('complete');
	campos = 0;
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/abrirModalRegistrarFamiliar',
			data    : {},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				stopLoadingButton();
				$("#icon-regFam").css("display","block");
				limpiarModalRegFam();
				cons_accion_editarGuardar = 0;
				$("#tituloRegistrarEditarFamiliar").text("Registrar Familiar");
				abrirCerrarModal("modalRegistrarFamiliar");
			} else {
				stopLoadingButton();
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	});
}

function limpiarModalRegFam(){
	$(".progress-bar-primary").css("width", "0");
	$("#mensaje_reg_familia").css("display", "none");
	$("#cont_tb_familiar_encontrado").html(null);
	$(".cont_reg_familiar").css("display", "none");
	$("#botonGuardarFamiliar").prop("disabled", true);
	$('#btnAgregarColegio').attr("disabled", false);
	//CABECERAcont_tb_familiar_encontrado
		
	cons_cambio_1 = true;
	cons_cambio_2 = true;
	setearNullGroup("tipoDocRegFam","dniRegFam","respeconomicoRegFam","parentescoRegFam","apoderadoRegFam",
                       "nombrePersonaRegFam","APPaternoPersonaRegFam","APMaternoPersonaRegFam","viveRegFam",
                       "fecNacPersonaRegFam","paisRegFam","direccionRegFam","referenciaRegFam","departamentoRegFam",
                       "provinciaRegFam","distritoRegFam","telfFijoRegFam","telfCelularRegFam","idiomaRegFam",
                       "estadoCivilRegFam","exalumnoRegFam","yearEgresoRegFam","coleEgresoRegFam","correo1RegFam",
                       "correo2RegFam","religionRegFam","ocupacionRegFam","centroTrabajoRegFam","direccionTrabajoRegFam",
                       "departamentoTrabajoRegFam","provinciaTrabajoRegFam","distritoTrabajoRegFam",
                       "telefonoTrabajoRegFam","situacionLaboralRegFam","sueldoRegFam","cargoRegFam",
                       "ocupacionRegFam","centroTrabajoRegFam","direccionTrabajoRegFam","telefonoTrabajoRegFam",
                       "sueldoRegFam","cargoRegFam","departamentoTrabajoRegFam","provinciaTrabajoRegFam","distritoTrabajoRegFam");
	
	disEnabledInputComboGroup(["tipoDocRegFam","dniRegFam","respeconomicoRegFam","parentescoRegFam","apoderadoRegFam",
	                           "nombrePersonaRegFam","APPaternoPersonaRegFam","APMaternoPersonaRegFam","viveRegFam",
	                           "fecNacPersonaRegFam","paisRegFam","direccionRegFam","referenciaRegFam","departamentoRegFam",
	                           "provinciaRegFam","distritoRegFam","telfFijoRegFam","telfCelularRegFam","idiomaRegFam",
	                           "estadoCivilRegFam","exalumnoRegFam","yearEgresoRegFam","coleEgresoRegFam","correo1RegFam",
	                           "correo2RegFam","religionRegFam","ocupacionRegFam","centroTrabajoRegFam","direccionTrabajoRegFam",
	                           "departamentoTrabajoRegFam","provinciaTrabajoRegFam","distritoTrabajoRegFam",
	                           "telefonoTrabajoRegFam","situacionLaboralRegFam","sueldoRegFam","cargoRegFam",
	                           "ocupacionRegFam","centroTrabajoRegFam","direccionTrabajoRegFam","telefonoTrabajoRegFam",
	                           "sueldoRegFam","cargoRegFam","departamentoTrabajoRegFam","provinciaTrabajoRegFam","distritoTrabajoRegFam"],false);

	changePaisRegFam();
	changeVive();
	changeDoc();
	changeSituacionLaboral();
}

function changeDoc(tipo){
	var valorTipoDoc = $("#tipoDocRegFam").val();
	if(valorTipoDoc.length != 0){
		if(tipo == 1){
			setearInput("dniRegFam", $("#dniRegFam").val(), $("#dniRegFam").val());
		} else {
			setearInput("dniRegFam", "", "");
		}
	} else {
		setearInput("dniRegFam", "", "", 1);
	}
}

function validateFamiliarExiste(tipo){
	if(tipo == 1 && cons_accion_editarGuardar == 0){
		if($("#tipoDocRegFam").val() == 1){
			$("#dniRegFam").attr('maxlength', 12);
		}else{
			$("#dniRegFam").attr('maxlength', 8);
		}
		setearInput("dniRegFam", null);
		$(".cont_reg_familiar").css('display', 'none');
		$("#cont_tb_familiar_encontrado").html(null);
		$('#rootwizard1').bootstrapWizard('show',0);
		initWizardVertical('rootwizard1', 1);
		$(".nav-pills").children().eq(0).addClass('active');
		$("#icon-regFam").css("display","block");
	}else if(cons_accion_editarGuardar == 1 && tipo == 1){   
		if($("#tipoDocRegFam").val() == 1){
			$("#dniRegFam").attr('maxlength', 12);
		}else{
			$("#dniRegFam").attr('maxlength', 8);
		}
		setearInput("dniRegFam", null);
	}else{
		nroDoc  = $("#dniRegFam").val();
		tipoDoc = $("#tipoDocRegFam").val();
		if(nroDoc != "" && tipoDoc != null && tipoDoc.length != 0 && $.isNumeric(nroDoc) == true){
			if(cons_accion_editarGuardar == 0){
				$.ajax({
					type    : 'POST',
					'url'   : 'c_detalle_alumno/validarFamiliarExiste',
					data    : {numeroDoc : nroDoc,
						       tipoDoc   : tipoDoc},
					'async' : false
				}).done(function(data){
					data = JSON.parse(data);
					if(data.existeCod == 2){
						$("#mensaje_reg_familia").text(data.msj);
						$("#mensaje_reg_familia").css('display', 'block');				
						$(".cont_reg_familiar").css('display', 'none');
						$("#cont_tb_familiar_encontrado").html(null);
					}else{
						if(data.count == 0){
							$("#mensaje_reg_familia").css("display", "none");
							$(".cont_reg_familiar").css("display", "block");
							$("#cont_tb_familiar_encontrado").html(null);
						}else{
							$("#mensaje_reg_familia").text(data.msj);
							$("#mensaje_reg_familia").css('display', 'block');
							
							if(data.existeCod == 0){
								$(".cont_reg_familiar").css('display', 'none');
								$("#cont_tb_familiar_encontrado").css('display', 'block');								
								$("#cont_tb_familiar_encontrado").html(data.tablaFamiliarEncontrado);
								$("#cont-reg-fam").css("display","block")
								$("#tablaFamiliarEncontrado").bootstrapTable({});
							}else{
								$(".cont_reg_familiar").css('display', 'none');
								$("#cont_tb_familiar_encontrado").html(null);
							}
						}
					}
				});
				if($("#cont_tb_familiar_encontrado").css('display', 'block') ||  $(".cont_reg_familiar").css('display', 'block')){
					$("#icon-regFam").css("display","none");
				} 
				else $("#icon-regFam").css("display","block");
			}
		}
	}
}

var cons_cambio_1 = true;
var cons_cambio_2 = true;
function initWizard() {
		$('#rootwizard1').bootstrapWizard({
			onTabClick: function(tab, navigation, index, clickedIndex) {
				if(campos != 1){
					if((index + 1) == 3 || (index + 1) == 4){
						verificarStep(clickedIndex);
						return true;
					}else if(clickedIndex <= index){
						verificarStep(clickedIndex);
						return true;
					}else if(index == 0 && (clickedIndex == 2 || clickedIndex == 3)){
						verificarStep(clickedIndex);
						return verificarTabContainer("dato2");
					}else{
						verificarStep(clickedIndex);
						return verificarTabContainer("dato"+(index+1));
					}
				}
			},
			onTabShow: function(tab, navigation, index) {
				actualizarAvanceEstado(navigation, $('#rootwizard1'), index, true);
			}
		});
}

function actualizarAvanceEstado(navigation, wizard, index, init) {
	navigation.find('li.active').prevAll().addClass('complete');
	navigation.find('li.active').nextAll().removeClass('complete');
}

function verificarTabContainer(dato){
	var cantElem = 0;
	var cantVal  = 0;
	$("."+dato).each(function() {
        if($(this).get(0).tagName != 'DIV'){
        	if(dato == 'dato2' && $("#viveRegFam").val() != 2){
            	if($(this).attr("id") == 'telfFijoRegFam' && $(this).val().trim().length != 0
                  	   && ($("#telfCelularRegFam").val().trim().length == 0)){
             		cantVal = cantVal + 2;
                } else if($(this).attr("id") == 'telfCelularRegFam' && $(this).val().trim().length != 0
                   	   && ($("#telfFijoRegFam").val().trim().length == 0)){
                 	cantVal = cantVal + 2;
                } else if($(this).attr("id") == 'paisRegFam' && $(this).val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal = cantVal + 4;
             	} else if($(this).attr("id") == 'departamentoRegFam' && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal++;
             	} else if($(this).attr("id") == 'provinciaRegFam'    && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal++;
             	} else if($(this).attr("id") == 'distritoRegFam'     && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal++;
             	} else if($(this).val().trim().length != 0){
             		cantVal++;
             	}
        	} else if($(this).val().trim().length != 0){
        		cantVal++;
        	}
        	cantElem = cantElem + 1;
        }
    });
	if($("#viveRegFam").val() == 2 && dato == 'dato2'){
		cantVal = cantVal + 9;
	}
	if($("#viveRegFam").val() == 2 && dato == 'dato1'){
		cantVal = cantVal + 1;
	}
	if(cantElem == cantVal){
		return true;
	}else{
		return false;
	}
}

function onChangeRegFam(){
	var cantElem = 0;
	var cantVal  = 0;
	var cantElem1 = 0;
	var cantVal1  = 0;
	$(".dato1").each(function() {
        if($(this).get(0).tagName != 'DIV'){
        	if($(this).attr("id") == 'viveRegFam' && $("#viveRegFam").val() == 2){
        		cantVal1 = cantVal1 + 9;
        		cantVal  = cantVal + 1;
        	}
        	if($(this).val().trim().length != 0){
        		cantVal++;
        	}
        	cantElem = cantElem + 1;
        }
    });
	$(".dato2").each(function() {
        if($(this).get(0).tagName != 'DIV'){
        	if($(this).attr("id") == 'telfFijoRegFam' && $(this).val().trim().length != 0
             	   && ($("#telfCelularRegFam").val().trim().length == 0)){
        		cantVal1 = cantVal1 + 2;
            }else if($(this).attr("id") == 'telfCelularRegFam' && $(this).val().trim().length != 0
              	   && ($("#telfFijoRegFam").val().trim().length == 0)){
            	cantVal1 = cantVal1 + 2;
            }else if($(this).attr("id") == 'paisRegFam' && $(this).val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
            	cantVal1 = cantVal1 + 4;
        	}else if($(this).attr("id") == 'departamentoRegFam' && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
        		cantVal1++;
        	}else if($(this).attr("id") == 'provinciaRegFam'    && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
        		cantVal1++;
        	}else if($(this).attr("id") == 'distritoRegFam'     && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
        		cantVal1++;
        	}else if($(this).val().trim().length != 0){
        		cantVal1++;
        	}
        	cantElem1 = cantElem1 + 1;
        }
    });
	if(cantElem == cantVal){
		$("#li1").addClass("complete");
	} else {
		$("#li1").removeClass("complete");
		$("#li2").removeClass("complete");
	}
	if(cantElem + cantElem1 == cantVal + cantVal1){
		$("#botonGuardarFamiliar").prop("disabled", false);
		$("#li1").addClass("complete");
		$("#li2").addClass("complete");
	}else{
		$("#botonGuardarFamiliar").prop("disabled", true);
		$("#li2").removeClass("complete");
	}
}

function pasarSiguienteTab(clas){
	var cantElem = 0;
	var cantVal  = 0;
	var cantElem1 = 0;
	var cantVal1  = 0;
	$("."+clas).each(function() {
        if($(this).get(0).tagName != 'DIV'){
        	if(clas == 'dato2'){
            	if($(this).attr("id") == 'telfFijoRegFam' && $(this).val().trim().length != 0
                  	   && ($("#telfCelularRegFam").val().trim().length == 0)){
             		cantVal = cantVal + 2;
                }else if($(this).attr("id") == 'telfCelularRegFam' && $(this).val().trim().length != 0
                   	   && ($("#telfFijoRegFam").val().trim().length == 0)){
                 	cantVal = cantVal + 2;
                }else if($(this).attr("id") == 'paisRegFam' && $(this).val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal = cantVal + 4;
             	}else if($(this).attr("id") == 'departamentoRegFam' && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal++;
             	}else if($(this).attr("id") == 'provinciaRegFam'    && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal++;
             	}else if($(this).attr("id") == 'distritoRegFam'     && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
             		cantVal++;
             	}else if($(this).val().trim().length != 0){
             		cantVal++;
             	}
        	}else if($(this).val().trim().length != 0){
        		cantVal++;
        	}
        	cantElem = cantElem + 1;
        }
    });
	
	if(clas == 'dato1'){
		if($("#viveRegFam").val() == 2){
			cantVal++;
		}
		if(cantElem == cantVal){
			if($("#viveRegFam").val() != 2){
				$('#rootwizard1').bootstrapWizard('show',1);
				initWizardVertical('rootwizard1', 2);
				$(".nav-pills").children().eq(1).addClass('active');
				cons_cambio_1 = false;
				$('#li2').removeAttr('style');
				$(".dato2").each(function() {
			        if($(this).get(0).tagName != 'DIV'){
		            	if($(this).attr("id") == 'telfFijoRegFam' && $(this).val().trim().length != 0
		                  	   && ($("#telfCelularRegFam").val().trim().length == 0)){
		             		cantVal1 = cantVal1 + 2;
		                }else if($(this).attr("id") == 'telfCelularRegFam' && $(this).val().trim().length != 0
		                   	   && ($("#telfFijoRegFam").val().trim().length == 0)){
		                	cantVal1 = cantVal1 + 2;
		                }else if($(this).attr("id") == 'paisRegFam' && $(this).val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
		                	cantVal1 = cantVal1 + 4;
		             	}else if($(this).attr("id") == 'departamentoRegFam' && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
		             		cantVal1++;
		             	}else if($(this).attr("id") == 'provinciaRegFam'    && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
		             		cantVal1++;
		             	}else if($(this).attr("id") == 'distritoRegFam'     && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
		             		cantVal1++;
		             	}else if($(this).val().trim().length != 0){
		             		cantVal1++;
		             	}
			        	cantElem1= cantElem1 + 1;
			        }
			    });
				if(cantVal1 == cantElem1){
					$('#li3').removeAttr('style');
					$('#li4').removeAttr('style');
				} else {
					$('#li3').css('pointer-events', 'none');
					$('#li4').css('pointer-events', 'none');
				}
			} else if($("#viveRegFam").val() == 2){
				$('#rootwizard1').bootstrapWizard('show',2);
				initWizardVertical('rootwizard1', 3);
				$(".nav-pills").children().eq(2).addClass('active');
				$('#li2').removeAttr('style');
				$('#li3').removeAttr('style');
				$('#li4').removeAttr('style');
			}
		} else {
			$('#li2').css('pointer-events', 'none');
			$('#li3').css('pointer-events', 'none');
			$('#li4').css('pointer-events', 'none');
		}
	}else if(clas == 'dato2'){
		if(cantElem == cantVal){
			$('#rootwizard1').bootstrapWizard('show',2);
			initWizardVertical('rootwizard1', 3);
			$(".nav-pills").children().eq(2).addClass('active');
			$('#li3').removeAttr('style');
			$('#li4').removeAttr('style');
		} else {
			$('#li3').css('pointer-events', 'none');
			$('#li4').css('pointer-events', 'none');
		}
	}
}

function changePaisRegFam(tipo){
	var valorCombo = $("#paisRegFam").val();
	if(valorCombo == paisPeru){
		if (tipo == 1){
			setearCombo("departamentoRegFam", $("#departamentoRegFam").val(), $("#departamentoRegFam").val());
			setearCombo("provinciaRegFam", $("#provinciaRegFam").val(), $("#provinciaRegFam").val());
			setearCombo("distritoRegFam", $("#distritoRegFam").val(), $("#distritoRegFam").val());
		} else {
			setearCombo("departamentoRegFam", "", "");
			setearCombo("provinciaRegFam", "", "");
			setearCombo("distritoRegFam", "", "");
		}
	} else {
		setCombo("provinciaRegFam", null, "Provincia");
		setCombo("distritoRegFam", null, "Distrito");
		setearCombo("departamentoRegFam", "", "", 1);
		setearCombo("provinciaRegFam", "", "", 1);
		setearCombo("distritoRegFam", "", "", 1);
	}
}

function InsertarFamiliar(){
	addLoadingButton('botonGuardarFamiliar');
	$("#botonGuardarFamiliar").prop("disabled", true);
	Pace.restart();
	Pace.track(function(){
		//CABECERA
		var tipodoc = $("#tipoDocRegFam").val();
		var nrodoc  = $("#dniRegFam").val();
		//PART1
		var economico  = $("#respeconomicoRegFam").val();
		var parentesco = $("#parentescoRegFam").val();
		var apodarado  = $("#apoderadoRegFam").val();
		var nombres    = $("#nombrePersonaRegFam").val();
		var appaterno  = $("#APPaternoPersonaRegFam").val();
		var apmaterno  = $("#APMaternoPersonaRegFam").val();
		var vive 	   = $("#viveRegFam").val();
		var fecnaci    = $("#fecNacPersonaRegFam").val();
		var pais       = $("#paisRegFam").val();
		//PART2
		//var viveduc  		= $("#viveEducRegFam").val();
		var direccionhogar  = $("#direccionRegFam").val();
		var referenciahogar = $("#referenciaRegFam").val();
		var departhogar     = $("#departamentoRegFam").val();
		var provhogar  	    = $("#provinciaRegFam").val();
		var distrhogar      = $("#distritoRegFam").val();
		var telffijo 	    = $("#telfFijoRegFam").val();
		var telfcel    		= $("#telfCelularRegFam").val();
		var idioma       	= $("#idiomaRegFam").val();
		//PART3
		var estadocivil = $("#estadoCivilRegFam").val();
		var exalumno    = $("#exalumnoRegFam").val();
		var yearegreso  = $("#yearEgresoRegFam").val();
		var coleegreso  = $("#coleEgresoRegFam").val();
		var correo1  	= $("#correo1RegFam").val();
		var correo2     = $("#correo2RegFam").val();
		var religion 	= $("#religionRegFam").val();
		//PART4
		var ocupacion  		 = $("#ocupacionRegFam").val();
		var centrotrabajo    = $("#centroTrabajoRegFam").val();
		var direcciontrabajo = $("#direccionTrabajoRegFam").val();
		var departtrabajo    = $("#departamentoTrabajoRegFam").val();
		var provtrabajo  	 = $("#provinciaTrabajoRegFam").val();
		var distrittrabajo   = $("#distritoTrabajoRegFam").val();
		var telftrabajo 	 = $("#telefonoTrabajoRegFam").val();
		var sitacionlaboral  = $("#situacionLaboralRegFam").val();
		var sueldo       	 = $("#sueldoRegFam").val();
		var cargo       	 = $("#cargoRegFam").val();
		
		datosFamiliar = {
				//CABECERA
				tipodoc : tipodoc,
				nrodoc  : nrodoc,
				//PART1
				respeconomico : economico,
				parentesco    : parentesco,
				apodarado     : apodarado,
				nombres       : nombres,
				appaterno     : appaterno,
				apmaterno     : apmaterno,
				vive          : vive,
				fecnaci       : fecnaci,
				pais          : pais,
				//PART2
				direccionhogar  : direccionhogar,
				referenciahogar : referenciahogar,
				departhogar     : departhogar,
				provhogar       : provhogar,
				distrhogar      : distrhogar,
				telffijo        : telffijo,
				telfcel         : telfcel,
				idioma          : idioma,
				//PART3
				estadocivil : estadocivil,
				exalumno    : exalumno,
				yearegreso  : yearegreso,
				coleegreso  : coleegreso,
				correo1     : correo1,
				correo2     : correo2,
				religion    : religion,
				//PART4
				ocupacion        : ocupacion,
				centrotrabajo    : centrotrabajo,
				direcciontrabajo : direcciontrabajo,
				departtrabajo    : departtrabajo,
				provtrabajo      : provtrabajo,
				distrittrabajo   : distrittrabajo,
				telftrabajo      : telftrabajo,
				sitacionlaboral  : sitacionlaboral,
				sueldo           : sueldo,
				cargo            : cargo,
				
				familiar : cons_familiar_editar
			};
		if(cons_accion_editarGuardar == 0){
			$.ajax({
				data : datosFamiliar,
				url : 'c_detalle_alumno/insertarFamiliar',
				async : true,
				type : 'POST'
			}).done(function(data) {
				
				data = JSON.parse(data);		
				if(data.error == 0) {
					$('#cont_familiares').html(data.vistaFamiliares);
					componentHandler.upgradeAllRegistered();
					limpiarModalRegFam();
					mostrarNotificacion('success', data.msj, null);
					modal("modalRegistrarFamiliar");
					stopLoadingButton('botonGuardarFamiliar');
					if(data.estado == 0){
						$("#estadoPersona").removeClass('datos-incompletos');
						$("#estadoPersona").addClass('pre-registro');
						$("div[for='estadoPersona']").find("font").html("pre-registro");
						stopLoadingButton('botonGuardarFamiliar');
					}
				}else if(data.error == 1){
					mostrarNotificacion('warning', data.msj, null);
					stopLoadingButton('botonGuardarFamiliar');
				}else if(data.error == 2){
					setearCombo("respeconomicoRegFam", data.opcion);
					setearCombo("apoderadoRegFam", data.opcion);
					$('#rootwizard1').bootstrapWizard('show',0);
					initWizardVertical('rootwizard1', 1);
					stopLoadingButton('botonGuardarFamiliar');
					mostrarNotificacion('warning', data.msj, null);
				}
			});
		}else{
			$.ajax({
				data : datosFamiliar,
				url : 'c_detalle_alumno/editarFamiliar',
				async : true,
				type : 'POST'
			}).done(function(data) {
				data = JSON.parse(data);
				if(data.error == 0){
					$('#cont_familiares').html(data.vistaFamiliares);				
					componentHandler.upgradeAllRegistered();
					limpiarModalRegFam();
					abrirCerrarModal("modalRegistrarFamiliar");
					cons_familiar_editar = null;
					stopLoadingButton('botonGuardarFamiliar');
					mostrarNotificacion('success', data.msj, null);	
				}else{
					stopLoadingButton('botonGuardarFamiliar');
					mostrarNotificacion('warning', data.msj, null);
				}
			});
		}
	});
	$("#cont_search_empty").css("display","none");
	$("#botonGuardarFamiliar").prop("disabled", false);
}

function changeSituacionLaboral(tipo){
	var valorCombo = $("#situacionLaboralRegFam").val();
	if(valorCombo == 1 || valorCombo == 4){
		if(tipo == 1){
			setearInput("ocupacionRegFam", $("#ocupacionRegFam").val(), $("#ocupacionRegFam").val());
			setearInput("centroTrabajoRegFam", $("#centroTrabajoRegFam").val(), $("#centroTrabajoRegFam").val());
			setearInput("direccionTrabajoRegFam", $("#direccionTrabajoRegFam").val(), $("#direccionTrabajoRegFam").val());
			setearInput("telefonoTrabajoRegFam", $("#telefonoTrabajoRegFam").val(), $("#telefonoTrabajoRegFam").val());
			setearInput("sueldoRegFam", $("#sueldoRegFam").val(), $("#sueldoRegFam").val());
			setearInput("cargoRegFam", $("#cargoRegFam").val(), $("#cargoRegFam").val());
			setearCombo("departamentoTrabajoRegFam", $("#departamentoTrabajoRegFam").val(), $("#departamentoTrabajoRegFam").val());
			setearCombo("provinciaTrabajoRegFam", $("#provinciaTrabajoRegFam").val(), $("#provinciaTrabajoRegFam").val());
			setearCombo("distritoTrabajoRegFam", $("#distritoTrabajoRegFam").val(), $("#distritoTrabajoRegFam").val());
		} else {
			setearInput("ocupacionRegFam", "", "");
			setearInput("centroTrabajoRegFam", "", "");
			setearInput("direccionTrabajoRegFam", "", "");
			setearInput("telefonoTrabajoRegFam", "", "");
			setearInput("sueldoRegFam", "", "");
			setearInput("cargoRegFam", "", "");
			setearCombo("departamentoTrabajoRegFam", "", "");
			setearCombo("provinciaTrabajoRegFam", "", "");
			setearCombo("distritoTrabajoRegFam", "", "");
		}
	} else {
		setearInput("ocupacionRegFam", "", "", 1);
		setearInput("centroTrabajoRegFam", "", "", 1);
		setearInput("direccionTrabajoRegFam", "", "", 1);
		setearInput("telefonoTrabajoRegFam", "", "", 1);
		setearInput("sueldoRegFam", "", "", 1);
		setearInput("cargoRegFam", "", "", 1);
		setearCombo("departamentoTrabajoRegFam", "", "", 1);
		setearCombo("provinciaTrabajoRegFam", "", "", 1);
		setearCombo("distritoTrabajoRegFam", "", "", 1);
	}
}

function busquedaFamiliares(){
	Pace.restart();
	Pace.track(function(){
		var nombreFamiliar = $("#nombreFamiliar").val();
		if(nombreFamiliar.length != 0){
			$.ajax({	
				type    : 'POST',
				'url'   : 'c_detalle_alumno/buscarFamiliares',
				data    : {nombre : nombreFamiliar},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				$("#cont_tabla_familiares_busqueda").html(data.tablaFamiliar);
				$("#tablaFamiliaresBusqueda").bootstrapTable({});
				componentHandler.upgradeAllRegistered();
			});
		}
	});
}

function abrirModalConfirmAsignarFamiliares(){
	var arrayIdFamiliares = [];
	$('.cb-familiar').each(function() {
		if($(this).parent().hasClass('is-checked')){
			var idFamiliar = $(this).attr('attr-id-familiar');
			arrayIdFamiliares.push(idFamiliar);
		}
	});
	if(arrayIdFamiliares.length != 0){
		abrirCerrarModal("modalConfirmAgsinarFamiliares");
	}
}

function asignarFamiliares(){
	Pace.restart();
	Pace.track(function(){
		var arrayIdFamiliares = [];
		$('.cb-familiar').each(function() {
			if($(this).parent().hasClass('is-checked')){
				var idFamiliar = $(this).attr('attr-id-familiar');
				arrayIdFamiliares.push(idFamiliar);
			}
		});
		if(arrayIdFamiliares.length != 0){
			$.ajax({	
				type    : 'POST',
				'url'   : 'c_detalle_alumno/asignarFamiliares',
				data    : {familiares : arrayIdFamiliares},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				busquedaFamiliares();
				if(data.estado == 0){
					$("#estadoPersona").removeClass('datos-incompletos');
					$("#estadoPersona").addClass('pre-registro');
					$("div[for='estadoPersona']").find("font").html("pre-registro");
				}
				$('#cont_familiares').html(data.vistaFamiliares);
				componentHandler.upgradeAllRegistered();
				abrirCerrarModal("modalConfirmAgsinarFamiliares");
				mostrarNotificacion('success', data.msj, null);
			});
		}
	});
}

function abrirModalConfirmAsignarFamilia(cod, nombre){
	cons_cod_familia = cod;
	$("#nombreFamiliaAsignar").text(nombre);
	abrirCerrarModal("modalConfirmAgsinarFamilia");
}

function agsinarFamiliaraFamilia(idFamiliar, nro){
	$.ajax({	
		type    : 'POST',
		'url'   : 'c_detalle_alumno/asignarFamiliaraFamilia',
		data    : {idfamiliar : idFamiliar,
			       nro        : nro},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			if(nro == 0){
				busquedaFamiliares();
			}else{
				$("#mensaje_reg_familia").css('display', 'none');
				$(".cont_reg_familiar").css('display', 'none');
				$("#cont_tb_familiar_encontrado").css('display', 'none');
				$("#cont_tb_familiar_encontrado").html(null);
				
				setearCombo("tipoDocRegFam", null);
				setearInput("dniRegFam", null);
			}
			
			$('#cont_familiares').html(data.vistaFamiliares);
			componentHandler.upgradeAllRegistered();
			mostrarNotificacion('success', data.msj, null);
		}else{
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

function verFamiliares(codFamiliar){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_alumno/verFamiliaresCodFamiliar',
		data    : {codFamiliar : codFamiliar},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_familiares_by_CodFam").html(data.tablaFamiliares);
		$("#tablaFamiliaresByCodFam").bootstrapTable({});
		abrirCerrarModal("modalVistaFamiliares");
	});
}

function agsinarFamilia(){
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/asginarFamilia',
			data    : {codigofam : cons_cod_familia},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				if(data.estado == 0){
					$("#estadoPersona").removeClass('datos-incompletos');
					$("#estadoPersona").addClass('pre-registro');
					$("div[for='estadoPersona']").find("font").html("pre-registro");
				}
				busquedaFamilias();
				$('#cont_familiares').html(data.vistaFamiliares);
				componentHandler.upgradeAllRegistered();
				abrirCerrarModal("modalConfirmAgsinarFamilia");
				mostrarNotificacion('success', data.msj, null);
				$("#cont_search_empty").css("display","none");
			}else{
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	});
}

function editDetalleFamiliar(idfam, nomFamiliar){
	$('#rootwizard1 ul li').css('pointer-events', 'none');
	$('#li1').removeAttr('style');
	$('#rootwizard1').bootstrapWizard('show',0);
	$("#mensaje_reg_familia").css("display", "none");
	$("#cont_tb_familiar_encontrado").html(null);
	campos = 0;
	initWizardVertical('rootwizard1', 1);
	$("#icon-regFam").css("display","none");
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/detalleFamiliar',
			data    : {idFamiliar : idfam},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			$(".progress-bar-primary").css("width", "0");
			abrirCerrarModal("modalRegistrarFamiliar");
			$('#btnAgregarColegio').attr("disabled", false);
			//CABECERA
			setearSinOpciones("tipoDocRegFam", data.tipoDocFam);
			setearSinOpciones("dniRegFam", data.nroDocFam);
			//PART1
			setearSinOpciones("respeconomicoRegFam", data.respEconoFam);
			setearSinOpciones("parentescoRegFam", data.parentescoFam);
			setearSinOpciones("apoderadoRegFam", data.apoderadoFam);
			setearSinOpciones("nombrePersonaRegFam", data.nomFam);
			setearSinOpciones("APPaternoPersonaRegFam", data.apePateFam);
			setearSinOpciones("APMaternoPersonaRegFam", data.apeMateFam);
			setearSinOpciones("viveRegFam", data.viveFam);
			setearSinOpciones("fecNacPersonaRegFam", data.fecNaciFam);
			setearSinOpciones("paisRegFam", data.paisFam);
			//PART2
			//setearCombo("viveEducRegFam", data.viveFam);
			setearSinOpciones("direccionRegFam", data.direccionHogarFam);
			setearSinOpciones("referenciaRegFam", data.referenciaHogarFam);
			setearSinOpciones("departamentoRegFam", data.departamentoHogarFam);
			getProvinciaPorDepartamento('departamentoRegFam', 'provinciaRegFam', 'distritoRegFam', 2, data.provinciaHogarFam, data.distritoHogarFam);
			setearSinOpciones("telfFijoRegFam", data.telefonoFijoFam);
			setearSinOpciones("telfCelularRegFam", data.telefonoCelularFam);
			setearSinOpciones("idiomaRegFam", data.idiomaFam);
			
			//PART3
			setearSinOpciones("estadoCivilRegFam", data.estadocivilFam);
			setearSinOpciones("exalumnoRegFam", data.exalumnoFam);
			setearSinOpciones("yearEgresoRegFam", data.yearEgresoFam);
			setearSinOpciones("coleEgresoRegFam", data.colegioFam);
			setearSinOpciones("correo1RegFam", data.correo1Fam);
			setearSinOpciones("correo2RegFam", data.correo2Fam);
			setearSinOpciones("religionRegFam", data.religionFam);
	
			//PART4
			setearSinOpciones("ocupacionRegFam", data.ocupacionFam);
			setearSinOpciones("centroTrabajoRegFam", data.centroTrabajoFam);
			setearSinOpciones("direccionTrabajoRegFam", data.direccionTrabajoFam);
			setearSinOpciones("departamentoTrabajoRegFam", data.departamentoTrabajoFam);
			getProvinciaPorDepartamento('departamentoTrabajoRegFam', 'provinciaTrabajoRegFam', 'distritoTrabajoRegFam', 2, data.provinciaTrabajoFam, data.distritoTrabajoFam);
			setearSinOpciones("telefonoTrabajoRegFam", data.telefonoTrabajoFam);
			setearSinOpciones("situacionLaboralRegFam", data.situacionLaboralFam);
			setearSinOpciones("sueldoRegFam", data.sueldoFam);
			setearSinOpciones("cargoRegFam", data.cargoFam);
			
			disEnabledInputComboGroup(["tipoDocRegFam","dniRegFam","respeconomicoRegFam","parentescoRegFam","apoderadoRegFam",
			        "nombrePersonaRegFam","APPaternoPersonaRegFam","APMaternoPersonaRegFam","viveRegFam","fecNacPersonaRegFam",
			        "paisRegFam","estadoCivilRegFam","exalumnoRegFam","yearEgresoRegFam","coleEgresoRegFam",
					"correo1RegFam","correo2RegFam","religionRegFam"],false);
			
			cons_familiar_editar      = data.familiar;
			cons_accion_editarGuardar = 1;
			$("#botonGuardarFamiliar").prop("disabled", false);
			$("#botonGuardarFamiliar").css("display", "inline-block");
			$(".cont_reg_familiar").css("display", "block");
			$("#tituloRegistrarEditarFamiliar").text("Editar Familiar: "+nomFamiliar);
			
			changeSituacionLaboral(1);
			changePaisRegFam(1);
			changeVive(1);
			changeDoc(1);
			verificarStep(0, 1);
			verificarStep(1, 1);
		});
	});
}

function verDetalleFamiliar(idfam, nomFamiliar){
	$("#mensaje_reg_familia").css("display", "none");
	$("#cont_tb_familiar_encontrado").html(null);
	$('#rootwizard1 ul li').css('pointer-events', 'none');
	$('#li1').removeAttr('style');
	$('#li2').removeAttr('style');
	$('#li3').removeAttr('style');
	$('#li4').removeAttr('style');
	campos = 1;
	$('#rootwizard1').bootstrapWizard('show',0);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/detalleFamiliar',
			data    : {idFamiliar : idfam},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			$(".progress-bar-primary").css("width", "0");
			//CABECERA
			setearSinOpciones("tipoDocRegFam", data.tipoDocFam);
			setearSinOpciones("dniRegFam", data.nroDocFam);
			
			//PART1
			setearSinOpciones("respeconomicoRegFam", data.respEconoFam);
			setearSinOpciones("parentescoRegFam", data.parentescoFam);
			setearSinOpciones("apoderadoRegFam", data.apoderadoFam);
			setearSinOpciones("nombrePersonaRegFam", data.nomFam);
			setearSinOpciones("APPaternoPersonaRegFam", data.apePateFam);
			setearSinOpciones("APMaternoPersonaRegFam", data.apeMateFam);
			setearSinOpciones("viveRegFam", data.viveFam);
			setearSinOpciones("fecNacPersonaRegFam", data.fecNaciFam);
			setearSinOpciones("paisRegFam", data.paisFam);
			
			//PART2
			//setearSinOpciones("viveEducRegFam", data.viveFam);
			setearSinOpciones("direccionRegFam", data.direccionHogarFam);
			setearSinOpciones("referenciaRegFam", data.referenciaHogarFam);
			setearSinOpciones("departamentoRegFam", data.departamentoHogarFam);
//			getProvinciaPorDepartamento('departamentoRegFam', 'provinciaRegFam', 'distritoRegFam', 2);
			getProvinciaPorDepartamento('departamentoRegFam', 'provinciaRegFam', 'distritoRegFam', 2, data.provinciaHogarFam, data.distritoHogarFam);
//			setearSinOpciones("provinciaRegFam", data.provinciaHogarFam);
//			getDistritoPorProvincia('departamentoRegFam', 'provinciaRegFam', 'distritoRegFam', 3);
//			setearSinOpciones("distritoRegFam", data.distritoHogarFam);
			setearSinOpciones("telfFijoRegFam", data.telefonoFijoFam);
			setearSinOpciones("telfCelularRegFam", data.telefonoCelularFam);
			setearSinOpciones("idiomaRegFam", data.idiomaFam);
			
			//PART3
			setearSinOpciones("estadoCivilRegFam", data.estadocivilFam);
			setearSinOpciones("exalumnoRegFam", data.exalumnoFam);
			setearSinOpciones("yearEgresoRegFam", data.yearEgresoFam);
			setearSinOpciones("coleEgresoRegFam", data.colegioFam);
			setearSinOpciones("correo1RegFam", data.correo1Fam);
			setearSinOpciones("correo2RegFam", data.correo2Fam);
			setearSinOpciones("religionRegFam", data.religionFam);
	
			//PART4
			setearSinOpciones("ocupacionRegFam", data.ocupacionFam);
			setearSinOpciones("centroTrabajoRegFam", data.centroTrabajoFam);
			setearSinOpciones("direccionTrabajoRegFam", data.direccionTrabajoFam);
			setearSinOpciones("departamentoTrabajoRegFam", data.departamentoTrabajoFam);
			getProvinciaPorDepartamento('departamentoTrabajoRegFam', 'provinciaTrabajoRegFam', 'distritoTrabajoRegFam', 2, data.provinciaTrabajoFam, data.distritoTrabajoFam);
//			getProvinciaPorDepartamento('departamentoTrabajoRegFam', 'provinciaTrabajoRegFam', 'distritoTrabajoRegFam', 2);
//			setearSinOpciones("provinciaTrabajoRegFam", data.provinciaTrabajoFam);
//			getDistritoPorProvincia('departamentoTrabajoRegFam', 'provinciaTrabajoRegFam', 'distritoTrabajoRegFam', 3);
//			setearSinOpciones("distritoTrabajoRegFam", data.distritoTrabajoFam);
			setearSinOpciones("telefonoTrabajoRegFam", data.telefonoTrabajoFam);
			setearSinOpciones("situacionLaboralRegFam", data.situacionLaboralFam);
			setearSinOpciones("sueldoRegFam", data.sueldoFam);
			setearSinOpciones("cargoRegFam", data.cargoFam);
			$('#btnAgregarColegio').attr("disabled", true);
			//CABECERA

			disEnabledInputComboGroup(["tipoDocRegFam","dniRegFam","respeconomicoRegFam","parentescoRegFam","apoderadoRegFam",
			                           "nombrePersonaRegFam","APPaternoPersonaRegFam","APMaternoPersonaRegFam","viveRegFam",
			                           "fecNacPersonaRegFam","paisRegFam","direccionRegFam","referenciaRegFam","departamentoRegFam",
			                           "provinciaRegFam","distritoRegFam","telfFijoRegFam","telfCelularRegFam","idiomaRegFam",
			                           "estadoCivilRegFam","exalumnoRegFam","yearEgresoRegFam","coleEgresoRegFam","correo1RegFam",
			                           "correo2RegFam","religionRegFam","ocupacionRegFam","centroTrabajoRegFam","direccionTrabajoRegFam",
			                           "departamentoTrabajoRegFam","provinciaTrabajoRegFam","distritoTrabajoRegFam",
			                           "telefonoTrabajoRegFam","situacionLaboralRegFam","sueldoRegFam","cargoRegFam",
			                           "ocupacionRegFam","centroTrabajoRegFam","direccionTrabajoRegFam","telefonoTrabajoRegFam",
			                           "sueldoRegFam","cargoRegFam","departamentoTrabajoRegFam","provinciaTrabajoRegFam","distritoTrabajoRegFam"],true);
			
			cons_familiar_editar      = data.familiar;
			cons_accion_editarGuardar = 1;
			$("#botonGuardarFamiliar").prop("disabled", true);
			$("#botonGuardarFamiliar").css("display", "none");
			stopLoadingButton();
			$(".cont_reg_familiar").css("display", "block");
			$("#tituloRegistrarEditarFamiliar").text("Editar Familiar: "+nomFamiliar);
			abrirCerrarModal("modalRegistrarFamiliar");
			cons_cambio_1 = false;
			cons_cambio_2 = false;
		});
	});
}

function abrirModalCambiarFechaDocumento(idDocumento, fecha){
	cons_id_documento = idDocumento;
	setearInput("fechaDocumentoEdit", fecha);
	abrirCerrarModal("modalChangeFechaDocumento");
}

function checkedEntrego(idDocumento, tipo){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_alumno/checkDocumento',
		data    : {iddocumento : idDocumento,
			       tipo        : tipo},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#cont_tb_documentos").html(data.tablaDocumentos);
			$('#tb_documentos').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu'); 
			initSearchTableNew();
			componentHandler.upgradeAllRegistered();
			mostrarNotificacion('success', data.msj, null);
		}else{
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

function changeFechaDocumento(){
	fecha = $("#fechaDocumentoEdit").val();
	if(fecha.length == 10){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/changeFechaDocumento',
			data    : {iddocumento : cons_id_documento,
				       fecrecibio  : fecha},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_tb_documentos").html(data.tablaDocumentos);
				$('#tb_documentos').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu'); 
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
				abrirCerrarModal("modalChangeFechaDocumento");
				mostrarNotificacion('success', data.msj, null);
			}else{
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	}
}
/*
function getGradosByNivelOnly(idnivel, idgrado){
	var valorNivel = $("#"+idnivel).val();
	if(valorNivel != null && valorNivel.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_alumno/getGradosByNivelAll',
			data    : {idnivel : valorNivel},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idgrado, data.comboGrados, "Grado");
		});
	}else{
		setCombo(idgrado, null, "Grado");
	}
}
*/

function changeTipoDoc() {
	tipoDoc = $("#tipoDoc").val();
	disableEnableInput("nro_documento", true);
	if(tipoDoc == 2){
		$("#nro_documento").attr('maxlength','8');
		disableEnableInput("nro_documento", false);
	}else if(tipoDoc == 1){
		$("#nro_documento").attr('maxlength','12');
		disableEnableInput("nro_documento", false);
	}
	setearInput("nro_documento", null);
	$("#nro_documento").parent().removeClass("is-disabled");
}

function abrirModalConfirmGenerarUsuario(cod, nombre){
	cons_cod_familia = cod;
	$("#tituloGenerarUsuario").text('¿Deseas enviar sus credenciales de acceso a '+nombre+'?');
	modal("modalConfirmGenerarUsuario");
}

function generarUsuario() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/generarUsuario',
			data    : {idfamiliar : cons_cod_familia},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$('#cont_familiares').html(data.vistaFamiliares);
				componentHandler.upgradeAllRegistered();
				modal("modalConfirmGenerarUsuario");	
				if(data.estado == 0){
					$("#estadoPersona").removeClass('datos-incompletos');
					$("#estadoPersona").addClass('pre-registro');
					$("div[for='estadoPersona']").find("font").html("pre-registro");
				}
			}
			stopLoadingButton();
			msj('success', data.msj, null);
		});
	});
}

function getSedesByYearWithoutCompromiso(year,sede,nivel,grado) {
	var valorYear = $("#"+year).val();
	if(valorYear != null && valorYear.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_alumno/getSedesByYear',
			data    : {year : valorYear},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(sede, data.comboSedes, "Sede(*)");
			setCombo(nivel, null, "Nivel(*)");
			setCombo(grado, null, "Grado(*)");
		});
	}
}

function getNivelesBySede(year, sede, nivel, grado) {
	var valorYear = $("#"+year).val();
	var valorSede = $("#"+sede).val();
	if(valorSede != null && valorSede.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_alumno/getNivelesBySede',
			data    : {idsede : valorSede,
				       year   : valorYear},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(nivel, data.comboNiveles, "Nivel(*)");
			setCombo(grado, null, "Grado(*)");
		});
	}
}

function getGradosByNivel(year, sede, nivel, grado) {
	var valorYear = $("#"+year).val();
	var valorSede  = $("#"+sede).val();
    var valorNivel = $("#"+nivel).val();
        
    if(valorNivel != null && valorNivel.length != 0){
    	$.ajax({
    		type    : 'POST',
    		'url'   : 'c_detalle_alumno/getGradosByNivel',
    		data    : {idnivel : valorNivel,
    			       idsede  : valorSede,
    			       year    : valorYear},
    		'async' : false
    	}).done(function(data){
    		data = JSON.parse(data);
    		setCombo(grado, data.comboGrados, "Grado(*)");
    	});
    }
}

function abrirModalCrearColegio(){
	modal("modalRegistrarColegio");
}


function changeVive(tipo) {
	var valorCombo = $("#viveRegFam").val();
	if(valorCombo == 1) {
		if(tipo == 1){
			setearInput("telfFijoRegFam", $("#telfFijoRegFam").val(), $("#telfFijoRegFam").val());
			setearInput("telfCelularRegFam", $("#telfCelularRegFam").val(), $("#telfCelularRegFam").val());
			setearInput("direccionRegFam", $("#direccionRegFam").val(), $("#direccionRegFam").val());
			setearInput("referenciaRegFam", $("#referenciaRegFam").val(), $("#referenciaRegFam").val());
			setearCombo("paisRegFam", $("#paisRegFam").val(), $("#paisRegFam").val());
			setearCombo("idiomaRegFam", $("#idiomaRegFam").val(), $("#idiomaRegFam").val());
			setearInput("correo1RegFam", $("#correo1RegFam").val(), $("#correo1RegFam").val());
			//setearInput("correo2RegFam", $("#correo2RegFam").val(), $("#correo2RegFam").val());
			setearCombo("respeconomicoRegFam", $("#respeconomicoRegFam").val(), $("#respeconomicoRegFam").val());
			setearCombo("apoderadoRegFam", $("#apoderadoRegFam").val(), $("#apoderadoRegFam").val());
		} else {
			setearInput("telfFijoRegFam", "", "");
			setearInput("telfCelularRegFam", "", "");
			setearInput("direccionRegFam", "", "");
			setearInput("referenciaRegFam", "", "");
			setearCombo("paisRegFam", "", "");
			setearCombo("idiomaRegFam", "", "");
			setearInput("correo1RegFam", "", "");
			//setearInput("correo2RegFam", "", "");
			setearCombo("respeconomicoRegFam", "", "");
			setearCombo("apoderadoRegFam", "", "");
		}
	} else {
		if(valorCombo == 2){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_detalle_alumno/getNoResponsableNoApoderado',
				data    : {},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				setearCombo("respeconomicoRegFam", data.noVivo, "", 1);
				setearCombo("apoderadoRegFam", data.noVivo, "", 1);
			});
		} else {
			setearCombo("respeconomicoRegFam","", "", 1);
			setearCombo("apoderadoRegFam", "", "", 1);
		}

		setearInput("correo1RegFam", "", "", 1);
		//setearInput("correo2RegFam", "", "", 1);
		setearInput("telfFijoRegFam", "", "", 1);
		setearInput("telfCelularRegFam", "", "", 1);
		setearInput("direccionRegFam", "", "", 1);
		setearInput("referenciaRegFam", "", "", 1);
		
		setearCombo("paisRegFam", "", "", 1);
		setearCombo("departamentoRegFam", "", "", 1);
		setearCombo("provinciaRegFam", "", "", 1);
		setearCombo("distritoRegFam", "", "", 1);
		setearCombo("idiomaRegFam", "", "", 1);
	}
}

function getAulasCantidad(){
	Pace.restart();
	Pace.track(function(){
		var year = $("#yearIngreso").val();
		var sede = $("#sedeIngreso").val();
		var nivel = $("#nivelIngreso").val();
		var grado = $("#gradoIngreso").val(); 
		if(grado.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_detalle_alumno/getAulasByGradoCantidad',
				data    : {year  : year,
					       sede  : sede,
					       nivel : nivel,
					       grado : grado},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				$('#cont_tabla_aulas_cantidad').html(data.aulas);
				$('#tb_aulasCantidad').bootstrapTable({ });
				modal("modalAulasCantidad");
			});
		}else{
			msj('success', 'Seelecciona un grado', null);
		}
	});
}

function activeDesactiveSearch(){
	var nameEstudiante = $("#nombreFamilia").val();
	if($.trim(nameEstudiante).length >= 3){
		$('#btnBuscar').attr('disabled', false);
		$('#btnBuscar').addClass('mdl-button--raised');
	} else {
		$("#cont_tabla_alumnos_sinaula").html(null);
		$('#btnBuscar').attr('disabled', true);
		$('#btnBuscar').removeClass('mdl-button--raised');
	}
}

function cambiarColorProgreso(){
	val = parseInt($("#progreso").attr("aria-valuenow"));
	$("#progreso").removeClass("progress-bar-success");
	$("#progreso").removeClass("progress-bar-warning");
	$("#progreso").removeClass("progress-bar-danger");
	
	if(val < 33){
		$("#progreso").addClass("progress-bar-success");
	}else if(val > 66){
		$("#progreso").addClass("progress-bar-danger");
	}else{
		$("#progreso").addClass("progress-bar-warning");
	}
}

function changePaisEstudiante(tipo){
	var pais = $("#pais").val();
	if(pais == paisPeruEnc){
		if (tipo == 1){
			setearCombo("departamento", $("#departamento").val(), $("#departamento").val());
			setearCombo("provincia", $("#provincia").val(), $("#provincia").val());
			setearCombo("distrito", $("#distrito").val(), $("#distrito").val());
		} else {
			setearCombo("departamento", "", "");
			setearCombo("provincia", "", "");
			setearCombo("distrito", "", "");
		}
	}else{
		setCombo("provincia", null, "Provincia");
		setCombo("distrito", null, "Distrito");
		setearCombo("departamento", "", "", 1);
		setearCombo("provincia", "", "", 1);
		setearCombo("distrito", "", "", 1);
	}
}

function verificarStep(clickedIndex, tipo){
	var cantElem = 0;
	var cantVal  = 0;
	if(clickedIndex == 0){
		if($("#viveRegFam").val() == 2){
		cantVal = cantVal + 1;
		}
		$(".dato1").each(function() {
	        if($(this).get(0).tagName != 'DIV'){
	        	if($(this).val().trim().length != 0){
	        		cantVal++;
	        	}
	        	cantElem = cantElem + 1;
	        }
	    });
	}
	if(clickedIndex == 1){
    	if($("#viveRegFam").val() == 2){
    		cantVal = cantVal + 9;
    	}
		$(".dato2").each(function() {
	        if($(this).get(0).tagName != 'DIV'){
	        	if($(this).attr("id") == 'telfFijoRegFam' && $(this).val().trim().length != 0
	             	   && ($("#telfCelularRegFam").val().trim().length == 0)){
	        		cantVal = cantVal + 2;
	            }else if($(this).attr("id") == 'telfCelularRegFam' && $(this).val().trim().length != 0
	              	   && ($("#telfFijoRegFam").val().trim().length == 0)){
	            	cantVal = cantVal + 2;
	            }else if($(this).attr("id") == 'paisRegFam' && $(this).val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
	            	cantVal = cantVal + 4;
	        	}else if($(this).attr("id") == 'departamentoRegFam' && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
	        		cantVal++;
	        	}else if($(this).attr("id") == 'provinciaRegFam'    && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
	        		cantVal++;
	        	}else if($(this).attr("id") == 'distritoRegFam'     && $("#paisRegFam").val() != paisPeru && $(this).val() != 0 && $(this).val() != null){
	        		cantVal++;
	        	}else if($(this).val() != null && $(this).val().trim().length != 0){
	        		cantVal++;
	        	}
	        	cantElem = cantElem + 1;
	        }
	    });
	}
	if(clickedIndex == 2){
		if(verificar(3,1) == true){
			cantElem = 1;
			cantVal = 1;
		}
	}
	if(clickedIndex == 3){
		if(verificar(4,1) == true){
			cantElem = 1;
			cantVal = 1;
		}
	}
	if(cantElem == cantVal && cantElem != 0){
		if(tipo != 1 || clickedIndex == 0){
			$("#li"+(clickedIndex+1)).addClass("complete");
		}
		if(clickedIndex+1 == 1){
			$('#li2').removeAttr('style');
		} else if(clickedIndex+1 == 2){
			$('#li3').removeAttr('style');
			$('#li4').removeAttr('style');
		}
	} else {
		$("#li"+(clickedIndex+1)).removeClass("complete");
	}
}

function verificar(click,tipo){
	var cantElem = 0;
	var cantVal  = 0;
	if(click == 3){
		$(".dato3").each(function() {
	        if($(this).get(0).tagName != 'DIV'){
        		if($(this).val().trim().length != 0){
	        		cantVal++;
	        	}
	        	cantElem = cantElem + 1;
	        }
		});
	} else if(click == 4){
		$(".dato4").each(function() {
	        if($(this).get(0).tagName != 'DIV'){
				if($(this).attr("id") == 'situacionLaboralRegFam' && $(this).val() == 2){
	            	cantVal = cantVal + 10;
	            } else if($(this).val().trim().length != 0){
	        		cantVal++;
	        	}
	        	cantElem = cantElem + 1;
	        }
		});
	}

	if(cantElem == cantVal && cantElem != 0){
		if(tipo != 1 ){
			$("#li"+(click)).addClass("complete");
		}
	} else {
		if(tipo != 1 ){
			$("#li"+click).removeClass("complete");
		}
	}
	return  cantElem == cantVal;
}

function stepAlumno(element){
	if($(element).attr('href') == "#tab-2"){
		if(idCargaInicialPagina2 == 0) {
			Pace.restart();
			Pace.track(function(){
				$.ajax({
					type    : 'POST',
					'url'   : 'c_detalle_alumno/getDatosAdmision',
					data    : {},
					'async' : true
				}).done(function(data){
					data = JSON.parse(data);
	                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	            	    $('.selectButtonAdm').selectpicker('mobile');
	            	} else {
	            	    $('.selectButtonAdm').selectpicker();
	            	}
					setearCombo('yearIngreso',data.yearIngreso);
		            setearInput("sedeGradoNivel",data.sedeGradoNivel);
		            setearInput("observacion",data.observ);
		            if(data.accion == 0){
		            	disableEnableInput("observacion", true);
		            }
	                disEnabledInputComboGroup(["yearIngreso","sedeGradoNivel"],true);
				});
			});
			initWizard();
			idCargaInicialPagina2 = 1;
		}
	} else if($(element).attr('href') == "#tab-3"){
		if(idCargaInicialPagina == 0) {
			Pace.restart();
			Pace.track(function(){
				$.ajax({
					type    : 'POST',
					'url'   : 'c_detalle_alumno/getFamiliaByEstudiante',
					data    : {},
					'async' : true
				}).done(function(data){
					data = JSON.parse(data);
	                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	            	    $('.selectButtonWiz').selectpicker('mobile');
	            	} else {
	            	    $('.selectButtonWiz').selectpicker();
	            	}
	                result = data.vistaFamiliares;
					if(result.length != 0){
						$('#cont_familiares').html(data.vistaFamiliares);
						componentHandler.upgradeAllRegistered();
						$("#cont_search_empty").css("display","none");
					}
					
				});
			});
			initWizard();
			idCargaInicialPagina = 1;
		}
	}
}