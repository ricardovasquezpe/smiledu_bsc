function habilitarComboSedes(){
	addLoadingButton('botonRV');
	var yearSelected = $('#selectYearVerano option:selected').val();
	var disabled     = (yearSelected == null || yearSelected == "") ? true : false;
	(yearSelected == null || yearSelected == "")
	disableEnableCombo('selectSedeVerano', disabled);
	stopLoadingButton('botonRV');
}

function getTiposBySede(){
	addLoadingButton('botonRV');
	var yearSelected = $('#selectYearVerano option:selected').val();
	var sedeSelected = $('#selectSedeVerano option:selected').val();
	$.ajax({
		data  : {yearSelected : yearSelected,
			     sedeSelected : sedeSelected},
		url   : 'c_reporte_verano/getTiposReporteVerano',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		setCombo('selectTipoVerano', data.optCrono, 'Tipo',true);
		stopLoadingButton('botonRV');
	});
}

function getTalleresByCronoTipo(){
	addLoadingButton('botonRV');
	var yearSelected = $('#selectYearVerano option:selected').val();
	var sedeSelected = $('#selectSedeVerano option:selected').val();
	var tipoSelected = $('#selectTipoVerano option:selected').val();
	$.ajax({
		data  : {yearSelected : yearSelected,
			     sedeSelected : sedeSelected,
			     tipoSelected : tipoSelected},
		url   : 'c_reporte_verano/getTalleresByCrono',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		setCombo('selectTallerVerano', data.optTalleres, 'Taller',true);
		stopLoadingButton('botonRV');
	});
}

function getEstudiantesByTaller(){
	addLoadingButton('botonRV');
	var tallerSelected = $('#selectTallerVerano option:selected').val();
	$.ajax({
		data  : {tallerSelected : tallerSelected},
		url   : 'c_reporte_verano/getEstudiantesByTaller',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#tableVerano').html(data.tbEstudiantes);
		$('#cont_filter_empty_verano').css('display','none');
		$('#tablaVera').css('display','block');
		$('#tb_estu_verano').bootstrapTable({ });
		stopLoadingButton('botonRV');
	});
}

function downloadPDFByFiltro(){
	var tallerSelected = $('#selectTallerVerano option:selected').val();
	$('#tallerVeranoPDF').val(tallerSelected);
	$('#formPDFVerano').submit();
}