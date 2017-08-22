window.onload = traerLimitesDeCombo;
var limiteIzq = null;
var limiteDer = null; 
function init() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	initButtonLoad('btnFD', 'buttonDocente', 'btnATC', 'btnCTD', 'btnCDD');
}

var idGradoGlobal = null;
var idCursoGlobal = null;
var yearGlobal    = null;
var idSedeGlobal  = null;

function getTbGrupos() {
	idGradoGlobal = $('#cmbGradoNivel option:selected').val();
	idCursoGlobal = $('#cmbCursos option:selected').val();
	yearGlobal    = $('#cmbYears option:selected').val();
	idSedeGlobal  = $('#cmbSede option:selected').val();
	var textGrado = $('#cmbGradoNivel option:selected').text()+" ("+$('#cmbYears option:selected').text()+")";
	$('#h2_Grupo').text("Grupos - "+textGrado);
	
	if(yearGlobal == '' || yearGlobal == null) {
		mostrarNotificacion('error', 'Seleccione el A&ntilde;o');
		return;
	}
	if(idCursoGlobal == '' || idCursoGlobal == null) {
		mostrarNotificacion('error', 'Seleccione un Curso');
		return;
	}
	if(idGradoGlobal == '' || idGradoGlobal == null) {
		mostrarNotificacion('error', 'Seleccione un Grado');
		return;
	}	
	if(idSedeGlobal == '' || idSedeGlobal == null) {
		mostrarNotificacion('error', 'Seleccione una Sede');
		return;
	}
	
	
	
	
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnCDD');
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_asig_alum_curso/getTbGrupos',
	 		data  : { idGrado : idGradoGlobal,
	 				  idCurso : idCursoGlobal,
	 				  year    : yearGlobal,
	 				  idSede  : idSedeGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				modal('modalFiltros');
				$('#contTbGrupos').html(data.tbGruposHtml);
				$('#tGrupos').css('display', 'block');
				$('#tbGrupos').bootstrapTable({});
				$('#cont_search_empty').css('display','none');
				$('#cont_not_filter').css('display','none');
				$('#cont_search_grupo').css('display','none');
        		if($('#contTbGrupos').find('table').length>0){
        			$('#cont_search_grupo').css('display','none');
        		}else $('#cont_search_grupo').css('display','block');
        		
        		$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#cmbSede option:selected').text());
        		$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#cmbGradoNivel option:selected').text());
        		$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#cmbCursos option:selected').text());
        		$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#cmbYears option:selected').text());
        		$('#cabecera').css('display','block');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
				$('#cont_search_empty').css('display','none');
				$('#cont_not_filter').css('display','block');
				$('#cont_search_grupo').css('display','block');
				$('#cabecera').css('display','none');
			}
			stopLoadingButton('btnCDD');
			$('#tAulas').css('display','block');
		});
	});
}

var idMainGlobal = null;
function getAulas(btn) {
	idMainGlobal = btn.data('id_main');
	if(yearGlobal == '' || yearGlobal == null || idSedeGlobal == null || idSedeGlobal == '') {
		return;
	}
	if(idGradoGlobal == '' || idGradoGlobal == null) {
		return;
	}
	if(idMainGlobal == '' || idMainGlobal == null) {
		return;
	}
 	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_asig_alum_curso/getTbAulas',
	 		data  : { idGrado : idGradoGlobal,
	 				  year    : yearGlobal, 
	 				  idSede  : idSedeGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				pintarFila(btn, 'tbGrupos');
				$('#contTbAulas').html(data.tbAulasHtml);
				$('#tAulas').css('display', 'block');
				$('#tbAulas').bootstrapTable({});
				$('#cont_show_aula').css('display','none');
				$('#cont_search_aula').css('display','none');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
				$('#cont_show_aula').css('display','none');
				$('#cont_search_aula').css('display','block');
			}
			
		});
	});	
}

function pintarFila(liObject, id) {
    $.each($("#"+id+" tbody>tr"), function() {
	    $(this).css('background-color','white');
    });
    $("#"+id+" tr").filter(function() {
        return $(this).data('index') == liObject.closest('tr').data('index');
    }).css('background-color','#EEEEEE');
}

var idAulaGlobal = null;
function getModalAlumnos(btn) {
	var idAula = btn.data('id_aula');
	if(idAula == '' || idAula == null || yearGlobal == '' || yearGlobal == null || idMainGlobal == '' || idMainGlobal == null) {
		return;
	}
	if(idCursoGlobal == '' || idCursoGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_asig_alum_curso/getTbAlumnosAula',
	 		data  : { idAula  : idAula,
	 				  year    : yearGlobal,
	 				  idCurso : idCursoGlobal,
	 				  idMain  : idMainGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				idAulaGlobal = idAula;
				$('#contTbAlum').html(data.tbAlumnosHtml);
				$('#tbAlumnos').bootstrapTable({});
				componentHandler.upgradeAllRegistered();
				tableEventsAlumnos();
				modal('modalAsignarAlumnos');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			
		});
	});	
}

var arrayAlumno = [];
function handleCheckAlumno(chkAlumno) {
	var checked = chkAlumno.is(":checked");
	var cnt = 0;
	$.each(arrayAlumno, function( index, value ) {
		if(value.__id_alumno == chkAlumno.data('id_alumno')) {//ELIMINA
			arrayAlumno.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {//AGREGAR
		arrayAlumno.splice(arrayAlumno.length, 0, { __id_alumno : chkAlumno.data('id_alumno') } );
	}
	
	var idCheck  = chkAlumno.attr('id');
	var idAlumno = chkAlumno.data('id_alumno');
	var indexRow = chkAlumno.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_alumno">'+
			   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="handleCheckAlumno($(this));" data-id_alumno="'+idAlumno+'">'+
			       '    <span class="mdl-checkbox__label"></span>'+
				   '</label>';
	$('#tbAlumnos').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function asignarAlumno() {
	if(arrayAlumno.legth == 0) {
		mostrarNotificacion('error','Seleccione alumnos');
	}
	
	if(idMainGlobal == null || idMainGlobal == '' || yearGlobal == '' || yearGlobal == null || idGradoGlobal == '' || idGradoGlobal == null ) {
		return;
	}	
	if(idCursoGlobal == '' || idCursoGlobal == null || idSedeGlobal == null || idSedeGlobal == '') {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('buttonDocente');
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_asig_alum_curso/asignarAlumno',
	 		data  : { arrayAlumno : arrayAlumno,
	 				  idMain      : idMainGlobal,
	 				  year	      : yearGlobal,
	 				  idSede      : idSedeGlobal,
	 				  idGrado     : idGradoGlobal,
	 				  idCurso     : idCursoGlobal }
		}).done(function(data) {
			stopLoadingButton('buttonDocente');
			data = JSON.parse(data);
			if(data.error == 0) {
				arrayAlumno = [];
				$('#contTbGrupos').html(data.tbGruposHtml);
				$('#tbGrupos').bootstrapTable({});
				mostrarNotificacion('succes','Se asignaron a los alumnos de forma correcta');
				modal('modalAsignarAlumnos');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			
		});
	});	
}

function buscarAlumnoAula(e) {
	var buscar = $('#buscar').val();
	if(e != undefined) {
		if(e.keyCode != 13) {//TECLA ENTER
			return;
		}
		if(buscar.length == 0) {
			$('#contTbAlum').html(null);
			return;
		}
	}
	if(buscar.length < 3) {
		return;
	}
	if(idAulaGlobal == '' || idAulaGlobal == null || yearGlobal == '' || yearGlobal == null || idMainGlobal == '' || idMainGlobal == null) {
		return;
	}
	if(idCursoGlobal == '' || idCursoGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_asig_alum_curso/buscarAlumnoAsignar',
	    	data   : { idAula  : idAulaGlobal,
					   year    : yearGlobal,
	 				   idCurso : idCursoGlobal,
	 				   buscar  : buscar,
	 				   idMain  : idMainGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
			   $('#contTbAlum').html(data.tbAlumnosHtml);
			   $('#tbAlumnos').bootstrapTable({});
			   componentHandler.upgradeAllRegistered();
			   tableEventsAlumnos();		
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function buscarAlumnoGrupo(e) {
	var buscar = $('#buscarAlumnoGrupo').val();

	if(e != undefined) {
		if(e.keyCode != 13) {//TECLA ENTER
			return;
		}
		if(buscar.length == 0) {
			$('#contTbAlumGrupo').html(null);
			return;
		}
	}
	if(buscar.length < 3) {
		return;
	}
	console.log(idMainGlobalGrupo);
	if(yearGlobal == '' || yearGlobal == null || idMainGlobalGrupo == '' || idMainGlobalGrupo == null) {
		return;
	}
	if(idCursoGlobal == '' || idCursoGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_asig_alum_curso/buscarAlumnoGrupo',
	    	data   : { year    : yearGlobal,
	 				   idCurso : idCursoGlobal,
	 				   buscar  : buscar,
	 				   idMain  : idMainGlobalGrupo }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
				$('#contTbAlumGrupo').html(data.tbAlumnosGrupoHtml);
				$('#tbAlumnoGrupo').bootstrapTable({});
				componentHandler.upgradeAllRegistered();
				tableEventsAlumnoGrupo();	
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

var idMainGlobalGrupo = null;
function getModalAlumnoGrupo(btn) {
	var idMain = btn.data('id_main');
	if(yearGlobal == '' || yearGlobal == null || idMain == '' || idMain == null) {
		return;
	}
	if(idCursoGlobal == '' || idCursoGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_asig_alum_curso/getTbAlumnosGrupo',
	 		data  : { year    : yearGlobal,
	 				  idCurso : idCursoGlobal,
	 				  idMain  : idMain }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				idMainGlobalGrupo = idMain;
				$('#contTbAlumGrupo').html(data.tbAlumnosGrupoHtml);
				$('#tbAlumnoGrupo').bootstrapTable({});
				componentHandler.upgradeAllRegistered();
				tableEventsAlumnoGrupo();
				modal('modalAlumnoGrupo');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			
		});
	});	
}

function handleCheckAlumnoGrupo(chkAlumno) {
	var checked  = chkAlumno.is(":checked");
	var idAlumno = chkAlumno.data('id_alumno');
	var idMain   = chkAlumno.data('id_main'); 
	
	if(idSedeGlobal == null || idSedeGlobal == '' || idMain == null || idMain == '') {
		return;
	}
	checkAlumno(chkAlumno, idAlumno, idMain, checked);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
	 		'url' : 'c_asig_alum_curso/desasigAlumnoGrupo',
	 		data  : { idAlumno : idAlumno,
	 				  idMain   : idMain,
	 				  year     : yearGlobal,
	 				  idCurso  : idCursoGlobal,
	 				  idGrado  : idGradoGlobal,
	 				  idSede   : idSedeGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbAlumGrupo').html(data.tbAlumnosGrupoHtml);
				$('#tbAlumnoGrupo').bootstrapTable({});
				$('#contTbGrupos').html(data.tbGruposHtml);
		    	$('#tbGrupos').bootstrapTable({});
		    	$('#buscarAlumnoGrupo').val(null);
				if(checked == true) {
					mostrarNotificacion('succes','El alumno volvio a pertenecer a este grupo y no podra ser cambiado');
				} else {
					mostrarNotificacion('succes','Se quito a este alumno de este grupo, podra asignarlo en un nuevo grupo');
				}
				componentHandler.upgradeAllRegistered();
				tableEventsAlumnoGrupo();
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			
		});
	});	
}

function getModalAnuncio() {
	modal('mdAlertCambioAlum');
}

function checkAlumno(chkAlumno, idAlumno, idMain, checked) {
	var idCheck  = chkAlumno.attr('id');
	var idAlumno = idAlumno;
	var idMain   = idMain;
	var indexRow = chkAlumno.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_alumno">'+
			   	   '    <input type="checkbox" class="mdl-checkbox__input" id="'+idCheck+'" '+chekado+' onclick="handleCheckAlumno($(this));" data-id_alumno="'+idAlumno+'" data-id_main ="'+idMain+'">'+
			       '    <span class="mdl-checkbox__label"></span>'+
				   '</label>';
	$('#tbAlumnoGrupo').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

/////////////////////////////////////////////////////////////////////////////////////////
function getComboAnioActual() {
	var fecha = new Date();
	var ano = fecha.getFullYear();

	$("#cmbYears option").filter(function() {
	    return this.text == ano; 
	}).attr('selected', true);
	$("#cmbYears").selectpicker('render');
}

function traerLimitesDeCombo() { 
	getComboAnioActual(); 	
	var ult = 1;
    var combo = $('#cmbGradoNivel').children();
    
    $.each(combo, function( index, value ) {
    	if(index == 1) {
    		limiteIzq = index;
        } else { limiteDer = index; }       		
    });         
 }


function goDerIzq( direccion, idxNuevo ) {
	 if(direccion == 1 && idxNuevo == limiteDer || direccion == 0 && idxNuevo == limiteIzq) {
		 ocultarFlecha(direccion);		 
	 } else { aparecerFlecha(direccion); }
}

function ocultarFlecha(direccion) {
	 if(direccion == 1) {
		 $('#der').attr('disabled', true); 
		 return;
	 } if(direccion == 0) {
		 $('#izq').attr('disabled', true); 
		 return; 
	 }	 
}

function aparecerFlecha(direccion) {
	 if(direccion == 1) {
		 $('#izq').removeAttr('disabled'); 
		 return;
	 } if(direccion == 0) {
		 $('#der').removeAttr('disabled'); 
		 return; 
	 }	  
}

function moverIzDer(indicador) {	

	var go_izq   = 0;
	var go_der   = 1;
	var combo    = $('#cmbGradoNivel').children(); 
    var idxNuevo = null;
    var ultimo   = null;
      ///////////////////////////
    $.each(combo, function( index, value ) {
		if(value.value == idGradoGlobal) {
			idxNuevo = index;
			return false;
		}
	 }); 
   //
    idxNuevo = (indicador == go_izq) ? (idxNuevo - 1) : (idxNuevo + 1);
    goDerIzq( indicador, idxNuevo );
    $.each(combo, function( index, value ) {			
	   	if(idxNuevo == index) {
		    nidGrado = value.value;
			return false;
		}
    }); 
    Pace.restart();
	Pace.track(function() {
	    $.ajax({
		    type   : 'POST',
	    	'url'  : 'c_asig_alum_curso/getTbGrupos',
	    	data   : { idGrado : nidGrado,
			    	   idCurso : idCursoGlobal,
					   year    : yearGlobal,
					   idSede  : idSedeGlobal }
	    }).done(function(data) {
	       	data = JSON.parse(data);

	        if(data.error == 0) {
	        	idGradoGlobal = nidGrado;
		    	$('#contTbGrupos').html(data.tbGruposHtml);
		    	$('#tbGrupos').bootstrapTable({});
	           	$('select[name=cmbGradoNivel]').val(nidGrado);
	           	$('#cmbGradoNivel').selectpicker('refresh');
	           	var textGrado = $('#cmbGradoNivel option:selected').text()+" ("+$('#cmbYears option:selected').text()+")";
	        	$('#h2_Grupo').text("Grupos - "+textGrado);
	        	$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#cmbGradoNivel option:selected').text());
	        	
				componentHandler.upgradeAllRegistered();

        		$("#contTbAulas").html(null);
        		//$("#cont_select_empty_curso").css("display", "block");
        		//$("#btnAddCursosEquiv").css("display", "none");
        		$('#contTbGrupos .bootstrap-table .fixed-table-container .fixed-table-body').addClass('table-responsive');
				//if($('#contTbCursoGrado').find('tbody tr.no-records-found').length != 1){
	        	//	$("#btnAddCursosGrado").css("display", "inline-block");
	        	//} else {
	        	//	$("#btnAddCursosGrado").css("display", "none");
        		if($('#contTbGrupos').find('table').length>0){
        			$('#cont_search_grupo').css('display','none');
        		}else $('#cont_search_grupo').css('display','block');
        		$('#cont_show_aula').css('display','block');
	        } else {
				return;
			}
	    });
	});
}  


function tableEventsAlumnos() {
	$(function () {
	    $('#tbAlumnos').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsAlumnoGrupo() {
	$(function () {
	    $('#tbAlumnoGrupo').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

$('#modalAsignarAlumnos').on('hidden.bs.modal', function () {
	arrayAlumno = [];
	$('#buscar').val(null);
});

$('#modalAlumnoGrupo').on('hidden.bs.modal', function () {
	$('#buscarAlumnoGrupo').val(null);
});