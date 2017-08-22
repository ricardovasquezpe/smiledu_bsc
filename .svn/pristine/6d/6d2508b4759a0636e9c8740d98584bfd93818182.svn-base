function init(){
	initButtonLoad('registrarEgreso','sabeConceptoEgreso','botonAE');
	initLimitInputs('observacionAnular','observacion','observacionNueva');
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip(); 
    }); 
}

function getReciboByEgreso(egreso){
	$.ajax({
		data  : {egreso : egreso},
		url   : 'c_mis_egresos/getReciboByEgreso',
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