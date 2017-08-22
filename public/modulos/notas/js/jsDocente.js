window.onload = traerLimitesDeCombo;
var idGradoGlobal 	    = null;
var idAnioGlobal  	    = null;
var limiteIzq     	    = null;
var limiteDer           = null; 
var indicLimite   	    = null;
var idCursoGlobal 	    = null;
var idCursoGrupoGlobal  = null;
var arrayResetCombo     = [];
var arrayAddRemoveClass = [];
var arrayInputNull      = [];
var arrayDisplay        = [];

function init() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	openModalConsultarGrupo();
	openModalCrearGrupo();
    $('[data-toggle="tooltip"]').tooltip(); 
	initButtonLoad('btnFD', 'buttonDocente', 'btnATC', 'btnCTD1', 'btnCDD','btnCTD2', 'botonRG','btnCTD');
}

function openModalCrearGrupo() {
	$('#btnCrearGrupo').click(function() {
		var foto = fotoDocente(window.location.origin+'/smiledu/public/general/img/profile/nouser.svg');
		$('#descDocente').val(null);
		$('#contFoto').html(foto);
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/iconsGrados',
	    }).done(function(data) { 
	       data = JSON.parse(data);
	    	$('#contTableGrados').html(data.tableGrados);
	    	$('#tbGradosGrupos').bootstrapTable({});
	    	tableEventsGradosGruposVisibility();
	    	componentHandler.upgradeAllRegistered();
	    	modal('modalRegistrarGrupo');	
	    });
	});
}

function openModalConsultarGrupo() {
	$('#consultarGrupos').click(function() {
		$.ajax({
	    	type  : 'POST',
	    	'url' : 'c_docentes/getTableConsultarGrup',
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       $('#contTbConsultarGrupos').html(data.tableGrupos);
	       modal('modalConsultarGrupos');	
	    });
	});
}

var idAreaGlobal = null;
var tipoGlobal   = null;
function getCmbTallerCursoModalGrupo() {
	idAreaGlobal = $('#selecArea option:selected').val();
	if(idAreaGlobal == null || idAreaGlobal == '') {
		$('#taller').css('display','none');
		$('#contCmbAula').html(null);
		$('#descGrupo').val(null);
		$('#capacidad').val(null);
		return;
	}
	$.ajax({
		url   : "c_docentes/getContenidoModalGrupo",
        data  : { idArea : idAreaGlobal },
       //async : false,
        type  : 'POST'
	}).done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			tipoGlobal = data.tipo;
			$('#contCmbTaller').html(data.htmlTallerCurso);
			$('#taller').css('display','none');

			arrayInputNull = ['descTaller', 'capacidad', 'descGrupo'];
			formReset(arrayResetCombo, arrayInputNull);
			inicializarComboClassHtml('select');
			//idAreaGlobal = idAreaUl;
		}
	});
}

var idCursoTallerGlobal = null;
function getFormularioGrupo() {
	idCursoTallerGlobal = $('#cmbCursoTaller option:selected').val();
	var textTaller      = $('#cmbCursoTaller option:selected').text();
	if(idCursoTallerGlobal == null || idCursoTallerGlobal == '') {
		$('#taller').css('display','none');
		$('#contCmbAula').html(null);
		$('#descGrupo').val(null);
		$('#capacidad').val(null);
		return;
	}
	$.ajax({
		url  : "c_docentes/getFormularioGrupo",
		data : { idCursoTaller : idCursoTallerGlobal }, 
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);	
		cssFormulario(tipoGlobal, data.htmlComboAulas, textTaller);
		$('#taller').css('display','block');
		inicializarComboClassHtml('select');
	});
}

function cssFormulario(tipoGlobal, cntAulas, textTaller) {
	if(tipoGlobal == 1) {
		$('.nomGrupo').removeClass('col-sm-12');
		$('.nomGrupo').addClass('col-sm-6');
	    $('#taller').css('display','block');
		$('#contDescTaller').html(inputs('descTaller', 'disabled'));
	    $('#contCmbAula').html(cntAulas);
	    $('#descTaller').val(textTaller);
	} else {
		$('.nomGrupo').removeClass('col-sm-6');
		$('.nomGrupo').addClass('col-sm-12');
		$('#contCmbAula').html(cntAulas);
		$('#contDescTaller').html(null);
	}
}

function inicializarComboHtml(id) {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('#'+id).selectpicker('mobile');
	} else {
		$('#'+id).selectpicker();
	}
}

function inicializarComboClassHtml(_class) {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.'+_class).selectpicker('mobile');
	} else {
		$('.'+_class).selectpicker();
	}
}

function js() {
	
}

function inputs(idName, attr, label='') {
	descTaller = '<div class="col-sm-6 mdl-input-group mdl-input-group__only taller">'+
	    	          '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label descT">'+
				         '<input class="mdl-textfield__input" type="text" id="'+idName+'" name="'+idName+'" maxlength="80"' +attr+'>'+
				         '<label class="mdl-textfield__label" for="capacidad">'+label+'</label>'+
				      '</div>'+
			     '</div>';
	return descTaller;
}

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
	//var ult  = 1;
    var combo = $('#cmbGradoNivel').children();
    $.each(combo, function( index, value ) {
    	limiteDer = index;
    	indicLimite = limiteDer;
    	if(value.value == "") {
    		limiteIzq = index + 1;
    		indicLimite = limiteIzq;
    	} else { index = index; }
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

var gradoMostrarGlobal;
var anioMostrarGlobal;

function getAulas() {
	//(indicLimite == limiteDer) ? $('#der').css('display', 'none') : $('#izq').css('display', 'none'); 
	$('#der').removeAttr('disabled');
	$('#izq').removeAttr('disabled');
	addLoadingButton('btnFD');
	var idGrado  = $('#cmbGradoNivel option:selected').val();
	var idAnio   = $('#cmbYears option:selected').val();
	$('#cont_search_empty').css('display', 'none');
	
	if(idAnio == '') {
		stopLoadingButton('btnFD');
		mostrarNotificacion('error', 'Seleccione el A&ntilde;o');
		return;
	}	
	if(idGrado == '') {
		stopLoadingButton('btnFD');
		mostrarNotificacion('error', 'Seleccione el Grado');
		return;
	}
	var gradoMostrar  = $('#cmbGradoNivel option:selected').text();
	var anioMostrar   = $('#cmbYears option:selected').text();
	
	gradoMostrarGlobal = gradoMostrar;
	anioMostrarGlobal  = anioMostrar;
	$('#mostrarGrado').text(gradoMostrar+" - "+anioMostrar);
	$('#taulas').css('display', 'block');
	$('#tcursos').css('display', 'block');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/getAulas',
	    	data   : { idGrado : idGrado,
	    			   idAnio  : idAnio }
	    }).done(function(data) {
	    	data = JSON.parse(data);
	    	result = data.tablaCurs_Aulas;
	        if(data.error == 0) { 	
	        	idGradoGlobal = idGrado;
		    	idAnioGlobal  = idAnio;		    
		    	$('#contTbAulas').html(data.tablaCurs_Aulas);
		    	$('#tbAulas').bootstrapTable({ });	
		    	$('#cabecera .breadcrumb li:NTH-CHILD(2)').text($('#cmbYears option:selected').text());
		    	$('#cabecera .breadcrumb li:NTH-CHILD(3)').text($('#cmbGradoNivel option:selected').text());
				componentHandler.upgradeAllRegistered();
				tableEventsAulasVisibility();
			    $('[data-toggle="tooltip"]').tooltip();
		    	
	        	if($('#contTbAulas').find('tbody tr.no-records-found').length != 1){			    	
	        		$('#cont_not_filter').css('display', 'none');
			    	$('#cont_filter_empty').css('display', 'none');
	        		$('#tcursos').css('display', 'block');
			    	$('#taulas').css('display', 'block');
			    	$('#cabecera').css('display', 'block');	
			    	abrirCerrarModal('modalDocentes');
	        	} else {
	        		$('#tcursos').css('display', 'none');
			    	$('#taulas').css('display', 'none');
			    	$('#cont_filter_empty').css('display', 'none');
			    	$('#cabecera').css('display', 'none');
			    	$('#cont_not_filter').css('display', 'block');
	        	}
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			stopLoadingButton('btnFD');
	    });
    });
}

var idxNuevo  = null;
function moverIzDer(indicador) {
	var go_izq = 0;
	var go_der = 1;
	var combo = $('#cmbGradoNivel').children(); 
    var nidGrado  = null;
    var ultimo    = null;
    $('#contTbCursos').html(null);
    $('#cont_select_empty_curso').css('display', 'block');
      ///////////////////////////
    $.each(combo, function( index, value ) {
		if(value.value == idGradoGlobal) {
			idxNuevo = index;
			return false;
		}
	 }); 
   //
    idxNuevo = (indicador == go_izq) ? (idxNuevo - 1) : (idxNuevo + 1);
    goDerIzq(indicador, idxNuevo);
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
	    	'url'  : 'c_docentes/getAulas',
	    	data   : { idGrado		: nidGrado,
	    			   idAnio		: idAnioGlobal }
	    }).done(function(data) {
	       	data = JSON.parse(data);
	    	result = data.tablaCurs_Aulas;
	    	
	        if(data.error == 0) {
	        	$('select[name=cmbGradoNivel]').val(nidGrado);
	        	$('#cmbGradoNivel').selectpicker('refresh');
	        	$('#mostrarGrado').text($('#cmbGradoNivel option:selected').text()+" - "+$('#cmbYears option:selected').text());
        		$('#cabecera .breadcrumb li:NTH-CHILD(3)').text($('#cmbGradoNivel option:selected').text());
	        	idGradoGlobal = nidGrado;
		    	$('#contTbAulas').html(data.tablaCurs_Aulas);
		    	$('#tbAulas').bootstrapTable({ });
		    	//$('#tcursos').css('display', 'none');
		    	//$('#cursosEquivalentes').css('display', 'none');
				componentHandler.upgradeAllRegistered();
				tableEventsAulasVisibility();
	        	if($('#contTbAulas').find('tbody tr.no-records-found').length != 1){	
			    	$('#cont_not_filter').css('display', 'none');
			    	$('#cont_filter_empty').css('display', 'none');
	        		$('#tcursos').css('display', 'block');
			    	$('#taulas').css('display', 'block');
			    	$('#cabecera').css('display', 'block');
	        	} else {
	        		$('#tcursos').css('display', 'none');
			    	$('#taulas').css('display', 'none');
			    	$('#cont_filter_empty').css('display', 'none');
			    	$('#cabecera').css('display', 'block');
			    	$('#cont_not_filter').css('display', 'block');
	        	}
	        } else {
				return;
		    }
	    });
	});
	$('[data-toggle="tooltip"]').tooltip(); 
	componentHandler.upgradeAllRegistered();
}
var idxTr = null;
var idAulaGlobal = null;
function getCursos(btn) {	
	var idAula = btn.closest('tr').find('.btnAulaID').data('id_aula');
	if(idGradoGlobal == null || idAnioGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/getCursos',
	    	data   : { idGrado : idGradoGlobal,
	    			   idAnio  : idAnioGlobal,
	    			   idAula  : idAula }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   idxTr = btn.closest('tr').data('index');
	    	   idAulaGlobal = idAula;
	    	  $('#tcursos').css('display', 'block');
			  $('#contTbCursos').html(data.tablaCursos);
	    	  $('#tbCursos').bootstrapTable({ });
	    	  tableEventsCursosVisibility();
	    	  $('#cont_select_empty_curso').css('display','none');
	    	  componentHandler.upgradeAllRegistered();
	    	  $.each($("#tbAulas tbody>tr"), function() {
	    		  $(this).css('background-color','white');
			  });
		      $("#tbAulas tr").filter(function() {
		    	  idxTr = btn.closest('tr').data('index');
			      return $(this).data('index') == idxTr;
			  }).css('background-color','#EEEEEE');
	       } else {
		    	  $('#cont_select_empty_curso').css('display','none');
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function getDocenteModal(liObject) {
	idCursoGlobal = liObject.closest('tr').find('.btnDoc').data('id_curso');
	$('#buttonDocente').attr('onclick','asignarDocente()');
	modalBuscar();
}

function getDocenteGrupoModal() {
	$('#buttonDocente').attr('onclick','asignarDocenteGrupo()');
	modalBuscar();
}

var arrayDocenteGrupo   = [];
var idPersonaGlobal     = null;
var nombreDocenteGlobal = null;
var fotoGlobal          = null;
function radioCheck(chkDocente) {
	var nombreDocente   = chkDocente.data('nom');
	var foto            = chkDocente.data('foto');
	var idPersona       = chkDocente.data('id_doc');
	fotoGlobal          = foto;
	idPersonaGlobal     = idPersona;
	nombreDocenteGlobal = nombreDocente;
}

function asignarDocente() {
	addLoadingButton('buttonDocente');
	if(idPersonaGlobal == null || idCursoGlobal == null || idAulaGlobal == null) {
		stopLoadingButton('buttonDocente');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/asignarDocente',
	    	data   : { idPersona : idPersonaGlobal,
	    			   idCurso   : idCursoGlobal,
	    			   idAula    : idAulaGlobal,
	    			   idGrado   : idGradoGlobal,
	    			   idAnio    : idAnioGlobal }
	    }).done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   arrayDocenteGrupo = [];
	    	   idPersonaGlobal = null;
		       $('#contTbAulas').html(data.tablaCurs_Aulas);
		       $('#tbAulas').bootstrapTable({ });
	    	   $('#contTbCursos').html(data.tablaCursos);
		       $('#tbCursos').bootstrapTable({ });
	    	   componentHandler.upgradeAllRegistered();
	    	   $("#tbAulas tr").filter(function() {
	    		   return $(this).data('index') == idxTr;
	    	   }).css('background-color','#EEEEEE');
	    	   modal('modalAsignarDocente');
	       } else {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	       stopLoadingButton('buttonDocente');
	    });
	});
}

function buscarDocente(e) {
	var buscar = $('#buscar').val();
	var idAula = idAulaGlobal;
	if(e != undefined) {
		if(e.keyCode != 13) {//TECLA ENTER
			return;
		}
		if(buscar.length == 0) {
			$('#contTbDocAsig').html(null);
			return;
		}
	}
	if(buscar.length < 3) {
		return;
	}
	
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/buscarDocenteAsignar',
	    	data   : { buscar     : buscar,
	    			   idAula     : idAula,
	    			   idCurso    : idCursoGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contTbDocAsig').html(data.tablaDocAsig);
	    	   $('#tbDocAsig').bootstrapTable({ });
	    	   componentHandler.upgradeAllRegistered(); 		
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

var idCursoDeleteGlobal   = null;
var idDocenteDeleteGlobal = null;
var idMainGlobal          = null;
var flgActGlobal		  = null;
function asigDesaModal(liObject) {
    idCursoDeleteGlobal   = liObject.closest('tr').find('.btnDoc').data('id_curso');
    idMainGlobal		  = liObject.data('id_main');
	idDocenteDeleteGlobal = liObject.data('id_docente');
	flgActGlobal          = liObject.data('activ_desac'); 
	if(flgActGlobal == 2) {
		$('#activarRadio').css('display', 'inline-block');
		$('#desactivarRadio').css('display', 'none');
	} else {
		$('#activarRadio').css('display', 'none');
		$('#desactivarRadio').css('display', 'inline-block');
	}
	//abrirCerrarModal(mdDesacDesasig);
	abrirCerrarModal('mdConfirmDelete');
}

var desacDesasigGlobal = null;
function radioDesacDesasig(opc) {
	desacDesasigGlobal = opc;
	//desactivarGlobal = $('input[name=radioVals]:checked').val();
}

function desaDesactDocente() {
	addLoadingButton('btnCDD');
	
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/deleteDocenteAsignado',
	    	data   : { idPersonaDocente  : idDocenteDeleteGlobal,
	    			   idAula            : idAulaGlobal,
	    			   idCurso			 : idCursoDeleteGlobal,
	    			   idGrado           : idGradoGlobal,
	    			   idAnio            : idAnioGlobal,
	    			   idMain			 : idMainGlobal, 
	    			   radiOption        : desacDesasigGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contTbCursos').html(data.tablaCursos);
		       $('#tbCursos').bootstrapTable({ });
		       $('#contTbAulas').html(data.tablaCurs_Aulas);
		       $('#tbAulas').bootstrapTable({ });
	    	   componentHandler.upgradeAllRegistered();
	    	   
	    	   $.each($("#tbAulas tbody>tr"), function() {
	    		   $(this).css('background-color','white');
	    	   });
	    	   $("#tbAulas tr").filter(function() {
	    		   return $(this).data('index') == idxTr;
	    	   }).css('background-color','#EEEEEE');
	    	   
	    	   modal('mdConfirmDelete');
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
		   }
	   		stopLoadingButton('btnCDD');
	    });
	});
}
var tutor = null;
var optionGlobalTuCo = null;
var idTutorRolxPersona = null;
function agregarTuCo(opcA, btn) {
	idTutorRolxPersona = btn.data('id_tutor_asignado');
	if(tutor != null) {
		mostrarNotificacion('error', 'Seleccione tutor o cotutor y vuela a dar click aqui');
		return;
	}
	abrirCerrarModal('modalAgregarTuCo');
	optionGlobalTuCo = opcA;
	$('#buscarTutor').val(null);
	$('#contTbTutorAsig').html(null);

}

function getHasTutor() {
	var hasTutor = false;
	$.each($("#tbTutores tbody>tr"), function() {
	   if($(this).hasClass('no-records-found')) {
		   hasTutor = false;
		   return false;
	   }
	   hasTutor = true;
	   return false;
   });
   return hasTutor;
}

var idAulaAsigTutor = null;
var nombreTutor     = null;
function getModalTuCo(list) {
	idAulaAsigTutor = list.closest('tr').find('.btnAulaID').data('id_aula');
	$('.mdl-tabs__tab').removeClass('is-active');
	tutor = 1;
	//$('.tutor_new_css').css('display', 'none');
	$('.cotutor_new_css').css('display', 'none');
	$('.mdl-list').css('display', 'none');
	abrirCerrarModal('modalAsignarTutor');
	//nombreTutor     = list.closest('tr').find('.btnFoto').data('nombre_tutor');
	//$('#nombreTutor').text(nombreTutor);
	
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/getImagesTuCoModal',
	    	data   : { idAula       : idAulaAsigTutor }
	    }).done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contListaCotutor').html(data.imgCotutor);
	    	   $('#contListaTutor').html(data.imgTutor);
	    	   //$('#tbTutorAsig').bootstrapTable({ });
	    	   componentHandler.upgradeAllRegistered();
	    	   
	    	   var hasTutor = getHasTutor();
	    	   if(hasTutor) {
	    		   $('.tutor_new_css').css('display', 'none');
	    	   } else {
	    		   $('.tutor_new_css').css('display', 'none');
	    	   }
	    	   
	    	   $('#buscarTutor').val(null);
	    	   $('#contTbTutorAsig').html(null);
	    	   $.each($("#tbAulas tbody>tr"), function() {
	    		   $(this).css('background-color','white');
	    	   });
	    	   $("#tbAulas tr").filter(function() {
	    		   idxTr = list.closest('tr').data('index');
	    		   return $(this).data('index') == idxTr;
	    	   }).css('background-color','#EEEEEE');
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});  
}

function clickTabAsigTutorCoTutor(aTag) {
	tutor = null;
	$('.mdl-list').css('display', 'block');
	var yaActivo = aTag.hasClass('is-active');
	if(yaActivo) {
		return;
	}
	if(aTag.attr('href') == '#tab_tutor') {
		var hasTutor = getHasTutor();
 	    if(hasTutor) {
 	    	$('.tutor_new_css').css('display', 'none');
			$('.cotutor_new_css').css('display', 'none');
 	    } else {
 	    	$('.tutor_new_css').css('display', 'inline-block');
			$('.cotutor_new_css').css('display', 'none');
 	    }
	} else if(aTag.attr('href') == '#tab_cotutores') {
		$('.tutor_new_css').css('display', 'none');
		$('.cotutor_new_css').css('display', 'inline-block');
	}
}

function buscarTutorAsig(e) {
    var buscarTutor = $('#buscarTutor').val();
    if(e != undefined) {
	    if(e.keyCode != 13) {
		    return;
		}
		if(buscarTutor.length == 0) {
			$('#contTbTutorAsig').html(null);
		    return;
		}
	}
    if(buscarTutor.length < 3) {
    	$('#contTbTutorAsig').html(null);
	    return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/buscarTutorAsignar',
	    	data   : { buscarTutor  : buscarTutor, 
	    			   idAula       : idAulaAsigTutor }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contTbTutorAsig').html(data.tablaTutorModalAsig);
		     //  $('#tbTutorAsig').bootstrapTable({ });
	    	   componentHandler.upgradeAllRegistered(); 		
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});  
}

var idTutorGlobal = null;
var countTutor = null;
function radioTutor(selectTutor) {
	idTutorGlobal = selectTutor.data('id_tutor');
	countTutor    = selectTutor.data('count_tutor');
}

function asigReasigTutorCoTutor() {
	addLoadingButton('btnATC');
	
	if(idAulaAsigTutor == null || idTutorGlobal == null || optionGlobalTuCo == null || idGradoGlobal == null || idAnioGlobal == null) {
		stopLoadingButton('btnATC');
		return;
	}	
    if(countTutor >= 1) {
    	stopLoadingButton('btnATC');
    	mostrarNotificacion('error', 'Ya es tutor de un aula');
    	return;
    } 
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/asigReasigTutorCoTutor',
	    	data   : { idTutorCotutor : idTutorGlobal,
	    			   idAula  		  : idAulaAsigTutor,
	    			   idGrado        : idGradoGlobal,
	    			   idAnio         : idAnioGlobal,
	    			   idTutorRolPer  : idTutorRolxPersona,
	    			   option         : optionGlobalTuCo }
	    }).done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   idTutorGlobal = null;
		       $('#contTbAulas').html(data.tablaCurs_Aulas);
		       $('#tbAulas').bootstrapTable({ });	       
		       $('#contListaCotutor').html(data.imgCotutor);
	    	   $('#contListaTutor').html(data.imgTutor);
	    	   
	    	   var hasTutor = getHasTutor();
	    	   if(hasTutor) {
	    	       $('.tutor_new_css').css('display', 'none');
	    	   } else {
	    		   $('.tutor_new_css').css('display', 'inline-block');
	    	   }
	    	   
			   componentHandler.upgradeAllRegistered();
			   
			   $.each($("#tbAulas tbody>tr"), function() {
	    		   $(this).css('background-color','white');
	    	   });
			   $("#tbAulas tr").filter(function() {
	    		   return $(this).data('index') == idxTr;
	    	   }).css('background-color','#EEEEEE');
			    
			   $('#buscarTutor').val(null);
			   $('#contTbTutorAsig').html(null);
			   
			   modal('modalAgregarTuCo');
	       } else {
	    	   mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	
	       stopLoadingButton('btnATC');
	    });
	});
}
var idCotutorDeleteGlobal = null;
var idTutorDeleteGlobal   = null;
function modalDeleteCotutor(btn) {
	abrirCerrarModal('mdConfirmCotutorDelete');
	var idCotutorDelete = btn.data('id_cotutor_delete');
	idCotutorDeleteGlobal = idCotutorDelete;
}

function modalDeleteTutor(btn) {
	abrirCerrarModal('mdConfirmTutorDelete');
	var idTutorDelete   = btn.data('id_tutor_delete');
	idTutorDeleteGlobal = idTutorDelete;
}

function deleteTutCot(option) {
	addLoadingButton('btnCTD');
	
	if(option != 1 && option != 2) {
		stopLoadingButton('btnCTD');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/deleteTutCot',
	    	data   : { idTutor		      : idTutorDeleteGlobal,
	    			   idAula  		      : idAulaAsigTutor,
	    			   optionDeleteTutCot : option,
	    			   idCotutor	      : idCotutorDeleteGlobal }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				idTutorGlobal = null;
		        $('#contListaCotutor').html(data.imgCotutor);
	    	    $('#contListaTutor').html(data.imgTutor);

	    	    var hasTutor = getHasTutor();
	    	    if(hasTutor) {
	    	    	$('.tutor_new_css').css('display', 'none');
	    	    } else {
	    		    $('.tutor_new_css').css('display', 'inline-block');
	    	    }
	    	    
	    	    if(option == 1) {
	    	    	$('#tbAulas').bootstrapTable('updateCell',{
	    	    		rowIndex   : idxTr,
	    	    		fieldName  : 2,
	    	    		fieldValue : '-'
	    	    	});
	    	    }
			    
			    $.each($("#tbAulas tbody>tr"), function() {
	    		    $(this).css('background-color','white');
	    	    });
			    var rowAula = $("#tbAulas tr").filter(function() {
	    		    return $(this).data('index') == idxTr;
	    	    });
			    rowAula.css('background-color','#EEEEEE');
			    rowAula.find('.btnFoto').removeAttr("data-foto_tutor");
			    rowAula.find('.btnFoto').removeAttr("data-nombre_tutor");
			    
			    componentHandler.upgradeAllRegistered();

			    $('#buscarTutor').val(null);
			    $('#contTbTutorAsig').html(null);
			    
			    if(option == 1) {
			    	modal('mdConfirmTutorDelete');
			    } else if(option == 2) {
			    	modal('mdConfirmCotutorDelete');
			    }
		    } else {
		    	stopLoadingButton('btnCTD');
		    	msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
		    }
			stopLoadingButton('btnCTD');
			});
		});
	}

function asignarDocenteGrupo() {
	var foto = fotoDocente(fotoGlobal);
	$('#descDocente').val(nombreDocenteGlobal);
	$('#contFoto').html(foto);
	abrirCerrarModal('modalAsignarDocente');
}

function fotoDocente(foto) {
	imageStudent = '<img alt="Student" src="'+foto+'" width=100px height=100px class="img-circle m-r-10">';
	return imageStudent;
}

function registrarGrupo() {
	var cmbAula   = $('#cmbAula option:selected').val();
	var nomGrupo  = $('#descGrupo').val();
	var docente   = $('#descDocente').val();
	var descGrupo = $('#descGrupo').val();
	var capacidad = $('#capacidad').val();
	
	if(idCursoTallerGlobal == '' || idCursoTallerGlobal == null) {
		mostrarNotificacion('error', 'Seleccione el Curso o Taller');
		return;
	}

	if(arrayGradoGrupo.length == 0) {
		mostrarNotificacion('error', 'Seleccione el grado o los grados para este grupo');
		return;
	}
	if(idAreaGlobal != null) {	
		if(idPersonaGlobal == '' || cmbAula == '' || descGrupo == '' || capacidad == '' || arrayGradoGrupo.length == 0 || docente == '') {
			mostrarNotificacion('error', 'Llene todos los campos');
			return;
		}
		
		if(capacidad == 0 || capacidad > 100) {
			mostrarNotificacion('error', 'La capacidad del grupo debe ser como minimo 1 y maximo 100');
			return;
		}
	} else {
		mostrarNotificacion('error', 'Selecciones un area');
		return;
	}
	
	if(cmbAula == '') {
		mostrarNotificacion('error', 'Selecciones un Aula');
		return;
	}

	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_docentes/registrarGrupo',
	    	data   : { idArea          : idAreaGlobal,
	    			   idCursoTaller   : idCursoTallerGlobal,
	    			   arrayGradoGrupo : arrayGradoGrupo,
	    			   idAula   	   : cmbAula,
	    			   descGrupo       : descGrupo,
	    			   idDocente       : idPersonaGlobal,
	    			   capacidad       : capacidad }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contCmbTaller').html(data.htmlTaller);
	    	   $('#contTbConsultarGrupos').html(data.tablaGruposConsult);
	    	    componentHandler.upgradeAllRegistered();
	    	    $(".inputDocente").prop("disabled", false);
	    	    $(".descDoc").removeClass('is-disabled');
	    	    $("#descDocente").val(null);
	    		mostrarNotificacion('succes','Se registro correctamente');
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
		   }	    	  
	    });
	}); 
}

var stepGlobal = null;
function nextStep(step) {
	if(step == 1){
		$("#progressBar").css("width","0%");
	} else if(step == 2) {
		$("#progressBar").css("width","50%");
	} else if(step == 3) {
		$("#progressBar").css("width","100%");
	}
	stepGlobal = step;
}

var arrayGradoGrupo = [];
function handleCheckGradoGrupo(chkGrado) {
	var checked = chkGrado.is(":checked");
	var cnt = 0;
	$.each(arrayGradoGrupo, function( index, value ) {
		if(value.__id_grado == chkGrado.data('id_grado_grupo')) {//ELIMINA
			arrayGradoGrupo.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {//AGREGAR
		arrayGradoGrupo.splice(arrayGradoGrupo.length, 0, { __id_grado : chkGrado.data('id_grado_grupo') } );
	}
	
	var idCheck  = chkGrado.attr('id');
	var idGrado  = chkGrado.data('id_grado_grupo');
	var indexRow = chkGrado.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_docente">'+
			   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="handleCheckGradoGrupo($(this));" data-id_grado_grupo="'+idGrado+'">'+
			       '    <span class="mdl-checkbox__label"></span>'+
				   '</label>';
	$('#tbGradosGrupos').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function formReset(arrayResetCombo, arrayInput) {
	if(arrayResetCombo.length > 0 && arrayResetCombo != null) {
		$.each(arrayResetCombo, function(index, value) {
			$("#"+value +" option").filter(function() {
			    return this.value == 0; 
			}).attr('selected', true);
			$("#"+value).selectpicker('render');	
		});
		arrayResetCombo = [];
	} 

	if(arrayInput.length > 0 && arrayInput != null) {
		$.each(arrayInput, function(index, value) {
			$("#"+value).val(null);	
		});
		arrayInputNull = [];
	}
}

function display($id, $tipo) {
	$('#'+$id).css('display', $tipo);
}

function arrayDisplay($array, $tipo) {	
	$.each($array, function(index, value) {
		$('#'+value).css('display', $tipo);
	});
}

function addRemoveClass(arrayClass, classAddRem, tipo) {
	if(arrayClass.length > 0 && arrayClass != null) {	
		if(tipo == 1) {
			$.each(arrayClass, function(index, value) {
				$('.'+value).addClass(classAddRem, true);
			});
			
		} else if(tipo == 2) {
			$.each(arrayClass, function(index, value) {
				$('.'+value).removeClass(classAddRem, true);
			});
		}
	}
	arrayAddRemoveClass = [];
}

function getAsigGrupoModal() {
	//var idArea = $('#selecArea option:selected').val();
	$.ajax({
		url   : "c_docentes/getTableAsigGrup",
        data  : { idGrado : idGradoGlobal },
        async : false,
        type  : 'POST'
	}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbAsigGrupos').html(data.tableAsigGrupos);
				modal('modalAsignarGrupos');
			} else {
				return;
			}
	});
}

function getGrupoModal(liObject) {
	idCurso = liObject.closest('tr').find('.btnDoc').data('id_curso');
	$.ajax({
		url   : "c_docentes/getGrupo",
        data  : { idCurso : idCurso,
        	      idGrado : idGradoGlobal,
        	      year    : idAnioGlobal},
        async : false,
        type  : 'POST'
	}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbGruposByCursos').html(data.tableGruposByCursos);
				modal('modalGrupos');
			} else {
				return;
			}
	});
}

function modalBuscar() {
	$('#buscar').val(null);
	$('#contTbDocAsig').html(null);
	abrirCerrarModal('modalAsignarDocente');
}

function tableEventsAulasVisibility() {
	$(function () {
	    $('#tbAulas').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsCursosVisibility() {
	$(function () {
	    $('#tbCursos').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsGradosGruposVisibility() {
	$(function () {
	    $('#tbGradosGrupos').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsDocAsigVisibility() {
	$(function () {
	    $('#tbDocAsig').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

$('#modalRegistrarGrupo').on('hidden.bs.modal', function () {
	arrayGradoGrupo   = [];
	display('taller','none');
	$("#progressBar").css("width","0%");
	$(".inputDocente").prop("disabled", false);
    $(".descDoc").removeClass('is-disabled');
    
    arrayAddRemoveClass = ['tab1', 'tabb1'];
    arrayInputNull      = ['descDocente', 'descTaller', 'capacidad', 'descGrupo'];
    arrayResetCombo     = ['cmbAula', 'selecArea', 'cmbTaller','cmbCursos'];
    
    formReset(arrayResetCombo, arrayInputNull);
    addRemoveClass(arrayAddRemoveClass, 'active', 1);
    $('#contCmbTaller').html(null);
    
	if (stepGlobal == 2) {
		arrayAddRemoveClass = ['tab2', 'tabb2'];		
	} else if(stepGlobal == 3) {
		arrayAddRemoveClass = ['tab3', 'tabb3'];
	}
	addRemoveClass(arrayAddRemoveClass, 'active', 2);
});