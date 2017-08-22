 function initPersona_rol() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_persona_rol').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	tableEventsCerti();
	initButtonLoad( 'btnMF' );
}
 
function getPersonaByRol() {
	addLoadingButton('btnMF');
	var idRol = $('#selectRol option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_persona_rol/getAllPersonaByRol",
	        data: { idRol   : idRol},
	        async : true,
	        type: 'POST'
		})
		.done(function(data) {
			if(data == "") {
				location.reload();
				stopLoadingButton('btnMF');
			} else {
				data = JSON.parse(data);
				$('#contTbPersona').html(data.personaRolesTable);
				$('#tb_persona_rol').bootstrapTable({});
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				tableEventsCerti();
				componentHandler.upgradeAllRegistered();	
				$('main section .mdl-content-cards .img-search').css('display', 'none');
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectRol option:selected').text());
				$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
				$('main section .mdl-content-cards .mdl-card').removeAttr('style');	
				if(data.fabOpcNuevoPers != undefined) {
					$('#idRolSelected').val(idRol);
					$('#fabOpciones').html(data.fabOpcNuevoPers);
				} else {
					$('#idRolSelected').val(null);
					$('#fabOpciones').html('<li>'+
										      '<a href="javascript:void(0);" data-mfb-label="Seleccionar Rol" class="mfb-component__button--child mdl-color--indigo">'+
										          '<i class="mfb-component__child-icon md md-edit" onclick="abrirCerrarModal(\'modalFiltro\')" style="font-size: 20px;padding-top: 1px;color:white;margin-top: -6px;">'+
										          '</i>'+
										      '</a>'+
										  '</li>');
				}
				stopLoadingButton('btnMF');
			}
		});
	});
} 
//

function abrirAsignarRoles(btn){
	abrirCerrarModal('modalAsignarRoles');
	initTableRoles(btn.getAttribute('attr-idpersona'));
	tableEventsCerti('contTbRolesPersona');	
}

function initTableRoles(idPersona){
	$.ajax({
		url: "c_persona_rol/getAllRolesByPersona",
        data: { idPersona   : idPersona},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			$('#contTbRolesPersona').html(data.rolesByPersonaTable);
			$('#tb_roles_all_persona').bootstrapTable({});
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			tableEventsCerti();
			componentHandler.upgradeAllRegistered();
			//initSearchTable();
		}
	});
}

function cambioCheckRol(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckRol("tb_roles_all_persona", index, 2, cb.checked, cb.id, cb.getAttribute('attr-idpersona'),
			         cb.getAttribute('attr-bd'), cb.getAttribute('attr-idrol'));
	tableEventsCerti();
}

function onChangeCheckRol(idTable, index, column, nuevoValor, id, idPersona, bd, idRol){
	var check = "checked";
	if(nuevoValor == false ){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	var classC = "";
	if(nuevoValor == bdVal){
		cambioCheck = false;
		classC = "";
	}else{
		cambioCheck = true;
		classC = "checkbox-gris";
	}
	
	var checkRol =  
				    '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				    '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckRol(this);" '+check+' id="'+id+'" attr-bd="'+bd+'"' +
				    '			attr-cambio="'+cambioCheck+'" attr-idpersona="'+idPersona+'" attr-idrol="'+idRol+'">' +
				    '        <span class="mdl-checkbox__label"></span>' +
				    '    </label>';
	
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 2,
		fieldValue : checkRol
	});
	tableEventsCerti();
	componentHandler.upgradeAllRegistered();
}

function cambioCheckPermiso(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckPermiso("tb_persona_permiso", index, 1, cb.checked, cb.id, cb.getAttribute('attr-idpersona'),
			         cb.getAttribute('attr-bd'), cb.getAttribute('attr-idpermiso'));
	tableEventsCerti();
}

function onChangeCheckPermiso(idTable, index, column, nuevoValor, id, idPersona, bd, idPermiso){
	var check = "checked";
	if(nuevoValor == false ){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	var classC = "";
	if(nuevoValor == bdVal){
		cambioCheck = false;
		classC = "";
	}else{
		cambioCheck = true;
		classC = "checkbox-gris";
	}
	
	var checkRol =  
				    '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				    '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckPermiso(this);" '+check+' id="'+id+'" attr-bd="'+bd+'"' +
				    '			attr-cambio="'+cambioCheck+'" attr-idpersona="'+idPersona+'" attr-idpermiso="'+idPermiso+'">' +
				    '        <span class="mdl-checkbox__label"></span>' +
				    '    </label>';
	
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 1,
		fieldValue : checkRol
	});
	tableEventsCerti();
	componentHandler.upgradeAllRegistered();
}

function capturarRolesPersona(){
	var json = {};
	var cont = 0;
	var personas = [];
	json.persona = personas;
	var arrayData = getCheckedFromTablaByAttr('tb_roles_all_persona', 2);
	$.each( arrayData, function( key, value ) {
		var idPersona = $(value).find(':checkbox').attr('attr-idpersona');
		var idRol     = $(value).find(':checkbox').attr('attr-idrol');
		var valor     = $(value).find(':checkbox').is(':checked');
		var persona = {"idPersona" : idPersona, "valor" : valor , "idRol" : idRol};
 		json.persona.push(persona);
 		cont++;
	});
	if(cont == 0) {
		mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringPersona = JSON.stringify(json);
	var idRolCombo = $('#selectRol option:selected').val();
	$.ajax({
		type : 'POST',
		url : 'c_persona_rol/grabarRolesPersona',
		data : { personas   : jsonStringPersona,
			     idRolCombo : idRolCombo}, 
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			$('#contTbRolesPersona').html(data.rolesByPersonaTable);
			$('#tb_roles_all_persona').bootstrapTable({ });
			$('#contTbPersona').html(data.personaRolesTable);
			$('#tb_persona_rol').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			tableEventsCerti();
			componentHandler.upgradeAllRegistered();
			//mostrarNotificacion('success', ''+data.msj, data.cabecera);
			abrirCerrarModal('modalAsignarRoles');
			initSearchTableNew();	
			mostrarNotificacion('success', 'Se ha modificado', 'Registro');
		}
	});
}

function capturarPermisosPersona(){
	var json = {};
	var cont = 0;
	var personas = [];
	json.persona = personas;
	var arrayData = getCheckedFromTablaByAttr('tb_persona_permiso', 1);
	tableEventsCerti();
	$.each( arrayData, function( key, value ) {
		var idPersona = $(value).find(':checkbox').attr('attr-idpersona');
		var idPermiso     = $(value).find(':checkbox').attr('attr-idpermiso');
		var valor     = $(value).find(':checkbox').is(':checked');
		var persona = {"idPersona" : idPersona, "valor" : valor , "idPermiso" : idPermiso};
 		json.persona.push(persona);
 		cont++;
	});
	if(cont == 0) {
		mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringPersona = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_persona_rol/grabarPermisosPersona',
		data : { permisos : jsonStringPersona}, 
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			abrirCerrarModal('modalAsignarPermisos');
			mostrarNotificacion('success', 'Se ha modificado', 'Registro');
		}
	});
}

function abrirAsignarPermisos(data){
	$.ajax({
		url  : 'c_persona_rol/getPermisosByPersona', 
		async: false,
		data : { idPersona : data}, 
		type : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contbPermisos').html(data.tabla);
		$('#tb_persona_permiso').bootstrapTable({ });		
		tableEventsCerti('contbPermisos');
		componentHandler.upgradeAllRegistered();
		abrirCerrarModal('modalAsignarPermisos');
	});
}

function logOut(){
	$.ajax({
		url  : 'c_persona_rol/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}
//
function openModalBuscarPersona() {
	abrirCerrarModal('modalBusqPersonas');
}
//
function buscarUsuarioForRol(e) {
	'use strict';
	if(e.keyCode != 13 && e.type == 'keyup') {//ENTER y TAB
		return;
	}
	var persBusq = $('#filtro_pers').val();
	var idRolSel = $('#idRolSelected').val();
	if($.trim(idRolSel) == "") {
		return false;
	}
	if($.trim(persBusq) != "") {
		$.ajax({
	        data: { persBusq : persBusq ,
	        	    idRolSel : idRolSel },
	        url: "c_persona_rol/buscarUsuarioForRol",
	        async: false,
	        type: 'POST'
	  	})
	  	.done(function(data) {
	  		data = JSON.parse(data);
			if(data.error == 0) {
				$('#divTbResultBusq').html(data.tbPersBusq);
				$('#tb_pers_busq').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				tableEventsCerti('tb_pers_busq');
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
			} else {
				mostrarNotificacion('error', 'Contacte con la persona a cargo', 'Error');
			}
	  	});
	} else {
		$('#divTbResultBusq').html(null);
	}
}
//Checked ROL
var arryPersGlobal = [];
function cambioCheckPersRol(checkBoxInstance) {
	var checked = checkBoxInstance.is(':checked');
	var idPers = checkBoxInstance.data('id_persona');
	if(checked) {
		arryPersGlobal.push(idPers);
	} else {
		$.each(arryPersGlobal, function(index, value) {
			if(value == idPers) {
				arryPersGlobal.splice(index, 1);
			}
		});
	}
}
//
function grabarPersRoles() {
	if(arryPersGlobal.length > 0) {
		var idRolSel = $('#idRolSelected').val();
		$.ajax({
	        data: { arryPersGlobal : arryPersGlobal ,
	        	          idRolSel : idRolSel },
	        url: "c_persona_rol/grabarPersonaRol",
	        async: false,
	        type: 'POST'
	  	})
	  	.done(function(data) {
	  		data = JSON.parse(data);
			if(data.error == 0) {
				$('#filtro_pers').val(null);
				$('#divTbResultBusq').html(null);
				$('#contTbPersona').html(data.tbPrincipal);
				$('#tb_persona_rol').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
				abrirCerrarModal('modalBusqPersonas');			
				arryPersGlobal = [];
			} else {
				mostrarNotificacion('error', 'Contacte con la persona a cargo', 'Error');
			}
	  	});
	}
}

function tableEventsCerti(idTabContenedor){
	var textGlob = null; 
	$(function () { 
		$('#'+idTabContenedor).on('all.bs.table', function (e, name, args) {
			
	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check.bs.table', function (e, row) {

	    })
	    .on('uncheck.bs.table', function (e, row) {

	    })
	    .on('check-all.bs.table', function (e) {

	    })
	    .on('uncheck-all.bs.table', function (e) {

	    })
	    .on('load-success.bs.table', function (e, data) {

	    })
	    .on('load-error.bs.table', function (e, status) {

	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}