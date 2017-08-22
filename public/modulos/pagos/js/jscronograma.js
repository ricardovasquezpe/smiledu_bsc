function init(){
	initButtonLoad('btnEditCalendario','btnCrearCronograma','btnDefinirCoutas','btnEditarConcepto','botonEC','btnRC');
}

var conceptoidCronograma=null;
$("#tb_cronograma").on('all.bs.table', function (e, name, args) {
	componentHandler.upgradeAllRegistered(); initButtonCalendarMaxDate
})
.on('page-change.bs.table', function (e, size, number) {
	componentHandler.upgradeAllRegistered();
})


function definirCuotas() {
	addLoadingButton('btnDefinirCoutas');
	var cerrar = $('input[name=cerrarCrono]:checked').val();
	$.ajax({
		data:{cerrar : cerrar},
	    url  : 'c_cronograma_detalle/definirCuotas',
	    async: true,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			stopLoadingButton('btnDefinirCoutas');
			mostrarNotificacion('warning' , data.msj,'');
			
			abrirCerrarModal('modalDefinirCuotas');
			componentHandler.upgradeAllRegistered();
		} else {
			mostrarNotificacion('warning' , data.msj,'');
			$('#botonesCuotas').html(data.botones);
			$('#radiobutton').html(data.radios);
			$('#lista_cronograma').html(data.lista_cronograma);
			$('#tb_cronograma').bootstrapTable({});
			initSearchTable();
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_cronograma');
			stopLoadingButton('btnDefinirCoutas');
			abrirCerrarModal('modalDefinirCuotas');
		}
	});
}

function idConceptoCronograma(id) {
	conceptoidCronograma=id;
}

var idCronograma=null;
function getidCronograma(id) {
	idCronograma=id;
	$("#modalPlantillaCronograma #sedeCrono").val('');
	$("#modalPlantillaCronograma #yearCrono").val('');
}

function cambiar_estado_beca_cronograma(concepto,id) {
	var beca = $("#lista_cronograma #"+id).attr("checked");
	$.ajax({
		data :{ concepto : concepto,
			    beca     : beca},
	    url  : 'c_cronograma_detalle/saveBecaCronograma',
	    async: false,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 0) {
			mostrarNotificacion('warning' , data.msj,'');
			componentHandler.upgradeAllRegistered();
		} else {
			mostrarNotificacion('warning' , data.msj,'');
		}
	});
}

function getCronogramaDetalle(id) {
	var idCrono =  id; 
	$.ajax({
		data : { idCrono : idCrono },
	    url  : 'c_cronograma/getCronogramaDetalleUrl',
	    async: false,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data);
		window.location.href = data['enlace_cronograma'];
		if(data.error == 1) {
			//mostrarNotificacion('warning' , data.msj,'');
		} else {
			//mostrarNotificacion('success' , data.msj,'');
		}
	});
}

function getCronogramaSede() {
	var idSedeC =  $("#modalSedesCronograma #sedes_cronograma").val();
	$.ajax({
		data : { idSedeC : idSedeC },
	    url  : 'c_cronograma/getCronogramaSede',
	    async: false,
	    type : 'post'	
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 1) {
			//mostrarNotificacion('warning' , data.msj,'');
		} else { 
			$("#cardsCompromisosPorAlumno").hide();
			$("#filtroCompromisosCronograma").hide();
			$("#cronograma_pagos").show();
			$("#lista_cronograma").show();
			$("#cronograma_pago_fg").show();$("#main_save_uno").parent().hide();
			$("#tab-2 #lista_cronograma").html(data['lista_cronograma']);
			$("#tab-2 h2").html('Cronograma ('+data['title_cronograma']+')');
			$('#tb_cronograma').bootstrapTable({});
		}
	});
}

function mostrar_radios_detalle_crono() {
	$("#modalCrearDetalleCronograma #matricula2").removeAttr("checked");
	$("#modalCrearDetalleCronograma #matricula2").parent().removeClass("is-checked");
	$("#modalCrearDetalleCronograma #ratificacion2").removeAttr("checked");
	$("#modalCrearDetalleCronograma #ratificacion2").parent().removeClass("is-checked");
	$("#modalCrearDetalleCronograma #cuotas2").removeAttr("checked");
	$("#modalCrearDetalleCronograma #cuotas2").parent().removeClass("is-checked");
	$("#modalCrearDetalleCronograma #cuotas2").addClass("is-checked");
	
	$.ajax({
	    url  : 'c_cronograma_detalle/mostrar_CrearConcepto',
	    async: false	
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			setTimeout(function(){
			$("#modalCrearDetalleCronograma #desc_detalle").focus();
			},500);
			$("#modalCrearDetalleCronograma #matricula2").val(data['lista_comb_1']);
			$("#modalCrearDetalleCronograma #ratificacion2").val(data['lista_comb_2']);
			$("#modalCrearDetalleCronograma #cuotas2").val(data['lista_comb_3']);
			$("#modalCrearDetalleCronograma #cuotas2").parent().addClass('is-checked');
			$('#tb_cronograma').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
			$('#fvencimiento').parent().addClass('is-dirty');
        	$('#fdescuento').parent().addClass('is-dirty');
        	$('#radiosRegistrar').html(data.radios);
        	$('#radiosRegistrarSummer').html(data.radios);
        	componentHandler.upgradeAllRegistered();
		}
	});
}

function editidConceptoCronograma(id) {
		var id2 = id;
		$.ajax({
			data : { idConcepto  : id2 },
		    url  : 'c_cronograma_detalle/mostrar_editConcepto',
		    async: false,
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			console.log(data);
			if(data.error == 1) {
				mostrarNotificacion('warning' , data.msj,'');
			} else {
				setearInput('conceptoeditar',id2);
				setearInput('desc_detalle', data.descrip);
				setearInput('mora', data.mora);
				var fdescuento   = (data.fdesc != "") ? getDateFormat(data.fdesc) : null;
				var fvencimiento = (data.fvenc != "") ? getDateFormat(data.fvenc) : null;
				setearInput('ffdescuento', fdescuento);
				setearInput('ffvencimiento', fvencimiento);
				setearInput('matricula',data.lista_comb_1);
				setearInput('ratificacion',data.lista_comb_2);
				setearInput('cuotas',data.lista_comb_3);
				$("#modalConceptoEditCronograma #matricula").parent().removeClass('is-checked');
				$("#modalConceptoEditCronograma #ratificacion").parent().removeClass('is-checked');
				$("#modalConceptoEditCronograma #cuotas").parent().removeClass('is-checked');
				$("#radiosEdit").html(data.radios);
				componentHandler.upgradeAllRegistered();
				$('#tb_cronograma').bootstrapTable({});
				//componentHandler.upgradeAllRegistered();
			}
		});
}

var meses_val; meses_val = new Array();
function vista_previa_cronograma(cronograma, titulo) {
	$.ajax({
		data  : { cronograma : cronograma, 
				  titulo     : titulo},
	    url   : 'c_cronograma/buildCalendarCuotasTablaCronogramaHTML',
	    async : false,
	    type  : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			$("#modalVistaPreviaCronograma .mdl-card__supporting-text .row").html(data['tabla']);
			$('#tb_cronograma_calendario').bootstrapTable({});
			$('#tituloCronograma').text(titulo);
		}
	});
}

function mostrar_calendario_cuotas() {
	$.ajax({
	    url   : 'c_cronograma_detalle/buildCalendarEditTablaCronogramaHTML',
	    async : false	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			$("#modalCalendarioEditCronograma .mdl-card__supporting-text .row").html(data['tabla']);
			$("#modalCalendarioEditCronograma .mdl-card__supporting-text .row").append(data['input']);
			$('#tb_cronograma_calendario').bootstrapTable({});
			meses_val ={1:'',2:'',3:'',4:'',5:'',6:'',7:'',8:'',9:'',10:'',11:'',12:''}
			tableEventsMeses('tb_cronograma_calendario');
			$('#tb_cronograma_calendario').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
		}
	});
}

function registrarCuotasCalendario() {
	addLoadingButton('btnEditCalendario');
	var tableData = $("#tb_cronograma_calendario").bootstrapTable('getData');
	var sede  	  = $("#modalCalendarioEditCronograma #sede_calendar").val();
	var year      = $("#modalCalendarioEditCronograma #year_calendar").val();
	$.ajax({
			data  : { sede : sede,
				      year : year,
				      mes  : meses_val
			},
		    url   : 'c_cronograma_detalle/saveCuotaSede',
		    async : true,
		    type  : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			stopLoadingButton('btnEditCalendario');
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			stopLoadingButton('btnEditCalendario');
			meses_val ={1:'',2:'',3:'',4:'',5:'',6:'',7:'',8:'',9:'',11:'',12:''}
			mostrarNotificacion('warning' , data.msj,'');
			abrirCerrarModal('modalCalendarioEditCronograma');
			stopLoadingButton('btnEditCalendario');
		}
	});
}

function editItemCronograma() {
	var tableEdit=$("#tab-2 #lista_edit_cronograma table tbody tr td .edit").parents("tr");
	var idItemCronograma = tableEdit.find(".id").html(); //alert(tableEdit.find(".descripcion").html());
	$.ajax({
		data : { idItemCronograma : idItemCronograma },
	    url  : 'c_cronograma/editItemCronograma',
	    async: false,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			$("#modalEditCronograma #listaEditItemCronograma").html(data['lista_item_cronograma']);
			$("#lista_edit_cronograma").html(data['"lista_edit_cronograma"']);
			abrirCerrarModal('modalChangeEvent');
			mostrarNotificacion('warning' , data.msj,'');
		}
	});
}

function getParameterByName(variable) {
	var query = window.location.search.substring(1);
	   var vars = query.split("&");
	   for (var i=0; i < vars.length; i++) {
	       var pair = vars[i].split("=");
	       if(pair[0] == variable) {
	           return pair[1];
	       }
	   }
	   return false;
}

function registrarCronograma() {
	addLoadingButton('btnRC');
	var sedeCrono = sedeGlob;
	var yearCrono = $("#modalCrearCronograma #yearCrono").val();
	var tipoCrono = $("#selectTipoCronoNuevo option:selected").val();
	$.ajax({
		data : { sedeCrono : sedeCrono,
				 yearCrono : yearCrono,
				 tipoCrono : tipoCrono},
	    url  : 'c_cronograma/CrearCronograma',
	    async: true,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			stopLoadingButton('btnRC');
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			stopLoadingButton('btnRC');
			abrirCerrarModal('modalCrearCronograma');
			window.location.href = data['enlace_cronograma'];
			mostrarNotificacion('warning' , data.msj,'');
		}stopLoadingButton('btnRC');
	});
}

function registrarPlantillaCronograma() {
	var idCrono   = $('#Cronogramas option:selected').val();
	var sedeCrono = sedeGlob;
	var yearCrono = $('#yearCrear').val();
	$.ajax({
		data :{  idCrono   : idCrono,
				sedeCrono : sedeCrono,
				yearCrono : yearCrono},
	    url  : 'c_cronograma/registrarPlantillaCronograma',
	    async: false,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			mostrarNotificacion('warning' , data.msj,'');
			window.location.href = data['enlace_cronograma'];
		}
	});
}

function estadoCambiarCuotaCrono() {
	if ($(this).is(':checked')) {
		$(this).prop("checked","");
	}
	else{
		$(this).prop("checked","checked");
	}
}

function registrarConceptosCronograma() {
	addLoadingButton('btnCrearCronograma');
	var desc_detalle = $("#modalCrearDetalleCronograma #desc_detalle").val();
	var mora		 = $("#modalCrearDetalleCronograma #mora").val();
	var fvencimiento = $("#modalCrearDetalleCronograma #fvencimiento").val();
	var fdescuento	 = $("#modalCrearDetalleCronograma #fdescuento").val();
	var condicion    = $('input[name=condicion_op2]:checked').val();
	var paquete      = $('#selectPaquete option:selected').val();
	$.ajax({
		data : { desc_detalle : desc_detalle,
			     mora		  : mora,
			     fvencimiento : fvencimiento,
			     fdescuento   : fdescuento,
			     condicion    : condicion,
			     paquete      : paquete},
	    url  : 'c_cronograma_detalle/addConceptoToCronograma',
	    async: true,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			stopLoadingButton('btnCrearCronograma');
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			setCombo('selectPaquete', data.optPaquete, ' Paquete');
			if(mora.length > 6) {
				stopLoadingButton('btnCrearCronograma');
				mostrarNotificacion('warning' , data.msj);
			}
			abrirCerrarModal('modalCrearDetalleCronograma');
			mostrarNotificacion('warning' , data.msj,'');
			$("#lista_cronograma").html(data['lista_cronograma']);
			$('#tb_cronograma').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_cronograma');
			$("#modalCrearDetalleCronograma #desc_detalle").val("");
			$("#modalCrearDetalleCronograma #mora").val("");
			$("#modalCrearDetalleCronograma #fvencimiento").val('');
			$("#modalCrearDetalleCronograma #fdescuento").val('');
			$("#modalCrearDetalleCronograma #matricula2").removeAttr("checked");
			$("#modalCrearDetalleCronograma #matricula2").parent().removeClass("is-checked");
			$("#modalCrearDetalleCronograma #ratificacion2").removeAttr("checked");
			$("#modalCrearDetalleCronograma #ratificacion2").parent().removeClass("is-checked");
			$("#modalCrearDetalleCronograma #cuotas2").removeAttr("checked");
			$("#modalCrearDetalleCronograma #cuotas2").parent().removeClass("is-checked");
			$("#modalCrearDetalleCronograma #cuotas2").addClass("is-checked");
			stopLoadingButton('btnCrearCronograma');
		}			
	});
}

function eliminarConceptoCronograma() {
	var idConcepto = conceptoidCronograma;
	$.ajax({
		data : { idConcepto : idConcepto },
	    url  : 'c_cronograma_detalle/deleteConceptoToCronograma',
	    async: false,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data); 
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
		} else {
			abrirCerrarModal('modalEliminarConcCrono');
			mostrarNotificacion('warning' , data.msj,'');
			$("#lista_cronograma").html(data['lista_cronograma']);
			componentHandler.upgradeAllRegistered();
			$('#tb_cronograma').bootstrapTable({});
		}
	});
}

function editarConceptoCronograma() {
	addLoadingButton('btnEditarConcepto');
	var concepto	 =	conceptoidCronograma; 
	var concepto2	 = $("#modalConceptoEditCronograma #conceptoeditar").val();
	var desc_detalle = $("#modalConceptoEditCronograma #desc_detalle").val();
	var mora		 = $("#modalConceptoEditCronograma #mora").val();
	var fdescuento	 = $("#modalConceptoEditCronograma #ffdescuento").val();
	var fvencimiento = $("#modalConceptoEditCronograma #ffvencimiento").val();
	var condicion    = $("input[name=condicion_op1]:checked").val(); 
	
	$.ajax({
		data : { concepto      : concepto,
			     concepto2     : concepto2,
			 	 desc_detalle  : desc_detalle,
				 mora		   : mora,
				 fdescuento    : fdescuento,
				 fvencimiento  : fvencimiento,
				 condicion     : condicion},
	    url  : 'c_cronograma_detalle/editConceptoToCronograma',
	    async: true,
	    type : 'POST'	
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 1) {
			mostrarNotificacion('warning' , data.msj,'');
			stopLoadingButton('btnEditarConcepto');
		} else {
			abrirCerrarModal('modalConceptoEditCronograma');
			mostrarNotificacion('warning' , data.msj,'');
			$("#lista_cronograma").html(data['lista_cronograma']);
			componentHandler.upgradeAllRegistered();
			$('#tb_cronograma').bootstrapTable({});
			stopLoadingButton('btnEditarConcepto');
		}			
	});
}

function validateYear() {
	year = $("#yearCrear").val();
	if(year < (new Date).getFullYear()){
		mostrarNotificacion('ERROR', 'Ingrese un a&ntilde;o actual o mayor');
	}
	if((new Date).getFullYear()+1<year){
		mostrarNotificacion('ERROR', 'Ingrese un a&ntilde;o menor');
	}
}

function tableEventsMeses(idTable) {
	$(function () {
		var tableData = $("#tb_cronograma_calendario").bootstrapTable('getData');
		
		$.each(tableData,function(key,value){ 
			$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).change(function(){ 
				meses_val[key+1] = $(this).val();
				$("#tb_cronograma_calendario").bootstrapTable('updateRow', {
	                index: key,
	                row: {
	                	input : '<div class="mdl-textfield mdl-js-textfield mdl-textfield__edit"><input type="text" class="mdl-textfield__input" name="'+value['lista'].toLowerCase()+'" id="'+value['lista'].toLowerCase()+'" value="'+$(this).val()+'"><label class="mdl-textfield__label" for="'+value['lista'].toLowerCase()+'">Couta</label></div>',
	                    mes   : $(this).val()
	                }
	            });
				componentHandler.upgradeAllRegistered(); 
				//$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).select();
	    	});
		});
		
	    $('#'+idTable).on('all.bs.table', function (e, name, args) { 
		    $.each(tableData,function(key,value){ 
				$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).change(function(){
					meses_val[key+1] = $(this).val();
					$("#tb_cronograma_calendario").bootstrapTable('updateRow', {
		                index: key,
		                row: {
		                	input : '<div class="mdl-textfield mdl-js-textfield mdl-textfield__edit"><input type="text" class="mdl-textfield__input" name="'+value['lista'].toLowerCase()+'" id="'+value['lista'].toLowerCase()+'" value="'+$(this).val()+'"><label class="mdl-textfield__label" for="'+value['lista'].toLowerCase()+'">Couta</label></div>',
		                    mes   : $(this).val()
		                }
		            });
					componentHandler.upgradeAllRegistered(); 
					//$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).select();
		    	});
			});
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    	
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    	
	    })
	    .on('sort.bs.table', function (e, name, order) {

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
	    	$.each(tableData,function(key,value){ 
				$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).change(function(){
					meses_val[key+1] = $(this).val();
					$("#tb_cronograma_calendario").bootstrapTable('updateRow', {
		                index: key,
		                row: {
		                	input : '<div class="mdl-textfield mdl-js-textfield mdl-textfield__edit"><input type="text" class="mdl-textfield__input" name="'+value['lista'].toLowerCase()+'" id="'+value['lista'].toLowerCase()+'" value="'+$(this).val()+'"></div>',
		                    mes   : $(this).val()
		                }
		            });
					//$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).select();
		    	});
			});
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	$.each(tableData,function(key,value){ 
				$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).change(function(){
					meses_val[key+1] = $(this).val();
					$("#tb_cronograma_calendario").bootstrapTable('updateRow', {
		                index: key,
		                row: {
		                	input : '<div class="mdl-textfield mdl-js-textfield mdl-textfield__edit"><input type="text" class="mdl-textfield__input" name="'+value['lista'].toLowerCase()+'" id="'+value['lista'].toLowerCase()+'" value="'+$(this).val()+'"></div>',
		                    mes   : $(this).val()
		                }
		            });
					//$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).select();
		    	});
			});
	    })
	    .on('search.bs.table', function (e, text) {
	    	$.each(tableData,function(key,value){ 
				$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).change(function(){
					meses_val[key+1] = $(this).val();
					$("#tb_cronograma_calendario").bootstrapTable('updateRow', {
		                index: key,
		                row: {
		                	input : '<div class="mdl-textfield mdl-js-textfield mdl-textfield__edit"><input type="text" class="mdl-textfield__input" name="'+value['lista'].toLowerCase()+'" id="'+value['lista'].toLowerCase()+'" value="'+$(this).val()+'"></div>',
		                    mes   : $(this).val()
		                }
		            });
					//$("#modalCalendarioEditCronograma #"+value['lista'].toLowerCase()).select();
		    	});
			});
	    });
	});
}

function crearCronograma(sede) {
	$.ajax({
		data : {},
		url  : 'c_cronograma/crearDetalleCronogramaBySede'
	})
	.done(function(data){
		
	});
}

var sedeGlob = null;
function abrirCerrarModalCrearCrono(sede,desc_sede) {
	$('#crearCrono').text('Nuevo cronograma de ' + desc_sede);
	$('.mdl-tabs__tab').removeClass('is-active');
	$('#direccionarNuevoCronograma').addClass('is-active');
	$('.mdl-tabs__panel').removeClass('is-active');
	$('#nuevoCronograma').addClass('is-active');
	sedeGlob = sede;
	abrirCerrarModal('modalCrearCronograma');	
}

function abrirModalPlantilla() {
	setTimeout(function(){
		$('#direccionarNuevaPlantilla').removeClass('is-active');
		$('#direccionarNuevoCronograma').addClass('is-active');
		$('#plantillaCronograma').removeClass('is-active');
		$('#nuevoCronograma').addClass('is-active');
	},20);
}

function getDateFormat(date) {
	var d = new Date(date);
	var mes = (d.getMonth()+1+'').length === 1 ? '0'+(d.getMonth()+1) : (d.getMonth()+1);
	var dia = (d.getDate()+'').length === 1 ? '0'+d.getDate() : (d.getDate());
	var hoyDia = dia+'/'+mes+'/'+d.getFullYear();
	return hoyDia;
}

var idCronoEliminar = null;
function modal_eliminar(cronograma) {
	idCronoEliminar = cronograma;
	modal('modalEliminarCronograma');
}

function eliminarCronograma() {
	addLoadingButton('botonEC');
	$.ajax({
		data : { idCronoEliminar : idCronoEliminar },
		url  : 'c_cronograma/eliminarCronograma',
		type : 'POST',
		async: true
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 1) {
			mostrarNotificacion('warning', data.msj,'');
			modal('modalEliminarCronograma');
			stopLoadingButton('botonEC');
		}else {
			mostrarNotificacion('warning', data.msj,'');
			$("#cronograma_pagos").html(data.tableCronograma);
			$('.tree').treegrid({
            	initialState: 'collapsed',
                expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
                expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
            });
			modal('modalEliminarCronograma');
            stopLoadingButton('botonEC');
		}
	})
}

function upper() {
	setTimeout(function(){
		$('#direccionarNuevoCronograma').addClass('is-active');
		$('#direccionarNuevaPlantilla').removeClass('is-active');
	},200);
	modal('modalSubirPaquete');
}

