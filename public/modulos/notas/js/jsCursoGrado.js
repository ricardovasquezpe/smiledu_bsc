window.onload = traerLimitesDeCombo;
var limiteIzq = null;
var limiteDer = null; 
var idGradoGlobal = null;
var idAnioGlobal  = null;

function initCursoGrado() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	openModalCrearCurso();
	initButtonLoad('btnFC', 'btnCC', 'btnNCU', 'btnNCE', 'btnEC', 'btnECE', 'btnEPC', 'btnEPCE');
}

function openModalCrearCurso() {
	$('#btnCrearCurso').click(function() {
		$('#descCurso').val(null);
		$('#abvrCurso').val(null);
		$('#divArea').css('display','none');
		$('#cmbTipoCurso').filter(function() {
			return this.value = "";
		}).attr('selected', true);
		$("#cmbTipoCurso").selectpicker('render');
		$('#cmbArea').filter(function() {
			return this.value = "";
		}).attr('selected', true);
		$("#cmbArea").selectpicker('render');
		modal('modalCrearCurso');
	});
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

function tipoCursoChange() {
	tipoCurso = $('#cmbTipoCurso option:selected').text();
	
	if(tipoCurso == 'UGEL') {
		$('#divArea').css('display', 'block');
	} else {
		$('#divArea').css('display', 'none');
	}
}

function registrarCurso() {
	addLoadingButton('btnCC');
	var descCurso = $('#descCurso').val();
	var idArea    = $('#cmbArea option:selected').val();
	var abvrCurso = $('#abvrCurso').val();
	var tipoCurso = $('#cmbTipoCurso option:selected').val();
	
	if($.trim(descCurso).length == 0) {
		stopLoadingButton('btnCC');
		mostrarNotificacion('error', 'Ingrese el nombre del curso');
		return;
	}
	if($.trim(abvrCurso).length == 0) {
		stopLoadingButton('btnCC');
		mostrarNotificacion('error', 'Ingrese la abreviatura del curso');
		return;
	}
	if($.trim(descCurso).length > 80) {
		stopLoadingButton('btnCC');
		mostrarNotificacion('error', 'El nombre del curso no debe exceder los 80 caracteres');
		return;
	}
	if($.trim(abvrCurso).length > 5) {
		stopLoadingButton('btnCC');
		mostrarNotificacion('error', 'La abreviatura no debe exceder los 5 caracteres');
		return;
	}

	if(tipoCurso == '') {
		stopLoadingButton('btnCC');
		mostrarNotificacion('error', 'Seleccione el tipo de curso');
		return;
	}
	
	if(idArea == '' && tipoCurso == 'EQUIVALENTE') {
		stopLoadingButton('btnCC');
		mostrarNotificacion('error', 'Seleccione el &aacute;rea acad&eacute;mica');
		return;
	}
	
	Pace.restart();
	Pace.track(function() {
		abvrCurso = abvrCurso.toUpperCase();
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/registrarCurso',
	    	data   : { descCurso : descCurso,
	    		       idArea    : idArea,
	    		       abvrCurso : abvrCurso,
	    		       tipoCurso : tipoCurso }
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   mostrarNotificacion('success', data.msj);
	    	   abrirCerrarModal('modalCrearCurso');
	       } else {
			  mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
	       }
    	   stopLoadingButton('btnCC');
	    });
	});
}
//FILTRAR..... 
function getCursosByGrado() {
	addLoadingButton('btnFC');	
	var idGrado  = $('#cmbGradoNivel option:selected').val();
	var idAnio   = $('#cmbYears option:selected').val();
	$('#cont_search_empty').css('display', 'none');
		
	if(idAnio == '') {
		stopLoadingButton('btnFC');
		mostrarNotificacion('error', 'Seleccione el A&ntilde;o');
		return;
	}	
	if(idGrado == '') {
		stopLoadingButton('btnFC');
		mostrarNotificacion('error', 'Seleccione el Grado');
		return;
	}
	//////////////////////////////////////////////////////////////////
	var gradoMostrar  = $('#cmbGradoNivel option:selected').text();
	var anioMostrar   = $('#cmbYears option:selected').text();
	$('#mostrarGradoAnio').text(gradoMostrar+" - "+anioMostrar);
	
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/selectCursoxGrado',
	    	data   : { idGrado : idGrado,
	    			   idAnio  : idAnio }
	    })
	   /////llama y muestra la tabla de CursoxGrado 
	    .done(function(data) {
	    	data = JSON.parse(data);
	        if(data.error == 0) {
	        	idGradoGlobal = idGrado;
		    	idAnioGlobal  = idAnio;
		    	$('#contTbCursoGrado').html(data.tablaCurs_Grado);
		    	$('#tbCursosByGrado').bootstrapTable({ });
		    	$('#cabecera .breadcrumb li:NTH-CHILD(2)').text($('#cmbYears option:selected').text());
		    	$('#cabecera .breadcrumb li:NTH-CHILD(3)').text($('#cmbGradoNivel option:selected').text());
				componentHandler.upgradeAllRegistered();
				tableEventsCursosxGradoVisibility();
				
	        	if($('#contTbCursoGrado').find('tbody tr.no-records-found').length != 1){
	        		$('#cont_filter_empty').css('display', 'none');
			    	$('#cont_not_filter').css('display', 'none');
	        		$('#cursosEquivalentes').css('display', 'block');
			    	$('#tableCursosUgel').css('display', 'block');
			    	$('#cabecera').css('display', 'block');
			    	$("#btnAddCursosEquiv").css('display', 'none');
			    	$("#btnAddCursosGrado").css("display", "inline-block");
			    	$('#contTbCursoGrado .bootstrap-table .fixed-table-container .fixed-table-body').addClass('table-responsive');
			    	modal('modalFiltros');
	        	} else {
	        		$('#cursosEquivalentes').css('display', 'none');
			    	$('#tableCursosUgel').css('display', 'none');
			    	$('#cabecera').css('display', 'none');
	        		$('#cont_filter_empty').css('display', 'none');
			    	$('#cont_not_filter').css('display', 'block');
			    	$("#btnAddCursosEquiv").css('display', 'none');
	        	}
			} else {
				stopLoadingButton('btnFC');
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
			stopLoadingButton('btnFC');
	    });
	});
}

function moveRowFactor() {
	$(".up,.down").click(function() {
		var idCurso   = $(this).closest('tr').find('.btnID').data('id_curso');
        var orden  	  = $(this).attr('attr-orden');
        var direccion = $(this).attr('attr-direccion');
        moverOrdenCursoUgel(orden, direccion, idCurso);
    });
}

function moverOrdenCursoUgel(orden, direccion, idCurso) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : { orden         : orden,
				      idCurso       : idCurso,
				      direccion     : direccion,
				      idGradoGlobal : idGradoGlobal,
				      idAnioGlobal  : idAnioGlobal},
			url   : 'c_curso_grado/changeOrdenCursoUgel',
			type  : 'POST'
	 	})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				mostrarNotificacion('error', data.msj);
			} else {
				$('#contTbCursoGrado').html(data.tablaCurs_Grado);
				$('#tbCursosByGrado').bootstrapTable({ });
				tableEventsCursosxGradoVisibility();
				componentHandler.upgradeAllRegistered();
				
				var row = $("#tbCursosByGrado tbody>tr").filter(function() {
				    orden = parseInt(orden);
				    var idx = ((direccion == 1) ? orden - 1 : orden + 1) - 1;
				    return $(this).data('index') === idx;
				});//
				if(idCursoGlobal != null && idCurso == idCursoGlobal ) {
					row.css('background-color','#EEEEEE');
				} else if(idCursoGlobal != null && row.find('.btnID').data('id_curso') != idCursoGlobal) {
					row.effect("highlight", {color : '#EEEEEE' }, 3000);
					$("#tbCursosByGrado tbody>tr").filter(function() {
					    return $(this).find('.btnID').data('id_curso') === idCursoGlobal;
					}).css('background-color','#EEEEEE');
				} else if(idCursoGlobal == null) {
					row.effect("highlight", {color : '#EEEEEE' }, 3000);
				}
		   	}
		});
	});
}

function getCursosPorAsignar() {
	///////////////////////////////////
	stopLoadingButton('btnNCU');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/getCursosPorAsignar',
	    	data   : { idGrado : idGradoGlobal,
	    			   idAnio  : idAnioGlobal }
	    })
	    .done(function(data) { // hecho, terminado (done)
	       data = JSON.parse(data);
	       if(data.error == 0) { //si no hay error
	    	   arrayCursoAsig = [];
			  $('#contTbCursosAsig').html(data.tablaCursAsignar);//llama al id donde ubicaremos la tabla Cursos generado en el controlador
	    	  $('#tbCursosAsig').bootstrapTable({ }); //da el formato boostrap
	    	  tableEventsCursosPorAsignar(); // permite dar un formato al combo en toda la paginacion
	    	  componentHandler.upgradeAllRegistered();// permite mantener el formato utilizado
	    	  $('#modalNuevo').find('.mdl-card__title-text').html('Agregar cursos UGEL a '+$('#mostrarGradoAnio').html());
			  modal('modalNuevo');
			} else {
				msj('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	    });
	});
}

var arrayCursoAsig = [];
function handleCheckAsigCurso(chkCurso) {
	var checked = chkCurso.is(":checked");
	var cnt = 0;
	$.each(arrayCursoAsig, function( index, value ) {
		if(value.id_curso == chkCurso.data('id_curso')) {//ELIMINA
			arrayCursoAsig.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {//AGREGAR
		arrayCursoAsig.splice(arrayCursoAsig.length, 0, {id_curso: chkCurso.data('id_curso') } );
	}
	var idCheck  = chkCurso.attr('id');
	var idCurso  = chkCurso.data('id_curso');
	var indexRow = chkCurso.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
			   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="handleCheckAsigCurso($(this));" data-id_curso="'+idCurso+'">'+
			       '    <span class="mdl-checkbox__label"></span>'+
				   '</label>';
	$('#tbCursosAsig').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function asignarCursoUgel() {
	addLoadingButton('btnNCU');
	Pace.restart();
	Pace.track(function() {
		if(arrayCursoAsig.length > 0) {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_curso_grado/asignarCursoUgel',
		    	data   : { arrayCursoAsig : arrayCursoAsig, 
		    		       idGradoGlobal  : idGradoGlobal,
		    		       idAnioGlobal   : idAnioGlobal   }
		    })
		    .done(function(data) {
		    	data = JSON.parse(data);
		    	if(data.error == 0) {
				    arrayCursoAsig = [];
				    $('#contTbCursoGrado').html(data.tablaCurs_Grado);
				    $('#tbCursosByGrado').bootstrapTable({ });
				    componentHandler.upgradeAllRegistered();
				    tableEventsCursosxGradoVisibility();
				    $('#contTbCursosEquiv').html(null);
				    abrirCerrarModal('modalNuevo');
				} else {
				    mostrarNotificacion('error', data.msj, 'ERROR');
				}
		    });
		} else {
			mostrarNotificacion('error', 'Seleccione un curso');
			stopLoadingButton('btnNCU');
		}	
	});	
}

var idGlobalCursoDelete = null;
function eliminarModal(liObject) {
	idGlobalCursoDelete = liObject.closest('tr').find('.btnID').data('id_curso');
    abrirCerrarModal('mdConfirmDelete');
}

function borrarCursoxGrado() {
	addLoadingButton('btnEC');
	if(idGlobalCursoDelete == null || idGradoGlobal == null || idAnioGlobal == null) {
		stopLoadingButton('btnEC');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/deleteCursosxGrado',
	    	data   : { idCurso : idGlobalCursoDelete, 
	    			   idGrado : idGradoGlobal,
	    			   idAnio  : idAnioGlobal}
	    })
	    .done(function(data) {
	    	data = JSON.parse(data);
	        if(data.error == 0) {
	        	idGlobalCursoDelete = null;
	        	idCursoGlobal = null;
		    	$('#contTbCursoGrado').html(data.tablaCurs_Grado);
		    	$('#tbCursosByGrado').bootstrapTable({ });
		    	$('#contTbCursosEquiv').html(null);
		    	abrirCerrarModal('mdConfirmDelete');
				componentHandler.upgradeAllRegistered();
				tableEventsCursosxGradoVisibility();
		    	$("#btnAddCursosEquiv").css('display', 'none');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	    	stopLoadingButton('btnEC');
	    });
		
	});
}


//MUESTRA LA TABLA CURSOS EQUIVALENTES
var idCursoGlobal = null;
function mostrarCursosEquivalentes(btn) {	
	var idCurso  = btn.closest('tr').find('.btnID').data('id_curso');
	if(idCurso == null || idGradoGlobal == null || idAnioGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/selectEquivalencia',
	    	data   : { idCurso : idCurso,
	    			   idGrado : idGradoGlobal,
	    			   idAnio  : idAnioGlobal }
	    })
	    .done(function(data) { // hecho, terminado (done)
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#cont_select_empty_curso').css('display', 'none');
	    	  idCursoGlobal = idCurso;
	    	  //tableEventsCerti(data.tablaCurs_Equivalencia),
	    	  $('#contTbCursosEquiv').css('display', 'block');
			  $('#contTbCursosEquiv').html(data.tablaCurs_Equivalencia);//llama al id donde ubicaremos la tabla Cursos generado en el controlador
	    	  $('#tbCursosEquiv').bootstrapTable({ }); //da el formato boostrap	 
	    	  $('#contTbCursosEquiv .bootstrap-table .fixed-table-container .fixed-table-body').addClass('table-responsive');
	    	  componentHandler.upgradeAllRegistered();
	    	  tableEventsEquivalentes();  	  
	    	  $.each($("#tbCursosByGrado tbody>tr"), function() {
	    		  $(this).css('background-color','white');
			  });
		      $("#tbCursosByGrado tr").filter(function() {
			      return $(this).data('index') == btn.closest('tr').data('index');
			  }).css('background-color','#EEEEEE');
		    	$("#btnAddCursosEquiv").css('display', 'inline-block');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	    });
	});

}

var idCursoEquivGlobal = null;
function moveRowCursoEquiv() {
	$(".up2,.down2").click(function() {
		var idCursoEquiv   = $(this).closest('tr').find('.btnEquivID').data('id_curso_equiv');
        var orden  	  	   = $(this).attr('attr-orden');
        var direccion 	   = $(this).attr('attr-direccion');
        idCursoEquivGlobal = idCursoEquiv;
        moverOrdenCursoEquiv(orden, direccion, idCursoEquiv);
    });
}

function moverOrdenCursoEquiv(orden, direccion, idCursoEquiv) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : { orden         : orden,
				      idCursoEquiv  : idCursoEquiv,
				      direccion     : direccion,
				      idGradoGlobal : idGradoGlobal,
				      idAnioGlobal  : idAnioGlobal,
				      idCursoGlobal : idCursoGlobal },
			url   : 'c_curso_grado/changeOrdenCursoEquiv',
			type  : 'POST'
	 	})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				mostrarNotificacion('error', data.msj);
			} else {
				$('#contTbCursosEquiv').html(data.tablaCurs_Equivalencia);
				$('#tbCursosEquiv').bootstrapTable({ });
				tableEventsEquivalentes();
				componentHandler.upgradeAllRegistered();

				var row = $("#tbCursosEquiv tbody>tr").filter(function() {
				    orden = parseInt(orden);
				    var idx = ((direccion == 1) ? orden - 1 : orden + 1) - 1;
				    return $(this).data('index') === idx;
				});
				row.effect("highlight", {color : '#EEEEEE' }, 3000);
		   	}
		});
	});
}
/////////////////asignar
function getCursosEquivalentesModal() {
	stopLoadingButton('btnNCE');
	if( idCursoGlobal == null) {
		mostrarNotificacion('error', 'Seleccione un curso UGEL');
		return;
	}
	if(idGradoGlobal == null || idAnioGlobal == null) {
		return;	
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/getCursosEquivalentesModal',
	    	data   : { idCurso : idCursoGlobal,
	    			   idGrado : idGradoGlobal,
	    			   idAnio  : idAnioGlobal  }
	    })	
	    .done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) { 
	    	  arrayCursoEquivAsig = [];
			  $('#contTbCursosEquivAsig').html(data.tablaCursEquivAsignar);//llama al id donde ubicaremos la tabla Cursos generado en el controlador
	    	  $('#tbCursosequivalentes').bootstrapTable({ });
	    	  $('#contTbCursosEquiv .bootstrap-table .fixed-table-container .fixed-table-body').addClass('table-responsive');
	    	  tableEventsCursosEquivPorAsignar(); // permite dar un formato al combo en toda la paginacion
	    	  componentHandler.upgradeAllRegistered();// permite mantener el formato utilizado
			  abrirCerrarModal('modalCursoEquivalente');
			} else {
			    mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	    });
	});
}
//////////////////////////
var arrayCursoEquivAsig = [];
function handleCheckAsigCursosEquivalentes(chkCursoEquiv) {
	var checked = chkCursoEquiv.is(":checked");
	var cnt = 0;
	$.each(arrayCursoEquivAsig, function( index, value ) {
		if(value.id_cursoequiv == chkCursoEquiv.data('id_cursoequiv')) {//ELIMINA
			arrayCursoEquivAsig.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {//AGREGAR
		arrayCursoEquivAsig.splice(arrayCursoEquivAsig.length, 0, { id_cursoequiv : chkCursoEquiv.data('id_cursoequiv') } );
	}
	//////////////////////////////////////////
	var idCheck  	  = chkCursoEquiv.attr('id');
	var idCursoEquiv  = chkCursoEquiv.data('id_cursoequiv');
	var indexRow      = chkCursoEquiv.closest('tr').data('index');
	var chekado       = checked == true ? 'checked' : null;
	var newCheck      = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				   	   		'<input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'"onclick="handleCheckAsigCursosEquivalentes($(this));" data-id_cursoEquiv="'+idCursoEquiv+'">'+
				   	   		'<span class="mdl-checkbox__label"></span>'+
			   	   		'</label>';
	$('#tbCursosequivalentes').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function asignarCursoEquiv() {
	addLoadingButton('btnNCE');	
	Pace.restart();
	Pace.track(function() {
		if(arrayCursoEquivAsig.length > 0) {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_curso_grado/asignarCursoEquivUgel',
		    	data   : { arrayCursoEquivAsig : arrayCursoEquivAsig, 
		    		       idGradoGlobal 	   : idGradoGlobal,
		    		       idAnioGlobal        : idAnioGlobal,
		    		       idCursoGlobal       : idCursoGlobal }
		    })
		    .done(function(data) {
		    	data = JSON.parse(data);
		    	if(data.error == 0) {
		    		arrayCursoEquivAsig = [];
				    $('#contTbCursosEquiv').html(data.tablaCurs_Equivalencia);
				    $('#tbCursosEquiv').bootstrapTable({ });
			    	$('#contTbCursoGrado').html(data.tablaCurs_Grado);
			    	$('#tbCursosByGrado').bootstrapTable({ });
			    	$('#contTbCursoGrado .bootstrap-table .fixed-table-container .fixed-table-body').addClass('table-responsive');
				    abrirCerrarModal('modalCursoEquivalente');
			    	tableEventsEquivalentes();
			    	componentHandler.upgradeAllRegistered();
				} else {
				    mostrarNotificacion('error', data.msj, 'ERROR');
				}
		    });
		} else {
			mostrarNotificacion('error', 'Seleccione un curso equivalente');
		}
		stopLoadingButton('btnNCE');
	});	
}

var idGlobalCursoEquivDelete = null;
function eliminar_modalEquivalencia(liObject) {
	 idGlobalCursoEquivDelete = liObject.closest('tr').find('.btnEquivID').data('id_curso_equiv');
	 abrirCerrarModal('mdConfirmDeleteEquivalencia');
	//var idCursoEquiv = idDelete.data('id_curso_equiv');	
}
	
function borrarEquivalencia() {
	addLoadingButton('btnECE');
	if(idGlobalCursoEquivDelete == null) {
    	stopLoadingButton('btnECE');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_curso_grado/deleteEquivalencia',
	    	data   : { idCursoEquiv : idGlobalCursoEquivDelete,
	    			   idGrado		: idGradoGlobal,
	    			   idAnio		: idAnioGlobal,
	    			   idCurso		: idCursoGlobal }
	    })		  
	    .done(function(data) {
	    	data = JSON.parse(data);
	        if(data.error == 0) {
		    	$('#contTbCursosEquiv').html(data.tablaCurs_equiv);
		    	$('#tbCursosEquiv').bootstrapTable({ });
		    	$('#contTbCursoGrado').html(data.tablaCurs_Grado);
		    	$('#tbCursosByGrado').bootstrapTable({ });
		    	abrirCerrarModal('mdConfirmDeleteEquivalencia');
		    	tableEventsEquivalentes();
				componentHandler.upgradeAllRegistered();
				tableEventsCursosxGradoVisibility();
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	    	stopLoadingButton('btnECE');
	    });
		
	});
}

var nidGrado  = null;
function moverIzDer(indicador) {	
	var go_izq = 0;
	var go_der = 1;
	var combo = $('#cmbGradoNivel').children(); 
    var idxNuevo = null;
    var ultimo    = null;
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
	    	'url'  : 'c_curso_grado/selectCursoxGrado',
	    	data   : { idGrado		: nidGrado,
	    			   idAnio		: idAnioGlobal }
	    }).done(function(data) {
	       	data = JSON.parse(data);
	        if(data.error == 0) {
	        	
	        	idGradoGlobal = nidGrado;
		    	$('#contTbCursoGrado').html(data.tablaCurs_Grado);
		    	$('#tbCursosByGrado').bootstrapTable({ });
	           	$('select[name=cmbGradoNivel]').val(nidGrado);
	           	$('#cmbGradoNivel').selectpicker('refresh');
	        	$('#mostrarGradoAnio').text($('#cmbGradoNivel option:selected').text()+" - "+$('#cmbYears option:selected').text());
	        	$('#cabecera .breadcrumb li:NTH-CHILD(3)').text($('#cmbGradoNivel option:selected').text());
				componentHandler.upgradeAllRegistered();
				tableEventsCursosxGradoVisibility();
				
	        	/*if($('#contTbCursoGrado').find('tbody tr.no-records-found').length != 1){
			    	$('#cont_filter_empty').css('display', 'none');
			    	$('#cont_not_filter').css('display', 'none');
	        		$('#cursosEquivalentes').css('display', 'block');
			    	$('#tableCursosUgel').css('display', 'block');
			    	$('#cabecera').css('display', 'block');
	        	} else {
	        		$('#cursosEquivalentes').css('display', 'none');
			    	$('#tableCursosUgel').css('display', 'none');
			    	$('#cont_filter_empty').css('display', 'none');
			    	$('#cabecera').css('display', 'block');
			    	$('#cont_not_filter').css('display', 'block');
	        	}*/

        		$("#contTbCursosEquiv").html(null);
        		$("#cont_select_empty_curso").css("display", "block");
        		$("#btnAddCursosEquiv").css("display", "none");
        		$('#contTbCursoGrado .bootstrap-table .fixed-table-container .fixed-table-body').addClass('table-responsive');
				if($('#contTbCursoGrado').find('tbody tr.no-records-found').length != 1){
	        		$("#btnAddCursosGrado").css("display", "inline-block");
	        	} else {
	        		$("#btnAddCursosGrado").css("display", "none");
	        	}
	        } else {
				return;
			}
	    });
	});
}  
var idGlobalCursoActualizar = null; 
function editarCursoxGrado(liObject) {
	componentHandler.upgradeAllRegistered();
	var peso = null;
	idGlobalCursoActualizar = liObject.closest('tr').find('.btnID').data('id_curso');
	peso = liObject.closest('tr').find('.btnEditar').data('peso');
    $('#peso').val(peso);
	abrirCerrarModal('mdEditarCursoxGrado');	
}

function actualizarPesoCursoxGrado() {
	addLoadingButton('btnEPC');
	var pesoActualizar = $.trim($('#peso').val());	
	if(pesoActualizar > 9) {
    	stopLoadingButton('btnEPC');
		mostrarNotificacion('error', 'El peso no puede ser mayor a 9 ');
		return;
	}
	if(!isNumerico(pesoActualizar)) {
    	stopLoadingButton('btnEPC');
		mostrarNotificacion('error', 'Debe ingresar un n&uacute;mero');
		return;
	}

	if(pesoActualizar <= 0) {
    	stopLoadingButton('btnEPC');
		mostrarNotificacion('error', 'El peso no puede ser cero ni n&uacute;mero negativo');
		return;
	}
	if(pesoActualizar == '') {
    	stopLoadingButton('btnEPC');
		mostrarNotificacion('error', 'ingresar el peso');
		return;
	}
	
	
	$.ajax({
    	type   : 'POST',
    	'url'  : 'c_curso_grado/actualizarCursoxGrado',
    	data   : { idGrado	: idGradoGlobal,
    			   idAnio	: idAnioGlobal,
    			   idCurso	: idGlobalCursoActualizar, 
    			   peso		: pesoActualizar }
    }).done(function(data) {
    	data = JSON.parse(data);
        if(data.error == 0) {
        	$('#contTbCursoGrado').html(data.tablaCurs_Grado);
	    	$('#tbCursosByGrado').bootstrapTable({ });
	    	abrirCerrarModal('mdEditarCursoxGrado');
	    	componentHandler.upgradeAllRegistered();
			tableEventsCursosxGradoVisibility();
		} else {
			mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
		}
    	stopLoadingButton('btnEPC');
    });
}

var idGlobalCursoEquivActualizar = null;
function editarCursoEquiv(liObject) {
	var peso = null;
	idGlobalCursoEquivActualizar = liObject.closest('tr').find('.btnEquivID').data('id_curso_equiv');
	peso = liObject.closest('tr').find('.btnPesoEquiv').data('peso_equiv');
    $('#pesoEquiv').val(peso);
	abrirCerrarModal('mdEditarEquiv');	
}

function actualizarPesoCursoEquiv() {
	addLoadingButton('btnEPCE');
	var pesoActualizar = $.trim($('#pesoEquiv').val());
	if(!isNumerico(pesoActualizar)) {
    	stopLoadingButton('btnEPCE');
		mostrarNotificacion('error', 'Debe ingresar un n&uacute;mero');
		return;
	}
	if(pesoActualizar > 9) {
    	stopLoadingButton('btnEPCE');
		mostrarNotificacion('error', 'El peso no puede ser mayor a 9');
		return;
	}
	if(pesoActualizar <= 0) {
    	stopLoadingButton('btnEPCE');
		mostrarNotificacion('error', 'El peso no puede ser cero ni n&uacute;mero negativo');
		return;
	}
	if(pesoActualizar == '') {
    	stopLoadingButton('btnEPCE');
		mostrarNotificacion('error', 'ingresar el peso');
		return;
	}
	
	$.ajax({
    	type   : 'POST',
    	'url'  : 'c_curso_grado/actualizarCursoEquiv',
    	data   : { idCursoEquiv : idGlobalCursoEquivActualizar,
    			   idGrado		: idGradoGlobal,
    			   idAnio		: idAnioGlobal,
    			   idCurso		: idCursoGlobal, 
    			   peso			: pesoActualizar }
    })		 
    .done(function(data) {
    	data = JSON.parse(data);
        if(data.error == 0) {
        	$('#contTbCursosEquiv').html(data.tablaCurs_equiv);
	    	$('#tbCursosEquiv').bootstrapTable({ });
	    	abrirCerrarModal('mdEditarEquiv');
			componentHandler.upgradeAllRegistered();
			tableEventsEquivalentes();
			tableEventsCursosxGradoVisibility();
		} else {
			mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
		}
    	stopLoadingButton('btnEPCE');
    });
}
//////////////////////////////////////////////
function tableEventsCursosPorAsignar() {
	$(function () {
	    $('#tbCursosAsig').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsCursosEquivPorAsignar() {
	$(function () {
	    $('#tbCursosequivalentes').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsCursosxGradoVisibility() {
	moveRowFactor();
	$(function () {
	    $('#tbCursosByGrado').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsEquivalentes() {
	moveRowCursoEquiv();
	$(function () {
	    $('#tbCursosEquiv').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsCerti(idTablaContenedora){
	var textGlob = null; 
	$(function () { 
		$('#'+idTablaContenedora).on('all.bs.table', function (e, name, args) {
			componentHandler.upgradeAllRegistered();
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
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
