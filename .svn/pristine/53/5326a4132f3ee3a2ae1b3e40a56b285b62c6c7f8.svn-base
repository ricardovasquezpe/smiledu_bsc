function initRubrica() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tbFactores').bootstrapTable({ });
	$('#tbFichas').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');	   
	tableEventsFactores();
	tableEventsFichas();
	initSearchTable();
	$("#modalAsignarValores, #modalEditarPesoFactor").draggable({
	    handle: ".mdl-card__title"
	});
}
//Trae los 5 primeros valores de la tabla evmvalo
function registrarValor() {
	val = $('#selectValor option:selected').val();
	Pace.restart();
	Pace.track(function() {
	    $.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_rubrica/getValores_CTRL',
	    	data   : {val : val}  	
	    })
	    .done(function(data) {
	    data = JSON.parse(data);
	       if(data.error == 0 || data.error == 2){
			  mostrarNotificacion('success', data.msj);
			  abrirCerrarModal('modalValores');
			}else{
			  mostrarNotificacion('error', data.msj, 'ERROR');
			}
	    });
	});
}

function abrirModalComboValor(){
	abrirCerrarModal('modalValores');
}

function finalizar() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_rubrica/finalizar",
	        type: 'POST'
		})
		.done(function(data) {
	    	location.reload();
		});
	});
}

function abrirModalCrearCriterio() {
	Pace.restart();
	Pace.track(function() {
		$('#descripcionCriterio').val("");
		$.ajax({
	    	type  : 'POST',
	    	'url' : 'c_rubrica/traerFactoresParaAgregar'
	    })
	    .done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	        	$('#contTbFactorAsignar').html(data.tbFactoresAsignar);
			    $('#tbFactoresAsignar').bootstrapTable({});
			    $('.fixed-table-toolbar').addClass('mdl-card__menu');
			    componentHandler.upgradeAllRegistered();
			    tableEventsFactoresPorAsignar();
			    initSearchTableById('tbFactoresAsignar');
	    		abrirCerrarModal('modalCrearCriterio');
			} else {
				mostrarNotificacion('error', data.msj, 'ERROR');
			}
	    });
	});
}

function abrirModalCrearIndicador() {
	$('#descripcionIndicador').val(null);
	$('#descripcionIndicador').parent().removeClass('is-dirty');
	if(idFactorGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_rubrica/traerSubFactoresParaAgregar',
	    	data   : {idFactor : idFactorGlobal}
	    })
	    .done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	        	$('#contTbSFAsignar').html(data.tbSFAsignar);
			    $('#tbSFAsignar').bootstrapTable({});
			    $('.fixed-table-toolbar').addClass('mdl-card__menu');
			    componentHandler.upgradeAllRegistered();
			    tableEventsIndicadoresPorAsignar();
			    initSearchTableById('tbSFAsignar');
			    abrirCerrarModal('modalCrearIndicador');
			} else {
			  mostrarNotificacion('error', data.msj, 'ERROR');
			}
	    });
	});
}

var arrayIndisAsig = [];
function manejarChecksSubFactAsignar(chkBox) {
	var checked = chkBox.is(":checked");
	var cnt = 0;
	$.each(arrayIndisAsig, function( index, value ) {
		if(value.id_subfactor_asig == chkBox.data('id_subfactor_asig')) {//ELIMINA
			arrayIndisAsig.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {
		arrayIndisAsig.splice(arrayIndisAsig.length, 0, {id_subfactor_asig: chkBox.data('id_subfactor_asig') } );
	}
	//
	var idCheck  = chkBox.attr('id');
	var idFactor = chkBox.data('id_subfactor_asig');
	var indexRow = chkBox.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
			   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="manejarChecksSubFactAsignar($(this));" data-id_subfactor_asig="'+idFactor+'">'+
			       '    <span class="mdl-checkbox__label"></span>'+
				   '</label>';
	$('#tbSFAsignar').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function asignarSubFactoresAFactor() {
	Pace.restart();
	Pace.track(function() {
		if(arrayIndisAsig.length > 0) {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_rubrica/asignarSubFactoresAFactor',
		    	data   : {idFactor       : idFactorGlobal,
		    		      arrayIndisAsig : arrayIndisAsig}
		    })
		    .done(function(data) {
		    	data = JSON.parse(data);
		    	if(data.error == 0) {
		    		$('#contTabSubFactor').html(data.tbSubFactores);
		    		var page_number = $.cookie('page_number_tbSubFactores');
					if( page_number == null ) {
						page_number = 1;
					}
					$('#tbSubFactores').bootstrapTable({ pageNumber : parseInt(page_number) });
					tableEventsSubFactores();
				    $('.fixed-table-toolbar').addClass('mdl-card__menu');
		    		arrayIndisAsig = [];
		    		$('#descripcionIndicador').val(null);
		    		$('#descripcionIndicador').parent().removeClass('is-dirty');
		    		abrirCerrarModal('modalCrearIndicador');
				} else {
				  mostrarNotificacion('error', data.msj, 'ERROR');
				}
		    });
		}
	});
}

var arrayFactAsig = [];
function manejarCheckFactAsignar(chkBox) {
	var checked = chkBox.is(":checked");
	var cnt = 0;
	$.each(arrayFactAsig, function( index, value ) {
		if(value.id_factor_asig == chkBox.data('id_factor_asig')) {//ELIMINA
			arrayFactAsig.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {
		arrayFactAsig.splice(arrayFactAsig.length, 0, {id_factor_asig: chkBox.data('id_factor_asig') } );
	}
	var idCheck  = chkBox.attr('id');
	var idFactor = chkBox.data('id_factor_asig');
	var indexRow = chkBox.closest('tr').data('index');
	var chekado  = checked == true ? 'checked' : null;
	var newCheck = '<label for="'+idCheck+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
			   	   '    <input type="checkbox" '+chekado+' class="mdl-checkbox__input" id="'+idCheck+'" onclick="manejarCheckFactAsignar($(this));" data-id_factor_asig="'+idFactor+'">'+
			       '    <span class="mdl-checkbox__label"></span>'+
				   '</label>';
	$('#tbFactoresAsignar').bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered();
}

function asignarFactoresARubrica() {
	Pace.restart();
	Pace.track(function() {
		if(arrayFactAsig.length > 0) {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_rubrica/asignarFactoresARubrica',
		    	data   : { arrayFactAsig : arrayFactAsig }
		    })
		    .done(function(data) {
		    	data = JSON.parse(data);
		    	if(data.error == 0) {
		    		$('#contTabRubricas').html(data.tbFactores);
		    		$('#tbFactores').bootstrapTable({ });
		    		componentHandler.upgradeAllRegistered();
		    		tableEventsFactores();
		    		idFactorGlobal = null;
		    		$('#contTabSubFactor').html(null);
				    $('#tbSubFactores').bootstrapTable({});
				    
				    $('.fixed-table-toolbar').addClass('mdl-card__menu');
				    arrayFactAsig = [];
		    		abrirCerrarModal('modalCrearCriterio');
				} else {
				  mostrarNotificacion('error', data.msj, 'ERROR');
				}
		    });
		}
	});
}

function enableRegistrar(idInput, idBtn){
	var cantPalabra = $('#'+idInput).val();
	if( (cantPalabra.length) > 0 ){
		$('#'+idBtn).removeClass('mdl-color-text--grey-500');
		$('#'+idBtn).addClass('mdl-color--green-500 mdl-color-text--white');
	}else if ( (cantPalabra.length) == 0){
		$('#'+idBtn).removeClass('mdl-color--green-500 mdl-color-text--white');
		$('#'+idBtn).addClass('mdl-color-text--grey-500');
	}
}

function cambioCheckCriterio(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckCriterio("tbcriterio", index, 2, cb.checked, cb.id,cb.getAttribute('attr-bd'), cb.getAttribute('attr-idcriterio'));	
}

function onChangeCheckCriterio(idTable, index, column, nuevoValor, id, bd, idCriterio){
	var check = "checked";
	if(nuevoValor == false ){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	if(nuevoValor == bdVal){
		cambioCheck = false;
	}else{
		cambioCheck = true;
	}
	 
	var check =    '<div class="checkbox checkbox-inline checkbox-styled"> '+
						    '    <label>'+
						    '        <input type="checkbox" onclick="cambioCheckCriterio(this);" '+check+' id="'+id+'" attr-bd="'+bd+'"' +
						    '			attr-cambio="'+cambioCheck+'" attr-idcriterio="'+idCriterio+'">' +
						    '        <span></span>' +
						    '    </label>' +
						    '</div> ';
		
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 2,
		fieldValue : check
	});	
}

/*function capturarCriterios() {
	var condicion = 0;
	var json = {};
	var criterios = [];
	json.criterio = criterios;	
		var arrayData = getCheckedFromTablaByAttr('tbcriterio', 2);
		$.each( arrayData, function( key, value ) {
			var idCriterio = $(value).find(':checkbox').attr('attr-idcriterio');
			var valor       = $(value).find(':checkbox').is(':checked');
			var criterio    = {"valor" : valor , "nid_criterio" : idCriterio};			
	 		json.criterio.push(criterio);
	 		condicion = 1;
		});
		var jsonStringCriterio = JSON.stringify(json);
		if(condicion == 0){
			mostrarNotificacion('warning','No se hicieron cambios','Ojo');
		} else{
			val = $('#selectValor option:selected').val();
			$.ajax({
				type : 'POST',
				url : 'c_rubrica/grabarCriterios',
				data : { criterios : jsonStringCriterio,
							   val : val}, 
				async : false
			})
			.done(function(data){
			data = JSON.parse(data);			
				if(data.error == 0) {
					$('#contTabRubricas').html(data.tbRub);
					$('#tbFactores').bootstrapTable({});
					mostrarNotificacion('success', 'Se ha Registro ', 'Se Registro');
					//initSearchTable();
					abrirCerrarModal('modalAsignarCriterio');
					$('#tbcriterio').bootstrapTable({});
				} else {
					mostrarNotificacion('error', data.msj, 'ERROR');
				}
				
			});
		}
}*/

//FIN DEL MODAL POPUP//
//INICIO DEL MODAL POPUP DETALLE//
/*function mostrarIndicadores(boton) {
	var row = boton.closest('tr');
	var idCriterio = row.find('.btnIDCrit').data('idCripk');
	$.ajax({
		type	: 'POST',
		'url'	: 'c_rubrica/mostrarIndicadores',
		data	: { idCriterio : idCriterio },
		'async' : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#contTbIndicadorModal').html(data.tablaIndicador);
		$('#tbindicador').bootstrapTable({});
		initSearchTableModal('customBarIndi', 'modalAsignarIndicadores');
	});
	abrirCerrarModal('modalAsignarIndicadores');
}*/

function cambioCheckIndicador(cb) {
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckIndicador("tbindicador", index, 2, cb.checked, cb.id,cb.getAttribute('attr-bd'), cb.getAttribute('attr-idindicador'), cb.getAttribute('attr-idcriterio'), cb.getAttribute('attr-idficha'));	
}

function onChangeCheckIndicador(idTable, index, column, nuevoValor, id, bd, idIndicador, idCriterio, idFicha){
	var check = "checked";
	if(nuevoValor == false ) {
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	if(nuevoValor == bdVal) {
		cambioCheck = false;
	} else {
		cambioCheck = true;
	}
	 
	var check =    '<div class="checkbox checkbox-inline checkbox-styled"> '+
				   '    <label>'+
				   '        <input type="checkbox" onclick="cambioCheckIndicador(this);" '+check+' id="'+id+'" attr-bd="'+bd+'"' +
				   '			attr-cambio="'+cambioCheck+'" attr-idindicador="'+idIndicador+'" attr-idcriterio="'+idCriterio+'" attr-idficha="'+idFicha+'">' +
				   '        <span></span>' +
				   '    </label>' +
				   '</div> ';
		
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 2,
		fieldValue : check
	});	
}

function capturarIndicadores(){
	val = $('#selectValor option:selected').val();
	var condicion = 0;
	var json = {};
	var indicadores = [];
	json.indicador = indicadores;	
		var arrayData = getCheckedFromTablaByAttr('tbindicador', 2);
		$.each( arrayData, function( key, value ) {
			var idIndicador= $(value).find(':checkbox').attr('attr-idindicador');
			var idCriterio = $(value).find(':checkbox').attr('attr-idcriterio');
			var idFicha    = $(value).find(':checkbox').attr('attr-idficha');
			var valor      = $(value).find(':checkbox').is(':checked');
			var indicador   = {"valor" : valor , "id_indicador" : idIndicador , "id_criterio" : idCriterio, "id_rubrica" : idFicha};
	 		json.indicador.push(indicador);
	 		condicion = 1;

		});
		var jsonStringIndicador = JSON.stringify(json);
		if(condicion == 0){
			mostrarNotificacion('warning','No se hicieron cambios','Ojo');
		} else {
			Pace.restart();
			Pace.track(function() {
				$.ajax({
					type : 'POST',
					url : 'c_rubrica/grabarIndicacores',
					data : { indicadores : jsonStringIndicador,
						             val : val}, 
					async : false
				})
				.done(function(data){
				    data = JSON.parse(data);			
					if(data.error == 0) {
						$('#contTabRubricas').html(data.tbRub);
						$('#tbFactores').bootstrapTable({});
						$('.fixed-table-toolbar').addClass('mdl-card__menu');
						componentHandler.upgradeAllRegistered();
						tableEventsFactores();
						mostrarNotificacion('success', 'Se ha modificado', 'OK');
						abrirCerrarModal('modalAsignarIndicadores');
					} else {
						mostrarNotificacion('error', data.msj, 'ERROR');
					}
				});
			});
		}
}

function mostrarValores(boton) {
	var row = boton.closest('tr');
	var idCrit = row.find('.btnIDCrit').attr('data-idCripk');
	var idInd  = row.find('.btnIDCrit').attr('data-idIndpk');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type	: 'POST',
			'url'	: 'c_rubrica/mostrarValores',
			data	: { idCrit : idCrit,
						idInd  : idInd }
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contTbValModal').html(data.tablaValor);
			$('#tbvalor').bootstrapTable({});
			componentHandler.upgradeAllRegistered();			
			abrirCerrarModal('modalAsignarValores');
		});
	});
}

function capturarValores(input) {
	var json = {};
	var leyendas = [];
	json.leyenda = leyendas;
	$('*[data-descrip="1"]').each(function() {
		var idCrit    = $(this).attr('data-pkidCrit');
		var idRub     = $(this).attr('data-pkidRub');
		var idInd     = $(this).attr('data-pkidInd');
		var idVal     = $(this).attr('data-pkidVal');
	    var descrip   = $(this).val();
	    var leyenda   = {"id_criterio" : idCrit , "id_rubrica" : idRub , "id_indicador" : idInd ,"valor" : idVal , "leyenda" : descrip};
		json.leyenda.push(leyenda);	
	});
	var jsonStringLeyenda = JSON.stringify(json);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type : 'POST',
			url : 'c_rubrica/grabarLeyendas',
			data : { leyendas : jsonStringLeyenda}
		})
		.done(function(data) {
			data = JSON.parse(data);			
			if(data.error == 0) {
				mostrarNotificacion('success', data.msj);
				abrirCerrarModal('modalAsignarValores');
			} else {
				mostrarNotificacion('error', data.msj, 'ERROR');
			}
		});
	});
}
//////////FIN////////////

/////////////////MODAL POPUP NUEVO CRITERO y INDICADOR///////////////////
function nuevoCriterio() {
	Pace.restart();
	Pace.track(function() {
		var descrip = $('#descripcionCriterio').val();
		if($.trim(descrip) == '') {
			mostrarNotificacion('error', 'Escriba el Factor');
			return;
		}
		$.ajax({
			url: "c_rubrica/crearCriterio",
	        data: { descrip : descrip},
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success', data.msj);
				//$('#contTbFactorAsignar').html(data.tbFactoresAsignar);
				
				$('#newFactor').removeClass('mdl-color--green-500 mdl-color-text--white');
				$('#newFactor').addClass('mdl-color-text--grey-500');
				$('#descripcionCriterio').val(null);
				$('#descripcionCriterio').parent().removeClass('is-dirty');
				
				/*var page_number = $.cookie('page_number_tbFactoresAsignar');
				if( page_number == null ) {
					page_number = 1;
				}
				$('#tbFactoresAsignar').bootstrapTable({ pageNumber : parseInt(page_number) });*/
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				var newIndex = $('#tbFactoresAsignar').bootstrapTable('getData').length;
				var newCheckbox = '<label for="chk_Fac_'+(newIndex + 1)+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
					   	       	  '    <input type="checkbox" class="mdl-checkbox__input" id="chk_Fac_'+(newIndex + 1)+'" onclick="manejarCheckFactAsignar($(this));" data-id_factor_asig="'+data.new_id_factor+'">'+
							      '    <span class="mdl-checkbox__label"></span>'+
							 	  '</label>';
				
				var $_index = $('#tbFactoresAsignar tbody>tr:first').data('index');
				if(!$_index) {
					$_index = 0;
				}
				var filaNum = $_index + 1;
				
				var tabla = $('#tbFactoresAsignar').bootstrapTable('getOptions').data;
				tabla = tabla.filter(function (i,n){
			        return i.nro >= filaNum;
			    });
				
				$('#tbFactoresAsignar').bootstrapTable('insertRow', {
	                index: $_index,
	                row: {
	                	nro: filaNum,
	                	desc_factor: descrip,
	                	checkbox: newCheckbox
	                }
	            });

				$.each(tabla, function(index, value) {
					$('#tbFactoresAsignar').bootstrapTable('updateCell',{
						rowIndex   : value.nro,
						fieldName  : 'nro',
						fieldValue : (parseInt(value.nro) + 1)
					});
				});
				
				componentHandler.upgradeAllRegistered();
				$('#tbFactoresAsignar tbody>tr:first').effect("highlight", {color : 'green' }, 3000);
			} else if(data.error == 1) {
			    mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		});
	});
}

function nuevoIndicador() {
	var descrip = $('#descripcionIndicador').val();
	if($.trim(descrip) == '') {
		mostrarNotificacion('error', 'Escriba el Subfactor');
		return;
	}
	///////////////////////////////////////////////
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_rubrica/crearIndicador",
	        data: { descrip  : descrip, 
	        	    idFactor : idFactorGlobal},
	        type: 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success', 'Se ha Registrado', 'Se Registro');

				$('#newIndi').removeClass('mdl-color--green-500 mdl-color-text--white');
				$('#newIndi').addClass('mdl-color-text--grey-500');
				$('#descripcionIndicador').val(null);
				$('#descripcionIndicador').parent().removeClass('is-dirty');
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				
				var newIndex = $('#tbSFAsignar').bootstrapTable('getData').length;
				var newCheckbox = '<label for="chk_'+(newIndex + 1)+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
					   	       	  '    <input type="checkbox" class="mdl-checkbox__input" id="chk_'+(newIndex + 1)+'" onclick="manejarChecksSubFactAsignar($(this));" data-id_subfactor_asig="'+data.new_id_subfactor+'">'+
							      '    <span class="mdl-checkbox__label"></span>'+
							 	  '</label>';
				
				var $_index = $('#tbSFAsignar tbody>tr:first').data('index');
				if(!$_index) {
					$_index = 0;
				}
				var filaNum = $_index + 1;
				
				var tabla = $('#tbSFAsignar').bootstrapTable('getOptions').data;
				tabla = tabla.filter(function (i,n){
			        return i.nro >= filaNum;
			    });
				
				$('#tbSFAsignar').bootstrapTable('insertRow', {
	                index : $_index,
	                row: {
	                	nro            : filaNum,
	                	desc_subfactor : descrip,
	                	checkbox       : newCheckbox
	                }
	            });
				
				$.each(tabla, function(index, value) {
					$('#tbSFAsignar').bootstrapTable('updateCell',{
						rowIndex   : value.nro,
						fieldName  : 'nro',
						fieldValue : (parseInt(value.nro) + 1)
					});
				});
				componentHandler.upgradeAllRegistered();
				$('#tbSFAsignar tbody>tr:first').effect("highlight", {color : 'green' }, 3000);
			} else if(data.error == 1) {
			    mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		});
	});
} 


////////////////////////////////////////////////////////
/////////////////////JS CONS_RUBRICA////////////////////
////MODAL POPUP NUEVA FICHA////
function mostrarFicha() {
	$('#descripcion').val(null);
	modal('modalAsignarFicha');
}

function getTipoFicha() {
	var idTipoFich = $('#selectTipoFicha option:selected').val();	
	$.ajax({
		url: "c_rubrica/comboTipoFicha_CTRL",
        data: { idTipoFich : idTipoFich},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0 || data.error==2) {
	    setCombo('selectTipoFichCurso', data.opTipoFiCurso, 'TipoFichaCurso');
		} else if(data.error == 1) {
	    mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
		}	
	});
}

function registrarRubricaNew() {
	var nombreRubrica = $.trim($('#descripcion').val());
	if(nombreRubrica.length > 50) {
		 mostrarNotificacion('error', 'La descripción debe contener como máximo 50 caracteres.');
		 return;
	}
	$.ajax({
		url: "c_rubrica/grabarFicha",
        data: { nombreRubrica : nombreRubrica },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			$('#contTabConsRubricas').html(data.tbRubricas);
			$('#tbFichas').bootstrapTable({});
			tableEventsFichas();
			
		    abrirCerrarModal('modalAsignarFicha');
			mostrarNotificacion('success', data.msj);
			componentHandler.upgradeAllRegistered();
		} else if(data.error == 1) {
		    mostrarNotificacion('error',CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
		}
	});
}

function editarFicha(id) {
	var idFicha = id;
	$.ajax({
		url: "c_rubrica/editarFicha",
		data: { idFicha : idFicha },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		location.reload();
	});  
} 

function activarFicha(id) {
	$.ajax({
		url: "c_rubrica/activarFicha",
		data: { idFicha : id},
        type: 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			mostrarNotificacion('success', data.msj);
			$('#contTabConsRubricas').html(data.tbFichas);
			$('#tbFichas').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
		} else if(data.error == 1) {
		    mostrarNotificacion('error', data.msj, CONFIG.get('CABE_ERR'));
		}
	});
}

function inactivarFicha(id) {
	$.ajax({
		url: "c_rubrica/inactivarFicha",
		data: { idFicha : id },
        type: 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			mostrarNotificacion('success', data.msj);
			$('#contTabConsRubricas').html(data.tbFichas);
			$('#tbFichas').bootstrapTable({});
			componentHandler.upgradeAllRegistered();
		} else if(data.error == 1) {
		    mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
		}
	});
}

/******************************************* NUEVOS METODOS *******************************************/

var btnEditarFactorGlobal = null;
function editarPesoFactorModal(btn) {
	btnEditarFactorGlobal = btn;
	
	var peso = btn.closest('tr').find('.classPeso').data('peso');
	var idFactor = btn.closest('tr').find('.btnID').data('id_factor');
	idFactorGlobal = idFactor;
	
	if(peso == undefined || peso == null) {
		peso = null;
	}
	$('#peso').val(peso);
	$('#peso').parent().addClass('is-focused');
	abrirCerrarModal('modalEditarPesoFactor');
}

function editarPesoFactor() {
	var peso = $.trim($('#peso').val());
	if(!isNumerico(peso)) {
		mostrarNotificacion('error', 'Debe ingresar un n&uacute;mero');
		return;
	}
	if(peso > 100) {
		mostrarNotificacion('error', 'El peso no puede ser mayor a 100');
		return;
	}
	if(peso <= 0) {
		mostrarNotificacion('error', 'El peso no puede ser cero');
		return;
	}
    Pace.restart();
	Pace.track(function() {
		var row = btnEditarFactorGlobal.closest('tr');
		var idFactor = row.find('.btnID').attr('data-id_factor');
		if(idFactor == undefined || idFactor == null) {
			mostrarNotificacion('error', CONFIG.get('MSJ_ERR'));
			return;
		}
		///////////////////////////////////////////////////////////
		$.ajax({
			type	: 'POST',
			'url'	: 'c_rubrica/editarPesoFactor',
			data	: { idFactor : idFactor,
				        peso     : peso },
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#tbFactores').bootstrapTable('updateCell',{
					rowIndex   : row.data('index'),
					fieldName  : 2,
					fieldValue : peso+' %'
				});
				
				$.each($("#tbFactores tr"), function() {
					$(this).css('background-color','white');
				});
				
				var newRow = $("#tbFactores tr").filter(function() {
							    return $(this).data('index') == row.data('index');
							 });
				newRow.css('background-color','rgba(255,146,0,0.2)');
				newRow.find('.classPeso').attr('data-peso', peso);
				
				$('#pesoTotal').text(data.suma_pesos);
				$('#pesoTotal').attr('class', data.pesoTotalCSS);
				
				$('#contTabSubFactor').html(null);

				componentHandler.upgradeAllRegistered();
				moveRowFactor();
				
				mostrarNotificacion('success', data.msj);
				abrirCerrarModal('modalEditarPesoFactor');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		});
	});
}

var btnBorrarFactorGlobal = null;
function borrarFactorModal(btn) {
	btnBorrarFactorGlobal = btn;
	abrirCerrarModal('mdConfirmDeleteFactor');
}

function borrarFactor() {
	Pace.restart();
	Pace.track(function() {
		var row = btnBorrarFactorGlobal.closest('tr');
		var idFactor = row.find('.btnID').attr('data-id_factor');
		/////////////////////////////
		$.ajax({
			type	: 'POST',
			'url'	: 'c_rubrica/borrarFactor',
			data	: { idFactor : idFactor },
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTabRubricas').html(data.tbFactores);
				$('#tbFactores').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				tableEventsFactores();
				
				$('#pesoTotal').text(data.suma_pesos);
				$('#pesoTotal').attr('class', data.pesoTotalCSS);
				
				$('#contTabSubFactor').html(null);

				componentHandler.upgradeAllRegistered();
				
				mostrarNotificacion('success', data.msj);
				abrirCerrarModal('mdConfirmDeleteFactor');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		});
	});
}

var btnBorrarSubFactorGlobal = null;
function borrarSubFactorModal(btn) {
	btnBorrarSubFactorGlobal = btn;
	abrirCerrarModal('mdConfirmDelete');
}

function borrarSubFactor() {
	Pace.restart();
	Pace.track(function() {
		var row = btnBorrarSubFactorGlobal.closest('tr');
		var idFactor    = row.find('.btnIDCrit').attr('data-idCripk');
		var idSubFactor = row.find('.btnIDCrit').attr('data-idIndpk');
		$.ajax({
			type	: 'POST',
			'url'	: 'c_rubrica/borrarSubFactor',
			data	: {idFactor    : idFactor,
					   idSubFactor : idSubFactor}
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTabSubFactor').html(data.tbSubFactores);

				var page_number = $.cookie('page_number_tbSubFactores');
				if( page_number == null ) {
					page_number = 1;
				}
				$('#tbSubFactores').bootstrapTable({ pageNumber : parseInt(page_number) });
				
		    	tableEventsSubFactores();
		    	
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();
				
				mostrarNotificacion('success', data.msj);
				abrirCerrarModal('mdConfirmDelete');
			} else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		});
	});
}

function getSubFactoresByFactor(btn) {
	var idFactor = btn.closest('tr').find('.btnID').data('id_factor');
	idFactorGlobal = idFactor;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_rubrica/traerSubFactoresByFactor',
	    	data   : {idFactor : idFactor}    	
	    })
	    .done(function(data) {
	    	data = JSON.parse(data);
	        if(data.error == 0) {
	        	$('#img_not_data').css('display' , 'none');
	        	$('#contTabSubFactor').html(data.tbSubFactores);
	        	$('#tbSubFactores').bootstrapTable({});
			    tableEventsSubFactores();
			    $.each($("#tbFactores tbody>tr"), function() {
					$(this).css('background-color','white');
				});
		    	$("#tbFactores tr").filter(function() {
			        return $(this).data('index') == btn.closest('tr').data('index');
			    }).css('background-color','rgba(255,146,0,0.2)');
			} else {
			  mostrarNotificacion('error', data.msj, 'ERROR');
			}
	    });
	});
}

function moveRowFactor() {
	$(".up,.down").click(function() {
		var idFactor  = $(this).closest('tr').find('.btnID').data('id_factor');
        var orden  	  = $(this).attr('attr-orden');
        var direccion = $(this).attr('attr-direccion');
        moverOrdenFactor(orden, direccion, idFactor);
    });
}

function moverOrdenFactor(orden, direccion, idFactor) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {orden     : orden,
				     idFactor  : idFactor,
				     direccion : direccion },
			url   : 'c_rubrica/changeOrdenFactor',
			type  : 'POST'
	 	})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				mostrarNotificacion('error', data.msj);
			} else {
				$('#contTabRubricas').html(data.tbFactores);
				$('#tbFactores').bootstrapTable({ });
				tableEventsFactores();
				componentHandler.upgradeAllRegistered();
				var row = $("#tbFactores tbody>tr").filter(function() {
				    orden = parseInt(orden);
				    var idx = ((direccion == 1) ? orden - 1 : orden + 1) - 1;
				    return $(this).data('index') === idx;
				});//
				if(idFactorGlobal != null && idFactor == idFactorGlobal ) {
					row.css('background-color','rgba(255,146,0,0.2)');
				} else if(idFactorGlobal != null && row.find('.btnID').data('id_factor') != idFactorGlobal) {
					row.effect("highlight", {color : 'rgba(255,146,0,0.2)' }, 3000);
					$("#tbFactores tbody>tr").filter(function() {
					    return $(this).find('.btnID').data('id_factor') === idFactorGlobal;
					}).css('background-color','rgba(255,146,0,0.2)');
				} else if(idFactorGlobal == null) {
					row.effect("highlight", {color : 'rgba(255,146,0,0.2)' }, 3000);
				}
		   	}
		});
	});
}

function moveRowSubFactor() {
	$(".upSF,.downSF").click(function() {
		var idSubFactor = $(this).closest('tr').find('.btnIDCrit').data('idindpk');
		var idFactor    = $(this).closest('tr').find('.btnIDCrit').data('idcripk');
        var orden  	    = $(this).attr('attr-orden');
        var direccion   = $(this).attr('attr-direccion');
        moverOrdenSubFactor(orden, direccion, idFactor, idSubFactor);
    });
}

function moverOrdenSubFactor(orden, direccion, idFactor, idSubFactor) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {orden       : orden,
				     idFactor    : idFactor,
				     idSubFactor : idSubFactor,
				     direccion   : direccion },
			url   : 'c_rubrica/changeOrdenSubFactor',
			type  : 'POST'
	 	})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1) {
				mostrarNotificacion('error', data.msj);
			} else {
				$('#contTabSubFactor').html(data.tbSubFactores);
				var page_number = $.cookie('page_number_tbSubFactores');
				if( page_number == null ) {
					page_number = 1;
				}
				$('#tbSubFactores').bootstrapTable({ pageNumber : parseInt(page_number) });
				tableEventsSubFactores();
			    $('.fixed-table-toolbar').addClass('mdl-card__menu');
	    		arrayIndisAsig = [];
				componentHandler.upgradeAllRegistered();
				$("#tbSubFactores tr").filter(function() {
				    orden = parseInt(orden);
				    var idx = ((direccion == 1) ? orden - 1 : orden + 1) - 1;
				    return $(this).data('index') === idx;
				}).effect("highlight", {color : 'rgba(255,146,0,0.2)' }, 3000);
				//$('#tbFactoresAsignar tbody>tr:first').effect("highlight", {color : 'green' }, 3000);
		   	}
		});
	});
}

function reactivarRubrica($idRubrica) {
	if($idRubrica == null || $idRubrica == undefined) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_rubrica/reactivarRubrica",
			data: { idRubrica : $idRubrica},
	        type: 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success', data.msj);
				$('#contTabConsRubricas').html(data.tbFichas);
				$('#tbFichas').bootstrapTable({});
				componentHandler.upgradeAllRegistered();
			} else if(data.error == 1) {
			    mostrarNotificacion('error', data.msj, CONFIG.get('CABE_ERR'));
			}
		});
	});
}

var idFactorGlobal = null;

function tableEventsFactores() {
	moveRowFactor();
	$(function () {
	    $('#tbFactores').on('all.bs.table', function (e, name, args) {

	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    	$('.mdl-button').on('click',function(){
	    		fix($(this));
	    	});
	    })
	    .on('check.bs.table', function (e, row) {
	    })
	    .on('uncheck.bs.table', function (e, row) {
	    })
	    .on('check-all.bs.table', function (e) {
	    })
	    .on('uncheck-all.bs.table', function (e) {
	    })
	    .on('load-success.bs.table', function (e, data) {
	    })
	    .on('load-error.bs.table', function (e, status) {
	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsFichas() {
	$(function () {
	    $('#tbFichas').on('all.bs.table', function (e, name, args) {
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    	$('.mdl-button').on('click',function(){
	    		fix($(this));
	    	});
	    })
	    .on('check.bs.table', function (e, row) {
	    })
	    .on('uncheck.bs.table', function (e, row) {
	    })
	    .on('check-all.bs.table', function (e) {
	    })
	    .on('uncheck-all.bs.table', function (e) {
	    })
	    .on('load-success.bs.table', function (e, data) {
	    })
	    .on('load-error.bs.table', function (e, status) {
	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsIndicadoresPorAsignar() {
	$(function () {
	    $('#tbSFAsignar').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	$.cookie('page_number_tbSFAsignar', size);
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsFactoresPorAsignar() {
	$(function () {
	    $('#tbFactoresAsignar').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	$.cookie('page_number_tbFactoresAsignar', size);
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsSubFactores() {
	 moveRowSubFactor();
	$(function () {
	    $('#tbSubFactores').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	$.cookie('page_number_tbSubFactores', size);
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}