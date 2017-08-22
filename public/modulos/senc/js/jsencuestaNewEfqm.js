var objJSON = [];
var arrCateObliJSON = null;
var cantPregObligatorias = null;
var contModal = 1;
var contador = 0;
var contNext = 1;
var postNext = 0;
var arrayServ = [];
var arrayCheck = [];
var indexCategoria = 0;
var elementoGlobalM = null;
function selectAnswer(face, preg, idCate, val, elemento, cont_preguntas) {

	var flg_active = elemento.children().hasClass('active-o');
	var flg_obli = elemento.data('flg_obli');
	if(flg_active == true && flg_obli == "0"){
		elemento.children().removeClass( "active" );
		elemento.children().removeClass("active-o");
		id_pregu  = elemento.parent().data('id_pregunta');
		id_altern = elemento.parent().data('id_alternativa');
		for(var i = 0; i < objJSON.length; i++){
			if(id_pregu ==objJSON[i].id_preg  && id_altern == objJSON[i].id_alter){
				objJSON[i].id_alter = null;
			}
		}
	} else{
		contador = 0;
		if(idCate == 0) {
			objJSON = [];
			obj = { id_preg  : elemento.data('id_pregunta'),
					id_alter : elemento.data('id_alternativa') 
				  };
			objJSON.push(obj);
			$("#empezar").css("display", "block");
		}
		last = $('.main-gallery' + idCate + ' .gallery-cell').size();	
		if(elemento.data("tipo-preg") == 'caritas'){
			$("#" + preg).find("i").removeClass("active");
			$("#" + preg).find("small").removeClass("active");
			$("#" + preg).find("i").removeClass("active-o");
			$("#" + preg).find("small").removeClass("active-o");
			$("#" + preg).find(".mdi-" + face).addClass("active");		
			$("#" + preg).find(".small-" + face).addClass("active");		
			setTimeout(function() {
				$("#" + preg).find("i").removeClass("active");
				$("#" + preg).find("small").removeClass("active");
				$("#" + preg).find(".mdi-" + face).addClass("active-o");		
				$("#" + preg).find(".small-" + face).addClass("active-o");
			}, 500);
		}
		var flg_obli = null;
		var id_alter = null;
		$.each(objJSON, function(index, data) {
			var liValor;
			if($(elemento).get(0).tagName == 'A' && elemento.data("tipo-preg") == 'caritas'){
				elementoGlobalM = elemento.data("tipo-preg");
		        liValor = elemento.find(".mdi-" + face).parent().parent();
			}else if(elemento.data("tipo-preg") == 'desplegable'){
				elementoGlobalM = elemento.data("tipo-preg");
				liValor = $('option:selected', elemento);
			}else if(elemento.data("tipo-preg") == '2opciones'){
				elementoGlobalM = elemento.data("tipo-preg");
				liValor = elemento;
			}else if(elemento.data("tipo-preg") == 'casilla'){
				elementoGlobalM = elemento.data("tipo-preg");
				liValor = elemento;			
			}else if(elemento.data("tipo-preg") == 'multiple'){
				elementoGlobalM = elemento.data("tipo-preg");
				liValor = elemento;				
			}
			var idPregActual = liValor.data("id_pregunta");			
			if (data.id_preg == idPregActual) {
				data.id_alter = liValor.data("id_alternativa");
				input = elemento.children().context;
				idInput = $(input).attr('id');
				checked = $('#' + idInput).is(':checked');
				if (elemento.data("tipo-preg") == 'casilla'){
					flg_obli = elemento.data("flg_obli");
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
		if ($("#" + preg).closest('.gallery-cell').data('last') == 1) {
			if ($("#categoria"+(indexCategoria + 1)).length ){
				$("#categoria"+indexCategoria).removeClass("is-active");
				$("#c_"+indexCategoria).removeClass("is-active");
				
				indexCategoria = indexCategoria + 1;
				
				$("#categoria"+indexCategoria).addClass("is-active");
				$("#c_"+indexCategoria).addClass("is-active");
			}
			$(".mdl-layout__content").animate({ scrollTop: 0 }, "slow");
		}
		if (contador == cantPregObligatorias) {
			if (contModal == 1) {
				if(elementoGlobalM == 'multiple' || elementoGlobalM == 'casilla'){
					setTimeout(function() {
						$('.fab').css('display', 'block');
						openFAB();
						finishSound.playclip();
						contModal = 0;
					}, 100);
				}else if(elementoGlobalM == 'caritas' || elementoGlobalM == '2opciones' || elementoGlobalM == 'desplegable'){
					$('.fab').css('display', 'block');
					openFAB();
					finishSound.playclip();
					contModal = 0;
				}
			}
		}
		
		if($('#cont_pregunta_'+(cont_preguntas+1)).length){
			$('#cont_pregunta_'+(cont_preguntas)).addClass("active");
			$('#cont_pregunta_'+(cont_preguntas+1)).addClass("active");
		}
	}
}

function setIndexCategoria(index){
	indexCategoria = index;
	$(".mdl-layout__tab-panel").removeClass("is-active");
	$("#categoria"+index).addClass("is-active");
	$(".mdl-layout__tab").removeClass( "is-active" );
	$("#c_"+index).addClass( "is-active" );
	$(".mdl-layout__content").animate({ scrollTop: 0 }, "slow");
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

function enviarEncuesta() {
	Pace.restart();
	Pace.track(function() {
		$("#siVista").prop("disabled", true);
		$("#siVista").unbind('click');
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
			type : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {//SUCCESS
				$("#nuevaPropM").css("display","none");
				$("#masPropM").css("display","none");
				$("#newPropM").css("display","none");
				$("#textoFinal").css("display","none");
				mostrarNotificacion('success', 'Su encuesta ha sido enviada');
				$(".bg-modal").css("opacity", "1");
				$(".bg-modal").css("background-color", "#EDECEC");
				$(".bg-modal").find(".header-senc").css("display", "block");
				$("#propuestaMejora").fadeOut(250);
				$(".bg-modal").find(".header-senc").addClass("animated slideInDown");
				$("#encuesta-init").find(".send").css("display", "none");
				$("#fab-hdr").css("display", "none");
				$('#divSelect').remove();
				$('#comentarioPropM').remove();
				$("#siVista").prop('disabled', true);
				$('#sendEncuesta').remove();
				$('.mdl-layout__header').remove();
				$('.mdl-layout__content').remove();
				$('.mdl-layout__tab-panel').remove();
				$('.bg-modal').remove();
				$('.fab').css("display", "none");
				$('#modalFinal').css("display","block");
			} else {//ERROR
				$('#modalFinalEnc').remove();
				mostrarNotificacion('warning', data.msj);
				$("#siVista").prop('disabled', false);
			}
		});
	});
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
	//$('#' + id).find('.hvr-icon-float-' + face).addClass('hidden');
}

function activeNavBar(id) {
	$('#' + id).addClass('active');
}

// Iniciar Encuesta
var errorServicio = 0;

function initEncuesta() {
	comenzarEncuesta();
	if (errorServicio == 0) {
		$("#modal-init").closest("section").css('display', 'none');
		$('header .mdl-layout__tab-bar-container').fadeIn();
		setTimeout(function() {
			$('#preguntas').css('display', 'block');
		}, 1000);
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
			$('#categorias').html(data.categoriaHTML);
			//setIndexCategoria(index);
			$('#preguntas').html(data.preguntasHTML);
			$('#barraProgreso').css("display","block");
			objJSON = JSON.parse(data.jsonObj);
			arrCateObliJSON = data.arrCateObliJSON;
			cantPregObligatorias = data.cant_pregObligatorias;
			$('#divAvance').html(data.barraAvance);
			componentHandler.upgradeAllRegistered();
			$(document).ready(function() {
			    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
			});
			if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
				$('.selectBootstrap').selectpicker('mobile');
			} else {
				$('.selectBootstrap').selectpicker();
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
fab.click(function(e) {
	openFAB(e);
});
overlay.click(function(e) {
	closeFAB(e);
});
cancel.click(function(e) {
	closeFAB(e);
});

function openFAB(event) {
	if (event){
		event.preventDefault();
	}
	fab.css("visibility", "visible");
	$(".bg-modal").css("display", "block");
	fab.addClass('active');
	overlay.addClass('dark-overlay');
};

function closeFAB(event) {
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
		if(data.error == 1){
			mostrarNotificacion('warning', data.msj);
		} else if(data.serviciosCount == 0 && data.encuestaActiva == 1){
		    if (data.error == 0) {
			    $('#overflow-wrap').html(data.categoriaHTML);
				$('#tabConpreg').html(data.preguntasHTML);
				arrCateObliJSON = data.arrCateObliJSON;
				cantPregObligatorias = data.cant_pregObligatorias;
				$('#divAvance').html(data.barraAvance);
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
		url: "../c_utils/getComboGradosNivelBySede",
        data: { idSede   : idSede },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0 || data.error == 2) {
			if(data.error == 2){
				mostrarNotificacion('warning',data.msj);
			}
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
		url: "../c_utils/getComboAulasByGradoNivel",
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
		if(idAula == "" || idAula == null || idAula == undefined ) {
			$("#empezar").css("display", "none");
		} else if(idAula != "" && idAula != null && idAula != undefined ) {
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
				mostrarNotificacion('warning',data.msj);
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

var msj = null;
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
		url   : "../c_utils/getComboGradosNivelBySede",
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
		url   : "../c_utils/getComboAulasByGradoNivel",
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
	Pace.restart();	
	Pace.track(function() {
		var idAula = $('#selectAula option:selected').val();
		$("#infoAulaElegida").css('display','none');
		$("div[name='infoAulaElegida']").css('display','none');
		if(idAula.length != 0){
			$.ajax({
				url   : "c_encuesta_efqm/getInfoAulaElegida",
		        data  : { idAula : idAula },
		        async : true,
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
				$('#btnLockAlum').children(":first").removeAttr('class').attr('class', data.candado);
				$('#cantEncRealizadas').text(data.encRealiz);
				$("#cantEstudiantes").text(data.cantAlum);
				$("#infoAulaElegida").css('display','block');
			});
		}else{
			$("#infoAulaElegida").css("display", "none");
		}
	});
}

function actualizarCantAlumnos(){
	Pace.restart();	
	Pace.track(function() {
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
	});
}

function goToEncuestaAlumno(aula, aux, tipo){
	window.location = window.location.pathname+'?aula='+aula+"&aux="+aux+"&tipo="+tipo;
}

function activarBtnAgregar(){
	var cantPalabra = $('#newPropM').val();
	if( (cantPalabra.length) > 0 ){
		$('#nuevaPropM').removeClass('mdl-color-text--grey-500');
		$('#nuevaPropM').addClass('mdl-color--orange-500 mdl-color-text--white');
	}else if ( (cantPalabra.length) == 0){
		$('#nuevaPropM').removeClass('mdl-color--orange-500 mdl-color-text--white');
		$('#nuevaPropM').addClass('mdl-color-text--grey-500');
	}
}

function lockAulaEncuestaEFQM_Estu() {
	var idAula = $('#selectAula option:selected').val();
	if(!idAula) {
		return;
	}
	Pace.restart();	
	Pace.track(function() {
		$.ajax({
			url   : "c_encuesta_efqm/bloquearAulaEncuEFMQEstu",
	        data  : { idAula : idAula },
	        async : true,
	        type  : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#btnLockAlum').children(":first").removeAttr('class').attr('class', data.candado);
			}
			mostrarNotificacion('error', data.msj);
		});
	});
}

function redirect(base, encuFisica) {
	if(encuFisica == undefined) {
		$("#siguienteForm")[0].submit();
	} else {
		window.open(base+'senc/c_encuesta_nueva/c_encuesta_efqm?encu_fisica='+encuFisica, '_self', false);
	}
}

function redirectLogin(){
	$.ajax({
		url   : 'c_encuesta_efqm/redirectLoginPadres',
		type  : 'POST',
		async : false 
	})
	.done(function(data){
		window.location.replace(data);
	});
}

function iniciarLlenadoFisico() {
	var idSede = getComboVal('selectSede');
	var idGradoNivel = getComboVal('selectGradoNivel');
	var idAula = getComboVal('selectAula');
	var idEstu = getComboVal('selectEstu');
	if(!idSede || !idGradoNivel || !idAula || !idEstu) {
		mostrarNotificacion('error', 'Seleccione la Sede / Grado / Aula / Estudiante');
		return;
	}
	$('#formEncuFisica').submit();
	
}

function setIdAulaHidden() {
	setCombo2('selectEstu', null);
	$('#aula').val(getComboVal('selectAula'));
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url   : "c_encuesta_efqm/getEstudiantesEncuFisica",
	        data  : { idAula : getComboVal('selectAula') },
	        type  : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				setCombo('selectEstu', data.comboEstus, 'Estudiante', false);
				$('#idEstu').val(null);
			} else {
				msj('error', data.msj);
			}
		});
	});
}

function setIdEstudianteHidden() {
	$('#idEstu').val(getComboVal('selectEstu'));
}