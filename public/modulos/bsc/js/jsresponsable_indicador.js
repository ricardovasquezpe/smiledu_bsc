var idPersona = null;
var idIndicador = null;
function initResponsableIndicador() {
	$('#tb_persona_x_indicadores').bootstrapTable({ });
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_alumnos').bootstrapTable({ });
	initButtonLoad('btnCIP','botonF');
}

function getObjetivosByLinea() {
	addLoadingButton('botonF');
	Pace.restart();
	Pace.track(function() {
		var idLinea =  $('#selectLinea option:selected').val();
		$.ajax({
			type   : 'POST',
	    	'url'  : 'c_responsable_indicador/comboObjetivos',
	    	data   : {idLinea : idLinea},
	    	'async': true
		})
		.done(function(data) {
			data = JSON.parse(data);
			setCombo('selectObjetivo', data.comboObjetivo, 'Objetivo');
			$('#contTbPersonas').html(null);
			stopLoadingButton('botonF');
			$("#btnAddResponsableIndicador").css("display", "none");
		});
	});
}

function getCategoriaByObjetivo() {
	addLoadingButton('botonF');
	Pace.restart();
	Pace.track(function() {
		var idObjetivo =  $('#selectObjetivo option:selected').val();
		$.ajax({
			type   : 'POST',
	    	'url'  : 'c_responsable_indicador/comboCategorias',
	    	data   : {idObjetivo : idObjetivo},
	    	'async': true
		})
		.done(function(data) {
			data = JSON.parse(data);
			setCombo('selectCategoria', data.comboCategoria, 'Categoria');
			$('#contTbPersonas').html(null);
			stopLoadingButton('botonF');
			$("#btnAddResponsableIndicador").css("display", "none");
		});
	});
}

function getIndicadoresByCategoria() {
	addLoadingButton('botonF');
	Pace.restart();
	Pace.track(function() {
		var idCategoria = $('#selectCategoria option:selected').val();
		$("#titleResponsables").html("Responsables - "+$('#selectCategoria option:selected').text());
		$.ajax({
			type 	: 'POST',
			'url' 	: 'c_responsable_indicador/comboIndicadores',
			data	: {idCategoria : idCategoria},
			'async' : true
		})
		.done(function(data) {
			data = JSON.parse(data);
			setCombo('selectIndicador', data.comboIndicador, 'Indicador');
			$('#contTbPersonas').html(null);
			stopLoadingButton('botonF');
			$("#btnAddResponsableIndicador").css("display", "none");
		});
	});
}

function getPersonasByIndicador(){
	addLoadingButton('botonF');
	Pace.restart();
	Pace.track(function() {
		var idIndicador = $('#selectIndicador option:selected').val();	
		indicadorSeleccionado = idIndicador;
		$.ajax({
			type	: 'POST',
			'url'	: 'c_responsable_indicador/tablePersonasByIndicador',
			data	: {idIndicador : idIndicador},
			'async' : true
		})
		.done(function(data){	
			data = JSON.parse(data);
			$('#contTbPersonas').html(data.tablaPersonas);
			$('#tb_persona_x_indicadores').bootstrapTable({});
			stopLoadingButton('botonF');
			$("#btnAddResponsableIndicador").css("display", "inline-block");
		});
	});
}

function getPersonasByIndicadorCod(){
	Pace.restart();
	Pace.track(function() {
		var idIndicador = $('#selectIndicadorCod option:selected').val();	
		$("#titleResponsables").html("Responsables - "+$('#selectIndicadorCod option:selected').text());
		indicadorSeleccionado = idIndicador;
		$.ajax({
			type	: 'POST',
			'url'	: 'c_responsable_indicador/tablePersonasByIndicador',
			data	: {idIndicador : idIndicador},
			'async' : false
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contTbPersonas').html(data.tablaPersonas);
			$('#tb_persona_x_indicadores').bootstrapTable({});
			$("#btnAddResponsableIndicador").css("display", "inline-block");
		});
	});
}

function filtrarPorIndicador(){
	Pace.restart();
	Pace.track(function() {
		var indicador = $('#codNombreFiltroIndicador').val();	
		$.ajax({
			type	: 'POST',
			'url'	: 'c_responsable_indicador/getIndicadoresByNombreCod',
			data	: {indicador : indicador},
			'async' : false
		})
		.done(function(data){
			data = JSON.parse(data);
			setCombo('selectIndicadorCod', data.comboIndicador, 'Indicador');
		});
	});
}
 var indicadorSeleccionado = null;
function abirModalAisgnarPersonas(){
	if(indicadorSeleccionado == null || indicadorSeleccionado == '') {
		mostrarNotificacion('success', 'Seleccione un indicador', null);
		return;
	}
	setearInput("nombrePersona", null);
	$('#contTbPersonasModal').html(null);
	$('#tb_persona_by_nombre').bootstrapTable({});
	abrirCerrarModal('modalAsignaPersonas');
}

function abirModalEliminarResponsable(btn){
	idPersona = btn.getAttribute('attr-idpersona');
	idIndicador = btn.getAttribute('attr-idindicador');
	abrirCerrarModal('modalEliminarResponsable');
}

function eliminarResponsableByIndicador(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url   :	'c_responsable_indicador/deleteResponsableByIndicador',
			data  :	{idPersona   : idPersona,
					 idIndicador : idIndicador},
		    async :  false,
			type  :	'POST'
		})
		.done(function(data){		
			data = JSON.parse(data);
			if(data.error == 0){
				abrirCerrarModal('modalEliminarResponsable');
				$('#contTbPersonas').html(data.tablaPersonas);
				$('#tb_persona_x_indicadores').bootstrapTable({});	
			}
			mostrarNotificacion('success', data.msj , null);
		});
	});
}

function getPersonasAddIndicador(){
	Pace.restart();
	addLoadingButton('btnCIP');
	Pace.track(function() {
		if(indicadorSeleccionado == null || indicadorSeleccionado == '') {
			mostrarNotificacion('success', 'Seleccione un indicador', null);
			return;
		}
		var nombrePersona = $('#nombrePersona').val();
		if(nombrePersona == null){
			stopLoadingButton('btnCIP');
			mostrarNotificacion('success','No ha ingresado ningún nombre',null);
		}
		if(nombrePersona.length >= 3){
			$.ajax({
				type	: 'POST',
				'url'	: 'c_responsable_indicador/tablePersonasAddIndicador',
				data	: {idIndicador   : indicadorSeleccionado,
						   nombrePersona : nombrePersona},
				'async' : true
			})
			.done(function(data){		
				data = JSON.parse(data);
				$('#contTbPersonasModal').html(data.tablePersonasModal);
				$('#tb_persona_by_nombre').bootstrapTable({});
				tableEvents("tb_persona_by_nombre");
				stopLoadingButton('btnCIP');
				componentHandler.upgradeAllRegistered();
			});	
		}
	});		
	componentHandler.upgradeAllRegistered();		
}

function cambioCheckIndicador(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckRol("tb_persona_by_nombre", index, 2, cb.checked, cb.id, cb.getAttribute('attr-idpersona'),
			         cb.getAttribute('attr-bd'), cb.getAttribute('attr-idindicador'));
}

function onChangeCheckRol(idTable, index, column, nuevoValor, id, idPersona, bd, idIndicador){
	var check = "checked";
	if(nuevoValor == false ){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	if(nuevoValor == bdVal){
		cambioCheck = false;
	}else{
		cambioCheck = true;
	}
	 
	var checkIndicador =    '    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'">'+
						    '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckIndicador(this);" '+check+' id="'+id+'" attr-bd="'+bd+'"' +
						    '			attr-cambio="'+cambioCheck+'" attr-idpersona="'+idPersona+'" attr-idindicador="'+idIndicador+'">' +
						    '        <span></span>' +
						    '    </label>';
		
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 3,
		fieldValue : checkIndicador
	});
	componentHandler.upgradeAllRegistered();
}

function capturarIndicadoresPersona(){
	Pace.restart();
	addLoadingButton('btnCIP');
	Pace.track(function() {
		var nombrePersona = $.trim($('#nombrePersona').val());
		var condicion = 0;
		var json = {};
		var personas = [];
		json.persona = personas;
		if(nombrePersona == null || nombrePersona == ""){
			componentHandler.upgradeAllRegistered();
			mostrarNotificacion('success', 'No se ingreso ningun nombre', null);
		} else{
			var arrayData = getCheckedFromTablaByAttr('tb_persona_by_nombre', 3);
			$.each( arrayData, function( key, value ) {
				var idPersona 	= $(value).find(':checkbox').attr('attr-idpersona');
				var idIndicador = $(value).find(':checkbox').attr('attr-idindicador');
				var valor       = $(value).find(':checkbox').is(':checked');
				var persona = {"idPersona" : idPersona, "valor" : valor , "idIndicador" : idIndicador};
		 		json.persona.push(persona);
		 		condicion = 1;
			});
			var jsonStringPersona = JSON.stringify(json);
			var idIndicador   = $('#selectIndicador option:selected').val();
			if(condicion == 0){
				abrirCerrarModal('modalAsignaPersonas');
				mostrarNotificacion('success','No se hicieron cambios',null);
			} else{
				$.ajax({
					type : 'POST',
					url : 'c_responsable_indicador/grabarIndicadoresPersona',
					data : { personas      : jsonStringPersona,
						     nombrePersona : nombrePersona,
						     idIndicador   : idIndicador}, 
					async : true
				})
				.done(function(data){
					data = JSON.parse(data);			
					if(data.error == 1) {
						mostrarNotificacion('success', data.msj , null);
					} else {
						$('#contTbPersonasModal').html(null);
						$('#tb_persona_by_nombre').bootstrapTable({ });
						$('#contTbPersonas').html(data.tablePersonaIndicador);
						$('#tb_persona_x_indicadores').bootstrapTable({ });
						mostrarNotificacion('success', data.msj , null);
						stopLoadingButton('btnCIP');
						abrirCerrarModal('modalAsignaPersonas');
					}
				});
			}
		}
	});
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