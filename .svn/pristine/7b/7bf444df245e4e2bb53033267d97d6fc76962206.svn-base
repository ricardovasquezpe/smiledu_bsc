//updatear bien
function initRolPermSist() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_rolessist').bootstrapTable({ });
	$('#tb_sistperm').bootstrapTable({ });
	generarBotonMenuSistema();
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	tableEventsCerti('tb_rolessist');
	initButtonLoad( 'btnMF' );
}

function getSistemaByRol() {
	addLoadingButton('btnMF');
	var idRol = $('#selectRol option:selected').val();
	$.ajax({
		url: "c_roles_permisos_sistemas/getRolesFromSistema",
        data: { idRol   : idRol},
        async : true,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
			stopLoadingButton('btnMF');			
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaRolesSist').html(data.tabRolesSist);
				$('#tb_rolessist').bootstrapTable({ });			
				tableEventsCerti('tb_rolessist');
				generarBotonMenuSistema();		
				$("#tb_rolessist tr td").css('cursor','pointer');
				/*$("#tb_rolessist tr td").click(function() {
					componentHandler.upgradeAllRegistered();
					var check = $(this).parent().find('input:checkbox').is(":checked")
					if(check == true){
						var idSist = $(this).parent().find('input:checkbox').attr('attr-idSist');
						getPermisoBySistema(idSist,idRol);
					} else {
						$('#contTablaSistPerm').html(null);					
					}
				});*/
				$('#contTablaSistPerm').html(null);
				$('#tb_sistperm').bootstrapTable({ });
				tableEventsCerti('tb_sistperm');
				generarBotonMenuPermiso();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();
				$('main section .mdl-content-cards .img-search').css('display', 'none');
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectRol option:selected').text());
				$('#contTablaSistPerm').parent().find('.img-search').css('display', 'block');
				$('main section .mdl-content-cards [class^="col-"], [class*="col-"]').removeAttr('style');
				stopLoadingButton('btnMF');
			} else {
				console.log('error al cargar la tabla');
				stopLoadingButton('btnMF');
			}
		}
	});
}


function getPermisoBySistema(idSist,idRol){
	$.ajax({
		url: "c_roles_permisos_sistemas/getSistemaFromPermiso",
        data: { idSist   : idSist,
        	    idRol    : idRol},
        async : false,
        type: 'POST'
	}).done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaSistPerm').hide().html(data.tabSistPerm).fadeIn(1500);
				$('#tb_sistperm').bootstrapTable({ });
				generarBotonMenuPermiso();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				//tableEventsCerti('tb_rolessist');
				tableEventsCerti('tb_sistperm');
				componentHandler.upgradeAllRegistered();
			}
		}
	});
}

function cambioCheckSist(cb) {
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckSist("tb_rolessist", index, 2, cb.checked, cb.id, cb.value, cb.getAttribute('attr-idSist'), cb.getAttribute('attr-idRol'),cb.getAttribute('attr-bd'));
	tableEventsCerti('tb_sistperm');
}

var idSistGlobal = 0;
function onChangeCheckSist(idTabla, indexRow, indexCampo, nuevoValor, id, value, idSist,idRol,bd) {
	idSistGlobal = idSist;
	var check = "checked";
	if(nuevoValor == false) {
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false;
	var cambio = false;
	var classC = "";
	if(nuevoValor == bdVal) {
		cambio = false;	
		classC = "";
	} else {
		cambio = true;
		classC = "checkbox-gris";
	}
	
	var newCheck = 
				   '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				   '        <input type="checkbox" onclick="cambioCheckSist(this);" class="mdl-checkbox__input" '+check+' id="'+id+'"  attr-bd="'+bd+'" attr-cambio="'+cambio+'"  attr-idSist="'+idSist+'" attr-idRol="'+idRol+'"> '+
				   '        <span class="mdl-checkbox__label"></span>'+
				   '    </label>';

	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
	if(check == "checked") {
		var idRol = $('#selectRol option:selected').val();
		getPermisoBySistema(idSist,idRol);
	} else {
		$('#contTablaSistPerm').html(null);	
	}
	
	/*$("#tb_rolessist tr td").click(function() {
		var check = $(this).parent().find('input:checkbox').is(":checked")
		componentHandler.upgradeAllRegistered();
		if(check == true){
			var idSist = $(this).parent().find('input:checkbox').attr('attr-idSist');
			getPermisoBySistema(idSist,idRol);
			componentHandler.upgradeAllRegistered();
		}
		else{
			$('#contTablaSistPerm').html(null);					
		}
	});*/
}

function cambioCheckPerm(cb) {
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckPerm("tb_sistperm", index, 2, cb.checked, cb.id, cb.value, cb.getAttribute('attr-idSist'), cb.getAttribute('attr-idRol'),cb.getAttribute('attr-idPerm'),cb.getAttribute('attr-bd'));
}

function onChangeCheckPerm(idTabla, indexRow, indexCampo, nuevoValor, id, value, idSist,idRol,idPerm,bd) {
	var check = "checked";
	if(nuevoValor == false) {
		check = "";
	}
	
	var bdVal = (bd == 'checked') ? true : false;
	var cambio = false;
	var classC = "";
	 if(nuevoValor == bdVal) {
		cambio = false;	
		classC = "";
	} else {
		cambio = true;
		classC = "checkbox-gris";
	}
	
	var newCheck = 
				     '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				     '        <input type="checkbox" onclick="cambioCheckPerm(this);" class="mdl-checkbox__input" '+check+' id="'+id+'"  attr-bd="'+bd+'" attr-cambio="'+cambio+'"  attr-idSist="'+idSist+'" attr-idRol="'+idRol+'" attr-idPerm="'+idPerm+'"> '+
				     '        <span class="mdl-checkbox__label"></span>'+
				     '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newCheck
	});
	//componentHandler.upgradeAllRegistered();
}

function rolsist() {
	var idRol = $('#selectRol option:selected').val();
	var json = {};
	var rolsistem = [];
	json.rolsist = rolsistem;
	var arryDivs = getCheckedFromTablaByAttr('tb_rolessist', 2);
	
	$.each( arryDivs, function( key, value ) {
		var idSist = $(value).find(':checkbox').attr('attr-idSist');
		var idRol = $(value).find(':checkbox').attr('attr-idRol');
		var valor = $(value).find(':checkbox').is(':checked');
		var rolsist = {"idSist" : idSist,"idRol":idRol , "valor" : valor};
		
 		json.rolsist.push(rolsist);
	});
	var jsonStringRolsist = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_roles_permisos_sistemas/grabarRolesSistema',
		data : { rolsistem : jsonStringRolsist },
		async : false
	})
	.done(function(data) {
		if(data == ""){
			location.reload();
		} else{
			result = JSON.parse(data);
			if(result.error == 0) {			
				$('#contTablaRolesSist').html(result.tabRolesSist);
				$('#tb_rolessist').bootstrapTable({ });
				generarBotonMenuSistema();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				tableEventsCerti('tb_rolessist');
				tableEventsCerti('tb_sistperm');
				componentHandler.upgradeAllRegistered();
				mostrarNotificacion('success', result.msj, 'Registro');
				$("#tb_rolessist tr td").css('cursor','pointer');
				/*$("#tb_rolessist tr td").click(function() {
					var check = $(this).parent().find('input:checkbox').is(":checked")
					if(check == true){
						var idSist = $(this).parent().find('input:checkbox').attr('attr-idSist');
						getPermisoBySistema(idSist,idRol);
					} else {
						$('#contTablaSistPerm').html(null);					
					}
				});*/
				//mostrarNotificacion('', 'Datos Guardados Exitosamente', 'SUCCESS');
			} else {
				console.log('error al cargar la tabla');
			}
		}
	});
}

function sistpe() {
	rolsist();
	var cont = 0;
	var json = {};
	var sistperm = [];
	json.sistper = sistperm;
	var arryDivs = getCheckedFromTablaByAttr('tb_sistperm', 2);
	$.each( arryDivs, function( key, value ) {
		var idSist = $(value).find(':checkbox').attr('attr-idSist');
		var idRol = $(value).find(':checkbox').attr('attr-idRol');
		var idPerm = $(value).find(':checkbox').attr('attr-idPerm');
		var valor = $(value).find(':checkbox').is(':checked');

		var sistper = {"idSist" : idSist,"idRol":idRol , "idPerm":idPerm ,"valor" : valor};
 		json.sistper.push(sistper);
 		cont++;
	});
	if(cont == 0) {
		//mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringSistper = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_roles_permisos_sistemas/grabarSistemaPermiso',
		data : { sistperm : jsonStringSistper },
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			result = JSON.parse(data);
			if(result.error == 0) {			
				$('#contTablaSistPerm').html(result.tabSistPerm);
				$('#tb_sistperm').bootstrapTable({ });
				generarBotonMenuPermiso();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				tableEventsCerti('tb_rolessist');
				tableEventsCerti('tb_sistperm');
				componentHandler.upgradeAllRegistered();
				mostrarNotificacion('success', result.msj, 'Registro');
				//mostrarNotificacion('', 'Datos Guardados Exitosamente', 'Registro');
			} else {
				console.log('error al cargar la tabla');
			}
		}
	});
}
var first = 1;
function generarBotonMenuSistema(){
	var div = $('#contTablaRolesSist .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	if(first != 1){
		div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="rolsist()" id="btnGuardarTabla">'+
						'<i class="mdi mdi-save"></i>'+
				   '</button>'+
				   '<div class="btn-group btn-group-sm pull-right">'+
				        '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" data-toggle="dropdown" id="buttonMoreVertSistemas">'+
			           	'<i class="mdi mdi-more_vert"></i>'+
				        '</button>'+
				        '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="buttonMoreVertSistemas">' +
				        	'<li class="mdl-menu__item"><i class="mdi mdi-print"></i> Imprimir</li>' +
				        	'<li class="mdl-menu__item"><i class="mdi mdi-file_download"></i> Descargar</li>' +
						'</ul>'+
				    '</div>');
	}
	first = 0;
}

function generarBotonMenuPermiso(){
	var div = $('#contTablaSistPerm .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="sistpe()" id="btnGuardarTabla">'+
					'<i class="mdi mdi-save"></i>'+
			   '</button>'+
			   '<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" data-toggle="dropdown" id="buttonMoreVertPermisos">'+
		            	'<i class="mdi mdi-more_vert"></i>'+
			        '</button>'+
			        '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="buttonMoreVertPermisos">' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-print"></i> Imprimir</li>' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-file_download"></i> Descargar</li>' +
					'</ul>'+
			    '</div>');
}

function logOut(){
	$.ajax({
		url  : 'c_roles_permisos_sistemas/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function tableEventsCerti(idTablaContenedora){
	var textGlob = null; 
	$(function () { 
		$('#'+idTablaContenedora).on('all.bs.table', function (e, name, args) {
			componentHandler.upgradeAllRegistered();
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
    		if(idTablaContenedora == 'tb_rolessist'){
    			var check = $element.find('input:checkbox').is(":checked");
        		if(check == true){
        			var idRol  = $('#selectRol option:selected').val();
        			var idSist = $element.find('input:checkbox').attr('attr-idSist');
        			$('#contTablaSistPerm').parent().find('.img-search').css('display', 'none');
        			getPermisoBySistema(idSist,idRol);
        			//componentHandler.upgradeAllRegistered();
        		}else{
        			$('#contTablaSistPerm').html(null);
        			$('#contTablaSistPerm').parent().find('.img-search').css('display', 'block');
        		}
    		}
	    	//componentHandler.upgradeAllRegistered();
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check.bs.table', function (e, row) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('uncheck.bs.table', function (e, row) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check-all.bs.table', function (e) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('uncheck-all.bs.table', function (e) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('load-success.bs.table', function (e, data) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('load-error.bs.table', function (e, status) {
	    	componentHandler.upgradeAllRegistered();
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
