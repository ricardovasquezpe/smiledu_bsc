cons_pregunta = null;
cons_index    = null;
function init(){
	initButtonLoad('buttonEstado');
	$('#tb_preguntas').bootstrapTable({ });
	initSearchTableNew();
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	tableEvents();
	initButtonLoad('botonI');
}

function selectServicio(pregunta, cont){
	var servicio = $('#selectServicio'+cont+' option:selected').val();
	$.ajax({
		data : {servicio : servicio,
			    pregunta : pregunta},
		url  : 'c_pregunta/saveServicioPregunta', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			mostrarNotificacion('success',data.msj);
		}else{
			mostrarNotificacion('warning',data.msj);
		}
	});
}

function elegirIndicador(pregunta, indicador, element){
	Pace.restart();
	addLoadingButton('botonI');
	Pace.track(function() {
		var check = 0;
		if($(element).is(':checked')){
			check = 1;
		}
		$.ajax({
			data : {indicador : indicador,
				    pregunta  : pregunta,
				    check     : check},
			url  : 'c_pregunta/saveIndicadoresPregunta', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				stopLoadingButton('botonI');
				mostrarNotificacion('success',data.msj);
			}else{
				stopLoadingButton('botonI');
				mostrarNotificacion('warning',data.msj);
			}
		});
	});
}

function abrirModalEditDescPregunta(pregunta, btn){
	if(pregunta != null){
		$.ajax({
			data : {pregunta : pregunta},
			url  : 'c_pregunta/getDetallePregunta', 
			async: false,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			abrirCerrarModal("modalEditDescripcionPregunta");
			$("#descPregunta").val(data.descPregunta);
			$("#descPregunta").parent().addClass("is-dirty");
			cons_pregunta = pregunta;
			cons_index = $(btn).closest("tr").data("index");
		});
	}
}

function cambiarDescripcionPregunta(){
	addLoadingButton('buttonEstado');
	descripcion = $("#descPregunta").val();
	if(cons_pregunta != null && $.trim(descripcion).length > 0 && $.trim(descripcion).length <= 200){
		$.ajax({
			data : {pregunta    : cons_pregunta,
				    descripcion : descripcion},
			url  : 'c_pregunta/editPregunta', 
			async: false,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				abrirCerrarModal("modalEditDescripcionPregunta");
				mostrarNotificacion('success',data.msj);
				stopLoadingButton('buttonEstado');
				$('#tb_preguntas').bootstrapTable('updateCell',{
					rowIndex   : cons_index,
					fieldName  : 1,
					fieldValue : data.newDesc
				});
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
		    	    $('.pickerButn').selectpicker('mobile');
		    	} else {
		    		$('.pickerButn').selectpicker();
		    	}
			}else{
				stopLoadingButton('buttonEstado');
				mostrarNotificacion('warning',data.msj);
			}
			
		});
	}
}

function tableEvents(){
	$(function () {
	    $('#tb_preguntas').on('all.bs.table', function (e, name, args) {
	    	
	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
	    	    $('.pickerButn').selectpicker('mobile');
	    	} else {
	    		$('.pickerButn').selectpicker();
	    	}
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
	    	
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
	    	    $('.pickerButn').selectpicker('mobile');
	    	} else {
	    		$('.pickerButn').selectpicker();
	    	}
	    })
	    .on('search.bs.table', function (e, text) {
	    	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
	    	    $('.pickerButn').selectpicker('mobile');
	    	} else {
	    		$('.pickerButn').selectpicker();
	    	}
	    });
	});
}

function abrirModalIndicadores(pregunta){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : {pregunta : pregunta},
			url  : 'c_pregunta/getIndicadoresPregunta', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$("#cont_tab_indicadores").html(data.tabla);
			$('#tb_indicadores').bootstrapTable({ });
			$(function () {
			    $('#tb_indicadores').on('all.bs.table', function (e, name, args) {

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
			componentHandler.upgradeAllRegistered();
			modal("modalIndicadoresPregunta");
		});
	});
}