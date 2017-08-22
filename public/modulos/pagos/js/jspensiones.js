var id_sede=null;
var flg_cerrado_matricula = null;
function init() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	tableEventsSedes('tb_sedes');
	$("#tittleBySedes").html(pensiones_year);
	initButtonLoad('botonCFP','botonAPS','botonAPSNG','botonCSP');
}

var fecha = new Date();
var pensiones_year = fecha.getFullYear();
var nombreSede = null;

$('.mdl-layout__tab[href="#tab-1"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">'+
											'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroPensiones" data-mfb-label="Filtrar Pensiones">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-2"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="cronograma_pago_fg">'+
											'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroAlumnoCompromiso" data-mfb-label="Generar Compromisos">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-assignment_ind"></i>'+
											'		<i class="mfb-component__main-icon--active  mdi mdi-assignment_ind"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-3"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap">'+
											'	<button class="mfb-component__button--main">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>'+
											'	</button>'+
											'	<button class="mfb-component__button--main" data-mfb-label="Asignar becas para estudiante" onclick="openModalAsignarBeca();">'+
											'		<i class="mfb-component__main-icon--active mdi mdi-new_student" ></i>'+
											'	</button>'+
											'	<ul class="mfb-component__list">'+
											'		<li class="">'+
											'			<button class="mfb-component__button--child " id="main_save_multi"  onclick="openModalcrearBeca();" data-mfb-label="Nuevo descuento">'+
											'				<i class="mdi mdi-mode_edit"></i>'+
											'			</button>'+
											'		</li>'+
											'	</ul>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-4"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap">'+
											'	<button class="mfb-component__button--main">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>'+
											'	</button>'+
											'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroCompromiso" data-mfb-label="Filtrar y Asignar">'+
											'		<i class="mfb-component__main-icon--active mdi mdi-filter_list" ></i>'+
											'	</button>'+
											'	<ul class="mfb-component__list">'+
											'		<li class="">'+
											'			<button class="mfb-component__button--child" id="main_save_multi" data-toggle="modal" data-target="#modalSaveCompromisos" onclick="loadCompromisosModal(\'modalSaveCompromisos\',\'conceptosCompromisos\')" data-mfb-label="Guardar Compromisos de aulas">'+
											'				<i class="mdi mdi-save"></i>'+
											'			</button>'+
											'		</li>'+
											'		<li class="">'+
											'			<button class="mfb-component__button--child" id="main_save_multiDelete" data-toggle="modal" data-target="#modalFiltroCompromisoDelete" onclick="loadComboCompromisosGlobales();" data-mfb-label="Eliminar Compromisos extras">'+
											'				<i class="mdi mdi-delete"></i>'+
											'			</button>'+
											'		</li>'+
											'	</ul>'+
											'</li>');	
});

function changeYearSedes(year) {
	var tipoCrono = $('#selectTipoCronoPensiones option:selected').val();
	pensiones_year = year;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : { pensiones_year : pensiones_year,
				      tipoCrono      : tipoCrono },
			url   : 'c_pensiones/cargarSedesByYear',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			$("#tittleBySedes").html(pensiones_year);
			$('#tableSSedes').html(data.tableSede);
			$('#tableNivel').html(data.img);
			$('#iconCerrar').html('');
			$('#flechasNavegacion').html(data.flechasNav);
			$('#tb_sedes').bootstrapTable({});
			$(document).ready(function(){
			    $('[data-toggle="tooltip"]').tooltip();
			});
			initSearchTable();
		    tableEventsSedes('tb_sedes');
		    $("#tittleBySedes").html(pensiones_year);
		});
	});
}

function openModalEditarCuota(id, sede) {
	refreshTabs('modalEditarCuota','couta_pensiones');
	nombreSede = sede;
	id_sede    = id;
	var tipoCrono = $('#selectTipoCronoPensiones option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { id             : id,
					 pensiones_year : pensiones_year,
					 tipoCrono      : tipoCrono },
			url   : 'c_pensiones/cargarMontosBySedes',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			flg_cerrado_matricula = data.readonly_mat;
			bloquearMatricula('montoMatriculal','montoMatriculaPromSede','bloquearSede','bloquearPromSede');
			disableEnableInput('switchProm', ((flg_cerrado_matricula == 1) ? true : false));
			if(flg_cerrado_matricula == 1){
				$('#fpromocion').attr('readonly' , true);
			} else{
				$('#fpromocion').attr('readonly' , false);
			}
			$("#nombreSede").html(nombreSede);
			setearInput("montoCuotas", data.monto_pension);
			setearInput("montoMatriculal" , data.monto_matricula);
			setearInput("montoInicial"    , data.cuota_ingreso);
			setearInput("descuentoSede"   , data.descuento_sede);
			setearInput("montoMatriculaPromSede" , data.monto_matricula_prom);
			setearInput("montoCuotaPromoSede"    , data.monto_pension_prom);
			setearInput("fpromocion"    	     , data.fecha_fin_promo);
			var checkedProm = (data.flg_promo == '1') ? 'true' : 'false';
			setChecked('switchProm', checkedProm);
			if(data.check == 'checked'){
				$('#switchCI').prop('checked',true);
				$('#switchCI').parent().addClass('is-checked');
				setCombo('selectTipoCI', data.combo, 'Tipo',true);
			} else {
				$('#switchCI').prop('checked',false);
				$('#switchCI').parent().removeClass('is-checked');
				setCombo('selectTipoCI', data.combo, 'Tipo');
			}
			tableEventsSedes('tb_sedes');
			abrirCerrarModal('modalEditarCuota');
			compruebaPensiones = data.flgNulo;
		});
	});
}

var id_condicion=null;
var id_sedeNivel=null;
var indexRow = null;
var globalMatricula = null;
var globalPension   = null;
var globalIngreso   = null;
var globalDescuento = null;
var globalPaquete   = null;

function openModalEditarNivel(id, idsede, nivel, row) {
	refreshTabs('modalEditarNivel','pensionesNivelTab');
	indexRow = $('#tree').find('tr[data-row="'+row+'"]');
	id_condicion = id;
	id_sedeNivel = idsede;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { id             : id,
					 pensiones_year : pensiones_year },
			url   : 'c_pensiones/cargarMontosbyNivel',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			bloquearMatricula('montoMatriculalNivel','montoMatriculaPromNivel','bloquearNivel','bloquearNivelProm');
			$("#nombreSede1").html(nivel);
			globalMatricula = data.monto_matricula;
			globalPension 	= data.monto_pension;
			globalIngreso 	= data.monto_cuota_ingreso;
			globalDescuento = data.descuento_nivel;
			setearInput("montoCuotasNivel", data.monto_pension);
			setearInput("montoMatriculalNivel" , data.monto_matricula);
			setearInput("montoInicialNivel"    , data.monto_cuota_ingreso);
			setearInput("descuentoNivel"       , data.descuento_nivel);
			setearInput("montoMatriculaPromNivel" , data.monto_matricula_prom);
			setearInput("montoCuotaPromoNivel"    , data.monto_pension_prom);
			tableEventsSedes('tb_sedes');
			abrirCerrarModal('modalEditarNivel');
		});
	});
}

function openModalEditarGrado(id, idsede, nivel, grado, row) {
	refreshTabs('modalEditarGrado','pensionesGradoTab');
	var pos = $('#montoInicialNivel').attr('data-index-table');
	indexRow = row;
	id_condicion = id;
	id_sedeNivel = idsede;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : {id             : id,
					pensiones_year : pensiones_year},
			url   : 'c_pensiones/cargarMontosbyGrado',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			bloquearMatricula('montoMatriculalGrado','montoMatriculaPromGrado','bloquearGrado','bloquearGradoProm');
			$("#nombreSede2").html(nivel+' - '+grado);
			setearInput("montoCuotasGrado"     , data.monto_pension);
			setearInput("montoMatriculalGrado" , data.monto_matricula);
			setearInput("montoInicialGrado"    , data.monto_cuota_ingreso);
			setearInput("montoMatriculaPromGrado" , data.monto_matricula_prom);
			setearInput("montoCuotaPromoGrado"    , data.monto_pension_prom);
			tableEventsSedes('tb_sedes');
			abrirCerrarModal('modalEditarGrado');
		});
	});
}

var compruebaPensiones = 0;
function actualizarPensionesSedes(){
	addLoadingButton('cuotaIngresoBTN');
	var montoMatriculal = $('#montoMatriculal').val();
	var montoCuotas     = $('#montoCuotas').val();
	//var montoInicial    = $('#montoInicial').val();
	var descuentoSede   = $('#descuentoSede').val();
	var tipoCrono       = $('#selectTipoCronoPensiones option:selected').val();
	
	if(montoMatriculal.trim() == '' || montoMatriculal.length == 0 || /^\s+$/.test(montoMatriculal)){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'Ingrese el monto de Matricula');
	}
	if(montoMatriculal <= 0){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'La Matricula debe ser un monto positivo');
	}
	if(montoMatriculal >= 1000000){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'La Matricula debe ser menor que 1000000');
	}
	if( isNaN(montoMatriculal) ) {
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'El monto de la Matricula solo debe contener n&uacute;meros');
	}
	if(montoCuotas.trim() == '' || montoCuotas.length == 0 || /^\s+$/.test(montoCuotas)){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'Ingrese el monto de la Pensi&oacute;n');
	}
	if(montoCuotas <= 0){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser un monto positivo');

	}
	if(montoCuotas >= 1000000){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser menor que 1000000');
	}
	if( isNaN(montoCuotas) ) {
		stopLoadingButton('cuotaIngresoBTN');
	    return mostrarNotificacion('warning', 'El monto de la Pensi&oacute;n solo debe contener n&uacute;meros');
	}
	if(Number(descuentoSede) >= Number(montoCuotas)){
		stopLoadingButton('cuotaIngresoBTN');
		return mostrarNotificacion('warning', 'El descuento debe ser menor a la Pensi&oacute;n 1');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : {montoMatriculal : montoMatriculal,
					montoCuotas     : montoCuotas,
					//montoInicial    : montoInicial,
					id_sede         : id_sede,
					pensiones_year  : pensiones_year,
					descuento_sede  : descuentoSede,
					tipoCrono       : tipoCrono},
			url   : 'c_pensiones/updatePensionesBySedes',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				$('#cuotaIngreso').bind('couta_ingreso',true);
				stopLoadingButton('cuotaIngresoBTN');
				mostrarNotificacion('warning', data.msj);
			} else {
				$('#contRadioCerrar').html(data.radios);
				mostrarNotificacion('warning', data.msj);
				$('#tableSSedes').html(data.tableSede);
				$('#tb_sedes').bootstrapTable({});
				initSearchTable();
			    tableEventsSedes('tb_sedes');
			    $('#flechasNavegacion').html(data.flechasNav);
			    $("#tb_sedes tr").filter(function() {
			        return $(this).data('index')  == indexSedeGlobal;
			    }).css('background-color','#F5F5F5');
			    $('#iconCerrar').html(data.iconSedes);
			    $('#tableNivel').html(data.tableNiveles);
				$('#tb_Nivel').bootstrapTable({});
				$(document).ready(function(){
		            $('[data-toggle="tooltip"]').tooltip(); 
		        });
				initSearchTable();;
				tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
				$('.tree').treegrid();
				$('#montoMatriculaPromSede').val();
				if(flg_cerrado_matricula != 1){
					$('#pensiones').removeClass('display','is-active');
					$('#cuotaIngreso').addClass('display','is-active');
					setTimeout(function(){
						$('#couta_pensiones').removeClass('is-active');
						$('#couta_ingreso').addClass('is-active');
						$('#pensiones').removeClass('is-active');
						$('#cuotaIngreso').addClass('is-active');	
					},100);
				}
				compruebaPensiones = data.flgNulo;
				stopLoadingButton('cuotaIngresoBTN');
			}
			stopLoadingButton('cuotaIngreso');
		});
	});
}

function cerrarSedesPagos() {
	addLoadingButton('botonCSP');
	var tipoCrono = $('#selectTipoCronoPensiones option:selected').val();
	var cerrar    = $('input[name=cerrar]:checked').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { indexSedeGlobalCerrar : indexSedeGlobalCerrar,
					 pensiones_year 	   : pensiones_year,
					 tipoCrono             : tipoCrono,
					 cerrar                : cerrar},
			url   : 'c_pensiones/cerrarSedesPagos',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				stopLoadingButton('botonCSP');
				mostrarNotificacion('warning', data.msj);
			} else {
				flg_cerrado_matricula = data.readonly_mat;
				mostrarNotificacion('warning', data.msj);
				$('#tableSSedes').html(data.tableSede);
				$('#tb_sedes').bootstrapTable({});
				initSearchTable();
			    tableEventsSedes('tb_sedes');
			    
			    $("#tb_sedes tr").filter(function() {
			       return $(this).data('index')  == indexSedeGlobal;
			    }).css('background-color','rgba(0,150,136,0.2)');
			    $('#iconCerrar').html(data.iconSedes);
			    $('#tableNivel').html(data.tableNiveles);
				$('#tb_Nivel').bootstrapTable({});
				$(document).ready(function(){
	                $('[data-toggle="tooltip"]').tooltip(); 
	            });
				initSearchTable();
				tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
				$('.tree').treegrid();
				$('#contRadioCerrar').html(data.radios);
				stopLoadingButton('botonCSP');
				updateMdl();
				stopLoadingButton('botonCSP');
				abrirCerrarModal('modalCerrarSede');
			}
			
		});
	});
}

function openModalCerrarSede(sede) {
	if(indexSedeGlobal != null){
		$("#sedeNombre").html('&#191;Est&aacute;s seguro de definir los montos para la sede '+sede+'?');
		abrirCerrarModal('modalCerrarSede');
	}				
}

var indexSedeGlobal = null;
var indexSedeGlobalCerrar = null;
var flg_promo = 0;
function tableEventsSedes() {
	$("#tb_sedes tbody").css('cursor','pointer');
	$(function () {
	    $('#tb_sedes').on('all.bs.table', function (e, name, args) {
    })
    .on('click-row.bs.table', function (e, row, $element) {
    	$(document).ready(function(){
              $('[data-toggle="tooltip"]').tooltip(); 
            });
    	var idsede 	  = $(row[0]).attr("attr-id-sede");
    	var tipoCrono = $('#selectTipoCronoPensiones option:selected').val();
    	Pace.restart();
    	Pace.track(function() {
	    	$.ajax({
				data : {idsede         : idsede,
						pensiones_year : pensiones_year,
						tipoCrono      : tipoCrono
					   },
				url   : 'c_pensiones/mostrarNiveles',
				type  : 'POST',
				async : true
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.error == 1) {
					mostrarNotificacion('warning', data.msj);
				} else {
					flg_promo 		      = data.flg_promo;
					flg_cerrado_matricula = data.readonly_mat;
					var trs = $element.parent()[0].childNodes;
					$.each(trs, function() {
						$(this).css('background-color','white');
					});
					var index = $element.index();
					indexSedeGlobal = index;
					indexSedeGlobalCerrar = idsede;
			    	$("#tb_sedes tr").filter(function() {
				       return $(this).data('index')  == index;
				    }).css('background-color','#F5F5F5');
			    	$('#iconCerrar').html(data.iconSedes);
				    $('#tableNivel').html(data.tableNiveles);
					$('#tb_Nivel').bootstrapTable({ });
					var visibleCI = (data.flgCI == 'checked') ? 'block' : 'none';
					$('#contCIngresoNivel').css('display',visibleCI);
					$('#contCIngresoGrado').css('display',visibleCI);
					$('#contRadioCerrar').html(data.radios);
					updateMdl();
					initSearchTable();
					tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
					$('.tree').treegrid({
						initialState: 'collapsed',
	                    expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
	                    expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
	                });
					$(document).ready(function(){
		                $('[data-toggle="tooltip"]').tooltip(); 
		            });
				}
			});
    	});
    })
    .on('dbl-click-row.bs.table', function (e, row, $element) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('sort.bs.table', function (e, name, order) {
    	updateMdl();
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('check.bs.table', function (e, row) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('uncheck.bs.table', function (e, row) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('check-all.bs.table', function (e) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('uncheck-all.bs.table', function (e) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('load-success.bs.table', function (e, data) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('load-error.bs.table', function (e, status) {
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('column-switch.bs.table', function (e, field, checked) {
    	updateMdl();
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('page-change.bs.table', function (e, size, number) {
    	updateMdl();
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
               $('[data-toggle="tooltip"]').tooltip(); 
           });
    })
    .on('search.bs.table', function (e, text) {
    	updateMdl();
    	$("#tb_sedes tr").filter(function() {
		       return $(this).data('index')  == indexSedeGlobal;
		    }).css('background-color','rgba(0,150,136,0.2)');
    	$(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });
	    });
	});
}

function actualizarPensionesSedesNivel() {
	addLoadingButton('botonAPS');
	var montoMatriculalNivel = $('#montoMatriculalNivel').val();
	var montoCuotasNivel     = $('#montoCuotasNivel').val();
	var montoInicialNivel    = $('#montoInicialNivel').val();
	var descuentoNivel       = $('#descuentoNivel').val();
	var tipoCrono 		     = $('#selectTipoCronoPensiones option:selected').val();
	if(montoMatriculalNivel.trim() == '' || montoMatriculalNivel.length == 0 || /^\s+$/.test(montoMatriculalNivel)){
		return mostrarNotificacion('warning', 'Ingrese el monto de Matricula');
		stopLoadingButton('botonAPS');
	}
	if(montoMatriculalNivel <= 0){
		return mostrarNotificacion('warning', 'La Matricula debe ser un monto positivo');
		stopLoadingButton('botonAPS');
	}
	if(montoMatriculalNivel >= 1000000){
		return mostrarNotificacion('warning', 'La Matricula debe ser menor que 1000000');
		stopLoadingButton('botonAPS');
	}
	if( isNaN(montoMatriculalNivel) ) {
		return mostrarNotificacion('warning', 'El monto de la Matricula solo debe contener n&uacute;meros');
		stopLoadingButton('botonAPS');
	}
	if(montoCuotasNivel.trim() == '' || montoCuotasNivel.length == 0 || /^\s+$/.test(montoCuotasNivel)){
		return mostrarNotificacion('warning', 'Ingrese el monto de la Pensi&oacute;n');
		stopLoadingButton('botonAPS');
	}
	if(montoCuotasNivel <= 0){
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser un monto positivo');
		stopLoadingButton('botonAPS');
	}
	if(montoCuotasNivel >= 1000000){
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser menor que 1000000');
		stopLoadingButton('botonAPS');
	}
	if( isNaN(montoCuotasNivel) ) {
		return mostrarNotificacion('warning', 'El monto de la Pensi&oacute;n solo debe contener n&uacute;meros');
		stopLoadingButton('botonAPS');
	}
	if(Number(montoCuotasNivel) < Number(descuentoNivel)){
		stopLoadingButton('botonAPS');
		return mostrarNotificacion('warning', 'El descuento debe ser menor a la Pensi&oacute;n');
	}
	
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { montoMatriculalNivel : montoMatriculalNivel,
					 montoCuotasNivel     : montoCuotasNivel,
					 montoInicialNivel    : montoInicialNivel,
					 id_condicion         : id_condicion,
					 id_sedeNivel         : id_sedeNivel,
					 pensiones_year       : pensiones_year,
					 descuento_nivel      : descuentoNivel,
					 tipoCrono			 : tipoCrono
					},
			url   : 'c_pensiones/updatePensionesBySedesByNivel',
			type  : 'POST',
			async : true
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.error == 1) {
					stopLoadingButton('botonAPS');					
					mostrarNotificacion('warning', data.msj);
				} else {
					mostrarNotificacion('warning', data.msj);
					$('#tableSSedes').html(data.tableSede);
					initSearchTable();
					$('#tb_sedes').bootstrapTable({});
				    tableEventsSedes('tb_sedes');
				    
				    $("#tb_sedes tr").filter(function() {
				       return $(this).data('index')  == indexSedeGlobal;
				    }).css('background-color','rgba(0,150,136,0.2)');
				    $(document).ready(function(){
		                $('[data-toggle="tooltip"]').tooltip(); 
		            });
				    $('#tableNivel').html(data.tableNiveles);
					$('#tb_Nivel').bootstrapTable({});
					initSearchTable();
					tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
					$('.tree').treegrid();
					stopLoadingButton('botonAPS');					
					abrirCerrarModal('modalEditarNivel');
				}
			});
	});
	
}

function agreegarParpadeo(flg) {
	flg.addClass("parpadea-text");
}

function actualizarPensionesSedesNivelGrado() {
	addLoadingButton('botonAPSNG');
	var montoInicialGrado    = $('#montoInicialGrado').val();
	var montoMatriculalGrado = $('#montoMatriculalGrado').val();
	var montoCuotasGrado     = $('#montoCuotasGrado').val();
	var tipoCrono 		     = $('#selectTipoCronoPensiones option:selected').val();
	
	if(montoMatriculalGrado.trim() == '' || montoMatriculalGrado.length == 0 || /^\s+$/.test(montoMatriculalGrado)){
		return mostrarNotificacion('warning', 'Ingrese el monto de Matricula');
		stopLoadingButton('botonAPSNG');
	}
	if(montoMatriculalGrado <= 0 ){
		return mostrarNotificacion('warning', 'La Matricula debe ser un monto positivo');
		stopLoadingButton('botonAPSNG');
	}
	if(montoMatriculalGrado >= 1000000){
		return mostrarNotificacion('warning', 'La Matricula debe ser menor que 1000000');
		stopLoadingButton('botonAPSNG');
	}
	if( isNaN(montoMatriculalGrado) ) {
		return mostrarNotificacion('warning', 'El monto de la Matricula solo debe contener n&uacute;meros');
		stopLoadingButton('botonAPSNG');
	}
	if(montoCuotasGrado.trim() == '' || montoCuotasGrado.length == 0 || /^\s+$/.test(montoCuotasGrado)){
		return mostrarNotificacion('warning', 'Ingrese el monto de la Pensi&oacute;n');
		stopLoadingButton('botonAPSNG');
	}
	if(montoCuotasGrado <= 0){
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser un monto positivo');
		stopLoadingButton('botonAPSNG');
	}
	if(montoCuotasGrado >= 1000000){
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser menor que 1000000');
		stopLoadingButton('botonAPSNG');
	}
	if( isNaN(montoCuotasGrado) ) {
		return mostrarNotificacion('warning', 'El monto de la Pensi&oacute;n solo debe contener n&uacute;meros');
		stopLoadingButton('botonAPSNG');
	}

	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { montoInicialGrado 	  : montoInicialGrado,
					 montoMatriculalGrado : montoMatriculalGrado,
					 montoCuotasGrado     : montoCuotasGrado,
					 id_condicion         : id_condicion,
					 id_sedeNivel         : id_sedeNivel,
					 pensiones_year       : pensiones_year,
					 tipoCrono            : tipoCrono},
			url : 'c_pensiones/updatePensionesBySedesByNivelByGrado',
			type : 'POST',
			async : true
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.error == 1) {
					mostrarNotificacion('warning', data.msj);
				} else {
					mostrarNotificacion('warning', data.msj);
					$('#tableSSedes').html(data.tableSede);
					$('#tb_sedes').bootstrapTable({});
					initSearchTable();
				    tableEventsSedes('tb_sedes');
				    $("#tb_sedes tr").filter(function() {
					       return $(this).data('index')  == indexSedeGlobal;
					    }).css('background-color','rgba(0,150,136,0.2)');
				    $(document).ready(function(){
		                $('[data-toggle="tooltip"]').tooltip(); 
		            });
				    $('#tableNivel').html(data.tableNiveles);
					$('#tb_Nivel').bootstrapTable({});
					initSearchTable();
					tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
					$('.tree').treegrid();
					abrirCerrarModal('modalEditarGrado');
				}
				stopLoadingButton('botonAPSNG');
			});
	});
}
	
/*function createFabByTab(tab) {
	$.ajax({
		data  : {tab : tab},
		url   : 'c_configuracion/createFab',
		type  : 'POST',
		async : false
	}).done(function(data){
		$('#menu').html(data);
		if(tab != 'tab3'){
			$('#buscadorMagic').css('display','none');
		}else{
			$('#buscadorMagic').css('display','block');
			$('#divBecas').show();
			$('#cardsAlumnos').hide();
			setearInput("searchMagic", null);
		}
		$('.parpadea-text').removeClass('parpadea-text');
		$('a.mfb-component__button--child').click(function(event){
			event.preventDefault();
			event.stopPropagation();
			abrirCerrarModal($(this).data('modal_open'));
		});
	});
}*/

function getSedesByTipoPago() {
	addLoadingButton('botonCFP');
	var tipoCrono = $('#selectTipoCronoPensiones option:selected').val();
	var anioActual = fecha.getFullYear();
	$.ajax({
		data  : { tipoCrono : tipoCrono },
		url   : 'c_pensiones/getSedesMontoByTipo',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			if(data.flg_sport_summer == 0){
				$('#tableSSedes').html(data.tableSede);
				$('#flechasNavegacion').html(data.flechasNav);
				$('#tb_sedes').bootstrapTable({ });
				tableEventsSedes();
				$('#tab-1 .empty_filter').css('display', 'none');
				$('#tab-1 #cards_tab').css('display', 'block');
				$('#cabecera .breadcrumb li:NTH-CHILD(2)').text($('#selectTipoCronoPensiones option:selected').text());
				$('.mdi.mdi-lock').parent().css("cursor", "default !important");
				$(document).ready(function(){
		            $('[data-toggle="tooltip"]').tooltip(); 
		        });
				$('.tree').treegrid({
					initialState: 'collapsed',
		            expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
		            expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
		        });
			} else{
			}
			stopLoadingButton('boton´CFP');
		} else {
			$('#tab-1 .empty_filter').css('display', 'block');
			$('#tab-1 #cards_tab').css('display', 'none');
			$('#flechasNavegacion').html(null);
			stopLoadingButton('botonCFP');
		}
		$("#tittleBySedes").html(anioActual);
		stopLoadingButton('botonCFP');
	});
}

function registrarActualizarConfigCI(){
	addLoadingButton('botonRAC');
	var montoInicial = $('#montoInicial').val();
	var tipoCI       = $('#selectTipoCI option:selected').val();
	var switchCI     = $('#switchCI').is(':checked');
	var tipoCrono 	 = $('#selectTipoCronoPensiones option:selected').val();
	if(switchCI == true){
		if(montoInicial.trim() == '' || montoInicial.length == 0 || /^\s+$/.test(montoInicial)){
			stopLoadingButton('botonRAC');
			return mostrarNotificacion('warning', 'Ingrese el monto de la Cuota de Ingreso');
		}
		if(montoInicial < 0){
			stopLoadingButton('botonRAC');
			return mostrarNotificacion('warning', 'La Cuota de Ingreso debe ser un monto positivo');
		}
		if(montoInicial >= 1000000){
			stopLoadingButton('botonRAC');
			return mostrarNotificacion('warning', 'La Cuota de Ingreso debe menor que 1000000');
		}
		if( isNaN(montoInicial) ) {
			stopLoadingButton('botonRAC');
			return mostrarNotificacion('warning', 'El monto de la Cuota de Ingreso solo debe contener n&uacute;meros');
		}
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {montoInicial   : montoInicial,
				     tipoCI         : tipoCI,
				     switchCI       : switchCI,
				     id_sede        : id_sede,
				     pensiones_year : pensiones_year,
				     tipoCrono      : tipoCrono},
			url   : 'c_pensiones/setConfigCI',
			async : true,
		    type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				modal('modalEditarCuota');
				$('#tableNivel').html(data.tableNiveles);
				$('.tree').treegrid();
				stopLoadingButton('botonRAC');
			}
			var visibleCI = (data.flgCI == 'true') ? 'block' : 'none';
			$('#contCIngresoNivel').css('display',visibleCI);
			$('#contCIngresoGrado').css('display',visibleCI);
			$('#contRadioCerrar').html(data.radio);
			updateMdl();
			stopLoadingButton('botonRAC');
			msj('success',data.msj);
		});
	});
}

function activeDesactiveCI(sw){
	if(sw.is(':checked')){
		$('#montoInicial').removeAttr('readonly');
		$('#selectTipoCI').removeAttr('disabled');
	} else {
		$('#montoInicial').attr('readonly','readonly');
		$('#selectTipoCI').attr('disabled','disabled');
	}
}

function cuotaIngreso(a) {
	var id = a.attr('id');
	if(id == 'couta_ingreso' || id == 'cuota_prom') {
		if(compruebaPensiones == 0) {
			msj('error','Debes llenar las pensiones');
			$('#modalEditarCuota .mdl-tabs__panel').removeClass('is-active');
			setTimeout(function(){
				$('#modalEditarCuota .mdl-tabs__tab').removeClass('is-active');
				$('#pensiones').addClass('is-active');
				$('#couta_pensiones').addClass('is-active');
			},5)
		} else {
			if(flg_cerrado_matricula == 1 && id == 'couta_ingreso'){
				msj('warning','Ya se ha cerrado la cuota de ingreso');
				setTimeout(function(){
					$('#modalEditarCuota .mdl-tabs__tab').removeClass('is-active');
					$('#couta_pensiones').addClass('is-active');
					$('#pensiones').addClass('is-active');
				},5);
				return; 
			} else if(flg_cerrado_matricula == 0 && id == 'couta_ingreso'){
				setTimeout(function(){
					$('#modalEditarCuota .mdl-tabs__tab').removeClass('is-active');
					$('#couta_ingreso').addClass('is-active');
					$('#cuotaIngreso').addClass('is-active');
				},5);
			}
			if(id == 'cuota_prom'){
				$('#modalEditarCuota .mdl-tabs__panel').removeClass('is-active');
				showPanel('promocionesSede');
			}
		}	
	} else {
		$('#modalEditarCuota .mdl-tabs__panel').removeClass('is-active');
		setTimeout(function(){
			$('#modalEditarCuota .mdl-tabs__tab').removeClass('is-active');
			$('#pensiones').addClass('is-active');
			$('#couta_pensiones').addClass('is-active');
		},5)
	}
}

function actualizaMontosPromocionSede(){
//	if(flg_cerrado_matricula == 0){
		addLoadingButton('btnMontosPromS');
		var switchProm      = $('#switchProm').is(':checked'); 
		var monto_matricula = $("#montoMatriculaPromSede").val();
		var fecFinPromo     = $("#fpromocion").val();
		var tipoCrono       = $('#selectTipoCronoPensiones option:selected').val();
		if(switchProm == true){
			if(monto_matricula.trim() == '' || monto_matricula.length == 0 || /^\s+$/.test(monto_matricula)){
				stopLoadingButton('btnMontosPromS');
				return mostrarNotificacion('warning', 'Ingrese el monto de Ratificaci&oacute;n');
			}
			if(monto_matricula <= 0){
				stopLoadingButton('btnMontosPromS');
				return mostrarNotificacion('warning', 'La Ratificaci&oacute;n debe ser un monto positivo');
			}
			if(monto_matricula >= 1000000){
				stopLoadingButton('btnMontosPromS');
				return mostrarNotificacion('warning', 'La Ratificaci&oacute;n debe ser menor que 1000000');
			}
			if( isNaN(monto_matricula) ) {
				stopLoadingButton('btnMontosPromS');
				return mostrarNotificacion('warning', 'El monto de la Ratificaci&oacute;n solo debe contener n&uacute;meros');
			}
		}
		$.ajax({
			data  : {monto_matricula : monto_matricula,
				     id_sede         : id_sede,
			         pensiones_year  : pensiones_year,
					 tipoCrono       : tipoCrono,
					 switchProm      : switchProm,
					 fecFinPromo     : fecFinPromo},
			url   : 'c_pensiones/saveMontosPromocion',
			async : true,
			type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			msj('warning',data.msj);
			stopLoadingButton('btnMontosPromS');
			flg_promo = data.flg_promo;
			abrirCerrarModal('modalEditarCuota');
		});
//	} else{
//		msj('warning','Ya has cerrado esta configuraci&oacute;n');
//	}
}

function refreshTabs(modal,idActive){
	$('#'+modal+' .mdl-tabs__tab').removeClass('is-active');
	$('#'+idActive).addClass('is-active');
	$('#'+modal+' .mdl-tabs__panel').removeClass('is-active');
	var href = $('#'+idActive).attr('redirect');
	$('#'+href).addClass('is-active');
}

function bloquearMatricula(pension,promocion,iconPension,iconProm){
	var readonly = (flg_cerrado_matricula == 1) ? true    : false;
	var display  = (flg_cerrado_matricula == 1) ? 'block' : 'none';
	$('#'+pension).attr('readonly'   , readonly);
	$('#'+promocion).attr('readonly' , readonly);
	$('#'+iconPension).css('display' ,display);
	$('#'+iconProm).css('display'    ,display);
}

function showPanel(id){
	setTimeout(function(){
		$('#'+id).addClass('is-active');
	},5);
}

function actualizaMontosPromocionNivel(){
	if(flg_promo == 0){
		return msj('warning', 'No se ha activado las promociones');
	}
	if(flg_cerrado_matricula == 0){
		addLoadingButton('btnMontosPromN');
		var monto_matricula = $("#montoMatriculaPromNivel").val();
		var tipoCrono       = $('#selectTipoCronoPensiones option:selected').val();
		if(monto_matricula.trim() == '' || monto_matricula.length == 0 || /^\s+$/.test(monto_matricula)){
			stopLoadingButton('btnMontosPromN');
			return msj('warning', 'Ingrese el monto de Ratificaci&oacute;n');
		}
		if(monto_matricula <= 0){
			stopLoadingButton('btnMontosPromN');
			return msj('warning', 'La Ratificaci&oacute;n debe ser un monto positivo');
		}
		if(monto_matricula >= 1000000){
			stopLoadingButton('btnMontosPromN');
			return msj('warning', 'La Ratificaci&oacute;n debe ser menor que 1000000');
		}
		if( isNaN(monto_matricula) ) {
			stopLoadingButton('btnMontosPromN');
			return msj('warning', 'El monto de la Ratificaci&oacute;n solo debe contener n&uacute;meros');
		}
		$.ajax({
			data  : {monto_matricula : monto_matricula,
			         year            : pensiones_year,
					 tipoCrono       : tipoCrono,
					 id_condicion    : id_condicion,
					 id_sede         : id_sedeNivel},
			url   : 'c_pensiones/saveMontosPromocionNivel',
			async : true,
			type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				modal('modalEditarNivel');
			}
			stopLoadingButton('btnMontosPromN');
			msj('warning',data.msj);
		});
	}
}

function actualizaMontosPromocionGrado(){
	if(flg_promo == 0){
		return msj('warning', 'No se ha activado las promociones');
	}
	if(flg_cerrado_matricula == 0){
		addLoadingButton('btnMontosPromG');
		var monto_matricula = $("#montoMatriculaPromGrado").val();
		var tipoCrono       = $('#selectTipoCronoPensiones option:selected').val();
		if(monto_matricula.trim() == '' || monto_matricula.length == 0 || /^\s+$/.test(monto_matricula)){
			stopLoadingButton('btnMontosPromG');
			return msj('warning', 'Ingrese el monto de Ratificaci&oacute;n');
		}
		if(monto_matricula <= 0){
			stopLoadingButton('btnMontosPromG');
			return msj('warning', 'La Ratificaci&oacute;n debe ser un monto positivo');
		}
		if(monto_matricula >= 1000000){
			stopLoadingButton('btnMontosPromG');
			return msj('warning', 'La Ratificaci&oacute;n debe ser menor que 1000000');
		}
		if( isNaN(monto_matricula) ) {
			stopLoadingButton('btnMontosPromG');
			return msj('warning', 'El monto de la Ratificaci&oacute;n solo debe contener n&uacute;meros');
		}
		$.ajax({
			data  : {monto_matricula : monto_matricula,
			         year            : pensiones_year,
					 tipoCrono       : tipoCrono,
					 id_condicion    : id_condicion,
					 id_sede         : id_sedeNivel},
			url   : 'c_pensiones/saveMontosPromocionGrado',
			async : true,
			type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				modal('modalEditarGrado');
			}
			msj('warning',data.msj);
			stopLoadingButton('btnMontosPromG');
		});
	}
}

function showHideTabProm(){
	
}

function openTableNivel(sede){
	$("#tb_paquetes tbody").css('cursor','pointer');
//	var idsede 	  = $(row[0]).attr("attr-id-sede");
	var tipoCrono = $('#selectTipoCronoPensiones option:selected').val();
	Pace.restart();
	Pace.track(function() {
    	$.ajax({
			data : {sede           : sede,
					pensiones_year : pensiones_year,
					tipoCrono      : tipoCrono
				   },
			url   : 'c_pensiones/getMontosByPaquete',
			type  : 'POST',
			async : true
		}).done(function(data) { 
			data = JSON.parse(data);
			if (data.error == 1) {
				mostrarNotificacion('warning', data.msj);
			} else {
		    	$('#iconCerrar').html(data.iconSedes);
			    $('#tableNivel').html(data.tableNiveles);
				$('#tb_Nivel').bootstrapTable({ });
				var visibleCI = (data.flgCI == 'checked') ? 'block' : 'none';
				$('#contCIngresoNivel').css('display',visibleCI);
				$('#contCIngresoGrado').css('display',visibleCI);
				$('#contRadioCerrar').html(data.radios);
				updateMdl();
				initSearchTable();
				tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
				$('.tree').treegrid({
					initialState: 'collapsed',
                    expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
                    expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
                });
				$(document).ready(function(){
	                $('[data-toggle="tooltip"]').tooltip(); 
	            });
			}
		});
	});
}

function openModalSportSummer(paquete, sede) {
	modal('modalEditarCuotaVerano');
	indexSedeGlobal = sede;
	globalPaquete   = paquete;
	$.ajax({
		data  : { paquete : paquete,
				  sede    : sede },
		url   : 'c_pensiones/cargarMontosBySedes',
		type  : 'POST',
		async : true
	}).done(function(data) {
		data = JSON.parse(data);
		flg_cerrado_matricula = data.readonly_mat;
//		bloquearMatricula('montoMatriculalVerano','montoMatriculaPromSede','bloquearSede','bloquearPromSede');
//		disableEnableInput('switchProm', ((flg_cerrado_matricula == 1) ? true : false));
		if(flg_cerrado_matricula == 1){
			$('#fpromocion').attr('readonly' , true);
		} else{
			$('#fpromocion').attr('readonly' , false);
		}
		$("#nombreSede").html(nombreSede);
		setearInput("montoCuotasVerano", data.monto_pension);
		setearInput("montoMatriculalVerano" , data.monto_matricula);
		setearInput("descuentoSedeVerano"   , data.descuento_sede);
		var checkedProm = (data.flg_promo == '1') ? 'true' : 'false';
		setChecked('switchProm', checkedProm);
//		if(data.check == 'checked'){
//			$('#switchCI').prop('checked',true);
//			$('#switchCI').parent().addClass('is-checked');
//			setCombo('selectTipoCI', data.combo, 'Tipo',true);
//		} else {
//			$('#switchCI').prop('checked',false);
//			$('#switchCI').parent().removeClass('is-checked');
//			setCombo('selectTipoCI', data.combo, 'Tipo');
//		}
		tableEventsSedes('tb_sedes');
		compruebaPensiones = data.flgNulo;
	});
}

function actualizarPensionesSedesVerano() {
	addLoadingButton('cuotaVeranoBTN');
	var montoCuotasVerano   = $('#montoCuotasVerano').val();
	var descuentoSedeVerano = $('#descuentoSedeVerano').val();
	var tipoCrono           = $('#selectTipoCronoPensiones option:selected').val();
	var year                = pensiones_year + 1;
	if(montoCuotasVerano.trim() == '' || montoCuotasVerano.length == 0 || /^\s+$/.test(montoCuotasVerano)){
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'Ingrese el monto de Pensi&oacute;n');
	}
	if(montoCuotasVerano <= 0){
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser un monto positivo');
	}
	if(montoCuotasVerano >= 1000000){
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'La Pensi&oacute;n debe ser menor que 1000000');
	}
	if( isNaN(montoCuotasVerano) ) {
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'El monto de la Pensi&oacute;n solo debe contener n&uacute;meros');
	}
	if(descuentoSedeVerano.trim() == '' || descuentoSedeVerano.length == 0 || /^\s+$/.test(descuentoSedeVerano)){
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'Ingrese el monto de descuento');
	}
	if(descuentoSedeVerano <= 0){
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'El descuento debe ser un monto positivo');
	}
	if(Number(montoCuotasVerano) < Number(descuentoSedeVerano)){
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'El descuento debe ser menor a la Pensi&oacute;n');
	}
	if( isNaN(descuentoSedeVerano) ) {
		stopLoadingButton('cuotaVeranoBTN');
		return mostrarNotificacion('warning', 'El monto de Descuento solo debe contener n&uacute;meros');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  :{ montoCuotasVerano   : montoCuotasVerano,
					 descuentoSedeVerano : descuentoSedeVerano,
				     indexSedeGlobal     : indexSedeGlobal,
				     globalPaquete       : globalPaquete,
				     tipoCrono           : tipoCrono,
				     year                : year},
			url   : 'c_pensiones/setConfigSportSummer',
			type  : 'POST',
			async : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				stopLoadingButton('cuotaVeranoBTN');
				mostrarNotificacion('warning', data.msj);
			} else {
//				$('#contRadioCerrar').html(data.radios);
				mostrarNotificacion('warning', data.msj);
				$('#tableSSedes').html(data.tableSede);
				initSearchTable();
			    tableEventsSedes('tb_paquetes');
//			    $('#flechasNavegacion').html(data.flechasNav);
			    $("#tb_paquetes tr").filter(function() {
			        return $(this).data('index')  == indexSedeGlobal;
			    }).css('background-color','#F5F5F5');
			    $('#iconCerrar').html(data.iconSedes);
			    $('#tableNivel').html(data.tableNiveles);
				$('#tb_Nivel').bootstrapTable({});
				$(document).ready(function(){
		            $('[data-toggle="tooltip"]').tooltip(); 
		        });
				initSearchTable();
				tableEventsUpgradeMdlComponentsMDL('tb_Nivel');
				$('.tree').treegrid();
				$('#montoMatriculaPromSede').val();
//				}
			}
			stopLoadingButton('cuotaVeranoBTN');
			modal('modalEditarCuotaVerano');
		});
	});
	
}