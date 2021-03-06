$('.mdl-layout__tab[href="#tab-compromiso"]').click(function(){
	$('#generarBoletas').css('display','block');
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="crono_cuota">'+
											'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalCronogramaCuota" data-mfb-label="Filtrar Cuota">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-detalle"], .mdl-layout__tab[href="#tab-boleta"]').click(function(){
	$('#generarBoletas').css('display','none');
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
});

function init() {
	$('#tb_correlativos_historial').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}	

	$('<button>').attr({
		'id'   	:	'iconTraslado',
		'class'	:	'mdl-button mdl-js-button mdl-button--icon'
	}).appendTo('#tableCorrelativos .fixed-table-toolbar .columns-right');

	$('<i>').attr({
		'class'	: 'mdi mdi-more_vert'
	}).appendTo('#iconTraslado');
	initSearchTableNew();
	initButtonLoad('botonGC','botonGB');
}

function checkAllBoletas(all) {
	var tbody 		   = $('#tb_boleta tbody tr');
	var checkedGeneral = $('#checkbox-allx').is(':checked');
	$.each(tbody,function(key,value){
		var input = $(value).find('input');
		if(input.attr('id') != undefined){
		addRemoveToArray(input,checkedGeneral);
		}
	});
	if(checkedGeneral == true){
		$('#checkbox-allx').addClass('is-checked');
		$('#checkbox-allx').parent().addClass('is-checked');
	}else{
		$('#checkbox-allx').removeClass('is-checked');
		$('#checkbox-allx').parent().removeClass('is-checked');
	}
}

function addRemoveToArray(cb,flg_general) {
	var checked = null;
	if(flg_general == null){
		checked    = cb.is(':checked');
	} else{
		checked    = flg_general;
	}
	var movimiento = cb.attr('attr-id_movi');
	var row_index  = $(cb).attr('attr-orden');
	var idCheck    = $(cb).attr('id');
	if(checked == true){
		arrayPagar.push(movimiento);
	}else if(checked == false){
		removeFromArray(movimiento);
	}
	updateCell(idCheck,movimiento,row_index,checked,row_index);
}

var arrayPagar = [];
function removeFromArray(idCompromiso) {
	for(var i = 0 ; i < arrayPagar.length ; i++){
		if(arrayPagar[i] == idCompromiso){
			arrayPagar[i] = "00000000";
//			return;
		}
	}
}

function updateCell(id,movimiento,rowIndex,checked,orden) {
	var newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect '+'" for="'+id+'">'+
				       '<input type="checkbox"'+((checked == true) ? ' checked ' : ' ')+ 
					   'attr-id_movi="'+movimiento+'" id="'+id+'" attr-orden="'+orden+'"'+
					   ' onclick="addRemoveToArray($(this),null);assignItemAUX(this.id, \'tb_boleta\', \'cabeConfirmar\');" class="mdl-checkbox__input">'+
				   '</label>';
	$('#tb_boleta').bootstrapTable('updateCell',{
		rowIndex   : rowIndex,
		fieldName  : 0,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function listarBoleta() {
	$('#tableBoleta').html(null);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {},
			url   : 'c_boleta/listarBoletas',
			type  : 'POST',
			async : false
		})
		.done(function(data){
			data = JSON.parse(data);
			setTimeout(function(){
				$('#menu').css('display','none');
			},200);
			$('#botonImprimir').html(data.imprimir);
			$('#tableBoleta').html(data.tableBoletas);
			componentHandler.upgradeAllRegistered();
			$('#tb_boleta').bootstrapTable({ });
			initSearchTable();
			tableEventsBoletasPrint();
//			tableEventsUpgradeMdlComponentsMDL('tb_boleta');
		});
	});
}

function blockPrint() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {arrayPagar  	    : arrayPagar},
			url   : 'c_boleta/imprimirBoleta',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			arrayPagar    = [];
			data = JSON.parse(data);
			$('#cabeConfirmar').css('display','none');
			$('#tableBoleta').html(data.tableBoletas);
			componentHandler.upgradeAllRegistered();
			$('#tb_boleta').bootstrapTable({ });
			sendToPreview(JSON.parse(data.arrays));
		});
	});
}

function sendToPreview(data) {
	var doc = new jsPDF('l', 'mm', [110, 160]);
    var chartHeight = 10;
    var currentdate = new Date(); 
    var datetime =    currentdate.getDate() + "-"
                    + (currentdate.getMonth()+1)  + "-" 
                    + currentdate.getFullYear() + " "  
                    + currentdate.getHours() + ":"  
                    + currentdate.getMinutes() + ":" 
                    + currentdate.getSeconds();
    doc.setTextColor(50,50,50);
    // x | y nro_documento
    for (var int = 0; int < data.length; int++) {
        doc.setFontSize(6);
        data[int]=JSON.parse(data[int]);
        doc.setFontSize(10);
        doc.text(21 , 33, data[int].nombrecompleto);
        
        doc.setFontSize(9);
        doc.text(21 , 38, data[int].ubicacion);
        
        doc.text(126, 29, data[int].nro_documento);
        doc.setFontSize(9);
        
        doc.text(126, 33, data[int].fecha);
        
        doc.setFontSize(10);
        doc.text(52 , 55, data[int].cuota);
        doc.text(140, 55, data[int].monto);
        if(data[int].flg_descuento == '1'){
        	doc.setFontSize(10);
            doc.text(52, 60, 'Descuento Pronto Pago');
            doc.text(142, 60, data[int].descuento);
        }
        if(Number(data[int].mora) != 0){
        	doc.setFontSize(8);
            doc.text(60, 65, 'Mora');
            doc.setFontSize(6);
            doc.text(100, 65, 'mora :' + data[int].mora);
        }
        
        doc.text(140, 105, data[int].total);
        doc.setFontSize(7);
        doc.text(26, 96, 'F.P:'+data[int].info_pago);
        if(int < data.length-1){
        	doc.addPage('500','500');
    	}
	}
        doc.output('save', ''+datetime+'.pdf');
}

function listarCompromisos() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {},
			url   : 'c_boleta/listarCompromisos',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			setTimeout(function(){
				$('#menu').css('display','none');
			},200);
			$('#generar').html(data.generar);
			$('#tableCompromisos').html(data.tablaCompromisos);
			componentHandler.upgradeAllRegistered();
			$('#tb_compromiso').bootstrapTable({ });
			initSearchTable();
			tableEventsUpgradeMdlComponentsMDL('tb_compromiso');
		});
	});
}

function generarBoletas() {
	addLoadingButton('botonGB');
	var idCuota       = $('#selectCuota option:selected').val();
	var flg_tipo 	  = $('input[name=radioBoletas]:checked').val();
	var fecha_emision = $('#fecha_boletas').val();
	var fechaInicio   = $('#fecInicioBol').val();
	var fechaFin      = $('#fecFinBol').val();
	if(fecha_emision == null){
		msj('warning','Ingresa una fecha');
		stopLoadingButton('botonGB');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {idCuota  	   : idCuota,
				     flg_tipo 	   : flg_tipo,
				     fecha_emision : fecha_emision,
		        	 fecInicio     : fechaInicio,
		        	 fecFin        : fechaFin},
			url   : 'c_boleta/generarBoletas',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				abrirCerrarModal('modalGenerarBoletas');
			}
			msj('warning',data.msj);
			$('#tableCompromisos').html(data.tablaCompromisos);
			componentHandler.upgradeAllRegistered();
			$('#tb_compromiso').bootstrapTable({ });
			initSearchTable();
			tableEventsUpgradeMdlComponentsMDL('tb_compromiso');
			stopLoadingButton('botonGB');
		});
	});
}

function openModalGenerar(firstCorre, lastCorre) {
	setearInput('primeraBoleta', firstCorre);
	setearInput('ultimaBoleta', lastCorre);
	abrirCerrarModal('modalGenerarBoletas');
}

function getCuotaByCronograma(){
	addLoadingButton('botonGC');
	var idCronograma =  $('#selectCronograma option:selected').val();
	$.ajax({
		url : "c_boleta/comboCronogramaCuota",
        data: {idCronograma : idCronograma},
        async: true,
        type: 'POST'
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
		    setCombo('selectCuota' , data.optCuotas, 'Cuota',null);
		}else if(data.error == 1) {
			setCombo('selectCuota' , data.optCuotas, 'Cuota',null);
		}
		stopLoadingButton('botonGC');
	});
}
var flg_tipo = '0';
function getCompromisosByCuota(){
	addLoadingButton('botonGC');
	var idCuota     = $('#selectCuota option:selected').val();
	var fechaInicio = $('#fecInicioBol').val();
	var fechaFin    = $('#fecFinBol').val();
//	var flg_tipo = $('input[name=radioBoletas]:checked').val();
	if(flg_tipo == null){
		msj('warning','Selecciona una opci&oacute;n');
		stopLoadingButton('botonGC');
		return;
	}
	$.ajax({
        data: {idCuota   : idCuota,
        	   flg_tipo  : flg_tipo,
        	   fecInicio : fechaInicio,
        	   fecFin    : fechaFin},
        url : "c_boleta/getCompromisosByCuota",
        async: true,
        type: 'POST'
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 1){
			msj('warning',data.msj);
		}
		$('#generar').html(data.generar);
		$('#tableCompromisos').html(data.tablaCompromisos);
		$('#tableCompromisos').parent().parent().css('display','block');
		$('#cont_img_search_alum').css('display','none');
		componentHandler.upgradeAllRegistered();
		$('#tb_compromisos').bootstrapTable({ });
		initSearchTable();
		tableEventsUpgradeMdlComponentsMDL('tb_compromiso');
		stopLoadingButton('botonGC');
	});
}

function tableEventsBoletasPrint(){
	$(function () {
	    $('#tb_boleta').on('all.bs.table', function (e, name, args) {

	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    	
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

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

	    })
	    .on('load-error.bs.table', function (e, status) {

	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	$('#checkbox-allx').removeAttr('checked');
	    	$('#checkbox-allx').removeClass('is-checked');
			$('#checkbox-allx').parent().removeClass('is-checked');
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function changeFlgBoletas(flg_tipo_cambio){
	flg_tipo = flg_tipo_cambio;
}