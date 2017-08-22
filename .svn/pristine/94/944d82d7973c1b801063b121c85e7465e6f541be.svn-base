function initGradoPPU(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_grado_ppu').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	initButtonLoad( 'btnMF', 'btnMP' );
}

function getNivelBySede() {
	var idSede =  $('#selectSede option:selected').val();
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_grado_ppu/comboGradosNivel',
    	data   : {idSede : idSede},
    	'async': false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectNivel', data.optNivel, 'Nivel');			
				$('#contTablaPPU').html(data.tabPPU);
				$('#tb_grado_ppu').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');				
				generarBotonMenu();
				initSearchTableNew();
			} else if(data.error == 1) {
				mostrarNotificacion('error', data.msj, 'Error');
			}
		}
	});
}

function getPPUfromGrado() {
	addLoadingButton('btnMF');
	var idPPu   =  $('#selectPPu option:selected').val();
	var idNivel =  $('#selectNivel option:selected').val();
	var idSede  =  $('#selectSede option:selected').val();
	Pace.restart();
	Pace.track(function() {

		$.ajax({
			url: "c_grado_ppu/getPPUfromGrado",
		    data: { 	    	   
		    	    idNivel : idNivel,
		    	    idSede  : idSede,
		    	    idPPu   : idPPu},
			async : true,
			type : 'POST'
		})
		.done(function(data){
			if(data == ""){
				location.reload();
			} else{
				data = JSON.parse(data);
				if(data.error == 0 ||data.error == 2) {
					if(data.error == 0){
						$('#puestoSede').addClass('dirty');
					}
					$('#puestoSede').val(data.puestoSede);
					$('#contTablaPPU').html(data.tabPPU);
					$('#tb_grado_ppu').bootstrapTable({ });
					$('.fixed-table-toolbar').addClass('mdl-card__menu');	
					$('main section .mdl-content-cards .img-search').css('display', 'none');
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectSede  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectNivel option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectPPu   option:selected').text());
					$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
					$('main section .mdl-content-cards .mdl-card').removeAttr('style');					
					generarBotonMenu();
					initSearchTableNew();
					componentHandler.upgradeAllRegistered();
					stopLoadingButton('btnMF');
				} else if(data.error == 1) {
					mostrarNotificacion('error', data.msj, 'Error');
					stopLoadingButton('btnMF');
				}
			}
			stopLoadingButton('btnMF');
		});
	});
}

function onChangePuesto(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellPuesto("tb_grado_ppu", index, 2, input.value, input.id, input.getAttribute('attr-idgrado'),input.getAttribute('attr-bd'));
}

function updateCellPuesto(idTabla, indexRow, indexCampo, nuevoValor, id,idGrado,bd) {

	var cambio = false;
	 if(nuevoValor == bd) {
		cambio = false;	
	} else  {
		cambio = true;
	}
	
	var newCheck = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
			           '<input type="text" onchange="onChangePuesto(this);" class="mdl-textfield__input" value="'+nuevoValor+'" '+
	    			   'name="puesto" id="'+id+'"  attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idgrado="'+idGrado+'">'+
    			       '<label class="mdl-textfield__label" for="'+id+'"></label>'+
    			   '</div>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function ppu(){
	var idNivel =  $('#selectNivel option:selected').val();
	var idPPu = $('#selectPPu option:selected').val();
	var idSede =  $('#selectSede option:selected').val();
	if(idNivel == null || idPPu == null || idSede == null || idNivel == '' || idPPu == '' || idSede == '') {
		error = 1;
		mostrarNotificacion('warning', 'Seleccionar las opciones');
		return;
	}
	var puestoSede = $('#puestoSede').val();
	if(puestoSede == null || puestoSede == undefined || puestoSede.trim().length == 0) {
		error = 1;
		mostrarNotificacion('warning', 'Ingrese el puesto de la sede');
		return;
	}
	if(!/^[1-9]([0-9]*)$/.test(puestoSede) || puestoSede <= 0) {
		error = 1;
		mostrarNotificacion('warning', 'El puesto de la sede debe ser num&eacute;rico');
		return;
	}
	var cont = 0;
	var json = {};
	var puestos = [];
    var h = 0;
	json.puesto = puestos;
	var arryDivs = getInputTextFromTablaByAttr('tb_grado_ppu', 2);
	$.each( arryDivs, function( key, value ) {
		var idGrado   = $(value).attr('attr-idgrado');
		var puesto    = $(value).val();
		var fila      = $(value).attr('id');
		    value     = $.trim(puesto);
		var puestoArr = {"idGrado" : idGrado, "puesto" : puesto, "fila" : fila };
		if(/^[1-9]([0-9]*)$/.test(puesto) && puesto>0 || value.length == 0){
 			json.puesto.push(puestoArr);
 			cont++;
 		}else{
 			mostrarNotificacion('warning', 'No se registro un puesto en la fila : '+fila, 'Ojo: ');
			error = 1;
			return;
 		}
	});
	if(cont == 0) {
		//mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringPuestos = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url  : 'c_grado_ppu/grabarGradoPuestos',
		data : { puesto     : jsonStringPuestos, 
			    idNivel     : idNivel,
			      idPPu     : idPPu, 
			     idSede     : idSede,
			     puestoSede : puestoSede},
	   async : false,
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaPPU').html(data.tabPPU);
				$('#tb_grado_ppu').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTable();
				generarBotonMenu();
				mostrarNotificacion('success', data.msj, data.cabecera);			
		    }
		}
	});
}

function abrirTablaAula(btn){
	var idGrado = btn.getAttribute('attr-idgrado')
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_grado_ppu/comboGradosAula',
    	data   : {idGrado : idGrado},
    	'async': false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);	
			if(data.error == 0) {
				$('#selectAula').find('option').remove().end().append('<option value="">Selec. Aula</option>'+data.optAula);
				$('select[name=selectAula]').val("");
				$('#selectAula').selectpicker('refresh');
			} else {
				mostrarNotificacion('error', data.msj, 'Error');
			}	
		}
	});
	
	abrirCerrarModal('modalAula');
	$('#contTbAulaByAlumno').html('');
}

function initTableAlumnos(){
	var idAula =  $('#selectAula option:selected').val();
	var idPPu = $('#selectPPu option:selected').val();
	$.ajax({
	url: "c_grado_ppu/getAllAulaByAlumno",
    data: { idAula   : idAula,
    	    idPPu    : idPPu},
			   async : false,
			    type : 'POST'
	})
	.done(function(data){console.log(data);
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			
			$('#contTbAulaByAlumno').html(data.AulaByAlumnoTable);
			$('#tb_aula_alumno').bootstrapTable({});
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			initSearchTableNew();
		}
	});
}

function onChangePuestoAlumno(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellPuestoAlumno("tb_aula_alumno", index, 2, input.value, input.id, input.getAttribute('attr-persona'),input.getAttribute('attr-bd'),input.getAttribute('attr-alumn'));
}

function updateCellPuestoAlumno(idTabla, indexRow, indexCampo, nuevoValor, id,idPersona, bd, idAlumno) {
	var cambio = false;
	if(nuevoValor == bd){
		cambio = false;	
	}else{
		cambio = true;
	}	
	var newCheck = '<input type="text" onchange="onChangePuesto(this);" class="form-control" value="'+nuevoValor+'" '+
	   			   'name="puesto" id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-persona="'+idPersona+'" attr-alumn="'+idAlumno+'">';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newCheck
	});
}

function capturarPuntajeAlumno(){
	var json    = {};
	var puestos = [];
	var h       = 0;
	json.puesto = puestos;
	var arryDivs = getInputTextFromTablaByAttr('tb_aula_alumno', 2);
	$.each( arryDivs, function( key, value ) {
		var idPersona = $(value).attr('attr-persona');
		var puesto    = $(value).val();		
		var fila      = $(value).attr('id');
			value     = $.trim(puesto)
		var puestoArr = {"idPersona" : idPersona, "puesto_alumno" : puesto, "fila" : fila};
		if(/^[1-9]([0-9]*)$/.test(puesto) && puesto>0 || value.length == 0){
			json.puesto.push(puestoArr);
			h++;
 		}else{
 			mostrarNotificacion('warning', 'No se registro un puesto en la fila : '+fila, 'Ojo: ');
			error = 1;
			return;
 		}		 			
	});
	var jsonStringPuestos = JSON.stringify(json);
	var idAula   =  $('#selectAula option:selected').val();
	var idPPu    =  $('#selectPPu option:selected').val();
	$.ajax({
		 type : 'POST',
		 url  : 'c_grado_ppu/grabarAlumnosPuestos',
		 data : { idAula : idAula,
			      idPPu  : idPPu,
		   puesto_alumno : jsonStringPuestos},
			    
		async : false,
		 type : 'POST'
	})  	
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbAulaByAlumno').html(data.AulaByAlumnoTable);
				$('#tb_aula_alumno').bootstrapTable({});
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTable();
				if(h>0){
					mostrarNotificacion('success', 'Tiene ' + h + ' puesto registrado', data.cabecera);
				}	
			}
		}
	});
}

function generarBotonMenu(){
	var div = $('#contTablaPPU .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append( '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="ppu()" id="btnGuardarTabla" >'+
	        		'<i class="mdi mdi-save"></i>'+
			    '</button>'+
			    '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="abrirModalPuesto()" >'+
	        		'<i class="mdi mdi-equalizer"></i>'+
			    '</button>'+
			    '<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" data-toggle="dropdown" id="buttonMoreVert">'+
		            	'<i class="mdi mdi-more_vert"></i>'+
			        '</button>'+
			        '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="buttonMoreVert">' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-print"></i> Imprimir</li>' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-file_download"></i> Descargar</li>' +
					'</ul>'+
			     '</div>');
}

function abrirModalPuesto(){
	abrirCerrarModal('modalPuesto');
}

function logOut(){
	$.ajax({
		url   : 'c_grado_ppu/logOut', 
		async : false,
		type  : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function guardarPuestoPpuGrado(){
	var idSede     = $('#selectSede option:selected').val();
	var idPPu      = $('#selectPPu option:selected').val();
	var idNivel    = $('#selectNivel option:selected').val();
	var puestoSede = $('#puestoSede').val();
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnMP');
		$.ajax({
			url: "c_grado_ppu/grabarPpuSedeGrado",
		    data: { idSede     : idSede,
		    	    idPPu      : idPPu,
		    	    idNivel    : idNivel,
		    	    puestoSede : puestoSede},
		    async : false,
		    type : 'POST'
		}).done(function(data){
			if(data == ""){
				location.reload();
			} else{
				stopLoadingButton('btnMP');
				data = JSON.parse(data);
				if(data.error == 1){
					mostrarNotificacion('warning','','Los datos no fueron editados');
				} else{
					mostrarNotificacion('success','Se ha modificado', data.msj);
					abrirCerrarModal('modalPuesto');
				}
			}
		});
	});
}

function abrirModalFiltro(){
	/*$('#selectNivel').find('option').remove().end().append('<option value="">Selec. Nivel</option>');
	$('select[name=selectNivel]').val("");
	$('#selectNivel').selectpicker('refresh');
	
	$('select[name=selectPPu]').val("");
	$('#selectPPu').selectpicker('refresh');
	
	$('select[name=selectSede]').val("");
	$('#selectSede').selectpicker('refresh');
	
	$('#puestoSede').val('');*/
	
	abrirCerrarModal('modalFiltro');
}
