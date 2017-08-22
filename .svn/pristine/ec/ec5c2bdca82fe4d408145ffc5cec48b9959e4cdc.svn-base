function init() {
	$('#tb_traslado').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu'); 
	initSearchTableNew();
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initLimitInputs('motivoRechazo','motivoConfirmacion','motivoTrasladoDetalle');	

	$('<button>').attr({
		'id'   	:	'iconTraslado',
		'class'	:	'mdl-button mdl-js-button mdl-button--icon'
	}).appendTo('#contTablaBusqueda .fixed-table-toolbar .columns-right');

	$('<i>').attr({
		'class'	: 'mdi mdi-more_vert'
	}).appendTo('#iconTraslado');
}

function abrirModalMotivosTraslado(mTraslado, mConfirmacion, tipo){
	if(tipo == 0){
		$("#cont_motivo_confirmacion").css("display", "none");
		setearInput("motivoTrasladoDetalle", mTraslado);
	}else{
		$("#cont_motivo_confirmacion").css("display", "block");
		setearInput("motivoTrasladoDetalle", mTraslado);
		setearInput("motivoConfirmacion", mConfirmacion);
	}
	abrirCerrarModal("modalMotivosTraslado");
}

cons_id_traslado = null;
function abrirModalConfirmTraslado(idTraslado, nombreAlumno){
	cons_id_traslado = idTraslado;
	$("#nombreAlumnoTralado").text('Deseas trasladar al estudiante ' +nombreAlumno+' ?');
	$("#cont_aula_destino").css("display", "none");
	$("#cont_motivo_rechazo").css("display", "none");
	setearInput("motivoRechazo", null);
	abrirCerrarModal("modalConfirmTraslado");
	$('#aceptar').removeClass('mdl-color--green  mdl-color-text--white');
	$('#aceptar').addClass('mdl-color-text--green');
	$('#rechazar').removeClass('mdl-color--red  mdl-color-text--white');
	$('#rechazar').addClass('mdl-color-text--red');
}
cons_estado_traslado = null;
function selectOpcionTraslado(estado, tipo){
	cons_estado_traslado = estado;
	if(tipo == 0){
		$("#cont_motivo_rechazo").css("display", "none");
		$('#aceptar').removeClass('mdl-color--green mdl-color-text--white');
		$('#aceptar').addClass('mdl-button--raised');
		$('#rechazar').addClass('mdl-button--colored');  		
		$.ajax({
			type: 'POST',
			url: "c_solicitud_traslado/aulasDestinoTraslado",
	        data: {idtraslado : cons_id_traslado},
	        async: false
	  	})
	  	.done(function(data) {
	  		data = JSON.parse(data);
			setCombo('selectAulaDestinoConfirm', data.comboAulas, "Aula");
	  		$("#cont_aula_destino").css("display", "block");
	  		$('#aceptar').removeClass('mdl-button--raised');
	  		$('#aceptar').addClass('mdl-color--green mdl-color-text--white');
	  		$('#rechazar').removeClass('mdl-color--red mdl-color-text--white');
	  		$('#rechazar').addClass('mdl-button--colored');
	  		$('#modalConfirmTraslado .mdl-card__actions').fadeIn();
	  	});
	}else{
		$("#cont_aula_destino").css("display", "none");
		$("#cont_motivo_rechazo").css("display", "block");
  		$('#rechazar').addClass('mdl-color--red mdl-color-text--white');
  		$('#aceptar').removeClass('mdl-color--green mdl-color-text--white');
  		$('#aceptar').removeClass('mdl-button--raised');
  		$('#modalConfirmTraslado .mdl-card__actions').fadeIn();
	}
}

$('#modalConfirmTraslado').on('hidden.bs.modal', function () {
	$('#rechazar').removeClass('mdl-button--colored');
	$('#aceptar').removeClass('mdl-button--primary');
	$('#aceptar').addClass('mdl-button--primary');
	$('#rechazar').addClass('mdl-button--colored');
	$('#modalConfirmTraslado .mdl-card__actions').css("display", "none");
	
});

function trasladarAlumno(){
	if(cons_id_traslado != null && cons_estado_traslado != null){
		var motivoRechazo = $("#motivoRechazo").val();
		var idAula = $("#selectAulaDestinoConfirm").val();
		$.ajax({
			type: 'POST',
			url: "c_solicitud_traslado/cambiarEstadoTraslado",
	        data: { idtraslado     : cons_id_traslado,
	        		estadotraslado : cons_estado_traslado,
	        		motivoTraslado : motivoRechazo,
	        		idaula         : idAula},
	        async: false
	  	})
	  	.done(function(data) {
	  		data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaBusqueda').html(data.tablaSolicitudes);
				$('#tb_traslado').bootstrapTable({});
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				abrirCerrarModal("modalConfirmTraslado");
				mostrarNotificacion('success', data.msj);
			} else {		
				mostrarNotificacion('success', data.msj);
			}
	  	});
	}
}