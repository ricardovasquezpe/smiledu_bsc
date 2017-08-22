function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectTipoReporte').selectpicker('mobile');
	} else {
		$('.selectTipoReporte').selectpicker();
	}
}

var idAula  = null;
var year    = null;
var idGrado = null;
var idSede  = null;
var idCiclo = null;
var valorReport = null;
function getComboByTipoReporte() {
	valorReport = $("#selectTipoReporte option:selected").val();
	if(valorReport != '') {
		$.ajax({
			type  : 'POST',
			'url' : 'c_reporte/tipoReporte',
			data  : { valorReport : valorReport }
		}).done(function(data) {
			data = JSON.parse(data);
			$('#contCmbYear').html(data.cmbYear);
			$('#contCmbSede').html(data.cmbSede);
			$('#contCmbGrado').html(data.cmbGrado);
			$('#contCmbBim').html(data.cmbBim);
			$('.iconsMenu').html(generarBotonMenu());
			componentHandler.upgradeAllRegistered();
			formatoCombo('selectButton');
			if(data.tipo == 'CURSO_GRADO') {
				$('#cmbGrado').attr('onchange', 'getTableCursos()');
				$('#contCmbAula').html(null);
				$('#contCmbBim').html(null);
			}
			if(data.tipo == 'ORDEN_MERITO') {
				$('.selectButton').attr('onchange', 'getTableOrdMerito()');
			}
			if(data.tipo == 'PROFESOR_POR_AULA') {
				$('.selectButton').attr('onchange', 'getTableProfesorAula()');
				$('#contCmbBim').html(null);
				$('#contCmbAula').html(null);
			}
		});
	} else {
		$('#contCmbYear').html(null);
		$('#contCmbSede').html(null);
		$('#contCmbGrado').html(null);
		$('#contCmbAula').html(null);
		$('#contCmbBim').html(null);
	}
}

function getTableProfesorAula(indic=null) {
	year    = $('#contCmbYear  option:selected').val();
    idGrado = $('#contCmbGrado option:selected').val();
    idAula  = $('#contCmbAula  option:selected').val();
    idSede  = $('#contCmbSede  option:selected').val();	
    $.ajax({
		type  : 'POST',
		'url' : 'c_reporte/tbProfesores',
		data  : { idGrado : idGrado,
				  year    : year,
				  idAula  : idAula, 
				  idSede  : idSede }
	}).done(function(data) {
		data = JSON.parse(data);
		if(indic == null) {
			$('#contCmbAula').html(data.cmbAula);
		}
		formatoCombo('selectButton2');
		$('#cmbAula').attr('onchange' , 'getTableProfesorAula(1)');
		$('#contTbCursos').html(data.table);
		$('#contTB').css('display', 'block');
		$('#tbProfAula').bootstrapTable({ });
	});
}

function getTableCursos() {
	year    = $('#contCmbYear  option:selected').val();
    idGrado = $('#contCmbGrado option:selected').val();

	if(year == '' || idGrado == '') {
		return;
	} else {
		$.ajax({
			type  : 'POST',
			'url' : 'c_reporte/tbCursos',
			data  : { idGrado : idGrado,
					  year    : year }
		}).done(function(data) {
			data = JSON.parse(data);
			$('#contTbCursos').html(data.table);
			$('#contTB').css('display', 'block');
			$('#tbCursos').bootstrapTable({ });
			tableEventsCursos();
		});
	}
}

function getTableOrdMerito(indic=null) {
	year    = $('#contCmbYear  option:selected').val();
    idGrado = $('#contCmbGrado option:selected').val();
    idAula  = $('#contCmbAula  option:selected').val();
    idSede  = $('#contCmbSede  option:selected').val();
    idCiclo = $('#contCmbBim   option:selected').val();	
    $.ajax({
		type  : 'POST',
		'url' : 'c_reporte/tbOrdMerito',
		data  : { idGrado : idGrado,
				  year    : year,
				  idAula  : idAula, 
				  idSede  : idSede,
				  idCiclo : idCiclo }
	}).done(function(data) {
		data = JSON.parse(data);
		if(indic == null) {
			$('#contCmbAula').html(data.cmbAula);
			$('#contCmbBim').html(data.cmbBim);
		}
		formatoCombo('selectButton2');
		$('.selectButton2').attr('onchange', 'getTableOrdMerito(1)');
		$('#contTbCursos').html(data.table);
		$('#contTB').css('display', 'block');
		$('#tbOrdMerito').bootstrapTable({ });
		//tableEventsOrdMerito();
	});  
}

function generarBotonMenu() {	
	var prueba= '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="aaa">'+
				    '<i class="mdi mdi-search"></i>'+
				'</button>';
				
	var aa= $('#aaa').append ='<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="getGraficos()">'+
							    	'<i class="mdi mdi-insert_chart"></i>'+
							  '</button>'+
							  '<button id="opc_docente" class="mdl-button mdl-js-button mdl-button--icon" >'+
							      '<i class="mdi mdi-more_vert"></i>'+
							  '</button>'+
							  '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_docente">'+
							      '<li class="mdl-menu__item" onclick="generarPdf()">Descargar PDF</li>'+
							      '<li class="mdl-menu__item" data-toggle="modal" data-target="#modalSubirPaquete" data-paquete-text="Enviar reporte por correo">Enviar Correo</li>'+
							  '</ul>';
	return aa;
}

function getGraficos() {
	
}

function generarPdf() {
	 $.ajax({
			type  : 'POST',
			'url' : 'c_reporte/getContPDF',
			data  : { idGrado     : idGrado,
					  year        : year,
					  idAula      : idAula, 
					  idSede      : idSede,
					  idCiclo     : idCiclo,
					  valorReport : valorReport }
		}).done(function(data) {
			data = JSON.parse(data);
			$('#contTabla').val(data.table);
			$('#contTipo').val("REPORTE "+data.tipoText);
			$('#contCount').val(data.count);
			$('#nomAlumno').val(data.nomAlumno);
			$('#grado').val(data.grado);
			$('#formReporte').submit();
		});
}

function formatoCombo(classs) {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.'+classs).selectpicker('mobile');
	} else {
		$('.'+classs).selectpicker();
	}
}

function tableEventsCursos() {
	$(function () {
	    $('#tbCursos').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsOrdMerito() {
	$(function () {
	    $('#tbOrdMerito').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}