function init(){
	$('#tbHijos').bootstrapTable({ });
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initButtonLoad('btnChangeTallerGrupo', 'btnConfirmAsignarGrupo');
}

var cons_hijo = null;
var cons_nombre_hijo = null;
function mostrarTalleresHijo(hijo, nombreHijo, elem){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_selec_taller/talleresHijo',
	    	data   : {hijo : hijo}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.count > 0){
	    	   cons_hijo = hijo;
	    	   cons_nombre_hijo = nombreHijo;
	    	   $("#cont_select_empty_taller").css("display", "none");
	    	   $("#contTalleresHijo").html(data.tbTalleres);
	    	   $('#tbTalleres').bootstrapTable({});
	    	   $(document).ready(function(){
	       	       $('[data-toggle="tooltip"]').tooltip();
	           });
	       }else{
	    	   $("#contTalleresHijo").html(null);
	    	   $("#cont_select_empty_taller").css("display", "block");
	       }
	       $(elem).closest('tr').parent().find("tr").css("background-color", "white");
	       $(elem).closest('tr').css("background-color", "#f1f1f1");
	    });
	});
}

function modalAsignarGrupoTaller(taller){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_selec_taller/gruposTaller',
	    	data   : { taller : taller,
	    		       hijo   : cons_hijo}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.count > 0){
	    	   cons_grupo = null;
	    	   $("#con_tb_grupos_taller").html(data.tbGrupos);
	    	   $('#tbGrupos').bootstrapTable({});
	    	   componentHandler.upgradeAllRegistered();
	    	   tableEvents("tbGrupos");
	    	   $("#btnAsignarGrupo").prop("disabled", true);
	    	   modal("modalAsignarGrupoTaller");
	       }else{
	    	   msj("success", "Este taller no tiene grupos creados", null);
	       }
	    });
	});
}

cons_grupo = null;
cons_nombre_grupo = null;
function radioCheck(id){
	rb = $("#"+id);
	var jason = JSON.stringify($('#tbGrupos').bootstrapTable('getOptions'));
	var obj = jQuery.parseJSON(jason);
	$.each(obj.data, function(key, value) {
		$.each(value, function(key, value) {
			if (key == 4) {
				rbp = $("#"+$(value).find("input").attr("id"));
				var radioButRep = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="'+rbp.attr("id")+'">'+
								        '<input type="radio" id="'+rbp.attr("id")+'" data-nombre-grupo="'+rbp.attr("data-nombre-grupo")+'" data-id-grupo="'+rbp.attr("data-id-grupo")+'" class="mdl-radio__button recto" name="'+rbp.attr("name")+'" onchange="radioCheck(\''+rbp.attr("id")+'\')">'+
								  '</label>';;
				$('#tbGrupos').bootstrapTable('updateCell',{
					rowIndex   : rbp.closest('tr').attr('data-index'),
					fieldName  : 4,
					fieldValue : radioButRep
				});
			}
		});
	});
	var radioBut = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="'+rb.attr("id")+'">'+
		               '<input type="radio" id="'+rb.attr("id")+'" data-nombre-grupo="'+rb.attr("data-nombre-grupo")+'" data-id-grupo="'+rb.attr("data-id-grupo")+'" class="mdl-radio__button recto" name="'+rb.attr("name")+'" onchange="radioCheck(\''+rb.attr("id")+'\')" checked>'+
                   '</label>';
	$('#tbGrupos').bootstrapTable('updateCell',{
		rowIndex   : rb.closest('tr').attr('data-index'),
		fieldName  : 4,
		fieldValue : radioBut
	});
	componentHandler.upgradeAllRegistered();
	cons_grupo        = rb.attr("data-id-grupo");
	cons_nombre_grupo = rb.attr("data-nombre-grupo");
	$("#btnAsignarGrupo").prop("disabled", false);
}

function asginarEstudianteGrupo(){
	addLoadingButton('btnConfirmAsignarGrupo');
	Pace.restart();
	Pace.track(function() {
		if(cons_grupo != null){
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_selec_taller/asignarEstudianteTaller',
		    	data   : { grupo : cons_grupo,
		    		       hijo  : cons_hijo}
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       if(data.error == 0){
		    	   if(data.count > 0){
			    	   $("#cont_select_empty_taller").css("display", "none");
			    	   $("#contTalleresHijo").html(data.tbTalleres);
			    	   $('#tbTalleres').bootstrapTable({});
			    	   $("#contTbHijos").html(data.hijos);
			    	   $('#tbHijos').bootstrapTable({});
			       }else{
			    	   $("#cont_select_empty_taller").css("display", "block");
			       }
		    	   modal("modalConfirmAsignar");
		    	   modal("modalAsignarGrupoTaller");
		       }
		       stopLoadingButton('btnConfirmAsignarGrupo');
		       msj("success", data.msj, null);
		    });
		}
	});
}

function abrirModalConfirmar(){
	$("#titleConfirmAsignar").text("Seguro de asignar a "+cons_nombre_hijo+" al grupo "+cons_nombre_grupo+" ?");
	modal("modalConfirmAsignar");
}

var cons_tipo_cambio = null;
var cons_taller_antiguo_cambio = null;
function abrirModalChangeTallerGrupo(idTaller, tipo){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_selec_taller/modalChangeTallerGrupo',
	    	data   : { hijo   : cons_hijo,
	    		       taller : idTaller,
	    		       tipo   : tipo}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       setCombo("selectTallerChange", data.comboTalleres, "Taller cambio");
	       setearCombo("selectTallerChange", idTaller);
	       $("#cont_tb_taller_change").html(data.tbGrupos);
	       $('#tbGruposChange').bootstrapTable({});
	       tableEvents("tbGruposChange");
	       componentHandler.upgradeAllRegistered();
	       //$("#btnChangeTallerGrupo").html(data.nombreButton);
	   	   $("#btnChangeTallerGrupo").prop("disabled", true);
	   	   if(data.motivo == 1){
	   		   $("#cont_motivo_cambio").css("display", "block");
	   	   }
	   	   setearInput("motivoCambio", null);
	       cons_tipo_cambio           = tipo;
	       cons_taller_antiguo_cambio = idTaller;
	       cons_grupo                 = data.grupoSelec;
    	   modal("modalChangeTallerGrupo");
	    });
	});
}

function getGruposByTallerChange(){
	var taller = $("#selectTallerChange").val();
	if(taller.length != 0){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_selec_taller/gruposTallerChange',
		    	data   : { hijo   : cons_hijo,
		    		       taller : taller}
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       $("#btnChangeTallerGrupo").prop("disabled", true);
		       if(data.count != 0){
		    	   $("#cont_tb_taller_change").html(data.tbGrupos);
		    	   $('#tbGruposChange').bootstrapTable({});
		    	   tableEvents("tbGruposChange");
		    	   componentHandler.upgradeAllRegistered();
		       }else{
		    	   $("#cont_tb_taller_change").html(null);
		    	   //AGREGAR UN MENSAJE DE QUE NO HAY GRUPOS EN EL TALLER SELECCIONADO
		       }
		    });
		});
	}else{
		$("#cont_tb_taller_change").html(null);
		$("#btnChangeTallerGrupo").prop("disabled", true);
		cons_grupo = null;
	}
}

function radioCheckCambio(id){	
	rb = $("#"+id);
	var jason = JSON.stringify($('#tbGruposChange').bootstrapTable('getOptions'));
	var obj = jQuery.parseJSON(jason);
	$.each(obj.data, function(key, value) {
		$.each(value, function(key, value) {
			if (key == 4) {
				rbp = $("#"+$(value).find("input").attr("id"));
				var radioButRep = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="'+rbp.attr("id")+'">'+
								        '<input type="radio" id="'+rbp.attr("id")+'" data-id-grupo="'+rbp.attr("data-id-grupo")+'" class="mdl-radio__button recto" name="'+rbp.attr("name")+'" onchange="radioCheckCambio(\''+rbp.attr("id")+'\')">'+
								  '</label>';
				$('#tbGruposChange').bootstrapTable('updateCell',{
					rowIndex   : rbp.closest('tr').attr('data-index'),
					fieldName  : 4,
					fieldValue : radioButRep
				});
			}
		});
	});
	
	var radioBut = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="'+rb.attr("id")+'">'+
		               '<input type="radio" id="'+rb.attr("id")+'" data-nombre-grupo="'+rb.attr("data-nombre-grupo")+'" data-id-grupo="'+rb.attr("data-id-grupo")+'" class="mdl-radio__button recto" name="'+rb.attr("name")+'" onchange="radioCheckCambio(\''+rb.attr("id")+'\')" checked>'+
                   '</label>';
	$('#tbGruposChange').bootstrapTable('updateCell',{
		rowIndex   : rb.closest('tr').attr('data-index'),
		fieldName  : 4,
		fieldValue : radioBut
	});
	componentHandler.upgradeAllRegistered();
	cons_grupo = rb.attr("data-id-grupo");
	$("#btnChangeTallerGrupo").prop("disabled", false);
}

function solicitarRealizarCambioTallerGrupo(){
	var taller = $("#selectTallerChange").val();
	if(cons_tipo_cambio != null && cons_grupo != null && taller != null){
		addLoadingButton('btnChangeTallerGrupo');
		Pace.restart();
		Pace.track(function() {
			motivo = $("#motivoCambio").val();
			if(motivo.length != 0){
				$.ajax({
			    	type   : 'POST',
			    	'url'  : 'C_selec_taller/solicitarRealizarCambio',
			    	data   : { hijo   		 : cons_hijo,
			    		       taller 		 : taller,
			    		       tallerAntiguo : cons_taller_antiguo_cambio,
			    		       grupo         : cons_grupo,
			    		       tipo   	     : cons_tipo_cambio,
			    		       motivo        : motivo}
				})
			    .done(function(data) {
			       data = JSON.parse(data);
			       if(data.error == 0){
			    	   if(data.count > 0){
				    	   $("#cont_select_empty_taller").css("display", "none");
				    	   $("#contTalleresHijo").html(data.tbTalleres);
				    	   $('#tbTalleres').bootstrapTable({});
				    	   $("#contTbHijos").html(data.hijos);
				    	   $('#tbHijos').bootstrapTable({});
				       }else{
				    	   $("#cont_select_empty_taller").css("display", "block");
				       }
			    	   $(document).ready(function(){
			       	       $('[data-toggle="tooltip"]').tooltip();
			           });
			    	   modal("modalChangeTallerGrupo");
			       }
			       stopLoadingButton('btnChangeTallerGrupo');
			       msj("success", data.msj, null);
			    });
			}else{
				stopLoadingButton('btnChangeTallerGrupo');
				msj("success", "ingrese un motivo", null);
			}
		});
	}
}

function tableEvents(idTablaContenedora){
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