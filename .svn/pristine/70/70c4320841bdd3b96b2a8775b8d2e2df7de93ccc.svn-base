var condicionComparativa = 0;
function initComparativa() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}	
	$('#tb_comparativas').bootstrapTable({ });
}

function abrirModalAddComparativas(){
	$('#formEditComparativa').data('bootstrapValidator').resetForm(true);
	$('#comparativaModal').prop( "disabled", true );
	$('#selectIndi').prop( "disabled", true );
	$('#comparativaModal').val('');
	$('select[name=selectTipoModal]').val('');
	$('#selectTipoModal').selectpicker('refresh');
	$('select[name=selectIndi]').val('');
	$('#selectIndi').selectpicker('refresh');
	$('#contDescComparativa').fadeOut();
	$('#contComboSelectIndi').fadeOut();
	$('#contYearComparativa').fadeOut();
	$('#valorModal').val('');
	abrirCerrarModal('modalAddComparativas');
}

function capturarValorNumerico(){
	var idIndi = $('#selectIndi option:selected').val();
	$.ajax({
		type	: 'POST',
		'url'	: 'c_comparativa/getValorNumerico',
		data	: {idIndi   : idIndi},
		'async' : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();	
		} else{
			data = JSON.parse(data);
			$('#valorModal').prop( "disabled", true );
			setearInput("valorModal", data.valor);
			setearInput("yearModal", data.year);
			$('#yearModal').prop( "disabled", true );
		}
	});
}

function initValidComparativa(){
	$('#formEditComparativa')
	.find('[name="selectTipoModal"]')
	   .selectpicker().change(function(e) {
	        $('#formEditComparativa').bootstrapValidator('revalidateField', 'selectTipoModal');
	   }).end()
	   
	.find('[name="selectIndi"]')
	   .selectpicker().change(function(e) {
	        $('#formEditComparativa').bootstrapValidator('revalidateField', 'selectIndi');
	   }).end()
	   
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {
	        valorModal : {
	        	 validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el valor'
	                 },
		             numeric: {
		                 message: 'El valor debe contener solo dígitos'
		             },
		             between: {
                         min: 0,
                         max: 100,
                         message: 'El valor debe estar entre 0 y 100'
                     }
	             }
	        },
	        comparativaModal : {
	        	 validators: {
	        		 notEmpty: {
	                     message: 'Ingrese la comparativa'
	                 }
	             }
	        },
	        selectTipoModal: {
	            validators: {
	                notEmpty: {
	                    message: 'Debe seleccionar un Tipo de Comparativa'
	                }
	            }
	        },
	        selectIndi: {
	            validators: {
	                notEmpty: {
	                    message: 'Debe seleccionar un Indicador'
	                },
                    callback: {
                        message: 'La comparativa ya fue registrada en el a\u00f1o',
                        callback: function (value, validator) { 
                        	if(value != "") {
                        		condicionComparativa = 0;
                        		result = existComparativa('indicador',value);
    	                        if(result == '1') {//Existe
    		                        return false;
    	                        } else {
    		                        return true;
    	                        }
                        	} else {
                        		return true;
                        	}
                        }
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
		    if(condicionComparativa == 1){
		    	mostrarNotificacion('success', 'La comaprativa ya fue registrada en el a\u00f1o', null);
		    } else if (condicionComparativa == 0){
		    $.each(params, function(i, val) {
	            formData.append(val.name, val.value);
	        });		  
		    
				
		    $.ajax({  
		        data: formData,
		        url: "c_comparativa/agregarComparativa",
		        cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST'
		  	})
		  	.done(function(data) {
		  		if(data == ""){
					location.reload();	
				} else{
			  		data = JSON.parse(data);
			  		if(data.error == 1){
			  			mostrarNotificacion('success', data.msj , null);
			  		} else{
			  			if(data.error == 2){
			  				mostrarNotificacion('success', data.msj , null);	
			  			} else{
			  				if(data.error == 3 ){
			  					mostrarNotificacion('success', data.msj , null);
			  				} else{
			  					abrirCerrarModal('modalAddComparativas');
								$('#contTbComparativas').html(data.tbComparativas);
								$('#tb_comparativas').bootstrapTable({});
								mostrarNotificacion('success', data.msj , null);
			  				}
			  			}
			  		}
				}
		     })
	     	 .fail(function(jqXHR, textStatus, errorThrown) {
	     		 //mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
		  	 })
		  	 .always(function() {		      	 
		  		 $('#formEditComparativa').data('bootstrapValidator').resetForm(true);
		  	 });}
	  });
	 
}

function onChangeComboComparativas(){
	var tipoComparativa = $('#selectTipoModal option:selected').val();
	addLoadingButton("agregarBtn");
	if(tipoComparativa == 'HISTORICO'){
		$('#comparativaModal').prop( "disabled", true );
		$('#selectIndi').prop( "disabled", false );
		$('#valorModal').val('');
		$('select[name=selectIndi]').val('');
		$('#selectIndi').selectpicker('refresh');
		$('#contDescComparativa').fadeOut();
		$('#contYearComparativa').fadeIn();
		$('#contComboSelectIndi').fadeIn();
		$.ajax({
			type	: 'POST',
			'url'	: 'c_comparativa/setComboInputComparativa',
			data	: {tipoComparativa   : tipoComparativa},
			'async' : false
		})
		.done(function(data){
			if(data == ""){
			location.reload();	
			} else{
				data = JSON.parse(data);
				if(data.error == 1) {
					mostrarNotificacion('success', data.msj , null);
				} else{
					$('#valorModal').prop( "disabled", true );
					$('#selectIndi').find('option').remove().end().append('<option value="">Selec. Indicadores</option>'+data.comboIndicadores);
					$('select[name=selectIndi]').val("");
					$('#selectIndi').selectpicker('refresh');
				}
			}			
		});

	} else if(tipoComparativa == 'OTRO'){
		$('#valorModal').val('');
		$('#comparativaModal').val('');
		$('#valorModal').prop( "disabled", false );
		$('#selectIndi').prop( "disabled", true );
		$('#comparativaModal').prop( "disabled", false );
		$('#contComboSelectIndi').fadeOut();
		$('#contYearComparativa').fadeOut();
		$('#contDescComparativa').fadeIn();
	} else{
		$('#valorModal').val("");
		$('#valorModal').prop( "disabled", true );
		$('#selectIndi').prop( "disabled", true );
		$('#contComboSelectIndi').fadeOut();
		$('#contYearComparativa').fadeOut();
		$('#contDescComparativa').fadeOut();
	}
}

function existComparativa(campo,valor){
	var result = $.ajax({
		type : "POST",
		'url' : 'c_comparativa/existComparativa',
		data : {'valor' : valor,
				'campo' : campo},
		'async' : false
	}).responseText;
	return result;
}

function onChangeComparativa(){
	var descComparativa = $('#comparativaModal').val(); 
	result = existComparativa('comparativa',$.trim(descComparativa));
	if(result == 1){
		mostrarNotificacion('warning', 'La comaprativa ya fue registrada en el a\u00f1o', 'OJO');
		condicionComparativa = 1;
		//$('#agregarBtn').prop( "disabled", true );
	} else{
		condicionComparativa = 0;
	}
}

function logOutCompa() {
	$.ajax({
		url  : 'c_comparativa/logOutCompa', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
	});
}