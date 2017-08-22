var alumnoDesmatricular = null;
var cons_inicio  = null;


function init(){
	$("#tablaAlumnos").bootstrapTable({});
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initLimitInputs('observacion','descAula','observDesmatricula');
	initButtonLoad('botonDM','btnAceptarMatricular','buttonEstado');
	$(":input").inputmask();
	
	$.ajax({
		type    : 'POST',
		'url'   : 'c_detalle_aula/getCapaActual',
		data    : {},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			if(data.capa_actual > 0){
				$('#sedeAula').attr('disabled', true);
				$('#nivelAula').attr('disabled', true);
				$('#gradoAula').attr('disabled', true);
				$('#selectTipoCiclo').attr('disabled', true);
				$('#year').attr('disabled', true);
				$('.selectButton').selectpicker('refresh');
			} else if(data.capa_actual == 0){
				$('#sedeAula').attr('disabled', false);
				$('#nivelAula').attr('disabled', false);
				$('#gradoAula').attr('disabled', false);
				$('#selectTipoCiclo').attr('disabled', false);
				$('#year').attr('disabled', false);
				$('.selectButton').selectpicker('refresh');
			}
		}
	});
}

$( document ).ready(function() {
	$('#menu .mfb-component__button--main').css('display', 'none');
	$('#menu .mfb-component__list').css('display', 'none');
});

$('.mdl-layout__tab[href="#tab-1"]').click(function(){

	/*var id = $(this).attr('href');
	var timeInit = (new Date()).getTime();
	var timeEnd = (new Date()).getTime();
	
	tabLoader(id, timeInit)
	console.log(timeInit + 'tiempo 1');
	console.log(timeEnd + 'tiempo 2');*/
	$('#menu .mfb-component__button--main').css('display', 'none');
	$('#menu .mfb-component__list').css('display', 'none');
    $('.mfb-component__wrap').removeClass(' mdl-only-btn__animation');
});

$('.mdl-layout__tab[href="#tab-2"]').click(function(){

	/*var id = $(this).attr('href');
	var timeInit = (new Date()).getTime();*/
	
	
	$('.mfb-component__wrap').addClass('mdl-only-btn__animation');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').attr({	
		'onclick'		: 	'abrirModalMatricular()',
		'data-mfb-label':	'Matricular Estudiante'
	});

	/*var timeEnd = (new Date()).getTime();
	
	tabLoader(id, timeInit)
	console.log(timeInit + 'tiempo 1');
	console.log(timeEnd + 'tiempo 2');*/
	$('#menu .mfb-component__button--main:NTH-CHILD(1)').find('.mdi').removeAttr('style');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').find('.mdi').removeAttr('style');
	$('#menu .mfb-component__button--main:NTH-CHILD(1)').find('.mdi').removeClass('mdi-filter_list');
	$('#menu .mfb-component__button--main:NTH-CHILD(1)').find('.mdi').addClass('mdi-add');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').find('.mdi').removeClass('mdi-filter_list');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').find('.mdi').addClass('mdi-new_student');
	$('#menu .mfb-component__list').removeAttr('style');
	$('#menu .mfb-component__button--main').removeAttr('style');
});

$('.mdl-layout__tab[href="#tab-3"]').click(function(){

	/*var id = $(this).attr('href');
	var timeInit = (new Date()).getTime();*/
//	$('.mfb-component__wrap').addClass('mdl-only-btn__animation');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').attr({
		'onclick'		: 	'abrirModalFiltrosDocentes()',
		'data-mfb-label':	'Filtrar'
	});

	/*var timeEnd = (new Date()).getTime();
	
	tabLoader(id, timeInit)*/
	$('#menu .mfb-component__button--main:NTH-CHILD(1)').find('.mdi').css('transform', 'rotate(0)');
	$('#menu .mfb-component__button--main:NTH-CHILD(1)').find('.mdi').removeClass('mdi-add');
	$('#menu .mfb-component__button--main:NTH-CHILD(1)').find('.mdi').addClass('mdi-filter_list');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').find('.mdi').css('transform', 'rotate(0)');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').find('.mdi').removeClass('mdi-new_student');
	$('#menu .mfb-component__button--main:NTH-CHILD(2)').find('.mdi').addClass('mdi-filter_list');
	$('#menu .mfb-component__list').css('display', 'none');
	$('#menu .mfb-component__button--main').removeAttr('style');
});

$('#modalFiltroDocentes').find('button[data-dismiss="modal"]').click(function(){
	resetSelect('selectCurso');
});

function enableDisableSelectDesc_Aula(desc_aula1,desc_aula2){
	var valorDesc_aula1 = $("#"+desc_aula1).val();
    var valorDesc_aula2 = $("#"+desc_aula2).val();
    if(valorDesc_aula1 == valorDesc_aula2 ){
    	$('#descAula2').attr('disabled', false);
    	$('#descAula').attr('disabled', false);
		$('.selectButton').selectpicker('refresh');
    } else if(valorDesc_aula1.length > 0) {
    	$('#descAula2').attr('disabled', true);
		$('.selectButton').selectpicker('refresh');
    } else if(valorDesc_aula2.length > 0) {
    	$('#descAula').attr('disabled', true);
		$('.selectButton').selectpicker('refresh');
    }
}

function getNivelesBySede(sede, nivel, grado){
	var valorSede = $("#"+sede).val();
	if(valorSede != null && valorSede.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_aula/getNivelesBySede',
			data    : {idsede : valorSede},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			enableDisableSelectDesc_Aula('descAula','descAula2');
			setCombo(nivel, data.comboNiveles, "Nivel (*)");
			setCombo(grado, null, "Grado (*)");
		});
	}
}

function getGradosByNivel(sede, nivel, grado){
	var valorSede  = $("#"+sede).val();
    var valorNivel = $("#"+nivel).val();
        
    if(valorNivel != null && valorNivel.length != 0){
    	$.ajax({
    		type    : 'POST',
    		'url'   : 'c_detalle_aula/getGradosByNivel',
    		data    : {idnivel : valorNivel,
    			       idsede  : valorSede},
    		'async' : false
    	}).done(function(data){
    		data = JSON.parse(data);
    		setCombo(grado, data.comboGrados, "Grado (*)");
    	});
    }
}

function onSaveCampo(element, flg){
	Pace.restart();
	Pace.track(function() {
		var valor = $(element).val();
		var abc   = $(element).attr('attr-abc');
		var descaula1 = $('#descAula').val();
		var descaula2 = $('#descAula2').val();
		var ugel      = $('#nombreLetra').val();
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_aula/updateCampoCambio',
			data    : {valor     : valor,
				       abc       : abc,
				       descaula1 : descaula1,
				       descaula2 : descaula2,
				       ugel      : ugel},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				setearInput($(element).attr('id'), $(element).attr("val-previo"));
				enableDisableSelectDesc_Aula('descAula','descAula2');
				mostrarNotificacion('success', data.msj, null);
			}
			if (data.error == 0){ //correcto
				$(element).attr("val-previo", valor);
				enableDisableSelectDesc_Aula('descAula','descAula2');
				if(abc == 'desc_aula'){
					$(".mdl-section-head").html("Editar: "+valor);
				}
				if(flg == 1){
					mostrarNotificacion('success', data.msj, null);
				}
			}
			if(data.error == 3){
				setearInput($(element).attr('id'), $(element).attr("val-previo"));
				mostrarNotificacion('warning', data.msj, null);
			}
			if(data.error == 4){
				setearCombo($(element).attr('id'), $(element).attr("val-previo"));
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	});
}

function abrirModalFiltros(){
	abrirCerrarModal('modalFiltro');
	$('#nombreFiltro').focus();
}

function buscarAlumno(){
	Pace.restart();
	Pace.track(function() {
		var nombreFiltro = $("#nombreFiltro").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_aula/buscarAlumno',
			data    : {nombre  : nombreFiltro},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			result = data.tablaAlumnos;
			if ( result.length != 0) {
				$("#cont2_search_empty").css("display", "none");
				$("#cont2_search_not_found").css("display", "none");
				$("#cont_tabla_alumnos").html(result);
				$("#tablaAlumnos").bootstrapTable({});
				componentHandler.upgradeAllRegistered();
			} else {
				$("#cont_tabla_alumnos").html(result);
				$("#cont2_search_empty").css("display", "none");
				$("#cont2_search_not_found").css("display", "block");
			}
		});
	});
}

function abrirModalMatricular(){
	Pace.restart();
	Pace.track(function() {
		$('#btnBuscar').attr('disabled', true);
		$.ajax({
	        type    : 'POST',
	        'url'   : 'c_detalle_aula/abrirModalMatricular',
	        data    : {},
	        'async' : false
	    }).done(function(data){
	        data = JSON.parse(data);
	        if(data.error == 0){
	        	$('#btnBuscar').attr('disabled', true);
	    		$('#btnBuscar').removeClass('mdl-button--raised');
	        	abrirCerrarModal("modalMatricularAlumnos");
	        	setearInput('inputNomAlumno',null);
	        	$("#cont_tabla_alumnos_sinaula").html(null);
	        } else {
	        	mostrarNotificacion('success', data.msj, null);
	        }
	    });
	});
}

function changeButtonBuscar(){
	var nameAlumno = $("#inputNomAlumno").val();
	if($.trim(nameAlumno).length >= 3){
		$('#btnBuscar').attr('disabled', false);
		$('#btnBuscar').addClass('mdl-button--raised');
	} else {
		$("#cont_tabla_alumnos_sinaula").html(null);
		$('#btnBuscar').attr('disabled', true);
		$('#btnBuscar').removeClass('mdl-button--raised');
	}
}

function getAlumnosByName(){
	var nameAlumno = $("#inputNomAlumno").val();
	$.ajax({
		data  : {nombre : nameAlumno},
		url   : 'c_detalle_aula/getAlumnosbyName',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_alumnos_sinaula").html(data.tablaAlumno);
		$("#tbAlumnobyNombre").bootstrapTable({});
    	componentHandler.upgradeAllRegistered();
    	tableEvents("tbAlumnobyNombre");
	});
}

function asignarEstudiantes(){
	addLoadingButton('buttonEstado');
	Pace.restart();
	Pace.track(function() {
		var arrayIdEstudiantes = [];
		$('.cb-estudiante').each(function() {
			if($(this).parent().hasClass('is-checked')){
				var idEstudiante = $(this).attr('attr-id-estudiante');
				arrayIdEstudiantes.push(idEstudiante);
			}
		});
		if(arrayIdEstudiantes.length != 0){
			$.ajax({
				data 	: {estudiantes : arrayIdEstudiantes},
				url		: 'c_detalle_aula/asignAlumnos',
				type 	: 'POST',
				async	: true 
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					$("#cont_tabla_alumnos").html(data.tablaAlumnos);
					$("#tablaAlumnos").bootstrapTable({});
					$("#cont2_search_empty").css("display", "none");
					getAlumnosByName();
					if(data.capa_actual > 0){
						$('#sedeAula').attr('disabled', true);
						$('#nivelAula').attr('disabled', true);
						$('#gradoAula').attr('disabled', true);
						$('#selectTipoCiclo').attr('disabled', true);
						$('#year').attr('disabled', true);
						$('.selectButton').selectpicker('refresh');
					}
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
					componentHandler.upgradeAllRegistered();
					abrirCerrarModal("modalConfirmAsignarEstudiantes");
					stopLoadingButton('buttonEstado');
					mostrarNotificacion('success', data.msj, null);
				} else {
					stopLoadingButton('buttonEstado');
					mostrarNotificacion('warning', data.msj, null);
				}
			});
		}
	});
}

function tableEvents(idTabla){
	$(function () {
	    $('#'+idTabla).on('all.bs.table', function (e, name, args) {

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
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function abrirModalConfirmarDesmatricular(idalumno){
	addLoadingButton('botonDM');
	$.ajax({
        type    : 'POST',
        'url'   : 'c_detalle_aula/abrirModalConfirmarDesmatricular',
        data    : {idalumno : idalumno},
        'async' : true
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0){
        	setearInput('observDesmatricula',null);
        	abrirCerrarModal("modalConfirmarDesmatricular");
			document.getElementById('msjConfirma').innerHTML = "&#191;Seguro que desea desmatricular al estudiante(a) "+data.alumno+"?";
        	alumnoDesmatricular = idalumno;
        }
        stopLoadingButton('botonDM');
    });
}

function desmatricular(){
	addLoadingButton('botonDM');
	Pace.restart();
	Pace.track(function() {
		var valorObser = $("#observDesmatricula").val();
		$.ajax({
			data 	: {idalumno    : alumnoDesmatricular,
				       observacion : valorObser},
			url		: 'c_detalle_aula/deleteAlumno',
			type 	: 'POST',
			async	: true 
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_tabla_alumnos").html(data.tablaAlumnos);
				$("#tablaAlumnos").bootstrapTable({});
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();
				abrirCerrarModal("modalConfirmarDesmatricular");
				stopLoadingButton('botonDM');
				mostrarNotificacion('success', 'Se desmatricul&oacute; con &eacute;xito', null);
				if(data.capa_actual == 0) {
					$('#sedeAula').attr('disabled', false);
					$('#nivelAula').attr('disabled', false);
					$('#gradoAula').attr('disabled', false);
					$('#selectTipoCiclo').attr('disabled', false);
					$('#year').attr('disabled', false);
					$('.selectButton').selectpicker('refresh');
				}
			}else{
				stopLoadingButton('botonDM');
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	});
}

function abrirModalConfirmAsignarEstudiantes(){
	
	var arrayIdEstudiantes = [];
	$('.cb-estudiante').each(function() {
		if($(this).parent().hasClass('is-checked')){
			var idEstudiante = $(this).attr('attr-id-estudiante');
			arrayIdEstudiantes.push(idEstudiante);
		}
	});

	if(arrayIdEstudiantes.length != 0){
		addLoadingButton('btnAceptarMatricular');
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_aula/abrirModalConfirmAsignarEstudiantes',
			data    : {arrayIdEstudiantes : arrayIdEstudiantes},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);			
			if(arrayIdEstudiantes.length == 1){
				stopLoadingButton('btnAceptarMatricular');
				document.getElementById('titleConfirmaMatricular').innerHTML = "&#191;Deseas matricular a "+data.nombreCompleto+"?";
				document.getElementById('msjConfirmaMatricular').innerHTML = "Al matricular al estudiante, formar&aacute; parte del aula "+data.aula+".";
				stopLoadingButton('btnAceptarMatricular');
				abrirCerrarModal("modalConfirmAsignarEstudiantes");
			} else if(arrayIdEstudiantes.length > 1){
				document.getElementById('titleConfirmaMatricular').innerHTML = "&#191;Deseas matricular a todos los estudiantes seleccionados?";
				document.getElementById('msjConfirmaMatricular').innerHTML = "Al matricular a todos los estudiantes, formar&aacute;n parte del aula "+data.aula+".";
				stopLoadingButton('btnAceptarMatricular');
				abrirCerrarModal("modalConfirmAsignarEstudiantes");
			}
		});
		stopLoadingButton('btnAceptarMatricular');
	}
}

function abrirModalFiltrosDocentes(){
	abrirCerrarModal("modalFiltroDocentes");
}

function getAlumnosMatriculablesByName(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {nombre : null},
			url   : 'c_detalle_aula/getAlumnosbyName',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_tabla_alumnos_sinaula").html(data.tablaAlumno);
				$("#tbAlumnobyNombre").bootstrapTable({});
		    	componentHandler.upgradeAllRegistered();
		    	tableEvents("tbAlumnobyNombre");
			} else {
				$("#cont_tabla_alumnos_sinaula").html(null);
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	});
}

function abrirModalCompromisos(idestudiante) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/getDeudasByEstudiante",
			data: {idpostulante  : idestudiante},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $("#calendarCompromisos").html(data.table);
			    $('#tb_compromisoCalendarAlu').bootstrapTable({});
				abrirCerrarModal('modalCompromisosEstudiante');
			} else if(data.error == 1) {
			    $("#cont_compromiso").html(null);
			}
		});
	});	
}

function getDocentesByCursosAula(curso){
	var valorCurso = $("#"+curso).val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_detalle_aula/getDocentesByCursosAula",
			data: {valorCurso  : valorCurso},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#cont3_search_empty").css("display", "none");
				$("#cont3_search_not_found").css("display", "none");
			    $("#cont_tabla_profesores").html(data.tableProfesores);
				abrirCerrarModal("modalFiltroDocentes");
			} else if(data.error == 1) {
			    $("#cont_tabla_profesores").html(null);
				$("#cont3_search_empty").css("display", "none");
				$("#cont3_search_not_found").css("display", "block");
			}
		});
	});
}

function getEstudiantesAula(){
	if(cons_inicio == null){
		buscarAlumno();
		cons_inicio = 1;
	}
}

/*
function getAulasNoActiBySede(sede, aula){
    var valorSede  = $("#"+sede).val();
    $.ajax({
        type    : 'POST',
        'url'   : 'c_detalle_aula/getAulasNoActiBySede',
        data    : {idsede : valorSede},
        'async' : false
    }).done(function(data){
        data = JSON.parse(data);
        setCombo(aula, data.comboGetAulasNoActiBysede, "Aula");
    });
}


function all(){
	$('#tb_preguntas').bootstrapTable('updateCell',{
		rowIndex   : (rowIndexPreg-1),
		fieldName  : 1,
		fieldValue : newInfo
	});
}
*/