function initAlumno_Eai() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_alumno_eai').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	initButtonLoad( 'btnMF' );
}

function getNivelesBySede() {
	var idSede =  $('#selectSede option:selected').val();
	$.ajax({
		url : "c_alumno_eai/comboSedesNivel",
        data: { idSede : idSede},
        async: false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {	
			   setCombo('selectNivel', data.optNivel, 'Nivel');
			   setCombo('selectGrado', null, 'Grado');
			   setCombo('selectAula', null, 'Aula');
			   $('#contTablaAlumnosAula').html(data.tablaAlumnosEai);
			   //$('#tb_alumno_eai').bootstrapTable({ });
			   //initSearchTableNew();
			}else if(data.error == 1) {
			   mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getGradosByNivel() {
	var idSede =  $('#selectSede option:selected').val();
	var idNivel =  $('#selectNivel option:selected').val();
	$.ajax({
		url : "c_alumno_eai/getComboGradoByNivel_Ctrl",
        data: { idNivel : idNivel,
        	    idSede  : idSede},
        async: false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
			   setCombo('selectGrado', data.optGrado, 'Grado');
			   setCombo('selectAula', null, 'Aula');
			   $('#contTablaAlumnosAula').html(data.tablaAlumnosEai);
			   //$('#tb_alumno_eai').bootstrapTable({ });
			   //initSearchTableNew();
			}else if(data.error == 1){
			   mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getAulasByNivelSede() {
	var idSede =  $('#selectSede option:selected').val();
	var idGrado =  $('#selectGrado option:selected').val();
	$.ajax({
		url: "c_alumno_eai/comboAulasByGradoUtils",
        data: { idGrado : idGrado,
        	    idSede  : idSede },
        async: false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
			   setCombo('selectAula', data.optAula, 'Aula');
			   $('#contTablaAlumnosAula').html(data.tablaAlumnosEai);
			   //$('#tb_alumno_eai').bootstrapTable({ });
			   //initSearchTableNew();
			}else if(data.error == 1) {
			   mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getAlumnosByAula() {
	addLoadingButton('botonMF');
	var idAula = $('#selectAula option:selected').val();
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnMF');
		$.ajax({
			url : "c_alumno_eai/getAlumnosFromAula",
	        data: { idAula  : idAula},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			stopLoadingButton('btnMF');
			if(data == ""){
				location.reload();
				stopLoadingButton('botonMF');
			} else{
				data = JSON.parse(data);
				if(data.error == 0 ||data.error == 2) {
				   $('#contTablaAlumnosAula').html(data.tablaAlumnosEai);
				   $('#tb_alumno_eai').bootstrapTable({ });
				   initSearchTableNew();
				   $('.fixed-table-toolbar').addClass('mdl-card__menu');
					$('main section .mdl-content-cards .img-search').css('display', 'none');
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectSede  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectNivel option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectGrado option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#selectAula  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
					$('main section .mdl-content-cards .mdl-card').removeAttr('style');
				   generarBotonMenu();
				   initXEditable();
				   stopLoadingButton('botonMF');
				   abrirCerrarModal("modalFiltro");
				}else if(data.error == 1) {
				   mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
				   stopLoadingButton('botonMF');
				}
			}
		});
	});
}

function generarBotonMenu(){
	var div = $('#contTablaAlumnosAula .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<div class="btn-group btn-group-sm pull-right">'+ 
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
		url  : 'c_alumno_eai/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

///////////////////////////////// Xeditable ///////////////////////////////
function initAllValid(){
	validXEditableEaiMatematica();
	validXEditableEaiComunicacion();
	validXEditableEaiCiencia();
	validXEditableEaiInformatica();
}

function initXEditable(){
	$.fn.editable.defaults.mode = 'inline';
	initAllValid();
	$(function () {
	    $('#tb_alumno_eai').on('all.bs.table', function (e, name, args) {

	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	initAllValid();
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
	    	initAllValid();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	initAllValid();
	    })
	    .on('search.bs.table', function (e, text) {
	    	initAllValid();
	    });
	});
}

function validXEditableEaiMatematica(){
	$('td').find(".classMatematica").editable({
        type: 'text',
        name: 'medida_rash_eai_mate',
        url : 'c_alumno_eai/editarPuntajeAlumnosEai',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene el campo de matem\u00e1 tica EAI';
            }
            if(value.length > 6){
            	return 'El EAI no debe exceder 6 caracteres';
            }
            if( value <= 0) {
            	return 'El puntaje debe ser un n\u00famero entero mayor a 0';
            }
            if(/^[a-z]+$/i.test(value)){
            	return 'El puntaje debe ser un n\u00famero entero';
            }
        },
        success: function(response, newValue) {
        	if(response == ""){
    			location.reload();
    		} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            newValue = parseInt(newValue);
	            successValid('tb_alumno_eai', index, 2, data.pk, newValue, null,'classMatematica');
	            initXEditable();
	            mostrarNotificacion('success',data.msj,'Registro');
    		}
        },
        error: function(data) {
            data = JSON.parse(data.responseText);
            initXEditable();
        	mostrarNotificacion('warning',data.msj,'Ojo');
        }
    });
}

function validXEditableEaiComunicacion(){
	$('td').find(".classComunicacion").editable({
        type: 'text',
        name: 'medida_rash_eai_comu',
        url : 'c_alumno_eai/editarPuntajeAlumnosEai',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene el campo de comunicaci\u00fan EAI';
            }
            if(value.length > 6){
            	return 'El EAI no debe exceder 6 caracteres';
            }
            if( value <= 0) {
            	return 'El puntaje debe ser un n\u00famero entero mayor a 0';
            }
            if(/^[a-z]+$/i.test(value)){
            	return 'El puntaje debe ser un n\u00famero entero';
            }
        },
        success: function(response, newValue) {
        	if(response == ""){
    			location.reload();
    		} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            newValue = parseInt(newValue);
	            successValid('tb_alumno_eai', index, 3, data.pk, newValue, null,'classComunicacion');
	            initXEditable();
	            mostrarNotificacion('success',data.msj,'Registro');
    		}
        },
        error: function(data) {
        	if(data == ""){
    			location.reload();
    		} else{
	            data = JSON.parse(data.responseText);
	            initXEditable();
	        	mostrarNotificacion('warning',data.msj,'Ojo');
    		}
        }
    });
}

function validXEditableEaiCiencia(){
	$('td').find(".classCiencia").editable({
        type: 'text',
        name: 'medida_rash_eai_ciencia',
        url : 'c_alumno_eai/editarPuntajeAlumnosEai',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene el campo de ciencia EAI';
            }
            if(value.length > 6){
            	return 'El EAI no debe exceder 6 caracteres';
            }
            if( value <= 0) {
            	return 'El puntaje debe ser un n\u00famero entero mayor a 0';
            }
            if(/^[a-z]+$/i.test(value)){
            	return 'El puntaje debe ser un n\u00famero entero';
            }
        },
        success: function(response, newValue) {
        	if(response == ""){
    			location.reload();
    		} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            newValue = parseInt(newValue);
	            successValid('tb_alumno_eai', index, 4, data.pk, newValue, null,'classCiencia');
	            initXEditable();
	            mostrarNotificacion('success',data.msj,'Registro');
    		}
        },
        error: function(data) {
        	if(data == ""){
    			location.reload();
    		} else{
	            data = JSON.parse(data.responseText);
	            initXEditable();
	        	mostrarNotificacion('warning',data.msj,'Ojo');
    		}
        }
    });
}

function validXEditableEaiInformatica(){
	$('td').find(".classInformatica").editable({
        type: 'text',
        name: 'medida_rash_eai_infor',
        url : 'c_alumno_eai/editarPuntajeAlumnosEai',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene el campo de inform\u00fatica EAI';
            }
        	if(value.length > 6){
            	return 'El EAI no debe exceder 6 caracteres';
            }
    	    if( value <= 0) {
          	return 'El puntaje debe ser un n\u00famero entero mayor a 0';
            }
            if(/^[a-z]+$/i.test(value)){
          	return 'El puntaje debe ser un n\u00famero entero';
            }
        },
        success: function(response, newValue) {
        	if(response == ""){
    			location.reload();
    		} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            newValue = parseInt(newValue);
	            successValid('tb_alumno_eai', index, 5, data.pk, newValue, null,'classInformatica');
	            initXEditable();
	            mostrarNotificacion('success',data.msj,'Registro');
    		}
        },
        error: function(data) {
        	if(data == ""){
    			location.reload();
    		} else{
	            data = JSON.parse(data.responseText);
	            initXEditable();
	        	mostrarNotificacion('warning',data.msj,'Ojo');
    		}
        }
    });
}

$('.editable-buttons').css('margin-top','-15px');