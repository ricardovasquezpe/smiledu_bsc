var idEncuesta 	  = null;
var idDispositivo = null;
var objProp    = [];
init();

function init(){
	initButtonLoad('btnMF', 'btnEPM');
}

function editPropMejora(idDisp,idEnc){
	$('#newPropM').val('');
    $('#newPropM').parent().removeClass('is-dirty');
	$('#nuevaPropM').css("background-color", "#EDEDED");
	$('#nuevaPropM').find("i").css("color", "#9E9E9E");
	var encuesta = $('#selectEncuesta option:selected').val();
	$.ajax({
		data  : {encuesta    : idEnc,
			     dispositivo : idDisp},
		url   : 'c_propuesta_mejora/getPropMejoraByComentario',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		setMultiCombo('selectPropM',data.optProp);
		idEncuesta 	  = encuesta;
		idDispositivo = idDisp; 
		abrirCerrarModal('editarPropuestasMejora');
	});
}

function getEncuestasByTipo(){
	addLoadingButton('btnMF');
	var tipo_encuesta = $('#selectTipoEncuesta option:selected').val();
	$.ajax({
		data  : {tipo_encuesta : tipo_encuesta},
		url   : 'c_propuesta_mejora/getEncuestaByTipoEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contTabPropuestas').html(data.tbComentarios);
		$('#tb_comentarios').bootstrapTable({ });
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		$('section .mdl-content-cards .img-search').css('display', 'none');
		$('section .mdl-content-cards .mdl-card').removeAttr('style');
		setCombo('selectEncuesta',data.optEnc,' una Encuesta');
		stopLoadingButton('btnMF');
	});
}

function getComentarioPropuestasMejoraByEncuesta(){
	addLoadingButton('btnMF');
	var encuesta 	  = $('#selectEncuesta option:selected').val();
	var tipo_encuesta = $('#selectTipoEncuesta option:selected').val();
	$.ajax({
		data  : {encuesta      : encuesta,
			     tipo_encuesta : tipo_encuesta},
		url   : 'c_propuesta_mejora/getComentariosByEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contTabPropuestas').html(data.tbComentarios);
		$('#tb_comentarios').bootstrapTable({ });
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		stopLoadingButton('btnMF');
	});
}

function activarBtnAgregar(){
	var cantPalabra = $('#newPropM').val();
	if( (cantPalabra.length) > 0 ){
		$('#nuevaPropM').css("background-color", "#FF9200");
		$('#nuevaPropM').find("i").css("color", "#FFF");
	}else if ( (cantPalabra.length) == 0){
		$('#nuevaPropM').css("background-color", "#EDEDED");
		$('#nuevaPropM').find("i").css("color", "#9E9E9E");
	}
}

function envNuevaPropM(){
	var selePropM = $('#selectPropM').val();
	var string = $('#newPropM').val();
	var porcion = (string.trim()).split(' ');
	if(porcion.length > 5 || porcion == null || porcion == ""){
		mostrarNotificacion('warning','Debe ingresar un m&aacute;ximo de 5 palabras');
	}else{
		$.ajax({
			data  : { newPropM   : string,
				      selePropM  : selePropM,
				      idEncuesta : idEncuesta,
				      objProp    : selePropM},
	        url   : 'c_propuesta_mejora/registraNuevaPropM',
		    type  : 'POST',
		    async : false
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1){
			} else {
				setMultiCombo('selectPropM', data.propuestaMHTML);
				$('#newPropM').val('');
			    $('#newPropM').parent().removeClass('is-dirty');
			    $('#nuevaPropM').css("background-color", "#EDEDED");
				$('#nuevaPropM').find("i").css("color", "#9E9E9E");
			}
			
		});
	}
}

function linkComentarioPropuesta(){
	var propuestas = $('#selectPropM').val();
	$.ajax({
		data  : {propuestas  : propuestas,
				 dispositivo : idDispositivo,
				 encuesta	 : idEncuesta},
		url   : 'c_propuesta_mejora/linkComentarioPropuesta',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		mostrarNotificacion('warning',data.msj);
		if(data.error == 1){
			abrirCerrarModal('editarPropuestasMejora');
		}
	});
}