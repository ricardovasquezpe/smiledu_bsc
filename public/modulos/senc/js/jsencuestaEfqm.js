var objJSON = [];
var arrCateObliJSON = null;
var cantPregObligatorias = null;
var contModal = 1;
var contador = 0;
var contNext = 1;
var postNext = 0;
var arrayServ = [];
var arrayCheck = [];

function activarMainGallery(idCate) {
	$(".tab-pane").each(function(index) {
		if (idCate == $(this).attr('id')) {
			ocultarMostrarTabPane($(this), true);
		} else {
			ocultarMostrarTabPane($(this), false);
		}
	});
};

function selectAnswer(face, preg, idCate, val, elemento) {
	var flg_active = $(elemento).children().hasClass('active-o');
	var flg_obli = $(elemento).data('flg_obli');
	if(flg_active == true && flg_obli == "0"){
		$(elemento).children().removeClass( "active-o" );
		id_pregu  = $(elemento).parent().data('id_pregunta');
		id_altern = $(elemento).parent().data('id_alternativa');
		for(var i = 0; i < objJSON.length; i++){
			if(id_pregu ==objJSON[i].id_preg  && id_altern == objJSON[i].id_alter){
				objJSON[i].id_alter = null;
			}
		}
	} else{
		contador = 0;
		$(".main-gallery" + idCate).each(function() {
			$('#panel-' + idCate + '-' + val).removeClass("active");
			$('#panel-' + idCate + '-' + (val + 1)).addClass("active");
		});
		if(idCate == 0) {
			objJSON = [];
			obj = { id_preg  : $(elemento).data('id_pregunta'),
					id_alter : $(elemento).data('id_alternativa') 
				  };
			objJSON.push(obj);
			selectAula();
		}
		last = $('.main-gallery' + idCate + ' .gallery-cell').size();
		var idPanel = '#panel-' + idCate + '-' + (val + 1);
		var valorSumar = $(idPanel).height() + 12;
		if ($(idPanel).offset() != undefined) {
			var topValue = (valorSumar * val);
			$('.tab-content').animate({
				scrollTop : topValue
			}, 1000);
		}
		if($(elemento).data("tipo-preg") == 'caritas'){
			$("#pregunta" + preg).find("i").removeClass("active");
			$("#pregunta" + preg).find("i").removeClass("active-o");
			$("#pregunta" + preg).find(".mdi-face-" + face).addClass("active");
			$("#pregunta" + preg).find("p").removeClass("active");
			$("#pregunta" + preg).find(".p-face-" + face).addClass("active");
			setTimeout(function() {
				$("#pregunta" + preg).find("i").removeClass("active");
				$("#pregunta" + preg).find(".mdi-face-" + face).addClass("active-o");
			}, 500);
		}
		$("#pregunta" + preg).find("img.inactive").css("display", "none");
		$("#pregunta" + preg).find("img.active").css("display", "block");
		$("#pregunta" + preg).find(".img").css("background-color", "#FF9900");
		$("#pregunta" + preg).find(".question").css("color", "#414142");
		var flg_obli = null;
		var id_alter = null;
		$.each(objJSON, function(index, data) {
			var liValor;
			if($(elemento).data("tipo-preg") == 'caritas'){
		        liValor = $(elemento).find(".mdi-face-" + face).parent().parent();
			}else if($(elemento).data("tipo-preg") == 'desplegable'){
				liValor = $('option:selected', elemento);
			}else if($(elemento).data("tipo-preg") == '2opciones'){
				liValor = $(elemento);
			}else if($(elemento).data("tipo-preg") == 'casilla'){
				liValor = $(elemento);
			}else if($(elemento).data("tipo-preg") == 'multiple'){
				liValor = $(elemento);
			}
			var idPregActual = liValor.data("id_pregunta");
			
			if (data.id_preg == idPregActual) {
				data.id_alter = liValor.data("id_alternativa");
				input = $(elemento).children().context;
				idInput = $(input).attr('id');
				checked = $('#' + idInput).is(':checked');
				if ($(elemento).data("tipo-preg") == 'casilla'){
					flg_obli = $(elemento).data("flg_obli");
					if(checked == true) {
						obj = {
							id_preg  : data.id_preg,
							id_alter : data.id_alter,
							flg_obli : flg_obli 
							  };
						if(data.id_alter != null){
							arrayCheck.push(obj);
						}
					}else if(checked == false) {
						eliminaralterByPreg(data.id_preg,data.id_alter);
					}
				}
			}
			if (data.flg_obli == 1 && data.id_alter != null) {
				contador++;
			}
		});
		cambiarProgressBar(contador);
		if ($("#pregunta" + preg).closest('.gallery-cell').data('last') == 1) {
			activarTapPanexCantPregObli(idCate);
		}
		if (contador == cantPregObligatorias) {
			if (contModal == 1) {
				openFAB();
				finishSound.playclip();
				contModal = 0;
			}
		}
	}
}

function eliminaralterByPreg(idPreg, idAlter){
	for(var i = 0; i < arrayCheck.length; i++){
		if(arrayCheck[i].id_preg == idPreg && arrayCheck[i].id_alter == idAlter){
			arrayCheck.splice(i, 1);
			break;
		}
	}
}

function cambiarProgressBar(contador) {
	var porct = (contador * 100) / cantPregObligatorias;
	var color = null;
	if (porct >= 0 && porct < 33) {
		color = '#FF7D00';
	} else if (porct > 33 && porct < 66) {
		color = '#FF8D00';
	} else {
		color = '#FF9900';
	}
	$('#progressBar').css('width', porct + '%');
	$('#progressBar').css('background-color', color);
	$('#divAvance').html(contador + ' / ' + cantPregObligatorias);
};

// tagInputPropuestasMejoras
var objJSONProp = [];

function initMagicSuggest() {
	var ms = $('#magicsuggest').magicSuggest({
		autoSelect : true,
		allowFreeEntries : true,
		placeholder : 'Escriba sus sugerencias',
		data : 'c_encuesta_efqm/getListaPropuestas'
	});
	$(ms).on('selectionchange', function() {
		objJSONProp = [];
		objJSONProp.push(ms.getSelection());
	});
}

//Artificio JSM
var idAulaPadre = null;
function initMagicSuggestAulas() {
	var ms = $('#magicSuggestAulas').magicSuggest({
		autoSelect : true,
		allowFreeEntries : false,
		maxSelection : 1,
		maxSuggestions : 7,
		noSuggestionText : 'No se encontr&oacute; el aula.',
		placeholder : 'Selecciona el aula del alumno',
		maxSelectionRenderer : function(v) {
			return 'Solo puede seleccionar un aula';
		},
		data : 'c_encuesta_efqm/getListaAulas'
	});
	$(ms).on('selectionchange', function() {
		if (ms.getSelection() != null && ms.getSelection().length == 1) {
			idAulaPadre = ms.getSelection()[0].id;
			$("#empezar").css("display", "block");
		} else {
			idAulaPadre = null;
			$("#empezar").css("display", "none");
		}
	});
}

//1 = success  mongo | 0 =error  mongo
function enviarEncuesta() {
	$("#siVista").prop("disabled", true);
	$("#labelNewPropM").remove();
	var comenPropM= $("#textAPropM").val();
	objJSONProp = objJSONProp.filter(function (e, i, arr) {
		   return objJSONProp.lastIndexOf(e) === i;
		});
	$.ajax({
		data : {
			objJson 	: objJSON,
			contador 	: contador,
			objJSONProp : objJSONProp,
			comenPropM  : comenPropM,
			idAulaPadre : idAulaGlobal,
			client_info : datosClient,
			arrayCheck  : arrayCheck
		},
		url : 'c_encuesta_efqm/enviarEncuesta',
		type : 'POST',
		async : true
	})
			.done(
					function(data) {
						data = JSON.parse(data);
						if (data.error == 1) {
							$("#nuevaPropM").css("display","none");
							$("#masPropM").css("display","none");
							$("#newPropM").css("display","none");
							$("#textoFinal").css("display","none");							
							mostrarNotificacion('success',
									'Su encuesta ha sido enviada :D');
							$(".bg-modal").css("opacity", "1");
							$(".bg-modal").css("background-color", "#EDECEC");
							$(".bg-modal").find(".header-senc").css("display",
									"block");
							$("#propuestaMejora").fadeOut(250);
							$(".bg-modal").find(".header-senc").addClass(
									"animated slideInDown");
							$("#encuesta-init").find(".send").css("display",
									"none");
							$("#fab-hdr").css("display",
							"none");
							
							$("#encuesta-init").find(".finish").css("display",
									"block");
							$('#divSelect').remove();
							$('#comentarioPropM').remove();							
							$('#modalFinalEnc').css('display','block');
						} else {
							$('#modalFinalEnc').remove();
           					mostrarNotificacion('warning', data.msj);
							$("#siVista").prop('disabled', false);
						}
					});
	$("#siVista").prop('disabled', true);
}

function activarTapPanexCantPregObli(idCate) {
	var idCateaMostrar = null;
	var indexActual = null;
	$(".tab-pane").each(function(index) {
		if (index == ((indexActual == null) ? -1 : (indexActual + 1))) {
			idCateaMostrar = $(this).attr('id');
		}
		if (idCate == $(this).attr('id')) {
			indexActual = index;
		}
	});
	$(".tab-pane").each(function(index) {
		if (idCateaMostrar == null) {// En la ultima iteracion (pregunta)
			if (index == $(".tab-pane").length - 1) {
				ocultarMostrarTabPane($(this), true);
			} else {
				ocultarMostrarTabPane($(this), false);
			}
		} else {
			if (idCateaMostrar != $(this).attr('id')) {
				ocultarMostrarTabPane($(this), false);
			} else {
				ocultarMostrarTabPane($(this), true);
			}
		}
	});
}

function ocultarMostrarTabPane(objThis, flgMostrar) {
	if (flgMostrar) {
		$('#' + objThis.attr('id')).show();
		$("#a_" + objThis.attr('id')).addClass("active");

	} else {
		$('#' + objThis.attr('id')).hide();
		$("#a_" + objThis.attr('id')).removeClass('active');
	}
	$('.tab-pane').scrollTop();
}

function mostrarCardBienvenida() {
	$('#modalInicioEncuesta').fadeOut(7500);
	$('.modal-backdrop fade in').css('display', 'none');
	setTimeout(function() {
		abrirCerrarModal('modalInicioEncuesta');
	}, 7000);
}

// Evento de sonidos
var html5_audiotypes = {
	"mp3" : "audio/mpeg",
	"mp4" : "audio/mp4",
	"ogg" : "audio/ogg",
	"wav" : "audio/wav"
};

function createsoundbite(sound) {
	var html5audio = document.createElement('audio')
	if (html5audio.canPlayType) { // Comprobar soporte para audio HTML5
		for (var i = 0; i < arguments.length; i++) {
			var sourceel = document.createElement('source')
			sourceel.setAttribute('src', arguments[i])
			if (arguments[i].match(/.(w+)$/i))
				sourceel.setAttribute('type', html5_audiotypes[RegExp.$1])
			html5audio.appendChild(sourceel)
		}
		html5audio.load()
		html5audio.playclip = function() {
			html5audio.pause()
			html5audio.currentTime = 0
			html5audio.play()
		}
		return html5audio
	} else {
		return {
			playclip : function() {
				throw new Error('Su navegador no soporta audio HTML5')
			}
		}
	}
}

function cerrarModalFinalizar() {
	$('#btnFinalizar').css("display", "block");
	$('#btnFinalizar').find(".mdi-send").css("color", "#FFF");
	abrirCerrarModal('modalFinalizarEncuesta');
}

function cerrarModalFinalizarAux() {
	$('#btnFinalizar').css("display", "block");
	$('#btnFinalizar').find(".mdi-send").css("color", "#FFF");
}

function activeEffectIcon(id, face) {
	$('#' + id).find('.hvr-icon-float-' + face).addClass('hidden');
}

function activeNavBar(id) {
	$('.tab-pane').removeClass('active');
	$('#' + id).addClass('active');
}

// Iniciar Encuesta
var errorServicio = 0;

function initEncuesta() {
	comenzarEncuesta();
	if (errorServicio == 0) {
		$("#modal-init").find(".cards").fadeOut();
		setTimeout(function() {
			$('#contEnc').fadeIn();
		}, 500);
	} else {
		mostrarNotificacion('warning', 'no hay preguntas con ese servicio');
	}
};

var contActive = 0;
var contBtn = 0;

function pintarActive(objServ, idServ) {
	var flg_acti = objServ.attr('attr-flg_active');
	btn = $('.card--services .btn-service').size(); 
	if (flg_acti == 0) {
		objServ.addClass('active');
		arrayServ.push({
			serv : idServ
		});
		objServ.attr('attr-flg_active', '1');
		contBtn++;
		if (contBtn >= 0) {
			$('#empezar').css("display", "none");
			$('#listo').css("display", "block");
		}
	} else if (flg_acti == 1) {
		objServ.removeClass('active');
		for (var i = 0; i < arrayServ.length; i++) {
			if (arrayServ[i].serv == idServ) {
				arrayServ.splice(i, 1);
				break;
			}
		}
		objServ.attr('attr-flg_active', '0');
		contBtn--;
	}
	if (arrayServ.length == 0) {
		$('#empezar').css("display", "block");
		$('#listo').css("display", "none");
	} else if (arrayServ.length > 0) {
		$('#empezar').css("display", "none");
		$('#listo').css("display", "block");
	}
}

function selectPropMejora() {
	selePropM = $('#selectPropM').val();
	for(var i = 0; i < selePropM.length; i++) {
		objJSONProp.push(selePropM[i]);
	}
	$('#textAPropM').prop('disabled',false);
}

function comenzarEncuesta() {
	$.ajax({
		data : {
			arrayServ : arrayServ,
		},
		url : 'c_encuesta_efqm/getPreguntasCategoriasEncuesta',
		type : 'POST',
		async : false
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 0) {
			$('#overflow-wrap').html(data.categoriaHTML);
			$('#tabConpreg').html(data.preguntasHTML);
			objJSON = JSON.parse(data.jsonObj);
			arrCateObliJSON = data.arrCateObliJSON;
			cantPregObligatorias = data.cant_pregObligatorias;
			$('#divAvance').html(data.barraAvance);
			activarMainGallery(data.idCategMainGalleryFirst);//
			$.material.init();
			$(function() {
				$('[data-toggle="tooltip"]').tooltip();
			});

			if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
				$('.selectBootstrap').selectpicker('mobile');
			} else {
				$('.selectBootstrap').selectpicker({
					noneSelectedText : 'Seleccione una opción'
				});
			}
		} else{
			mostrarNotificacion('warning',data.msj);
		}
		errorServicio = data.error;
	});
}

// Variables
var overlay = $("#overlay"), fab = $(".fab"), cancel = $("#noVista"), submit = $("#siVista");

// Opciones Modal Final
fab.click(function() {
	openFAB();
});
overlay.click(function() {
	closeFAB();
});
cancel.click(function() {
	closeFAB();
});

function openFAB() {
	if (event)
		event.preventDefault();
	fab.css("visibility", "visible");
	$(".bg-modal").css("display", "block");
	fab.addClass('active');
	overlay.addClass('dark-overlay');
};

function closeFAB() {
	if (event) {
		event.preventDefault();
		event.stopImmediatePropagation();
	}
	fab.removeClass('active');
	$(".bg-modal").css("display", "none");
	overlay.removeClass('dark-overlay');
};

/* Cambiar a Servicios */
var buttons = $('.main-nav ');
buttons.bind('click', function() {
	if (!$(this).hasClass('active')) {
		$('.btn.active').removeClass('active');
		$(this).addClass('active');
	}
	buttons.css("display", "none");
});

function evaluaServiciosByEncuesta(){
	$.ajax({
		url : 'c_encuesta_efqm/evaluaServiciosInEncuesta',
		type : 'POST',
		async : false
	}).done(function(data) {
		data = JSON.parse(data);
		  if(data.serviciosCount == 0 && data.encuestaActiva == 1){
			if (data.error == 0) {
				$('#overflow-wrap').html(data.categoriaHTML);
				$('#tabConpreg').html(data.preguntasHTML);
				arrCateObliJSON = data.arrCateObliJSON;
				cantPregObligatorias = data.cant_pregObligatorias;
				$('#divAvance').html(data.barraAvance);
				activarMainGallery(data.idCategMainGalleryFirst);
				$.material.init();
				$(function() {
					$('[data-toggle="tooltip"]').tooltip()
				});
			}
		}
	});
}

function getGradosNivel() {
	var idSede = $('#cmbSede option:selected').val();
	$.ajax({
		url: "c_utils/getComboGradosNivelBySede",
        data: { idSede   : idSede },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0 || data.error == 2) {
			setCombo('cmbGradoNivel', data.optGradoNivel, 'Grado - Nivel');
			setCombo('cmbAula', null, 'Aula');
			selectAula();
		} else if(data.error == 1) {
			mostrarNotificacion('error', data.msj, 'Error');
		}
	});
}

function getAulasByGradoNivel() {
	var idSede = $('#cmbSede option:selected').val();
	var idgradoNivel = $('#cmbGradoNivel option:selected').val();
	$.ajax({
		url: "c_utils/getComboAulasByGradoNivel",
        data: { idSede       : idSede,
        	    idgradoNivel : idgradoNivel },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0 || data.error == 2) {
			setCombo('cmbAula', data.optAulas, 'Aula');
			selectAula();
		} else if(data.error == 1) {
			mostrarNotificacion('error', data.msj, 'Error');
		}
	});
}

var idAulaGlobal = 0;

function selectAula() {
	var idAula = $('#cmbAula option:selected').val();
	idAulaGlobal = idAula;
	if(tipoEncuGlobal == 'P' || tipoEncuGlobal == 'A') {
		if(idAula == "" || idAula == null || idAula == undefined || objJSON[0] == undefined || objJSON == null ) {
			$("#empezar").css("display", "none");
		} else if(idAula != "" && idAula != null && idAula != undefined && 
				  objJSON[0] != undefined && objJSON != null && objJSON[0].id_preg != null && objJSON[0].id_alter != null ) {
			$("#empezar").css("display", "block");
		}
	} else if(tipoEncuGlobal == 'I'){//Invitado
		if(objJSON[0] == undefined || objJSON == null ) {
			$("#empezar").css("display", "none");
		} else if(objJSON[0] != undefined && objJSON != null && objJSON[0].id_preg != null && objJSON[0].id_alter != null ) {
			$("#empezar").css("display", "block");
		}
	}
}

var tipoEncuGlobal = null;
function selectTipoEncuestado(tipoEncu) {
	tipoEncuGlobal = tipoEncu;
	$("#btnEmpezarUno").css("display", "block");
}

var arraFavoPropM = [];
function envNuevaPropM(){
	$('#textAPropM').prop('disabled',false);
	var selePropM = $('#selectPropM').val();
	var string = $('#newPropM').val();
	var porcion = (string.trim()).split(' ');
	if(porcion.length > 5 || porcion == null){
			mostrarNotificacion('warning','Debe ingresar un m&aacute;ximo de 5 palabras');
	}else{
		$.ajax({
			data  : { newPropM  : string,
				      selePropM : selePropM},
	        url   : 'c_encuesta_efqm/registraNuevaPropM',
		    type  : 'POST',
		    async : false
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1){
			} else {
				setMultiCombo('selectPropM', data.propuestaMHTML);
				objJSONProp.push(data.idNewPropMejora);
			}
			
		});
		
	}
	    $('#newPropM').val('');
	    $("#inputPropM").find("label.control-label").removeClass("active-input");
	    $('#nuevaPropM').css("background-color", "#EDEDED");
		$('#nuevaPropM').find("i").css("color", "#FFF");
}

//display = 0 oculta el div | display = 1 muestra el div
var display  = 0;
//firstTime = 0 primera vez al ajax | firstTime = 1 no entra ajax
var firstTime = 0;
function verMasPropM(){	
	if(firstTime == 0){
		$('#masPropM').html("VER MENOS");
		$.ajax({
			data  : { arraEncPropM : arraFavoPropM },
	        url   : 'c_encuesta_efqm/mostrarPropMrestantes',
		    type  : 'POST',
		    async : false
		})
		.done(function(data) {
				data = JSON.parse(data);
				if(data.error == 1){
					toastr.error(data.msj);
				} else {
					$('#mostrarMasPropM').html(data.arraPropMRest);
					$('#mostrarMasPropM').fadeIn(200);
				}
		});
		firstTime = 1;
	} else{
		if(display == 0){
			$(".fab.active").css("height", "45%");
			$('#mostrarMasPropM').fadeOut(200);
			display  = 1;
			$('#masPropM').html("VER MAS");
		} else{
			$('#mostrarMasPropM').fadeIn(200);
			display  = 0;
			$('#masPropM').html("VER MENOS");
		}
	}
}

function resize(valor){
	if((display == 0 && valor == 0) || (display == 1 && valor == 0)){
		$(".fab.active").css("height", "56px");
	} else if((display == 1 && valor == 1) || (display == 0 && valor == 1)){
		$(".fab").css("height", "55%");
	}
}

function initCardElegirAula() {
	comenzarEncuesta();
	if (errorServicio == 0) {
		$("#modal-init").removeClass("slideInDown");
		$("#modal-init").find(".header-senc").addClass("slideOutUp");
		$("#modal-init").removeClass("slideInUp");
		$("#modal-init").find(".cards").addClass("slideOutDown");
		$("#modal-init").find(".cards").fadeOut(750);
		setTimeout(function() {
			$("#modal-aula-init").removeClass("slideInDown");
			$("#modal-aula-init").find(".header-senc").addClass("slideOutUp");
			$("#modal-aula-init").removeClass("slideInUp");
			$("#modal-aula-init").find(".cards").addClass("slideOutDown");
			$("#modal-aula-init").find(".cards").fadeOut(750);
		}, 500);
	} else {
		mostrarNotificacion('warning', 'no hay preguntas con ese servicio');
	}
};

function initEncuestaDocente(){
	$.ajax({
		url   : "c_encuesta_efqm/getSedes",
        async : false,
        type  : 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		setCombo('selectSede', data.optSedes, 'Sede');
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.pickerButn').selectpicker('mobile');
		} else {
			$('.pickerButn').selectpicker();
		}
	});
}

function getGradosNivel() {
	var idSede = $('#selectSede option:selected').val();
	$.ajax({
		url   : "c_utils/getComboGradosNivelBySede",
        data  : { idSede   : idSede },
        async : false,
        type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		setCombo('selectGradoNivel', data.optGradoNivel, 'Grado - Nivel');
		setCombo('selectAula', null, 'Aula');
		$("#infoAulaElegida").css('display','none');
	});
}

function getAulasByGradoNivel() {
	var idSede = $('#selectSede option:selected').val();
	var idgradoNivel = $('#selectGradoNivel option:selected').val();
	$.ajax({
		url   : "c_utils/getComboAulasByGradoNivel",
        data  : { idSede       : idSede,
        	    idgradoNivel : idgradoNivel },
        async : false,
        type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		setCombo('selectAula', data.optAulas, 'Aula');
		$("#infoAulaElegida").css('display','none');
	});
}

function getInfoAulaElegida(){
	var idAula = $('#selectAula option:selected').val();
	if(idAula.length != 0){
		$.ajax({
			url   : "../c_encuesta_efqm/getInfoAulaElegida",
	        data  : { idAula : idAula },
	        async : false,
	        type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.perm == 1){
				$("#urlGenerada").val(data.urlTiny);
				$("#urlGenerada1").text(data.urlTiny);
				$("#contUrlGenerada").css('display','block');
			}else{
				$("#urlGenerada").val("");
				$("#urlGenerada1").text("");
				$("#contUrlGenerada").css('display','none');
			}
			$('#cantEncRealizadas').text(data.encRealiz);
			$("#cantEstudiantes").text(data.cantAlum);
			$("#infoAulaElegida").css('display','block');
		});
	}else{
		$("#infoAulaElegida").css('display','none');
	}
}

function actualizarCantAlumnos(){
	var idAula = $('#selectAula option:selected').val();
	$("#btnActAlumnos").prop("disabled", true);
	$.ajax({
		url   : "c_encuesta_efqm/getInfoAulaElegida",
        data  : { idAula : idAula },
        async : true,
        type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#cantEncRealizadas').text(data.encRealiz);
		$("#cantEstudiantes").text(data.cantAlum);
		$("#btnActAlumnos").prop("disabled", false);
	});
}

function goToEncuestaAlumno(aula, aux, tipo){
	window.open(window.location.pathname+'?aula='+aula+"&aux="+aux+"&tipo="+tipo);
}

function activarBtnAgregar(){
	var cantPalabra = $('#newPropM').val();
	if( (cantPalabra.length) > 0 ){
		$('#nuevaPropM').css("background-color", "#FF9200");
		$('#nuevaPropM').find("i").css("color", "#FFF");
	}else if ( (cantPalabra.length) == 0){
		$('#nuevaPropM').css("background-color", "#EDEDED");
		$('#nuevaPropM').find("i").css("color", "#FFF");
	}
}