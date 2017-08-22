function initConfigValoresGraficos() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_lineas_estrategicas').bootstrapTable({ });
	generarBotonMenuLineas();
	initButtonLoad('btnGrupEduc');
}

function getObjetivos(){
	$("#tb_lineas_estrategicas tr td").click(function() {
		var idLinea = $(this).parent().find('input:text').attr('attr-idLinea');
		getTableObjetivos(idLinea);
		generarBotonMenuObjetivos();
	});
}

function getTableObjetivos(idLinea){
	$.ajax({
		url: "c_config_valor_graf/getTableObjetivosByLinea",
        data: { idLinea   : idLinea},
        async : false,
        type: 'POST'
	}).done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbObjetivos').hide().html(data.tablaObjetivos).fadeIn(1500);
				$('#tb_objetivos').bootstrapTable({ });
				$("#btn-save-valores_objetivos").css("display", "inline-block");
				$("#cont_select_empty_objetivos").css("display", "none");
			};
		}
	});
}

function generarBotonMenuLineas(){
	var div = $('#contTbLineas .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="btn btn-default-light" onclick="grabarValoresLinea()" id="btnGuardarTabla" style="margin-right: 11px;margin-top: -2px;">'+
			        '<span class="md md-save" style="font-size:18px;margin-right:-17px"></span>'+
			   '</button>'+
			   '<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="btn btn-default-light" data-toggle="dropdown">'+
			             '<span class="md md-more-vert" style="font-size:18px;margin-right:-17px"></span>'+
			        '</button>'+
			        '<ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu" style="background-color:#fafafa">'+
			            '<li><a href="#"><i class="md md-print" style="margin-right:10px"></i>Imprimir</a></li>'+
			            '<li><a href="#"><i class="md md-file-download" style="margin-right:10px"></i>Descargar</a></li>'+
			        '</ul>'+
			    '</div>');
}

function generarBotonMenuObjetivos(){
	var div = $('#contTbObjetivos .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="btn btn-default-light" onclick="grabarValoresObjetivo()" id="btnGuardarTabla" style="margin-right: 11px;margin-top: -2px;">'+
			        '<span class="md md-save" style="font-size:18px;margin-right:-17px"></span>'+
			   '</button>'+
			   '<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="btn btn-default-light" data-toggle="dropdown">'+
			             '<span class="md md-more-vert" style="font-size:18px;margin-right:-17px"></span>'+
			        '</button>'+
			        '<ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu" style="background-color:#fafafa">'+
			            '<li><a href="#"><i class="md md-print" style="margin-right:10px"></i>Imprimir</a></li>'+
			            '<li><a href="#"><i class="md md-file-download" style="margin-right:10px"></i>Descargar</a></li>'+
			        '</ul>'+
			    '</div>');
}

function onChangeFlgAmarilloLinea(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellFlgAmarilloLinea("tb_lineas_estrategicas", index, 2, input.value, input.id, input.getAttribute('attr-idlinea'),input.getAttribute('attr-bd'));
}

function updateCellFlgAmarilloLinea(idTabla, indexRow, indexCampo, nuevoValor, id,idLinea,bd) {
	var idx = parseInt(indexRow) + 1;
	var cambio = false;
	 if(nuevoValor == bd) {
		cambio = false;	
	} else  {
		cambio = true;
	}
	
	var newInput = '<input type="text" onchange="onChangeFlgAmarilloLinea(this);" class="form-control" value="'+nuevoValor+'" id="'+id+'"  attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idlinea="'+idLinea+'">';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newInput
	});
	
	var valVerdeL = $('#flg_verdeLE'+idx).attr('attr-valorVerdeL');
	var bdVerde   = $('#flg_verdeLE'+idx).attr('attr-bd');
	var camVerde  = $('#flg_verdeLE'+idx).attr('attr-cambio');
	
	var demas = false;
	if(cambio == 'true' || camVerde=='true') {
		demas = true;
	}
    var foco = (demas == false) ? cambio : true;
	var newInputVerde = '<input type="text" onchange="onChangeFlgVerdeLinea(this);" class="form-control" value="'+valVerdeL+'" id="flg_verdeLE'+idx+'"  attr-bd="'+bdVerde+'"' +
						'attr-valorVerdeL="'+valVerdeL+'" attr-focoL="'+foco+'" attr-valorAmarilloL="'+nuevoValor+'" attr-cambio="'+camVerde+'" attr-idlinea="'+idLinea+'">';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 3,
		fieldValue : newInputVerde
	});
	getObjetivos();
}

function onChangeFlgVerdeLinea(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellFlgVerdeLinea("tb_lineas_estrategicas", index, 3, input.value, input.id, input.getAttribute('attr-idlinea'),input.getAttribute('attr-bd'),input.getAttribute('attr-valorAmarilloL'));
}

function updateCellFlgVerdeLinea(idTabla, indexRow, indexCampo, nuevoValor, id,idLinea,bd,valorLineaAmarillo) {

	var cambio = false;
	 if(nuevoValor == bd) {
		cambio = false;	
	} else  {
		cambio = true;
	}
    var idx = parseInt(indexRow) + 1;
	var cambioAmarillo = $('#flg_amarilloLE'+idx).attr('attr-cambio');
	var demas = false;
	if(cambioAmarillo == 'true') {
		demas = true;
	}
	
	var foco = (demas == false) ? cambio : true;
	var newInput = '<input type="text" onchange="onChangeFlgVerdeLinea(this);" class="form-control" value="'+nuevoValor+'" id="'+id+'"  attr-bd="'+bd+'"' +
					'attr-valorVerdeL="'+nuevoValor+'" attr-focol="'+foco+'" attr-valorAmarilloL="'+valorLineaAmarillo+'" attr-cambio="'+cambio+'" attr-idlinea="'+idLinea+'">';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 3,
		fieldValue : newInput
	});
	getObjetivos();
}

function grabarValoresLinea(){
	initSavingButton('btn-save-valores_linea');
	var json = {};
	var valoresL = [];
	var j = 0;
	var h = 0;
	json.valoresArray = valoresL;
	var arryDivs = getCheckedFromTablaByAttrFOCOL('tb_lineas_estrategicas', 3);//console.log(arryDivs);	
	$.each( arryDivs, function( key, value ) {
		var id_linea 	  = $(value).attr('attr-idlinea');
		var valAmarillo	  = $(value).attr('attr-valorAmarilloL');
		var valVerde      = $(value).attr('attr-valorVerdeL');
		var data  	      = {"id_linea"    : id_linea,
							 "valAmarillo" : valAmarillo,
							 "valVerde"    : valVerde};	
		var valueA=$.trim(valAmarillo);
		var valueV=$.trim(valVerde);
 			if($.isNumeric(valAmarillo) && valueA.length != 0 && valAmarillo > 0 && $.isNumeric(valVerde) && valueV.length != 0 && valVerde >0 && valAmarillo < valVerde){
 				json.valoresArray.push(data);
 				h++;
 	 		}else{
 	 			j++;
 	 		}
	});
	
	var jsonStringPorcentaje = JSON.stringify(json);
	
	$.ajax({
		type : 'POST',
		url : 'c_config_valor_graf/grabarValoresLineaEstrategica',
		data : {valoresJSON : jsonStringPorcentaje},			    
		async : true,
		 type: 'POST'
	})  	
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbLineas').html(data.tablaLineasEstrategicas);
				$('#tb_lineas_estrategicas').bootstrapTable({ });
				getObjetivos();
				generarBotonMenuLineas();
				if(h>0){
					stopSavingButton('btn-save-valores_linea');
					mostrarNotificacion('success', data.msj , null);
				}
				if(j>0){
					stopSavingButton('btn-save-valores_linea');
					//mostrarNotificacion('warning', 'Tiene ' + j + ' registro no editado '+ data.msj, data.cabecera);	
				}
				stopSavingButton('btn-save-valores_linea');
			}
		}
	});
}




//Grabar objetivos

function onChangeFlgAmarilloObjetivo(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellFlgAmarilloObjetivo("tb_objetivos", index, 2, input.value, input.id, input.getAttribute('attr-idobjetivo'),input.getAttribute('attr-bd'),input.getAttribute('attr-valorAmarilloO'));
}

function updateCellFlgAmarilloObjetivo(idTabla, indexRow, indexCampo, nuevoValor, id,idObjetivo,bd) {

	var cambio = false;
	 if(nuevoValor == bd) {
		cambio = false;	
	} else  {
		cambio = true;
	}
	
	var newInput = '<input type="text" onchange="onChangeFlgAmarilloObjetivo(this);" class="form-control" value="'+nuevoValor+'" id="'+id+'"  attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idobjetivo="'+idObjetivo+'">';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newInput
	});
	var idx = parseInt(indexRow) + 1;
	var valVerdeO = $('#flg_verdeO'+idx).attr('attr-valorVerdeO');
	var bdVerde   = $('#flg_verdeO'+idx).attr('attr-bd');
	var camVerde  = $('#flg_verdeO'+idx).attr('attr-cambio');
	
	var demas = false;
	if(cambio == 'true' || camVerde=='true') {
		demas = true;
	}
    var foco = (demas == false) ? cambio : true;
    var newInputVerde  = '<input type="text" onchange="onChangeFlgVerdeObjetivo(this);" class="form-control" value="'+valVerdeO+'" id="flg_verdeO'+idx+'"  attr-bd="'+bdVerde+'"' +
	'attr-valorVerdeO="'+valVerdeO+'" attr-focoO="'+foco+'" attr-valorAmarilloO="'+nuevoValor+'" attr-cambio="'+camVerde+'" attr-idobjetivo="'+idObjetivo+'">';
    
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 3,
		fieldValue : newInputVerde
	});
}

function onChangeFlgVerdeObjetivo(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellFlgVerdeObjetivo("tb_objetivos", index, 3, input.value, input.id, input.getAttribute('attr-idobjetivo'),input.getAttribute('attr-bd'),input.getAttribute('attr-valorAmarilloO'));
}

function updateCellFlgVerdeObjetivo(idTabla, indexRow, indexCampo, nuevoValor, id,idObjetivo,bd,valorLineaAmarillo) {

	var cambio = false;
	 if(nuevoValor == bd) {
		cambio = false;	
	} else  {
		cambio = true;
	}
	 
	var idx = parseInt(indexRow) + 1;
	var cambioAmarillo = $('#flg_amarilloO'+idx).attr('attr-cambio');
	var demas = false;
	if(cambioAmarillo == 'true') {
		demas = true;
	}
	var foco = (demas == false) ? cambio : true;
	
	var newInput = '<input type="text" onchange="onChangeFlgVerdeObjetivo(this);" class="form-control" value="'+nuevoValor+'" id="'+id+'"  attr-bd="'+bd+'"' +
					'attr-valorVerdeO="'+nuevoValor+'" attr-focoO="'+foco+'" attr-valorAmarilloO="'+valorLineaAmarillo+'" attr-cambio="'+cambio+'" attr-idobjetivo="'+idObjetivo+'">';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 3,
		fieldValue : newInput
	});
}

function grabarValoresObjetivo(){
	initSavingButton('btn-save-valores_objetivos');
	var json = {};
	var valoresL = [];
	var j = 0;
	var h = 0;
	json.valoresArray = valoresL;
	var arryDivs = getCheckedFromTablaByAttrFOCOO('tb_objetivos', 3);//console.log(arryDivs);
	$.each( arryDivs, function( key, value ) {
		var id_objetivo 	  = $(value).attr('attr-idobjetivo');
		var valAmarillo	  = $(value).attr('attr-valorAmarilloO');
		var valVerde      = $(value).attr('attr-valorVerdeO');
		var data  	      = {"id_objetivo" : id_objetivo,
							 "valAmarillo" : valAmarillo,
							 "valVerde"    : valVerde};	
		var valueA=$.trim(valAmarillo);
		var valueV=$.trim(valVerde);
 			if($.isNumeric(valAmarillo) && valueA.length != 0 && valAmarillo > 0 && $.isNumeric(valVerde) && valueV.length != 0 && valVerde >0 && valAmarillo < valVerde){
 				json.valoresArray.push(data);
 				h++;
 	 		}else{
 	 			j++;
 	 		}
	});
	
	var jsonStringPorcentaje = JSON.stringify(json);
	
	$.ajax({
		type : 'POST',
		url : 'c_config_valor_graf/grabarValoresObjetivos',
		data : {valoresJSON : jsonStringPorcentaje},			    
		async : true,
		 type: 'POST'
	})  	
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTbObjetivos').html(data.tablaObjetivos);
				$('#tb_objetivos').bootstrapTable({ });
				generarBotonMenuObjetivos();
				if(h>0){
					stopSavingButton('btn-save-valores_objetivos');
					mostrarNotificacion('success', data.msj , null);
				}
				if(j>0){
					stopSavingButton('btn-save-valores_objetivos');
					//mostrarNotificacion('warning', 'Tiene ' + j + ' registro no editado', data.cabecera);	
				}
				stopSavingButton('btn-save-valores_objetivos');
			}
		}
	});
}

function grabarValoresGrupoEduc(){
	addLoadingButton('btnGrupEduc');
	var valAmarillo = $('#valorAmarilloGE').val();
	var valVerde    = $('#valorVerdeGE').val();
	var idConfig    = $('#valorVerdeGE').attr('attr-idconfig');
	if(valAmarillo != null || valVerde != null){
		$.ajax({
			type : 'POST',
			url : 'c_config_valor_graf/grabarValoresGrupoEduc',
			data : { valAmarillo : valAmarillo,
				     valVerde    : valVerde   ,
				     idConfig    : idConfig},			    
			async : true,
			type: 'POST'
		})  	
		.done(function(data){
			if(data == ""){
				location.reload();
			} else{
				data = JSON.parse(data);
				mostrarNotificacion('success', data.msj , null);
				stopLoadingButton('btnGrupEduc');
			}
		});		
	}
}

function logOutConfigValores() {
	$.ajax({
		url  : 'c_config_valor_graf/logOutConfigValores', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
	});
}