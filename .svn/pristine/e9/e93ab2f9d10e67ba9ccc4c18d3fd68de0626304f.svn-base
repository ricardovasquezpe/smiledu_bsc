var idEncuestaG = null;
var condicionG = null;
function init(){
	$('#tb_encuestas').bootstrapTable({ });
	initSearchTableNew();
	tableEvents();
}

function openModalChangeEstado(idEncuesta,classButton,titulo,condicion,idTipoEnc){
	flg_aperturar = 0;
	idEncuestaG = idEncuesta;
	condicionG  = condicion;
	idTipoEncG  = idTipoEnc;
	$('#texto').text(titulo);
	$('#textoInfo').text("");
	modal('modalAperturarCerrarEncuesta');
}

var flg_aperturar = 0;
function cambiarEstadoEncuesta() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {idEncuesta    : idEncuestaG,
				     condicion     : condicionG,
				     flg_aperturar : flg_aperturar,
				     idTipoEnc     : idTipoEncG},
	        url   : 'c_consultar_encuesta/cambiarEstadoEncuesta',
	        type  : 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.flg_aperturar == 0) {
				$('#textoInfo').text(data.msj);
				flg_aperturar = 1;
			} else {
				if(data.error == 1) {
					msj('warning',data.msj);
				} else if(data.error == 0) {
					$('#contTabEncuestas').html(data.tablaEncuestas);
					$('#tb_encuestas').bootstrapTable({ });
					//$('#ulsEncuesta').html(data.uls); //dfloresgonz 11.05.2016 Se cambio por los ULs se generan al lado del boton mdl-button
					//Inicializa los componentes de MDL
					componentHandler.upgradeAllRegistered();
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
					initSearchTableNew();
					tableEvents();
					modal('modalAperturarCerrarEncuesta');
					flg_aperturar = 0;
					msj('success',data.msj);
				} else {
					msj('success',data.msj);
				}
			}
		});
	});
}

function redirectCrearEncuesta(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	        url   : 'c_consultar_encuesta/redirectCrearEncuesta',
	        type  : 'POST',
	        async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				msj('warning', data.msj);
			} else{
				location.href = data.url;
			}
		});
	});
}

function redirectEditEncuestaInactiva(idEncuesta){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : { idEncuesta : idEncuesta },
	        url   : 'c_consultar_encuesta/redirectEditEncuestaInactiva',
	        type  : 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 1) {
				msj('error', data.msj);
			} else {
				location.href = data.url;
			}
		});
	});
}

var idEncuesta = null;
function openModalDescargarExcel(encuesta){
	$('#cantEncuestas').val(null);
	idEncuesta = encuesta;
	$('#id_encu').val(idEncuesta);
	modal('modalDescargarExcel');
}

var idEncuestaGlobal = null;
var $indexRowGlobal = null;
function openModalSubirExcel(encuesta, liObj){
	idEncuestaGlobal = encuesta;
	$indexRowGlobal  = liObj.closest('tr').data('index');
	modal('modalSubirExcel');
}


function descargarExcelByEncuesta() {
	var cantEncuestados = $('#cantEncuestas').val();
	if(idEncuesta == null || cantEncuestados == null || idEncuesta == "" || cantEncuestados == ""){
		msj('warning','Ingrese la cantidad de encuestados');
	}else if(!($.isNumeric(cantEncuestados))){
		msj('warning','Ingrese s&oacute;lo n&uacute;meros enteros');
	}else{
		$('#cantEncuestados').val(cantEncuestados);
		$('#formExcel').submit();
		modal('modalDescargarExcel');
	}	
}

var idEncBloquear = null;
function openModalBloquearEncuesta(idEncuesta,idTipoEnc){
	idEncBloquear     = idEncuesta;
	idTipoEncBloquear = idTipoEnc;
	modal('modalBloquearEncuesta');
}

function  bloquearEncuesta(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {idEncuesta : idEncBloquear,
				     idTipoEnc  : idTipoEncBloquear},
			url   : 'c_consultar_encuesta/bloquearEncuesta',
			type  : 'POST',
			async : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				msj(data.msj);
			} else{
				$('#contTabEncuestas').html(data.tablaEncuestas);
				//$('#ulsEncuesta').html(data.uls);
				$('#tb_encuestas').bootstrapTable({ });
				//Inicializa los componentes de MDL
				componentHandler.upgradeAllRegistered();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				tableEvents();
				modal('modalBloquearEncuesta');
				msj('success',data.msj);
			}	
		});
	});
}

function vistaPrevia(encuesta){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {encuesta : encuesta},
			url   : 'c_consultar_encuesta/redirectVistaPrevia',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			window.open(data.url,'_blank');
		});
	});
}

function generaUrl(idEncuesta){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {idEncuesta : idEncuesta},
			url   : 'c_consultar_encuesta/getTinyUrlEncuesta',
			type  :'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				msj('warning',data.msj);
			} else{
				modal('modalGeneraUrl');
				$('#urlGenerada').val(data.urlTiny);
				$('#divUrl').addClass('is-focused');
			}
		});
	});
}


function copiarUrl() {
	var copy = document.querySelector('#urlGenerada');
	copy.select();
    document.execCommand('copy');
    msj('success', 'Copiado');
}

function buscarUsuarioCompartir(input) {
	if(!idEncuestaGlobal) {
		return;
	}
	var search = $.trim(input.val());
	if(search.length < 3) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url   : 'c_consultar_encuesta/buscarUsuarios',
			data  : { search     : search ,
				      idEncuesta : idEncuestaGlobal},
			type  : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contBusqPers').html(data.tabla_busqueda);
				$('#tbBusqPersonal').bootstrapTable({ });
				componentHandler.upgradeAllRegistered();
				tableEventsBusquedaPers();
			}
		});
	});
}

function openModalCompartirEncuesta(liObj) {
	idEncuestaGlobal = liObj.closest('tr').find('.cellID').data('id_encuesta');
	flgFirstLoad = null;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url   : 'c_consultar_encuesta/checkIfHasPermisoCompartir',
			data  : { idEncuesta : idEncuestaGlobal },
			type  : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0 && data.is_ok == true) {
				$('#contBusqPers').html(null);
				$('#contCompart').html(null);
				$('#busqUsuario').val(null);
				$('a.mdl-tabs__tab').removeClass('is-active');
				$('.mdl-tabs__panel').removeClass('is-active');
				$('a[href="#buscar"]').addClass('is-active');
				
				$("#btnCompartEncu").unbind("click");
				$('#btnCompartEncu').click(function() {
					compartirEncuesta();
				});
				
				$('#buscar').addClass('is-active');
				modal('modalCompartidos');
			} else {
				msj('error', data.msj);
			}
		});
	});
}

function compartirEncuesta() {
	if(!idEncuestaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		var tableData = $('#tbBusqPersonal').bootstrapTable('getData');
		var permisos = [];
		$.each(tableData, function( key, value ) {
			if(!$(value['checkbox1']).find(':checkbox').is(":checked") && 
			   !$(value['checkbox2']).find(':checkbox').is(":checked") && 
			   !$(value['checkbox3']).find(':checkbox').is(":checked")) {
				return false;
			}
			var permiso = { "id_pers" 			: value['_1_data']['id_pers'],
					        "permiso_editar"    : $(value['checkbox1']).find(':checkbox').is(":checked"),
					        "permiso_compartir" : $(value['checkbox2']).find(':checkbox').is(":checked"),
					        "permiso_graficos"  : $(value['checkbox3']).find(':checkbox').is(":checked") 
					      };
			permisos.push(permiso);
		});console.log(JSON.stringify(permisos));
		$.ajax({
			url   : 'c_consultar_encuesta/compartirEncuesta',
			data  : { permisos   : JSON.stringify(permisos),
				      idEncuesta : idEncuestaGlobal },
			type  : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				msj('success', data.msj);
				$('#contBusqPers').html(null);
				$('#busqUsuario').val(null);
				
				$('#contCompart').html(data.tablaCompartidos);
				$('#tbCompartidos').bootstrapTable({ });
				componentHandler.upgradeAllRegistered();
				
				$("#btnCompartEncu").unbind("click");
				$('#btnCompartEncu').click(function() {
					modal('modalCompartidos');
				});
				
				$('a.mdl-tabs__tab').removeClass('is-active');
				$('.mdl-tabs__panel').removeClass('is-active');
				$('a[href="#usuarios"]').addClass('is-active');
				$('#usuarios').addClass('is-active');
			} else {
				msj('error', data.msj);
			}
		});
	});
}

function modalCompartirtabActionsChange() {
	$('.mdl-tabs__tab').click(function() {
		var yaActivo = $(this).hasClass('is-active');
		if(yaActivo) {
			return;
		}
		if($(this).attr('href') == '#buscar') {
			$("#btnCompartEncu").unbind("click");
			$('#btnCompartEncu').click(function() {
				compartirEncuesta();
			});
		} else if($(this).attr('href') == '#usuarios') {
			$("#btnCompartEncu").unbind("click");
			$('#btnCompartEncu').click(function() {
				modal('modalCompartidos');
			});
			if(flgFirstLoad == null) {
				Pace.restart();
				Pace.track(function() {
					if(!idEncuestaGlobal) {
						return;
					}
					$.ajax({
						url   : 'c_consultar_encuesta/getCompartidos',
						data  : { idEncuesta : idEncuestaGlobal },
						type  : 'POST'
					}).done(function(data) {
						data = JSON.parse(data);
						if(data.error == 0) {
							$('#contCompart').html(data.tablaCompartidos);
							$('#tbCompartidos').bootstrapTable({ });
							componentHandler.upgradeAllRegistered();
						} else {
							msj('error', data.msj);
						}
					});
				});
			}
			flgFirstLoad = 1;
		}
	});
}

function updateCellPermiso(chkBox) {
	var checked  = chkBox.is(":checked");
	var idCheck  = chkBox.attr('id');
	var indexRow = chkBox.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var iClass   = chkBox.next().attr("class");
	var fieldNom = 'checkbox'+chkBox.data('tipo');
	var newCheck = '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="'+idCheck+'">'+
			   	   '    <input type="checkbox" id="'+idCheck+'" '+chekado+' class="mdl-icon-toggle__input" onclick="updateCellPermiso($(this));"'+
			   	   '           data-tipo="'+chkBox.data('tipo')+'">'+
			       '    <i class="'+iClass+'"></i>'+
				   '</label>';
	$('#tbBusqPersonal').bootstrapTable('updateCell', {
		rowIndex   : indexRow,
		fieldName  : fieldNom,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function updateCellPermisoNow(e, chkBox) {
	var checked  = chkBox.is(":checked");
	var fieldNom = 'checkbox'+chkBox.data('tipo');
	var idPers   = chkBox.closest('tr').find('.cellId_pers').data('id_pers');
	if(!idEncuestaGlobal) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : { checked    : checked ,
				      fieldNom   : fieldNom , 
				      idPers     : idPers ,
				      idEncuesta : idEncuestaGlobal} ,
			url   : 'c_consultar_encuesta/modificarPermisosByPers',
			type  : 'POST',
			async : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contCompart').html(data.tablaCompartidos);
				$('#tbCompartidos').bootstrapTable({ });
				componentHandler.upgradeAllRegistered();
				msj('success', data.msj);
			} else {
				msj('error', data.msj);
				e.preventDefault();
				e.stopPropagation();
				componentHandler.upgradeAllRegistered();
			    return false;
			}
		});
	});
}

function tableEvents(){
	$(function () {
	    $('#tb_encuestas').on('all.bs.table', function (e, name, args) {
	    }).on('click-row.bs.table', function (e, row, $element) {
	    }).on('dbl-click-row.bs.table', function (e, row, $element) {
	    }).on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    	$('.mdl-button').on('click',function(){
	    		fix($(this));
	    	});
	    }).on('check.bs.table', function (e, row) {
	    }).on('uncheck.bs.table', function (e, row) {
	    }).on('check-all.bs.table', function (e) {
	    }).on('uncheck-all.bs.table', function (e) {
	    }).on('load-success.bs.table', function (e, data) {
	    }).on('load-error.bs.table', function (e, status) {
	    }).on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    	$('.mdl-button').on('click',function(){
	    		fix($(this));
	    	});
	    }).on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    	$('.mdl-button').on('click',function(){
	    		fix($(this));
	    	});
	    }).on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    	$('.mdl-button').on('click',function(){
	    		fix($(this));
	    	});
	    });
	});
	
	$('.mdl-button').on('click',function(){
		if($(this).data('refresh') == undefined){
			fix($(this));
		}
	});
}

function tableEventsBusquedaPers() {
	$(function () {
	    $('#tbBusqPersonal').on('all.bs.table', function (e, name, args) {
	    }).on('click-row.bs.table', function (e, row, $element) {
	    }).on('dbl-click-row.bs.table', function (e, row, $element) {
	    }).on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    }).on('check.bs.table', function (e, row) {
	    }).on('uncheck.bs.table', function (e, row) {
	    }).on('check-all.bs.table', function (e) {
	    }).on('uncheck-all.bs.table', function (e) {
	    }).on('load-success.bs.table', function (e, data) {
	    }).on('load-error.bs.table', function (e, status) {
	    }).on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    }).on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    }).on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function fix(btn) {
	setTimeout(function() {
		var divOpciones = btn.siblings().first();
		if(divOpciones.offset() != undefined) {
			var bottom = divOpciones.offset().top + divOpciones.height();
			if($(window).height() < bottom) {
				var newTop = (parseInt(divOpciones.css("top"), 10) / 2) ;
				if($('#tb_encuestas').offset().top < 0) {
					newTop = (newTop - $('#tb_encuestas').offset().top ) - 355;
				}
				divOpciones.css({top: newTop});
			}
		}
	}, 500);
}

function setStandByToEnc(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {idEncuesta : idEncuestaG},
	        url   : 'c_consultar_encuesta/changeStandByEncuesta',
	        type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			msj('warning',data.msj);
			if(data.error == 0){
				$('#contTabEncuestas').html(data.tablaEncuestas);
				$('#tb_encuestas').bootstrapTable({ });
				//$('#ulsEncuesta').html(data.uls); //dfloresgonz 11.05.2016 Se cambio por los ULs se generan al lado del boton mdl-button
				//Inicializa los componentes de MDL
				componentHandler.upgradeAllRegistered();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				tableEvents();
				abrirCerrarModal('modalStandByEncuesta');
			}
		});
	});
}

function openModalStandBy(idEncuesta,titulo){
	idEncuestaG = idEncuesta;
	modal('modalStandByEncuesta');
}

function refreshTableEncuestas() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url   : 'c_consultar_encuesta/refreshTable',
			type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contTabEncuestas').html(data.tablaEncuestas);
			//$('#ulsEncuesta').html(data.uls);
			$('#tb_encuestas').bootstrapTable({ });
			//Inicializa los componentes de MDL
			componentHandler.upgradeAllRegistered();
			tableEvents();
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
		});
	});
}

function goLlenadoFisico(idEncuesta, base) {
	window.open(base+'senc/c_encuesta_nueva/c_encuesta_efqm?encu_fisica='+idEncuesta, '_blank');
}

