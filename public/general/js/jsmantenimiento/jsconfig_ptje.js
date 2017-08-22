function initConfigPtje() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_configPtje').bootstrapTable({ });
}

//INICIO MODAL POPUP 
function btnNuevoPtjeUniv(){
	 $('#selectTipoExamPopup').val("");
	 $('#selectTipoExamPopup').selectpicker('refresh');	
	 $('#selectUniv').val("");
	 $('#selectUniv').selectpicker('refresh');
	 $('#puntaje').val("");
	 abrirCerrarModal('modalTipoExam');
}

function grabarPuntajeUnivPopup() {
	var puntaje = $('#puntaje').val();
	var idUniv  = $('#selectUniv option:selected').val();
	var msjError = null;
	if(idUniv == null) {
		msjError = 'Seleccione una universidad';
	} else if(!$.isNumeric(puntaje) || puntaje.trim().length == 0 || puntaje <= 0) {
		msjError = 'El puntaje debe ser un n&uacute;mero entero mayor a 0';
	}
	if(msjError != null) {
		mostrarNotificacion('warning' , msjError, 'Ojo');
		return;
	}
	$.ajax({
		type : 'POST',
		url  : 'c_config_ptje/grabarUnivPuntajesPopup',
		data : { puntaje : puntaje,
				 idUniv  : idUniv},
	   async : false,
	})
	.done(function(data) {
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaConfigPtje').html(data.table);
				$('#tb_configPtje').bootstrapTable({ });
				initXEditable();
				generarBotonMenu();
				mostrarNotificacion('success', 'Se ha creado', 'Registro');
				abrirCerrarModal('modalTipoExam');
			} else {
				mostrarNotificacion('warning' , data.msj , 'Ojo');
			}
		}
	});
}

//FIN MODAL POPUP 
function generarBotonMenu(){
	var div = $('#contTablaConfigPtje .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="btn btn-icon-toggle" data-toggle="dropdown" style="margin-top:-4px;margin-left: 2px;width: 35px;margin-right:-10px">'+
		                '<span class="md md-more-vert" style="font-size:18px;margin-right: -1px;"></span>'+
			        '</button>'+
			        '<ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu" style="background-color:#fafafa">'+
			        	'<li><a href="#"><i class="md md-print" style="margin-right:10px"></i>Imprimir</a></li>'+
			            '<li><a href="#"><i class="md md-file-download" style="margin-right:10px"></i>Descargar</a></li>'+
			        '</ul>'+
			    '</div>');
}

///////////////////////////////// Xeditable ///////////////////////////////
function initXEditable() {
	$.fn.editable.defaults.mode = 'inline';
	validXEditablePuntaje();
}

function validXEditablePuntaje() {
	$('td').find(".classPtje").editable({
        type: 'text',
        name: 'valor_numerico',
        url: 'c_config_ptje/editarPuntaje',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene un puntaje';
            }
            if(value.length > 6){
            	return 'El puntaje no debe exceder 6 caracteres';
            }
            if(!$.isNumeric(value) || value <= 0) {
            	return 'El puntaje debe ser un número entero mayor a 0';
            }
        },
        success: function(response, newValue) {
        	if(response == ""){
    			location.reload();
    		} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            successValid('tb_configPtje', index, 2, data.pk, newValue, null,'classPtje');
	            mostrarNotificacion('success', data.msj, data.cabe);
	            initXEditable();
    		}
        },
        error: function(data) {
        	if(data == ""){
    			location.reload();
    		} else{
	            data = JSON.parse(data.responseText);
	            initXEditable();
	        	mostrarNotificacion('warning', data.msj, 'Ojo');
    		}
        }
    });
}

function logOut(){
	$.ajax({
		url  : 'c_config_ptje/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}