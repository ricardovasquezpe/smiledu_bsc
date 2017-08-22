var $_yearGlobal       = null;
var $_yearFiltroGlobal = null;
var $_yearSubirGlobal  = null;
var flg_estado = 0;
function initEceAlumnos() {
	if(flg_estado == 0) {
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		    $('.pickerButn').selectpicker('mobile');
		} else {
			$('.pickerButn').selectpicker();
		}
		$('#tb_alumnos').bootstrapTable({ });
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		generarBotonMenu();
	} else {
		$('#contTbAlumnos').html();
		$('#descarExcel').remove();
		$('#lista3').remove();
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		    $('.pickerButn').selectpicker('mobile');
		} else {
			$('.pickerButn').selectpicker();
		}
		$('#tb_alumnos').bootstrapTable({ });
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		generarBotonMenu();
	}
	initButtonLoad('botonMF','btnMG');
	flg_estado = 1;
}

function setYear() {
	var year =  $('#cmbYear option:selected').val();
	$_yearGlobal = year;
}

function setYearFiltro() {
	var year =  $('#cmbYearFiltro option:selected').val();
	$_yearFiltroGlobal = year;
}

function setYearSubir() {
	var year =  $('#cmbYearSubir option:selected').val();
	$_yearSubirGlobal = year;
}

//FILTROS PARA IMPORTAR UN EXCEL//
//INICIO//
function getNivelBySede_Excel() {
	var idSede =  $('#selectSedeExcel option:selected').val();
	if(!$_yearSubirGlobal || !idSede) {
		return;
	}
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_ece_alumnos/comboSedesNivelEce_CtrlEce',
    	data   : {idSede : idSede , 
    		      year   : $_yearSubirGlobal }
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {			
				setCombo('selectNivelExcel', data.optNivel, 'Nivel');
				setCombo('selectGradoExcel', null, 'Grado');
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
			} else {
				mostrarNotificacion('error', data.msj, 'Error');
			}
		}
	});	
}

function getGradosByNivel_Excel() {
	var idSede =  $('#selectSedeExcel option:selected').val();
	var idNivel =  $('#selectNivelExcel option:selected').val();
	if(!$_yearSubirGlobal || !idSede || !idNivel) {
		return;
	}
	$.ajax({
		url: 'c_ece_alumnos/getComboGradoByNivel_CtrlEce',
        data: { idNivel : idNivel,
        	    idSede  : idSede ,
        	    year    : $_yearSubirGlobal },
        type: 'POST'
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectGradoExcel', data.optGrado, 'Grado');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}
//FIN//

//FILTRO DE CONSULTA//
//INICIO//
function getNivelBySede(){
	var idSede =  $('#selectSede option:selected').val();
	if(!$_yearGlobal || !idSede) {
		return;
	}
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_ece_alumnos/comboSedesNivelEce_CtrlEce',
    	data   : { idSede : idSede , 
    		       year   : $_yearGlobal }
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {			
				setCombo('selectNivel', data.optNivel, 'Nivel');
				setCombo('selectGrado', null, 'Grado');
			} else {
				msj('error', data.msj, 'Error');
			}
		}
	});	
}

function getGradosByNivel() {
	var idSede =  $('#selectSede option:selected').val();
	var idNivel =  $('#selectNivel option:selected').val();
	if(!$_yearGlobal || !idSede || !idNivel) {
		return;
	}
	$.ajax({
		url: 'c_ece_alumnos/getComboGradoByNivel_CtrlEce',
        data: { idNivel   : idNivel,
        	    idSede    : idSede ,
        	    year      : $_yearGlobal},
        type: 'POST'
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectGrado', data.optGrado, 'Grado');
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getMostrarGrado() {//gg
	addLoadingButton('btnMG');
	$('#descarExcel').remove();
	$('#lista3').remove();
	var idSede  = $('#selectSede option:selected').val();
	var idGrado = $('#selectGrado option:selected').val();
	if(!$_yearGlobal || !idSede || !idGrado) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: 'c_ece_alumnos/getComboGrado_CtrlEce',
	        data: { idSede   : idSede,
	        	    idGrado  : idGrado,
	        	    year     : $_yearGlobal},
	        	    type     : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				$('#contTbAlumnos').html(data.tablaAlumnos);
				$('#tb_alumnos').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');	
				$('main section .mdl-content-cards .img-search').css('display', 'none');
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#cmbYear     option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectSede  option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectNivel option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#selectGrado option:selected').text());
				$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
				$('main section .mdl-content-cards .mdl-card').removeAttr('style');		
				generarBotonMenu();
				initSearchTableNew();
			    abrirCerrarModal("modalFiltro");
			    stopLoadingButton('btnMG');
			}else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
				stopLoadingButton('btnMG');
			}
		});
	});
}
//FIN//

//FILTRO DE MODAL POPUP//
//INICIO//
function abrirModalMostrarAulas(){
	$('#selectSede_').val("");
	$('#selectSede_').selectpicker('refresh');	
	$('#selectGrado_').val("");
	$('#selectGrado_').selectpicker('refresh');	
	$('#selectNivel_').val("");
	$('#selectNivel_').selectpicker('refresh');
	$('#contTabAulas').html('');
	$('#tb_aulas').bootstrapTable({ });
	abrirCerrarModal('modalMostrarAulas');
}

function getNivelBySede_(){
	var idSede =  $('#selectSede_ option:selected').val();
	if(!idSede || !$_yearFiltroGlobal) {
		return;
	}
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_ece_alumnos/comboSedesNivelEce_CtrlEce',
    	data   : {idSede : idSede ,
    		      year   : $_yearFiltroGlobal }
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {			
				setCombo('selectNivel_', data.optNivel, 'Nivel');
				setCombo('selectGrado_', null, 'Grado');
				$('#contTabAulas').html(null);
				$('#tb_aulas').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
			} else {
				msj('error', data.msj, 'Error');
			}
		}
	});	
}

function getGradosByNivel_() {
	var idSede =  $('#selectSede_ option:selected').val();
	var idNivel =  $('#selectNivel_ option:selected').val();
	if(!idSede || !idNivel || !$_yearFiltroGlobal) {
		return;
	}
	$.ajax({
		url: 'c_ece_alumnos/getComboGradoByNivel_CtrlEce',
        data: { idNivel : idNivel,
        	    idSede  : idSede ,
        	    year    : $_yearFiltroGlobal},
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectGrado_', data.optGrado, 'Grado');
				$('#contTabAulas').html(null);
				$('#tb_aulas').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
			}else if(data.error == 1){
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getMostrarGrado_() {
	var idSede  = $('#selectSede_ option:selected').val();
	var idGrado = $('#selectGrado_ option:selected').val();
	if(!idSede || !idGrado || !$_yearFiltroGlobal) {
		return;
	}
	$.ajax({
		url: 'c_ece_alumnos/getMostrarAulas_CtrlEce',
        data: { idSede  : idSede,
    	    	idGrado : idGrado,
    	    	year    : $_yearFiltroGlobal},
        type: 'POST'
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				$('#contTabAulas').html(data.tablaAulas);
				$('#tb_aulas').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
			} else if(data.error == 1) {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function CapturarAulas(){
	addLoadingButton('botonMF');
	var idSede  = $('#selectSede_ option:selected').val();
	var idGrado = $('#selectGrado_ option:selected').val();
	var json = {};
	var letras = [];
	json.letra = letras;
	$('*[data-descrip="1"]').each(function() {
		var idAula   = $(this).attr('data-pkIdAul');
		var descrip   = $(this).val();
		if(/^[A-Za-z]$/.test(descrip)){
			  var letra   = {"nid_aula" : idAula , "nombre_letra" : descrip};
		      json.letra.push(letra);	
		}else if (descrip == null || descrip == ""){
			stopLoadingButton('botonMF');
			// mostrarNotificacion('warning', 'No se inserto valores', 'ERROR');
		}
		else{
			stopLoadingButton('botonMF');
			 mostrarNotificacion('warning', 'Solo letras', 'ERROR');
			 return;
		}	  
	});
	var jsonStringLetra = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_ece_alumnos/grabarAulas',
		data : { letras : jsonStringLetra}, 
		async : true
	})
	.done(function(data) {
		data = JSON.parse(data);			
		if(data.error == 0 || data.error == 2) {			
			mostrarNotificacion('success', 'Se ha Registro ', 'Se Registro');
			$.ajax({
				url: 'c_ece_alumnos/getComboGrado_CtrlEce',
		        data: { idSede   : idSede,
	        	        idGrado  : idGrado},
		        async : true,
		        type: 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);	
				$('#descarExcel').remove();
				$('#lista3').remove();
				$('#contTbAlumnos').html(data.tablaAlumnos);
				$('#tb_alumnos').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				generarBotonMenu();						
				//initEceAlumnos();
				mostrarNotificacion('success', "Datos Cargados", data.msj);
				
			});		
			abrirCerrarModal('modalMostrarAulas');
			stopLoadingButton('botonMF');
		} else if (data.error == 1){
			stopLoadingButton('botonMF');
			//mostrarNotificacion('error', data.msj, 'ERROR');
		}
	});
}
//FIN//

function getAlumnosByAula() {
	$('#descarExcel').remove();
	$('#lista3').remove();
	var idAula = $('#selectAula option:selected').val();
	$.ajax({
		url: "c_ece_alumnos/getAlumnosFromAulaEce",
        data: { idAula   : idAula},
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 ||data.error == 2)  {
				$('#contTbAlumnos').html(data.tablaAlumnos);
				$('#tb_alumnos').bootstrapTable({});
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				generarBotonMenu();
			} else if(data.error == 1) {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function generarBotonMenu(){
	var div = $('#contTbAlumnos .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" id="descarExcel" onclick="openModalExcel()" id="btnGuardarTabla">'+
	               '<i class="mdi mdi-file_upload"></i>'+
			   '</button>'+
			   '<button id="lista3" class="mdl-button mdl-js-button mdl-button--icon">'+
			   	   '<i class="mdi mdi-more_vert"></i>'+
			   '</button>'+
			   '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="lista3">'+
			   	   '<li class="mdl-menu__item"><i class="mdi mdi-print"></i> Imprimir</li>'+
				   '<li class="mdl-menu__item"><i class="mdi mdi-file_download"></i> Descargar</li>'+
			   '</ul>');
}

function openModalExcel(){
	$('#rutaExcel').val(null);
	$('#selectSedeExcel').val("");
	$('#selectSedeExcel').selectpicker('refresh');	
	$('#selectGradoExcel').val("");
	$('#selectGradoExcel').selectpicker('refresh');	
	$('#selectNivelExcel').val("");
	$('#selectNivelExcel').selectpicker('refresh');	
	$('#itExcel').val("");
	modal('modalExcel');
}

function logOut(){
	$.ajax({
		url  : 'c_ece_alumnos/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

//JAVASCRIPT
function subirExcel(){
	//$('#descarExcel').remove();
	//$('#lista3').remove();
    var idSede =  $('#selectSedeExcel option:selected').val();
    var idGrado =  $('#selectGradoExcel option:selected').val();
    var idNivel =  $('#selectNivelExcel option:selected').val();
    if(idSede == null && idGrado == null && idNivel == null || idGrado == "" ||  idNivel == "" || idSede == ""){
    	msj('warning', 'Seleccione todos los campos', 'Ojo:');
    	return;
    }
    var inputFileExcel = document.getElementById("itExcel");
    var file = inputFileExcel.files[0];
    if(file == null || file == "") {
    	msj('warning', 'Seleccione un archivo excel', 'Ojo:');
    	return;
    }
    var formData = new FormData();   
    formData.append('itFileXLS', file);
    formData.append('idSede', idSede);
    formData.append('idGrado', idGrado);
    formData.append('year', $_yearSubirGlobal);
    $.ajax({
        data: formData,
        url: "c_ece_alumnos/subir_excel_CTRL",
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
  	}).done(function(data) {
  		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error_excel == 1) {	
				$("#expexcel")[0].submit();
				msj('error', CONFIG.get('CABE_ERR'),CONFIG.get('MSJ_ERR')+' - '+data.msj);
			} else if(data.error == 1) {
				msj('error', "El archivo no contiene el formato correcto", data.msj);
			} else {
				$('#contTbAlumnos').html(data.tablaAlumnos);
				$('#tb_alumnos').bootstrapTable({ });
				
				modal('modalExcel');
				generarBotonMenu();						
				initEceAlumnos();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				msj('success', data.msj);
			}
		}	
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
  		msj('error',CONFIG.get('CABE_ERR'),CONFIG.get('MSJ_ERR'));
  	})
  	.always(function() {
  		/*$('#divSubir').html($('#divSubir').html());//resetea el form
  		initExcel();*/
  	});
    
 }