	var nidD = null;
function initDisciplina(){
	$('#tb_disciplinas').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	generarBotonMenu();
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}	
	
	
	$('#selectTipoDisciplina').change(function(){
		val = $('#selectTipoDisciplina option:selected').val();
	    $.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_disciplina/getDisciplinasByTipo',
	    	data   : {val : val},
	    	'async': false	    	
	    })
	    .done(function(data){
	    	if(data == ""){
				location.reload();
			} else{
		    	$('#selectDisciplina').html(data).selectpicker('refresh');
		    	var validator = $('#formNewCompetencia').data('bootstrapValidator');
		    	if(val == "ARTISTICA"){
		    		$('#selectNivelCompetitivo').val("");
		    		$('#divNvlCompe').addClass('hide');
		    	    validator.enableFieldValidators('selectNivelCompetitivo', false);
		    	} else {
		    		$('#divNvlCompe').removeClass('hide');
		    		validator.enableFieldValidators('selectNivelCompetitivo', true);
		    	}
			}
	     });
	});
	initButtonLoad( 'btnMNC', 'btnMCE' );
	initValidatorNewActiComi();
}

function abrirModalReg(){
	 $('#selectTipoDisciplina').val("");
	 $('#selectTipoDisciplina').selectpicker('refresh');	
	 $('#selectDisciplina').val("");
	 $('#selectDisciplina').selectpicker('refresh');
	 $('#selectNivelCompetitivo').val("");
	 $('#selectNivelCompetitivo').selectpicker('refresh');
	 $('#selectNivelAcademico').val("");
	 $('#selectNivelAcademico').selectpicker('refresh');
	 $('#selectDocentes').val("");
	 $('#selectDocentes').selectpicker('refresh');
	 $('#fecCompe').val("");
	 $('#organizador').val("");
	 $('#nroCopas').val("");
	 $('#formNewCompetencia').data('bootstrapValidator').resetForm(true);
	 abrirCerrarModal('modalNuevaCompetencia');		
}

function initValidatorNewActiComi(){
	$('#formNewCompetencia')	  
	   .find('[name="selectTipoDisciplina"]')
	   .selectpicker().change(function(e) {
	        $('#formNewCompetencia').bootstrapValidator('revalidateField', 'selectTipoDisciplina');
	   }).end()
	       
	   .find('[name="selectDisciplina"]')
	   .selectpicker().change(function(e) {
	        $('#formNewCompetencia').bootstrapValidator('revalidateField', 'selectDisciplina');
	   }).end()  
	   
	   .find('[name="selectNivelCompetitivo"]')
	   .selectpicker().change(function(e) {
	        $('#formNewCompetencia').bootstrapValidator('revalidateField', 'selectNivelCompetitivo');
	   }).end()  
	   
	   .find('[name="selectNivelAcademico"]')
	   .selectpicker().change(function(e) {
	        $('#formNewCompetencia').bootstrapValidator('revalidateField', 'selectNivelAcademico');
	   }).end()
	   
	   .find('[name="selectDocentes"]')
	   .selectpicker().change(function(e) {
	        $('#formNewCompetencia').bootstrapValidator('revalidateField', 'selectDocentes');
	   }).end()   
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {	 
	    	organizador : {
	        	validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el organizador de la competencia'
	                 }
	            }
	        },
	        fecCompe : {
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
	        nroCopas : {
	        	 validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el número de Copas'
	                 },	                
		             digits: {
		                 message: 'El número de copas debe ser un número entero'
		             }
	               }
	        },
	        selectTipoDisciplina: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un tipo disciplina'
	                }
	            }
	        },
	        selectDisciplina: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione una disciplina'
	                }
	            }
	        },
	        selectNivelCompetitivo: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un tipo de nivel competitivo'
	                }
	            }
	        },
	        selectNivelAcademico: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un nivel académico'
	                }
	            }
	        },
	        selectDocentes: {
	            validators: {
	                notEmpty: {
	                    message: 'Seleccione un docente'
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
		    $.ajax({  
		        data: formData,
		        url: "c_disciplina/insCompetencia",
		        cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST'
		  	})
		  	.done(function(data) {
		  		if(data == ""){
					location.reload();
				} else{
			  		var	dato	=	JSON.parse(data);
					if(	dato.error	==	0){//SUCCESS
						mostrarNotificacion('success', dato.msj, 'Registro');
						$('#contTabCompe').html(dato.tabCompetencias);
						$('#tb_disciplinas').bootstrapTable({ });
						$('.fixed-table-toolbar').addClass('mdl-card__menu');
						initSearchTableNew();
						generarBotonMenu();
						abrirCerrarModal('modalNuevaCompetencia');
					}else if( dato.error	==	1){//ERROR
						toastr.error(dato.msj, dato.cabecera, {timeOut: 2000});				
					}else if( dato.error	==	2){//WARNING
						toastr.warning(dato.msj, dato.cabecera, {timeOut: 2000});
					}
				}
		  		})
	     	  .fail(function(jqXHR, textStatus, errorThrown) {
	     		 mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
		  	  })
		  	  .always(function() {		      	 
		  	});	 
	    });
	
	}

function deleteCompetencia(id){
	nidD = id;
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	initSearchTableNew();
	abrirCerrarModal('mdConfirmDelete');
	initSearchTableNew();
}

function confirmDelete(){
	var id_delete = nidD;
	$.ajax({
		type   : 'POST',
		'url' : 'c_disciplina/deletCompetencia',
		data   : {id_delete : id_delete},
		'async': false		
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
				var	dato = JSON.parse(data);
			if(	dato.error	==	0){//SUCCESS			
				mostrarNotificacion('success', 'Se ha eliminado', 'Registro');
				$('#contTabCompe').html(dato.tabCompetencias);
				$('#tb_disciplinas').bootstrapTable({ });
				generarBotonMenu();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
			}else if( dato.error	==	1){//ERROR			
				toastr.error(dato.msj, dato.cabecera, {timeOut: 2000});				
			}else if( dato.error	==	2){//WARNING			
				toastr.warning(dato.msj, dato.cabecera, {timeOut: 2000});
			}		
			abrirCerrarModal('mdConfirmDelete');
		}
	});
}

function generarBotonMenu(){
	var div = $('#contTabCompe .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" data-toggle="dropdown" id="buttonMoreVert">'+
			        	'<i class="mdi mdi-more_vert"></i>'+
			        '</button>'+
			        '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="buttonMoreVert">' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-print"></i> Imprimir</li>' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-file_download"></i> Descargar</li>' +
					'</ul>'+
			    '</div>');
}

function logOut(){
	$.ajax({
		url  : 'c_disciplina/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

