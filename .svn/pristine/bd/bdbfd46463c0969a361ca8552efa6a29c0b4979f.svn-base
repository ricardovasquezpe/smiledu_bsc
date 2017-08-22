var eventId     = null;
var fechaCambio = null;
var actualYear  = (new Date).getFullYear();
var actual  = $.datepicker.formatDate('yy-mm-dd', new Date());
function initCalendar(datos) {
	$('#calendar').fullCalendar({
		header : {
			left   : 'prev,next today',
            center : 'title',
            rigth  : ''
		},
		eventColor: '#004062',
    	weekends: true,
    	lang: 'es',
    	events: datos,
    	//events: datos,
    	dayRender: function(date, cell){
            if (moment().diff(date,'days') > 0){
                cell.css("background-color","#efefef");
            }
            var today = new Date();
        },
    	dayClick: function(date) {
    		var year = date.format().substring(0, 4);
    		if(actualYear == year){
    			fechaCambio = date.format();
        		abrirModalAddCapacitacion();
    		}
        },
        eventClick: function(event) {
        	eventId     = event.id;
    		fechaCambio = event.start.format();
        	abrirModalEditEvento(event);
	    }
    });
	
	$(".fc-left").css('display','none');	
	$(".fc-center").find('h2').css('font-size','21px');
	$(".fc-right").find('.fc-today-button').removeClass("fc-button fc-state-default fc-corner-left fc-corner-right");
	$(".fc-right").find('.fc-today-button').addClass("btn ink-reaction btn-primary btn-flat");
	$(".fc-right").find('.fc-today-button').css("padding","1.5px 8px");
	
	$(".fc-right").find('.fc-button-group').find(".fc-prev-button").removeClass("fc-button fc-state-default fc-corner-left");
	$(".fc-right").find('.fc-button-group').find(".fc-prev-button").addClass("btn btn-icon-toggle");
	$(".fc-right").find('.fc-button-group').find(".fc-prev-button").css({padding: "1.5px 8px",height: "35px"});
	
	$(".fc-right").find('.fc-button-group').find(".fc-next-button").removeClass("fc-button fc-state-default fc-corner-left");
	$(".fc-right").find('.fc-button-group').find(".fc-next-button").addClass("btn btn-icon-toggle");
	$(".fc-right").find('.fc-button-group').find(".fc-next-button").css({padding: "1.5px 8px",height: "35px"});
}

function abrirModalAddCapacitacion() {
	$('#descripcion').val(null);
	$('#detalle').val(null);
	$('#selectSede').val("");
	$('#selectSede').selectpicker('refresh');
	$('#selectArea').val("");
	$('#selectArea').selectpicker('refresh');
	$('#selectAreaEsp').val("");
	$('#selectAreaEsp').selectpicker('refresh');
	$('#formNuevaCapacitacion').data('bootstrapValidator').resetForm(true);
	
	$("#descripcion").removeClass("dirty");
	$("#detalle").removeClass("dirty");
	abrirCerrarModal('modalNewCapacitacion');
}

function initValidatorNuevaCapacitacion(){
	$('#formNuevaCapacitacion')	  
	   .find('[name="selectSede"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaCapacitacion').bootstrapValidator('revalidateField', 'selectSede');
	   }).end()  
	   
	   .find('[name="selectArea"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaCapacitacion').bootstrapValidator('revalidateField', 'selectArea');
	   }).end()  
	   
	   .find('[name="selectAreaEsp"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaCapacitacion').bootstrapValidator('revalidateField', 'selectAreaEsp');
	   }).end() 
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {	 
	    	descripcion : {
	        	validators: {
	        		 notEmpty: {
	                     message: 'Ingrese una descripcion de la capacitacion'
	                 }
	            }
	        },
	        selectSede: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione una sede'
	                }
	            }
	        },
	        selectArea: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un área'
	                }
	            }
	        },
	        selectAreaEsp: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un área especifica'
	                }
	            }
	        }        
	       }  
	    })
	    .on('success.form.bv', function(e) {
			e.preventDefault();
		    var $form = $(e.target),
		        formData = new FormData(),
		        params   = $form.serializeArray(),
		        fv    = $form.data('bootstrapValidator');
		    $.each(params, function(i, val) {
	            formData.append(val.name, val.value);
	        });
		    
		    formData.append('fecha', fechaCambio);
		    
		    $.ajax({  
		        data: formData,
		        url: "c_capacitacion/agregarCapacitacion",
		        cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST'
		  	})
		  	.done(function(data) {
		  		data = JSON.parse(data);
				if(data.error == 1){
					mostrarNotificacion('error',data.msj,'');
				} else{
					//AGREGA EL EVENTO AL CALENDARIO
					$('#calendar').fullCalendar('renderEvent', data);
					abrirCerrarModal('modalNewCapacitacion');
					mostrarNotificacion('success',data.msj,'');
				}
		  		
		  		})
	     	  .fail(function(jqXHR, textStatus, errorThrown) {
	     		 mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
		  	  })
		  	  .always(function() {		      	 
		  	});	 
	    });
	}

function abrirModalEditEvento(event) {
	var val = (event.realizado === "true");
	$('#descripcionEdit').val(event.title);
	$('#detalleEdit').val(event.observaciones);
	$('#fechaEdit').val(event.fec_reali);
	$('#fechaEdit').addClass("dirty");
	$('#realizado').prop('checked', val);
	
	$("#detalleEdit").addClass("dirty");
	$("#descripcionEdit").addClass("dirty");
	$.ajax({
		data : { idCap  : event.id },  
		url  : 'c_capacitacion/getComboCapEdit', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		res = JSON.parse(data);
		setCombo('selectAreaEspEdit', res.areasEsps, 'Área Específica');
		
		$('#selectSedeEdit').val(res.sede);
		$('#selectSedeEdit').selectpicker('refresh');
		$('#selectAreaEdit').val(res.area);
		$('#selectAreaEdit').selectpicker('refresh');
		$('#selectAreaEspEdit').val(res.areaEsp);
		$('#selectAreaEspEdit').selectpicker('refresh');
	});

	abrirCerrarModal('modalEditEvent');
}

function changeAreaGeneral(){
	val = $('#selectArea option:selected').val();
	$.ajax({
		data : { idArea  : val },  
		url  : 'c_capacitacion/comboAreasEspecificas', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		setCombo('selectAreaEsp', data, 'Área Específica');
	});
}

function initValidatorEditarCapacitacion(){
	$('#formEditCapacitacion')	  
	   .find('[name="selectSedeEdit"]')
	   .selectpicker().change(function(e) {
	        $('#formEditCapacitacion').bootstrapValidator('revalidateField', 'selectSede');
	   }).end()  
	   
	   .find('[name="selectAreaEdit"]')
	   .selectpicker().change(function(e) {
	        $('#formEditCapacitacion').bootstrapValidator('revalidateField', 'selectArea');
	   }).end()  
	   
	   .find('[name="selectAreaEspEdit"]')
	   .selectpicker().change(function(e) {
	        $('#formEditCapacitacion').bootstrapValidator('revalidateField', 'selectAreaEsp');
	   }).end() 
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {	 
	    	descripcionEdit : {
	        	validators: {
	        		 notEmpty: {
	                     message: 'Ingrese una descripcion de la capacitacion'
	                 }
	            }
	        },
	        fechaEdit : {
	        	 validators: {
	                 notEmpty: {
	                     message: 'Ingrese la fecha'
	                 },
	                 date: {
	                     format: 'DD/MM/YYYY',
	                     message: 'El formato de fecha es DD/MM/YYYY'
	                 }      
	               }
	        },
	        selectSedeEdit: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione una sede'
	                }
	            }
	        },
	        selectAreaEdit: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un área'
	                }
	            }
	        },
	        selectAreaEspEdit: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un área especifica'
	                }
	            }
	        }        
	       }  
	    })
	    .on('success.form.bv', function(e) {
			e.preventDefault();
		    var $form = $(e.target),
		        formData = new FormData(),
		        params   = $form.serializeArray(),
		        fv    = $form.data('bootstrapValidator');
		    $.each(params, function(i, val) {
	            formData.append(val.name, val.value);
	        });
		    
		    formData.append('id', eventId);
		    
		    $.ajax({  
		        data: formData,
		        url: "c_capacitacion/editarCapacitacion",
		        cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST'
		  	})
		  	.done(function(data) {
		  		data = JSON.parse(data);
				if(data.error == 1){
					mostrarNotificacion('error', data.msj, null);
				} else {
					$('#calendar').fullCalendar('removeEvents', eventId);
					$('#calendar').fullCalendar('renderEvent', data);
					abrirCerrarModal('modalEditEvent');
					mostrarNotificacion('success', data.msj, null);
				}
		  	  })
	     	  .fail(function(jqXHR, textStatus, errorThrown) {
	     		 mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
		  	  })
		  	  .always(function() {		      	 
		  	});	 
	    });
	}

function editarCapacitacion() {
	if($.trim($('#descripcionEdit').val()) == "" || $('#descripcionEdit').val() == null || 
	   $.trim($('#descripcionEdit').val()) == "" || $('#descripcionEdit').val() == null ) {
		mostrarNotificacion('error', 'Registre la descripción','');
		return;
	}
	var realizado = $('#realizado').is(':checked');
	$.ajax({
		url  : 'c_capacitacion/editarCapacitacion', 
		data : {descripcion : $.trim($('#descripcionEdit').val()),
			    detalle     : $.trim($('#detalleEdit').val()),
			    fecha       : fechaCambio,
			    eventId	    : eventId,
				realizado   : realizado},
		async: false,
		type : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 1){
			mostrarNotificacion('error', data.msj, null);
		} else {
			$('#calendar').fullCalendar('removeEvents', eventId);
			$('#calendar').fullCalendar('renderEvent', data);
			abrirCerrarModal('modalEditEvent');
			mostrarNotificacion('success', data.msj, null);
		}
	});
}