function initConfigMedidaNota() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_config_medidarash_nota').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	initButtonLoad( 'btnMF', 'btnMMN' );
	$('#selectConfig').change(function(){
		addLoadingButton('btnMF');
		config = $('#selectConfig option:selected').val();
	    $.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_config_medida_nota/getConfigByMedidaRashPromedio_CTRL',
	    	data   : {config : config},
	    	'async': true	    	
	    })
	    .done(function(data){
	    	if(data == ""){
	    		location.reload();
	    		stopLoadingButton('btnMF');
	    	} else{
	    		data = JSON.parse(data);
				if(data.error == 0 || data.error==2) {
					$('#contTablaConfigMedidaRashNota').html(data.tablaConfigMedidaRashNota);
					$('#tb_config_medidarash_nota').bootstrapTable({ });
					initSearchTableNew();
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
					$('.fixed-table-toolbar').addClass('mdl-card__menu');	
					$('main section .mdl-content-cards .img-search').css('display', 'none');
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#year').val());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectConfig  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
					$('main section .mdl-content-cards .mdl-card').removeAttr('style');	
					generarBotonMenu();				
					initXEditable();
					stopLoadingButton('btnMF');
				} else if(data.error == 1) {
					mostrarNotificacion('warning', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
					stopLoadingButton('btnMF');
				}
	    	}
	     });
	});
}

//Modal Popup///
//Inicio
function getConfigByMedidaPromedioPopup(){
	var configpopup = $('#selectConfigPopup option:selected').val();
    $.ajax({
    	type   : 'POST',
    	url    : 'c_config_medida_nota/comboMedidaPromedio',
    	data   : {configpopup : configpopup},
    	'async': false	    	
    }).done(function(data){
    	if(data == ""){
    		location.reload();
    	} else{
	    	data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selecPromMedidaRash', data.optPromMedida, 'Promedio/Medida Rash');
			} else if(data.error == 1) {
				mostrarNotificacion('warning', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
    	}
     });
}

function btnMedidaNotaPopup(){
	 $('#selectConfigPopup').val("");
	 $('#selectConfigPopup').selectpicker('refresh');	
	 $('#selecPromMedidaRash').val("");
	 $('#selecPromMedidaRash').selectpicker('refresh');
	 $('#puntaje').addClass('form-control');
	 $('#puntaje').val("");
	 abrirCerrarModal('modalMedidaNota');
}

function grabarMedidaNotaPopup() {
	addLoadingButton('btnMMN');
	var configpopup = $('#selectConfigPopup option:selected').val();
	var configPromMedida = $('#selecPromMedidaRash option:selected').val();
	var puntaje = $('#puntaje').val();	
	var msjError = null;
	
		if(configpopup == null || configpopup == "" ) {
			msjError = 'Seleccione una configuraci&oacuten';
			stopLoadingButton('btnMMN');
		}else if(configPromMedida == null || configPromMedida == ""){
			msjError = 'Seleccione un promedio/medida rash';
			stopLoadingButton('btnMMN');
		}else if(puntaje == null || puntaje == ""){
        	  msjError = 'Ingrese un puntaje';
        	  stopLoadingButton('btnMMN');
        }else if(puntaje.length > 6){
        	  msjError = 'El puntaje no debe exceder 6 caracteres';
        	  stopLoadingButton('btnMMN');
        }else if( puntaje <= 0) {
        	msjError = 'El puntaje debe ser un n\u00famero entero mayor a 0';
        	stopLoadingButton('btnMMN');
        }else if(/^[a-z]+$/i.test(puntaje)){
        	msjError = 'El puntaje debe ser un n\u00famero entero';
        	stopLoadingButton('btnMMN');
        }
        if(msjError != null) {
    		mostrarNotificacion('warning' , msjError, 'Ojo');
    		stopLoadingButton('btnMMN');
    		return;
    	}
 
	$.ajax({
		url: "c_config_medida_nota/grabarMedidaRashPromedioPuntajesPopup",
	    data: { configpopup  : configpopup, 
	    	configPromMedida : configPromMedida,
	                 puntaje : puntaje},
		async : true,
		type : 'POST'
	})
	.done(function(data){
		if(data == ""){
    		location.reload();
    		stopLoadingButton('btnMMN');
    	} else{
			data = JSON.parse(data);
			if(data.error == 0 ||data.error == 2) {
				$('#contTablaConfigMedidaRashNota').html(data.tablaConfigMedidaRashNota);
				$('#tb_config_medidarash_nota').bootstrapTable({ });
				mostrarNotificacion('success', 'Se ha creado', 'Registro');
				initXEditable();
				generarBotonMenu();
				initSearchTableNew();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				abrirCerrarModal('modalMedidaNota');
				stopLoadingButton('btnMMN');
			} else if(data.error == 1) {
				mostrarNotificacion('warning', data.msj, 'Ojo');
				stopLoadingButton('btnMMN');
			}
    	}
	});
}

function abrirCerrarModal (){
	$('#modalMedidaNota').modal({
	    backdrop: 'static',
	    keyboard: false
	});
}

function btnMedidaNotaPopup(){
	$('#modalFiltro').modal({
	    backdrop: 'static',
	    keyboard: false
	});
}

///Fin

///////////////////////////////// Xeditable ///////////////////////////////
function initXEditable() {
	$.fn.editable.defaults.mode = 'inline';
	validXEditablePuntaje();
}

function validXEditablePuntaje() {
	$('td').find(".classPtje").editable({
        type: 'text',
        name: 'valor_numerico',
        url: 'c_config_medida_nota/editarPuntaje',
        params: function (params) {
        	params.grupo = $(this).data('grupo'),
        	params.id_nota = $(this).data('id_nota')
            return params;
        },
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene un puntaje';
            }
            if(value.length > 6){
            	return 'El puntaje no debe exceder 6 caracteres';
            }
            if( value <= 0) {
            	return 'El puntaje debe ser un n\u00famero entero mayor a 0';
            }
            if(/^[a-z]+$/i.test(value)){
            	return 'El puntaje debe ser un n\u00famero entero';
            }
        },
        success: function(response, newValue) {
        	if(response == ""){
	    		location.reload();
	    	} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            successValidConfig('tb_config_medidarash_nota', index, 2, data.pk, newValue, null,'classPtje', $(this).data('grupo'), $(this).data('id_nota'));
	            mostrarNotificacion('success', data.msj, 'Edit');
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


function generarBotonMenu(){
	var div = $('#contTablaConfigMedidaRashNota .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
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
		url  : 'c_config_medida_nota/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}
