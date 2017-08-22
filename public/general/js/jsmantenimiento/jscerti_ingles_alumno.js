function init(){
    initButtonLoad('btnCIA');
}

function initcerti_ingles_alumno() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_alumnos').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	
	$('#selectSede').change(function(){
		val = $('#selectSede option:selected').val();
	    $.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_certi_ingles_alumno/getGradosBySede',
	    	data   : {val : val},
	    	'async': false	    	
	    })
	    .done(function(data){
	    	if(data == ""){
    			location.reload();
    		} else{
    			$('#selectGrado').html(data).selectpicker('refresh');
    		}
	     });

	});
	
	$('#selectGrado').change(function(){
		valSed = $('#selectSede option:selected').val();
		valGra = $('#selectGrado option:selected').val();
	    $.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_certi_ingles_alumno/getAulasBySedeGrado',
	    	data   : {valGra : valGra,
	    			  valSed : valSed},
	    	'async': false	    	
	    })
	    .done(function(data){
	    	if(data == ""){
    			location.reload();
    		} else{
    			$('#selectAula').html(data).selectpicker('refresh');
    		}
	     });
	});
	
	$('#selectAula').change(function(){
		addLoadingButton('btnCIA');
		val = $('#selectAula option:selected').val();
	    $.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_certi_ingles_alumno/getTableAlumnos',
	    	data   : {val : val},
	    	'async': true	    	
	    })
	    .done(function(data){
	    	if(data == ""){
    			location.reload();
	    		stopLoadingButton('btnCIA');
    		} else{
		    	var dato	= JSON.parse(data);
		    	$('#contTabAlumnos').html(dato.tbAlumnos);
				$('#tb_alumnos').bootstrapTable({ });  				
				generarBotonMenu();
				initSearchTableNew()
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				$('.fixed-table-toolbar').addClass('mdl-card__menu');	
				$('main section .mdl-content-cards .img-search').css('display', 'none');
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectSede  option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectGrado option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectAula  option:selected').text());
				$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
				$('main section .mdl-content-cards .mdl-card').removeAttr('style');	
				componentHandler.upgradeAllRegistered();
				tableEventsCerti();
				stopLoadingButton('btnCIA');
    		}
	     });
	});
}

function cambioCheckAprobo(cb){
	var index = $(cb).closest('tr').attr('data-index');//console.log('index: '+index);
	onChangeCheckAprobo("tb_alumnos", index, 3, cb.checked, cb.id, cb.value, cb.getAttribute('attr-idalumno'), cb.getAttribute('attr-bd'));
}

function onChangeCheckAprobo(idTabla, indexRow, indexCampo, nuevoValor, id, value, idAlumno, bd){
	var check = "";
	var state = "P";	
	var bdVal = (bd == 'A') ? true : false;
	var cambio = false;
	var classC = "";
	if(nuevoValor == bdVal) {
	    if(bd == 'P'){
	    	cambio = false;
	    	classC = "";
	    }else if(bd == 'A'){
	    	cambio = false;
	    	classC = "";
	    }else {
	    	cambio = true;
	    	classC = "checkbox-gris";
	    }
	} else {
		cambio = true;
		classC = "checkbox-gris";
	}
	if(nuevoValor === true){	
		check = "checked";
		state = "A";
		var checkParti = '    <label for="'+id+'1" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
					      '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckPostulo(this);" attr-idalumno="'+idAlumno+'" checked id="'+id+'1" attr-bd="'+bd+'">'+
					      '        <span class="mdl-checkbox__label"></span>'+
					      '    </label>';
		$('#'+idTabla).bootstrapTable('updateCell',{
			rowIndex   : indexRow,
			fieldName  : 2,
			fieldValue : checkParti
		});
	}
	
	var newCheck = '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
	               '        <input type="checkbox" class="mdl-checkbox__input" '+check+' id="'+id+'" onclick="cambioCheckAprobo(this);" attr-idalumno="'+idAlumno+'" attr-cambio="'+cambio+'" attr-bd="'+bd+'" attr-state="'+state+'"> '+
	               '        <span class="mdl-checkbox__label"></span>'+
                   '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
    	rowIndex   : indexRow,
    	fieldName  : indexCampo,//Celda 0 en la tabla
    	fieldValue : newCheck
    });
	componentHandler.upgradeAllRegistered();
}

function cambioCheckPostulo(cb){
	var index = $(cb).closest('tr').attr('data-index');//console.log('index: '+index);
	onChangeCheckPostulo("tb_alumnos", index, 2, cb.checked, cb.id, cb.value, cb.getAttribute('attr-idalumno'), cb.getAttribute('attr-bd'));
}

function onChangeCheckPostulo(idTabla, indexRow, indexCampo, nuevoValor, id, value, idAlumno, bd){
	var check = "checked";
	var state = "P";
	if(nuevoValor == false){
		check = "";
		state = "N";
	}
	var bdVal = (bd == 'P') ? true : false;
	var cambio = false;
	var classC = "";
	if(nuevoValor == bdVal) {
		if(bd == 'A'){
			cambio = true;
			classC = "checkbox-gris";
		}else{
			cambio = false;
			classC = "";
		}
	} else {
		cambio = true;
		classC = "checkbox-gris";
	}
	
	var newCheck = '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				   '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckAprobo(this);"  id="'+id+'" attr-idalumno="'+idAlumno+'" attr-state="'+state+'" attr-cambio="'+cambio+'" attr-bd="'+bd+'"> '+
				   '        <span class="mdl-checkbox__label"></span>'+
				   '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
	rowIndex   : indexRow,
	fieldName  : 3,//Celda 0 en la tabla
	fieldValue : newCheck
	});
	
	var checkParti = '    <label for="'+id+'1" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				     '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckPostulo(this);" '+check+' attr-idalumno="'+idAlumno+'" id="'+id+'1" attr-bd="'+bd+'"> '+
				     '        <span class="mdl-checkbox__label"></span>'+
				     '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
	rowIndex   : indexRow,
	fieldName  : indexCampo,
	fieldValue : checkParti
	});
	componentHandler.upgradeAllRegistered();
}

function saveCheckeados(){
	var json = {};
	var alumnos = [];
	var cont = 0;
	json.alumno = alumnos;
	var arryDivs = getCheckedFromTablaByAttr('tb_alumnos', 3);
	$.each( arryDivs, function( key, value ) {
		var idAlumno = $(value).find(':checkbox').attr('attr-idalumno');
		var estado   = $(value).find(':checkbox').attr('attr-state');
		var bd       = $(value).find(':checkbox').attr('attr-bd');
		var alumno   = {"idAlumno" : idAlumno, "estado" : estado, "bd" : bd};
 		json.alumno.push(alumno);
 		cont++;
	});
	if(cont == 0) {
		//mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringAlumnos = JSON.stringify(json);
	var idSede  = $('#selectSede option:selected').val();
	var idGrado = $('#selectGrado option:selected').val();
	var idAula  = $('#selectAula option:selected').val();
	$.ajax({
		type  : 'POST',
		url   : 'c_certi_ingles_alumno/saveAlumnosCertificados',
		data  : { alumnos : jsonStringAlumnos,
				  idAula  : idAula ,
				  idSede  : idSede,
				  idGrado : idGrado},
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			result = JSON.parse(data);
			if(result.error == 0) {
				$('#contTabAlumnos').html(result.tablaAlumnos);
				$('#tb_alumnos').bootstrapTable({ });
				generarBotonMenu();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
				tableEventsCerti();
				//mostrarNotificacion('success', 'Datos Guardados Exitosamente', 'Correcto');
				mostrarNotificacion('success', 'Se ha modificado', 'Registro');
			} else {
				console.log('error al cargar la tabla');
			}
		}
	});
}

function generarBotonMenu(){
	var div = $('#contTabAlumnos .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="saveCheckeados()" id="btnGuardarTabla">'+
					'<i class="mdi mdi-save"></i>'+
			   '</button>'+
			   '<div class="btn-group btn-group-sm pull-right" id="lista3">'+
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
		url  : 'c_certi_ingles_alumno/logOut', 
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
		$('#tb_alumnos').on('all.bs.table', function (e, name, args) {
			
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