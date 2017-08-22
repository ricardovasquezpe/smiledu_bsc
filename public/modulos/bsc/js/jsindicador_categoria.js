function initIndicadorCategoria(){
	$('#tb_indicador_categoria').bootstrapTable({ });
	initSearchTableNew();
}

function abrirModalCategorias(idIndicador){
	$.ajax({
		type	: 'POST',
		'url'	: 'c_indicador_categoria/getCategoriasByIndicador',
		data	: {idIndicador : idIndicador},
		'async' : false
	})
	.done(function(data){
		data = JSON.parse(data);
		abrirCerrarModal('modalCategorias');
		$("#contTbCategorias").html(data.tabla);
		$('#tb_categorias').bootstrapTable({ });
		componentHandler.upgradeAllRegistered();
		tableEvents("tb_categorias");
	});
}

function cambioCheckCategoria(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckCategoria("tb_categorias", index, 2, cb.checked, cb.id, cb.getAttribute('attr-idindicador'),
			         cb.getAttribute('attr-bd'), cb.getAttribute('attr-idcategoria'),cb.getAttribute('attr-idobjetivo'));
}

function onChangeCheckCategoria(idTable, index, column, nuevoValor, id, idIndicador, bd, idCategoria, idObjetivo){
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
	
	var checkCategoria =    ' '+
						    '    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'">'+
						    '        <input type="checkbox" onclick="cambioCheckCategoria(this);" '+check+' id="'+id+'" attr-bd="'+bd+'"' +
						    '			attr-cambio="'+cambioCheck+'" attr-idobjetivo="'+idObjetivo+'" attr-idindicador="'+idIndicador+'" attr-idcategoria="'+idCategoria+'" class="mdl-checkbox__input">' +
						    '        <span class="mdl-checkbox__label"></span>' +
						    '    </label>' +
						    '';
		
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 2,
		fieldValue : checkCategoria
	});
	componentHandler.upgradeAllRegistered();
}

function capturarCategoriaIndicador(){
	var condicion = 0;
	var json = {};
	var categorias = [];
	json.categoria = categorias;
		var arrayData = getCheckedFromTablaByAttr('tb_categorias', 2);
		$.each( arrayData, function( key, value ) {
			var idCategoria 	= $(value).find(':checkbox').attr('attr-idcategoria');
			var idIndicador = $(value).find(':checkbox').attr('attr-idindicador');
			var idObjetivo  = $(value).find(':checkbox').attr('attr-idobjetivo');
			var valor       = $(value).find(':checkbox').is(':checked');
			var categoria = {"idCategoria" : idCategoria, "valor" : valor , "idIndicador" : idIndicador, "idObjetivo" : idObjetivo};
	 		json.categoria.push(categoria);
	 		condicion = 1;
		});
		var jsonStringCategoria = JSON.stringify(json);
		
		if(condicion == 0){
			abrirCerrarModal('modalCategorias');
			mostrarNotificacion('success','No se hicieron cambios',null);
		} else{
			$.ajax({
				type : 'POST',
				url : 'c_indicador_categoria/grabarCategoriaIndicador',
				data : { categorias : jsonStringCategoria}, 
				async : false
			})
			.done(function(data){
				if(data == ""){
					location.reload();
				} else{
					data = JSON.parse(data);			
						if(data.error == 1) {
							mostrarNotificacion('success', data.msj , null);
						} else {
							mostrarNotificacion('success', data.msj , null);
							$("#contTabCategoria").html(data.tableCategoriaIndicador);
							$('#tb_indicador_categoria').bootstrapTable({ });
							abrirCerrarModal('modalCategorias');
						}
				}
			});
		}
}

function tableEvents(idTabla){
	$(function () {
	    $('#'+idTabla).on('all.bs.table', function (e, name, args) {

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
}