var arrayPagar    = [];
var arrayPagarAux = [];
var montoPagar    = null;
var montoPagarAux = null;
var parpadeo      = null;
var currentdate = new Date(); 
var datetime =  currentdate.getDate() + "_"
                + (currentdate.getMonth()+1)  + "_" 
                + currentdate.getFullYear() + "_"  
                + currentdate.getHours() + "_"  
                + currentdate.getMinutes() + "_" 
                + currentdate.getSeconds();

function init(){
	initButtonLoad('botonRC','botonRP','botonGB','botonAB','botonAC','botonACT');
	initLimitInputs('observacionAnular');
	mostrarChips();
}

function registrarPago(data){
	if(data.error == 1){
		stopLoadingButton('botonRP');
		mostrarNotificacion('warning',data.msj);
	} else{
		mostrarNotificacion('success',data.msj);
		abrirCerrarModal('modalConfirmar');
		$('#checkVisa').parent().removeClass('is-checked');
		$('#checkVisa').attr('checked',null);
		$('#checkAdelanto').parent().removeClass('is-checked');
		$('#checkAdelanto').attr('checked',null);
		$('#checkAdelantoCont').css('display','block');
		$('#montoAdelantoCont').css('display','none');
		$('#monto_adelanto').val("");
		arrayPagar = [];
		initTableComponents(data,currentTabl);
		setTimeout(function(){
			createReciboByCompromisos(JSON.parse(data.datosRecibos));
		},700);
	}
//	cambiarColorProgreso();	
}

var idCompromisoSel = null;
var flg_elimina = null;
function openModalRegistrarPago(idCompromiso,flg,monto){
	$('#montoDesc').css('display','none');
	if(flg == true){
		montoPagarAux = montoPagar; 
		montoPagar    = parseFloat(monto);
		arrayPagarAux = arrayPagar;
		arrayPagar = [];
		arrayPagar.push(idCompromiso);
		idCompromisoSel = idCompromiso;
		$('#checkAdelantoCont').css('display','block');
		$('#titleConfirmar').text('Pagar 1 cuota');
	} else{
		idCompromisoSel = null;
		$('#titleConfirmar').text('Realizar '+arrayPagar.length+' pagos');
		if(arrayPagar.length == 1){
			$('#checkAdelantoCont').css('display','block');
		} else{
			$('#montoAdelantoCont').css('display','none');
			$('#checkAdelantoCont').css('display','none');	
			$('#monto_adelanto').val("");
		}
	}
	var montoDescuento = evaluatePromocion(montoPagar);
	if(montoDescuento != null){
		$('#montoDesc').css('display','block');
		$('#montoDesc').text('Monto con descuento S/.'+(montoDescuento.toFixed(2)));
	}
	flg_elimina = flg;
	$('#montoCobrar').text('Monto a cobrar S/.'+(montoPagar.toFixed(2)));
	abrirCerrarModal('modalConfirmar');
}

$('#modalConfirmar').on('hidden.bs.modal', function () {
	removeFromArray(idCompromisoSel);
	if(flg_elimina == true){
		arrayPagar 	    = arrayPagarAux;
		arrayPagarAux   = [];
		idCompromisoSel = null;
		montoPagar      = montoPagarAux;
	    montoPagarAux   = 0;
	}
});

function goToAjax(){
	addLoadingButton('botonRP');
	var data    = null;
	var checkedVisa     = $('#checkVisa').is(':checked');
	var checkedAdelanto = $('#checkAdelanto').is(':checked');
	var monto_adelanto  = $('#monto_adelanto').val();
	$.ajax({
		data  : {arrayPagar  	 : arrayPagar,
				 currentTabl 	 : currentTabl,
				 currentPers 	 : currentPers,
				 checkedVisa 	 : checkedVisa,
				 checkedAdelanto : checkedAdelanto,
				 monto_adelanto  : monto_adelanto},
		url   : 'c_ingresos/registrarIngreso',
		type  : 'POST',
		async : true
	})
	.done(function(dataJS){
		data = JSON.parse(dataJS);
		stopLoadingButton('botonRP');
		registrarPago(data);
	});
	return data;
}

function removeFromArray(idCompromiso){
	for(var i = 0 ; i < arrayPagar.length ; i++){
		if(arrayPagar[i] == idCompromiso){
			arrayPagar.splice(i,1);
			return;
		}
	}
}

function changeContTableNumber(tab,persona,totalCrompromisos,i){
	currentTabl = tab;
	currentPers = persona;
	totalCompromisos = totalCrompromisos;
	arrayPagar = [];
	$.ajax({
		data  : {currentTabl : currentTabl,
				 currentPers : currentPers},
	    url   : 'c_ingresos/getTableByPersona',
	    type  : 'POST',
	    async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		initTableComponents(data,currentTabl);
		$('.mdl-chip').removeClass('active',true);
		$('#chip'+i).addClass('active',true);
	});
}

function addRemoveToArray(cb,flgUse){
	var checked    = cb.is(':checked');
	var idComp     = cb.attr('attr-id_movi');
	var row_index  = $(cb).attr('attr-orden');
	var idCheck    = $(cb).attr('id');
	var movimiento = $(cb).attr('attr-id_movi');
	var monto      = $(cb).attr('attr-monto');
	var crono      = $(cb).attr('attr-crono');
	if(checked == true){
		montoPagar    += parseFloat(monto);
		arrayPagar.push(idComp);
		if(flgUse == true){
			activeDesactiveNextCheck(cb,true,row_index);
		}
	} else{
		montoPagar    = montoPagar - parseFloat(monto);
		if(flgUse == true){
			activeDesactiveNextCheck(cb,false,row_index);
		}
		if(crono == 1){
			repaintChecksByPadre(cb);
		}
		removeFromArray(idComp);
	}
	updateCell(idCheck,movimiento,row_index,checked,row_index,flgUse,false,monto);
}

function initTableComponents(data,currentTabl,row_index){
	montoPagar = 0;
	var tb = 'tb_compromisos'+String(currentTabl);
	$('#contTbCompromisos'+currentTabl).html(data.tbCompromisos);
	$('#'+tb).bootstrapTable({});
	$('#datos'+String(currentTabl)).html(data.datos);
	$('#'+parpadeo).addClass('mdl-parpadea');
	$('#cabeConfirm'+currentTabl).css('display','none');
	componentHandler.upgradeAllRegistered();
	tableEventsUpgradeMdlComponentsMDL(tb);
}

var cbNext = null;
function activeDesactiveNextCheck(cbIndex,flg){
//	var tableData = $('#tb_compromisos'+currentTabl).bootstrapTable('getData');
	var td       = $(cbIndex.closest('tr')).prev();
	var cont = 0;
	while(!td.find('input').is('input') && cont < totalCompromisos){
		td = $(td).prev();
		cont++;
	}
	cbNext 	     = $(td).find('input');
	var flgCrono = $(cbIndex.closest('tr')).prev().find('input').attr('attr-crono');
	if(flgCrono == 0){
		activeDesactiveNextCheck(cbNext,flg);
		return;
	}
	var checked    = cbNext.is(':checked');
	var idComp     = cbNext.attr('attr-id_movi');
	var row_index  = $(cbNext).attr('attr-orden');
	var idCheck    = $(cbNext).attr('id');
	var movimiento = $(cbNext).attr('attr-id_movi');
	var monto	   = $(cbNext).attr('attr-monto');
	if(row_index != undefined){
		if(flg == true){
			updateCell(idCheck,movimiento,row_index,checked,row_index,true,false,monto);
		} else{
			updateCell(idCheck,movimiento,row_index,checked,row_index,true,true,monto);
		}
	}
	cbNext = null;
}

function openModalAnularPago(compromiso,info){
	sessionStorage.idCompromisoAnul = compromiso;
	$('#infoAnular').text(info);
	$('#observacionAnular').val("");
	abrirCerrarModal('modalAnularCompromiso');
}

function anularCompromisoByPersona(){
	addLoadingButton('botonAC');
	var observaciones = $('#observacionAnular').val();
	$.ajax({
		data  : {currentPers   : currentPers,
				 currentTabl   : currentTabl,
			     compromiso    : sessionStorage.idCompromisoAnul,
			     observaciones : observaciones},
		url   : 'c_ingresos/anularCompromiso',
		type  : 'POST',
		async :true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			if(observaciones.length >200) {
				mostrarNotificacion('warning',data.msj);
				stopLoadingButton('botonAC');
			} else{
				abrirCerrarModal('modalAnularCompromiso');
				initTableComponents(data,currentTabl);
				$('#observacionAnular').val(null);
				$('#observacionAnular').parent().removeClass('is-dirty');
				stopLoadingButton('botonAC');
			}
		}
		stopLoadingButton('botonAC');
		mostrarNotificacion('success',data.msj);
	});
}

function abrirModalGenerarBoleta(compromiso){
	sessionStorage.idCompromisoBol = compromiso;
	abrirCerrarModal('modalGenerarBoleta');
}

function generarBoletaByCompromiso(){
	addLoadingButton('botonGB');
	$.ajax({
		data  : {currentPers : currentPers,
				 currentTabl : currentTabl,
			     compromiso  : sessionStorage.idCompromisoBol},
		url   : 'c_ingresos/generarBoleta',
		type  : 'POST',
		async :true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			abrirCerrarModal('modalGenerarBoleta');
			initTableComponents(data,currentTabl);
			sendToPreview(JSON.parse(data.datosBoleta));
			stopLoadingButton('botonGB');
		}
		stopLoadingButton('botonGB');
		mostrarNotificacion('warning',data.msj);
	});
}

function openModalDocumentos(compromiso){
	$.ajax({
		data  : {currentPers : currentPers,
				 currentTabl : currentTabl,
				 compromiso : compromiso},
		url   : 'c_ingresos/getDocumentosByCompromiso',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		abrirCerrarModal('modalVisualizarDocumentos');
		$('#contentDocs').html(data.content);
		componentHandler.upgradeAllRegistered();
		$(document).ready(function(){
    	    $('[data-toggle="tooltip"]').tooltip(); 
        });      
	});
}

function abrirModalConfirmarDoc(compromiso,tipoDoc,nro_doc,i,textInfo){
	$('#modalAnularBoleta .mdl-card__title h2.mdl-card__title-text').text('Eliminar '+$('#tb_documentos tr:NTH-CHILD('+i+') td:FIRST-CHILD').text());
	$('#modalAnularBoleta .mdl-card__actions button.mdl-button--raised').attr("onclick", "eliminarDocumento('"+compromiso+"','"+tipoDoc+"','"+nro_doc+"')");
	$('#infoAnularDoc').text(textInfo);
}

function eliminarDocumento(compromiso,tipoDoc,nro_doc){
	addLoadingButton('botonAB');
	$.ajax({
		data  : {currentPers : currentPers,
			 	 currentTabl : currentTabl,
			 	 compromiso  : compromiso,
			 	 tipoDoc     : tipoDoc,
			 	 nro_doc     : nro_doc},
		url   : 'c_ingresos/anularDocumentoByTipo',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		parpadeo = data.parpadeo;
		if(data.error == 0){
			abrirCerrarModal('modalAnularBoleta');
			$('#contentDocs').html(data.content);
			initTableComponents(data,currentTabl);
		}
		stopLoadingButton('botonAB');
		mostrarNotificacion('success',data.msj);
	});
}

function getMontoReferenciaByConcepto(){
	var monto = $('#selectConcepto').find(':selected').data('monto');
	setearInput('monto', monto);
}

function registrarCompromiso(){
	addLoadingButton('botonRC');
	var concepto = $('#selectConcepto option:selected').val();
	var monto    = $('#monto').val();
	$.ajax({
		data  : {currentPers : currentPers,
		 	 	 currentTabl : currentTabl,
		 	 	 concepto    : concepto,
		 	 	 monto       : monto},
		url   : 'c_ingresos/registrarMovimiento',
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			initTableComponents(data,currentTabl);
			abrirCerrarModal('modalAgregarCompromiso');
			setearCombo('selectConcepto', "");
			setearInput('monto', null);
		}
		stopLoadingButton('botonRC');
		mostrarNotificacion('warning',data.msj);
	});
}

function updateCell(id,movimiento,rowIndex,checked,orden,flgUse,disabled=false,monto){
	var tabla = $('#'+id).attr('attr-tabla');
	var card  = $('#'+id).attr('attr-card');
	var newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect '+((disabled == true) ? 'is-disabled' :  null)+'" for="'+id+'">'+
				    	'<input type="checkbox"  '+((checked == true) ? 'checked' : null)+' attr-monto="'+monto+'"  attr-crono="'+((flgUse == true) ? '1' : '0')+'"  '+((disabled == true) ? 'disabled' :  null)+' attr-id_movi="'+movimiento+'" id="'+id+'" attr-orden="'+orden+'" attr-tabla="'+tabla+'" attr-card="'+card+'"' +
				    	'onclick="addRemoveToArray($(this),'+flgUse+');assignItem(this.id, \''+tabla+'\', \''+card+'\');" class="mdl-checkbox__input">'+
				    '</label>';
	$('#tb_compromisos'+currentTabl).bootstrapTable('updateCell',{
		rowIndex   : rowIndex, 
		fieldName  : 0,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function repaintChecksByPadre(cb){
	var ordenChecked = $(cb).attr('attr-orden');
	var tableData = $('#tb_compromisos'+currentTabl).bootstrapTable('getData');
	$.each(tableData,function(){
		var crono = $(this[0]).find('input:checkbox').attr('attr-crono');
		var id    = $(this[0]).find('input:checkbox').attr('id');
		var orden = $(this[0]).find('input:checkbox').attr('attr-orden');
		var movi  = $(this[0]).find('input:checkbox').attr('attr-id_movi');
		var monto = $(this[0]).find('input:checkbox').attr('attr-monto');
		var check = $(this[0]).find('input:checkbox').is(':checked');
		if(crono == 1 && orden < ordenChecked){
			if(ordenChecked != orden && check == true){
				montoPagar = montoPagar - monto;
			}
			updateCell(id,movi,orden,false,orden,true,true,monto);
			removeFromArray(movi);
		}
	});
}

function hideShowInputAdelanto(cb){
	var checked = cb.is(':checked');
	if(checked == true){
		$('#montoAdelantoCont').fadeIn();
	} else{
		$('#montoAdelantoCont').fadeOut();
	}
}

function createReciboByCompromisos(arrayData){
	var cant = "1";
    ///////////////PRIMERO
//	var doc = new jsPDF('l', 'mm', [150, 340]);  x  = 
	var doc = new jsPDF('l', 'cm', [25, 13]);
	
    for(var i = 0 ; i< arrayData.length ; i++){
    	doc.setFont("helvetica");
    	doc.setFontSize(8);
//        doc.setTextColor(50,50,50);
//        doc.text(1, 2, 'Colegio Privado\n "Nuestra Se\u00F1ora de la Ascensi\u00F3n"\n');
        doc.text(0.005, 0.3, arrayData[i].cod_alumno);
        
        doc.text(1.8, 2.1, arrayData[i].estudiante);
        doc.setFontSize(8);
        doc.text(1, 2.8, arrayData[i].desc_nivel);
        
        doc.text(3.5, 2.8, arrayData[i].desc_grado);
        
        doc.text(7.1, 2.8, arrayData[i].desc_aula);

        doc.text(10,2.8, arrayData[i].fecha_pago);
        
        doc.text(20, 70, arrayData[i].cod_alumno);
        
        doc.setFontSize(9);
        
        doc.text(1.6, 4.9, cant);
        
        doc.text(2.9, 4.9, arrayData[i].concepto);
        
        doc.text(9.3, 4.9, arrayData[i].importe);
        
        doc.text(0.005, 4.9, arrayData[i].nro_documento);
        
        doc.text(3, 11.6, arrayData[i].usuario);
        
        doc.text(9.3, 11.6, arrayData[i].importe);
        
        ///////////////SEGUNDO
//        doc.setFontSize(9);
        /*doc.setTextColor(50,50,50);
        doc.text(235, 15, 'Colegio Privado\n "Nuestra Se\u00F1ora de la Ascensi\u00F3n"\n');*/
        doc.text(12.5005, 0.3, arrayData[i].cod_alumno);
        
        doc.text(14.3, 2.1,arrayData[i].estudiante);
        
        doc.setFontSize(8);
        doc.text(13.5, 2.8, arrayData[i].desc_nivel);
        
        doc.text(16, 2.8, arrayData[i].desc_grado);
        
        doc.text(19.6, 2.8, arrayData[i].desc_aula);
        
        doc.text(22.5, 2.8, arrayData[i].fecha_pago);
        
//        doc.text(1,1, arrayData[i].cod_alumno);
        
        doc.setFontSize(9);
        doc.text(14.1, 4.9, cant);
        
        doc.text(15.4, 4.9, arrayData[i].concepto);
        
        doc.text(21.8, 4.9, arrayData[i].importe);
        
        doc.text(15.5, 11.6, arrayData[i].usuario);
        
        doc.text(21.8, 11.6, arrayData[i].importe);
        
        doc.text(12.5005, 4.9, arrayData[i].nro_documento);
        
        if(i < arrayData.length-1){
        	doc.addPage('500','500');
    	}
    }
    doc.output('save', 'recibo_'+datetime);
}

function getDataByDocumento(movi,tipo_doc,nro_doc){
	$.ajax({
		data  : {movi     	 : movi,
			     tipo_doc 	 : tipo_doc,
			     currentPers : currentPers,
			     nro_doc     : nro_doc},
	    url   : 'c_ingresos/getDataByDocumento',
	    async : false,
	    type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.tipo == 'BOLETA'){
			sendToPreview(JSON.parse(data.datos));
		} else {
			createReciboByCompromisos(JSON.parse(data.datos));
		}
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
    console.log(data);
    for (var int = 0; int < data.length; int++) {
        doc.setFontSize(10);
        doc.text(21 , 33, data[int].nombrecompleto);
        doc.setFontSize(9);
        doc.text(21 , 38, data[int].ubicacion);
        doc.setFontSize(10);
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
        if(Number(data[int].mora) == 0){
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
    	doc.output('dataurlnewwindow');
}

function enviarDocumento(){
	abrirModalPaquete('Enviar Correo')
}

function mostrarChips(){
	if($('#div-parientes-chip').find('span').length>0){
		$('.mdl-layout__content').css('display','block');
		$('#state_empty').css('display','none');
	}
	else if($('#div-parientes-chip').find('span').length<0){
		$('.mdl-layout__content').css('display','none');
		$('#state_empty').css('display','block');
	}
	else
		$('#state_empty').css('display','block');
}

$('header .mdl-button__return').click(function() {
	location.reload();
});

function evaluatePromocion(monto){
	var descuento     = null;
	var flg_descuento = false;
	for(i = 0; i < promociones.length; i++){
		if(promociones[i].cuotas == arrayPagar.length){
			descuento = promociones[i].descuento;
			flg_descuento = true;
		}
	}
	montoDescuento = (flg_descuento == true) ? parseFloat(monto) - parseFloat((parseFloat(monto)*parseFloat(descuento))/100) : null;
	return montoDescuento;
}

var idMoviAnularTotal = null;
function openModalAnularCompromisoTotal(movi){
	modal('modalAnularCompromisoTotal');
	idMoviAnularTotal = movi;
}

function anularCompromisoTotal(){
	addLoadingButton('botonACT');
	var observacion = $.trim($('#observacionAnularTotal').val());
	if(observacion == null || observacion == ""){
		msj('warning','Ingresa una observaci&oacute;n');
		stopLoadingButton('botonACT');
		return;
	}
	$.ajax({
		data  : {idMovi      : idMoviAnularTotal,
			     currentTabl : currentTabl,
			     currentPers : currentPers,
			     observacion : observacion},
		url   : 'c_ingresos/anularCompromisoTotal',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			initTableComponents(data,currentTabl);
			modal('modalAnularCompromisoTotal');
			$('#observacionAnularTotal').val(null);
		}
		msj('warning',data.msj);
		stopLoadingButton('botonACT');
	});
}
