var currentdate = new Date(); 
var datetime =  currentdate.getDate() + "_"
                + (currentdate.getMonth()+1)  + "_" 
                + currentdate.getFullYear() + "_"  
                + currentdate.getHours() + "_"  
                + currentdate.getMinutes() + "_" 
                + currentdate.getSeconds();
function init(){
	initButtonLoad('registrarEgreso','sabeConceptoEgreso','botonAE');
	initLimitInputs('observacionAnular','observacion','observacionNueva');
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip(); 
    }); 
}

function registrarEgreso(){
	addLoadingButton('registrarEgreso');
	var concepto    = $('#selectConceptoEgreso option:selected').val();
	var monto 	    = $('#montoEgreso').val();
	var observacion = $('#observacion').val();
	$.ajax({
		data  : {concepto    : concepto,
			     monto       : monto,
			     observacion : observacion}, 
		url   : 'c_egresos/registrarEgresoPersona',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){	
			$('#contTbEgresos').html(data.tbEgresos);
			$('#tb_egresos').bootstrapTable({});
			componentHandler.upgradeAllRegistered();			
			tableEventsUpgradeMdlComponentsMDL('tb_egresos');
			
			abrirCerrarModal('modalAddEgreso');			
			setearCombo('selectConceptoEgreso', "");
			setearInput('montoEgreso', "");
			setearInput('observacion', "");
			setTimeout(function(){
				imprimirDocumento(data.compromiso,data.tipo_doc);
			},500);	
		}
		stopLoadingButton('registrarEgreso');
		mostrarNotificacion('warning',data.msj);
	});
}

function getMontoReferenciaByConcepto(){
	var monto = $('#selectConceptoEgreso').find(':selected').data('monto');
	setearInput('montoEgreso', monto);
}

function openModalAnularEgreso(egreso){
	sessionStorage.idEgreso = egreso;
	abrirCerrarModal('modalAnularEgreso');
}

function anularEgresoByPersona(){
	addLoadingButton('botonAE');
	var observacion = $('#observacionAnular').val();
	$.ajax({
		data  : {egreso 	 : sessionStorage.idEgreso,
			     observacion : observacion},
		url   : 'c_egresos/anularEgreso',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$('#contTbEgresos').html(data.tbEgresos);
			$('#tb_egresos').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_egresos');
			abrirCerrarModal('modalAnularEgreso');
			setearInput('observacionAnular', "");			
		}
		stopLoadingButton('botonAE');
		mostrarNotificacion('warning' , data.msj);	
	});
}

function getReciboByEgreso(egreso){
	$.ajax({
		data  : {egreso : egreso},
		url   : 'c_egresos/getReciboByEgreso',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contentDocsEgreso').html(data.boleta);
		abrirCerrarModal('modalVisualizarDocumentos');
		$(document).ready(function(){
    	    $('[data-toggle="tooltip"]').tooltip(); 
        });
	});
}

function saveConceptoAddEgreso(){
	addLoadingButton('sabeConceptoEgreso');
	var concepto    = $('#desc_concepto').val();
	var monto       = $('#monto_concepto').val();
	var observacion = $('#observacionNueva').val();
	$.ajax({
		data  : {concepto    : concepto,
				 monto       : monto,
				 observacion : observacion},
		url   : 'c_egresos/guardarConceptoEgreso',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$('#contTbEgresos').html(data.tbEgresos);
			$('#tb_egresos').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_egresos');
			
			abrirCerrarModal('modalAgregarConcepto');			
			setCombo("selectConceptoEgreso", data.optConceptos, 'Concepto',null);
			setearInput('desc_concepto', "");
			setearInput('monto_concepto', "");
			setearInput('observacionNueva', "");
			
		}
		stopLoadingButton('sabeConceptoEgreso');
		mostrarNotificacion('warning',data.msj);			
	});
}

function imprimirDocumento(compromiso,tipo_doc){
	$.ajax({
		data  : {compromiso : compromiso,
			     tipo_doc   : tipo_doc},
		url   : 'c_egresos/getDatosByRecibo',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		sendToPreview(data)
	});
}

function sendToPreview(data){
	var doc = new jsPDF('p', 'cm', [11, 7.5]);
    var chartHeight = 10;
    var currentdate = new Date(); 
    var datetime =    currentdate.getDate() + "-"
                    + (currentdate.getMonth()+1)  + "-" 
                    + currentdate.getFullYear() + " "  
                    + currentdate.getHours() + ":"  
                    + currentdate.getMinutes() + ":" 
                    + currentdate.getSeconds();
    doc.setFontSize(8);
    doc.setFont("helvetica");
    var filaInicioDetalle = 3.2;
    var aumento 		  = 0.4;
    var filaInicio        = 3.5;
    // x | y
    //CAMBIAR POR PNG
    var base64 = getBase64Image(document.getElementById("logo_avantgard_none"));
    doc.addImage(base64, 'JPEG',75, 5, 20, 20);
    doc.text(3.1, 3.1, 'SEDE ' + data.sede);
    doc.setFontSize(8);
    doc.setFont("helvetica");
    filaInicioDetalle = filaInicioDetalle + aumento;
    doc.text(2.1, filaInicioDetalle, 'RUC: 20390193883');
    filaInicioDetalle = filaInicioDetalle + aumento;
    doc.text(2.1, filaInicioDetalle, 'Fecha: ' + data.fecha);
    filaInicioDetalle = filaInicioDetalle + aumento;
    doc.text(2.1, filaInicioDetalle, 'Hora: '  + data.hora);
    filaInicioDetalle = filaInicioDetalle + aumento;
    doc.text(2.1, filaInicioDetalle, 'Usu. Reg: ' + data.usuario);
    filaInicioDetalle = filaInicioDetalle + aumento;
    doc.text(2.2, filaInicioDetalle, 'Num Ope : ' + data.nro_documento);
    //traer correlativo
    doc.setFontSize(8);
    doc.setFont("helvetica");
    //CUOTA
    doc.text(1.2  , (filaInicioDetalle+0.5) , data.desc_concepto);
    doc.text(1.2  , (filaInicioDetalle+1) , 'Total');
    //TOTAL
    doc.setFontSize(8);
    doc.setFont("helvetica");
    doc.text(6 , (filaInicioDetalle+0.5) , data.monto_final);
    doc.text(6 , (filaInicioDetalle+1) , data.monto_final);
    doc.text(1.1 , (filaInicioDetalle+2.5) , 'Cliente : ' + data.persona);
    /////////////////////////////////////
    setTimeout(function(){
    	doc.output('save','ticket_'+datetime);
    },1000);
}

function enviarDocumento(){
	abrirModalPaquete('Enviar Correo')
}
