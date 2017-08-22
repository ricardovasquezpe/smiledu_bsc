function initCertIngDoc() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_docentes').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	generarBotonMenu();
	tableEventsCerti();
}

function cambioCheckCert(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckCertificacion("tb_docentes", index, 3, cb.checked, cb.id, cb.value, cb.getAttribute('attr-iddocente'), cb.getAttribute('attr-bd'));
}

function onChangeCheckCertificacion(idTabla, indexRow, indexCampo, nuevoValor, id, value, idDocente, bd){
	var check = "checked";
	if(nuevoValor == false){
		check = "";
	}
	var idx = parseInt(indexRow) + 1;
	var bdVal = (bd == 'checked') ? true : false;
	var cambio = false;
	if(nuevoValor == bdVal) {
		cambio = false;
	} else {
		cambio = true;
	}
	var newCheck =  
				    '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				    '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckCert(this);" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-iddocente="'+idDocente+'"> '+
				    '        <span class="mdl-checkbox__label"></span>'+
				    '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : indexCampo,
		fieldValue : newCheck
	});
	var idNativo    = 'nati'+idx;
	var checkNativo = ($('#'+idNativo).is(':checked') == true) ? 'checked' : '';
	var bdNativo    = $('#'+idNativo).attr('attr-bd');
	var cambioNati  = $('#'+idNativo).attr('attr-cambio');
	
	var demas = false;
	if(cambioNati == 'true') {
		demas = true;
	}
	var foco = $('#'+idNativo).attr('attr-foco');
	
    foco = (!demas) ? cambio : foco;
	//Cambiar el check de Nativo
	var newCheck =  
				    '    <label for="'+idNativo+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				    '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckNativo(this);" '+checkNativo+' id="'+idNativo+'" attr-bd="'+bdNativo+'" attr-cambio="'+cambioNati+'" attr-foco="'+foco+'" attr-iddocente="'+idDocente+'" attr-efce="'+check+'"> '+
				    '        <span class="mdl-checkbox__label"></span>'+
				    '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 4,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function cambioCheckNativo(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckNativo("tb_docentes", index, 4, cb.checked, cb.id, cb.getAttribute('attr-iddocente'), cb.getAttribute('attr-bd'));
}

function onChangeCheckNativo(idTabla, indexRow, indexCampo, nuevoValor, id, idDocente, bd){
	var check = "checked";
	if(nuevoValor == false){
		check = "";
	}
	var idx = parseInt(indexRow) + 1;
	var bdVal = (bd == 'checked') ? true : false;
	var cambio = false;
	if(nuevoValor == bdVal) {
		cambio = false;
	} else {
		cambio = true;
	}
	var idCerti     = 'check'+idx;
	var checkCerti  = ($('#'+idCerti).is(':checked') == true) ? 'checked' : '';
	var bdCert      = $('#'+idCerti).attr('attr-bd');
	var cambioCerti = $('#'+idCerti).attr('attr-cambio');
	
	var demas = false;
	if(cambioCerti == 'true') {
		demas = true;
	}
	var foco = $("#"+id).attr('attr-foco');
    foco = (!demas) ? cambio : foco;
    
	//Cambiar el check de Nativo
	var newCheck =  
				    '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				    '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckNativo(this);" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-foco="'+foco+'" attr-iddocente="'+idDocente+'" attr-efce="'+checkCerti+'"> '+
				    '        <span class="mdl-checkbox__label"></span>'+
				    '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : indexCampo,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function grabarDocentesIngles(){
	var json = {};
	var docentes = [];
	var cont = 0;
	json.docente = docentes;
	var arryDivs = getCheckedFromTablaByAttrFOCO('tb_docentes', 4);
	$.each( arryDivs, function( key, value ) {
		var idDocente = $(value).find(':checkbox').attr('attr-iddocente');
		var chkNati   = $(value).find(':checkbox').is(':checked');
		var chkCerti  = $(value).find(':checkbox').attr('attr-efce');
		var docente = {"idDocente" : idDocente, "chkNati" : chkNati, "chkCerti": chkCerti};
 		json.docente.push(docente);
 		cont++;
	});
	if(cont == 0) {
		//mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringDocentes = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_cert_ing_doc/grabarDocentesIngles',
		data : { docentes : jsonStringDocentes },
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			result = JSON.parse(data);
			if(result.error == 0) {
				$('#contTbDocentes').html(result.tablaDocentes);
				$('#tb_docentes').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');				
				generarBotonMenu();
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
				tableEventsCerti();
				mostrarNotificacion('success', 'Se ha modificado', 'Registro');
			}
		}
	});
}

function generarBotonMenu(){
	var div = $('#contTbDocentes .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="grabarDocentesIngles()" id="btnGuardarTabla">'+
					'<i class="mdi mdi-save"></i>'+
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

function logOut(){
	$.ajax({
		url  : 'c_cert_ing_doc/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function tableEventsCerti(){
	var textGlob = null; 
	$(function () { 
		$('#tb_docentes').on('all.bs.table', function (e, name, args) {
			
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