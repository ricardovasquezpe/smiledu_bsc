function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	
	$('#tb_incidencias').bootstrapTable({ });
	initValidatorNuevoIncidencia();
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
}

var idIncidencia;
var valEstado;
function abrirModalCambiarEstado(data, dataID){
	if(data == 0 || data == 1){
		$('#desc_cambiarEstado').html('¿Se resolvió la incidencia?');
	}else if(data == 2 || data == 3){
		$('#desc_cambiarEstado').html('¿Hubo recuperación oportuno de pagos por incapacidad?');
	}
	idIncidencia = dataID;
	$('#divFechaResuelto').css('display','none');
	$('input[name=radioVals]').attr('checked',false);
	abrirCerrarModal('modalCambiarEstado');	
}

function abrirModalPersonal(){
	$("#personaBusqueda").val("");
	$("#cont_tab_personal").html("");
	abrirCerrarModal('modalPersonal');
}

function getPersonasByNombre(){
	var nombrePersona = $('#personaBusqueda').val();
	if(nombrePersona == null){
		mostrarNotificacion('warning','No ha ingresado ningún nombre','Ojo');
	}
	if(nombrePersona.length >= 3){
		$.ajax({
			type	: 'POST',
			'url'	: 'c_incidencia/getPersonasByNombre',
			data	: {nombre   : nombrePersona},
			'async' : false
		})
		.done(function(data){
			result = JSON.parse(data);
			$("#cont_tab_personal").html(result.tabla);
			$('#tb_personal').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
		});
	}
}

var idPersonal;
function elegirPersonal(data, nombreCompleto){
	idPersonal = data;
	abrirCerrarModal('modalPersonal');
	$("#personalElegido").addClass("dirty");
	$("#personalElegido").val(nombreCompleto);
	
	$('#formNuevaIncidencia').bootstrapValidator('revalidateField', 'personalElegido');
}

function cambiarEstado(){
	valEstado = $('input[name="radioVals"]:checked').val();
	fechaRealizado = $('#fechaResuelto').val();
	$.ajax({
		data : { idTipoIncidencia : idIncidencia,
				 valEstado        : valEstado,
				 fecha            : fechaRealizado},  
		url  : 'c_incidencia/cambiarEstado', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
  		$('#contTablaIncidencia').html(result.tb_incidencias);
  		$('#tb_incidencias').bootstrapTable({ });
  		initSearchTable();
  		$('.fixed-table-toolbar').addClass('mdl-card__menu');
  		idIncidencia = "";
  		valEstado = "";
  		abrirCerrarModal('modalCambiarEstado');
	});
}

function abrirModalRegIncidencia(){
	$('#selectPersonal').val("");
	$('#selectPersonal').selectpicker('refresh');
	$('#selectSede').val("");
	$('#selectSede').selectpicker('refresh');
	$('#selectArea').val("");
	$('#selectArea').selectpicker('refresh');
	$('#selectAreaEsp').val("");
	$('#selectAreaEsp').selectpicker('refresh');
	$('#selectTincidencia').val("");
	$('#selectTincidencia').selectpicker('refresh');
	$('#fecha').val("");
	$('#descripcion').val("");
	$("#personalElegido").removeClass("dirty");
	//$('#formNuevaIncidencia').data('bootstrapValidator').resetForm(true);
	$('#cont_cb').html('');
	$('#fecha').removeClass("dirty");
	$('#descripcion').removeClass("dirty");
	abrirCerrarModal('modalRegIncidencia');
	$('#modalRegIncidencia').modal({
	    backdrop: 'static',
	    keyboard: false
	});
}

function changeTipoIncidencia(){
	val = $('#selectTincidencia option:selected').val();
	$.ajax({
		data : { idTipoIncidencia  : val },  
		url  : 'c_incidencia/evalComboTipo', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		if(result.opc == 1){
			$('#cont_cb').html('<label><input type="checkbox" id="checkbox" name="checkbox"><span>'+result.text+'</span></label>');
		}else{
			$('#cont_cb').html('');
		}
	});
}

function changeAreaGeneral(){
	val = $('#selectArea option:selected').val();
	$.ajax({
		data : { idArea  : val },  
		url  : 'c_incidencia/comboAreasEspecificas', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		setCombo('selectAreaEsp', data, 'Área Específica');
	});
}

function initValidatorNuevoIncidencia(){
	$('#formNuevaIncidencia')	  
	   .find('[name="selectSede"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaIncidencia').bootstrapValidator('revalidateField', 'selectSede');
	   }).end()  
	   
	   .find('[name="selectArea"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaIncidencia').bootstrapValidator('revalidateField', 'selectArea');
	   }).end()  
	   
	   .find('[name="selectAreaEsp"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaIncidencia').bootstrapValidator('revalidateField', 'selectAreaEsp');
	   }).end() 
	   
	   .find('[name="selectTincidencia"]')
	   .selectpicker().change(function(e) {
	        $('#formNuevaIncidencia').bootstrapValidator('revalidateField', 'selectTincidencia');
	   }).end() 
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {	 
	    	personalElegido : {
	        	validators: {
	        		 notEmpty: {
	                     message: 'Seleccione una persona de la incidencia'
	                 }
	            }
	        },
	        fecha : {
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
	        },
	        selectTincidencia: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un tipo de incidencia'
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
		    var valCB = "0";
		    if ($('#checkbox').is(":checked"))
		    {
		    	valCB = "1";
		    }
		    
		    formData.append('checkBox', valCB);
		    formData.append('selectPersonal', idPersonal);
		    
		    $.ajax({  
		        data: formData,
		        url: "c_incidencia/insertIncidencia",
		        cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST'
		  	})
		  	.done(function(data) {
		  		result = JSON.parse(data);
		  		console.log(data)
		  		if(result.error == 1){
		  			mostrarNotificacion('error',result.msj, null);
		  		} else{
			  		$('#contTablaIncidencia').html(result.tb_incidencias);
			  		$('#tb_incidencias').bootstrapTable({ });
			  		initSearchTable();
			  		$('.fixed-table-toolbar').addClass('mdl-card__menu');
			  		abrirCerrarModal('modalRegIncidencia');
		  		}
		  		})
	     	  .fail(function(jqXHR, textStatus, errorThrown) {
	     		 mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
		  	  })
		  	  .always(function() {		      	 
		  	});	 
	    });
	}

function verObservacion(data){
	$("textarea#textObservacion").val(data);
	abrirCerrarModal('modalObservacion');
}


function logOut(){
	$.ajax({
		url  : 'c_incidencia/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function showFechaRealizado(data){
	if(data == 1){
		$('#divFechaResuelto').css('display','block');
		$('#fechaResuelto').val('');
	}else{
		$('#divFechaResuelto').css('display','none');
		$('#fechaResuelto').val('');
	}
}