var tipoEncuGlobal = null
function avanzar() {
	$('#modal-init').closest('section').css('display', 'none');
	$.ajax({
		data  : {tipoEncuGlobal : tipoEncuGlobal},
		url : 'c_vista_previa/getPreguntasCategoriasEncuesta',
		type : 'POST',
		async : false
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 0) {
			$('#categorias').html(data.categoriaHTML);
			$('#preguntas').html(data.preguntasHTML);
			$('#barraProgreso').css("display","block");
			objJSON = JSON.parse(data.jsonObj);
			arrCateObliJSON = data.arrCateObliJSON;
			cantPregObligatorias = data.cant_pregObligatorias;
			$('#divAvance').html(data.barraAvance);
			$("body").tooltip({ selector: '[data-toggle=tooltip]' });
			if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
				$('.selectBootstrap').selectpicker('mobile');
			} else {
				$('.selectBootstrap').selectpicker();
			}
			$('header .mdl-layout__tab-bar-container').fadeIn();
			componentHandler.upgradeAllRegistered();
		}
		errorServicio = data.error;
	});
}

function selectTipoEncuestadoVistaPrevia(tipoEncuestado, element){
	tipoEncuGlobal = tipoEncuestado;
	$("#btnEmpezarUno").css("display", "block");
	$(element).find("a").addClass("active");
}


function setIndexCategoriaVistaPrevia(index){
	indexCategoria = index;
	$(".mdl-layout__tab-panel").removeClass("is-active");
	$("#categoria"+index).addClass("is-active");
	$(".mdl-layout__tab").removeClass( "is-active" );
	$("#c_"+index).addClass( "is-active" );
}

function openModalChangeEstado(){
	abrirCerrarModal('modalAperturarCerrarEncuesta');
}

function cambiarEstadoEncuesta(idEncuesta){
	$.ajax({
		data  : {idEncuesta : idEncuesta},
        url   : 'c_vista_previa/cambiarEstadoEncuesta',
        type  : 'POST',
        async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			mostrarNotificacion('successs',data.msj);
			$("#aperturar").css("display", "none");
			$("#aperturar").remove();
			abrirCerrarModal('modalAperturarCerrarEncuesta');
		} else{
			mostrarNotificacion('warning',data.msj);
			abrirCerrarModal('modalAperturarCerrarEncuesta');
		}
	});
}

function selectAnswerVista(face, preg, idCate, val, elemento, cont_preguntas) {
//	setTimeout(function(){
	var flg_active = elemento.children().hasClass('active');	
	var flg_obli = elemento.data('flg_obli');
	if(flg_active == true && flg_obli == "0"){
		elemento.children().removeClass("active");
		elemento.children().removeClass("active-o");
		id_pregu  = elemento.parent().data('id_pregunta');
		id_altern = elemento.parent().data('id_alternativa');
		for(var i = 0; i < objJSON.length; i++){
			if(id_pregu ==objJSON[i].id_preg  && id_altern == objJSON[i].id_alter){
				objJSON[i].id_alter = null;
			}
		}
	}else{
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
		        liValor = elemento.find(".mdi-" + face).parent().parent();
			}else if(elemento.data("tipo-preg") == 'desplegable'){
				liValor = $('option:selected', elemento);
			}else if(elemento.data("tipo-preg") == '2opciones'){
				liValor = elemento;
			}else if(elemento.data("tipo-preg") == 'casilla'){
				liValor = elemento;
			}else if(elemento.data("tipo-preg") == 'multiple'){
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
		}
		if (contador == cantPregObligatorias) {
			if (contModal == 1) {
				$('.fab').css('display', 'block');
				openFAB();
				finishSound.playclip();
				contModal = 0;
			}
		}
		
		if($('#cont_pregunta_'+(cont_preguntas+1)).length){
			$('#cont_pregunta_'+(cont_preguntas)).addClass("active");
			$('#cont_pregunta_'+(cont_preguntas+1)).addClass("active");
		}
	}
//	},1500);
}

/**
 * @author 
 */
function imprimirEncuesta(idEncuesta) {
	if(idEncuesta == null || idEncuesta == undefined) {
		return;
	}
	var path = '';
	var error = null;
	$.ajax({
        url: "c_vista_previa/imprimirEncuesta",
        data : { idEncuesta : idEncuesta},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		error = data.error;
		if(data.error == 0) {
			path = data.file;
			window.open(location.origin+'/smiledu/'+data.file, '_blank');
		}
	})
	.always(function(jqXHR, textStatus, jqXHR2) {
		if(error == 0) {
			setTimeout(function() {
	        	borrar(path);
			}, 1500);	
    	}
	});
}

/**
 * @author diego
 */
function borrar(path) {
	$.ajax({
		data : { ruta : path },
        url: "c_vista_previa/borrar",
        async : false,
        type: 'POST'
	})
	.done(function(data) {
	});
}