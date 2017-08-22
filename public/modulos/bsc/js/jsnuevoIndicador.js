function initNuevoIndicador() {
	//$('#tb_persona_x_indicadores').bootstrapTable({ });
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	//$('#tb_alumnos').bootstrapTable({ });	
}

function actualizarTable(data){
	$('#contTbFrecuencias').html(data.tableFrecuencias);
	$('#tb_frecuencias').bootstrapTable({});
	$('#contTbComparativas').html(data.tableComparativas);
	$('#tb_comparativas').bootstrapTable({});
	$('#contTbMetas').html(data.tableMetas);
	$('#tb_metas').bootstrapTable({});
	$('#valorAmarillo').val(data.valorAmarillo);
	$('#valorMeta').val(data.valorMeta);
}

function getObjetivosByLinea(){
	var idLinea = $('#selectLinea option:selected').val();
	var yearIndicador = $('#yearIndicador').val();
	$.ajax({
		type   : 'POST',
    	'url'  : 'c_nuevo_indicador/getObjetivosByLinea',
    	data   : {idLinea : idLinea,
    		yearIndicador :yearIndicador},
    	'async': false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			$('#selectObjetivo').find('option').remove().end().append('<option value="">Selec. Objetivo</option>'+data.comboObjetivo);
			$('select[name=selectObjetivo]').val("");
			$('#selectObjetivo').selectpicker('refresh');
			
			$('#selectIndicador').find('option').remove().end().append('<option value="">Selec. Indicador</option>');
			$('select[name=selectIndicador]').val("");
			$('#selectIndicador').selectpicker('refresh');
			actualizarTable(data);
		} else {
			$('#selectObjetivo').find('option').remove().end().append('<option value="">Selec. Objetivo</option>');
			$('select[name=selectObjetivo]').val("");
			$('#selectObjetivo').selectpicker('refresh');
			
			$('#selectIndicador').find('option').remove().end().append('<option value="">Selec. Indicador</option>');
			$('select[name=selectIndicador]').val("");
			$('#selectIndicador').selectpicker('refresh');
			actualizarTable(data);
			mostrarNotificacion('warning', data.msj, 'Ojo');
		}	
	});
}

function getIndicadoresByObjetivo(){
	var idObjetivo    = $('#selectObjetivo option:selected').val();
	var yearIndicador = $('#yearIndicador').val();
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_nuevo_indicador/comboIndicadores',
		data	: {idObjetivo    : idObjetivo,
			       yearIndicador : yearIndicador},
		'async' : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			$('#selectIndicador').find('option').remove().end().append('<option value="">Selec. Indicador</option>'+data.comboIndicador);
			$('select[name=selectIndicador]').val("");
			$('#selectIndicador').selectpicker('refresh');
			actualizarTable(data);
		} else {
			$('#selectIndicador').find('option').remove().end().append('<option value="">Selec. Indicador</option>');
			$('select[name=selectIndicador]').val("");
			$('#selectIndicador').selectpicker('refresh');
			actualizarTable(data);
			mostrarNotificacion('warning', data.msj, 'Ojo');
		}	
	});
}

function getDataByIndicador(){
	var yearIndicador = $('#yearIndicador').val();
	var idIndicador = $('#selectIndicador option:selected').val();

	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_nuevo_indicador/getDataByIndicador',
		data	: {yearIndicador : yearIndicador,
			       idIndicador   : idIndicador},
		'async' : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 1){
			mostrarNotificacion('warning',data.msj,'Ojo');
			actualizarTable(data);
		} else{
			actualizarTable(data);
		}
	});
}

function onChangeYearIndicador(){
	$('select[name=selectLinea]').val("");
	$('#selectLinea').selectpicker('refresh');
	
	$('#selectObjetivo').find('option').remove().end().append('<option value="">Selec. Objetivo</option>');
	$('select[name=selectObjetivo]').val("");
	$('#selectObjetivo').selectpicker('refresh');
	
	$('#selectIndicador').find('option').remove().end().append('<option value="">Selec. Indicador</option>');
	$('select[name=selectIndicador]').val("");
	$('#selectIndicador').selectpicker('refresh');
	
	$.ajax({
		type 	: 'POST',
		'url' 	: 'c_nuevo_indicador/limpiaTablasData',
		'async' : false
	})
	.done(function(data){
		data = JSON.parse(data);
		actualizarTable(data);
	});
}

function initValidComparativa(){
	$('#formValueIndi') 
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {
	    	valorAmarillo : {
	        	 validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el Valor Amarillo'
	                 },
		             numeric: {
		                 message: 'El valor amarillo debe contener solo dígitos'
		             },
		             between: {
                         min: 0,
                         max: 100,
                         message: 'El valor amarillo debe estar entre 0 y 100'
                     }
	             }
	        },
	        valorMeta : {
	        	 validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el Valor Meta'
	                 },
		             numeric: {
		                 message: 'El valor meta debe contener solo dígitos'
		             },
		             between: {
                        min: 0,
                        max: 100,
                        message: 'El valor meta debe estar entre 0 y 100'
                    }
	             }
	        }
        }
	}).on('success.form.bv', function(e) {
			e.preventDefault();
		    var $form = $(e.target),
		        formData = new FormData(),
		        params   = $form.serializeArray(),
		        fv       = $form.data('bootstrapValidator');
		    $.each(params, function(i, val) {
	            formData.append(val.name, val.value);
	        });		  
		    
		    var idIndicador   = $('#selectIndicador option:selected').val();
		    if(idIndicador != null){
			    $.ajax({  
			        data: formData,
			        url: "c_nuevo_indicador/registraIndicador",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
			  	.done(function(data) {
			  		data = JSON.parse(data);
			  		if(data.error == 1){
			  			mostrarNotificacion('warning', data.msj, 'Ojo');
			  		} else{
			  			actualizarTable(data);
			  			$('#formValueIndi').data('bootstrapValidator').resetForm(true);
			  			$('select[name=selectLinea]').val("");
			  			$('#selectLinea').selectpicker('refresh');
			  			
			  			$('#selectObjetivo').find('option').remove().end().append('<option value="">Selec. Objetivo</option>');
			  			$('select[name=selectObjetivo]').val("");
			  			$('#selectObjetivo').selectpicker('refresh');
			  			
			  			$('#selectIndicador').find('option').remove().end().append('<option value="">Selec. Indicador</option>');
			  			$('select[name=selectIndicador]').val("");
			  			$('#selectIndicador').selectpicker('refresh');
			  			
			  			$('#yearIndicador').val('');
			  			mostrarNotificacion('success',data.msj,data.cabecera);
			  		}
			     })
		     	 .fail(function(jqXHR, textStatus, errorThrown) {
		     		 //mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
			  	 })
			  	 .always(function() {		      	 
			  	 });
		    }
	  });
}

function logOutNuevoIndicador() {
	$.ajax({
		url  : 'c_comparativa/logOutNuevoIndicador', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
	});
}