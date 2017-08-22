function initSolicitud() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_solicitudes').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	generarBotonMenu();
}

function insertarSolicitudes(){
	var idPuesto = $('#selectPuesto option:selected').val();
	var idArea   = $('#selectArea option:selected').val();
	var idAreaEsp= $('#selectAreaEsp option:selected').val();
	var idSede   = $('#selectSede option:selected').val();
	var observaciones = $('#textAareaObs').val();
	var cantidad = $('#cantidad').val();
	$.ajax({
		data  : {idPuesto      : idPuesto,
			     idArea        : idArea,
			     idAreaEsp     : idAreaEsp,
			     idSede        : idSede,
			     observaciones : observaciones,
			     cantidad      : cantidad},
        url   : 'c_solicitud_personal/grabarSolicitud',
        type  : 'POST',
        async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			refrescaModal(data);
			mostrarNotificacion('success' , data.msj , 'Registro');
			$('#contTablaSolicitudes').html(data.tablaSolicitudes);
			$('#tb_solicitudes').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			initSearchTableNew();
			generarBotonMenu();
		} else{
			mostrarNotificacion('warning' , data.msj , 'Ojo');
		}
	});
}

function refrescaModal(data){
	$('#textAareaObs').val('');
	$('#cantidad').val('1');
	setCombo('selectPuesto' , data.comboPuesto , 'Puesto');
	setCombo('selectArea'   , data.comboArea   , '\xc1rea');
	setCombo('selectAreaEsp', null		       , '\xc1rea Espec\xedfica');
	setCombo('selectSede'   , data.comboSede   , 'Sede');
	abrirCerrarModal('modalFiltro');
}

function abrirModalSolicitud(){	
	$.ajax({
		url   : 'c_solicitud_personal/getDataModal',
		type  : 'POST',
		async : false 
	})
	.done(function(data){
		data = JSON.parse(data);		
		refrescaModal(data);		
	});
}

function abrirModalCambiarEstado(btn){
	var btnId    = btn.id;
	var estado = $('#'+btnId).attr('attr-estado');
	var idVacante = $('#'+btnId).attr('attr-idvac');
	if(estado == 'ANULADO' || estado == 'CONTRATADO'){
		mostrarNotificacion('warning','No se puede cambiar el estado');
	}
	else {
		$.ajax({
			data  : {idVacante : idVacante},
			url   : 'c_solicitud_personal/getDataModalEstado',
			type  : 'POST',
			async : false
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.radio != ""){
				$('#contRadioEstados').html(data.radio);
				$('#contBtnCambia').html(data.btn);
				abrirCerrarModal('modalCambiaEstado');
			} else {
				mostrarNotificacion('warning' , data.msj);
			}
			
		});
	}
}

function cambiaEstadoSolicitud(btn){
	var estado = $('input[name="radioVals"]:checked').val();
	var idVacante = $('#btnAcepta').attr('attr-idvac');
	$.ajax({
		data  : {idVacante : idVacante,
				 estado    : estado},
		url   : 'c_solicitud_personal/cambiaEstadoSolicitud',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 1){
			mostrarNotificacion('warning' , data.msj);
		} else{
			$('#contTablaSolicitudes').html(data.tablaSolicitudes);
			$('#tb_solicitudes').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu');			
			generarBotonMenu();
			initSearchTableNew();
			mostrarNotificacion('success' , data.msj);
			abrirCerrarModal('modalCambiaEstado');
		}
	});
}

function generarBotonMenu(){
	var div = $('#contTablaSolicitudes .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append( '<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="btn btn-icon-toggle" data-toggle="dropdown" style="margin-top:-4px;margin-left: 2px;width: 35px;margin-right:-10px">'+
		             '<i class="mdi mdi-more_vert"></i>'+
			        '</button>'+
			        '<ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu" style="background-color:#fafafa">'+
			            '<li><a href="#"><i class="mdi mdi-print"></i> Imprimir</a></li>'+
			            '<li><a href="#"><i class="mdi mdi-file_download"></i>Descargar</a></li>'+
			        '</ul>'+
			    '</div>');
}

function logOut(){
	$.ajax({
		url  : 'c_solicitud_personal/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function getAreasEspeficicas(){
	var idArea   = $('#selectArea option:selected').val();
	$.ajax({
		data  : {idArea : idArea},
		url   : 'c_solicitud_personal/buildComboAreasEspecificas',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		setCombo('selectAreaEsp' , data.comboAreaEsp , data.nombre);
	});
}